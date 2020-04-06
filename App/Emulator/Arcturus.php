<?php
namespace App\Emulator;

use \App\Models\Core;
use \App\Config;
use \App\Token;

use \Core\Emu;

use QueryBuilder;
use PDO;

class Arcturus {
  
    public function __construct(){
        $this->setTables();
    }

    public function setTables(){
        Emu::Set('table.Bans.type', 'type');
        Emu::Set('table.Bans.user_id', 'user_id');
        Emu::Set('table.Bans.type.account', 'account');
        Emu::Set('table.Bans.type.expire', 'ban_expire');

        /**
         * Permissions tabel.
         */
        Emu::Set('tablename.Permissions', 'permissions');
        Emu::Set('table.Permissions.level', 'level');
        Emu::Set('table.Permissions.rank_name', 'rank_name');

        /**
         * Users tabel.
         */
        Emu::Set('tablename.Users', 'users');
        Emu::Set('table.Users.username', 'username');
        Emu::Set('table.Users.mail', 'mail');
        Emu::Set('table.Users.look', 'look');
        Emu::Set('table.Users.pixels', 'pixels');
        Emu::Set('table.Users.points', 'points');
        Emu::Set('table.Users.account_created', 'account_created');
        Emu::Set('table.Users.last_login', 'last_login');
        Emu::Set('table.Users.last_online', 'last_online');
        Emu::Set('table.Users.ip_register', 'ip_register');
        Emu::Set('table.Users.ip_current', 'ip_current');

        /**
         * Users_badges tabel.
         */
        Emu::Set('tablename.Users_badges', 'users_badges');
        Emu::Set('table.Users_badges.user_id', 'user_id');
        Emu::Set('table.Users_badges.slot_id', 'slot_id');
        Emu::Set('table.Users_badges.badge_code', 'badge_code');

        /**
         * Guilds tabel.
         */
        Emu::Set('tablename.Guilds', 'guilds');
        Emu::Set('table.Guilds.user_id', 'user_id');
        Emu::Set('table.Guilds.date_created', 'date_created');

        /**
         * Guilds_members table.
         */
        Emu::Set('tablename.Guilds_members', 'guilds_members');
        Emu::Set('table.Guilds_members.user_id', 'user_id');
        Emu::Set('table.Guilds_members.guild_id', 'guild_id');
        Emu::Set('table.Guilds_members.level_id', 'level_id');

        /**
         * Rooms table.
         */
        Emu::Set('table.rooms.owner_id', 'owner_id');
        Emu::Set('table.rooms.name', 'name');

         /**
         * Namechanges
         */   
        Emu::Set('tablename.Namechange_log', 'namechange_log');
      
         /**
         * Chatlogs
         */   
        Emu::Set('tablename.Chatlogs_room', 'chatlogs_room');
        Emu::Set('table.chatlogs_room.from_id', 'user_from_id');
      
        Emu::Set('tablename.Chatlogs_private', 'chatlogs_private');
        Emu::Set('tablename.chatlogs.from_id', 'user_from_id');



        /**
         * Custom query's.
         */
        Emu::Set('query.quickmenu.friends', 'SELECT user_two_id FROM messenger_friendships WHERE user_one_id =%i ORDER BY friends_since LIMIT 100');
    }
  
 
    public static function addUsers($array){
        $token = new Token();
        
        $data = array(
            'username' => $array['username'],
            'password' => $token->password($array['password']),
            'mail' => $array['email'],
            'account_created' => time(),
            'last_login' => time(),
            'motto' => Config::get('motto'),
            'look' => $array['habbo-avatar'],
            'rank' => Config::get('rank'),
            'credits' => Config::get('credits'),
            'pixels' => Config::get('pixels'),
            'points' => Config::get('points'),
            'auth_ticket' => $token->authTicket(),
            'ip_register' => Core::getIpAddress(),
            'ip_current' => Core::getIpAddress()
        );

        return QueryBuilder::table(Emu::Get('tablename.Users'))
            ->setFetchMode(PDO::FETCH_CLASS, get_called_class())
            ->insert($data);
    }
}
