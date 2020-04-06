<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Auth;
use \App\Flash;
use \App\Token;

use \App\Models\News;
use \App\Models\Core;
use \App\Models\Housekeeping;

class Article extends \App\Controllers\Housekeeping\Authenticated
{
    public function __construct()
    {
        $this->load()->libaries('validate');
    }
  
    public function createAction()
    {
        if($this->request->all())
        {
            $newstitle   = $this->request->input('title');
            $description = $this->request->input('description');
            $form        = $this->request->input('form');
            $headerimg   = $this->request->input('header');
            $message     = $this->request->input('body');

            $this->validate->name('Titel')->value($newstitle)->required()->min(3)->max(50);
            $this->validate->name('Omschrijving')->value($description)->required()->min(3)->max(100);
            $this->validate->name('Bericht')->value($message)->required()->min(25);
          
            if($this->validate->isSuccess())
            {
                if(Housekeeping::insertNewsItem($newstitle, $description, $headerimg, $message, Auth::returnUserId(), $form))
                {
                    Flash::addMessage('Nieuwsbericht is toegevoegd en zal vanaf te zien zijn op de website', FLASH::SUCCESS);
                    $this->redirect('/housekeeping/manage/news');
                }
            }
            else
            {
                $data = new \stdClass();
                $data->category = News::getCategory();
              
                View::renderTemplate('Housekeeping/Management/news.html', [ 
                    'data' => $data,
                    'permission' => 'housekeeping_website_news',
                    'newstitle' => $newstitle,
                    'description' => $description,
                    'message' => $message
                ]);
            }
        }
    }
  
    public function indexAction()
    {
        $data = new \stdClass();

        $newsArticles = News::getArticles();

        foreach($newsArticles as $news)
        {
            $news->author = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $news->user_id);
        }

        $data->newsArticles = $newsArticles;
        $data->category     = News::getCategory();

        View::renderTemplate('Housekeeping/Management/news.html', ['data' => $data, 'permission' => 'housekeeping_website_news']);
    }
}
    