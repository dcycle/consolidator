<?php

namespace consolidator\ReportType;
use consolidator\traits\Environment;
use consolidator\Report\Report;

/**
 * A report type.
 */
abstract class ReportType {

  use Environment;

  /**
   * Build a report based on the information gathered during the steps.
   *
   * @return array
   *   Report information which will be passed to ::displayReport().
   */
  abstract public function buildReport() : array;

  /**
   * Display markup for a report.
   *
   * @param array $report
   *   A report as generated from ::buildReport().
   *
   * @return string
   *   Markup.
   */
  abstract public function displayReport(array $report) : string;

  abstract public function name() : string;

  public function get($name) {
    return empty($this->context['sandbox'][$name]) ? '' : $this->context['sandbox'][$name];
  }

  public function getContext() : array {
    return $this->context;
  }

  public function fromLastCall($key, $default) {
    $intra_step = $this->getIntraStepInfo();
    return array_key_exists($key, $intra_step) ? $intra_step[$key] : $default;
  }

  public function getIntraStepInfo() : array {
    return $this->intra_step_info;
  }

  public function getStepDone() {
    return $this->step_done;
  }

  public function id() : string {
    return get_class($this);
  }

  public function nextStep() {
    $all_steps = $this->operations();
    $context = $this->getContext();
    if (!empty($context['sandbox'])) {
      $context['sandbox'] = unserialize(gzuncompress($context['sandbox']));
    }

    foreach (array_keys($all_steps) as $step) {
      if (empty($context['sandbox']['_step_info'][$step]['done'])) {
        if (empty($context['sandbox']['_intra_step_info'][$step])) {
          $context['sandbox']['_intra_step_info'][$step] = [];
        }
        $this->setStepDone(TRUE, $context['sandbox']['_intra_step_info'][$step]);
        $context['sandbox'][$step] = $this->{$step}();
        $context['sandbox']['_step_info'][$step]['done'] = $this->getStepDone();
        $context['sandbox']['_intra_step_info'][$step] = $this->getIntraStepInfo();
        $context['sandbox'] = gzcompress(serialize($context['sandbox']), 9);
        $this->setContext($context);
        $context['message'] = $this->progressMessage();
        $context['finished'] = FALSE;
        return $context;
      }
    }

    $context['results'] = [
      'class' => get_class($this),
      'report' => $context['sandbox']['buildReport'],
      'generated' => date('d F Y, H:i'),
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

  public function progressMessage(string $message = '') : string {
    if ($message) {
      $this->progressMessage = $message;
    }
    if (empty($this->progressMessage)) {
      return 'Processing...';
    }
    else {
      return $this->progressMessage;
    }
  }

  public function rememberForNext($key, $value) {
    $intra_step = $this->getIntraStepInfo();
    $intra_step[$key] = $value;
    $this->setStepDone(FALSE, $intra_step);
  }

  public function setContext(array $context) {
    $this->context = $context;
  }

  public function setStepDone($done, array $intra_step_info = []) {
    $this->step_done = $done;
    $this->intra_step_info = $intra_step_info;
  }

  abstract public function steps() : array;

}
