<?php
namespace App\Widgets;

use \Core\View;
use \App\Auth;
use \App\Models\User;
use \Core\Emu;

use QueryBuilder;
use PDO;

class Tags extends \Core\Model
{
    public static function set(User $user){
        $object = new \stdClass();
        
        $object->alltags = static::getTagObject();
        $object->mytags = static::getTagObject($user->id);  
 
        return $object;  
    }
  
    public static function getByTag($param)
    {
        $lastlogin = Emu::Get('table.Users.last_login');
        return QueryBuilder::table('cms_tags')->select('username', 'tag', 'look', QueryBuilder::raw($lastlogin . ' as last_login'))->join(Emu::Get('tablename.Users'), Emu::Get('tablename.Users') . '.id', '=', 'cms_tags.user_id')->findAll('tag', $param);
    }
  
    public static function getTagObject($userid = null)
    {
        if($userid == null)
        {
            return QueryBuilder::table('cms_tags')->select(QueryBuilder::raw('tag,COUNT(tag) AS MOST_FREQUENT'))->groupBy('tag')->orderBy(QueryBuilder::raw('count(tag)'), 'desc limit 20')->get();
        }
        else
        {
            return QueryBuilder::table('cms_tags')->select('tag')->findAll('user_id', $userid);
        }
    }
  
    public static function addTag($userid, $tag)
    {
        $data = array(
            'user_id' => $userid,
            'tag'     => $tag
        );

        return QueryBuilder::table('cms_tags')->insert($data);
    }
  
    public static function userHasTag($userid, $tag)
    {
        return QueryBuilder::table('cms_tags')->where('user_id', $userid)->where('tag', $tag)->count();
    }
}