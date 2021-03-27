<?php

spl_autoload_register(function ($class) 
{
  $class = str_replace('\\', '/', $class);
  require_once($_SERVER['DOCUMENT_ROOT'].'/'.$class.'.class.php');
    
});




