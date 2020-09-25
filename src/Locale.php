<?php

class Locale {

  static protected $messages;

  static function init() { 
    self::$messages = [
      'mixed' => [
        'default' => '${path} is invalid',
        'required' => '${path} is a required field',
        'oneOf' => '${path} must be one of the following values: ${values}',
        'notOneOf' => '${path} must not be one of the following values: ${values}',
        'notType' => (function($options) {
          $path = isset($options['path']) ? $options['path'] : null;
          $type = isset($options['type']) ? $options['type'] : null;
          $value = isset($options['value']) ? $options['value'] : null;
          $originalValue = isset($options['originalValue']) ? $options['originalValue'] : null;
          
          $isCast = $originalValue != null && $originalValue !== $value;
          $msg =
            '${path} must be a ${type} type, ' .
            'but the final value was: ${printValue(value, true)}' .
            ($isCast
              ? ' (cast from the value ${printValue(originalValue, true)}).'
              : '.');
      
          if ($value === null) {
            $msg .= PHP_EOL .' If "null" is intended as an empty value be sure to mark the schema as ->nullable()';
          }
      
          return $msg;
        }),
        'defined' => '${path} must be defined',
      ],
      'object' => [
      //  'required' => '',
      ],
      'string' => [
        'length' => '${path} must be exactly ${length} characters',
        'min' => '${path} must be at least ${min} characters',
        'max' => '${path} must be at most ${max} characters',
        'matches' => '${path} must match the following"${regex}"',
        'email' => '${path} must be a valid email',
        'url' => '${path} must be a valid URL',
        'uuid' => '${path} must be a valid UUID',
        'trim' => '${path} must be a trimmed string',
        'lowercase' => '${path} must be a lowercase string',
        'uppercase' => '${path} must be a upper case string',
      ],
      'number' => [
        'number' => '${path} must be an number',
        'min' => '${path} must be greater than or equal to ${min}',
        'max' => '${path} must be less than or equal to ${max}',
        'lessThan' => '${path} must be less than ${less}',
        'moreThan' => '${path} must be greater than ${more}',
        'notEqual' => '${path} must be not equal to ${notEqual}',
        'positive' => '${path} must be a positive number',
        'negative' => '${path} must be a negative number',
        'integer' => '${path} must be an integer',
      ],
      'date' => [
      //  'positive' => '',
      //  'integer' => '',
      ],
      'boolean' => [
      //  'positive' => '',
      //  'integer' => '',
      ],
    ];
  }

  static function message($type, $param, $message=null) {
    if (!isset(self::$messages[$type][$param])) {
      if ($type==='mixed') {
        return false;
      }
      return self::message('mixed',$param, $message);
    }
    $result = self::$messages[$type][$param];
    if (isset($message)) {
      self::$messages[$type][$param] = $message;
    }
    return $result;
  }

  static function mixed($param, $message=null) {
    return self::message('mixed', $param, $message);
  }

  static function object($param, $message=null) {
    return self::message('object', $param, $message);
  }

  static function string($param, $message=null) {
    return self::message('string', $param, $message);
  }

  static function number($param, $message=null) {
    return self::message('number', $param, $message);
  }

  static function date($param, $message=null) {
    return self::message('date', $param, $message);
  }

  static function boolean($param, $message=null) {
    return self::message('bool', $param, $message);
  }
}
Locale::init();
