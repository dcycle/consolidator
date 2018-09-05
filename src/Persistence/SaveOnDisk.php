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

}
