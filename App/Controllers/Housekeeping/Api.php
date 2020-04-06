<?php
namespace App\Controllers\Housekeeping;

use \Core\View;

use \App\Config;

use \App\Models\User;
use \App\Models\Forum;
use \App\Models\Core;
use \App\Models\Housekeeping;

use \App\Widgets\Spotlight;

use \Libaries\Json;
use \Libaries\Upload;

class Api extends \App\Controllers\Housekeeping\Authenticated
{
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
    
    public function searchPlayerAction()
    {
        $string =  $this->request->get('searchTerm');
      
        if(isset($string))
        {
            $userObject = User::FindAllUsersByString($string);
            foreach($userObject as $user)
            {
                $this->paths[] = array('id' => $user->username, 'text' => $user->username);
            }
            echo Json::encode($this->paths);
        }
        else
        {
            echo Json::encode(array(['id' => 1, 'text' => 'Where are you searching for?']));
        }
    }
  
    public function searchRoleAction()
    {
        $string =  $this->request->get('searchTerm');
      
        $roleObject = Housekeeping::getRoles($string);
        if($roleObject){
            foreach($roleObject as $rank)
            {
                $this->paths[] = array('id' => $rank->id, 'text' => $rank->name);
            }
            echo Json::encode($this->paths);
        }
    }
  
    public function searchPermissionAction()
    {
        $permissionid =  $this->request->get('searchTerm');
        $roleid =  $this->request->get('roleid');
      
        $rankObject = Housekeeping::getPermissions($permissionid);
        if($rankObject)
        {
            foreach($rankObject as $rank)
            {
                if(!Housekeeping::checkIfPermissionExists($roleid, $rank->id))
                {
                    $this->paths[] = array('id' => $rank->id, 'text' => $rank->permission);
                }
            }
            if(empty($this->paths))
            {
                echo Json::encode(array(['id' => 1, 'text' => 'This role has all the permissions they can have']));
            }
            else
            {
                echo Json::encode($this->paths);
            }
        }
    }
  
    public function forumCategorysAction()
    {
        $string =  $this->request->get('searchTerm');
      
        $category = Forum::getCategory();
        if($category){
            foreach($category as $forum)
            {
                $this->paths[] = array('id' => $forum->id, 'text' => $forum->category);
            }
            echo Json::encode($this->paths);
        }
    }
  
    public function setOrderTopicAction()
    {
        if($this->request->all())
        {
            $item = $this->request->input('item');
            
            $i = 0;
            foreach($item as $position)
            {
                Core::updateField('cms_forum_forums', 'position', $i, $position, 'id');
                $i++;
            }
            echo Json::encode(array('status' => 'success', 'msg' => $item));
        }
    }
  
    public function setOrderCatAction()
    {
        if($this->request->all())
        {
            $item = $this->request->input('item');

            $i = 0;
            foreach($item as $position)
            {
                Core::updateField('cms_forum_category', 'position', $i, $position, 'id');
                $i++;
            }
            echo Json::encode(array('status' => 'success', 'msg' => $item));
        }
    }
    
    public function changeForumInputAction()
    {
        if($this->request->all())
        {
            $column = $this->request->input('name');
            $value  = $this->request->input('value');
            $where  = $this->request->input('pk');
          
            if($this->validate->isSuccess())
            {
                return Core::updateField('cms_forum_forums', $column, $value, $where, 'id');
            }
            else
            {
                unset($_SESSION['flash_notifications']);
                echo Json::encode(array('status' => 'error', 'msg' => 'Velden moeten ingevuld zijn!'));
            }
        }
      
    }
  
    public function changeCategoryInputAction()
    {
        if($this->request->all())
        {
            $column = $this->request->input('name');
            $value  = $this->request->input('value');
            $where  = $this->request->input('pk');
          
            if($this->validate->isSuccess())
            {
                return Core::updateField('cms_forum_category', $column, $value, $where, 'id');
            }
            else
            {
                unset($_SESSION['flash_notifications']);
                echo Json::encode(array('status' => 'error', 'msg' => 'Velden moeten ingevuld zijn!'));
            }
        }
           
    }
  
    public function uploadImageAction()
    {
        if($this->request->all())
        {
            $id     = $this->request->input('id');
            $path   = $this->request->input('path');
            $target = $this->request->input('target');
          
            if(!file_exists($path) && $target == 'forumimage')
            {
                $upload = Upload::factory($path);
                $upload->file($_FILES['image']);
              
                $results = $upload->upload();

                Core::updateField('cms_forum_forums', 'image', $results['filename'], $_POST['id'], 'id');    
                echo Json::encode(array('status' => 'success', 'msg' => $path));
            }
            else
            {
                echo Json::encode(array('status' => 'error', 'msg' => $path));
            }
        }
    }
      
    public function getWidgetAction()
    {
        $input = $this->route_params['do'];
      
        $data = new \stdClass();
      
        $data->discord   = Config::get('discord_invite');
        $data->spotlight = Spotlight::set();

        View::renderTemplate("Housekeeping/Widgets/{$input}.html", [
            'data' => $data,
            'permission' => 'housekeeping_cms_tools'
        ]);
        
    }

}

