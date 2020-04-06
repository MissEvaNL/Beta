<?php
namespace Core;

class Loader{
    /**
     *  Child instance
     *
     *  @var object
     */
    private $instance;
    public $name;

    public function __construct($instance = null){
        $this->setInstance($instance);
    }

    public function input(){
        $this->getInstance()->request = new Request();
    }
    
    public function libaries($targetvar){
        $namespace = '\Libaries\\' . ucfirst($targetvar);
        $this->getInstance()->{$targetvar} = new $namespace();
    }
  
    public function emulator($targetvar){
        $namespace = '\App\Emulator\\' . ucfirst($targetvar);
        return new $namespace();
    }
  
    public function widgets($targetvar)
    {
        $namespace = '\App\Widgets\\' . ucfirst($targetvar);
        if(class_exists($namespace)){
            return new $namespace();
        }
    }
    /**
     * @return object
     */
    private function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param object $instance
     */
    private function setInstance($instance)
    {
        $this->instance = $instance;
    }

}