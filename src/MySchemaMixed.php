<?php
  
  class MySchemaMixed {

    protected $required = false;
    protected $rule;
    protected $default;

    function getProps() {
      $props = [
        'required' => $this->required,
        'rule' => $this->rule,
        'default' => $this->default
      ];
      return $props;
    }

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
      if ($this->required && !isset($value)) {
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