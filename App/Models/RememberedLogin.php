<?php
namespace App\Models;

use \App\Token;
use QueryBuilder;
use PDO;

class RememberedLogin extends \Core\Model
{
    
    /**
    * Find a remembered login model by the token
    *
    * @param string $token The remembered login token
    *
    * @return mixed Remembered login object if found, false otherwise
    */
    
    public static function findByToken($token)
    {
        $token = new Token($token);
        $token_hash = substr($token->getHash(), 0, -64);
        
        $query = QueryBuilder::table('remembered_logins')
            ->select('*')
            ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
            ->where('token_hash', '=', $token_hash);
       
        
        return $query->numRows();
    }
    
    /**
    * Get the user model associated with this remembered login
    *
    * @return User The user model
    */
    
    public function getUser()
    {
        return User::findById($this->user_id);
    }
    
    /**
    * See if the remember token has expired or not, based on the current system time
    *
    * @return boolean True if the token has expired, false otherwise
    */
    
    public function hasExpired()
    {
        return strtotime($this->expire_at) < time();
    }
    
    /**
    * Delete this model
    *
    * @return void
    */
    
    public function delete()
    {
        QueryBuilder::table('remembered_logins')      
            ->where('token_hash', '=', $this->token_hash)
            ->delete();
    }
}