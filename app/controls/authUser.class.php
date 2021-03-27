<?php
namespace app\controls;

use app\controls\blowfish_crypt;
use config\connect_db;
use app\controls\session;
use app\controls\reportBug;

class authUser extends blowfish_crypt
{   
    
    private $con = null;
  
    /**
     * Define o tempo que o usuário ficara bloqueado após exceder o limite de tentativas de login 
     * Valores somente em minutos entre 1 e 59
    */
    private static $time_bloq = 30;
    
    /**
     * Define o limite máximo de tentativas de login 
     * Padrão = 5
     */
    private static $max_attempts = 5;
    
    public $user = null;
    
    public $password = null;
    
    public $hash_password = null;
    
    public $user_active = null;
    
    public $user_id = null;
    
    /** 
     * Tipo de usuário 
     * Suporte = Equipe do suporte somente, controla tudo
     * Administrador = Dono do site, controla tudo menos o usuário God
     * Auxiliar = Realiza funções que o admin lhe forneçe permissão
     * Convidado = Normalmente só visualiza as páginas ou então, executa funções que o God lhe permite
     */
    public $user_type = null;
    
    public $last_attempts = null;
    
    public $attempts = 0;
    
    public $ip = null;
    
    public $user_bloq = null;
    
    
    public function startAuth($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->ip = $_SERVER['REMOTE_ADDR'];
        
        $con_db = new connect_db();
        $this->con = $con_db->connect();
    }
    
    public function existUser()
    {
        if( $this->user === null or $this->password === null )
        {
            return false;
        }
        else
        {
            $exist_user = $this->con->prepare("SELECT id, password, type, active FROM sec_users WHERE login COLLATE utf8_bin = ? ");
            $exist_user->bind_param('s', $this->user);
            $exist_user->execute();
            $exist_user->store_result();
            $exist_user->bind_result($id_bd, $password_bd, $type_bd, $active_bd);
            $exist_user->fetch();
            $num_rows = $exist_user->num_rows;
            $exist_user->free_result();
            $exist_user->close();
            
            if($exist_user and $num_rows > 0)
            {
                $this->user_id = $id_bd;
                $this->hash_password = $password_bd;
                $this->user_type = $type_bd;
                $this->user_active = $active_bd;
                
                return true;
            }
            else
            {
                return false;
            }
        
        }
                
    }
    
    public function isActive()
    {
       return ($this->user_active === 'Y') ?  true :  false ;    
    }
    
    /**
     * Metodo da classe extendida blowfish_crypt 
     * @param String $this->password = Senha não criptografada
     * @param String $this->hash_password = Senha criptografada vindo do banco de dados
     * @return Bool
     */
    
    public function checkPassword()
    {   
        return $this->check($this->password, $this->hash_password);
    }
    
    /* Verifica se ultrapassou as tentativas de login */
    public function isExceededAttempts()
    {       
        $attempts = $this->con->prepare("SELECT attempts, last_attempts FROM login_attempts WHERE user_id = ? AND ip = ? ");
        $attempts->bind_param('is', $this->user_id, $this->ip);
        $attempts->execute();
        $attempts->store_result();
        $attempts->bind_result( $number_attempts, $last_attempts);
        $attempts->fetch();
        $rows_attempts = $attempts->num_rows;
        $attempts->free_result();
        $attempts->close();
        
        if( $attempts and $rows_attempts > 0)
        {
            $last_attempts = $this->calcIntervalAttempts($last_attempts);
            if($number_attempts >= self::$max_attempts and $last_attempts <= self::$time_bloq)
            {
                return self::$time_bloq - $last_attempts;
            } 
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
     /* Calcula o tempo restante que o usuário está bloqueado */
    public function calcIntervalAttempts($last_attempts)
    {
        $last_attempts = strtotime($last_attempts);      
        $dif =  time() - $last_attempts;
        return ceil($dif / 60) ;        
    }
    

    public function registerAttempts()
    {
        $date_time = date('Y-m-d  H:i:s');
        $attempts = 1;
        $insert_attempts = $this->con->prepare("INSERT INTO login_attempts (user_id, attempts, last_attempts, ip) VALUES (?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE attempts = attempts+1, last_attempts = IF(attempts < 5, NOW(), last_attempts) ") ;
        $insert_attempts->bind_param('iis', $this->user_id, $attempts, $this->ip);
        $insert_attempts->execute();
        $rows = $insert_attempts->affected_rows;
        $insert_attempts->close();
        return ($insert_attempts) ? true : false ;
    }

    /* Reseta tentativas de login caso o login seja bem sucedido */
    public function resetAttempts()
    {
        $reset_attempts = $this->con->query("DELETE FROM login_attempts WHERE user_id = '$this->user_id' AND ip = '$this->ip' ");
        return ($reset_attempts) ? true : false ;       
    }
    
    public function startSession()
    {
        $session = new session($this->user_id); 
        $session->initSession();
        $session->setSessionValues();
        if($session->loadUserPerms() === false)
        {
            $error_log = new reportBug($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'Falha ao carregar permissões.', 'WARNING', date('Y/m/d  H:i:s') );
            $error_log->writeLog();
        }

        if($session->loadSystemConfigurations() === false)
        {
            $error_log = new reportBug($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'Falha ao carregar configurações do sistema.', 'WARNING', date('Y/m/d  H:i:s') );
            $error_log->writeLog();
        }
        
        // verifica se sessão foi iniciada corretamente
        return ($session->registerSession() === true) ? true : false ;
    }
    
    
    public function existSession()
    {
        if(empty($_SESSION['user_id']) or !isset($_SESSION['user_id']))
        {
            $this->destroySession();
            return false;
        }
        else
        {
            return true;
        }
    }
    
    
}