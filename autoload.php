<?php

/**
 * @file
 * Class autoloader.
 */

spl_autoload_register(function ($class) {
  if (substr($class, 0, strlen('consolidator\\')) == 'consolidator\\') {
    $class = preg_replace('/^consolidator\\\\/', '', $class);
    $path = 'src/' . str_replace('\\', '/', $class);
    if (!module_load_include('php', 'consolidator', $path)) {
      throw new \Exception('Could not load ' . $path);
    }
  }
});
