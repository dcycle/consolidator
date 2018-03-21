<?php

namespace consolidator_example\ReportType;

use consolidator\ReportType\ReportType;
use consolidator\Report\Report;

/**
 * A sample report to copy-paste and build your own reports.
 */
class WorkplaceGroupsUsers extends ReportType {

  /**
   * {@inheritdoc}
   */
  public function displayReport(array $report) : string {
    $return = '<ul>';
    foreach ($report as $item) {
      $return .= '<li>' . $item['title'] . ':<strong> ' . $item['value'] . '</strong></li>';
    }
    return $return . '</ul>';
  }

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return 'Star wars characters average height vs. average air quality measurements in cities';
  }

  /**
   * {@inheritdoc}
   */
  public function steps() : array {
    return [
      'getStarwarsInfo' => [],
      'getCitiesInfo' => [],
    ];
  }

  /**
   * Step 1, get Star Wars character info.
   */
  public function getStarwarsInfo() {
    return $this->getJson('https://swapi.co/api/people/?format=json');
  }

  /**
   * Step 2, get Cities air quality info.
   */
  public function getCitiesInfo($info) {
    $cities = $this->getJson('https://api.openaq.org/v1/cities?page=10');
    if (count($cities['results'])) {
      $this->setStepDone(FALSE);

    }
  }

  /**
   * Final step, build a report.
   */
  public function buildReport() : array {
    $starwars = $this->get('getStarwarsInfo');
    $cities = $this->get('getCitiesInfo');

    $height = $this->average($starwars['results'], 'height');
    $readings = $this->average($cities['results'], 'count');

    return [
      [
        'title' => 'Average Star Wars character height in cm',
        'value' => $height,
      ],
      [
        'title' => 'Average Number of air quality readings',
        'value' => $readings,
      ],
    ];
  }

}
