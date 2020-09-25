<?php

class MySchemaNumber extends MySchemaMixed {

  function getProps() {
    $props = parent::getProps();
    if (isset($this->validations['positive'])) {
      $props['positive'] = true;
    }
    if (isset($this->validations['negative'])) {
      $props['negative'] = true;
    }
    if (isset($this->validations['integer'])) {
      $props['integer'] = true;
    }
    return $props;
  }

  function __construct($message=null) {
    $this->test([
      'name' => 'number',
      'message' => $message,
      'params' => $this,
      'test' => function ($value, $thisInstance) {
        return !isset($thisInstance->validations['required']) 
          && (!isset($value) || $value==='') 
          || is_numeric($value);
      }
    ]);
  }

  function positive($message=null) {
    $this->test(
      'positive',
      $message,
      function ($value) {
        return $value+0>0;
      }
    );
    return $this;
  }

  function negative($message=null) {
    $this->test(
      'negative',
      $message,
      function ($value) {
        return $value+0<0;
      }
    );
    return $this;
  }

  function integer($message=null) {
    $this->test(
      'integer',
      $message,
      function($value) {
        return is_int($value+0);
      }
    );
    return $this;
  }
}
