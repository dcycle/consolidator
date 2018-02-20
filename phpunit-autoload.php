<?php

/**
 * @file
 * Class autoloader.
 */

spl_autoload_register(function ($class) {
  if (substr($class, 0, strlen('consolidator\\')) == 'consolidator\\') {
    $class = preg_replace('/^consolidator\\\\/', '', $class);
    $path = 'src/' . str_replace('\\', '/', $class);
    require_once $path . '.php';
  }
});
