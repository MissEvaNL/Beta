<?php
namespace App;

use \Core\Emu;

use \App\Config;
use \App\Models\User;
use \App\Models\Core;
use \App\Models\RememberedLogin;

class Auth {

    /**
    * Login the user
    *
    * @param User $user The user model
    * @param boolean $remember_me Remember the login if true
    *
    * @return true or false
    */

    public static function login(User $user, $token = null)
    {
        if(!self::checkBan($user->username))
        {
            return false;
        }
        else
        {
            Session::set('user_id', $user->id);
            Session::set('lastActivity', time());
          
            User::updateUser($user->id, Emu::Get('table.Users.last_login'), time());
            User::updateUser($user->id, Emu::Get('table.Users.ip_current'), Core::getIpAddress());  
            return true;
        }
    }
  
    /**
    * Check if user is banned by IP or Username
    *
    * @param username
    *
    * @return true or false
    */
  
    public static function checkBan($username)
    {
        $ban = Core::getBans($username, Core::getIpAddress());
        if($ban)
        {
            Flash::addMessage('Je bent verbannen met als reden: ' . $ban->reason, FLASH::WARNING);
            return false;
        } 
        else
        {
            return true;
        }
    }

    /**
    * Logout the user
    *
    * @return void
    */
  
    public static function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies'))
        {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time(),
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        Session::destroy();
    }
  
    public static function loginAttempt()
    {
        //* will be added soon
        return true;
    } 
    
    /**
    * Remember the originally-requested page in the session
    *
    * @return void
    */
    
    public static function rememberRequestedPage()
    {
        Session::set('return_to', $_SERVER['REQUEST_URI']);
    }
  
    /**
    * Get the originally-requested page to return to after requiring login, or default to the homepage
    *
    * @return void
    */
    
    public static function getReturnToPage()
    {
        return Session::get('return_to') ?? '/';
    }

    /**
    * Get the current logged-in user, from the session or the remember-me cookie
    *
    * @return mixed The user model or null if not logged in
    */
  
    public static function getUser()
    {
        if(Session::get('user_id'))
        {
            return (new User())->FindById(Session::get('user_id'));
        }
    }
  
    /**
    * Get the current logged-in user
    *
    * @return userid
    */
  
    public static function returnUserId()
    {
        if(Session::get('user_id'))
        {
            return self::getUser()->id;
        }
    }

    /**
    * Get user latest activity from a session
    *
    * @return mixed Log off the user when the session is expired
    */
    
    public static function userActivity()
    {
      if(Config::get('maintenance') == 0 || self::checkUserRank() >= 5)
        {
            $userInactivity = Config::get('userinactivity');
            if (Session::get('lastActivity'))
            {
                $InactiveTime = time() - Session::get('lastActivity');		
                if ($InactiveTime >= $userInactivity) 
                {
                    static::logout();
                }
                else if ($InactiveTime < $userInactivity) 
                {
                    return Session::set('lastActivity', time());
                }	
            }

        }else{
            static::logout();
            Flash::addMessage('We zijn momenteel in onderhoud en komen straks bij je terug!', FLASH::ERROR);
        }
    }
  
    public static function checkUserRank()
    {
        $loggedin = Auth::getUser();
      
        if($loggedin)
        {
            return Auth::getUser()->rank;
        }
    }
  
}
?>