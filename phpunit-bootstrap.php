<?php
/**
 * Created by PhpStorm.
 * User: smedina
 * Date: 15/11/13
 * Time: 02:14 PM
 */

function testAutoloader($className)
{
  $callers = debug_backtrace();
  if (!empty($callers[1]) && !empty($callers[1]['file']) && strpos($callers[1]['file'], '/Tests/') > 0) {
    $filePath = $callers[1]['file'];
    list($projectPath, $testPath) = explode('/Tests/', $filePath);

    // First we check if we find the class in the corresponding directory - same as the Test directory structure
    $dirName           = dirname($testPath);
    $potentialFilePath = $dirName . DIRECTORY_SEPARATOR . $className . '.php';
    if (file_exists($potentialFilePath)) {
      require_once $potentialFilePath;
    } else {
      // If it's not like the Test directory structure, look everywhere else
      $objects
        = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($projectPath), RecursiveIteratorIterator::SELF_FIRST);
      foreach ($objects as $name => $object) {
        $fileName = basename($object);

        if ($fileName !== '.' && $fileName !== '..' && $fileName === $className . '.php'
        ) {
          require_once $object;
        }
      }
    }
  }

}

spl_autoload_register('testAutoloader');