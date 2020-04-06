<?php
namespace App\Controllers;

use \Core\Emu;

use \App\Auth;
use \App\Flash;
use \App\Config;

use \App\Models\User;
use \App\Models\Forum;
use \App\Models\Core;
use \App\Models\Shop;
use \App\Models\Feeds;

use \Libaries\Json;

use \Core\View;

class Profile extends Authenticated
{
    public function __construct()
    {
        $this->load()->libaries('validate');
        $this->data = new \stdClass();
    }
    
    public function indexAction()
    {
        $userid = Core::getData(Emu::Get('tablename.Users'), 'id', Emu::Get('table.Users.username'), $this->route_params['username']);

        if(isset($userid))
        {
            $this->data->feeds = Feeds::getFeeds($userid);   
          
            foreach($this->data->feeds as $feed)
            {
                $feed->likes = Feeds::getLikes($feed->id);
                $feed->countreactions = count(Feeds::getFeedReactions($feed->id));
                $feed->reactions = Feeds::getFeedReactions($feed->id);

                $feed->to_username      = Core::getData(Emu::Get('tablename.Users'), 'username', 'id', $feed->to_user_id);
                $feed->from_username    = Core::getData(Emu::Get('tablename.Users'), 'username', 'id', $feed->from_user_id);
                $feed->look             = Core::getData(Emu::Get('tablename.Users'), 'look', 'id', $feed->from_user_id);

                foreach($feed->reactions as $reactions)
                {
                    $settings            = User::userCmsSettings($reactions->user_id);
                    $reactions->username = Core::getData(Emu::Get('tablename.Users'), 'username', 'id', $reactions->user_id);
                    $reactions->look     = Core::getData(Emu::Get('tablename.Users'), 'look', 'id', $reactions->user_id);
                    $reactions->font     = Shop::selectItemById($settings->item_font);
                }
            }
          
            $this->data->profile              = User::FindByName($this->route_params['username']);
            $this->data->profile->badges      = User::getBadges($this->data->profile->id);
            $this->data->profile->countbadges = User::countBadges($this->data->profile->id);
            $this->data->profile->countposts  = Forum::countPostsByUser($this->data->profile->id);
            $this->data->profile->counttopics = Forum::countTopicsByUser($this->data->profile->id);
          
            View::renderTemplate('Profile/home.html', ['data' => $this->data]);
            
        }
        else
        {
            return $this->redirect('/');
        }
    }
	
    /**
    * Like a post by another user. Also check if user already like the post
    *
    * @return true or false
    */
  
    public function likeAction()
    {
        $post = $this->request->input('post');

        if($this->request->all())
        {
            if(Feeds::userAlreadylikePost($post, Auth::returnUserId()))
            {
                echo Json::encode(array('status' => 'liked'));
            }
            else
            {
                Feeds::insertLike($post, Auth::returnUserId());
                echo Json::encode(array('status' => 'success'));
            }
        }
        else
        {
            $this->redirect('/');   
        }
    }
  
    public function postFeedAction()
    {
        if($this->request->all())
        {
            $feed = new \App\Controllers\Feed;
            $feed->postFeedAction($this->request->input('reply'), $this->route_params['feedid']);
        }
        else
        {
            $this->redirect('/');
        }
    }
  
} 