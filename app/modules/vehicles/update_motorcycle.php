<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['vehicles_edit'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if( empty($_POST['form-token']) or !isset($_POST['form-token']) )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( empty($_POST['user_id']) or !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'error',
        'message' => 'É necessário estar logado para completar está ação.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
  $user_id = filterString($_POST['user_id'], 'INT');
}
//****************************************************************************

$error_message = '';
if( empty($_POST['id-motorcycle']) or !isset($_POST['id-motorcycle']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não identificamos o veículo que está tentando editar.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $id_motorcycle = filterString($_POST['id-motorcycle'], 'INT');
}

if( empty($_POST['motorcycle-marca']) or !isset($_POST['motorcycle-marca']) )
{
    $error_message .= '<p>Informe a marca do veículo</p>';
}
else
{
    $motorcycle_marca = filterString($_POST['motorcycle-marca'], 'CHAR');
}

if( empty($_POST['motorcycle-modelo']) or !isset($_POST['motorcycle-modelo']) )
{
    $error_message .= '<p>Informe a modelo do veículo.</p>';
}
else
{
    $motorcycle_modelo = filterString($_POST['motorcycle-modelo'], 'CHAR');
}

if( empty($_POST['motorcycle-submodelo']) or !isset($_POST['motorcycle-submodelo']) )
{
    $error_message .= '<p>Informe o submodelo.</p>';
}
else
{
    $motorcycle_submodelo = filterString($_POST['motorcycle-submodelo'], 'CHAR');
}

if( empty($_POST['motorcycle-ano-fab']) or !isset($_POST['motorcycle-ano-fab']) )
{
    $error_message .= '<p>Informe o ano de fabricação.</p>';
}
else
{
    $motorcycle_ano_fab = filterString($_POST['motorcycle-ano-fab'], 'INT');
}

if( empty($_POST['motorcycle-ano-mod']) or !isset($_POST['motorcycle-ano-mod']) )
{
    $error_message .= '<p>Informe o ano de fabricação.g</p>';
}
else
{
    $motorcycle_ano_mod = filterString($_POST['motorcycle-ano-mod'], 'INT');
}

if( empty($_POST['motorcycle-estilo']) or !isset($_POST['motorcycle-estilo']) )
{
    $error_message .= '<p>Informe o estilo da moto.</p>';
}
else
{
    $motorcycle_estilo = filterString($_POST['motorcycle-estilo'], 'CHAR');
}

if( empty($_POST['motorcycle-valor']) or !isset($_POST['motorcycle-valor']) )
{
    $error_message .= '<p>Informe o valor do veículo.</p>';
}
else
{
    $motorcycle_valor = filterString($_POST['motorcycle-valor'], 'CHAR');
    $motorcycle_valor = moedaDecimal($motorcycle_valor);
}

if( empty($_POST['motorcycle-km']) or !isset($_POST['motorcycle-km']) )
{
    $motorcycle_km = '';
}
else
{
    $motorcycle_km = filterString($_POST['motorcycle-km'], 'INT');
}

if( empty($_POST['motorcycle-cor']) or !isset($_POST['motorcycle-cor']) )
{
    $error_message .= '<p>Informe a cor do veículo.</p>';
}
else
{
    $motorcycle_cor = filterString($_POST['motorcycle-cor'], 'CHAR');
}

if( empty($_POST['motorcycle-combustivel']) or !isset($_POST['motorcycle-combustivel']) )
{
    $error_message .= '<p>Informe o tipo de combustível.</p>';
}
else
{
    $motorcycle_combustivel = filterString($_POST['motorcycle-combustivel'], 'CHAR');
}

if( empty($_POST['motorcycle-placa']) or !isset($_POST['motorcycle-placa']) )
{
    $motorcycle_placa= '';
}
else
{
    $motorcycle_placa = filterString($_POST['motorcycle-placa'], 'CHAR');
}

if( empty($_POST['motorcycle-loja']) or !isset($_POST['motorcycle-loja']) )
{
    $error_message .= '<p>Informe em qual das lojas encontra-se o veículo.</p>';
}
else
{
    $motorcycle_loja = filterString($_POST['motorcycle-loja'], 'CHAR');
}

if( empty($_POST['motorcycle-troca']) or !isset($_POST['motorcycle-troca']) )
{
    $error_message .= '<p>Diga-nos se aceita-se troca no veículo.</p>';
}
else
{
    $motorcycle_troca = filterString($_POST['motorcycle-troca'], 'CHAR');
}

if( empty($_POST['motorcycle-estado']) or !isset($_POST['motorcycle-estado']) )
{
    $error_message .= '<p>Diga-nos se o veículo é novo, seminovo ou usado.</p>';
}
else
{
    $motorcycle_estado = filterString($_POST['motorcycle-estado'], 'CHAR');
}

if( empty($_POST['motorcycle-unico-dono']) or !isset($_POST['motorcycle-unico-dono']) )
{
    $error_message .= '<p>Diga-nos se o veículo é de único dono ou não.</p>';
}
else
{
    $motorcycle_unico_dono = filterString($_POST['motorcycle-unico-dono'], 'CHAR');
}

if( empty($_POST['motorcycle-obs']) or !isset($_POST['motorcycle-obs']) )
{
    $motorcycle_obs = '';
}
else
{
    $motorcycle_obs = filterString($_POST['motorcycle-obs'], 'CHAR');
}


if( !empty($error_message) )
{
    $response = array(
            'status'  => 'error',
            'message' => $error_message,
            'link'    => '',
          );
    die(json_encode($response));
}
//****************************************************************************

$motorcycle_rodas           = ( !isset($_POST['motorcycle-rodas']) ) ?  0 : 1 ;
$motorcycle_computador        = ( !isset($_POST['motorcycle-computador']) ) ?  0 :  1 ;
$motorcycle_abs               = ( !isset($_POST['motorcycle-abs']) ) ?  0 :  1 ;
$motorcycle_chave_reserva     = ( !isset($_POST['motorcycle-chave-reserva']) ) ?  0 :  1 ;
$motorcycle_manual            = ( !isset($_POST['motorcycle-manual']) ) ?  0 :  1 ;
$motorcycle_neblina           = ( !isset($_POST['motorcycle-neblina']) ) ?  0 :  1 ;
$motorcycle_partida          = ( !isset($_POST['motorcycle-partida']) ) ?  0 :  1 ;
$motorcycle_escapamento           = ( !isset($_POST['motorcycle-escapamento']) ) ?  0 :  1 ;
$motorcycle_bau         = ( !isset($_POST['motorcycle-bau']) ) ?  0 :  1 ;
$motorcycle_guidon           = ( !isset($_POST['motorcycle-guidon']) ) ?  0 :  1 ;
$motorcycle_amortecedor            = ( !isset($_POST['motorcycle-amortecedor']) ) ?  0 :  1 ;
$motorcycle_objetos            = ( !isset($_POST['motorcycle-objetos']) ) ?  0 :  1 ;
$motorcycle_freio_disco            = ( !isset($_POST['motorcycle-freio-disco']) ) ?  0 :  1 ;
$motorcycle_freio_traseiro       = ( !isset($_POST['motorcycle-freio-traseiro']) ) ?  0 :  1 ;
$motorcycle_freio_dianteiro    = ( !isset($_POST['motorcycle-freio-dianteiro']) ) ?  0 :  1 ;
$motorcycle_injecao      = ( !isset($_POST['motorcycle-injecao']) ) ?  0 :  1 ;

$con_db = new config\connect_db();
$con = $con_db->connect();

$update = $con->prepare(" UPDATE `motorcycles` SET `marca` = ?, `modelo` = ?, `submodelo` = ?, `ano_fab` = ?, `ano_mod` = ?, `combustivel` =  ?, `valor` = ?, `estilo` = ?, `km` = ?, `cor` = ?, `final_placa` = ?, `obs` = ?, `local_loja` = ?, `unico_dono` = ?, `estado` = ?, `troca` = ? WHERE `id` = ? ");
$update->bind_param('sssiisssssssssssi', $motorcycle_marca, $motorcycle_modelo, $motorcycle_submodelo, $motorcycle_ano_fab, $motorcycle_ano_mod, $motorcycle_combustivel, $motorcycle_valor, $motorcycle_estilo, $motorcycle_km, $motorcycle_cor, $motorcycle_placa, $motorcycle_obs, $motorcycle_loja, $motorcycle_unico_dono, $motorcycle_estado, $motorcycle_troca, $id_motorcycle);
$update->execute();
$update->close();

if( $update ) 
{
   $opcionais = $con->prepare(" UPDATE `motorcycles_optional` SET `rodas` = ?, `abs` = ?, `chave_reserva` = ?, `manual` = ?, `computador` = ?, `injecao` = ?, `disco` = ?, `disco_dianteiro` = ?, `disco_traseiro` = ?, `porta_objetos` = ?, `amortecedor_direcao` = ?, `escapamento` = ?, `partida` = ?, `bau` = ?, `neblina` = ? WHERE `id_motorcycle` = ? ");
   $opcionais->bind_param('iiiiiiiiiiiiiiii', $motorcycle_rodas, $motorcycle_abs, $motorcycle_chave_reserva, $motorcycle_manual, $motorcycle_computador, $motorcycle_injecao, $motorcycle_freio_disco, $motorcycle_freio_dianteiro, $motorcycle_freio_traseiro, $motorcycle_objetos, $motorcycle_amortecedor, $motorcycle_escapamento, $motorcycle_partida, $motorcycle_bau, $motorcycle_neblina, $id_motorcycle);
   $opcionais->execute();
   $rows_opcionais = $opcionais->affected_rows;
   $opcionais->close();
    
    if( $opcionais)
    {
        $response = array(
            'status'  => 'success',
            'message' => 'Veículo atualizado',
            'link'    => '',
          );
        die(json_encode($response));
    }
    else
    {
       $response = array(
            'status'  => 'error',
            'message' => 'Falha ao atualizar informações.',
            'link'    => '',
          );
       die(json_encode($response));
    }
}
else
{ 
    $response = array(
            'status'  => 'error',
            'message' => 'Falha ao atualizar informações.',
            'link'    => '',
          );
    die(json_encode($response));
}
