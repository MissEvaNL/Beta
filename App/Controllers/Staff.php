<?php
namespace App\Controllers;

use \Core\View;
use \Core\Emu;

use \App\Auth;
use \App\Flash;

use \App\Models\User;
use \App\Models\Core;
use \App\Models\Shop;
use \App\Models\Employee;

class Staff extends Authenticated
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
        $data = new \stdClass();
      
        $employee = Employee::getRoles();
        foreach($employee as $rank)
        {
            $rank->users = User::getUsersByRankId($rank->id);
          
            if(!empty($rank->users) && is_array($rank->users))
            {
                foreach($rank->users as $users)
                {
                    $userSettings = User::userCmsSettings($users->id);
                  
                    $users->id = $users->id;
                    $users->username = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $users->id); 
                    $users->look     = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.look'), 'id', $users->id); 
                    $users->online   = Core::getData(Emu::Get('tablename.Users'), 'online', 'id', $users->id); 
                  
                    $users->role     = $userSettings->role;
                    $users->country  = $userSettings->country; 
                    $users->font     = Shop::selectItemById($userSettings->item_font);
                }
            }
        }
      
        $data->employee = $employee;

        View::renderTemplate('Index/staff.html', ['data' => $data]);
    }
  
}