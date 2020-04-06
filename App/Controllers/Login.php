<?php
namespace App\Controllers;

use \Core\View;
use \Core\Emu;

use \Libaries\Json;

use \App\Auth;
use \App\Token;
use \App\Config;
use \App\Flash;

use \App\Models\User;
use \App\Models\Core;

class Login extends \Core\Controller 
{
    public function __construct()
    {
        $this->alreadyLoggedIn();
        $this->load()->libaries('validate');
    }
  
    public function newAction()
    {
        View::renderTemplate('Index/login.html');
    }

    public function validateTokenAction()
    {
        /**
         * Retrieve values from the requested page
         * Class: Core\Request.php
         */
        
        $pin = $this->request->input('pin');
        $tokenid = $this->request->input('tokenid');

        /**
         * When the user try to login and has a higher rank, he / she must enter a pin
         * Class: App\Models\User.php, App\Auth.php
         */  
             
        if($tokenid)
        {
            $token = User::tokenAuth($tokenid);
            if($token->pin == $pin || $token->pin == null)
            {
                $this->loginUser($token);
            }
            else
            {
                $this->securitypin($token); 
            }
        }
       
    }
    
    public function doAction()
    {
        
        $this->alreadyLoggedIn();
        /**
         * Retrieve values from the requested page
         * Class: Core\Request.php
         */
      
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        $remember_me = $this->request->input('remember_me');
     
        /**
         * Check if given values are correcting in accordance with the validation requirements
         * Class: Libaries\Validate.php
         */

        $this->validate->name(Config::get('sitename') . 'naam')->value($username)->required();
        $this->validate->name('Wachtwoord')->value($password)->required();

         /**
         * First we check users validation. After that we check if the entered user enters his login data correctly
         * Class: Core\Request.php, Libaries\Validate.php, App\Models\User.php
         */  

        if ($this->request->all()){
            if ($this->validate->isSuccess()){
                $user = User::Authenticate($username, $password);
                if ($user) {
                    $this->securitypin($user);  
                }else{
                    Flash::addMessage('Inloggegevens onjuist ingevoerd', FLASH::ERROR);
                    $this->redirect('/login');
                }
            }else{
                View::renderTemplate('Index/login.html', [
                  'errors' => $this->validate->getErrors() , 
                  'username' => $username, 
                  'remember_me' => $remember_me
                ]);
            }
        }else{
            $this->newAction();
        }
            
    }
    
    private function securitypin($user)
    {   
        $user->token = Token::addToken();
        $token = User::updateToken($user->token, $user->id);
      
        if(Core::getData('cms_user_settings', 'pin', 'user_id', $user->id) != NULL){
            if($token){
                Flash::addMessage('Voer je pincode in om door te gaan!', 'warning');
                View::renderTemplate('Index/securitypin.html', [
                    'token' => $user->token
                ]);
            }
        }
        else
        {
            Flash::addMessage('Je hebt nog geen pin ingesteld! Stel deze snel in bij instellingen om te voorkomen dat je account wordt overgenomen door derden!', 'warning-hide');
            $this->loginUser($user);
        }
    }

    private function loginUser($user)
    {
        if(Auth::login($user))
        {
            Flash::addMessage('Welkom ' . $user->username . '!');
            if(User::giveUserPresent())
            {
                Flash::addMessage('Je hebt een cadeau gekregen! Bekijk hem tussen bij transacties op jouw account', FLASH::SUCCESS);
            }   
            $this->redirect('/');
        }
        else
        { 
            $this->newAction();
        }

    }
  
    public function userLookAction()
    {
        if ($this->request->all()){
            $user = $this->request->input('post');
            $look = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.look', 'look'), Emu::Get('table.Users.username', 'username'), $user);
            if($look)
            {
                echo Json::encode(array('look' => $look, 'status' => 'success'));
            }
        }
        else
        {
            $this->redirect('/');
        }
    }
}

