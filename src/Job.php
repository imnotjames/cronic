<?php

namespace imnotjames\Cronic;

use Cron\CronExpression;

class Job {
  private $callable;

  private $cronExpressions = [];

  public function __construct(array $cronValues, callable $callable) {
    $this->cronExpressions = $this->parseCronExpressions($cronValues);
    $this->callable = $callable;
  }

  private function parseCronExpressions(array $values) {
    return array_map(
      function($c) {
        return CronExpression::factory($c);
      },
      $values
    );
  }

  public function isDue() {
    return array_reduce(
      $this->cronExpressions,
      function ($carry, CronExpression $cron) {
        return $carry || $cron->isDue();
      },
      false
    );
  }

  public function execute() {
    $callable = $this->callable;

    $callable();
  }
}
