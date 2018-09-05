<?php

namespace consolidtor\Tests;

use PHPUnit\Framework\TestCase;
use consolidator\App;

/**
 * Test App.
 *
 * @group consolidator
 */
class AppTest extends TestCase {

  /**
   * Test for hookPermission().
   *
   * @cover ::hookPermission
   */
  public function testHookPermission() {
    $object = $this->getMockBuilder(App::class)
      ->setMethods([
        't',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('t')
      ->willReturn('some translated string');

    $permissions = $object->hookPermission();

    $this->assertTrue(count($permissions) > 0);
    foreach ($permissions as $key => $permission) {
      $this->assertFalse(empty($permission['title']), 'title exists');
      $this->assertFalse(empty($permission['description']), 'description exists');
      $this->assertTrue(is_string($key), 'key is string');
    }
  }

}
