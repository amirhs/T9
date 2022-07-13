<?php
namespace app\src\utility;

class Request {

    public static function getParams() {

        $params = [];

        if (empty($_GET) === false) {

            foreach ($_GET as $key => $value) {
                $params[$key] = trim(filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS));
            }

        }

        return $params;
    }
}