<?php

namespace imnotjames\Cronic;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

use hanneskod\classtools\Iterator\ClassIterator;

use ReflectionMethod;

class Cron {
  private $jobs = [];

  public function __construct(array $classes) {
    foreach ($classes as $class) {
      $this->jobs = array_merge($this->jobs, $this->getJobFromClass($class));
    }
  }

  private static function findClassesFromFinder(Finder $finder) {
    $classIterator = new ClassIterator($finder);

    $classIterator->enableAutoloading();

    $classes = [];
    // Print all classes, interfaces and traits in 'src'
    foreach ($classIterator as $class) {
      $classes[] = $class->getName();
    }

    return $classes;
  }

  private static function findClassesInDirectory($directory) {
    if (empty($directory)) {
      return [];
    }

    return self::findClassesFromFinder((new Finder())->in($directory));
  }

  public static function fromDirectory($directory) {
    return new Cron(self::findClassesInDirectory($directory));
  }

  public static function fromFiles(array $files) {

    $finder = new Finder();

    foreach ($files as $filename) {
      if (is_dir($filename)) {
        $finder->in($filename);
      } else {
        $finder->append([ new SplFileInfo($realFilename, $filename, basename($realFilename)) ]);
      }
    }

    return new Cron(self::findClassesFromFinder($finder));
  }

  private static function findClassesInNamespace($namespace) {
    // Ensure all namespaces start without the root indicator
    // and end with a namespace separator
    $namespace = '\\' . trim($namespace, '\\') . '\\';

    if ($namespace === '\\\\') {
      $namespace = '\\';
    }

    // Of all classes, return only the classes that match at least
    // one of the namespaces.
    return array_filter(
      get_declared_classes(),
      function($className) use ($namespace) {
        $className = '\\' . $className;

        if (stripos($className, $namespace) === 0) {
          return true;
        }

        return false;
      }
    );
  }

  public static function fromNamespace($namespace) {
    return new Cron(self::findClassesInNamespace($namespace));
  }

  private function getJobFromClass($className) {
    $methodNames = get_class_methods($className);

    $jobs = [];

    foreach ($methodNames as $methodName) {
      if (!method_exists($className, $methodName)) {
        continue;
      }

      $method = new ReflectionMethod($className, $methodName);

      if (!$method->isPublic()) {
        continue;
      }

      $values = $this->getCronAnnotations($method);

      $values = array_filter($values);

      if (empty($values)) {
        continue;
      }

      $jobs[] = new Job($values, [ $className, $methodName ]);
    }

    return $jobs;
  }

  private function getCronAnnotations(ReflectionMethod $method) {
    $annotations = $this->getAnnotations($method);

    $cronAnnotations = array_filter(
      $annotations,
      function($a) {
        return strtolower($a['annotation']) === 'cron';
      }
    );

    return array_map('trim', array_column($cronAnnotations, 'value'));
  }

  private function getAnnotations(ReflectionMethod $method) {
    $comment = $method->getDocComment();

    if (!preg_match_all('/^\s*\*[*\s]*(@([^\s]*)\s*(.+))$/m', $comment, $matches, PREG_SET_ORDER)) {
      return [];
    }

    return array_map(
      function($match) {
        return [
          'full' => $match[1],
          'annotation' => $match[2],
          'value' => $match[3],
        ];
      },
      $matches
    );
  }

  public function execute() {
    foreach ($this->jobs as $job) {
      if ($job->isDue()) {
        $job->execute();
      }
    }
  }
}
