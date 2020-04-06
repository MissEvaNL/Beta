<?php
namespace Core;

use \Core\Emu;
use \App\Models\Widget;
use \App\Models\User;
use \App\Models\Core;
use \App\Auth;
use \App\Flash;
use \App\Config;

class View
{

    
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate($template, $args = [])
    {
        echo static::getTemplate($template, $args);
    }
  
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader, array(
              'debug' => true
            ));

            if(Auth::getUser()){
                $twig->addGlobal('widgets', Widget::getWidgets(basename(substr($template, strrpos($template, '/') + 1), '.html')));  
                $twig->addGlobal('current_user_information', User::setUserSettings(Auth::returnUserId()));
                $twig->addGlobal('current_user_permissions', User::userPermissions(Auth::getUser()->rank));
            }
          
            $twig->addExtension(new \Twig_Extension_Debug());
            $twig->addExtension(new \Twig_Extensions_Extension_Date());
            $twig->addExtension(new \Libaries\Bbcode(new \ChrisKonnertz\BBCode\BBCode()));

            $twig->addGlobal('current_user', Auth::getUser());
            $twig->addGlobal('flash_messages', Flash::getMessages());
            $twig->addGlobal('siteurl', Config::get('siteurl'));
            $twig->addGlobal('sitename', Config::get('sitename'));
            $twig->addGlobal('hotelname', Config::get('hotelname'));
            $twig->addGlobal('discord', Config::get('discord_invite'));
            $twig->addGlobal('online', Core::getOnline());
        } 
      
        return $twig->render($template, $args);

    }

  
}
