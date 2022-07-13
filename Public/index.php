<?php

    require __DIR__ . '/../vendor/autoload.php';

    use app\src\db\Db as DbConf;
    use app\src\utility\Request;
    use app\src\utility\Trie;
    use Illuminate\Database\Capsule\Manager as Database;


    include 'header.php';

    $errorMessage = '';

    // Extract form parameters
    $params = Request::getParams();

    if (empty($params) !== true && $params['digit'] === '') {
        $errorMessage = 'Please fill Search field';
    }

    if (empty($params) !== true && $errorMessage === '') {

        DbConf::configDb();
        $results = [];

//        $redis = new Predis\Client();
//        $trieRedis = $redis->get('trie');
        $time_elapsed_secs = '';

//        if ($trieRedis === null) {
//
////            I have tried to implement "Trie Data Structure" because of efficiency for searching in Data
////            but it's add too much complexity to the Project and needs more Time to implement it correctly
//
//            $dbResults = Database::table('contacts')->select(['name', 'family', 't9Number'])->get();
//
//            $trie = new Trie();
//
//            foreach ($dbResults as $result) {
//                $fullName = strtolower($result->name) . strtolower($result->family);
//                $trie->insert($fullName, (int) $result->t9Number);
//                $redis->set('trie', serialize($trie));
//            }
//        } else {
//
//            $trie = unserialize($redis->get('trie'));
//            $start = microtime(true);
//
//            $results = $trie->getSuggestions($params['digit'], 20);
//            $time_elapsed_secs = microtime(true) - $start;
//
//        }




//        $dbResults = Database::table('contacts')->select(['name', 'family', 't9Number'])->get();
//
//        $trie = new Trie();
//
//        foreach ($dbResults as $result) {
//            $fullName = strtolower($result->name) . strtolower($result->family);
//            $trie->insert($fullName, (int) $result->t9Number);
//        }
//
//        $results = $trie->getSuggestions($params['digit'], 10);



        try {

//          Extract relevant contacts from database with frequency number
            $start = microtime(true);
            $results = Database::table('contacts')->select(['name', 'family', 'phoneNumber', 't9Number'])->where('t9Number', 'like', '%' . $params['digit'] . '%')->get();
            $time_elapsed_secs = microtime(true) - $start;
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }
    }

    include 'body.php';
