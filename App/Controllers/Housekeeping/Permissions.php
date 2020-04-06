<?php
namespace App\Controllers\Housekeeping;

use \Core\View;
use \Core\Emu;

use \App\Flash;
use \App\Auth;
use \App\Models\Core;
use \App\Models\Housekeeping;

class Permissions extends \App\Controllers\Housekeeping\Authenticated
{
  
    public function __construct()
    {
        $this->load()->libaries('validate');
        $this->role = 'housekeeping_permissions';
    }
  
    public function indexAction($data = null)
    {    
        View::renderTemplate('Housekeeping/Management/permissions.html', [
            'permission' => $this->role
        ]);
    }
  
    public function searchAction($roleid = null)
    { 
        if($this->request->all() || isset($roleid))
        {         
            $data = new \stdClass();
      
            $permissionsById = Housekeeping::getPermissionsData($roleid ?? $this->request->input('element'));     
            if($permissionsById)
            {
                $data->permissionById = $permissionsById;
            }
   
            $data->roleid = $roleid ?? $this->request->input('element');

            View::renderTemplate('Housekeeping/Management/permissions.html', [
                'permission' => $this->role,
                'data' => $data
            ]); 
            
        }
        else
        {
            $this->redirect('/housekeeping/manage/permissions');
        }
    }
  
    public function listAction()
    {
        $data = new \stdClass();
      
        $roleid = $this->route_params['id'];
        $list   = $this->searchUsers($roleid);
        
        if(!empty($roleid))
        {
            $data->role = Core::getData('cms_permissions', 'permission', 'id', $roleid); 
            $data->list = $list;

            View::renderTemplate('Housekeeping/Management/permissions.html', [
                'permission' => $this->role,
                'type' => 'list',
                'data' => $data
            ]); 
        }
        else
        {
            Auth::returnRequestedPage();
        }
    }
  
    public function searchUsers($roleid)
    {
        $data = new \stdClass();
      
        $permissionsId  = Housekeeping::getPermissionsByRoleid($roleid);
        if($permissionsId && $roleid)
        {
            foreach($permissionsId as $permission)
            {
                $users = Housekeeping::getUsersByRank($permission->permissions_groups);
                foreach($users as $user)
                {
                    $this->username[$user->id]['user_id'] = $user->id;
                    $this->username[$user->id]['username'] = $user->username;
                    $this->username[$user->id]['last_login'] = Core::getData(Emu::Get('tablename.Users'), Emu::Get('table.Users.last_login'), Emu::Get('table.Users.username'), $user->username); 
                }
            }
            return $this->username;
        }
        else
        {
            $this->indexAction();
        }
    }
  
    public function addAction()
    {     
        if($this->request->all())
        {   
            $roleid = $this->request->input('role');
            $permissionid = $this->request->input('permission');
          
            if(!empty($roleid) && !empty($permissionid))
            {
                if(!Housekeeping::ifUserAndRoleExists($roleid, $permissionid))
                {
                    Housekeeping::insertPermission($roleid, $permissionid);
                    Flash::addMessage('Permissie toegevoegd!', FLASH::SUCCESS);
                    $this->searchAction($roleid);
                }
                else
                {
                    Flash::addMessage('De door jouw geselecteerde rol is al toegekend aan de opgegeven rank!', FLASH::ERROR);  
                    $this->redirect('/housekeeping/manage/permissions');
                }
                
            }
        }
        else
        {
            $this->indexAction();
        }
    }
  
    public function deleteAction()
    {
        $permissionid = Core::getData('cms_permissions_ranks', 'id', 'id', $this->route_params['id']); 
        $roleid = Core::getData('cms_permissions_ranks', 'permissions_groups', 'id', $this->route_params['id']); 
      
        if($permissionid)
        {
            Housekeeping::deletePermission($permissionid);
            Flash::addMessage('Permissie succesvol verwijderd', FLASH::SUCCESS);
            $this->searchAction($roleid);
        }
        else
        {
            $this->indexAction();
        }
    }
}
    