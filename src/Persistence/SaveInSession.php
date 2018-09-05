<?php

namespace consolidator\Persistence;

/**
 * A persistence engine to save in the session.
 */
class SaveInSession extends Persistence {

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

}
