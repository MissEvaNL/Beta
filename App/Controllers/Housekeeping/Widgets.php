<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Auth;
use \App\Flash;
use \App\Config;

use \App\Models\Core;
use \App\Models\Widget;
use \App\Widgets\Spotlight;

use \Libaries\Json;

class Widgets extends \App\Controllers\Housekeeping\Authenticated
{
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
  
    public function indexAction()
    {            
        $this->data->widgets = Widget::getAllWidgets();
      
        foreach($this->data->widgets as $widgets)
        {
            $widgets->widget = Widget::getWidgetById($widgets->id);
        }
        
        View::renderTemplate('Housekeeping/Tools/widgets.html', [
            'permission' => 'housekeeping_cms_tools',
            'data' => $this->data
        ]);
    }
    
    public function deleteAction()
    {
        $widgetid = Core::getData('cms_widget_to_page', 'id', 'id', $this->route_params['id']);
        if($widgetid)
        {
            if(Widget::deleteFromWidgetId($widgetid))
            {
                Flash::addMessage('Widget is succesvol verwijderd!');
            }
        }
        $this->redirect('/housekeeping/manage/widgets');
    }
  
    public function spotlightAction()
    {
        $object = (object)$_POST;

        if($this->request->all())
        {
            $this->validate->name('Staffmember')->value($object->staffmember)->required();
            $this->validate->name('Staff description')->value($object->staffdescription)->required();
            $this->validate->name('Member')->value($object->member)->required();
            $this->validate->name('Description')->value($object->description)->required();
            
            $staffid  = Core::getData(Emu::Get('tablename.Users'), 'id', Emu::Get('table.Users.username'), $object->staffmember);
            $memberid = Core::getData(Emu::Get('tablename.Users'), 'id', Emu::Get('table.Users.username'), $object->member);

            if($this->validate->isSuccess())
            {
                Spotlight::updateSpotlight($staffid, $object->staffdescription, $memberid, $object->description);
                echo Json::encode(array('status' => 'success', 'msg' => 'Spotlight widget is aangepast!'));
            }
            else
            {
                echo Json::encode(array('status' => 'error', 'msg' => $object->staffmember));
            }
        }
        else
        {
            $this->redirect('/housekeeping');   
        }
    }
    
    public function discordAction()
    {
        if($this->request->all())
        {
            $discord = $this->request->input('invite');
            if(Core::updateField('cms_settings', 'discord_invite', $discord, Config::get('secretkey'), 'secretkey'))
            {
                echo Json::encode(array('status' => 'success', 'msg' => 'Discord invite link aangepast!')); 
            }
            else
            {
                echo Json::encode(array('status' => 'error', 'msg' => $discord));
            }
        }
    }
}