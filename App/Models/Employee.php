<?php
namespace App\Models;

use \Core\Emu;
use \App\Auth;

use QueryBuilder;
use PDO;

class Employee extends \Core\Model
{
    public static function getRoles()
    {
        return QueryBuilder::table(Emu::Get('tablename.Permissions'))->select(QueryBuilder::raw(Emu::Get('table.Permissions.rank_name') . ' as name, id'))->where('id', '!=', 1)->orderBy('id', 'desc')->get();
    }
  
}