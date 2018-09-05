<?php

namespace consolidator\Persistence;

/**
 * A persistence engine.
 */
abstract class Persistence {

  /**
   * Constructor.
   *
   * @param string $id
   *   Id for this persistence, such as 'report-a-batch-12345'.
   */
  public function __construct(string $id) {
    $this->id = $id;
  }

  /**
   * Decode data from the session.
   *
   * @param mixed $encoded
   *   Encode data such as 'data_on_file-12345'.
   *
   * @return mixed
   *   Decoded data such as the contents of /tmp/12345.txt
   *
   * @throws Exception
   */
  abstract public function decode($encoded);

  /**
   * Encode data for placment in the session.
   *
   * @param mixed $data
   *   Data such as 'this is really too long to store in the session'.
   *
   * @return mixed
   *   Encoded data such as 'data_on_file-12345'.
   *
   * @throws Exception
   */
  abstract public function encode($data);

}
