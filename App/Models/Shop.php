<?php
namespace App\Models;

use \Core\View;
use \Core\Emu;

use QueryBuilder;
use PDO;

class Shop extends \Core\Model
{
    public static $inArray = array();

    /**
    * Get all the addons by classname from the given class
    * 
    * @access public, @param int $classname, @return object
    */
    
    public static function purchasedAddons($array)
    {      
        foreach($array as $addons)
        {
            $object = queryBuilder::table('cms_shop_objects')->where('param', $addons->objectname);
            $getName = $object->first();
          
            if($object->count() != 0)
            {
                if($getName->object == 'array'){
                    $inArray[$getName->param][] = $addons->image;
                }
            }
            else
            {
                $inArray[$addons->objectname] = true;
            }
        }
        return $inArray ?? null;
    }
    
    /**
    * Get all the addons by classname from the given class
    * 
    * @access public, @param int $classname, @return object
    */
    
    public static function shopAddons($userid)
    {
        $shopAddons = queryBuilder::table('cms_shop_items')->setFetchMode(PDO::FETCH_CLASS, get_called_class())
            ->join('cms_shop_purchases', 'cms_shop_purchases.item_id', '=', 'cms_shop_items.id')->where('cms_shop_purchases.user_id', $userid)->get();

        if($shopAddons)
        {
            return self::purchasedAddons($shopAddons);
        }
        
    }
  
    public static function getShopCategory()
    {
        return queryBuilder::table('cms_shop_category')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->get();
    }
  
    public static function getItemById($itemid)
    {
        return queryBuilder::table('cms_shop_items')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($itemid);
    }
  
    public static function selectItemById($itemid)
    {
        $query = queryBuilder::table('cms_shop_items')->select('image')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $itemid)->first();
        return $query->image ?? null;
    }
    
    public static function checkItemIsPurchased($userid, $itemid)
    {
        return queryBuilder::table('cms_shop_purchases')->where('item_id', $itemid)->where('user_id', $userid)->count();
    }
  
    public static function getItemsByCatId($catid, $userid)
    {
        return queryBuilder::table('cms_shop_items')->where('cat_id', $catid)->get();
    }
  
    public static function checkItemType($itemid, $objectname)
    {
        return queryBuilder::table('cms_shop_items')->where('id', $itemid)->where('objectname', $objectname)->count();
    }
  
    public static function getPurchasedItemByObject($objectname, $userid)
    {
       return queryBuilder::table('cms_shop_items')->where('objectname', $objectname)->join('cms_shop_purchases', 'cms_shop_items.id', '=', 'cms_shop_purchases.item_id')->where('cms_shop_purchases.user_id', $userid)->get();
    }
  
    public static function getPurchasedItemsByUserId($userid)
    {
       return queryBuilder::table('cms_shop_items')->join('cms_shop_purchases', 'cms_shop_items.id', '=', 'cms_shop_purchases.item_id')->where('cms_shop_purchases.user_id', $userid)->get();
    }
  
    public static function getByCouponId($couponid)
    {
        return queryBuilder::table('cms_coupon')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('coupon', $couponid)->first();
    }
  
    public static function buyItemById($itemid, $userid, $points = 0)
    {
        $query = queryBuilder::table('cms_shop_purchases')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $userid)->where('item_id', $itemid)->count();
      
        if(!$query)
        {
            $data = array(
                'user_id'     => $userid,
                'item_id'     => $itemid
            );

            $product = QueryBuilder::table('cms_shop_purchases')->insert($data);

            if($product)
            {
                return queryBuilder::table(Emu::Get('tablename.Users'))->where('id', $userid)->update(array('vip_points' => $points));
            }
        }
        else
        {
            return false;
        }
    }
}
