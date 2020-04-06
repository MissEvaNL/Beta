<?php
namespace App\Controllers;

use \App\Auth;

abstract class Authenticated extends \Core\Controller 
{ 
    protected function before()
    {
        $this->requiredLogin();
        $this->load()->input();
       
      
        if(Auth::getUser())
        {
            if(Auth::checkBan(Auth::getUser()->username)){
                return true;
            }else{
                $this->redirect('/');
            }
        }else{
            $this->redirect('/');
        }
    }
}