<?php
namespace config;

use app\controls\reportBug;

class connect_db 
{

	private $user_DB ;
	private $pass_DB ;
	private $data_DB ;
	private $host_DB ;
    
	
	public function __construct() 
	{
        
        //************************************************
    	$this->user_DB = 'root';
		$this->pass_DB = '';
		$this->data_DB = 'imobi';
		$this->host_DB = 'localhost';
        /* Ativa as exceptions */
        mysqli_report(MYSQLI_REPORT_STRICT|MYSQLI_REPORT_ERROR);
	}
	
	public function connect() 
	{
        try
        {
            $con = new \mysqli($this->host_DB, $this->user_DB, $this->pass_DB, $this->data_DB);
		    mysqli_set_charset($con, 'utf8');
            return $con; 
        } 
        catch ( mysqli_sql_exception $e ) 
        {
            $error_log = new reportBug($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'Impossível se conectar com o Banco de Dados.', 'ERROR', date('Y/m/d  H:i:s') );
            $error_log->writeLog();
            $error_log->sendMail();
        } 
        
		
	}

	public function close()
	{
		return mysqli_close(connect_db::connect());
	}
  

}


?>