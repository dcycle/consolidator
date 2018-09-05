<?php

namespace consolidator_example\ReportType;

use consolidator\Persistence\Persistence;
use consolidator\Persistence\SaveOnDisk;

/**
 * Similar to CleanAirStarWarsReport but stores intra-step info on disk.
 */
class CleanAirStarWarsReportDisk extends CleanAirStarWarsReport {

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return parent::name() . ' (saves internal data to disk, not session)';
  }

  /**
   * {@inheritdoc}
   */
  public function persistence() : Persistence {
    return new SaveOnDisk($this->name());
  }

}
