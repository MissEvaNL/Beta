<?php
namespace App\Controllers;

use \Core\View;
use \Core\Emu;

use \App\Auth;
use \App\Models\News;
use \App\Models\Core;
use \App\Models\Shop;
use \App\Models\User;

class Dashboard extends \Core\Controller
{
 
    public function dashboardAction()
    {  
        $data = new \stdClass();
      
        $news = News::getArticles(4);
        foreach($news as $item)
        {
            $settings = User::userCmsSettings($item->user_id);
            $item->author = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $item->user_id); 
            if($settings)
            {
                $item->font = Shop::selectItemById($settings->item_font);
            }
        }
      
        $data->news = $news;
      
        View::renderTemplate('Index/dashboard.html', [
          'data' => $data
        ]);   
    }
 
    public function indexAction()
    {
        if(Auth::getUser())
        {
            $this->dashboard();
        }
        else
        {
            View::renderTemplate('Index/login.html');  
        }
    }
  
    public function onlineUsersAction()
    {
        View::renderTemplate('Index/displayonline.html');  
    }
    
}
