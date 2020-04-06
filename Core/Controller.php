<?php
namespace Core;

use \App\Auth;
use \App\Session;
use \App\Flash;
use \App\Config;

use \Core\Loader;

class Controller
{
    protected $models = [];
    protected $route_params = [];

    public function routeParams($route_params){
        $this->route_params = $route_params;
    }

    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function load()
    {
        return new Loader($this);
    }

    protected function before()
    {          
        $this->load()->input();
              
        if(Auth::getUser() && Auth::userActivity()){
            if(Auth::checkBan(Auth::getUser()->username)){
                return true;
            }else{
                Auth::logout();
            }
        }
    }

    protected function after()
    {       
        Auth::rememberRequestedPage();
    }
  
    public function requiredLogin()
    {
        if(!Auth::getUser()){
            Auth::rememberRequestedPage();
            $this->redirect('/');
        }
    }
    
    public function alreadyLoggedIn(){
        if(Auth::getUser()){
            Auth::rememberRequestedPage();
            $this->redirect('/');
        }
    }
    
  
    public function redirect($url){
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }
}
