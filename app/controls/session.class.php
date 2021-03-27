<?php
namespace app\controls;
use app\controls\Browser;
use config\connect_db;

require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

class session 
{
    private $con = null;
    
    private $user_id = null;
    
    private $user = null;
    
    private $user_active = null;
    
    private $user_type = null;
    
    private $browser = null;
    
    private $ip = null;
    
    private $session_uid = null;
    
    private $token = null;
    
    private $session_expire = null;
    
    private static $cookie_name = 'auth-loires';
    
    /* Prazo de validade da sessão - default = 60 */
    private static $session_life = 60 ;
    
    public function __construct($user_id)
    {
        if(empty($user_id))
        {
            $this->destroySession();
            return false;
        }
        else
        {
            $this->user_id = $user_id; 
            return true;
        }
    }
    
    public function initSession()
    {
        $con_db = new connect_db();
        $this->con = $con_db->connect(); 
        
        session_name(SESSION_NAME);
        session_start();
        session_regenerate_id();
    }
    
    public function setSessionValues()
    {
        $session_values = $this->con->prepare("SELECT login, type, user_name, user_profile_photo FROM sec_users LEFT JOIN user_profile ON (sec_users.id = user_profile.user_id) WHERE sec_users.id = ? ");
        $session_values->bind_param('i', $this->user_id);
        $session_values->execute();
        $session_values->store_result();
        $session_values->bind_result($login, $type, $user_name, $user_photo);
        $session_values->fetch();
        $rows = $session_values->affected_rows;
        $session_values->free_result();
        $session_values->close();
        
        if($session_values and $rows > 0)
        {
            $_SESSION['user_auth'] = 'Y';
            $_SESSION['user_id'] = $this->user_id;
            $_SESSION['user'] = $login;
            $_SESSION['user_type'] = $type;
            if (empty($user_photo) or $user_photo === null) 
            {
                $_SESSION['user_photo'] = SUBDOMAIN_IMGS.'/defaults/default-user.png'; 
            }
            else
            {
               $_SESSION['user_photo'] = (fileRemoteExist(SUBDOMAIN_IMGS.$user_photo) === true) ? SUBDOMAIN_IMGS.$user_photo : SUBDOMAIN_IMGS.'/defaults/default-user.png'; 
            }
            $_SESSION['user_name'] = (empty($user_name) or $user_name === null) ? 'Anônimo' : $user_name ;
            $_SESSION['secret_form_token'] = md5(SECRET_FORM_TOKEN.$this->user_id.$login);
        }   
    }
    
    public function registerSession()
    {
        $session_id = md5( uniqid( mt_rand(), true ).microtime() );
        $token = sha1( uniqid( mt_rand() + time(), true ) );
        $session_expire = ( time() + (self::$session_life * 24 * 3600 ) );
        $ip = $_SERVER['REMOTE_ADDR'];
        $browser = new Browser();
        $browser = strip_tags($browser->getBrowser());
        
        $_SESSION['token'] = $token;

        $date_created = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        
        $register_session = $this->con->prepare("INSERT INTO sessions (session_id, user_id, token, browser, ip, date_created) VALUES (?, ?, ?, ?, ?, NOW())");
        $register_session->bind_param('sisss', $session_id, $this->user_id, $token, $browser, $ip);
        $register_session->execute();
        $rows = $register_session->affected_rows;
        $register_session->close();
        
        return ($register_session and $rows > 0) ? true : false ;
    }
    
    public function updateSession()
    {
        $update_session = $this->con->query("UPDATE sessions SET last_access = NOW(), user_status = 'ON' WHERE user_id = $this->user_id");
        return ($update_session) ? true : false ;
    }

    public function loadUserPerms()
    {
        $user_perms = $this->con->prepare("SELECT perms FROM user_perms WHERE user_id = ? ");
        $user_perms->bind_param('i', $this->user_id);
        $user_perms->execute();
        $user_perms->store_result();
        $user_perms->bind_result($perms);
        $user_perms->fetch();
        $rows = $user_perms->affected_rows;
        $user_perms->free_result();
        $user_perms->close();
        
        if($user_perms and $rows > 0)
        {
            $perms = explode(',', $perms);
            foreach($perms as $value)
            {
                $_SESSION[$value] = 'Y';
            }
            return true;
            
        }
        else
        {
            return false;
        }
    }
    
    public function loadSystemConfigurations()
    {
        $system_config = $this->con->query("SELECT * FROM system_configs");
        $rows = $system_config->num_rows;
        
        while($reg = $system_config->fetch_assoc())
        {
            $_SESSION['system-configs'] = 'Y';
            $_SESSION['slide_lg'] = $reg['slide_lg'];
            $_SESSION['slide_md'] = $reg['slide_md'];
            $_SESSION['slide_sm'] = $reg['slide_sm'];
            $_SESSION['slide_xs'] = $reg['slide_xs'];
            $_SESSION['slide_image_quality'] = $reg['slide_image_quality'];
            $_SESSION['vehicles_image_quality'] = $reg['vehicles_image_quality'];
            $_SESSION['gallery_image_quality'] = $reg['gallery_image_quality'];
            $_SESSION['news_image_quality'] = $reg['news_image_quality'];
            $_SESSION['banners_image_quality'] = $reg['banners_image_quality'];
            $_SESSION['advertising_image_quality'] = $reg['advertising_image_quality'];
        }
        $system_config->close();
        return ($system_config and $rows > 0) ? true : false ;
    }
    
    

	public function destroySession () 
	{   
		$_SESSION = array();
        unset($_SESSION);
   		session_destroy();
    
		if(empty($_SESSION) or !isset($_SESSION))
		{
		  	return true;
		}
		else 
		{
			return false;
		}
    
	}
	
}

