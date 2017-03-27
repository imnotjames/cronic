<?php

class ExampleB {
    /**
     *    @cron   * * * * *
     */
    function exampleMethod() {
      echo "SuccessA";
    }

    /**
     * All kinds of extra stuff
     *@cron  * * * * *
     */
    function dailyMethod() {
      echo "SuccessB";
    }

    /**
     * This is not an @cron * * * * *
     *
     * @help @cron * * * * *
     * @notcron   * * * * *
     * @return    * * * * *
     */
    function notCalled() {
      echo "Failure";
    }
}
