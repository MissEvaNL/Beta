<?php
namespace App\Models;

use \Core\View;
use \Core\Emu;
use \App\Models\User;
use \App\Models\Core;

use QueryBuilder;
use PDO;

class Feeds extends \Core\Model
{
  
    public static function getFeeds($userid)
    {
      
        $feeds = QueryBuilder::table('cms_feeds')->where('to_user_id', $userid)->orderBy('id', 'desc')->get();

        foreach($feeds as $feed)
        {
            $feed->from_user = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $feed->from_user_id);
        }
      
        return $feeds ?? null;
    }
  
    public static function getAllFeeds($limit = 10, $offset = null)
    {
        return QueryBuilder::table('cms_feeds')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->offset($offset)->limit($limit)->orderBy('id', 'desc')->get();
    } 
  
    public static function getFeedReactions($feedid)
    {
        return QueryBuilder::table('cms_feeds_reactions')->where('feed_id', $feedid)->get();
    }
      
    public static function countAllFeeds()
    {
        return QueryBuilder::table('cms_feeds')->count();
    }
  
    public static function getLikes($feedid)
    {
        return QueryBuilder::table('cms_feeds_likes')->where('feed_id', $feedid)->count();
    }
 
    public static function userAlreadylikePost($feedid, $userid)
    {
        return QueryBuilder::table('cms_feeds_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('feed_id', $feedid)->where('user_id', $userid)->count();
    }
  
    /**
    * Like post by userid
    * 
    * @access public, 
    * @param int $postid, $userid, 
    * @return int
    */
  
    public static function insertLike($feedid, $userid)
    {
        $data = array(
            'feed_id'     => $feedid,
            'user_id'     => $userid
        );

        return QueryBuilder::table('cms_feeds_likes')->insert($data);
    }   
  
    public static function addReaction($feedid, $message, $userid)
    {
        $data = array(
            'feed_id'     => $feedid,
            'message'     => $message,
            'timestamp'   => time(),
            'user_id'     => $userid
        );

        return QueryBuilder::table('cms_feeds_reactions')->insert($data);
    }
  
    public static function addFeed($message, $userid)
    {
        $data = array(
            'to_user_id'  => $userid,
            'message'     => $message,
            'timestamp'   => time(),
            'from_user_id' => $userid
        );

        return QueryBuilder::table('cms_feeds')->insert($data);
    }
  
    public static function addFeedToProfile($message, $userid, $touser)
    {
        $data = array(
            'to_user_id'  => $touser,
            'message'     => $message,
            'timestamp'   => time(),
            'from_user_id' => $userid
        );

        return QueryBuilder::table('cms_feeds')->insert($data);
    }
} 