<?php
namespace App\Controllers;

use \App\Auth;
use \App\Models\User;
use \App\Models\Core;

use \Core\View;

class Online extends Authenticated
{
    public function indexAction()
    {
        View::renderTemplate('Community/online.html');
    }
	
} 