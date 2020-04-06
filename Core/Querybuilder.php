<?php
namespace Core;

use \App\Config;
use PDO;

class Querybuilder {
    
    public function __construct() {
         $config = array(
                'driver'    => Config::driver, // Db driver
                'host'      => Config::host,
                'database'  => Config::database,
                'username'  => Config::username,
                'password'  => Config::password,
                'charset'   => Config::charset, // Optional
                'collation' => Config::collation, // Optional
                'prefix'    => Config::prefix, // Table prefix, optional
                'options'   => array( // PDO constructor options, optional
                    PDO::ATTR_TIMEOUT => 5,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ),
            );
        
         new \Pixie\Connection('mysql', $config, 'QueryBuilder');
        

    }
}

?>