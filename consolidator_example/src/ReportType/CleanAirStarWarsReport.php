<?php

namespace consolidator_example\ReportType;

use consolidator\ReportType\ReportType;
use consolidator\Report\Report;

/**
 * A report.
 */
class CleanAirStarWarsReport extends ReportType {

  public function displayReport(array $report) : string {
    return serialize($report);
  }

  public function name() : string {
    return 'Star wars characters average height vs. average air quality measurements in cities';
  }

  public function steps() : array {
    return [
      'getStarwarsInfo' => [],
      'getCitiesInfo' => [],
    ];
  }

  public function getStarwarsInfo() {
    return $this->getJson('https://swapi.co/api/people/?format=json');
  }

  public function getCitiesInfo() {
    return $this->getJson('https://api.openaq.org/v1/cities?page=10');
  }

  public function buildReport() : array {
    $starwars = $this->get('getStarwarsInfo');
    $cities = $this->get('getCitiesInfo');

    $height = $this->parseJson($starwars, 'results/*/height', 'average');
    $locations = $this->parseJson($cities, 'results/*/locations', 'average');

    return [
      [
        'Average Star Wars character height',
        $height,
      ],
      [
        'Average Number of locations',
        $locations,
      ],
    ];
  }

}
