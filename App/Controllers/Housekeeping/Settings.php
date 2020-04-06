<?php
namespace App\Controllers\Housekeeping;

use \Core\View;

use \App\Flash;
use \App\Models\Core;
use \App\Models\Housekeeping;

class Settings extends \App\Controllers\Housekeeping\Authenticated
{
    public function __construct()
    {
        $this->data = new \stdClass();
        $this->load()->libaries('validate');
    }
  
    public function homeAction()
    {            
        $this->data->settings = Core::getAllFromSettings();

        View::renderTemplate('Housekeeping/Management/settings.html', [
            'permission' => 'housekeeping_settings',
            'data' => $this->data
        ]);
    }
  
    public function changeAction()
    {
        if($this->request->all())
        {
            if($this->request->input('cmssettings'))
            {
                $sitename       = $this->request->input('sitename');
                $hotelname      = $this->request->input('hotelname');
                $siteurl        = rtrim($this->request->input('siteurl'), '/') . '/';
                $emulator       = $this->request->input('emulator');
                $recaptchakey   = $this->request->input('recaptchakey');
                $motto          = $this->request->input('motto');
                $maintenance    = $this->request->input('maintenance');
                $credits        = $this->request->input('credits');
                $points         = $this->request->input('points');
                $pixels         = $this->request->input('pixels');

                $this->validate->name('Sitenaam')->value($sitename)->min(2)->max(20)->required();
                $this->validate->name('Hotelnaam')->value($hotelname)->min(2)->max(20)->required();
                $this->validate->name('Siteurl')->value($siteurl)->min(4)->max(50)->pattern('url')->is_url($siteurl);
                $this->validate->name('Recaptcha')->value($recaptchakey)->required();
                $this->validate->name('Motto')->value($motto)->required()->max(50)->required();
                $this->validate->name('Credits')->value($credits)->min(1)->max(8)->required()->is_int($credits);
                $this->validate->name('Pixels')->value($pixels)->min(1)->max(8)->required()->is_int($pixels);
                $this->validate->name('Diamanten')->value($points)->min(1)->max(8)->required()->is_int($points);
              
                if($this->validate->isSuccess())
                {
                    if(Housekeeping::changeCmsSettings($sitename, $hotelname, $siteurl, $emulator, $recaptchakey, $motto, $maintenance, $credits, $points, $pixels))
                    {
                        Flash::addMessage('Je hotel instellingen zijn aangepast');
                        Core::logger("Hotel settings changed", 'change');
                        $this->redirect('/housekeeping/settings/home');
                    }
                }
            } 
            else if($this->request->input('clientsettings'))
            {
                $ipadress           = $this->request->input('ipaddress');
                $port               = $this->request->input('port');
                $habboswf           = rtrim($this->request->input('habboswf'), '/') . '/';
                $habboswffolder     = rtrim($this->request->input('habboswffolder'), '/') . '/';
                $externaltexts      = $this->request->input('externaltexts');
                $overridetexts      = $this->request->input('overridetexts');
                $externalvariables  = $this->request->input('externalvariables');
                $overridevariables  = $this->request->input('overridevariables');
                $furnidata          = $this->request->input('furnidata');
                $productdata        = $this->request->input('productdata');
                $flashclient        = $this->request->input('flashclient');

                $this->validate->name('Ipaddress')->value($ipadress)->min(8)->max(20)->required();
                $this->validate->name('Port')->value($port)->min(4)->max(8)->required()->is_int($port);
                $this->validate->name('Habboswf')->value($habboswf)->min(4)->max(50)->pattern('url')->is_url($habboswf);
                $this->validate->name('Habboswffolder')->value($habboswffolder)->required();
                $this->validate->name('Externaltexts')->value($externaltexts)->required()->max(100)->required();
                $this->validate->name('Overridetexts')->value($overridetexts)->required()->max(100)->required();
                $this->validate->name('Externalvariables')->value($externalvariables)->required()->max(100)->required();
                $this->validate->name('Overridevariables')->value($overridevariables)->required()->max(100)->required();
                $this->validate->name('Furnidata')->value($furnidata)->required()->max(100)->required();
                $this->validate->name('Productdata')->value($productdata)->required()->max(100)->required();
                $this->validate->name('Flashclient')->value($flashclient)->required()->max(100)->required();

                if($this->validate->isSuccess())
                {
                    if(Housekeeping::changeClientSettings($ipadress, $port, $habboswf, $habboswffolder, $externaltexts, $overridetexts, $externalvariables, $overridevariables, $furnidata, $productdata, $flashclient))
                    {
                        Flash::addMessage('Je client instellingen zijn aangepast');
                        Core::logger("Client settings changed", 'change');
                        $this->redirect('/housekeeping/settings/home');
                    }
                }
            }
          

        }
      
        $this->homeAction();
    }
  
}