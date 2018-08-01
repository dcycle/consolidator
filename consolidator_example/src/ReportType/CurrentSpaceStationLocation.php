<?php

namespace consolidator_example\ReportType;

use consolidator\ReportType\ReportType;

/**
 * A sample report to copy-paste and build your own reports.
 */
class CurrentSpaceStationLocation extends ReportType {

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
    return '
    <p>Latitude: ' . $report['lat'] . ', longitude: ' . $report['lon'] . '</p><script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
function initialize() {
var map_canvas = document.getElementById("map_canvas");
var map_options = {
center: new google.maps.LatLng(' . $report['lat'] . ', ' . $report['lon'] . '),
zoom:5,
mapTypeId: google.maps.MapTypeId.ROADMAP
}
var map = new google.maps.Map(map_canvas, map_options)
var marker = new google.maps.Marker({
  position: {lat: ' . $report['lat'] . ', lng: ' . $report['lon'] . '},
  map: map,
  title: "Position of the international space station"
});
}
google.maps.event.addDomListener(window, "load", initialize);
</script>
</head>
<body>
<div id="map_canvas" style="height:500px; width: 500px;"></div>';
  }

  /**
   * {@inheritdoc}
   */
  public function name() : string {
    return 'Current location of the international space station';
  }

  /**
   * {@inheritdoc}
   */
  public function steps() : array {
    return [
      'getSpaceStationInfo' => [],
    ];
  }

  /**
   * Step 1, get Space station info.
   */
  public function getSpaceStationInfo() {
    return $this->getJson('http://api.open-notify.org/iss-now.json');
  }

  /**
   * Final step, build a report.
   */
  public function buildReport() : array {
    $info = $this->get('getSpaceStationInfo');

    return [
      'lat' => $info['iss_position']['latitude'],
      'lon' => $info['iss_position']['longitude'],
    ];
  }

}
