<?php

/**
 * This file is a backwards compatible autoloader for SimpleSAMLphp.
 * Loads the Composer autoloader.
 *
 * @author Olav Morken, UNINETT AS.
 * @package SimpleSAMLphp
 */

declare(strict_types=1);

// SSP is loaded as a separate project

$test = dirname(dirname(__FILE__)) ;
$test=str_replace("workspace/","",$test);
echo $test;exit;

if (file_exists( $test. '/vendor/autoload.php')) {
    require_once  $test . '/vendor/autoload.php';
} else {
    // SSP is loaded as a library
    if (file_exists( $test . '/../../autoload.php')) {
        require_once  $test . '/../../autoload.php';
    } else {
        throw new Exception('Unable to load Composer autoloader');
    }
}
