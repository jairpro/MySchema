<?php

class MySchema {

  function __constructor() {
    return $this;
  }

  static function object($schema) {
    return new MySchemaObject($schema);
  }

  static function string() {
    return new MySchemaString();
  }

  static function number() {
    return new MySchemaNumber();
  }

  static function date() {
    return new MySchemaDate();
  }
}

function MySchema($schema=null) {
  return new MySchemaObject($schema); 
}

function MySchemaObject($schema) {
  return new MySchemaObject($schema);
}

function MySchemaString() {
  return new MySchemaString();
}

function MySchemaNumber() {
  return new MySchemaNumber();
}

function MySchemaDate() {
  return new MySchemaDate();
}

