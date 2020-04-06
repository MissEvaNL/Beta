<?php
namespace App\Controllers;

use \Core\View;
use \Core\Emu;
use \App\Auth;
use \App\Flash;
use \App\Config;
use \App\Models\User;
use \App\Models\Core;
use \App\Models\Forum;
use \App\Models\Shop;
use \Libaries\Json;

class Forums extends Authenticated
{
    public function __construct(){
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
    
    /**
    * Shows forum index, get all categorys and forums
    *
    * @return object of categorys and forums
    */
    
    public function indexAction()
    {
        $forums = Forum::getCategory();

        foreach($forums as $category)
        {
            $category->forum = Forum::getForums($category->id);

            foreach($category->forum as $forum)
            {
                $forum->count_topics = count(Forum::getForumTopics($forum->id));
                $forum->count_posts  = Forum::countForumPosts($forum->id);
                
                if($forum->count_topics > 0)
                {
                    $forum->latest_topic            = Forum::getForumLatestTopic($forum->id);
                    $forum->latest_topic->author    = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $forum->latest_topic->user_id); 
                  
                    $topicFont = User::userCmsSettings($forum->latest_topic->user_id);
                    $forum->latest_topic->font = Shop::selectItemById($topicFont->item_font);
                }
            }
        }
          
        $this->data->forums = $forums;

        View::renderTemplate('Forum/forum.html', [
            'data' => $this->data
        ]);
    }
    
    /**
    * Shows category index and get all topics by category id
    *
    * @return object of topics and latest post information
    */
  
    public function categoryAction()
    {
        $topics = Forum::getForumByCatId($this->route_params['id']);
      
        if(!$topics){
            $this->redirect(Auth::getReturnToPage());
        }
        
        $topics->count_topics   = count(Forum::getForumTopics($topics->id));
        $topics->topics         = Forum::getForumTopics($topics->id);
      
        foreach($topics->topics as $topic)
        {

            if ($topics->count_topics > 0) 
            {
                $topic->latest_post = Forum::getLatestPostByTopic($topic->id);
                $topic->count_posts = Forum::countPostsByTopic($topic->id);
              
                $topicFont = User::userCmsSettings($topic->user_id);

                if($topic->latest_post)
                {
                    $postFont = User::userCmsSettings($topic->latest_post->user_id);

                    $topic->latest_post->author = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $topic->latest_post->user_id);
                    $topic->latest_post->font = Shop::selectItemById($postFont->item_font);
                }
                
                $topic->look    = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.look'), 'id', $topic->user_id);
                $topic->author  = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $topic->user_id);
                $topic->font    = Shop::selectItemById($topicFont->item_font);
                
            }
        }     
     
        $this->data->topics = $topics;

        View::renderTemplate('Forum/category.html', [
            'data' => $this->data   
        ]);
    }
  
    /**
    * Shows forum index, get all categorys and forums
    *
    * @return object of categorys and forums
    */
    
    public function topicAction()
    {
        $topic    = Forum::getTopicByForumId($this->route_params['id']);
      
      
        if(!$topic){
            $this->redirect(Auth::getReturnToPage());
        }
        
        $forum      = Forum::getForumByCatId($topic->forum_id);
        $settings   = User::userCmsSettings($topic->user_id);
        $totalItems = Forum::countAllPostsInTopic($this->route_params['id']);
      
        if(isset($this->route_params['page']))
        {
            $currentPage  = $this->route_params['page'];
            $offset       = ($currentPage - 1) * Config::get('postslimit');
            $totalPages   = ceil($totalItems / Config::get('postslimit'));
            $posts        = Forum::getPostsByTopicId($this->route_params['id'], Config::get('postslimit'), $offset);
        }
        else
        {
          $currentPage  = 1;
          $totalPages   = ceil($totalItems / Config::get('postslimit'));
          $posts        = Forum::getPostsByTopicId($this->route_params['id'], Config::get('postslimit'));
        }

        $posts        = Forum::getPostsByTopicId($this->route_params['id'], Config::get('postslimit'));
  
        foreach ($posts as $post)
        {
            
            $post->author   = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $post->user_id);
            $post->look     = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.look'), 'id', $post->user_id);
            $post->signup   = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.account_created'), 'id',  $post->user_id);     
            $post->rank     = Core::getData(Emu::Get('tablename.Users'), 'rank', 'id', $post->user_id);

            $post->posts    = Forum::countPostsByUser($post->user_id);
            $post->likes    = Forum::likesByPost($post->id);
          
            //* Select from itemshop
            $post->avatar   = Shop::selectItemById($settings->item_avatar);
            $post->font     = Shop::selectItemById($settings->item_font);
          
            //* Shop addons */
            $post->shopAddons = Shop::shopAddons($post->user_id);
                                                      
        }
      
        $this->data->forum = $forum;
        $this->data->topic = $topic;
        $this->data->posts = $posts;
        
        if(!$topic->is_closed){
            $reply = true;
        }else{
            $reply = false;
        }
      
        View::renderTemplate('Forum/topic.html', [
              'data' => $this->data,
              'reply' => $reply,
              'currentPage' => $currentPage,
              'totalPages' => $totalPages,
              'modrank' => Config::get('rangmodtools')
        ]);
    }
    
    /**
    * Create topic in given category
    *
    * @return display template
    */
    
    public function createTopicAction()
    {
        $topics = Forum::getForumByCatId($this->route_params['id']);
      
        if($this->request->all())
        {
            $topictitle     = $this->request->input('title');
            $topicmessage   = $this->request->input('message');

            $this->validate->name('Titel')->value($topictitle)->required()->min(6)->max(50);
            $this->validate->name('Bericht')->value($topicmessage)->required();
            
            if($this->validate->isSuccess())
            {
                $id = Forum::createTopic($topics->id, $topictitle, $topicmessage, Auth::returnUserId());
                Flash::addMessage('Je topic is aangemaakt!');
                $this->redirect('/forum/thread/' . $id);
            }else
            {
                $this->redirect('/forum/category/' . $topics->id .'/new'); 
            }
        }else
        {
            $this->redirect(Auth::getReturnToPage());    
        }
        
    }
    
    /**
    * View create topic
    *
    * @return display template
    */
  
    public function createTopicViewAction()
    {
        $topics = Forum::getForumByCatId($this->route_params['id']);
        
        if(!$topics){
            $this->redirect(Auth::getReturnToPage());
        }
        
        $this->data->topics = $topics;

        View::renderTemplate('Forum/create_topic.html', [
             'data' => $this->data
        ]);  
    }
    
    /**
    * Post handler when user reply in a topic with some checks
    *
    * @return true or false
    */
  
    public function postReplyAction()
    {
        $topics = Forum::getTopicByForumId($this->route_params['id']);
        $forum = Forum::getForumByCatId($topics->forum_id);
      
        if($this->request->all())
        {
            $postmessage = $this->request->input('reply');
            
            $this->validate->name('Bericht')->value($postmessage)->required()->min(10);
                    
            if($this->validate->isSuccess())
            {
                $spamfilter = Forum::postSpamFilter($topics->id, Auth::returnUserId());
                //use $spamtime when machine time is -1 hour. 
                if($spamfilter != null)
                {
                    $spamtime = date("Y-m-d H:i:s", strtotime($spamfilter->created_at) + Config::get('spamlimit'));
                }
                else
                {
                    $spamtime = null;
                }
                
                if(strtotime($spamtime) < time() || $spamtime = null)
                {
                    $postid = Forum::createPost($topics->id, $postmessage, Auth::returnUserId());
                    Flash::addMessage('Je bericht is geplaatst!');
                    $this->redirect(Auth::getReturnToPage() . '#last');   
                }
                else
                {                
                    Flash::addMessage('Oops.. dubbelpost gedetecteerd!', 'error');
                    $this->redirect(Auth::getReturnToPage() . '#last'); 
                }
            }
            else
            {
                $this->redirect(Auth::getReturnToPage() . '#last'); 
            }
        }
        else
        {
            $this->redirect(Auth::getReturnToPage());    
        }
    }
    
    /**
    * Edit post by user
    *
    * @return true or false
    */
  
    public function editAction()
    {
        $id         = $this->route_params['id'];
        $post       = Forum::getPostByPostId($id);
        $content    = $this->request->input('content');
        $user       = Auth::getUser();
        
        if($this->request->all())
        {
            $this->validate->name('Bericht')->value($content)->required()->min(10);
            
            if($this->validate->isSuccess())
            {
                
                if($post->user_id == Auth::returnUserId() || $user->rank >= Config::get('rangmodtools'))
                {
                    Forum::updatePostById($id, $content);
                    Flash::addMessage('Je bericht is aangepast!');
                    $this->redirect('/forum/thread/' . $post->topic_id . '#' . $post->id);
                }
                else
                {
                    Flash::addMessage('Foutmelding', 'Dit bericht heb jij niet gepost!', FLASH::ERROR);
                    $this->redirect('/forum/thread/' . $post->topic_id);
                }
            }
            else
            {
                $this->redirect('/forum/thread/' . $post->topic_id);
            }
        }
        else
        {
            if($post != null)
            {
                if($post->user_id == Auth::returnUserId() || $user->rank >= Config::get('rangmodtools'))
                {
                    View::renderTemplate('Forum/edit.html', [
                         'post' => $post
                    ]);   
                }
                else{
                    Flash::addMessage('Foutmelding', 'Dit bericht heb jij niet gepost!', FLASH::ERROR);
                }
            }
            else
            {
                Flash::addMessage('Foutmelding', 'Dit bericht heb jij niet gepost!', FLASH::ERROR); 
                $this->redirect(Auth::getReturnToPage());   
            }   
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
            if(Forum::userAlreadylikePost($post, Auth::returnUserId()))
            {
                echo Json::encode(array('status' => 'error'));
            }
            else
            {
                Forum::insertLike($post, Auth::returnUserId());
                echo Json::encode(array('status' => 'success', 'data' => Forum::likesByPost($post)));
            }
        }
        else
        {
            $this->redirect('/forum');   
        }
    }
    
    /**
    * Close topic, only if user has the right forum permissions
    *
    * @return true or false
    */
    
    public function closeAction()
    {
        $topicid = $this->request->input('data');

        if($this->request->all())
        {
            $user = Auth::getUser();
            if($user->rank >= Config::get('rangmodtools'))
            {
                Forum::closeThread($topicid);
                echo Json::encode(array('status' => 'success'));
            }
            else
            {
                echo Json::encode(array('status' => 'error'));
            }
        }
        else
        {
            $this->redirect(Auth::getReturnToPage());   
        }
    }
    
    /**
    * Make topic sticky, only if the user has the right permissions
    *
    * @return true or false
    */
    
    public function stickyAction()
    {
        $topicid = $this->request->input('data');

        if($this->request->all())
        {
            $user = Auth::getUser();
            if($user->rank >= Config::get('rangmodtools'))
            {
                Forum::stickyThread($topicid);
                echo Json::encode(array('status' => 'success'));
            }
            else
            {
                echo Json::encode(array('status' => 'error'));
            }
        }
        else
        {
            $this->redirect(Auth::getReturnToPage());   
        }
    }
    
    /**
    * delete topic, only if the user has the right permissions
    *
    * @return true or false
    */
    
    public function deleteAction()
    {
        $topicid = $this->request->input('data');
        if($this->request->all())
        {
            $user = Auth::getUser();
            if($user->rank >= Config::get('rangmodtools'))
            {
                Forum::deleteThread($topicid);
                echo Json::encode(array('status' => 'success'));
            }
            else
            {
                echo Json::encode(array('status' => 'error'));
            }
        }
        else
        {
            $this->redirect(Auth::getReturnToPage());   
        }
    }
} 