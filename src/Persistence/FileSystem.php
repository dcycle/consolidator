<?php

namespace consolidator\Persistence;

use consolidator\traits\Singleton;

/**
 * Wrapper around the file system.
 */
class FileSystem {

  use Singleton;

  /**
   * Delete everything, or a single file.
   *
   * @param string $file
   *   A file name; if omitted, we will delete everything.
   *
   * @throws \Exception
   */
  public function delete(string $file = '') {
    try {
      $fullpath = $this->diskLocation();
    }
    catch (\Exception $e) {
      // Cannot figure out what to delete, fail silently.
    }
    if (!$file) {
      $objects = scandir($fullpath);
      foreach ($objects as $object) {
        if ($object != '.' && $object != '..') {
          if (is_dir($fullpath . '/' . $object)) {
            rrmdir($fullpath . '/' . $object);
          }
          else {
            unlink($fullpath . '/' . $object);
          }
        }
      }
      rmdir($fullpath);
    }
    elseif (is_file($fullpath . '/' . $file)) {
      unlink($fullpath . '/' . $file);
    }
  }

  /**
   * Retrieve data saved with ::filePutContents().
   *
   * @param string $file
   *   A key provided by ::filePutContents().
   *
   * @return mixed
   *   The data.
   *
   * @throws \Exception
   */
  public function fileGetContents(string $file) {
    $full = $this->diskLocation() . '/' . $file;
    if (!is_file($full)) {
      throw new \Exception($full . ' does not exist.');
    }
    $data = file_get_contents($full);
    if ($data === FALSE) {
      throw new \Exception('Could not get contents of ' . $full);
    }
    return drupal_json_decode($data);
  }

  /**
   * Put contents on the disk and return a key with which to get them.
   *
   * @param mixed $data
   *   The data.
   *
   * @return string
   *   A key to be used with ::fileGetContents() later.
   *
   * @throws \Exception
   */
  public function filePutContents($data) : string {
    $file = random_int(PHP_INT_MIN, PHP_INT_MAX);
    $full = $this->diskLocation() . '/' . $file;
    if (file_put_contents($full, drupal_json_encode($data)) === FALSE) {
      throw new \Exception('Could not put contents to ' . $full);
    }
    return $file;
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
   * @throws \Exception
   */
  public function diskLocation() : string {
    $var = 'consolidator_disk_persistence';
    $return = variable_get($var);
    if (!$return) {
      throw new \Exception('Please set ' . $var . ' as a variable or in settings.php; it need to be in the format "/full/path/not/web/accessible".');
    }
    if (!is_dir($return)) {
      throw new \Exception($return . ' is not a directory.');
    }
    $return = $return . '/consolidator';
    if (!file_exists($return)) {
      if (mkdir($return, 0777, TRUE) === FALSE) {
        throw new \Exception('Could not make directory ' . $return);
      }
    }
    if (!is_dir($return)) {
      throw new \Exception($return . ' is not a directory.');
    }
    return $return;
  }

}
