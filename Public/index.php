<?php

    require __DIR__ . '/../vendor/autoload.php';

    use app\src\db\Db as DbConf;
    use app\src\utility\Request;
    use Illuminate\Database\Capsule\Manager as Database;

    $errorMessage = '';

    // Extract form parameters
    $params = Request::getParams();

    if (empty($params) !== true && $params['digit'] === '') {
        $errorMessage = 'Please fill Search field';
    }

    if (empty($params) !== true && $errorMessage === '') {

        DbConf::configDb();
        $results = [];

        try {

//          Extract relevant contacts from database with frequency number
            $start = microtime(true);
            $results = Database::table('contacts')->select(['name', 'family', 'phoneNumber', 't9Number'])->where('t9Number', 'like', $params['digit'] . '%')->get();

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }

    include 'body.php';
