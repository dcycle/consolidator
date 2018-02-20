<?php

namespace consolidator\Collection;

use consolidator\ReportType\ReportType;

/**
 * A wrapper around an array of report types.
 */
class ReportTypes extends Collection {

  public function validateArray(array $array) {
    foreach ($array as $item) {
      if (!($item instanceof ReportType)) {
        throw new \Exception('Item is not a ' . ReportType::class);
      }
    }
  }

  public function toOptions() : array {
    $return = [];
    $array = $this->toArray();
    array_walk($array, function ($item, $key) use (&$return) {
      $return[$item->id()] = $item->name();
    });
    return $return;
  }

}
