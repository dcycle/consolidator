<?php

/**
 * @file
 * Class autoloader.
 */

spl_autoload_register(function ($class) {
  if (substr($class, 0, strlen('consolidator_example\\')) == 'consolidator_example\\') {
    $class = preg_replace('/^consolidator_example\\\\/', '', $class);
    $path = 'src/' . str_replace('\\', '/', $class);
    if (!module_load_include('php', 'consolidator_example', $path)) {
      throw new \Exception('Could not load ' . $path);
    }
  }
});
