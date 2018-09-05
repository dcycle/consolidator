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
    return FileSystem::instance()->fileGetContents($encoded);
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data) {
    return FileSystem::instance()->filePutContents($data);
  }

  /**
   * Get the persistent directory location on disk.
   *
   * If you're using Kubernetes or a highly-available cluster, please be
   * aware that /tmp may not be persistent across calls. This might be
   * the case on enterprise Acquia hosts, for example.
   *
   * @return string
   *   Something like '/path/to/directory'.
   *
   * @throws Exception
   */
  public function diskLocation(string $name) {
    $var = 'consolidator_disk_persistence';
    $return = variable_get($var) . '/consolidator';
    if (!file_exists($return)) {
      mkdir($return, 0777, true);
    }
    if (!is_dir($return)) {
      throw new \Exception($return . ' is not a directory.');
    }
    if (!$return) {
      throw new \Exception('Please set ' . $var . ' as a variable or in settings.php; it need to be in the format "/full/path/not/web/accessible".');
    }
    return $return;
  }

}
