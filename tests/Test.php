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

  public function testInheritedMethods() {
    $this->expectOutputString('SuccessC');

    $cron = Cron::fromDirectory(self::FIXTURE_DIRECTORY . '/testInheritedMethods/');
    $cron->execute();
  }

  public function testStaticMethods() {
    $this->expectOutputString('SuccessE');

    $cron = Cron::fromDirectory(self::FIXTURE_DIRECTORY . '/testStaticMethods/');
    $cron->execute();
  }

  public function testFailureBecauseConstructor() {
    $this->expectException(InvalidArgumentException::class);

    $cron = Cron::fromDirectory(self::FIXTURE_DIRECTORY . '/testFailureBecauseConstructor/');
    $cron->execute();
  }
}
