<?php

namespace consolidator\Collection;

/**
 * A wrapper around an array
 */
abstract class Collection {

  public function __construct(array $array) {
    $this->validateArray($array);
    $this->array = $array;
  }

  public function toArray() : array {
    $array = $this->array;
    $this->validateArray($array);
    return $array;
  }

  abstract public function validateArray(array $array);

}
