<?php

namespace consolidator\ReportType;

use consolidator\traits\Environment;
use consolidator\Persistence\Persistence;
use consolidator\Persistence\SaveIsSession;

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

  /**
   * Name of this report type.
   *
   * @return string
   *   Name of this report type.
   */
  abstract public function name() : string;

  /**
   * Get information set while the report was being built, if possible.
   *
   * @param string $name
   *   Data key.
   *
   * @return mixed
   *   Data value.
   */
  public function get($name) {
    return empty($this->context['sandbox'][$name]) ? '' : $this->context['sandbox'][$name];
  }

  /**
   * Get the Drupal context of the batch.
   *
   * @return array
   *   The batch context.
   */
  public function getContext() : array {
    return $this->context;
  }

  /**
   * Retrieve info set using ::rememberForNext() in previous method call.
   *
   * @param string $key
   *   The data key.
   * @param mixed $default
   *   The default data value.
   *
   * @return mixed
   *   The data value.
   *
   * @throws Exception
   */
  public function fromLastCall($key, $default) {
    $intra_step = $this->getIntraStepInfo();
    $unprocessed = array_key_exists($key, $intra_step) ? $intra_step[$key] : $default;
    return $this->persistence()->decode($unprocessed);
  }

  /**
   * Retrieve intra-step info.
   *
   * @return array
   *   Arbitrary intra-step info.
   */
  public function getIntraStepInfo() : array {
    return $this->intra_step_info;
  }

  /**
   * Whether the step is done or not.
   *
   * @return bool
   *   Whether the step is done or not.
   */
  public function getStepDone() {
    return $this->step_done;
  }

  /**
   * Get a unique id for this report type.
   *
   * @return string
   *   Unique id for this report type.
   */
  public function id() : string {
    return get_class($this);
  }

  /**
   * Get information about which stage the batch is at.
   *
   * @return array
   *   The Drupal-structured context for the batch.
   */
  public function nextStep() {
    $all_steps = $this->operations();
    $context = $this->getContext();
    if (!empty($context['sandbox'])) {
      $context['sandbox'] = unserialize(gzuncompress($context['sandbox']));
    }

    foreach (array_keys($all_steps) as $step) {
      if (empty($context['sandbox']['_step_info'][$step]['done'])) {
        // Get the information from the last call.
        if (empty($context['sandbox']['_intra_step_info'][$step])) {
          $context['sandbox']['_intra_step_info'][$step] = [];
        }
        // Remember, internally, the information from the last call.
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

  /**
   * Get all operations, which are the internal steps plus the buildReport step.
   *
   * @return array
   *   An associative array where the keys are method names.
   */
  public function operations() {
    $steps = $this->steps();
    $steps['buildReport'] = [];

    return $steps;
  }

  /**
   * Function
   *
   * Description
   *
   * @param $
   *   What
   *
   * @return Persistence
   *
   * @throws Exception
   */


  /**
   * Within a step, get/set the progress message to show to the user.
   *
   * @param string $message
   *   The progress message, or empty if you just want to see the current
   *   message.
   *
   * @return string
   *   The progress message.
   */
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

  /**
   * Within a step, remember information for the next call of the step.
   *
   * @param string $key
   *   The data key.
   * @param mixed $value
   *   The data value.
   */
  public function rememberForNext($key, $value) {
    $intra_step = $this->getIntraStepInfo();
    $intra_step[$key] = $this->persistence()->encode($value);
    $this->setStepDone(FALSE, $intra_step);
  }

  /**
   * Keep track of the context array.
   *
   * @param array $context
   *   A context array contains the keys 'sandbox', etc.
   */
  public function setContext(array $context) {
    $this->context = $context;
  }

  /**
   * Called within a step to state whether a step is done or not.
   *
   * This should not be called by subclasses, it is used internally.
   *
   * @param bool $done
   *   Whether a step is done or not.
   * @param array $intra_step_info
   *   If a step is not done, what info should be kept for the next call.
   */
  public function setStepDone($done, array $intra_step_info = []) {
    $this->step_done = $done;
    $this->intra_step_info = $intra_step_info;
  }

  /**
   * Get method names for each step of the report.
   *
   * @return array
   *   An array where the keys are the method names to call for each step.
   */
  abstract public function steps() : array;

}
