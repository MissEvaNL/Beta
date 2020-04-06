<?php
namespace App;

class Token {
    protected $token;
    
    public function __construct($token_value  = null)
    {
        if($token_value){
            $this->token = $token_value;
        }else{
            $this->token = bin2hex(random_bytes(16));
        }
    }
    
    public function getValue()
    {
        return $this->token;
    }
    
    public function getHash()
    {
        return hash_hmac('sha512', $this->token, \App\Config::get('secretkey'));
    }
  
    public function authTicket()
    {
        return 'Habbox-'.rand(9,999).'/'.substr(sha1(time()).'/'.rand(9,9999999).'/'.rand(9,9999999).'/'.rand(9,9999999),0,33);

    }
  
    public static function addToken()
    {
      return bin2hex(openssl_random_pseudo_bytes(4));
    }
  
    public function password($password)
    {
      return password_hash($password, PASSWORD_DEFAULT);
    }
}