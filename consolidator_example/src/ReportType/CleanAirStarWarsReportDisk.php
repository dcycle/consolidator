<?php

namespace consolidator_example\ReportType;

use consolidator\Persistence\Persistence;
use consolidator\Persistence\SaveOnDisk;
use consolidator\ReportType\ReportType;

/**
 * Similar to CleanAirStarWarsReport but stores intra-step info on disk.
 */
class CleanAirStarWarsReportDisk extends CleanAirStarWarsReport {

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return parent::name() . ' (saves to disk, not session)';
  }

  /**
   * {@inheritdoc}
   */
  public function persistence() : Persistence {
    return new SaveOnDisk($this->name());
  }

}
