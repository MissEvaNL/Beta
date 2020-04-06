<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Flash;
use \App\Auth;
use \App\Models\Core;
use \App\Models\Housekeeping;

class Rooms extends \App\Controllers\Housekeeping\Authenticated
{
  
    public function __construct()
    {
        $this->load()->libaries('validate');
        $this->role = 'housekeeping_remote_control';
    }
  
    public function indexAction()
    {    
        View::renderTemplate('Housekeeping/Tools/rooms.html', [
            'permission' => $this->role
        ]);
    }

}
    