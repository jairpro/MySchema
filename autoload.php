<?php
  require_once dirname(__FILE__)."/src/MySchema.php";

  spl_autoload_register('mySchemaAutoload');

  function mySchemaAutoload($classname) {
    $dir = dirname(__FILE__)."/";

    $php = $dir . "/src/$classname.php";
    if (file_exists($php)) {
      require_once $php;
      return true;
    }

    return false;
  }
