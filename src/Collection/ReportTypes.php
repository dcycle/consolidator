<?php

namespace consolidator\Collection;

use consolidator\ReportType\ReportType;

/**
 * A wrapper around an array of report types.
 */
class ReportTypes extends Collection {

  /**
   * {@inheritdoc}
   */
  public function validateArray(array $array) {
    foreach ($array as $item) {
      if (!($item instanceof ReportType)) {
        throw new \Exception('Item is not a ' . ReportType::class);
      }
    }
  }

  /**
   * Get the report types collection as an option array.
   *
   * @return array
   *   An array ready for display as an option list.
   *
   * @throws Exception
   */
  public function toOptions() : array {
    $return = [];
    $array = $this->toArray();
    // @codingStandardsIgnoreStart
    array_walk($array, function ($item, $key) use (&$return) {
    // @codingStandardsIgnoreEnd
      $return[$item->id()] = $item->name();
    });
    return $return;
  }

}
