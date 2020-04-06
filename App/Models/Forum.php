<?php
namespace App\Models;

use \Core\View;
use \Core\Emu;
use \App\Auth;

use QueryBuilder;
use PDO;

class Forum extends \Core\Model
{
    /**
    * Get all the category that are created.
    * 
    * @access public, 
    * @param int, 
    * @return object
    */

    public static function getCategory()
    {
        return QueryBuilder::table('cms_forum_category')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('position', 'asc')->get();
    }
  
    /**
    * Get all the forums order by category
    * 
    * @access public, 
    * @param int $catjd, 
    * @return object
    */

    public static function getForums($catid)
    {
        return QueryBuilder::table('cms_forum_forums')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('cat_id', $catid)->orderBy('position', 'asc')->get();
    }
  
    /**
    * Get the forum by category id
    * 
    * @access public, 
    * @param int $catid, 
    * @return single object
    */
  
    public static function getForumByCatId($catid)
    {
        return QueryBuilder::table('cms_forum_forums')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $catid)->first();
    }

    /**
    * Get topic by forum id
    * 
    * @access public, 
    * @param int $catid, 
    * @return single object
    */
  
    public static function getTopicByForumId($catid)
    {
        return QueryBuilder::table('cms_forum_topics')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $catid)->where('is_visible', 1)->first();
    }
  
    /**
    * Get all the posts by topic id
    * 
    * @access public, 
    * @param int $topicid, 
    * @return object
    */
  
    public static function countAllPostsInTopic($catid)
    {
        return QueryBuilder::table('cms_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $catid)->count();
    }
  
    /**
    * Get all the posts by topic id with given limit and offset
    * 
    * @access public, 
    * @param int $topicid, 
    * @return object
    */
  
    public static function getPostsByTopicId($catid, $limit, $offset = null)
    {
        return QueryBuilder::table('cms_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $catid)->offset($offset)->limit($limit)->get();
    }
    /**
    * get_forum_topics function.
    * 
    * @access public, 
    * @param int $forum_id, 
    * @return array of objects
    */
    
    public static function getForumTopics($forumid)
    {
        return QueryBuilder::table('cms_forum_topics')->orderBy('is_sticky', 'DESC')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('forum_id', $forumid)->where('is_visible', '1')->get();
    }

    /**
    * get_forum_latest_topic function.
    * 
    * @access public, 
    * @param int $forum_id, 
    * @return object
    */
    
    public static function getForumLatestTopic($forumid)
    {
        return QueryBuilder::table('cms_forum_topics')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('forum_id', $forumid)->orderBy('created_at', 'DESC')->limit(1)->first();
    }
  
    /**
    * Get all the posts posted in topic.
    * 
    * @access public, 
    * @param int $topicid, 
    * @return object
    */
  
    public static function getLatestPostByTopic($topicid)
    {
        return QueryBuilder::table('cms_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $topicid)->orderBy('id', 'DESC')->first();
    }
  
    /**
    * Get all the posts posted in topic.
    * 
    * @access public, 
    * @param int $topicid, 
    * @return object
    */
  
    public static function WidgetLatestPosts($limit)
    {

        return QueryBuilder::table('cms_forum_posts')->select(Emu::Get('tablename.Users') . '.' . Emu::Get('table.Users.username'))->select('cms_forum_topics.title')
                      ->select('cms_forum_posts.user_id')->select('cms_forum_posts.created_at')->select('cms_forum_posts.id')->select('cms_forum_posts.topic_id')
                      ->select('cms_shop_items.image')
                      ->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('cms_forum_posts.created_at', 'DESC')->limit($limit)
                      ->join('cms_forum_topics', 'cms_forum_posts.topic_id', '=', 'cms_forum_topics.id')
                      ->LeftJoin('cms_user_settings', 'cms_forum_posts.user_id', '=', 'cms_user_settings.user_id')
                      ->LeftJoin('cms_shop_items', 'cms_user_settings.item_font', '=', 'cms_shop_items.id')
                      ->join(Emu::Get('tablename.Users'), 'cms_forum_posts.user_id',  '=', Emu::Get('tablename.Users') . '.id')
                      ->whereNull('cms_forum_posts.is_topic')->where('cms_forum_topics.is_visible', 1)->get();
    }
  
    public static function WidgetLatestTopics($limit)
    {
      
      return QueryBuilder::table('cms_forum_topics')->select(Emu::Get('tablename.Users') . '.' . Emu::Get('table.Users.username'))->select('cms_forum_topics.title')
                    ->select('cms_forum_topics.user_id')->select('cms_forum_topics.created_at')->select('cms_forum_topics.id')
                    ->select('cms_shop_items.image')
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('cms_forum_topics.created_at', 'DESC')->limit($limit)
                    ->LeftJoin('cms_user_settings', 'cms_forum_topics.user_id', '=', 'cms_user_settings.user_id')
                    ->LeftJoin('cms_shop_items', 'cms_user_settings.item_font', '=', 'cms_shop_items.id')
                    ->join(Emu::Get('tablename.Users'), 'cms_forum_topics.user_id',  '=', Emu::Get('tablename.Users') . '.id')->where('cms_forum_topics.is_visible', 1)->get();
    }
  
    /**
    * Get all the posts posted in topic.
    * 
    * @access public, 
    * @param int $topicid, 
    * @return object
    */
  
    public static function getPostByPostId($postid)
    {
        return QueryBuilder::table('cms_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $postid)->first();
    }
  
    /**
    * Count all the posts in a topic.
    * 
    * @access public, 
    * @param int $topicid, 
    * @return int
    */
  
    public static function countPostsByTopic($topicid)
    {
        return QueryBuilder::table('cms_forum_posts')->select('id')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $topicid)->count();
    }
  
    /**
    * Check if user already like the post
    * 
    * @access public, 
    * @param int $userid, 
    * @return int
    */
  
    public static function userAlreadylikePost($postid, $userid)
    {
        return QueryBuilder::table('cms_forum_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('post_id', $postid)->where('user_id', $userid)->count();
    }
  
    /**
    * Count all posts and topics by userid.
    * 
    * @access public, 
    * @param int $userid, 
    * @return int
    */
  
    public static function countPostsByUser($userid)
    {
        return QueryBuilder::table('cms_forum_posts')->select('id')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $userid)->count();
    }
  
    public static function countTopicsByUser($userid)
    {
        return QueryBuilder::table('cms_forum_topics')->select('id')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $userid)->count();
    }
  
    /**
    * Count all the forum posts
    * 
    * @param int $forumid, 
    * @return int
    */
    
    public static function countForumPosts($forumid)
    {
        return QueryBuilder::table('cms_forum_posts')
                    ->select('cms_forum_posts.id')                    
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                    ->join('cms_forum_topics', 'cms_forum_posts.topic_id', '=', 'cms_forum_topics.id')
                    ->where('cms_forum_topics.forum_id', $forumid)
                    ->count();
    }
  
    /**
    * Create topic 
    * 
    * @param forumid, title, content and userid
    * @return created topicid and postid
    */
  
    public static function createTopic($forumid, $title, $content, $userid)
    {
        $topic = array(
            'title'       => $title,
            'created_at'  => date('Y-m-j H:i:s'),
            'user_id'		  => $userid,
            'forum_id'    => $forumid,
        );

        $topic = QueryBuilder::table('cms_forum_topics')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($topic);
      
        $post = array(
            'content'     => $content,
            'user_id'		  => $userid,
            'topic_id'    => $topic,
            'created_at'  => date('Y-m-j H:i:s'),
            'is_topic' => 1
        );
        
        QueryBuilder::table('cms_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($post);
        return $topic;
    }  
  
    /**
    * Create post by a array
    * 
    * @access public, 
    * @param int $topicid, $content, $userid
    * @return int
    */
  
    public static function createPost($topicid, $content, $userid)
    {
        $data = array(
            'content'     => $content,
            'user_id'     => $userid,
            'topic_id'	  => $topicid,
            'created_at'  => date('Y-m-j H:i:s')
        );

        return QueryBuilder::table('cms_forum_posts')->insert($data);
    } 
  
    /**
    * Like post by userid
    * 
    * @access public, 
    * @param int $postid, $userid, 
    * @return int
    */
  
    public static function insertLike($postid, $userid)
    {
        $data = array(
            'post_id'     => $postid,
            'user_id'     => $userid
        );

        return QueryBuilder::table('cms_forum_likes')->insert($data);
    }   
  
    /**
    * Update when user try to edit his post
    * 
    * @access public, 
    * @param int $postid, $content, 
    * @return true or false
    */
  
    public static function updatePostByid($postid, $content)
    {
        $data = array(
            'content'     => $content,
            'updated_at'  => time(),
        );

        return QueryBuilder::table('cms_forum_posts')->where('id', $postid)->update($data);
    }  
  
  
    /**
    * Insert category post by a array
    * 
    * @access public, 
    * @param int $topicid, $content, $userid
    * @return int
    */
  
    public static function insertCategory($catid)
    {
        $data = array(
            'category'     => $catid,
        );

        return QueryBuilder::table('cms_forum_category')->insert($data);
    } 
  
    public static function insertTopic($catid, $title)
    {
        $data = array(
            'title' => $title,
            'created_at' => time(),
            'cat_id' => $catid
        );

        return QueryBuilder::table('cms_forum_forums')->insert($data);
    } 
  
    public static function deleteCategory($catid)
    {
        return QueryBuilder::table('cms_forum_category')->where('id', $catid)->delete();
    }
  
    public static function deleteForum($topicid)
    {
        return QueryBuilder::table('cms_forum_forums')->where('id', $topicid)->delete();
    }
  
    /**
    * Close thread by topicid
    * 
    * @access public, 
    * @param int $topicid, 
    * @return true or false
    */
  
    public static function closeThread($topicid)
    {
        return QueryBuilder::query('UPDATE cms_forum_topics SET is_closed = 1 - is_closed WHERE id = "'. $topicid .'"');
    }
  
    /**
    * Sticky thread by topicid
    * 
    * @access public, 
    * @param int $topicid, 
    * @return true or false
    */
  
    public static function stickyThread($topicid)
    {
        return QueryBuilder::query('UPDATE cms_forum_topics SET is_sticky = 1 - is_sticky WHERE id = "'. $topicid .'"');
    }
  
    /**
    * Delete thread by topicid
    * 
    * @access public, 
    * @param int $topicid, 
    * @return true or false
    */
  
    public static function deleteThread($topicid)
    {
        return QueryBuilder::query('UPDATE cms_forum_topics SET is_visible = 1 - is_visible WHERE id = "'. $topicid .'"');
    }
  
    /**
    * Check latest user post in topic when user try to post
    * 
    * @access public, 
    * @param int $topicid, $userid
    * @return latestpost
    */
    
    public static function postSpamFilter($topicid, $userid)
    {
        return QueryBuilder::table('cms_forum_posts')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('topic_id', $topicid)->where('user_id', $userid)->orderBy('id', 'DESC')->first();
    }
    
    /**
    * Return all the likes by user from posts
    * 
    * @access public, 
    * @param int $postid, 
    * @return object
    */
  
    public static function likesByPost($postid)
    {
        return QueryBuilder::table('cms_forum_likes')->select(Emu::Get('table.Users.username'))->where('post_id', $postid)
                ->join(Emu::Get('tablename.Users'), Emu::Get('tablename.Users') . '.id', '=', 'cms_forum_likes.user_id')->orderBy('cms_forum_likes.id', 'desc')->get();
       
    }
}