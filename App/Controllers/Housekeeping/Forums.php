<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Flash;
use \App\Models\Core;
use \App\Models\Forum;
use \App\Models\User;
use \App\Models\Housekeeping;

use \Libaries\Json;

class Forums extends \App\Controllers\Housekeeping\Authenticated
{
  
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
  
    public function manageAction()
    {
        if($this->request->all() || isset($this->route_params['id']))
        {
            $catid = $this->request->input('element') ?? $this->route_params['id'];
            $forum = Forum::getForums($catid);
            
            foreach($forum as $topic)
            {
                $topic->count_topics = count(Forum::getForumTopics($topic->id));
                $topic->count_posts  = Forum::countForumPosts($topic->id);

                if($topic->count_topics > 0)
                {
                    $topic->latest_topic            = Forum::getForumLatestTopic($topic->id);
                    $topic->latest_topic->author    = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $topic->latest_topic->user_id); 
                    $topic->latest_topic->font      = $topic->latest_topic->user_id;
                }
            }
            
            $this->data->catid  = $catid;
            $this->data->forums = $forum;
        }
        else
        {
            $this->data->category = Forum::getCategory();
        }
        
        View::renderTemplate('Housekeeping/Management/forums.html', [
            'permission' => 'housekeeping_staff_logs',
            'data' => $this->data
        ]);
    }
  
    public function createAction()
    {
        if($this->request->all())
        {
            $category   = $this->request->input('category');
            $topic      = $this->request->input('topic');
            $catid      = $this->request->input('catid');
            
            if(!empty($category))
            {
                $this->validate->name('Categorie')->value($category)->min(4)->max(50)->required();
                
                if($this->validate->isSuccess())
                {
                    Forum::insertCategory($category);
                    Flash::addMessage('Categorie is aangemaakt');
                    Core::logger('Created a category in forums ', 'created');
                    $this->redirect('/housekeeping/manage/forums');
                }
            }else if(!empty($topic))
            {
                $this->validate->name('Topic')->value($topic)->min(4)->max(50)->required();
                
                if($this->validate->isSuccess())
                {
                    Forum::insertTopic($catid, $topic);
                    Flash::addMessage('Topic is aangemaakt');
                    Core::logger('Created a topic in forums ', 'created');
                    $this->redirect('/housekeeping/manage/forums/catid/' . $catid);
                }
            }
        }
        else
        {
            $this->redirect('/');
        }
    }
    
    public function deleteAction()
    {
        if($this->route_params['do'] == 'category')
        {
            $catid = Core::getData('cms_forum_category', 'id', 'id', $this->route_params['id']); 
            if($catid)
            {
                if(Forum::deleteCategory($catid)){
                    Flash::addMessage('Categorie is verwijderd');
                    Core::logger('Deleted a category in forums ', 'delete');
                    $this->redirect('/housekeeping/manage/forums');
                }
            }
            else
            {
                $this->redirect('/housekeeping/forums/manage');
            }
        }else if($this->route_params['do'] == 'forum')
        {
            $topicid = Core::getData('cms_forum_forums', 'id', 'id', $this->route_params['id']); 
            if($topicid)
            {
                if(Forum::deleteForum($topicid)){
                    Flash::addMessage('Forum is verwijderd');
                    Core::logger('Deleted a forum in forums ', 'delete');
                    $this->redirect('/housekeeping/manage/forums');
                }
            }
            else
            {
                $this->redirect('/housekeeping/forums/manage');
            }
        }else{
          $this->redirect('/housekeeping');
        }

    }
  
}