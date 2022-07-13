<?php

require __DIR__ . '/../vendor/autoload.php';

use app\src\utility\Request;
use app\src\utility\T9NumberProcessor;
use app\src\handlers\ContactHandler;
use app\src\db\Db;

$errorMessage = '';
$params = Request::getParams();

// Check if one of the fields was empty
if (
    empty($params) !== true &&
    ($params['name'] === '' ||
        $params['family'] === '' ||
        $params['phoneNumber'] === '')
) {
    $errorMessage = 'Please fill all Contact fields';
}

if (empty($params) !== true && $errorMessage === '') {

    // Add database Connection
    Db::configDb();

    // Instantiate calculator of frequency number
    $T9NumberProcessor = new T9NumberProcessor();

    $ContactHandler = new ContactHandler($params, $T9NumberProcessor);

    try {

        // Store contact in database
        $ContactHandler->store();

        $Message = 'Contact saved.';
    } catch (\Exception $e) {
        $Message = $e->getMessage();
    }
}

include 'body.php';
