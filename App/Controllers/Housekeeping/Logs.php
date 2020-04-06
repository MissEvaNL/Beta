<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Models\Core;
use \App\Models\Housekeeping;

class Logs extends \App\Controllers\Housekeeping\Authenticated
{
  
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
  
    public function staffLogsAction()
    {    
        if($this->request->all())
        {
            $username   = $this->request->input('element');
            $userid     = Core::getData(Emu::Get('tablename.Users'), 'id', 'username', $username);
            $stafflogs  = Housekeeping::getStaffLogsByUser($username, $userid);
        }
        else
        {
            $stafflogs = Housekeeping::getStaffLogs();
        }

        foreach($stafflogs as $logs)
        {
            $logs->username = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $logs->user_id);

            if (is_numeric($logs->target)) {
                $logs->target = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $logs->target);  
            }
        }

        $this->data->logs = $stafflogs;
                
        View::renderTemplate('Housekeeping/Management/stafflogs.html', [
            'permission' => 'housekeeping_staff_logs',
            'data' => $this->data
        ]);
    }
  
    public function chatLogsAction()
    {    
        $chatlogs = Housekeeping::getAllChatLogs();
        
        foreach($chatlogs as $logs)
        {
            $logid          = Emu::Get('table.chatlogs_room.from_id');
            $logs->username = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.username'), 'id', $logs->$logid);
            $logs->room     = Core::getData('rooms', Emu::Get('table.rooms.name'), 'id', $logs->room_id);
        }
        
        $this->data->chatlogs = $chatlogs;
        
        View::renderTemplate('Housekeeping/Tools/chatlogs.html', [
            'permission' => 'housekeeping_chat_logs',
            'data' => $this->data
        ]);
    }
  
    public function banLogsAction()
    {    
        $bans = Housekeeping::getAllUserBans();

        foreach($bans as $ban)
        {
            $ban->bantype   = Core::getData('bans', Emu::Get('table.Bans.type'), 'id', $ban->id); 
            $ban->value     = Core::getData('bans', Emu::Get('table.Bans.user_id'), 'id', $ban->id); 
            $ban->addedby   = Core::getData('bans', Emu::Get('table.Bans.type.added_by'), 'id', $ban->id); 
        }
        
        $this->data->banlogs = $bans;
        
        View::renderTemplate('Housekeeping/Tools/banlogs.html', [
            'permission' => 'housekeeping_ban_logs',
            'data' => $this->data
        ]);
    }
  
}
    