<?php
ini_set('display_errors', 1);
error_reporting(E_ALL); 
require __DIR__ . '/../../../vendor/autoload.php';
if($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'){
   define('BASE_URL', 'http://localhost/habittracker/Backend'); 
} else {
   define('BASE_URL', 'http://localhost/habittracker/Backend');
}
    $openapi = \OpenApi\Generator::scan([
        __DIR__ . '/doc_setup.php',
        __DIR__ . '/../../../rest'
    ]);
    header('Content-Type: application/json');
    echo $openapi->toJson();
?>