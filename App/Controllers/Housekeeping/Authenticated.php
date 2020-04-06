<?php
namespace App\Controllers\Housekeeping;

use \App\Auth;
use \App\Token;
use \App\Models\User;
use \App\Models\Core;

abstract class Authenticated extends \Core\Controller 
{
    protected function before()
    {
        $this->requiredLogin();
        $this->load()->input();
        Auth::userActivity(); 

        if(Auth::getUser())
        {
            $userid = Auth::returnUserId();
            if(Auth::checkBan(Auth::getUser()->username))
            {
                if(in_array('housekeeping', array_column(User::userPermissions(Auth::getUser()->rank), 'permission')))
                {
                    $this->token = Token::addToken();
                    if(User::updateToken($this->token, Auth::getUser()->id))
                    {
                        return true;
                    }
                }else{
                  Core::logger("[{$userid}] probeer gehoor te krijgen, maar diegene is geen medewerker van het hotel", 'let op!');
                  $this->redirect('/');
                }
            }else{
              Core::logger("Iemand die verbannen is van het hotel en paneel probeert toegang te verlenen [{$userid}]", 'let op!');
              $this->redirect('/');
          }
      }else{
          $this->redirect('/');
      }
      
    }

}