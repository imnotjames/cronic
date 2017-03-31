<?php

class ExampleF {
  public function __construct($bar) {
    // do nothing
  }

  /**
   * @cron * * * * *
   */
  function exampleMethod() {
    echo "Failure";
  }
}
