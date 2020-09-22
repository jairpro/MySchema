<?php

class MySchemaNumber extends MySchemaMixed {

  protected $positive = null;
  protected $integer = false;

  function getProps() {
    $props = array_merge(parent::getProps(), [
      'positive' => $this->required,
      'integer' => $this->rule,
    ]);
    return $props;
  }

function positive($positive=true) {
    $this->positive = $positive==true;
    return $this;
  }

  function integer($integer=true) {
    $this->integer = $integer==true;
    return $this;
  }

  function isValid($value) {
    if (!parent::isValid($value)) {
      return false;
    }
    if (!is_numeric($value)) {
      return false;
    }
    $number = $value + 0;
    if ($this->positive===true && $number<0) {
      return false;
    }
    if ($this->positive===false && $number>=0) {
      return false;
    }
    if ($this->integer===true && !is_int($number)) {
      return false;
    }
    return true;
  }
}
