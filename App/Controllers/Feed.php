<?php
namespace App\Controllers;

use \Core\Emu;
use \Core\View;

use \App\Auth;
use \App\Flash;

use \App\Models\Core;
use \App\Models\Feeds;
use \App\Models\User;
use \App\Models\Shop;

use \Libaries\Json;

class Feed extends \Core\Controller
{
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
  
    public function homeAction()
    {
        $this->totalPages(Feeds::countAllFeeds());
        
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
        
        View::renderTemplate('Community/feeds.html', [
            'data' => $this->data,
            'currentPage' => $this->data->currentPage,
            'totalPages' => $this->data->totalPages
        ]);
    }
  
    public function timelineAction()
    {
      
    }
    
    public function postFeedTimelineAction()
    {
        if($this->request->all())
        {
            $this->data->reply = $this->request->input('reply');
            $this->validate->name('Bericht')->value($this->data->reply)->min(6)->max(75)->required();
          
            if($this->validate->isSuccess())
            {
                $this->validateReply();
              
                if($this->route_params['page'] == 'timeline')
                {
                    $feedid = Feeds::addFeedToProfile($this->data->reply, Auth::returnUserId(), $this->route_params['id']);
                }else{
                    $feedid = Feeds::addFeed($this->data->reply, Auth::returnUserId());
                }

                Flash::addMessage('Feed geplaatst!');
                $this->redirect(Auth::getReturnToPage() . '#' . $feedid);
            }
        }
        $this->redirect('/feeds');
    }
  
    public function postFeedAction($reply, $feedid)
    {
        $feedid = Core::getData('cms_feeds', 'id', 'id', $feedid);

        if($feedid)
        {
            $userid   = Core::getData('cms_feeds', 'to_user_id', 'id', $feedid);
            $username = Core::getData(Emu::Get('tablename.Users'), 'username', 'id', $userid);

            $this->data->reply = $reply;

            $this->validate->name('Bericht')->value($this->data->reply)->min(6)->max(75)->required();

            if($this->validate->isSuccess())
            {
                $this->validateReply();
              
                Feeds::addReaction($feedid, $this->data->reply, Auth::returnUserId());
                Flash::addMessage('Bericht geplaatst!');
            }
        }
        $this->redirect(Auth::getReturnToPage() . '#' . $feedid);
    }
  
    public function validateReply()
    {
        preg_match_all('/@(\w+)/', $this->data->reply, $match);
        foreach($match[1] as $user)
        {
            $userid = Core::getData(Emu::Get('tablename.Users'), 'id', 'username', $user);
            if($userid != null)
            {
                $userProfile = '@[url=/profile/'.$user.']' .$user . '[/url]';
                $this->data->reply = str_replace("@" . $user, $userProfile, $this->data->reply);
            }
        }
    }
  
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
  
    public function totalPages($totalItems)
    {
        if(isset($this->route_params['page']))
        {
            $currentPage    = $this->route_params['page'];
            $offset         = ($currentPage - 1) * 10;
            $totalPages     = ceil($totalItems / 10);
            $feeds           = Feeds::getAllFeeds(10, $offset);
        }
        else
        {
          $currentPage  = 1;
          $totalPages   = ceil($totalItems / 10);
          $feeds        = Feeds::getAllFeeds(10);
        }
        
        $this->data->feeds = $feeds;
        $this->data->currentPage = $currentPage;
        $this->data->totalPages = $totalPages;
    }
  
}