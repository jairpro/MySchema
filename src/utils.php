<?php

function isAssoc($arr) {
  if (!is_array($arr) || empty($arr)) return null;
  if (array() === $arr) return false;
  return array_keys($arr) !== range(0, count($arr) - 1);
}