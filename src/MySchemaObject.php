<?php

class MySchemaObject {

  protected $schema;

  function __construct($schema=null) {
    $this->schema = $schema;
  }

  function object($schema) {
    return new MySchemaObject($schema);
  }

  function string() {
    return new MySchemaString();
  }

  function number() {
    return new MySchemaNumber();
  }

  function date() {
    return new MySchemaDate();
  }

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

  function isValid($schema, $options=null) {
    if (!is_array($this->schema) || count($this->schema)===0) {
      return false;
    }

    if (!is_array($schema)) {
      return false;
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

      if (!$field->isValid($value)) {
        return false;
      }
    }

    return true;
  }

  function validate($data, $options=null) {
    $abortEarly = isset($options['abortEarly']) ? $options['abortEarly'] : true;
    return !$abortEarly || $this->isValid($data) ? $this->cast($data, $options) : false;
  }
}
