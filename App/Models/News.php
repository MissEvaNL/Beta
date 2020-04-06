<?php
namespace App\Models;

use \Core\View;
use \Core\Emu;
use \App\Auth;

use QueryBuilder;
use PDO;

class News extends \Core\Model
{
    /**
    * Get all the category that are created.
    * 
    * @access public, 
    * @param int, 
    * @return object
    */

    public static function getArticles($limit = 10, $offset = null)
    {
        return QueryBuilder::table('cms_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->offset($offset)->limit($limit)
                  ->join('cms_news_category', 'cms_news.cat_id', '=', 'cms_news_category.id')->select('cms_news.*')->select('cms_news_category.category')->orderBy('id', 'desc')->get();
    }

    public static function getCategory()
    {
        return QueryBuilder::table('cms_news_category')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->get();
    }
  
    public static function getArticleById($id)
    {
        return QueryBuilder::table('cms_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($id);
    }
  
    public static function getPostByArticleId($id)
    {
        return QueryBuilder::table('cms_news_message')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('id', 'desc')->findAll('news_id', $id);
    }
  
    public static function latestPostReaction($id)
    {
        return QueryBuilder::table('cms_news_message')->where('news_id', $id)->orderBy('id', 'desc')->first();
    }
  
    public static function deleteByUserAndId($userid, $id)
    {
 
        return QueryBuilder::table('cms_news_message')->where('user_id', $userid)->where('id', $id)->delete();
    }
  
    public static function countAllArticles()
    {
        return QueryBuilder::table('cms_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->count();
    }
  
    public static function addReaction($newsid, $userid, $message)
    {
        $data = array(
            'date'      => time(),
            'news_id'   => $newsid,
            'user_id'   => $userid,
            'message'   => $message
        );

        $product = QueryBuilder::table('cms_news_message')->insert($data);
    }
}