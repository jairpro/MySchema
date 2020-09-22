<?php

class MySchemaString extends MySchemaField {

  function email() {
    return $this->setRule("email");
  }

  function url() {
    return $this->setRule("url");
  }

  function isValid($value) {
    if (!parent::isValid($value)) {
      return false;
    }
    switch ($this->rule) {
      case 'email': 
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;

      case 'url': 
        return filter_var($value, FILTER_VALIDATE_URL) !== false;

      default: 
        return true; 
    }
  }
}
