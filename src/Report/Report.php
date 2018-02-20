<?php

namespace consolidator\Report;
use consolidator\traits\Environment;

/**
 * A report type.
 */
class Report {

  public function addInfo($name, $value) {
    $this->info[$name] = $value;
  }

}
