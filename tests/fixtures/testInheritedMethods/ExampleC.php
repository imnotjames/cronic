<?php

class ExampleC {
  /**
   * @cron * * * * *
   */
  function exampleMethod() {
    echo "SuccessC";
  }

  function notCalled() {
    echo "Failure";
  }
}
