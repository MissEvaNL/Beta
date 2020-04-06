<?php
namespace Core;

abstract class Model
{

    protected function load(){
        return new Loader($this);
    }
  

}


