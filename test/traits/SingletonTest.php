<?php

namespace consolidtor\Tests;

use consolidator\traits\Singleton;
use PHPUnit\Framework\TestCase;

/**
 * Dummy Singleton for testing.
 */
class SingletonObject {
  use Singleton;

}

/**
 * Test Singleton.
 *
 * @group consolidator
 */
class SingletonTest extends TestCase {

  /**
   * Test Singleton::instance().
   *
   * @covers ::instance().
   */
  public function testInstance() {
    $this->assertTrue(SingletonObject::instance() === SingletonObject::instance(), 'Singleton works correctly: instance is the same no matter how many times it is called.');
  }

}
