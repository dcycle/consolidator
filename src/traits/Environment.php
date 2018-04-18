<?php

namespace consolidator\traits;

/**
 * Wrapper around elements external to the application logic.
 */
trait Environment {

  public function average($array, $item_key) : float {
    $candidates = [];
    array_walk($array, function ($item, $key) use (&$candidates, $item_key) {
      if (isset($item[$item_key])) {
        $candidates[] = $item[$item_key];
      }
    });
    return array_sum($candidates) / count($candidates);
  }

  public function batchSet(array $batch_definition) {
    batch_set($batch_definition);
  }

  public function getJson(string $url, array $options = array()) : array {
    $http_result = drupal_http_request($url, $options);
    if (empty($http_result->code)) {
      throw new \Exception('Http request does not have a resulting code.');
    }
    if ($http_result->code != 200) {
      throw new \Exception('Http request code is not 200.');
    }
    return $this->jsonDecode($http_result->data);
  }

  public function jsonDecode($json) : array {
    $return = json_decode($json, TRUE);
    if (!is_array($return)) {
      throw new \Exception(json_last_error());
    }
    return $return;
  }

  public function parseJson(array $starwars, string $path, string $calculation) : int {
    return 3;
  }

  public function t(string $string, array $args = array(), array $options = array()) : string {
    return t($string, $args, $options);
  }

}
