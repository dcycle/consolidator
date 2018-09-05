<?php

namespace consolidator\Collection;

/**
 * A wrapper around an array.
 */
abstract class Collection {

  /**
   * Constructor.
   *
   * @param array $array
   *   An array with which to construct the collection.
   *
   * @throws Exception
   */
  public function __construct(array $array) {
    $this->validateArray($array);
    $this->array = $array;
  }

  /**
   * Transform the collection into an array.
   *
   * @return array
   *   The collection as an array.
   *
   * @throws Exception
   */
  public function toArray() : array {
    $array = $this->array;
    $this->validateArray($array);
    return $array;
  }

  /**
   * Validate the array.
   *
   * @param array $array
   *   An internal array which should be validated.
   *
   * @throws Exception
   */
  abstract public function validateArray(array $array);

}
