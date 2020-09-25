<?php

class MySchemaString extends MySchemaMixed {

  function getProps() {
    $props = parent::getProps();
    if (isset($this->validations['min'])) {
      $props['min'] = $this->validations['min']['params'];
    }
    if (isset($this->validations['max'])) {
      $props['max'] = $this->validations['max']['params'];
    }
    return $props;
  }

  function required($message=null) {
    $this->test('required',
      $message,
      function ($value) {
        return MySchemaMixed()->required()->isValid($value, ['strict'=>true]) && $value!=='';
      });
    return $this;
  }

  function min($limit, $message=null) {
    if (is_int($limit)) {
      $this->test([
        'name' => 'min',
        'message' => $message,
        'params' => $limit,
        'test' => function($value, $limit) {
          return strlen($value)>=$limit;
        }
      ]);
    }
    return $this;
  }

  function max($limit, $message=null) {
    if (is_int($limit)) {
      $this->test([
        'name' => 'max',
        'message' => $message,
        'params' => $limit,
        'test' => function($value, $limit) {
          return strlen($value)<=$limit;
        }
      ]);
    }
    return $this;
  }

  function email($message=null) {
    $this->test([
      'name' => 'rule',
      'params' => [
        'thisInstance' => $this,
        'rule' => 'email'
      ],
      'message' => isset($message) ? $message : Locale::string('email'),
      function($value, $thisInstance) {
        return !$thisInstance->isRequired() && $value!=='' && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
      }
    ]);
    return $this;
  }

  function url($message=null) {
    $this->test([
      'name' => 'rule',
      'params' => [
        'thisInstance' => $this,
        'rule' => 'url',
      ],
      'message' => isset($message) ? $message : Locale::string('url'),
      function($value, $thisInstance) {
        return !$thisInstance->isRequired() && $value!=='' && filter_var($value, FILTER_VALIDATE_URL) !== false;
      }
    ]);
    return $this;
  }
}
