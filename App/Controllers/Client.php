<?php
namespace App\Controllers;

use \App\Auth;
use \App\Models\User;
use \App\Models\Core;

use \Core\View;

class Client extends Authenticated
{
    public function indexAction()
    {
        $data = new \stdClass();

       if(User::createSso(Auth::returnUserId()))
       {
             $user = Auth::getUser();
             $data->client = Core::getAllFromSettings();
             $data->sso    = $user->auth_ticket;
       }
      
        View::renderTemplate('client.html', [
            'data' => $data
        ]);
    }
	
    public function noflash()
    {  
        View::renderTemplate('noflash.html');
    }
  
} 