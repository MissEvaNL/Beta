<?php
namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Auth;
use \App\Config;
use \App\Models\Shop;
use \App\Models\Core;
use \App\Models\User;

class Store extends Authenticated
{
    public function __construct(){
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
    
    public function indexAction()
    {
        $shop = Shop::getShopCategory();
            
        if($this->request->all())
        {
            $itemid = $this->request->input('itemid');
            
            $item   = Shop::getItemById($itemid);
            $user   = Auth::getUser();

            if($item->price <= $user->vip_points)
            {
                $calculate = $user->vip_points - $item->price;
                if(Shop::buyItemById($itemid, Auth::returnUserId(), $calculate))
                {
                    Flash::addMessage('Je aankoop is succesvol toegevoegd aan jouw talpa account!');
                }
                else
                {
                    Flash::addMessage('Je hebt dit product al eens gekocht.', FLASH::ERROR);    
                }
            }
            else
            {
                Flash::addMessage('Je hebt niet genoeg diamanten!', FLASH::WARNING);
            }
            
            $this->redirect('/shop');
        }
        else
        {
            foreach($shop as $item)
            {
                $userItems = Shop::getItemsByCatId($item->id, Auth::returnUserId());
              
                foreach($userItems as $items)
                {
                    if(!Shop::checkItemIsPurchased(Auth::returnUserId(), $items->id))
                    {
                        $item->items[$items->id] = $items;
                    }
                }
            }
            
            $this->data->shop = $shop;
          
            $clubDays = User::getClubDays(Auth::returnUserId());
        
            View::renderTemplate('Shop/store.html', [
                'data' => $this->data,
                'club' => $clubDays
            ]);
        }
    }
  
    public function redeemCouponAction()
    {
        if($this->request->all())
        {
            $couponid = $this->request->input('couponid');
            $coupon = Shop::getByCouponId($couponid);

            if($coupon)
            {
                if(strtotime($coupon->expire_date) >= time())
                {
                    if(Shop::buyItemById($coupon->item_id, Auth::returnUserId()))
                    {
                        Flash::addMessage('Coupon verzilverd, kijk snel!');
                    }
                    else
                    {
                        Flash::addMessage('Je bent al in bezit van dit item!', FLASH::WARNING);  
                    }
                }
                else
                {
                    Flash::addMessage('Coupon is niet meer geldig!', FLASH::WARNING);    
                }
            }
            else
            {
                Flash::addMessage('Coupon bestaat niet!', FLASH::ERROR);    
            }
            $this->redirect('/shop');
        }
    }
}