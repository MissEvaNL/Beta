<?php
namespace Core;

class Emu {
  
  static $Emu = array();

  public static function All()
  {
    return self::$Emu;
  }
  
  public static function Get($table)
  {
    return self::$Emu[$table];
  }
  
  public static function Set($table, $column)
  { 
    self::$Emu[$table] = $column;
  }

}