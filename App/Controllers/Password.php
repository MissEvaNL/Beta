<?php
namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

class Password extends \Core\Controller 
{
    public function __construct()
    {
        $this->alreadyLoggedIn();
        $this->load()->libaries('validate');
    }
    
    public function forgotAction()
    {
        View::renderTemplate('Password/forgot.html');
    }    
    
    public function requestResetAction()
    {
        $email = $this->request->input('email');

        $this->validate->name('Email')->value($email)->pattern('email')->required();
        
        if ($this->request->all()){
            if ($this->validate->isSuccess()){
                $user = User::sendPasswordReset($email);
                if($user){
                  $this->redirect('/password/request-success');  
                }else{
                  Flash::addMessage('Opgegeven email is niet bekend in het talpahotel database!', 'fout opgetreden');
                  $this->redirect('/password/forgot');
                }
            }
        }
    }
    
    public function resetAction()
    {
        $token = $this->route_params['token'];
        
        $user = $this->getUserOrExit($token);
        View::renderTemplate('Password/reset.html', ['token' => $token]);
    }
    
    public function resetPasswordAction()
    {
        $password = $this->request->input('password');
        $passwordConfirmation = $this->request->input('passwordConfirmation');
        $token = $this->request->input('token');
        
        $this->validate->name('Wachtwoorden')->value($password)->equal($passwordConfirmation)->min(6)->required();
        
        $user = $this->getUserOrExit($token);
        if($this->validate->isSuccess()){
            if($user->resetPassword($password)){
                 $this->redirect('/password/password-change-success');
            }
        }else{
            View::renderTemplate('Password/reset.html', [
                'token'     => $token, 
                'user'      => $user
            ]);
        }
    }
    
    protected function getUserOrExit($token)
    {
        $user = User::findByPasswordReset($token);
        if($user){
            return $user;
        }else{
            View::renderTemplate('Password/token_expired.html');
            exit;
        }
    }
	
    public function requestSuccess(){
        Flash::addMessage('Er is een mail gestuurd met verdere instructies voor instellen van je wachtwoord', 'success');
        $this->redirect('/'); 
	}
  
    public function passwordChangeSuccess()
    {
        Flash::addMessage('Je wachtwoord is aangepast, je kunt inloggen met je wachtwoord!', 'success');
        $this->redirect('/');   
    }
}