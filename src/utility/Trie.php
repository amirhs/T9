<?php
namespace app\src\utility;

class Trie {

    private array $fullNames;
    private array $children;

    public function __construct() {
        $this->fullNames = [];
        $this->children = [];
    }

    public function insert(string $fullName, int $digits) {

        $nodeToAddName = $this->bridgeNodes($this, $fullName);

        $this->insertFullNameToListByDigits($nodeToAddName->fullNames, $fullName, $digits);

    }

    private function bridgeNodes(&$node, $fullName) {
        $len = strlen($fullName);

        for($i = 0; $i < $len; $i++) {
            $letter = str_split($fullName)[$i];
            $thisKey = T9NumberProcessor::KEYS[$letter];

            if(empty($node->children[$thisKey]) === false) {
                $node = $node->children[$thisKey];
            } else {
                break;
            }
        }

        for($i = 0; $i < $len; $i++) {
            $letter = str_split($fullName)[$i];
            $thisKey = T9NumberProcessor::KEYS[$letter];

            $node->children[$thisKey] = new Trie();

            $node = $node->children[$thisKey];

        }

        return $node;
    }

    private function insertFullNameToListByDigits(&$list, $fullName, $digits)
    {
        $fullNameToInsert = [$fullName, $digits];
        $fullNameLength = strlen($fullName);

        if ($fullNameLength === 0) {
            // Add name for the first time
            $list[] = $fullNameToInsert;
        } else {

            // Find where to add name considering Digit
            for ($i = 0; $i < $fullNameLength; $i++) {
                if (count($list) <= 0) {
                    continue;
                }
                $compareDigit = $list[$i][1];
                $insertDigit = $fullNameToInsert[1];

                if ($insertDigit >= $compareDigit) {
                    array_splice($list, $i, 0, $fullNameToInsert);
                    return;
                }
            }

            array_splice($list, $i + 1, 0, $fullNameToInsert);
        }
    }

    public function getSuggestions(string $key, int $suggestionsDepth) {
        $result = [];
        $node = $this;

        for ($i = 0; $i < strlen($key); $i++) {
            $thisKey = $key[$i];
            $node = $node->children[$thisKey];
        }

        $result = array_merge($result, array_map(function($fullNameAndDigit) {
            return $fullNameAndDigit[0];
        }, $node->fullNames));

        return $suggestionsDepth > 0 ? array_merge($result, $this->getDeeperSuggestions($node, $suggestionsDepth)) : $result;
    }

    private function getDeeperSuggestions($node, int $maxDepth) {
        $deepSuggestions = [];
        while(count($deepSuggestions) <  $maxDepth) {
            $deepSuggestions[] = [];
        }

        $deepSuggestions = array_map(function($level) {
            return usort($level, function($a, $b) {
                return $a[1] - $b[1];
            });
        }, $deepSuggestions);

        $this->traverse($node, 0, $maxDepth, $deepSuggestions);

        return array_reduce($deepSuggestions, function($result, $level) {
            return array_merge($result, array_map(function($fullNameAndDigit){
                return $fullNameAndDigit[0];
            }, $level));
        }, []);
    }

    private function traverse($node, $depth, $maxDepth, $deepSuggestions) {

        if ($depth <= $maxDepth && $depth !== 0) {
            $d = $depth - 1;
            $deepSuggestions[$d] = array_merge($deepSuggestions[$d], $node->fullNames);
        }

        if ($depth === $maxDepth) {
            return;
        }

        foreach($node->children as $childKey) {
            $this->traverse($node->children[$childKey], $depth);
        }
    }
}