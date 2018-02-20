<?php

/**
 * @file
 * Test Environment.
 */

use consolidator\traits\Environment;
use PHPUnit\Framework\TestCase;

/**
 * Dummy object using Environment for testing.
 */
class EnvironmentObject {
  use Environment;

}

/**
 * Test Environment.
 *
 * @group consolidator
 */
class EnvironmentTest extends TestCase {

  /**
   * Smoke test for the environment trait.
   *
   * We cannot test the methods because they are wrappers for environment
   * functions. However, just making sure creating an object of the class
   * which uses Environment will confirm that it does not break anything.
   */
  public function testSmokeTest() {
    $this->assertTrue(is_object(new EnvironmentObject()));
  }

}
