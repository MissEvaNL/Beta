<?php
namespace App\Widgets;

use \Core\Emu;
use \Core\View;

use \App\Auth;
use \App\Config;

use \App\Models\User;
use \App\Models\Forum;
use \App\Models\Core;

use QueryBuilder;
use PDO;

class Latestforum extends \Core\Model
{
    public static function set(User $user){
        $object = new \stdClass();
        
        $object->latestposts = static::getLatestPosts();
        foreach($object->latestposts as $user)
        {
            $user->look = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.look'), 'id', $user->user_id);
        }
      
        $object->latesttopics = static::getLatestTopics();
        
        return $object;  
    }
  
    public static function getLatestPosts()
    {
        return Forum::WidgetLatestPosts(Config::get('latestpostslimit'));
    }
    
    public static function getLatestTopics()
    {
        return Forum::WidgetLatestTopics(Config::get('latestpostslimit'));
    }
  
}