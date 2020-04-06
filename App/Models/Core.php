<?php
namespace App\Models;

use \Core\Emu;
use \App\Auth;

use QueryBuilder;
use PDO;

class Core extends \Core\Model
{
    public static function getData($table, $data, $id, $user_id)
    {
        $query = QueryBuilder::table($table)->select($data)->where($id, $user_id)->first();
        return $query->$data ?? null;
    }
  
    public static function insertLog($userid, $type, $data, $timestamp, $target)
    {
        $data = array(
            'user_id' => $userid,
            'type'  => $type,
            'data' => $data,
            'timestamp' => $timestamp,
            'target' => $target
        );
        
        return QueryBuilder::table('cms_logs')->insert($data);
    }
  
    public static function logger($log, $type)
    {
        preg_match_all('#\[(.*?)\]#', $log, $match);
        Core::insertLog(Auth::returnUserId(), strtoupper($type), preg_replace('#\[(.*?)\]#', '', $log), time(), $match[1][0] ?? null);
    }
  
    public static function updateField($table, $key, $value, $user_id, $where){  
        return QueryBuilder::table($table)->where($where, $user_id)->update(array($key => $value));
    }
 
    public static function getFromSettings($key)
    {
        $query = QueryBuilder::table('cms_settings')->select($key)->first();
        return $query->$key ?? null;
    }
  
    public static function getOnline() 
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))->where('online', '1')->get();
    }
  
    public static function convertIp($ipadress, $userrank)
    {
        if(in_array('housekeeping_ip_display', array_column(User::userPermissions(Auth::getUser()->rank), 'permission')))
                {
            $regex   = array("/[\d]{3}$/", "/[\d]{2}$/", "/[\d]$/");
            $replace = array("xxx", "xx", "x");
            return preg_replace($regex, $replace, $ipadress);
        } else {
            return '0.0.0.0';
        }
    }

    public static function getIpAddress() 
    {
        return filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : false;
    }
  
    public static function getStaffOnline() 
    {
        return QueryBuilder::table(Emu::Get('tablename.Users'))->where('online', '1')->where('rank', '>', 5)->get();
    }
  
    public static function getAllFromSettings()
    {
        return QueryBuilder::table('cms_settings')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->first();
    }
  
    public static function getBans($username, $ipadress)
    {
        return QueryBuilder::table('bans')->where(Emu::Get('table.Bans.user_id'), $username)->orWhere(Emu::Get('table.Bans.user_id'), $ipadress)->where('expire', '>', time())->first();
    }
  
    public static function calculate($key, $value, $operator)
    {
        $param = $key + $value;
        return $param;
    }
}