<?php
namespace app\setup;

require __DIR__ . '/../vendor/autoload.php';

use app\src\db\Db;
use Illuminate\Database\Capsule\Manager as Capsule;

class SetupDb {

    public static function setup():void {
        Db::configDb();
        Capsule::schema()->create('contacts', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('family');
            $table->string('phoneNumber')->unique();
            $table->string('t9Number');
            $table->index('t9Number');
        });
    }
}

SetupDb::setup();