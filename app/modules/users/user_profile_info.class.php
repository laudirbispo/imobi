<?php
namespace app\models\users;

use config\connect_db;
use app\controls\errors;


class user_profile_info
{
  
    public $user_name;
  
    public $user_profile_photo;
  
    public $user_profile_about;
  
    public $user_profile_facebook;
  
    public $user_profile_google;
  
    public $user_profile_twitter;
  
    public $user_profile_linkedin;
  
    public $user_profile_email;
   
    
    public function loadProfile($user_id)
    {
        if( empty($user_id) )
        {
       
            return  $_SESSION['alert_errors'][] = '<LI><a href=""> <i class="fa fa-exclamation-triangle text-red"></i> <small>Ocorreu uma falha ao carregar suas informações pessoais. Reinicie a sessão</small></a></LI>';
        }
        else
        {
            $con_db = new connect_db();
            $con = $con_db->connect();
       
           $profile = $con->query(" SELECT * FROM `user_profile` WHERE `id_user` = '$user_id' ") or die(mysqli_error($con)); ;
           $num_rows = $profile->num_rows;
       
           if( $num_rows === 1 )
           {
               while( $reg = $profile->fetch_array() )
               {
                   $this->user_name             = $reg['user_name'];
                   $this->user_profile_photo    = $reg['user_profile_photo']; 
                   $this->user_profile_about    = $reg['user_profile_about'];
                   $this->user_profile_email    = $reg['user_profile_email']; 
                   $this->user_profile_facebook = $reg['user_profile_facebook'];
                   $this->user_profile_linkedin = $reg['user_profile_linkedin'];
                   $this->user_profile_google   = $reg['user_profile_google'];
                   $this->user_profile_twitter  = $reg['user_profile_twitter'];     
               }
         
               return true;
           }
           else
           {
               return  $_SESSION['notifications_system'][] = '<LI><a href=""> <i class="fa fa-exclamation-triangle text-red"></i> <small>Ocorreu uma falha ao carregar suas informações pessoais. Reinicie a sessão</small></a></LI>';
           }
       
            $profile->close();
       
        }
     
     
    }
   

   
   public function user_photo()
   {
       if( empty($this->user_profile_photo) )
       {
           $_SESSION['notifications_system'][] = '<LI><a href=""> <i class="fa fa-picture-o text-red"></i> <small> Você ainda não escolheu uma imagem de perfil! Clique aqui para escolher um imagem.</small></a></LI>';
       return '/app/images/default-user.png';
       }
       else
       {
           return $this->user_profile_photo;
       }
   }
  
 
   
  
}

?>