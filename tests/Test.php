<?php

use PHPUnit\Framework\TestCase;
use imnotjames\Cronic\Cron;

class CronTest extends TestCase {
  const FIXTURE_DIRECTORY = __DIR__ . '/fixtures';

  public function testFindExampleInDirectoryAndRun() {
      $this->expectOutputString('Success');

      $cron = Cron::fromDirectory(self::FIXTURE_DIRECTORY . '/testFindExampleInDirectoryAndRun/');
      $cron->execute();
  }

  public function testWeirdAnnotations() {
    $this->expectOutputString('SuccessASuccessB');

    $cron = Cron::fromDirectory(self::FIXTURE_DIRECTORY . '/testWeirdAnnotations/');
    $cron->execute();
  }
}
