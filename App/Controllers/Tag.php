<?php
namespace App\Controllers;

use \App\Auth;
use \App\Widgets\Tags;
use \Libaries\Json;
use \Core\View;

class Tag extends Authenticated
{
    public function __construct()
    {
        $this->load()->libaries('validate');
    }
    
    public function allAction()
    {
        $post = $this->request->input('post');

        if($this->request->all())
        {
            $tags = Tags::getByTag($post);
            echo Json::encode(array('tag' => $tags, 'status' => 'success'));
        }
    }
  
    public function addtagAction()
    {
        $post = $this->request->input('post'); 

        if($this->request->all())
        {
            $this->validate->name('tag')->value($post)->pattern('alpha');
            
            if($this->validate->isSuccess())
            {
                if(!Tags::userHasTag(Auth::returnUserId(), $post))
                {
                    Tags::addTag(Auth::returnUserId(), $post);
                    
                    $tagbyme = Tags::getTagObject(Auth::returnUserId());
                    $tags = Tags::getTagObject();

                    echo Json::encode(array('tags' => $tags, 'tagbyme' => $tagbyme, 'message' => 'Je tag is toegevoegd!', 'status' => 'success'));
                }
                else
                {
                    echo Json::encode(array('message' => 'Het lijkt erop dat je deze tag al hebt toegevoegd!', 'status' => 'error'));
                }
            }
            else
            {
                echo Json::encode(array('message' => 'Alleen alpha letters zijn toegestaan!', 'status' => 'error'));
            }
        }
        else
        {
            return $this->redirect('/');
        }
    }
} 