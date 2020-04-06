<?php
namespace App;

class Session
{

    private static $prefix = 'habbox_';

    private static $sessionStarted = false;

    public static function setPrefix($prefix)
    {
        return is_string(self::$prefix = $prefix);
    }

    public static function getPrefix()
    {
        return self::$prefix;
    }

    public static function init($lifeTime = 0)
    {
        if (self::$sessionStarted == false) 
        {
            session_start();
            return self::$sessionStarted = true;
        }
        return false;
    }

    public static function set($key, $value = false)
    {
        if (is_array($key) && $value == false) 
        {
            foreach ($key as $name => $value) 
            {
                $_SESSION[self::$prefix . $name] = $value;
            }
        } 
        else
        {
            $_SESSION[self::$prefix . $key] = $value;
        }
        return true;
    }

    public static function pull($key)
    {
        if (isset($_SESSION[self::$prefix . $key]))
        {
            $value = $_SESSION[self::$prefix . $key];
            unset($_SESSION[self::$prefix . $key]);
            return $value;
        }
        return null;
    }

    public static function get($key = '', $secondkey = false)
    {
        $name = self::$prefix . $key;
      
        if (empty($key)) 
        {
            return isset($_SESSION) ? $_SESSION : null;
        } 
        elseif ($secondkey == true)
        {
            if (isset($_SESSION[$name][$secondkey])) 
            {
                return $_SESSION[$name][$secondkey];
            }
        }
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }
 
    public static function id()
    {
        return session_id();
    }
  
    public static function regenerate()
    {
        session_regenerate_id(true);
        return session_id();
    }
  
    public static function destroy($key = '', $prefix = false)
    {
        if (self::$sessionStarted == true) 
        {
            if ($key == '' && $prefix == false) 
            {
                session_unset();
                session_destroy();
            } 
            elseif ($prefix == true) 
            {
                foreach ($_SESSION as $index => $value) 
                {
                    if (strpos($index, self::$prefix) === 0)
                    {
                        unset($_SESSION[$index]);
                    }
                }
            } 
            else 
            {
                unset($_SESSION[self::$prefix . $key]);
            }
            return true;
        }
        return false;
    }
}