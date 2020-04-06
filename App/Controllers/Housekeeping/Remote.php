<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Flash;
use \App\Auth;
use \App\Models\User;
use \App\Models\Core;
use \App\Models\Shop;
use \App\Models\Housekeeping;

class Remote extends \App\Controllers\Housekeeping\Authenticated
{
  
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
  
    public function userAction($userid = null, $type = null)
    {    
        if($type == null && isset($this->route_params['do']))
        {
            $type = $this->route_params['do'];
        }
        
        $user = Core::getData(Emu::Get('tablename.Users'), 'id', Emu::Get('table.Users.username'), $userid ?? $this->route_params['username']);
      
        if(isset($user))
        {
            /**
             * Retrieve object from the user
             * Class: App\Models\User.php
             */
          
            $this->data->user = User::FindById($user);
          
            $this->data->user->ipregister = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_register'), 'id', $this->data->user->id);
            $this->data->user->ipcurrent  = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_current'), 'id', $this->data->user->id);
          
            /**
             * Set user data in object for every emulator
             */
          
            $this->data->user->mail         = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.mail'), 'id', $this->data->user->id);
            $this->data->user->ip_reg       = Core::convertIp($this->data->user->ipregister, Auth::getUser()->rank);
            $this->data->user->ip_last      = Core::convertIp($this->data->user->ipcurrent, Auth::getUser()->rank);
            $this->data->user->last_online  = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.last_online'), 'id', $this->data->user->id);
            $this->data->user->points       = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.points'), 'id', $this->data->user->id);
            $this->data->user->pincode      = Core::getData('cms_user_settings', 'pin', 'user_id', $this->data->user->id);
            $this->data->user->role         = Core::getData('cms_user_settings', 'role', 'user_id', $this->data->user->id);
            $this->data->user->sadmin       = Core::getData('cms_user_settings', 'is_sadmin', 'user_id', Auth::returnUserId());
            $this->data->user->uadmin       = Core::getData('cms_user_settings', 'is_sadmin', 'user_id', $this->data->user->id);
          
            /**
             * Retrieve object from the user
             * Class: App\Models\User.php
             */
            
            $this->data->banmessages = Housekeeping::getBanMessages();
            $this->data->bantime = Housekeeping::getBanTime(Auth::getUser()->rank);
            
            /**
             * Retrieve chatlogs from user
             * Class: App\Models\Housekeeping.php
             */
          
            if($type == 'chatlogs-all')
            {
               $this->data->user->chatlogs = Housekeeping::getChatLogs($this->data->user->id); 
               $type = 'chatlogs';
            }
            else
            {
                $this->data->user->chatlogs = Housekeeping::getChatLogs($this->data->user->id, 1000);  
            }
            
            if($type == 'messengerlogs')
            {
                if(in_array('housekeeping_staff_logs', array_column(User::userPermissions(Auth::getUser()->rank), 'permission')))
                {
                    $this->data->hotelranks = Housekeeping::getHotelRanks(Auth::getUser()->rank);
                    $messengerlogs = Housekeeping::getMessengerLogs($this->data->user->id); 

                    foreach($messengerlogs as $logs)
                    {
                        $logs->to_user = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $logs->to_id);
                    }

                    $this->data->user->messengerlogs = $messengerlogs;
                    }
                else
                {
                    Flash::addMessage('Je hebt geen rechten om deze informatie in te zien');
                    $this->redirect('/');
                }
            }
          
            /**
             * Foreach chatlogs to add rooms where the message was sent
             * Class: App\Models\Housekeeping.php
             */
          
            foreach($this->data->user->chatlogs as $chatlogs)
            {
                $chatlogs->room = Core::getData('rooms', Emu::Get('table.rooms.name'), 'id', $chatlogs->room_id);
            }
            
            /**
             * Search for duplicate accounts that user has created 
             * Class: App\Models\Housekeeping.php
             */
          
            $this->data->duplicateUsers = Housekeeping::getDuplicateusers($this->data->user->ipregister, $this->data->user->ipcurrent);
            
            /**
             * Add user data to the founded duplicated user account 
             */
          
            foreach($this->data->duplicateUsers as $duplicate)
            {
                $duplicate->last_ip   = Core::convertIp(Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_current'), 'id', $duplicate->id), Auth::getuser()->rank);
                $duplicate->reg_ip    = Core::convertIp(Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_register'), 'id', $duplicate->id), Auth::getuser()->rank);
                $duplicate->lastvisit = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.last_online'), 'id', $duplicate->id);
            }
          
            /**
             * Retrieve created rooms by userid
             * Class: App\Models\Housekeeping.php
             */
          
            $this->data->rooms = Housekeeping::getRoomsByUserId($this->data->user->id);
            
            foreach($this->data->rooms as $rooms)
            {
                $rooms->caption     = Core::getData('rooms', Emu::Get('table.rooms.name'), 'id', $rooms->id);
                $rooms->description = Core::getData('rooms', 'description', 'id', $rooms->id);

            }
          
            /**
             * Retrieve the users purchased cms items
             * Class: App\Models\Shop.php
             */
          
            $this->data->shopItems        = Shop::getPurchasedItemsByUserId($this->data->user->id);
            $this->data->usernameChanges  = Housekeeping::getUserNameChangesByid($this->data->user->id);
          
            /**
             * Check if the user can adjust ranks
             * Class: App\Models\User.php
             */
            
            if(in_array('housekeeping_ranks', array_column(User::userPermissions(Auth::getUser()->rank), 'permission')))
            {
                $this->data->hotelranks = Housekeeping::getHotelRanks(Auth::getUser()->rank);
            }

            if(Auth::getUser()->rank > $this->data->user->rank || $this->data->user->id == Auth::returnUserId() || $this->data->user->sadmin == 1)
            {
                View::renderTemplate('Housekeeping/Tools/remote.html', [
                    'permission' => 'housekeeping_remote_control',
                    'data' => $this->data,
                    'type' => $type
                ]);
            }
            else
            {
                Flash::addMessage('Je kunt geen informatie zien van een speler met dezelfde rechten!', FLASH::ERROR);
                Core::logger("Try to see user information from someone with the same rank [{$this->data->user->id}]", 'info');
                $this->redirect('/housekeeping');
            }
        }
        else
        {
            $this->redirect('/housekeeping');
        }
    }
  
    public function controlAction()
    {
      
      /**
       * Check if user did a post request otherwise return homepage 
       */
      
        if($this->request->all())
        {
            
          /**
           * Get post requests and put them in variables
           */
            
            $username = $this->request->input('element');
            $action   = $this->request->input('object');
            $type     = $this->request->input('type');
            
            if($action == 'manageuser')
            {
                    $this->userAction($username, $type ?? null); 
            }
            else if($action == 'banuser') 
            {
                if(in_array('housekeeping_ban_user', array_column(User::userPermissions(Auth::getUser()->rank), 'permission')))
                {
                    $banmessage = Housekeeping::getBanMessagesById($this->request->input('reason'));
                    $bantime    = Housekeeping::getBanTimeById($this->request->input('expire'));

                    if($type == 'user')
                    {
                        $bantype = $username;
                    }
                    else
                    {
                        $bantype = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.ip_register'), Emu::Get('table.Users.username'), $username); 
                    }

                    if(Housekeeping::insertBanByType($bantype, $banmessage->message, $bantime->seconds, $type, Auth::getUser()->username))
                    {
                        Flash::addMessage('Je hebt ' . $username . ' een ban gegeven van ' . $bantime->message, FLASH::SUCCESS);
                        Core::logger("Gives a ban for reason: " . $banmessage->message . " [{$bantype}]", 'ban');
                        $this->redirect('/housekeeping/remote/control');
                    }
                }else{
                    Flash::addMessage('Je hebt niet de rechten om te kunnen bannen!', FLASH::ERROR);
                    $this->redirect('/housekeeping');
                }
            }else if($action == 'all')
            {
                $this->userAction($username);  
            } 
        }
        else
        {
            $this->data->banmessages = Housekeeping::getBanMessages();
            $this->data->bantime = Housekeeping::getBanTime(Auth::getUser()->rank);
          
            View::renderTemplate('Housekeeping/Tools/search.html', ['permission' => 'housekeeping_remote_control', 'data' => $this->data]);
        }
    }
    
    public function unbanAction()
    {
        $user = Core::getData(Emu::Get('tablename.Users'), 'id', Emu::Get('table.Users.username'), $this->route_params['username']);

        if($user)
        {
            $userObject = (object)User::setUserSettings($user);
            
            if(Core::getData('bans', 'id',  Emu::Get('table.Bans.user_id'), $userObject->username))
            {
                if(Housekeeping::deleteUserBan($userObject->username))
                {
                    Flash::addMessage('Je hebt de speler ' . $userObject->username . ' succesvol verwijderd van onze banlijst.');
                    Core::logger("Gives an unban to user [{$userObject->username}]", 'ban');
                }
            }
            else if(Core::getData('bans', 'id',  Emu::Get('table.Bans.user_id'), $userObject->ip_register))
            {
                if(Housekeeping::deleteUserBan($userObject->ip_register))
                {
                    Flash::addMessage('Je hebt de speler ' . $userObject->username . ' succesvol verwijderd van onze banlijst.');   
                    Core::logger("Gives an unban to user [{$userObject->username}]", 'ban');
                }
            }     
            else
            {
                Flash::addMessage('We hebben het verzoek niet kunnen verwerken, vraag onze Hotel Manager voor meer informatie');
            }
            $this->redirect(Auth::getReturnToPage()); 
        }
        else
        {
            Flash::addMessage('Wij kennen deze gebruiker niet!', FLASH::ERROR);
            $this->redirect(Auth::getReturnToPage()); 
        }
    }
  
    public function changeAction()
    {
        if($this->request->all())
        {
            $username = $this->route_params['username'];
          
            $userid   = Core::getData(Emu::Get('tablename.Users'), 'id', Emu::Get('table.Users.username'), $username);
            $rank     = Core::getData(Emu::Get('tablename.Users'), 'rank', Emu::Get('table.Users.username'), $username);
          
            if($userid)
            {
                if(isset($this->route_params['do']) == 'userinfo')
                {          
                    $email    = $this->request->input('email');
                  
                    if(in_array('housekeeping_ranks', array_column(User::userPermissions(Auth::getUser()->rank), 'permission')))
                    {
                        $rank     = $this->request->input('rank');
                        $this->validate->name('Rank')->value($rank)->required()->is_int($rank);
                    }
                  
                    $sadmin   = $this->request->input('sadmin');
                    $country  = $this->request->input('country');
                    $pincode  = $this->request->input('pincode');
                    $motto    = $this->request->input('motto');
                    $role     = $this->request->input('roledescription');
                    $credits  = $this->request->input('credits');
                    $diamonds = $this->request->input('diamonds');
                    $bcredits = $this->request->input('bcredits');

                    $this->validate->name('Email')->value($email)->required()->pattern('email');
                    $this->validate->name('Land')->value($country)->required();
                    $this->validate->name('Pincode')->value($pincode)->pattern('int')->max(6)->is_int($pincode);
                    $this->validate->name('Motto')->value($motto)->required()->max(50);
                    $this->validate->name('Credits')->value($credits)->required()->min(1)->max(7)->is_int($credits);
                    $this->validate->name('Diamonds')->value($diamonds)->required()->min(1)->max(7)->is_int($diamonds);
                    $this->validate->name('Belcredits')->value($diamonds)->required()->min(1)->max(7)->is_int($bcredits);

                    if($this->validate->isSuccess()){
                        if(Housekeeping::changeUserSettings($email, $rank, $motto, $credits, $diamonds, $bcredits, $userid))
                        {
                            $is_admin = Core::getData('cms_user_settings', 'is_sadmin', 'user_id', Auth::returnUserId());
                          
                            if($is_admin == true)
                            {
                                Core::updateField('cms_user_settings', 'is_sadmin', $sadmin ?? null, $userid, 'user_id');
                            }
                            if(!empty($pincode))
                            {
                                Core::updateField('cms_user_settings', 'pin', $pincode, $userid, 'user_id');
                            }
                            if(!empty($role)){
                                Core::updateField('cms_user_settings', 'role', $role, $userid, 'user_id');
                            }
                            if(!empty($country)){
                                Core::updateField('cms_user_settings', 'country', $country, $userid, 'user_id');
                            }   
                          
                            Flash::addMessage('Gegevens van ' . $username . ' zijn aangepast!');
                            Core::logger("Change profile settings [{$userid}]", 'change');
                        }
                        $this->redirect('/housekeeping/remote/user/' . $username . '/' . $this->route_params['do']);
                    }
                    else
                    {
                        $this->redirect('/housekeeping/remote/user/' . $username . '/' . $this->route_params['do']);
                    }
                }     
            }
            else
            {
                Flash::addMessage('Gebruiker komt niet voor die je probeert aan te passen!', FLASH::ERROR);
                $this->redirect(Auth::getReturnToPage()); 
            }
        }
        else
        {
            Flash::addMessage('Er is iets mis gegaan..', FLASH::ERROR);
            $this->redirect(Auth::getReturnToPage());    
        }
    }
}
    