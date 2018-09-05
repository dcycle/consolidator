<?php

namespace consolidator\Persistence;

/**
 * A persistence engine to save on disk.
 */
class SaveOnDisk extends Persistence {

  /**
   * {@inheritdoc}
   */
  public function decode($encoded) {
    return $encoded;
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data) {
    return $data;
  }

  /**
   * Get the persistent directory location on disk.
   *
   * If you're using Kubernetes or a highly-available cluster, please be
   * aware that /tmp may not be persistent across calls.
   *
   * @return string
   *   Something like '/path/to/directory'.
   *
   * @throws Exception
   */
  public function diskLocation(string $name) {
    $var = 'consolidator_disk_persistence';
    $return = variable_get($var);
    if (!$return) {
      throw new \Exception('Please set ' . $var . ' as a variable or in settings.php.');
    }
    return $return;
  }

}
