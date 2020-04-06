<?php
namespace App\Controllers;

use \Core\View;

use \App\Auth;
use \App\Config;
use \App\Flash;
use \App\Models\User;
use \App\Models\Core;

class Signup extends \Core\Controller
{
    public function __construct(){
        $this->alreadyLoggedIn();
        $this->load()->libaries('validate');
    }
  
    public function newAction()
    {
        View::renderTemplate('Signup/new.html', ['recaptchakey' => Config::get('recaptchakey')]);
    }

    public function createAction()
    {
        if($this->request->all()){
          
          
        /**
         * Retrieve values from the requested page
         * Class: Core\Request.php
         */

          $username = $this->request->input('username');
          $password = $this->request->input('password');
          $password_repeat = $this->request->input('password_repeat');
          $email = $this->request->input('email');
          $habboavatar = $this->request->input('habbo-avatar');


        /**
         * Check if given values are correcting in accordance with the validation requirements
         * Class: Libaries\Validate.php
         */

          $this->validate->name(Config::get('hotelname') . 'naam')->value($username)->required()->min(3)->max(22)->customPattern('[a-zA-Z0-9-]+');
          $this->validate->name('email')->value($email)->required()->pattern('email');
          $this->validate->name('password')->value($password)->required()->min(6)->max(32)->customPattern('[A-Za-z0-9-.;_!#@]{5,15}')->equal($password_repeat);

        /**
         * Check if there is valid post request, if the result is true, we can validate the post values.
         * When we get status true, the account can be registered in the user class
         * Class: Core\Request.php, Libaries\Validate.php, Core\Models\User.php
         */
          
            if($this->validate->isSuccess())
            {
                if(Auth::checkBan(null, Core::getIpAddress()))
                {
                    $register = User::registerAccount($this->request->all());
                    if($register)
                    {
                        Auth::login(User::FindById($register));
                        Flash::addMessage('Wij heten je van harte welkom in Talpahotel');
                        $this->redirect(Auth::getReturnToPage());
                    }
                    else
                    {
                        View::renderTemplate('Signup/new.html', [
                          'username' => $username,
                          'mail' => $email,
                          'recaptchakey' => Config::get('recaptchakey')
                        ]);
                    }
                }
                else
                {
                    $this->redirect('/'); 
                }
            }
            else
            {
                View::renderTemplate('Signup/new.html', [
                    'username' => $username,
                    'mail' => $email,
                    'recaptchakey' => Config::get('recaptchakey')
		        ]);
            }
        }
        else
        {
            $this->redirect('/register');

            
        }
    }
}