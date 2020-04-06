<?php
namespace App\Controllers\Housekeeping;

use \Core\Logger;
use \Core\View;
use \Core\Emu;

use \App\Auth;
use \App\Token;
use \App\Models\User;
use \App\Models\Core;
use \App\Models\Housekeeping;

use \Libaries\ApiEmulator;

class Dashboard extends \App\Controllers\Housekeeping\Authenticated
{
    public function dashboard()
    {   
        $data = new \stdClass();
      
        $data->namechanges  = Housekeeping::getAllUserNameChanges();
        $data->staffOnline  = Core::getStaffOnline();
        $data->banmessages  = Housekeeping::getBanMessages();
        $data->bantime      = Housekeeping::getBanTime(Auth::getUser()->rank);
      
        $latestUsers  = Housekeeping::getLatestUsers();
      
        foreach($latestUsers as $latest)
        {
            $latest->username  = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $latest->id); 
            $latest->ipreg     = Core::convertIp(Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_register'), 'id', $latest->id), Auth::getUser()->rank); 
            $latest->iplast    = Core::convertIp(Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_current'), 'id', $latest->id), Auth::getUser()->rank); 
            $latest->lastvisit = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.last_online'), 'id', $latest->id); 
        }
      
        $data->latestUsers = $latestUsers;
      
        View::renderTemplate('Housekeeping/Dashboard/home.html', [
              'data' => $data,
              'permission' => 'housekeeping'
        ]);
      
    }
  
    public function indexAction()
    {
        $this->dashboard();
    }
  
    public function send()
    {
        $rcon = new ApiEmulator(
        [
            'host' => '185.114.157.11',
            'port' => 30001
        ]
        );
        $onlineCount = $rcon->getOnlineCount();
        print_r($onlineCount);
    }
    
}
    