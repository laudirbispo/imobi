<?php
namespace app\controls;
use config\connect_db;

class perms
{
    
   public function loadPerms($user_id)
   {
     
     $con_db = new connect_db();
     $con = $con_db->connect();
     
     $perms = $con->query(" SELECT * FROM `user_perms` WHERE `id_user` = '$user_id' LIMIT 1 ") ;
     $num_rows = $perms->num_rows;
     
     if($perms and $num_rows > 0 )
     {
         
         
         while( $reg= $perms->fetch_array() )
         {
             $_SESSION['advertising_read']   = $reg['advertising_read'];
         }
       
         return true;
     }
     else
     {
       return false;
     }
     
     $perms->close();
     
     
   }
   
   /* verifica a permisão para leitura e acesso a página
    *
    * @param[1] = $module 
    *
    * @param[2] = $action  // read, edit, delete, create
    *
    * return = true or false
    *
    */
    
    public function isAllowedModule($module, $action)
    {
        if( $_SESSION['user_master_perms'] === 'administrador' )
        {
            return 'yes';
        }
        else if( !isset($_SESSION[$module.'_'.$action])  )
        {
            return 'no';
        }
        else if ($_SESSION[$module.'_'.$action] === 0 )
        {
            return 'no';
        }
        else if( $_SESSION[$module.'_'.$action] === 1 )
        {
            return 'yes';
        }
        else
        {
            return 'no';
        }
        
    }
   
   
}