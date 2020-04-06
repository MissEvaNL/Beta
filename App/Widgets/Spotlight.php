<?php
namespace App\Widgets;

use \Core\Emu;
use \Core\View;

use \App\Models\User;
use \App\Models\Shop;

use QueryBuilder;
use PDO;

class Spotlight extends \Core\Model
{
    public static function set()
    {
        $object = new \stdClass();
        
        $staffObj  = static::getFromSpotlight('staff');
        $userObj   = static::getFromSpotlight('user');

        $object->staff = $staffObj;
        $object->staff->font = Shop::selectItemById($staffObj->item_font);
 
        $object->user = $userObj;
        $object->user->font = Shop::selectItemById($userObj->item_font);
      
        return $object;  
    }
  
    public static function getFromSpotlight($column)
    {
        return QueryBuilder::table('cms_spotlight')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('cms_spotlight.id', 'desc')
                    ->select(Emu::Get('table.Users.username'))->select(Emu::Get('table.Users.look'))->select('message' . $column)->select('cms_user_settings.item_font')
                    ->join(Emu::Get('tablename.Users'), Emu::Get('tablename.Users') . '.id', '=', 'cms_spotlight.' . $column)
                    ->join('cms_user_settings', Emu::Get('tablename.Users'). '.id', '=', 'cms_user_settings.user_id')->first(); 
    }
  
    public static function updateSpotlight($staff, $sdescription, $user, $mdescription)
    {
        $data = array(
            'staff' => $staff,
            'user' => $user,
            'messagestaff' => $sdescription,
            'messageuser' => $mdescription
        );
      
        $count = QueryBuilder::table('cms_spotlight')->where('id', 1)->count();
      
        if($count == 0)
        {         
            QueryBuilder::table('cms_spotlight')->insert($data);
        }
        else
        {
            QueryBuilder::table('cms_spotlight')->where('id', 1)->update($data);
        }
    }
}