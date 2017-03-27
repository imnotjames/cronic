<?php

class ExampleA {
    /**
     * @cron * * * * *
     */
    function exampleMethod() {
      echo "Success";
    }

    function notCalled() {
      echo "Failure";
    }
}
