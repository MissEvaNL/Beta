<?php
namespace App\Controllers;

use \Core\Emu;
use \Core\View;

use \App\Auth;
use \App\Flash;

use \App\Models\User;
use \App\Models\Core;
use \App\Models\Shop;

use \Libaries\Json;

class Account extends \Core\Controller
{
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
    
    public function settingsAction()
    {
        $this->redirect('/account/profile-settings');
    }
  
    public function changePinAction()
    {
        $pin = $this->request->input('pin');
      
        $this->validate->name('Pincode')->value($pin)->min(4)->max(10)->pattern('int');
      
        if($this->request->all())
        {
            if($this->validate->isSuccess())
            {
                if(Core::getData('cms_user_settings', 'pin', 'user_id', Auth::returnUserId()) == NULL)
                {
                    Core::updateField('cms_user_settings', 'pin', $pin, Auth::returnUserId(), 'user_id');
                    Flash::addMessage('Je pincode is ingesteld!');  
                }
                else  
                {
                    Flash::addMessage('Voer je oude pincode om de wijzing definitief te maken', FLASH::WARNING);
                    View::renderTemplate('Account/changepin.html', [
                        'pin' => $pin
                    ]);
                    exit;
                }
            }
        }
        $this->redirect(Auth::getReturnToPage());
    }
  
    public function  ValidatePinAction()
    {
        $oldpin = $this->request->input('oldpin');
        $newpin = $this->request->input('pin');
        
        if($this->request->all())
        {
            if(Core::getData('cms_user_settings', 'pin', 'user_id', Auth::returnUserId()) == $oldpin)
            {
                Core::updateField('cms_user_settings', 'pin', $newpin, Auth::returnUserId(), 'user_id');
                Flash::addMessage('Je pincode is ingesteld!');  
            }
            else
            {
                Flash::addMessage('Ingevoerde pincode komt niet overeen met de oude pincode.', FLASH::ERROR);
            }
            $this->redirect('/account/user-settings');
        }
    }
  
    public function changeMottoAction()
    {
        $motto = $this->request->input('motto');
        
        $this->validate->name('Missie')->value($motto)->max(30);

        if($this->request->all())
        {
            User::updateUser($_SESSION['user_id'], 'motto', $motto);
            Flash::addMessage('Jouw missie is aangepast!', 'success');
            $this->redirect('/');
        }
        else
        {
            Flash::addMessage('Er is iets mis gegaan!');
            $this->redirect('/');
        }
    }
    
    public function userSettingsAction()
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $currpassword = $this->request->input('currpassword');
        
        if($this->request->all())
        {
            $this->validate->name('Je email')->value($email)->required()->pattern('email');
            
            if(trim($password) != '')
            {
                $this->validate->name('Je wachtwoord')->value($password)->min(6)->max(32)->customPattern('[A-Za-z0-9-.;_!#@]{5,15}')->equal($currpassword);
            }
            
            if($this->validate->isSuccess())
            {
                User::changeAccountSettings($email, $password, Auth::returnUserId());
                Flash::addMessage('Instellingen zijn opgeslagen', FLASH::SUCCESS);
            }
            $this->redirect('/account/user-settings');
        }
        
        View::renderTemplate('Account/account_settings.html'); 
    }
    
    public function hotelSettingsAction()
    {
        //Move this in the future to database
        $inArray = array(
            'block_newfriends',
            'hide_online',
            'hide_inroom',
            'trading_locked'
        );
        
        $column = $this->request->input('post');
        $type   = (int)filter_var($this->request->input('type'), FILTER_VALIDATE_BOOLEAN);

        if($this->request->all())
        {                        
            if(is_int($type) && in_array($column, $inArray))
            {
                User::updateUser(Auth::returnUserId(), $column, strval($type));
                echo Json::encode(array('status' => 'success'));
            }
        }
        else
        {
            $this->data->settings = Auth::getUser();
          
            View::renderTemplate('Account/hotel_settings.html', [
                'data' => $this->data,
            ]);     
        }
     
    }
  
    public function profileSettingsAction()
    {
        $motto = $this->request->input('motto');

        if($this->request->all())
        {
            $profileBio     = $this->request->input('bio'); 
            $fontid         = $this->request->input('colorfont'); 
            $prvateProfile  = $this->request->input('privateprofile'); 
            
            if(Shop::checkItemIsPurchased(Auth::returnUserId(), $fontid) || $fontid == 0)
            {
                if(Shop::checkItemType($fontid, 'font')){
                    $settings = User::changeSettings($profileBio, $fontid, $prvateProfile, Auth::returnUserId());
                    if($settings)
                    {
                        Flash::addMessage('Instellingen zijn opgeslagen', FLASH::SUCCESS);
                    }  
                }
                else
                {
                    Flash::addMessage('Hacking attempt', FLASH::ERROR);
                }
            }
            else
            {
                Flash::addMessage('Er heeft zich een fout opgetreden', FLASH::ERROR);
            }
            $this->redirect('/account/settings');
        }
        
        $this->data->settings = User::userCmsSettings(Auth::returnUserId());
        $this->data->fonts = Shop::getPurchasedItemByObject('font', Auth::returnUserId());

        View::renderTemplate('Account/settings.html', [
            'data' => $this->data
        ]); 
    }
  
    public function transactionsOverview()
    {
        $transactions = User::getTransactions(Auth::returnUserId());
      
        $this->data->transactions = $transactions;
      
        View::renderTemplate('Account/transactions.html', [
            'data' => $this->data
        ]);
    }
  
}