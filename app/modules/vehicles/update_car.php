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

if( empty($_POST['id-car']) or !isset($_POST['id-car']) )
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
    $id_car = filterString($_POST['id-car'], 'INT');
}

if( empty($_POST['car-marca']) or !isset($_POST['car-marca']) )
{
    $error_message .= '<p>Informe a marca do veículo</p>';
}
else
{
    $car_marca = filterString($_POST['car-marca'], 'CHAR');
}

if( empty($_POST['car-modelo']) or !isset($_POST['car-modelo']) )
{
    $error_message .= '<p>Informe a modelo do veículo.</p>';
}
else
{
    $car_modelo = filterString($_POST['car-modelo'], 'CHAR');
}

if( empty($_POST['car-submodelo']) or !isset($_POST['car-submodelo']) )
{
    $error_message .= '<p>Informe o submodelo.</p>';
}
else
{
    $car_submodelo = filterString($_POST['car-submodelo'], 'CHAR');
}

if( empty($_POST['car-ano-fab']) or !isset($_POST['car-ano-fab']) )
{
    $error_message .= '<p>Informe o ano de fabricação.</p>';
}
else
{
    $car_ano_fab = filterString($_POST['car-ano-fab'], 'INT');
}

if( empty($_POST['car-ano-mod']) or !isset($_POST['car-ano-mod']) )
{
    $error_message .= '<p>Informe o ano de fabricação.</p>';
}
else
{
    $car_ano_mod = filterString($_POST['car-ano-mod'], 'INT');
}

if( empty($_POST['car-carroceria']) or !isset($_POST['car-carroceria']) )
{
    $error_message .= '<p>Informe o tipo de carroceria.</p>';
}
else
{
    $car_carroceria = filterString($_POST['car-carroceria'], 'CHAR');
}

if( empty($_POST['car-valor']) or !isset($_POST['car-valor']) )
{
    $error_message .= '<p>Informe o valor do veículo.</p>';
}
else
{
    $car_valor = filterString($_POST['car-valor'], 'CHAR');
    $car_valor = moedaDecimal($car_valor);
}

if( empty($_POST['car-km']) or !isset($_POST['car-km']) )
{
    $car_km = '';
}
else
{
    $car_km = filterString($_POST['car-km'], 'INT');
}

if( empty($_POST['car-cor']) or !isset($_POST['car-cor']) )
{
    $error_message .= '<p>Informe a cor do veículo.</p>';
}
else
{
    $car_cor = filterString($_POST['car-cor'], 'CHAR');
}

if( empty($_POST['car-portas']) or !isset($_POST['car-portas']) )
{
    $error_message .= '<p>Informe o número de portas do veículo.</p>';
}
else
{
    $car_portas = filterString($_POST['car-portas'], 'INT');
}

if( empty($_POST['car-combustivel']) or !isset($_POST['car-combustivel']) )
{
    $error_message .= '<p>Informe o tipo de combustível.</p>';
}
else
{
    $car_combustivel = filterString($_POST['car-combustivel'], 'CHAR');
}

if( empty($_POST['car-placa']) or !isset($_POST['car-placa']) )
{
    $car_placa= '';
}
else
{
    $car_placa = filterString($_POST['car-placa'], 'CHAR');
}

if( empty($_POST['car-cambio']) or !isset($_POST['car-cambio']) )
{
    $error_message .= '<p>Informe o tipo de câmbio.</p>';
}
else
{
    $car_cambio = filterString($_POST['car-cambio'], 'CHAR');
}

if( empty($_POST['car-loja']) or !isset($_POST['car-loja']) )
{
    $error_message .= '<p>Informe em qual das lojas encontra-se o veículo.</p>';
}
else
{
    $car_loja = filterString($_POST['car-loja'], 'CHAR');
}

if( empty($_POST['car-troca']) or !isset($_POST['car-troca']) )
{
    $error_message .= '<p>Diga-nos se aceita-se troca no veículo.</p>';
}
else
{
    $car_troca = filterString($_POST['car-troca'], 'CHAR');
}

if( empty($_POST['car-estado']) or !isset($_POST['car-estado']) )
{
    $error_message .= '<p>Diga-nos se o veículo é novo, seminovo ou usado.</p>';
}
else
{
    $car_estado = filterString($_POST['car-estado'], 'CHAR');
}

if( empty($_POST['car-unico-dono']) or !isset($_POST['car-unico-dono']) )
{
    $error_message .= '<p>Diga-nos se o veículo é de único dono ou não.</p>';
}
else
{
    $car_unico_dono = filterString($_POST['car-unico-dono'], 'CHAR');
}

if( empty($_POST['car-obs']) or !isset($_POST['car-obs']) )
{
    $car_obs = '';
}
else
{
    $car_obs = filterString($_POST['car-obs'], 'CHAR');
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

$car_airbag            = ( !isset($_POST['car-airbag']) ) ?  0 : 1 ;
$car_ar_condicionado   = ( !isset($_POST['car-ar-condicioando']) ) ?  0 :  1 ;
$car_direcao           = ( !isset($_POST['car-direcao']) ) ?  0 :  1 ;
$car_alarme            = ( !isset($_POST['car-alarme']) ) ?  0 :  1 ;
$car_cd_player         = ( !isset($_POST['car-cd-player']) ) ?  0 :  1 ;
$car_rodas             = ( !isset($_POST['car-rodas']) ) ?  0 :  1 ;
$car_travas            = ( !isset($_POST['car-travas']) ) ?  0 :  1 ;
$car_vidros            = ( !isset($_POST['car-vidros']) ) ?  0 :  1 ;
$car_camera            = ( !isset($_POST['car-camera']) ) ?  0 :  1 ;
$car_abs               = ( !isset($_POST['car-abs']) ) ?  0 :  1 ;
$car_neblina           = ( !isset($_POST['car-neblina']) ) ?  0 :  1 ;
$car_couro             = ( !isset($_POST['car-couro']) ) ?  0 :  1 ;
$car_teto_solar        = ( !isset($_POST['car-teto-solar']) ) ?  0 :  1 ;
$car_estacionamento    = ( !isset($_POST['car-estacionamento']) ) ?  0 :  1 ;
$car_chave_reserva     = ( !isset($_POST['car-chave-reserva']) ) ?  0 :  1 ;
$car_manual            = ( !isset($_POST['car-manual']) ) ?  0 :  1 ;
$car_desembacador      = ( !isset($_POST['car-desembacador']) ) ?  0 :  1 ;
$car_computador        = ( !isset($_POST['car-computador']) ) ?  0 :  1 ;
$car_ar_quente         = ( !isset($_POST['car-ar-quente']) ) ?  0 :  1 ;
$car_multimidia        = ( !isset($_POST['car-multimidia']) ) ?  0 :  1 ;
$car_controles_volante = ( !isset($_POST['car-controles-volante']) ) ?  0 :  1 ;
$car_volante_regulagem = ( !isset($_POST['car-volante-regulagem']) ) ?  0 :  1 ;
$car_retrovisor        = ( !isset($_POST['car-retrovisor']) ) ?  0 :  1 ;
$car_gps               = ( !isset($_POST['car-gps']) ) ?  0 :  1 ;
$car_capota            = ( !isset($_POST['car-capota']) ) ?  0 :  1 ;
$car_farois_regulagem  = ( !isset($_POST['car-farois-regulagem']) ) ?  0 :  1 ;
$car_sensor_chuva      = ( !isset($_POST['car-sensor-chuva']) ) ?  0 :  1 ;
$car_brake_light       = ( !isset($_POST['car-brake-light']) ) ?  0 :  1 ;
$car_limpador_traseiro = ( !isset($_POST['car-limpador-traseiro']) ) ?  0 :  1 ;
$car_aerofolio         = ( !isset($_POST['car-aerofolio']) ) ?  0 :  1 ;
$car_engate            = ( !isset($_POST['car-engate']) ) ?  0 :  1 ;
$car_estribo           = ( !isset($_POST['car-estribo']) ) ?  0 :  1 ;
$car_santoantonio      = ( !isset($_POST['car-santoantonio']) ) ?  0 :  1 ;
$car_mascara_negra     = ( !isset($_POST['car-mascara-negra']) ) ?  0 :  1 ;
$car_estacionamento    = ( !isset($_POST['car-estacionamento']) ) ?  0 :  1 ;

$con_db = new config\connect_db();
$con = $con_db->connect();

$update_car = $con->prepare(" UPDATE `cars` SET `marca`= ?, `modelo`= ?, `submodelo`= ?, `ano_fab`= ?, `ano_mod`= ?, `combustivel`= ?, `valor`= ?, `carroceria`= ?, `km`= ?, `cor`= ?, `portas`= ?, `final_placa`= ?,`cambio`= ?, `obs`= ?, `local_loja`= ?, `unico_dono`= ?, `troca`= ?, `estado`= ? WHERE `id` = ? ");
$update_car->bind_param('sssiisssssssssssssi', $car_marca, $car_modelo, $car_submodelo, $car_ano_fab, $car_ano_mod, $car_combustivel, $car_valor, $car_carroceria, $car_km, $car_cor, $car_portas, $car_placa, $car_cambio, $car_obs, $car_loja, $car_unico_dono, $car_troca, $car_estado, $id_car);
$update_car->execute();
$rows_update = $update_car->affected_rows;
$update_car->close();

if( $update_car ) 
{
   $opcionais = $con->prepare(" UPDATE `cars_optional` SET `airbag` = ?, `ar_condicionado` = ?, `direcao` = ?, `alarme` = ?, `cd_player` = ?, `rodas` = ?, `travas` = ?, `vidros` = ?, `camera` = ?, `sensor_estacionamento` = ?, `chave_reserva` = ?, `manual` = ?, `desembacador_traseiro` = ?, `computador` = ?, `ar_quente` = ?, `multimidia` = ?, `controles_volante` = ?, `volante_regulagem` = ?, `retrovisor` = ?, `gps` = ?, `abs` = ?, `farois_neblina` = ?, `capota` = ?, `teto_solar` = ?, `bancos_couro` = ?, `farois_regulagem` = ?, `sensor_chuva` = ?, `brake_light` = ?, `limpador_traseiro` = ?, `aerofolio` = ?, `engate_traseiro` = ?, `estribo` = ?, `santantonio` = ?, `mascara_negra` = ? WHERE `id_car` = ? ");
   $opcionais->bind_param('iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', $car_airbag, $car_ar_condicionado, $car_direcao, $car_alarme, $car_cd_player, $car_rodas, $car_travas, $car_vidros, $car_camera, $car_estacionamento, $car_chave_reserva, $car_manual, $car_desembacador, $car_computador, $car_ar_quente, $car_multimidia, $car_controles_volante, $car_volante_regulagem, $car_retrovisor, $car_gps, $car_abs, $car_neblina, $car_capota, $car_teto_solar, $car_couro, $car_farois_regulagem, $car_sensor_chuva, $car_brake_light, $car_limpador_traseiro, $car_aerofolio, $car_engate, $car_estribo, $car_santoantonio, $car_mascara_negra, $id_car);
   $opcionais->execute();
   $rows_opcionais = $opcionais->affected_rows;
   $opcionais->close();
    
    if( $opcionais )
    {
        $response = array(
            'status'  => 'success',
            'message' => 'Informações atualizadas.',
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
