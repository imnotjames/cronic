<?php

class ExampleE {
  /**
   * @cron * * * * *
   */
  static function exampleMethod() {
    echo "SuccessE";
  }

  static function notCalled() {
    echo "Failure";
  }
}
