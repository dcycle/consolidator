<?php

namespace consolidator\traits;

/**
 * Wrapper around elements external to the application logic.
 */
trait Environment {

  /**
   * Get an average of all keyed items in an array.
   *
   * @param array $array
   *   An array like [0 => ['whatever' => 4], 1 => ['whatever' => 5]].
   * @param string $item_key
   *   A key which each array item has, such as 'whatever' in the above
   *   example.
   *
   * @return float
   *   The average of the values identified by the key, 4.5 in the above
   *   example.
   */
  public function average($array, $item_key) : float {
    $candidates = [];
    // @codingStandardsIgnoreStart
    array_walk($array, function ($item, $key) use (&$candidates, $item_key) {
    // @codingStandardsIgnoreEnd
      if (isset($item[$item_key])) {
        $candidates[] = $item[$item_key];
      }
    });
    return array_sum($candidates) / count($candidates);
  }

  /**
   * Mockable wrapper around batch_set().
   */
  public function batchSet(array $batch_definition) {
    batch_set($batch_definition);
  }

  /**
   * Get Json from an URL.
   *
   * @param string $url
   *   The URL which has the Json.
   * @param array $options
   *   Options when getting the URL, passed to drupal_http_request().
   *
   * @return array
   *   The decoded Json.
   *
   * @throws Exception
   */
  public function getJson(string $url, array $options = array()) : array {
    $http_result = drupal_http_request($url, $options);
    if (empty($http_result->code)) {
      throw new \Exception('Http request does not have a resulting code.');
    }
    if ($http_result->code != 200) {
      throw new \Exception('Http result code is not 200, it is ' . $http_result->code . ' for ' . $url);
    }
    return $this->jsonDecode($http_result->data);
  }

  /**
   * Decode Json, throw an Exception on failure.
   *
   * @param string $json
   *   Valid json.
   *
   * @return array
   *   Decoded Json.
   *
   * @throws Exception
   */
  public function jsonDecode($json) : array {
    $return = json_decode($json, TRUE);
    if (!is_array($return)) {
      throw new \Exception(json_last_error());
    }
    return $return;
  }

  /**
   * Mockable wrapper around t().
   */
  public function t(string $string, array $args = array(), array $options = array()) : string {
    // @codingStandardsIgnoreStart
    return t($string, $args, $options);
    // @codingStandardsIgnoreEnd
  }

}
