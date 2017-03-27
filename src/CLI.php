<?php

namespace imnotjames\Cronic;

use splitbrain\phpcli\CLI AS ParentCLI;
use splitbrain\phpcli\Options;

class CLI extends ParentCLI {
  protected function setup(Options $options) {
    $options->setHelp('Cronic is a library used to run commands at intervals.');
    $options->registerOption('version', 'print version', 'v');
    $options->registerOption('namespace', 'namespace to search for cron jobs in', 'n', 'namespace');
    $options->registerArgument('path', 'one or more paths to search for cronic annotations', false);
  }

  protected function main(Options $options) {
    if ($options->getOpt('version')) {
      echo json_decode(file_get_contents(__DIR__ . '/../composer.json'))->version ?? 'unknown version';
      echo "\n";
      die(0);
    } else if ($options->getOpt('namespace')) {
      $cron = Cron::fromNamespace($options->getOpt('namespace'));
    } else if (count($options->getArgs()) > 0) {
      $cron = Cron::fromFiles($options->getArgs());
    } else {
      echo $options->help();
      die(1);
    }

    $cron->execute();
  }
}
