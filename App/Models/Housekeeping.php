<?php
namespace App\Models;

use \Core\Emu;

use QueryBuilder;
use PDO;

class Housekeeping extends \Core\Model
{
    public static function changeUserSettings($email, $rank, $motto, $credits, $diamonds, $bcredits, $userid)
    {
        $data = array(
            Emu::Get('table.Users.mail') => $email,
            'rank'  => $rank,
            'motto' => $motto,
            'credits' => $credits,
            Emu::Get('table.Users.points') => $diamonds,
            Emu::Get('table.Users.gotw_points') => $bcredits
        );
        
        return QueryBuilder::table(Emu::Get('tablename.Users'))->where('id', $userid)->update($data);
    }
  
    public static function insertPermission($role, $permission)
    {
        $data = array(
            'permission_id' => $permission,
            'permissions_groups'  => $role,
        );
        
        return QueryBuilder::table('cms_permissions_ranks')->insert($data);
    }
  
    public static function deletePermission($permissionid)
    {       
        return QueryBuilder::table('cms_permissions_ranks')->where('id', $permissionid)->delete();
    }
  
    public static function insertNewsItem($newstitle, $description, $headerimg, $message, $userid, $form)
    {
        $data = array(
            'title' => $newstitle,
            'image'  => $headerimg,
            'shortstory'  => $description,
            'longstory' => $message,
            'user_id' => $userid,
            'date' => time(),
            'cat_id' => $form
        );
        
        return QueryBuilder::table('cms_news')->insert($data);
    }
  
    public static function changeCmsSettings($sitename, $hotelname, $siteurl, $emulator, $recaptchakey, $motto, $maintenance, $credits, $points, $pixels)
    {
        $data = array(
            'sitename' => $sitename,
            'hotelname'  => $hotelname,
            'siteurl'  => $siteurl,
            'emulatorname' => $emulator,
            'recaptchakey' => $recaptchakey,
            'motto' => $motto,
            'maintenance' => $maintenance,
            'credits' => $credits,
            'pixels' => $points,
            'points' => $pixels
        );  
      
        return QueryBuilder::table('cms_settings')->where('id', 1)->update($data);
    }
  
    public static function changeClientSettings($ipadress, $port, $habboswf, $habboswffolder, $externaltexts, $overridetexts, $externalvariables, $overridevariables, $furnidata, $productdata, $flashclient)
    {
        $data = array(
            'ip' => $ipadress,
            'port'  => $port,
            'habboswf'  => $habboswf,
            'habboswffolder' => $habboswffolder,
            'externaltexts' => $externaltexts,
            'overridetexts' => $overridetexts,
            'externalvariables' => $externalvariables,
            'overridevariables' => $overridevariables,
            'furnidata' => $furnidata,
            'productdata' => $productdata,
            'flashclient' => $flashclient
        );  
      
        return QueryBuilder::table('cms_settings')->where('id', 1)->update($data);
    }
  
    public static function insertBanByType($bantype, $banmessage, $bantime, $type, $user)
    {
        $data = array(
            Emu::Get('table.Bans.type') => $type,
            Emu::Get('table.Bans.user_id')  => $bantype,
            'reason'  => $banmessage,
            'expire' => time() + $bantime,
            'added_by' => $user,
            'added_date' => time()
        );

        return QueryBuilder::table('bans')->insert($data);
    }
  
    public static function getLatestUsers()
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))->OrderBy('id', 'desc')->limit(50)->get();
    }
  
    public static function getChatLogs($userid, $limit = null)
    {
        return QueryBuilder::table(Emu::Get('tablename.Chatlogs_room'))->where(Emu::Get('table.chatlogs_room.from_id'), $userid)->OrderBy('timestamp', 'desc')->limit($limit)->get();
    }
  
    public static function getStaffLogs()
    {
        return QueryBuilder::table('cms_logs')->OrderBy('id', 'desc')->get();
    }
      
    public static function getStaffLogsByUser($username, $userid)
    {
        return QueryBuilder::table('cms_logs')->where('target', $username)->orWhere('target', $userid)->OrderBy('id', 'desc')->get();
    }
  
   public static function getMessengerLogs($userid)
    {
        return QueryBuilder::table(Emu::Get('tablename.Chatlogs_private'))->where(Emu::Get('tablename.chatlogs.from_id'), $userid)->OrderBy('id', 'desc')->get();
    }
  
    public static function getAllChatLogs()
    {
        return QueryBuilder::table(Emu::Get('tablename.Chatlogs_room'))->OrderBy('timestamp', 'desc')->limit(1000)->get();
    }
  
    public static function getDuplicateusers($last_ip, $reg_ip)
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))->where(Emu::Get('table.Users.ip_register'), $last_ip)->orWhere(Emu::Get('table.Users.ip_current'), $reg_ip)->get();
    }
  
    public static function getRoomsByUserId($userid)
    {
        return QueryBuilder::table('rooms')->where(Emu::Get('table.rooms.owner_id'), $userid)->get();
    }
  
    public static function getUserNameChangesByid($userid)
    {
        return QueryBuilder::table(Emu::Get('tablename.Namechange_log'))->where('user_id', $userid)->get();
    }
  
    public static function getAllUserNameChanges()
    {
        return QueryBuilder::table(Emu::Get('tablename.Namechange_log'))->get();
    }
  
    public static function getBanMessages()
    {
        return QueryBuilder::table('cms_ban_messages')->get();
    }
  
    public static function getBanMessagesById($id)
    {
        return QueryBuilder::table('cms_ban_messages')->where('id', $id)->first();
    }
  
    public static function getBanTime($userrank)
    {
        return QueryBuilder::table('cms_ban_type')->where('min_rank', '<=', $userrank)->orWhereNull('min_rank')->get();
    }
  
    public static function getBanTimeById($id)
    {
        return QueryBuilder::table('cms_ban_type')->where('id', $id)->first();
    }
  
    public static function getHotelRanks($limit = null)
    {
        return QueryBuilder::table(Emu::Get('tablename.Permissions'))->limit($limit)->get();
    }
  
    public static function getAllUserBans()
    {
        return QueryBuilder::table('bans')->orderBy('id', 'desc')->where(Emu::Get('table.Bans.type'), Emu::Get('table.Bans.type.account'))->get();
    }
  
    public static function deleteUserBan($value)
    {
        return QueryBuilder::table('bans')->where(Emu::Get('table.Bans.user_id'), $value)->delete();
    }
  
    public static function getRoles($string = null)
    {
        return QueryBuilder::table(Emu::Get('tablename.Permissions', 'permissions_groups'))->select(Emu::Get('table.Permissions.rank_name'))->select(Emu::Get('table.Permissions.level'))->orderBy('id', 'desc')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where(Emu::Get('table.Permissions.rank_name'), 'LIKE ', '%' . $string . '%')->get();
    }
  
    public static function getPermissions($string = null)
    {
        return QueryBuilder::table('cms_permissions')->select('cms_permissions.id')->select('cms_permissions.permission')->orderBy('id', 'desc')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('cms_permissions.permission', 'LIKE ', '%' . $string . '%')->get();
    }
      
    public static function getPermissionsData($id)
    {
        return QueryBuilder::table('cms_permissions_ranks')->select(QueryBuilder::raw('cms_permissions.id as idp'))->select('cms_permissions_ranks.*')->select('cms_permissions.description')->select('cms_permissions.permission')->where('permissions_groups', $id)->join('cms_permissions', 'cms_permissions_ranks.permission_id', '=', 'cms_permissions.id')->get();
    }
  
    public static function getPermissionsByRoleid($id)
    {
        return QueryBuilder::table('cms_permissions_ranks')->where('permission_id', $id)->get();
    }
  
    public static function checkIfPermissionExists($role, $permission)
    {
        return queryBuilder::table('cms_permissions_ranks')->where('permissions_groups', $role)->where('permission_id', $permission)->count();
    }
  
    public static function getPermissionsByRankId($id)
    {
        return QueryBuilder::table('cms_permissions_ranks')->where('permissions_groups', $id)->get();
    }
  
    public static function getUsersByRank($id)
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))->select(Emu::Get('table.Users.username'))->select('id')->where('rank', $id)->get();
    }
  
    public static function ifUserAndRoleExists($role, $permission)
    {
        return QueryBuilder::table('cms_permissions_ranks')->where('permission_id', $permission)->where('permissions_groups', $role)->count();
    }
  
}