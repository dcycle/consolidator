<?php

namespace consolidator\ReportType;
use consolidator\traits\Environment;
use consolidator\Report\Report;

/**
 * A report type.
 */
abstract class ReportType {

  use Environment;

  abstract public function buildReport() : array;

  abstract public function displayReport(array $report) : string;

  abstract public function name() : string;

  public function get($name) {
    return empty($this->context['sandbox'][$name]) ? '' : $this->context['sandbox'][$name];
  }

  public function getContext() : array {
    return $this->context;
  }

  public function id() : string {
    return get_class($this);
  }

  public function nextStep() {
    $all_steps = $this->operations();
    $context = $this->getContext();

    foreach (array_keys($all_steps) as $step) {
      if (!array_key_exists($step, $context['sandbox'])) {
        $context['sandbox'][$step] = $this->{$step}();
        $this->setContext($context);
        $context['finished'] = FALSE;
        return $context;
      }
    }

    $context['results'] = [
      'class' => get_class($this),
      'report' => $context['sandbox']['buildReport'],
      'generated' => date('d m Y, h:i'),
    ];
    $this->setContext($context);
    $context['finished'] = TRUE;
    return $context;
  }

  public function operations() {
    $steps = $this->steps();
    $steps['buildReport'] = [];

    return $steps;
  }

  public function setContext(array $context) {
    $this->context = $context;
  }

  abstract public function steps() : array;

}
