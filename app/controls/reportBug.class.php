<?php 
namespace app\controls;
require_once($_SERVER['DOCUMENT_ROOT'].'/libs/PHPMailer/PHPMailerAutoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');

class reportBug extends \PHPMailer
{
    /* Pasta para salvar o arquivo de log - default = logs */
    public  $patch_log = '/logs';
    
    /* Host em que o sistema está hospedado e enviará o e-mail */
    public  $host = SMTP_SERVER;
    
    /* Endereço do e-mail de envio */
    public  $email_auth = 'errors_log@goldstarmoveis.com.br';
    
    /* Senha para autenticação */
    public  $pass_auth = '#FFF#ccc#000';   
    
    /* E-mail que receberá os logs. - Não altere está linha */
    public  $email_send = 'errors_log@grupositefacil.com.br';
    
    /* Site name - Identificador da origem do erro */
    public  $site_name = 'Meu site.com';
    
    /* Site name - Identificador da origem do erro */
    public  $assunto = 'Alerta de error:';
    
    /* Página, script ou ação que gerou o erro */
    private $source_error;
    
    /* Error - Pode ser erro do sistema ou personalizado */
    private $error_message = 'Default Message';
    
    /* Tipo do erro - Desconhecido, warning, error, info */
    private $error_type;
    
    /* Data e hora do erro */
    private $error_time;
    
    public function __construct($source, $message, $type, $time)
    {
        $this->source_error = $source;
        $this->error_message = $message;
        $this->error_type = $type;
        $this->error_time = $time;
    }
    
    public function sendMail()
    {
        $send = $this->IsSMTP();            // set mailer to use SMTP
        $send = $this->SMTPAuth = true;     // turn on SMTP authentication
        $send = $this->Host     = $this->host;
        $send = $this->Username = $this->email_auth;
        $send = $this->Password = $this->pass_auth;
        $send = $this->SMTPsecure = 'tls' ;

        // na classe, há a opção de idioma, setei como br
        $send = $this->SetLanguage("br");
        // ativa o envio de e-mails em HTML, se false, desativa.
        $send = $this->IsHTML(true); 

        // email do remetente da mensagem
        $send = $this->From = $this->email_auth;
        // nome do remetente do email
        $send = $this->FromName = $this->site_name;
        // Endereço de destino do email, ou seja, pra onde você quer que a mensagem do formulário vá?
        $send = $this->AddAddress($this->email_send);
        // informando no email, o assunto da mensagem
        $send = $this->Subject = $this->assunto; 
        // Define o texto da mensagem (aceita HTML)
        $send = $this->Body .= "<p><strong>Alerta de falha - ".$this->site_name."  </strong> </p><br>";
        $send = $this->Body .= "<p>[".$this->error_time."][TIPO:".$this->error_type."][LOCAL:".$this->source_error."]: ".$this->error_message."</p>";
        $send = $this->Send();
        if(!$send )
        {
            $this->writeLog();
            return false;
        }
        else
        {
            return true;
        }

    }
    
    public function writeLog()
    {
        date_default_timezone_set('America/Sao_Paulo');
        $data = date("d-m-y");
        $arq_name = 'LOG-'.$data.'.txt';
        
        if( !file_exists($_SERVER['DOCUMENT_ROOT'].$this->patch_log) )
        {
            mkdir($_SERVER['DOCUMENT_ROOT'].$this->patch_log, 0777, true);
        }
        
        $arquivo = $_SERVER['DOCUMENT_ROOT'].$this->patch_log.'/'.$arq_name;
        $log = "[".$this->error_time."][TIPO: ".$this->error_type."][URL: ".$this->source_error."]: ".$this->error_message. "\r\n\r\n" ; 
        
        $write_log = fopen("$arquivo", "a+b");
        fwrite($write_log, $log);
        fclose($write_log);
    }
    
}
