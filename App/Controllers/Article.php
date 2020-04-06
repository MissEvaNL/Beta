<?php
namespace App\Controllers;

use \App\Config;
use \App\Auth;
use \App\Models\News;
use \App\Models\Core;
use \App\Models\User;
use \App\Models\Shop;

use \Libaries\Json;

use \Core\Emu;
use \Core\View;

class Article extends Authenticated
{
    
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
    
    public function overviewAction()
    {  
        $totalItems = News::countAllArticles();
      
        if(isset($this->route_params['page']))
        {
            $currentPage    = $this->route_params['page'];
            $offset         = ($currentPage - 1) * 10;
            $totalPages     = ceil($totalItems / 10);
            $articles       = News::getArticles(10, $offset);
        }
        else
        {
          $currentPage  = 1;
          $totalPages   = ceil($totalItems / 10);
          $articles     = News::getArticles(10);
        }
        
        $this->data->news = $articles;
      
        View::renderTemplate('News/news.html', [
                'data' => $this->data,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
        ]);   
    }
    
    public function articleAction(){
      
        $id = $this->route_params['id'];
        
        if(isset($id))
        {
            $this->data->article = News::getArticleById($id);

            if($this->data->article != null)
            {
                $this->data->article->author   = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $this->data->article->user_id);
              
                $this->data->posts = News::getPostByArticleId($id);
                
                foreach($this->data->posts as $post)
                {
                    $settings       = User::userCmsSettings($post->user_id);
                    
                    $post->author   = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $post->user_id);
                    $post->font     = Shop::selectItemById($settings->item_font);
                }

                View::renderTemplate('News/article.html', [
                        'data' => $this->data
                ]);  
            }
            else
            {
                $this->redirect('/news');
            }

        }
    }
  
    public function replyAction()
    {      
        if($this->request->all())
        {
            $id = $this->request->input('id');
            $message = $this->request->input('message');
            $latestpost = News::latestPostReaction($id);

            $this->validate->name('Je bericht')->value($message)->required()->min(3)->max(30);
            
            if($this->validate->isSuccess()){
                if(News::getArticleById($id))
                {
                    if($latestpost ? $latestpost->user_id != Auth::returnUserId() : true)
                    {
                        News::addReaction($id, Auth::returnUserId(), $message);
                        echo Json::encode(array('message' => 'Je reactie is toegevoegd!', 'status' => 'success'));
                    }
                    else
                    {
                        echo Json::encode(array('message' => 'Dubbelposten is niet toegestaan!', 'status' => 'error'));
                    }
                }
                else
                {
                    echo Json::encode(array('message' => 'Artikel niet gevonden, of reeds verwijderd!', 'status' => 'error'));
                }     
            }
            else
            {
                echo Json::encode(array('messages' => $_SESSION['flash_notifications'], 'status' => 'error'));
                unset($_SESSION['flash_notifications']);
            }
        }
    }
  
    public function deleteAction()
    {
        if($this->request->all())
        {
            $id = $this->request->input('id');
            if(News::deleteByUserAndId(Auth::returnUserId(), $id))
            {
                echo Json::encode(array('message' => 'Je post is verwijderd!', 'status' => 'success'));
            }
        }
    }
}