<?php
namespace app\src\handlers;

use app\src\utility\T9NumberProcessor;
use Exception;
use Illuminate\Database\Capsule\Manager as Db;

class ContactHandler {

    private string $name;
    private string $family;
    private string $fullName;
    private string $phoneNumber;
    private T9NumberProcessor $t9NumberProcessor;
    private string $t9Number;

    /**
     * @param array $params
     * @param T9NumberProcessor $t9NumberProcessor
     */
    public function __construct(array $params, T9NumberProcessor $t9NumberProcessor) {
        $this->name = $params['name'] ?? '';
        $this->family = $params['family'] ?? '';
        $this->fullName = $this->name . ' ' . $this->family;
        $this->phoneNumber = $params['phoneNumber'] ?? '';
        $this->t9NumberProcessor = $t9NumberProcessor;
    }

    private function processT9Numbers():void {
        $this->t9NumberProcessor->setName($this->fullName);
        $this->t9NumberProcessor->process();
        $this->t9Number = $this->t9NumberProcessor->getT9Number();
    }

    /**
     * Store Contact to Database
     *
     * @throws Exception
     */
    public function store():bool {

        // Calculate frequency number of contact name
        $this->processT9Numbers();

        // Check if phoneNumber already exist
        $phoneNumber = Db::table('contacts')->where('phoneNumber', '=', $this->phoneNumber)->first();

        if ($phoneNumber !== null ) {
            throw new Exception('Phone number already exists');
        }

        // Insert parameters in database
        return Db::table('contacts')->insert(
            [
                'name'        => $this->name,
                'family'      => $this->family,
                'phoneNumber' => $this->phoneNumber,
                't9Number'    => $this->t9Number,
            ]
        );
    }
}