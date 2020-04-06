<?php

namespace Core;


class Request {

    private $protected = [
        'password',
        'current_password',
        'new_password'
    ];

    protected $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    protected $allowedSpoofMethods = ['PUT', 'PATCH', 'DELETE'];

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();

        }

        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->$key = $value;
            }

        }

    }
    
    public function getReq(){
        return $_GET;
    }
  
    public function get($param){
        if(isset($_GET[$param]))
        {
            return $_GET[$param];
        }
    }

    public function store()
    {
        $_SESSION['request'] = self::except($this->protected);
    }

    public static function restore()
    {
        if (isset($_SESSION['request'])) {
            $session = $_SESSION['request'];
            unset($_SESSION['request']);

            return $session;
        }

    }

    public function all()
    {
        return (!empty($_POST) ? $_POST : false);
    }

    public function input($field)
    {
        if (!empty($_POST)) {
            return (array_key_exists($field, $_POST) ? $_POST[$field] : false);
        }
    }

    public function only($fields = null)
    {
        if (!empty($_POST)) {
            if (is_array($fields)) {
                foreach ($fields as $input) {
                    if (isset($_POST[$input])) {
                        $post[$input] = $_POST[$input];
                    }
                }
            } else {
                if (isset($_POST[$fields])) {
                    $post[$fields] = $_POST[$fields];
                }
            }
            return (!empty($post) ? $post : false);

        } else {
            return false;
        }
    }

    public function except($fields = null)
    {
        if (!empty($_POST)) {
            $post = $_POST;

            if (is_array($fields)) {
                foreach($post as $key => $value) {
                    foreach($fields as $input) {
                        if ($key == $input) {
                            unset($post[$key]);
                        }
                    }
                }
            } else {
                foreach($post as $key => $value) {
                    if ($key == $fields) {
                        unset($post[$key]);
                    }
                }
            }
            return (!empty($post) ? $post : false);
        } else {
            return false;
        }

    }

    public function url()
    {
        return strtok((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], '?');
    }

    public function fullUrl()
    {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function path()
    {
        return strtok($_SERVER['REQUEST_URI'], '?');

    }

    public function fullPath()
    {
        return $_SERVER['REQUEST_URI'];

    }

    public function query($key = null)
    {
        if ($key !== null)
        {
            parse_str($_SERVER['QUERY_STRING'], $query);
            return (array_key_exists($key, $query) ? $query[$key] : false);
        } else {
            return $_SERVER['QUERY_STRING'];
        }
    }

    public function method()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);

    }

    public function spoof()
    {
        return (!empty($_POST['_method']) && in_array(strtoupper($_POST['_method']), $this->allowedSpoofMethods) ? strtoupper($_POST['_method']) : false);
    }

    public function isMethod($method)
    {
        return (strtoupper($method) == self::method() ? true : false);
    }

    public function isSpoof($method)
    {

        return (strtoupper($method) == self::spoof() ? true : false);
    }

}