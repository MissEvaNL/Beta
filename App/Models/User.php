<?php
namespace App\Models;

use \Core\Loader;
use \Core\View;
use \Core\Emu;
use \App\Config;
use \App\Token;
use \App\Mail;
use \App\Auth;
use \App\Flash;
use \App\Emulator;
use \App\Models\User;
use \App\Models\Core;

use \Libaries\Validate;

use QueryBuilder;
use PDO;

class User extends \Core\Model
{
 
    /**
    * See if a user record already exists with the specified record
    *
    * @param string $username usercode to search for
    *
    * @return boolean  True if a record already exists with the specified user, false otherwise
    */
    public static function userExists($username)
    {
        return static::FindByName($username);
    }
    
    /**
    * See if a user record already exists with the specified reord
    *
    * @param string $email usercode to search for
    *
    * @return boolean  True if a record already exists with the specified email, false otherwise
    */
    public static function emailExists($email)
    {
        return static::FindByEmail($email);
    }
  
    public static function idExists($userid)
    {
        return static::FindByid($userid);
    }

    /**
    * See if a token record already exists with the specified reord
    *
    * @param string $email usercode to search for
    *
    * @return boolean  True if a record already exists with the specified email, false otherwise
    */
    
    public static function tokenExists($token)
    {
        return static::FindByToken($token);
    }
  
    /**
    * Check if tokenid exists
    *
    * @param string $email usercode to search for
    *
    * @return boolean  True if a record already exists with the specified token, false otherwise
    */
    
    public static function tokenAuth($tokenid)
    {
        $token = static::tokenExists($tokenid);
        if($token){
            return $token;
        }
    }
    
    /**
    * Authenticate a user by email and password.
    *
    * @param string $email email address
    * @param string $password password
    *
    * @return mixed  The user object or false if authentication fails
    */
    public static function Authenticate($username, $password){
        $user = static::userExists($username);
        if($user){
            if(password_verify($password, $user->password)){
                return $user;
            }
        }
        return false;
    }
   
    /**
    * Check if user is banned 
    * for this user record
    *
    * @return boolean  True if the user has no ban, false otherwise
    */
    
    public static function isUserBanned($user_id){  
        return QueryBuilder::table('bans')
                    ->where(Emu::Get('table.Bans.type'), Emu::Get('table.Bans.type.account'))
                    ->where(Emu::Get('table.Bans.user_id'), $user_id)->first();
    }
  
    /**
    * Remember the login by inserting a new unique token into the remembered_logins table
    * for this user record
    *
    * @return boolean  True if the login was remembered successfully, false otherwise
    */
    
    public static function updateUser($user_id, $key, $value){  
        return QueryBuilder::table(Emu::Get('tablename.Users'))->where('id', $user_id)->update(array($key => $value));
    }

  
    /**
    * Find a user model by usercode
    *
    * @param string $email user code to search for
    *
    * @return mixed User object if found, false otherwise
    */
    
    public static function FindByName($username)
    {
        return  QueryBuilder::table(Emu::Get('tablename.Users'))
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                    ->where(Emu::Get('table.Users.username'), $username)->first();
    }
  
    public static function FindAllUsersByString($string)
    {
        return  QueryBuilder::table(Emu::Get('tablename.Users'))->select('username')->select('id')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where(Emu::Get('table.Users.username'), 'LIKE ', '%' . $string . '%')->get();
    }
  
    /**
    * Find a user model by email
    *
    * @param string $email user code to search for
    *
    * @return mixed User object if found, false otherwise
    */
    
    public static function FindByEmail($email)
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                    ->where(Emu::Get('table.Users.mail'), $email)->first();
        
    }
	
    /**
    * Check if registered account exists in our database. Also check if values already exists
    *
    * @param string $data array of given values
    *
    * @return mixed  The user object or false if authentication fails
    */
    
    public static function registerAccount($data)
    {
        if (static::userExists($data['username'] ?? null)) 
        {
            Flash::addMessage(Config::get('hotelname') . 'naam is al ingebruik!', 'error');
        } 
        else if (static::emailExists($data['email'] ?? null)) 
        {
            Flash::addMessage('opgegeven email is al ingebruik', 'error');
        }
        else
        {
            $loader = '\App\Emulator\\' . ucfirst(Config::get('emulatorname'));
            $register = call_user_func($loader. '::addUsers', $data);
            if($register)
            {
                queryBuilder::table('cms_user_settings')->insert(array('user_id' => $register));
                queryBuilder::table('cms_feeds')->insert(array('to_user_id' => $register, 'message' => 'Een warm welkom naar onze nieuwe hotel speler!', 'timestamp' => time(), 'from_user_id' => 0));
                return $register;
            }
        }
   }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
  
    public static function FindById($id)
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($id);  
    }
  
    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
  
    public static function userCmsSettings($id)
    {
        return QueryBuilder::table('cms_user_settings')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($id, 'user_id');  
    }
  
    public static function userPermissions($rank)
    {
        return QueryBuilder::table('cms_permissions_ranks')->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                  ->join('cms_permissions', 'cms_permissions_ranks.permission_id', '=', 'cms_permissions.id')->where('permissions_groups', $rank)->get();  
    }
    
    /**
    * Find a user model by Token
    *
    * @param string $token The user ID
    *
    * @return mixed User object if found, false otherwise
    */
    
    public static function FindByToken($token)
    {
        return QueryBuilder::table('cms_user_settings')
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                    ->select('*')
                    ->select('cms_user_settings.pin')
                    ->where('token', $token)
                    ->join(Emu::Get('tablename.Users'), Emu::Get('tablename.Users') . '.id', '=', 'cms_user_settings.user_id')->first(); 
    }
  
    /**
    * CreateSSO for logging in the client for security
    *
    * @param string userid
    *
    * @return user object
    */
  
    public static function createSso($user_id) 
    {
        $authTicket = new Token();
        return self::updateUser($user_id, 'auth_ticket', $authTicket->authTicket());
    }
  
    /**
    * Update token when user try after request password
    *
    * @return boolean And insert token to our database
    */
  
    public static function changeAccountSettings($email, $password, $user_id)
    {
        if(!empty($password))
        {
            $token = new Token();
            $password = $token->password($password);    
          
            $data = array(
                Emu::Get('table.Users.mail') => $email,
                'password' => $password
            );
        }
        else
        {
            $data = array(
                Emu::Get('table.Users.mail') => $email
            );
        }
      
        QueryBuilder::table(Emu::Get('tablename.Users'))->where('id', $user_id)->update($data);
    }
    
    /**
    * Update Token for security reasons
    *
    * @param string $token, $userid
    *
    * @return true or false
    */
  
    public static function updateToken($token, $user_id)
    {  
        $data = array(
            'token'    => $token
        );
        
        $query = QueryBuilder::table('cms_user_settings')->where('user_id', $user_id)->update($data);
        if($query){
            return true;
        }
    }
  
    /**
    * Get clubdays from user table
    *
    * @param string userid
    *
    * @return days of clubmember
    */
  
    public static function getClubDays($user_id) {
        if(Core::getData(Emu::Get('tablename.Habbo_club'), Emu::Get('table.Habbo_club.timestamp_expire'), 'user_id', $user_id) > 0) 
        {
            $query = QueryBuilder::table(Emu::Get('tablename.Habbo_club'))->select(Emu::Get('table.Habbo_club.timestamp_expire'))->where('user_id', $user_id)->first();
            return strftime("%A %e %B %Y %H:%M", $query->timestamp_expire);
        }
        else
        {
            return false;
        }
    }
  
    /**
    * Get all the transaction from and to users
    *
    * @param string userid
    *
    * @return object of transactions
    */
  
    public static function getTransactions($userid)
    {
        return QueryBuilder::table('cms_user_transactions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('to_user_id', $userid)->Orwhere('from_user_id', $userid)
                  ->join('cms_present_items', 'cms_user_transactions.present_id', '=', 'cms_present_items.id')->orderBy('timestamp', 'desc')->get();
    }
  
    /**
    * Check if the user has already receive a gift
    *
    * @param string userid
    *
    * @return true or false
    */
 
    public static function getUserPresent($userid)
    {
        $query = QueryBuilder::query("SELECT * FROM cms_user_transactions WHERE to_user_id = '".$userid."' and from_user_id = '0' and timestamp >= UNIX_TIMESTAMP(CURDATE())")->get();
        if(count($query) != 0)
        {
            return true;
        }
    }
  
    /**
    * Give the user their daily gift based on items in the present table
    *
    * @param string userid
    *
    * @return object
    */
  
    public static function giveUserPresent()
    {
        if(self::getUserPresent(Auth::returnUserId()) == 0)
        {
            $user = static::FindByid(Auth::returnUserId());
            if($user->startGivePresent())
            {
                return true;
            }
        }
    }
  
    /**
    * Give the user their daily gift based on items in the present table
    *
    * @param string userid
    *
    * @return object
    */
  
    protected function startGivePresent()
    {
        $present = QueryBuilder::query('SELECT * FROM cms_present_items ORDER BY rand()')->first();
        $column = $present->column;

        if($present->type == 'calculate')
        {
            $sum = $present->gift;
        } 
        else if($present->type == 'rand')
        {
            $sum = rand(1,20);
        }
      
        QueryBuilder::table(Emu::Get('tablename.Users'))->where('id', Auth::returnUserId())->update(array($present->column => $this->$column + $sum));

        $data = array(
            'to_user_id' => Auth::returnUserId(),
            'present_id' => $present->id,
            'timestamp' => time(),
            'from_user_id' => 0,
            'gift'  => $sum
        );
        
        return QueryBuilder::table('cms_user_transactions')->insert($data);
    }
  
    /**
    * Count badges by given user in user table
    *
    * @param string userid
    *
    * @return int
    */  
  
    public static function countBadges($userid)
    {
        return queryBuilder::table(Emu::Get('tablename.Users_badges'))->where(Emu::Get('table.Users_badges.user_id'), $userid)->count();
    }
  
    /**
    * Get all badges from given user
    *
    * @param string userid
    *
    * @return object 
    */  
  
    public static function getBadges($userid)
    {
        return queryBuilder::table(Emu::Get('tablename.Users_badges'))->where(Emu::Get('table.Users_badges.user_id'), $userid)->get();
    }
  
    /**
    * Get all badges from given user
    *
    * @param string userid
    *
    * @return object 
    */  
  
    public static function getUsersByRankId($rankid)
    {
        return queryBuilder::table(Emu::Get('tablename.Users'))->select('id')->select(Emu::Get('table.Users.username'))->whereNot('id', 3459)->where('rank', $rankid)->get();
    }
	
    /**
    * Set user settings in array for frontpage in twig. You can use this everywhere without define in the controller
    * You can use it by call {{current_user_information.arraykey}}
    *
    * @param string userid
    *
    * @return array 
    */  
  
    public static function setUserSettings($id)
    {
        $settings = array(
          'username'        => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $id),
          'mail'            => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.mail'), 'id', $id),
          'look'            => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.look'), 'id', $id),
          'points'          => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.points'), 'id', $id),
          'pixels'          => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.pixels'), 'id', $id),
          'account_created' => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.account_created'), 'id', $id),
          'last_login'      => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.last_login'), 'id', $id),
          'last_online'     => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.last_online'), 'id', $id),
          'ip_register'     => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_register'), 'id', $id),
          'ip_current'      => Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_current'), 'id', $id),
          'rank'            => Core::getData(Emu::Get('tablename.Permissions'), Emu::Get('table.Permissions.rank_name'), 'id', Auth::getUser()->rank),
          'rankid'          => Core::getData(Emu::Get('tablename.Users'), 'rank', 'id', $id),
          'is_admin'        => Core::getData('cms_user_settings', 'is_sadmin', 'user_id', Auth::returnUserId())
        );

        return $settings;
    }
    
    /**
    * Remember the login by inserting a new unique token into the remembered_logins table
    * for this user record
    *
    * @return boolean  True if the login was remembered successfully, false otherwise
    */
    
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();
        
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;
        
        $data = array(
            'token_hash'    => $hashed_token,
            'user_id'		=> $this->user_ID,
            'expire_at'		=> date('Y-m-d H:i:s', $this->expiry_timestamp)
        );

        return QueryBuilder::table('remembered_logins')
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                    ->insert($data);
    }
	
    /**
    * Send password reset instructions to the user specified
    *
    * @param string $email The email address
    *
    * @return void
    */
    
	  public static function sendPasswordReset($email)
    {
		    $user = static::emailExists($email);
		    if($user)
        {
			      if($user->startPasswordReset())
            {
				        $user->sendPasswordResetMail();
				        return true;
			      }
		    }
	  }
	
    /**
    * Start the password reset process by generating a new token and expiry
    *
    * @return void
    */
  
    public static function changeSettings($profileBio, $colorfont, $private = 0, $userid)
    {
        $data = array(
            'profile_bio'     => $profileBio,
            'item_font'       => $colorfont,
            'private_profile'	=> $private
        ); 

        return QueryBuilder::table('cms_user_settings')->where('user_id', $userid)->update($data);
    }
    
    /**
    * Password reset, create token and set token in the user settings
    *
    * @param string $email The email address
    *
    * @return void
    */
  
	  protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 2;

        $data = array(
            'user_id' => $this->id,
            'token' => $hashed_token,
            'token_expires_at'	=> date('Y-m-d H:i:s', $this->expiry_timestamp)
        );

        $mail = Emu::Get('table.Users.mail');
        $this->mail = $this->$mail;
        
        return QueryBuilder::table('cms_user_settings')->where('user_id', $this->id)->update($data);
        
	  }
	
    /**
    * Send password reset instructions in an email to the user
    *
    * @return void
    */
    
	protected function sendPasswordResetMail()
    {
        $url	= 'https://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->token;

        $text 	= View::getTemplate('Password/reset_email.txt', ['url' => $url, 'email' => $this->mail]);
        $html 	= View::getTemplate('Password/reset_email.html', ['url' => $url, 'email' => $this->mail]);

        Mail::send($this->mail, 'Wachtwoord reset', $text, $html);
	}



  
    /**
    * Find a user model by password reset token and expiry
    *
    * @param string $token Password reset token sent to user
    *
    * @return mixed User object if found and the token hasn't expired, null otherwise
    */
    
	public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = substr($token->getHash(), 0, -64);

        $query = static::tokenAuth($hashed_token);
	    if($query){
            if (strtotime($query->token_expires_at) > time()){
	            return $query;
            }
        }
	}
	
    /**
    * Reset the password
    *
    * @param string $password The new password
    *
    * @return boolean  True if the password was updated successfully, false otherwise
    */
    
	public function resetPassword($password)
    {
        $this->password = $password;
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $data = array(
            'password'  => $password_hash,
        );

        return QueryBuilder::table(Emu::Get('tablename.Users'))->where('id', $this->id)->update($data);
	}
	
}