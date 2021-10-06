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
// echo '1212'
// echo dirname(__FILE__);exit;
// echo file_exists('/ms-sso');exit;
// echo file_exists('/ms-sso/simplesamlphp/vendor/autoload.php');exit;
// if (file_exists('/ms-sso/simplesamlphp/vendor/autoload.php')) {
//     require_once  '/ms-sso/simplesamlphp/vendor/autoload.php';
// } else {
    if (file_exists($test . '/vendor/autoload.php')){
        // require_once $test . '/vendor/autoload.php';
         include $test . '/vendor/autoload.php';
        exit;
    }
    if (file_exists($test . '/ms-sso/simplesamlphp/vendor/autoload.php')){
        echo '2';exit;
    }
if (file_exists($test . 'ms-sso/simplesamlphp/vendor/autoload.php')){
    echo '3';exit;
}
if (file_exists($test . '/ms-sso/simplesamlphp/vendor/autoload.php')){
    echo '4';exit;
}
if (file_exists($test . '/vendor/autoload.php')){
    echo '5';exit;
}
if (file_exists($test . '/../vendor/autoload.php')){
    echo '6';exit;
}
    
    if (file_exists($test . '/vendor/autoload.php')) {
        require_once $test . '/vendor/autoload.php';
    } else {
    // SSP is loaded as a library
    if (file_exists($test. '/../../../autoload.php')) {
        // echo 'if2';exit;
        require_once  $test. '/../../../autoload.php';
    } else {
        echo 'el2';exit;
        throw new Exception('Unable to load Composer autoloader');
        echo 'el2';
    }
}
