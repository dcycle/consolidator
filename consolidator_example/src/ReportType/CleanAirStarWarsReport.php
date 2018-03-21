<?php

namespace consolidator_example\ReportType;

use consolidator\ReportType\ReportType;
use consolidator\Report\Report;

/**
 * A sample report to copy-paste and build your own reports.
 */
class CleanAirStarWarsReport extends ReportType {

  /**
   * Display markup for a report.
   *
   * @param array $report
   *   A report as generated from ::buildReport().
   *
   * @return string
   *   Markup.
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
    $next_page = $this->fromLastCall('next-page', 1);
    $existing_results = $this->fromLastCall('existing', []);

    $cities = $this->getJson('https://api.openaq.org/v1/cities?page=' . $next_page);
    if (count($cities['results'])) {
      $this->setStepDone(FALSE, [
        'next-page' => ++$next_page,
        'existing' => array_merge($existing_results, $cities['results']),
      ]);
    }
    else {
      $cities['results'] = array_merge($cities['results'], $existing_results);
      return $cities;
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
