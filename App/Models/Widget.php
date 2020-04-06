<?php
namespace App\Models;

use \Core\View;
use \Core\Loader;
use \App\Auth;
use \App\Flash;
use \App\Models\User;

use QueryBuilder;
use PDO;

class Widget extends \Core\Model
{
    public static $inArray = array();
    
    public static function loader($widget)
    {
        $loader =  (new Loader())->widgets($widget);
        if($loader)
        {
            return $loader->set(Auth::getUser());
        }
      else
        {
            //Flash::addMessage('Class not found: /Widgets/' . ucfirst($widget), FLASH::WARNING);
        }
    }
  
    public static function getWidgets($param)
    {
        $query = QueryBuilder::table('cms_widgets')->join('cms_widget_to_page', 'cms_widgets.id', '=', 'cms_widget_to_page.widget_id')
                    ->join('cms_pages', 'cms_widget_to_page.page_id', '=', 'cms_pages.id')->where('cms_pages.blockname', $param)->get();
        
        foreach($query as $row)
        {
            $inArray[$row->blockname][] = $row->widget;
            $inArray[$row->widget] = self::loader($row->widget);
        }
        return $inArray ?? null;
    }
  
    public static function getAllWidgets()
    {
        return QueryBuilder::table('cms_pages')->get();
    }
  
    public static function getWidgetById($id)
    {
        return QueryBuilder::table('cms_pages')->select('cms_widgets.*')->select('cms_pages.*')->select('cms_widget_to_page.id')
                  ->join('cms_widget_to_page', 'cms_pages.id', '=', 'cms_widget_to_page.page_id')
                  ->join('cms_widgets', 'cms_widget_to_page.widget_id', '=', 'cms_widgets.id')->where('cms_widget_to_page.page_id', $id)->get();
    }
  
    public static function deleteFromWidgetId($id)
    {
        return QueryBuilder::table('cms_widget_to_page')->where('id', $id)->delete();
    }
 
} 