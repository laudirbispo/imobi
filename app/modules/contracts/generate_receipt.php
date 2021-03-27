<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\blowfish_crypt;

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    if(!isset($_SESSION['contracts_read']) or $_SESSION['contracts_read'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        );
        die(json_encode($response));
    }    
}

if (!isset($_GET['contract_id']) or empty($_GET['contract_id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Contrato não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $contract_id = filterString($_GET['contract_id'], 'CHAR');
}

if (!isset($_GET['receipt_id']) or empty($_GET['receipt_id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Recibo não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $receipt_id = filterString($_GET['receipt_id'], 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$receipt = $con->prepare("SELECT re.value_gross, re.discount, re.discount_cause, re.addition, re.addition_cause, re.due_date, ow.client_type AS owner_type, ow.client_social_name AS owner_social_name, ow.client_cnpj AS owner_cnpj, ow.client_name AS owner_name, ow.client_last_name AS owner_last_name, ow.client_cpf AS owner_cpf, ow.client_fantasy_name AS owner_fantasy_name, tn.client_type AS tenant_type, tn.client_social_name AS tenant_social_name, tn.client_cnpj AS tenant_cnpj, tn.client_name AS tenant_name, tn.client_last_name AS tenant_last_name, tn.client_cpf AS tenant_cpf, tn.client_fantasy_name AS tenant_fantasy_name, pr.address_state, pr.address_city, pr.address_street, pr.address_number, pr.address_neighborhood, pr.address_postal_code, pr.ref, co.type FROM receipts re LEFT JOIN contracts co ON (co.contract_id = re.contract_id) LEFT JOIN clients ow ON (ow.id = co.owner_id) LEFT JOIN clients tn ON (tn.id = co.tenant_id) LEFT JOIN properties pr ON (pr.ref = co.property_ref) WHERE re.contract_id = ? AND re.id = ?");
$receipt->bind_param('si', $contract_id, $receipt_id);
$receipt->execute();
$receipt->store_result();
$receipt->bind_result($value_gross, $discount, $discount_cause,$addition, $addition_cause, $due_date, $owner_type, $owner_social_name, $owner_cnpj, $owner_name, $owner_last_name, $owner_cpf, $owner_fantasy_name, $tenant_type, $tenant_social_name, $tenant_cnpj, $tenant_name, $tenant_last_name, $tenant_cpf, $tenant_fantasy_name, $state, $city, $street, $street_number, $neigborhood, $postal_code, $property_ref, $contract_type);
$receipt->fetch();
$rows = $receipt->num_rows;
$receipt->free_result();
$receipt->close();

if ($receipt and $rows > 0)
{
    if ($owner_type === 'physical') 
    {
        $owner_name = $owner_name.' '.$owner_last_name;
        $owner_idt = $owner_cpf;
    }
    else
    {
        $owner_name = (empty($owner_fantasy_name)) ? $owner_social_name : $owner_fantasy_name ;
        $owner_idt = $owner_cnpj;
    }
    
    if ($tenant_type === 'physical') 
    {
        $tenant_name = $tenant_name.' '.$tenant_last_name;
        $tenant_idt = $tenant_cpf;
    }
    else
    {
        $tenant_name = (empty($tenant_fantasy_name)) ? $tenant_social_name : $tenant_fantasy_name ;
        $tenant_idt = $tenant_cnpj;
    }
    
    $extenso = new app\controls\writeNumber;
    
    /*
    if ($contract_type === 'rent')
    {
        
    }
    else if ($contract_type === 'management')
    {
        
    }
    else
    {
        $response = array(
            'status'  => 'error',
            'message' => 'Não é possível gerar recibos para este tipo de contrato.',
            'link'    => '',
         );
         die(json_encode($response));
    }
    */
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $now = strftime('%A, %d de %B de %Y', strtotime('today'));

    
    $html  = '';
    $html .= '<div class="clearfix"></div>';
    $html .= '<SECTION CLASS="container" id="print">';
    $html .= '<div id="container-receipts">';   
    $html .= '<div id="doc-receipts" class="center-block">';
    $html .= '<div class="header">';
    $html .= '<div class="pull-left doc-type">RECIBO DE LOCAÇÃO Nº '.$receipt_id.'</div>';
    $html .= '<div class="pull-right doc-due-date">VENCIMENTO:'.inverteData($due_date).'</div>';
    $html .= '</div>';
    $html .= '<div id="info-property">';
    $html .= '<p>O presente recibo corresponde ao aluguel do imóvel <span class="span-markdow">'.$property_ref.'</span>, <span class="span-markdow">'.$street.' nº: '.$street_number.' '.$neigborhood.'</span>, da propriedade de: <span class="span-markdow">'.$owner_name.'</span> portador(a) do cpf/cnpj: <span class="span-markdow">'.$owner_idt.'</span>, residente e domiciliado na cidade de <span class="span-markdow">'.$city.'-'.$state.'</span></p>';
    $html .= '</div>';
    $html .= '<div id="info-values">';           
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6 no-padding" style="height:2.6cm">';
    $html .= '<p><Strong>Acréscimos:</Strong></p>';
    $html .= '<p>'.$addition_cause.'</p>';
    $html .= '<p><strong>Descontos:</strong></p>';
    $html .= '<p>'.$discount_cause.'</p>';
    $html .= '</div>';          
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6 no-padding" style="height:2.6cm">';
    $html .= '<table class="table table-bordered" id="table-values-receipts">';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<th scope="row">Valor bruto</th>';
    $html .= '<td>R$ '.decimalMoeda($value_gross).'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th scope="row">Descontos</th>';
    $html .= '<td>R$ '.decimalMoeda($discount).'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th scope="row">Acréscimos</th>';
    $html .= '<td>R$ '.decimalMoeda($addition).'</td>';
    $html .= '</tr>';
    $html .= '<tr class="bg-gray">';
    $html .= '<th scope="row">Total</th>';
    $html .= '<td>R$ '.decimalMoeda($value_gross+$addition-$discount).'</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
    $html .= '<div class="clearfix" style="clear: both"><br></div>';
    $html .= '<p><strong>Valor do recibo por extenso: </strong>***** '.$extenso->valorPorExtenso(decimalMoeda($value_gross+$addition-$discount), true, false).' *****</p>';
    $html .= '</div>';    
    $html .= '<div class="col-md-12" style="border: 1px solid #999;height:2.1cm; margin-bottom: 0.2cm; font-size: 9pt">';
    $html .= '<p>Recebemos de <span class="span-markdow">'.$tenant_name.'</span> portador do cpf/cnpj: <span class="span-markdow">'.$tenant_idt.'</span> aluguel ref. locação periodo: '.inverteData($due_date).' a '.date('d/m/Y', strtotime($due_date. ' + 30 days')).'</p>';
    $html .= '<p>Por ser verdade, assino(amos) o presente para que produza os efeitos legais:</p>';
    $html .= '</div>';        
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6">';
    $html .= '<img class="pull-left" src="https://ariomarimoveis.com.br/assets/images/logoariomar.png" height="60">';
    $html .= '<DIV class="clearfix"></DIV>';
    $html .= '<small style="font-size: 8pt">Rua Osvaldo Cruz, 1898, Vila Vitória - Apucarana - PR</small>';
    $html .= '</div>';      
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6">';
    $html .= '<small class="pull-right">'.dataExtenso($date).'</small>';
    $html .= '<DIV class="CLEARFIX"></DIV>';
    $html .= '<div id="assinature">';
    $html .= 'Ariomar Imóveis <BR>';
    $html .= '<SMALL>80.570.831/0001-38</SMALL>';
    $html .= '</div>';
    $html .= '</div>';        
    $html .= '</div>';   
    $html .= '<div style="height: 0.5cm;" class="clearfix"></div>';    
    $html .= '<div id="doc-receipts" class="center-block">';
    $html .= '<div class="header">';
    $html .= '<div class="pull-left doc-type">RECIBO DE LOCAÇÃO Nº '.$receipt_id.'</div>';
    $html .= '<div class="pull-right doc-due-date">VENCIMENTO:'.inverteData($due_date).'</div>';
    $html .= '</div>';
    $html .= '<div id="info-property">';
    $html .= '<p>O presente recibo corresponde ao aluguel do imóvel <span class="span-markdow">'.$property_ref.'</span>, <span class="span-markdow">'.$street.' nº: '.$street_number.' '.$neigborhood.'</span>, da propriedade de: <span class="span-markdow">'.$owner_name.'</span> portador(a) do cpf/cnpj: <span class="span-markdow">'.$owner_idt.'</span>, residente e domiciliado na cidade de <span class="span-markdow">'.$city.'-'.$state.'</span></p>';
    $html .= '</div>';
    $html .= '<div id="info-values">';           
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6 no-padding" style="height:2.6cm">';
    $html .= '<p><Strong>Acréscimos:</Strong></p>';
    $html .= '<p>'.$addition_cause.'</p>';
    $html .= '<p><strong>Descontos:</strong></p>';
    $html .= '<p>'.$discount_cause.'</p>';
    $html .= '</div>';          
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6 no-padding" style="height:2.6cm">';
    $html .= '<table class="table table-bordered" id="table-values-receipts">';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<th scope="row">Valor bruto</th>';
    $html .= '<td>R$ '.decimalMoeda($value_gross).'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th scope="row">Descontos</th>';
    $html .= '<td>R$ '.decimalMoeda($discount).'</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th scope="row">Acréscimos</th>';
    $html .= '<td>R$ '.decimalMoeda($addition).'</td>';
    $html .= '</tr>';
    $html .= '<tr class="bg-gray">';
    $html .= '<th scope="row">Total</th>';
    $html .= '<td>R$ '.decimalMoeda($value_gross+$addition-$discount).'</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
    $html .= '<div class="clearfix" style="clear: both"><br></div>';
    $html .= '<p><strong>Valor do recibo por extenso: </strong>***** '.$extenso->valorPorExtenso(decimalMoeda($value_gross+$addition-$discount), true, false).' *****</p>';
    $html .= '</div>';    
    $html .= '<div class="col-md-12" style="border: 1px solid #999;height:2.1cm; margin-bottom: 0.2cm; font-size: 9pt">';
    $html .= '<p>Recebemos de <span class="span-markdow">'.$tenant_name.'</span> portador do cpf/cnpj: <span class="span-markdow">'.$tenant_idt.'</span> aluguel ref. locação periodo: '.inverteData($due_date).' a '.date('d/m/Y', strtotime($due_date. ' + 30 days')).'</p>';
    $html .= '<p>Por ser verdade, assino(amos) o presente para que produza os efeitos legais:</p>';
    $html .= '</div>';        
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6">';
    $html .= '<img class="pull-left" src="https://ariomarimoveis.com.br/assets/images/logoariomar.png" height="60">';
    $html .= '<DIV class="clearfix"></DIV>';
    $html .= '<small style="font-size: 8pt">Rua Osvaldo Cruz, 1898, Vila Vitória - Apucarana - PR</small>';
    $html .= '</div>';      
    $html .= '<div class="col-md-6 col-sm-6 col-xs-6">';
    $html .= '<small class="pull-right">'.dataExtenso($date).'</small>';
    $html .= '<DIV class="CLEARFIX"></DIV>';
    $html .= '<div id="assinature">';
    $html .= 'Ariomar Imóveis <BR>';
    $html .= '<SMALL>80.570.831/0001-38</SMALL>';
    $html .= '</div>';
    $html .= '</div>';        
    $html .= '</div>';      
    $html .= '</div>'; 
    $html .= '</SECTION>';
    
    $response = array(
        'status'  => 'success',
        'message' => $html,
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Algumas informações estão corrompidas! Não foi possível gerar o recibo.',
        'link'    => '',
     );
     die(json_encode($response));
}