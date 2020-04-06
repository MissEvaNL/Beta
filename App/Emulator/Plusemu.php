<?php
namespace App\Emulator;

use \Core\Emu;

use \App\Config;
use \App\Token;
use \App\Models\Core;

use QueryBuilder;
use PDO;

class Plusemu {
  
    public function __construct(){
        $this->setTables();
    }

    public function setTables(){
        /**
         * Bans tabel.
         */
        Emu::Set('table.Bans.type', 'bantype');
        Emu::Set('table.Bans.user_id', 'value');
        Emu::Set('table.Bans.type.account', 'user');
        Emu::Set('table.Bans.type.added_by', 'added_by');
        Emu::Set('table.Bans.type.expire', 'expire');
      
        /**
         * Permissions tabel.
         */
        Emu::Set('tablename.Permissions', 'permissions_groups');
        Emu::Set('table.Permissions.level', 'id');
        Emu::Set('table.Permissions.rank_name', 'name');

        /**
         * Users tabel.
         */
        Emu::Set('tablename.Users', 'users');
        Emu::Set('table.Users.username', 'username');
        Emu::Set('table.Users.mail', 'mail');
        Emu::Set('table.Users.look', 'look');
        Emu::Set('table.Users.pixels', 'activity_points');
        Emu::Set('table.Users.points', 'vip_points');
        Emu::Set('table.Users.account_created', 'account_created');
        Emu::Set('table.Users.last_login', 'last_change');
        Emu::Set('table.Users.last_online', 'last_online');
        Emu::Set('table.Users.ip_register', 'ip_reg');
        Emu::Set('table.Users.ip_current', 'ip_last');
        Emu::Set('table.Users.gotw_points', 'gotw_points');

        /**
         * Users_badges tabel.
         */
        Emu::Set('tablename.Users_badges', 'user_badges');
        Emu::Set('table.Users_badges.user_id', 'user_id');
        Emu::Set('table.Users_badges.slot_id', 'badge_slot');
        Emu::Set('table.Users_badges.badge_code', 'badge_id');

        /**
         * Guilds tabel.
         */
        Emu::Set('tablename.Guilds', 'groups');
        Emu::Set('table.Guilds.user_id', 'owner_id');
        Emu::Set('table.Guilds.date_created', 'created');

        /**
         * Guilds_members table.
         */
        Emu::Set('tablename.Guilds_members', 'group_memberships');
        Emu::Set('table.Guilds_members.user_id', 'user_id');
        Emu::Set('table.Guilds_members.guild_id', 'group_id');
        Emu::Set('table.Guilds_members.level_id', 'rank');

        Emu::Set('tablename.Habbo_club', 'user_subscriptions');
        Emu::Set('table.Habbo_club.subscription_id', 'subscription_id');
        Emu::Set('table.Habbo_club.timestamp_expire', 'timestamp_expire');
        Emu::Set('table.Habbo_club.timestamp_activate', 'timestamp_activate'); 
      
        /**
         * Rooms table.
         */
        Emu::Set('table.rooms.owner_id', 'owner');
        Emu::Set('table.rooms.name', 'caption');
      
         /**
         * Namechanges
         */   
        Emu::Set('tablename.Namechange_log', 'logs_client_namechange');
      
         /**
         * Chatlogs
         */   
        Emu::Set('tablename.Chatlogs_room', 'chatlogs');
        Emu::Set('table.chatlogs_room.from_id', 'user_id');
      
        Emu::Set('tablename.Chatlogs_private', 'chatlogs_console');
        Emu::Set('tablename.chatlogs.from_id', 'user_id');
      
        /**
         * Custom query's.
         */
        Emu::Set('query.quickmenu.friends', 'SELECT user_two_id FROM messenger_friendships WHERE user_one_id =%i LIMIT 100');
    }
  
 
    public static function addUsers($array){
        $token = new Token();
        
        $data = array(       
            'username' => $array['username'],
            'password' => $token->password($array['password']),
            'mail' => $array['email'],
            'account_created' => time(),
            'last_online' => time(),
            'motto' => Config::get('motto'),
            'look' => $array['habbo-avatar'],
            'rank' => Config::get('rank'),
            'credits' => Config::get('credits'),
            'activity_points' => Config::get('pixels'),
            'vip_points' => Config::get('points'),
            'auth_ticket' => $token->authTicket(),
            'ip_reg' => Core::getIpAddress(),
            'ip_last' => Core::getIpAddress()
        );

        return QueryBuilder::table(Emu::Get('tablename.Users'))
            ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
            ->insert($data);
    }
}
