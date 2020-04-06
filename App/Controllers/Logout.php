<?php
namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;

class Logout extends \Core\Controller 
{

    public function destroyAction()
    {
        $this->requiredLogin();

        Auth::logout();
        $this->redirect('/logout/show-logout-message');
    }

    public function showLogoutMessageAction()
    {
        Flash::addMessage('U bent met succesvol uitgelogd van Talpahotel, kom je nog snel een keer terug?');
        $this->redirect('/');
    }
}