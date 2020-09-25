<?php
  
  class MySchemaMixed {
    protected $schema;
    protected $default;
    protected $validations = [];
    protected $error;
    protected $errors;

    function getProps() {
      $props = [
        'type' => $this->getTypeName()
      ];
      if ($this->isRequired()) {
        $props['required'] = true;
      }
      if (isset($this->validations['rule']['params']['rule'])) {
        $props['rule'] = $this->validations['rule']['params']['rule'];
      }
      if (isset($this->default)) {
        $props['default'] = $this->default;
      }
      if (isset($this->validations['oneOf']['params'])) {
        $props['oneOf'] = $this->validations['oneOf']['params'];
      }
      if (isset($this->validations['notOneOf']['params'])) {
        $props['notOneOf'] = $this->validations['notOneOf']['params'];
      }
      return $props;
    }

    function __construct($schema=null) {
      $this->schema = $schema;
    }

    function oneOf($arrayOfValues, $message=null) {
      if (is_array($arrayOfValues)) {
        $this->test([
          'name' => 'oneOf',
          'message' => $message,
          'params' => $arrayOfValues,
          'test' => function ($value, $arrayOfValues) {
              return in_array($value, $arrayOfValues);
            }
        ]);
      }
      return $this;
    }
  
    function notOneOf($arrayOfValues, $message=null) {
      if (is_array($arrayOfValues)) {
        $this->test([
          'name' => 'notOneOf',
          'message' => $message,
          'params' => $arrayOfValues,
          'test' => function ($value, $arrayOfValues) {
              return !in_array($value, $arrayOfValues);
            }
        ]);
      }
      return $this;
    }
  
    function getTypeName() {
      return substr(strtolower(get_class($this)),strlen("MySchema"));
    }

    function required($message=null) {
      $this->test('required',
        $message,
        function($value) {
          return isset($value);
        }
      );
      return $this;
    }

    function isRequired() {
      return isset($this->validations['required']);
    }

    function test($arg1, $arg2=null, $arg3=null) {
      if (is_array($arg1)) {
        $options = $arg1;
        $name = isset($options['name']) ? $options['name'] : "";
        $test = isset($options['test']) ? $options['test'] : false;
        $message = isset($options['message']) ? $options['message'] : Locale::message($this->getTypeName(), $name);
        $params = isset($options['params']) ? $options['params'] : null;
        $exclusive = isset($options['exclusive']) ? $options['exclusive'] : false;
      }
      else {
        $name = is_string($arg1) ? $arg1 : null;
        $message = isset($arg2) && !is_callable($arg2) ? $arg2 : Locale::message($this->getTypeName(), $name);
        $test = is_callable($arg2) ? $arg2 : $arg3;
      }
      if ($name && $test && $message) {
        $validation = [
          'test' => $test,
          'message' => $message
        ];

        if (isset($params)) {
          $validation['params'] = $params;
        }
        if (isset($exclusive)) {
          $validation['exclusive'] = $exclusive;
        }

        $this->validations[$name] = $validation;
      }
      return $this;
    }

    protected function _validate($schema, $value, $options) {
      extract($options);
      if (is_array($schema->validations)) {
        foreach ($schema->validations as $name => $validation) {
          if (!isset($validation['test']) || !is_callable($validation['test'])) {
            continue;
          }

          $callable = $validation['test'];

          $refFunc = new ReflectionFunction($callable);
          $refParams = $refFunc->getParameters();

          $validParams = isset($validation['params']) ? $validation['params'] : [];
          $callParams = [];
          $fillWithNulls = false;

          require_once dirname(__FILE__).'./utils.php';
          $validParamsIsAssoc = isAssoc($validParams);
          
          foreach ($refParams as $refParamIndex => $refParam) {
            $refParamName = $refParam->getName();
            if ($refParamName==='value') {
              $callParams[] = $value;
            }
            else {
              if ($fillWithNulls) {
                $callParams[] = null;
              }
              else if (!is_array($validParams) || !$validParamsIsAssoc) {
                $callParams[] = $validParams;
                $fillWithNulls = true;
              }
              else {
                $validParamKey = $validParamsIsAssoc ? $refParamName : $refParamIndex;
                $callParams[] = $validParams[$validParamKey] ? $validParams[$validParamKey] : null;
              }
            }
          }

          $result = call_user_func_array($callable, $callParams);
          if (!$result) {
            $this->valid = false;
            if (!$strict) {
              $error = [
                'name' => $name,
                'message' => $validation['message']
              ];
              $this->error = $error;
              $this->errors[] = $error;
            }
            if ($strict || $abortEarly) {
              break;
            }
          }
        }
      }
    }

    protected static function resolveValidateOptions($options=null) {
      $parse = isset($options) ? $options : [];

      $parse['strict'] = isset($options['strict']) ? $options['strict'] : false;
      $parse['abortEarly'] = isset($options['abortEarly']) ? $options['abortEarly'] : true;
      $parse['stripUnknown'] = isset($options['stripUnknown']) ? $options['stripUnknown'] : false;
      $parse['recursive'] = isset($options['recursive']) ? $options['recursive'] : true;
      $parse['context'] = isset($options['context']) ? $options['context'] : null;
      
      return $parse;      
    }

    function validate($value, $options=null, $then=null, $catch=null) {
      $options = self::resolveValidateOptions($options);
      extract($options);

      $this->valid = true;
      if (!$strict) {
        $this->error = null;
        $this->errors = [];
      }

      $values = [$value];
      if (is_array($value)) {
        $values = $value;
      }
      
      require_once dirname(__FILE__)."./utils.php";
      $valuesIsAssoc = isAssoc($values);
      foreach ($values as $valueIndex => $valueElement) {
        if (is_array($this->schema)) {
          if ($valuesIsAssoc && in_array($valueIndex, array_keys($this->schema))) {
            $this->_validate($this->schema[$valueIndex], $valueElement, $options);
          }
          else {
            foreach ($this->schema as $schemaElement) {
              $this->_validate($schemaElement, $valueElement, $options);
            }
          }
        }
        $this->_validate($this, $valueElement, $options);
      }

      $valid = $this->valid;

      if (!$strict) {
        if ($valid && $then) {
          $then($value);
        }
        else if (!$valid && $catch) {
          $catch($abortEarly ? $this->error : $this->errors);
        }
      }

      return $valid;
    }

    function isValid($value, $options=null, $then=null, $catch=null) {
      return $this->validate($value, $options, $then, $catch);
    }

    function cast($value, $options=null, $then=null, $catch=null) {
      return $this->validate($value, $options, $then, $catch);
    }

    /*
    function isValid($schema, $options=null) {
      $options = isset($options) ? $options : [];
      $cleanMessages = isset($options['cleanMessages']) ? $options['cleanMessages'] : true;
      if ($cleanMessages) {
        $this->cleanMessages();
        $options['cleanMessages'] = false;
      }
  
      if (!is_array($this->schema) || count($this->schema)===0) {
        $then = function($value) {

        };
        $catch = function($error) {

        };
        return $this->_isValid($this->schema, $options, $then, $catch);
        //return false;
      }
  
      if (!is_array($schema)) {
        $then = function($value) {

        };
        $catch = function($error) {

        };
        return $this->_isValid($schema, $options, $then, $catch);
        //return false;
      }
  
      foreach ($schema as $key=>$value) {
        if (!array_key_exists($key, $this->schema)) {
          return false;
        }
      }
  
      foreach ($this->schema as $key=>$field) {
        $exists = array_key_exists($key, $schema);
        if (method_exists($field, 'isRequired') && $field->isRequired() 
          && !$exists
        ) {
          return false;
        }
        if (!$exists) {
          continue;
        }
  
        $value = $schema[$key];
  
        if (!method_exists($field, 'isValid')) {
          return false;
        }
  
        if (!$field->isValid($value, $options)) {
          return false;
        }
      }
  
      return true;
    }
    */

    /*
    protected function _validate($value, $options=null, $then=null, $catch=null) {
      $this->errorName = null;
      $this->errors = [];

      $options = isset($options) ? $options : [];
      $isValid = $this->isValid($value, $options);
      if ($isValid) {
        if ($then) {
          $then($value);
        }
        return true;
      }
      else {
        if ($catch) {
          $error = [
            'name' => $this->errorName,
            'errors' => $this->errors
          ];
          $catch($error);
        }
        return false;
      }
    }
    */
    /*
    function validate($data, $options=null) {
      if (!is_array($this->schema)) {
        $this->_validate($data, $options);
      }
      else {
        foreach ($this->schema as $index => $element) {
          $element->validate($data, $options);
        }
      }
      
      //$options = isset($options) ? $options : [];
      //$cleanMessages = isset($options['cleanMessages']) ? $options['cleanMessages'] : true;
      //if ($cleanMessages) {
      //  $this->cleanMessages();
      //  $options['cleanMessages'] = false;
      //}
      //$abortEarly = isset($options['abortEarly']) ? $options['abortEarly'] : true;
      //return !$abortEarly || $this->isValid($data, $options) ? $this->cast($data, $options) : false;
      
    }*/

    /*
    function cast($data, $options=null) {
      $abortEarly = isset($options['abortEarly']) ? $options['abortEarly'] : true;
      
      if (!is_array($this->schema)) {
        return false;
      }
      
      if (!is_array($data)) {
        return false;
      }
      
      $result = array();
  
      foreach($this->schema as $key => $field) {
        $getValue = isset($data[$key]) ? $data[$key] : null;
        if (isset($getValue)) {
          $setValue = $getValue;
        }
        else if (method_exists($field, 'getDefault')) {
          $getDefault = $field->getDefault();
          $setValue = $getDefault;
          if (is_callable($getDefault)) {
            $setValue = call_user_func($getDefault);
          }
        }
        $resultValue = $setValue;
        $class = is_object($field) ? get_class($field) : ""; 
        switch ($class) {
          case 'MySchemaNumber':
            $resultValue = isset($setValue) ? $setValue+0 : $setValue;
          break;
  
          case 'MySchemaDate':
            $resultValue = !isset($setValue) || is_object($setValue) ? $setValue : new DateTime($setValue);
          break;
        }
        if ((!method_exists($field, 'IsRequired') || !$field->isRequired()) && !isset($resultValue)) {
          $result[$key] = null;
        }
        else if (method_exists($field, 'IsValid') && $field->isValid($resultValue)) {
          $result[$key] = $resultValue;
        }
        else if (!$abortEarly) {
          $result[$key] = false;
        }
      }
  
      return $result;    
    }
    */

    /*
    function getMessages() {
      if (!is_array($this->schema)) {
        return $this->messages;
      }
      $messages = [];
      foreach ($this->schema as $index => $element) {
        $messages[$index] = $element->getMessages();
      }
      return $messages;
    }
  
    function cleanMessages() {
      if (!is_array($this->schema)) {
        $this->messages = [];
      }
      else {
        foreach ($this->schema as $index => $element) {
          $element->cleanMessages();
        }
      }
    }
    */

    function default($value) {
      $this->default = $value;
      return $this;
    }

    function getDefault() {
      return $this->default;
    }
  }