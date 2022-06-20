<?php

    require __DIR__ . '/../vendor/autoload.php';

    use app\src\db\Db as DbConf;
    use app\src\utility\Params;
    use app\src\utility\Trie;
    use Illuminate\Database\Capsule\Manager as Database;

    $errorMessage = '';

    // Extract form parameters
    $params = Params::getParams();

    if (empty($params) !== true && $params['digit'] === '') {
        $errorMessage = 'Please fill Search field';
    }

    if (empty($params) !== true && $errorMessage === '') {

        DbConf::configDb();

//            I have tried to implement "Trie Data Structure" because of efficiency for searching in Data
//            but it's add too much complexity to the Project and needs more Time to implement it correctly
//
//            $dbResults = Database::table('contacts')->select(['name', 'family', 't9Number'])->get();
//
//            $trie = new Trie();
//
//            foreach ($dbResults as $result) {
//                $fullName = strtolower($result->name) . strtolower($result->family);
//                $trie->insert($fullName, (int) $result->t9Number);
//            }
//
//            $result = $trie->getSuggestions($params['digit'], 5);

        try {

//          Extract relevant contacts from database with frequency number
            $results = Database::table('contacts')->select(['name', 'family', 'phoneNumber', 't9Number'])->where('t9Number', 'like', '%' . $params['digit'] . '%')->get();

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }

    include 'body.php';
