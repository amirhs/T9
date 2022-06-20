<?php
namespace app\src\utility;

class T9NumberProcessor {

    private string $fullname;
    private string $t9Number = '';

    // Simulate mobile keys
    public const KEYS = [
        'a' => 2, 'b' => 2, 'c' => 2,
        'd' => 3, 'e' => 3, 'f' => 3,
        'g' => 4, 'h' => 4, 'i' => 4,
        'j' => 5, 'k' => 5, 'l' => 5,
        'm' => 6, 'n' => 6, 'o' => 6,
        'p' => 7, 'q' => 7, 'r' => 7, 's' => 7,
        't' => 8, 'u' => 8, 'v' => 8,
        'w' => 9, 'x' => 9, 'y' => 9, 'z' => 9,
    ];


    /**
     * @param string $fullname
     * @return void
     */
    public function setName(string $fullname):void {
        $this->fullname = $fullname;
    }

    /**
     * @return void
     */
    public function process():void {

        $fullName_chars = str_split(strtolower($this->fullname));

        foreach($fullName_chars as $char) {

            if ($char === ' ') {
                continue;
            }

            $this->t9Number .= (string) self::KEYS[$char];
        }

    }

    /**
     * @return string
     */
    public function getT9Number(): string {
        return $this->t9Number;
    }
}