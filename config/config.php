<?php
define('DOMINIO', 'https://imobiliaria.loires.com.br');
/**
 * @const SITE_NAME - nome da pasta remota para upload de arquivos externos ;
*/
define('SITE_NAME', 'Coimbra Imóveis');
/**
 *  configurações para upload de imagens em subdominio ;
*/
// verificar o HTTPS - HTTP
define('SUBDOMAIN_IMGS', 'https://cdn.loires.com.br');
define('SUBDOMAIN_FTP', '31.170.163.38');
define('USER_FTP', 'imobi@imobiliaria.loires.com.br');
define('PASS_FTP', '3JKe%K*$?mGT');
/**
 * @const SESSION_NAME - diferencia a sessão para cada dominio ;
*/
define('SESSION_NAME', 'f5fatdfs5');
define('SMTP_SERVER',  'smtp.loires.com.br');
define('SECRET_FORM_TOKEN', 'FFxPtDe03SDklW2pv');

/**
 * Identificação do sistema para tabelas de configurações;
 * Todas as tabelas marcadas como settings_ deve ter um uniqueID com o valor abaixo;
 * Cada sistema deve ter o SYSTEM_ID diferente;
 * Tamanho deve ser sempre 16 CHARS UTF-8
*/
define('SYSTEM_ID', 'FsYcoUg7WodXwdkY');

/**
 * Configurações do ambiente de desenvolvimento 
 * 1 mostra todos os erros, 0 desativa
*/
define('DESENVOLVIMENTO', '1');

/* Permite o acesso aos Cookies somente por HTTP */
session_set_cookie_params(0, "/", $_SERVER['HTTP_HOST'], 0);

/* Tempo de vida das sessões */
ini_set('session.gc_maxlifetime',3600); 

/* Cookies somente acessiveis por HTTP */
ini_set('session.cookie_httponly', 1); 

/* Configurações de data e hora  */
date_default_timezone_set('America/Sao_Paulo');
$date_time = date('Y/m/d  H:i:s');
$date      = date('Y/m/d');
$time      = date('H:i:s');

if(DESENVOLVIMENTO == 1)
{
    ini_set('display_errors', 1 );
    error_reporting(E_ALL); 
}
else
{
    error_reporting(0);
    ini_set('display_errors', 0 );   
}

/* Tamanho do display do DEBUG */
ini_set('xdebug.var_display_max_depth', 10000);
ini_set('xdebug.var_display_max_children', 1000000);
ini_set('xdebug.var_display_max_data', 2000000);

/** $active_modules = Array com módulos ativos no sistema 
 * 
 * Caso não esteja no array, os usuário não poderão usar;
 * Todos os módulos:
 *
 * ('news', 'scrap', 'gallery', 'top', 'advertising', 'events', 'vehicles', 'slide', 'customers', 'products', 'blog', 'statistics', 'promotions', 'survey', 'properties', 'team', 'programming', 'settings', 'representatives', 'banners', 'downloads')
 *
 */

$active_modules = array('contracts', 'clients', 'customers', 'gallery',  'slide', 'customers', 'properties', 'team', 'settings', 'banners');

