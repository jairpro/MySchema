<?php
  
  class MySchemaField {

    protected $required = false;
    protected $rule;
    protected $default;

    protected function setRule($rule) {
      $this->rule = $rule;
      return $this;
    }

    function required($required=true) {
      $this->required = $required==true;
      return $this;
    }

    function isRequired() {
      return $this->required;
    }

    function isValid($value) {
      if ($this->required && $value===null) {
        return false;
      }
      return true;
    }

    function default($value) {
      $this->default = $value;
      return $this;
    }

    function getDefault() {
      return $this->default;
    }
  }