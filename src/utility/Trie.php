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

    private function bridgeNodes($node, $fullName) {
        $len = strlen($fullName);
        $i = 0;
        $test = '';

        for($i; $i < $len; $i++) {
            $letter = str_split($fullName)[$i];
            $thisKey = T9NumberProcessor::KEYS[$letter];

            if(array_key_exists($thisKey, $node->children) === true) {
                $node = $node->children[$thisKey];
            } else {
                break;
            }
        }

        for($i; $i < $len; $i++) {
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
        $fullNameLength = count($list);

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
            $thisKey = (int) str_split($key)[$i];
            $node = $node->children[$thisKey];
        }

        $result = array_merge($result, array_map(function($fullNameAndDigit) {
            return $fullNameAndDigit[0];
        }, $node->fullNames));

        return $suggestionsDepth > 0 ? array_merge($result, $this->getDeeperSuggestions($node, $suggestionsDepth)) : $result;
    }

    private function getDeeperSuggestions(&$node, int $maxDepth) {
        $deepSuggestions = [];
        while(count($deepSuggestions) <  $maxDepth) {
            $deepSuggestions[] = [];
        }

        $this->traverse($node, 0, $maxDepth, $deepSuggestions);

        $deepSuggestions = array_map(function($level) {
            usort($level, function($a, $b) {
                return $a[1] - $b[1];
            });
            return $level;
        }, $deepSuggestions);

        return array_reduce($deepSuggestions, function($result, $level) {
            $level = $level;
            return array_merge($result, array_map(function($fullNameAndDigit){
                return $fullNameAndDigit[0];
            }, $level));
        }, []);
    }

    private function traverse(&$node, $depth, $maxDepth, &$deepSuggestions) {

        if ($depth <= $maxDepth && $depth !== 0) {
            $d = $depth - 1;
            $deepSuggestions[$d] = array_merge($deepSuggestions[$d], $node->fullNames);
        }

        if ($depth === $maxDepth) {
            return;
        }

        foreach($node->children as $childKey) {
            $this->traverse($childKey, $depth + 1, $maxDepth, $deepSuggestions);
        }
    }
}