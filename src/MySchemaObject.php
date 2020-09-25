<?php

class MySchemaObject extends MySchemaMixed {

  function getProps() {
    $props = [];
    if (is_array($this->schema)) {
      foreach ($this->schema as $index => $element) {
        $props[$index] = [];
        if (is_object($element) && method_exists($element, "getProps")) {
          $props[$index] = $element->getProps();
        }
      }
    }
    return $props;
  }

  function __construct($schema=null) {
    $this->schema = $schema;
  }

  function mixed($schema) {
    return new MySchemaMixed($schema);
  }
  
  function object($schema) {
    return new MySchemaObject($schema);
  }

  function string() {
    return new MySchemaString();
  }

  function number($message=null) {
    return new MySchemaNumber($message);
  }

  function date() {
    return new MySchemaDate();
  }

}
