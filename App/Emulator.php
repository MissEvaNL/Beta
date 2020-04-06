<?php
namespace App;

use \App\Emulator;

class Emulator {

    public static function Insert($func, $array, $targetvar = Config::emulator) {
        // you can call all necessary cleaning methods here
        // before creating new object
        $namespace = '\App\Emulator\\' . ucfirst($targetvar);
            //print_r($targetvar\Emulators::Test());

        return (new $namespace)->$func($array);
    }

}

