<?php
namespace App;

use \App\Models\Core;
use \Core\Loader;

use QueryBuilder;
use PDO;

class Config
{
  
    public function __construct()
    {
        return (new Loader())->emulator(self::get('emulatorname')); 
    }

    public static function get($variable)
    {
        return Core::getFromSettings($variable);
    }
    
    /**
    * Configuration database
    */
    const driver        = 'mysql';
    const host          = 'localhost';
    const database      = 'beta';
    const username      = 'root';
    const password      = 'cosmic123';
    const charset       = 'utf8';
    const collation     = 'collation';
    const prefix        = '';

    /**
    * Configuration mailserver // PHPMAILER
    */
    const mailhost      = '193.33.61.122';
    const mailuser      = 'info@betahotel.nl';
    const mailpass      = 'USnxxKjri';
    const mailport      = 587;
    const mailssl       = true;

    //Errors
    const SHOW_ERRORS   = true;
   
}
