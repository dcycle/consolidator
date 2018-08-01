<?php

namespace consolidator;

use consolidator\traits\Environment;
use consolidator\traits\Singleton;
use consolidator\Collection\ReportTypes;
use consolidator\ReportType\ReportType;

/**
 * Encapsulated code for the application.
 */
class App {

  use Environment;
  use Singleton;

  /**
   * Batch definition info.
   */
  public function batchDefinition(ReportType $report_type) : array {
    $batch = [
      'operations' => [
        [
          'consolidator_batch_operation',
          [
            $report_type,
          ],
        ],
      ],
      'finished' => 'consolidator_batch_finished',
      'title' => t('Processing @n', ['@n' => $report_type->name()]),
      'init_message' => t('@n is starting.', ['@n' => $report_type->name()]),
      'progress_message' => t('Processing...'),
      'error_message' => t('@n has encountered an error.', ['@n' => $report_type->name()]),
    ];
    return $batch;
  }

  /**
   * Batch operation info.
   */
  public function batchOperation(ReportType $object, &$context) {
    if (empty($context['sandbox'])) {
      $context['sandbox'] = [];
    }
    $object->setContext($context);
    $context = $object->nextStep();
  }

  /**
   * Called when a batch operation is finished.
   */
  public function batchFinished($success, $results, $operations) {
    $_SESSION['consolidated_reports_result'] = $results;
  }

  /**
   * Callback for the consolidator admin form.
   */
  public function form() : array {
    $form['description'] = array(
      '#type' => 'markup',
      '#markup' => t('Select which report you would like to generate.'),
    );
    $form['batch'] = array(
      '#type' => 'select',
      '#title' => 'Choose report',
      '#options' => $this->reportTypeList()->toOptions(),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Go',
    );
    $form['clear'] = array(
      '#type' => 'submit',
      '#value' => 'Clear all',
    );
    $form['result'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="report">' . $this->reportMarkup() . '</div>',
    );
    return $form;
  }

  /**
   * Submit handler for the consolidator admin form.
   */
  public function formSubmit($form, &$form_state) {
    if ($form_state['values']['op'] == 'Clear all') {
      unset($_SESSION['consolidated_reports_result']);
      drupal_set_message(t('Cleared session variables with old reports.'));
      return;
    }

    $report_class = $form_state['values']['batch'];
    $this->batchSet($this->batchDefinition(new $report_class()));
  }

  /**
   * Testable implementation of hook_menu().
   */
  public function hookMenu() : array {
    $items = array();
    $items['admin/reports/consolidator'] = array(
      'title' => 'Consolidated Reports',
      'description' => 'Consolidate reports from a variety of sources',
      'page callback' => 'drupal_get_form',
      'page arguments' => array('consolidator_form'),
      'access arguments' => ['generate-consolidator-reports'],
    );
    return $items;
  }

  /**
   * Testable implementation of hook_permission().
   */
  public function hookPermission() : array {
    return [
      'generate-consolidator-reports' => [
        'title' => $this->t('Generate Consolidator Reports'),
        'description' => $this->t('Allows the user to generate reports using the Consolidator module.'),
      ],
    ];
  }

  /**
   * Show a report from session memory if possible.
   *
   * @return string
   *   Markup of a report, or an empty string.
   */
  public function reportMarkup() : string {
    try {
      if (!empty($_SESSION['consolidated_reports_result'])) {
        $class = $_SESSION['consolidated_reports_result']['class'];
        $object = new $class();
        $title = $object->name();
        $generated = $_SESSION['consolidated_reports_result']['generated'];
        $markup = $object->displayReport($_SESSION['consolidated_reports_result']['report']);
        $return = '<h3>' . $title . '</h3>';
        $return .= '<h4>' . $generated . '</h4>';
        $return .= '<div class="report-content">' . $markup . '</div>';
        return $return;
      }
    }
    catch (\Throwable $t) {
      drupal_set_message($t->getMessage(), 'error');
    }
    return '';
  }

  /**
   * Get a list of all report types from all modules.
   *
   * @return ReportTypes
   *   A collection of report types.
   *
   * @throws Exception
   */
  public function reportTypeList() : ReportTypes {
    return new ReportTypes(module_invoke_all('consolidator_reports'));
  }

}
