<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/config/config.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/config/autoload.php' );
use app\controls\reportBug;

function DeleteFiles($file)
{
  if(file_exists($file))
  {
    unlink($file) ;
    return 'Arquivo Apagado';
  }
  else 
  {
    return "Arquivo não pode ser excluido.";
  }
}

/**
 * Delete files in FTP Recursive method
 *
 * @param $directoty = Directorys of list and delete arqs.
 *
 */
function recursiveDeleteFTP($directory) { 
    $ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die('Sem conexão FTP');
    ftp_login($ftp_connect, USER_FTP, PASS_FTP);
    
    if( !(@ftp_rmdir($ftp_connect, $directory) || @ftp_delete($ftp_connect, $directory)) ) 
    {  
        $filelist = @ftp_nlist($ftp_connect, $directory); 
        
        foreach($filelist as $file) 
        { 
            recursiveDeleteFTP($file); 
        } 
        
        recursiveDeleteFTP($directory); 
    }
    
    ftp_close($ftp_connect);
}

function createDirFTP($dir)
{
    $ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die('Sem conexão FTP');    
    ftp_login($ftp_connect, USER_FTP, PASS_FTP);

    if (!file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir))
    {
        ftp_mkdir($ftp_connect, $dir);  
        $message = TRUE;
    }
    else
    {
        $message = 'Diretório já existe no Servido.';
    }

    ftp_close($ftp_connect); 
    return $message;
}

function deleteDirFTP($dir)
{
    $ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die('Sem conexão FTP');    
    ftp_login($ftp_connect, USER_FTP, PASS_FTP);
    if (file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir))
    {
        ftp_delete($ftp_connect, $dir);
        ftp_close($ftp_connect);
        $message = TRUE;
    }
    else
    {
        $message = 'Diretório não existe';
    }
    ftp_close($ftp_connect); 
    return $message;
}

function CountImagesAlbum($id)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
  
    $count = $con->query(" SELECT * FROM `images_gallery` WHERE `id_album` = '$id' ");
    $total = $count->num_rows;
    $count->close();
    if(!$count)
    {
        return  null;
    }
    else if($total > 0)
    {
        return  $total;
    }
    else 
    {
        return false;
    }
  
}


// lista todos os arquivos e diretórios da pasta e apaga
// usar segundo parametro como true ou false se quiser a pagar a pasta informada na chamada de função  
function unlinkRecursive($dir, $deleteRootToo) 
{ 
  if(!$dh = @opendir($dir)) 
  { 
      return; 
  } 
  while (false !== ($obj = readdir($dh))) 
  { 
    if($obj == '.' || $obj == '..') 
    { 
        continue; 
    } 

    if (!@unlink($dir . '/' . $obj)) 
    { 
        unlinkRecursive($dir.'/'.$obj, true); 
    } 
  } 
  closedir($dh);
  
  // se parametro 2 for true apaga também o diretório o qual foi passado
  if ($deleteRootToo) 
  { 
      @rmdir($dir); 
  } 
  return; 
}

// Gera lista de marcas por categoria

function generateListBrands($category)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $list = $con->query("SELECT `marca` FROM `vehicles_brands` WHERE `categoria` = '$category' ");
    $rows = $list->num_rows;
    
    $li = '';
    while( $reg = $list->fetch_assoc() )
    {
      $li .= '<OPTION VALUE="'.$reg['marca'].'">'.$reg['marca'].'</OPTION>';  
    }
    
    if( $list and $rows > 0)
    {
        return $li;
    }
    else
    {
        return '<OPTION VALUE="">Falha ao obter lista de marcas.</OPTION>';
    }
}

// retorna a assinatura padrão para o campo observações do vendedor

function signatureObsVehicles()
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $signature = $con->query("SELECT `obs_signature` FROM `settings_vehicles` ");
    $rows = $signature->num_rows;
    
    $reg = $signature->fetch_assoc();
    
    if( $signature and $rows > 0)
    {   
        $signature->close();
        return ltrim($reg['obs_signature']);
    }
    else
    {
        $signature->close();
        return 'Não foi possível obter a assinatura padrão.';
    }
    
}

// retorna o número de registros
function countRegTables($table)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $count = $con->query(" SELECT count(*) FROM $table ");
    $rows = $count->fetch_row();
    $count->close();
    
    if($count)
    {
        return $rows[0];
    }
    else
    {
        return 'indefinido';
    }
}

// soma os campos de valores monetários
function sumMoneyTable($table)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $value = $con->query(" SELECT SUM(valor) FROM $table ");
    $rows = $value->fetch_row();
    $value->close();
    
    if($value)
    {
        return $rows[0];
    }
    else
    {
        return 0.00;
    }
}

// conta o total de views
function countViews($table)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $views = $con->query(" SELECT SUM(views) FROM $table ");
    $rows = $views->fetch_row();
    $views->close();
    
    if($views)
    {
        return $rows[0];
    }
    else
    {
        return 0;
    }
}

/**
 * Apartir daquis as funções são para o módulo "contracts".
 *
 */

// gera options pro select de clientes
function listClients()
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $list = $con->prepare("SELECT id, client_type, client_social_name, client_fantasy_name, client_name, client_last_name FROM clients");
    $list->execute();
    $list->store_result();
    $list->bind_result($id, $client_type, $social_name, $fantasy_name, $client_name, $last_name);
    $rows = $list->num_rows;
    
    $op = '<option value="">Escolha uma opção</option>'; 
    while($list->fetch())
    {
        if($client_type === 'juridical')
        {
            $name = $fantasy_name;   
        }
        else if ($client_type === 'physical')
        {
            $name = $client_name. ' '.$last_name;
        }
        else
        {
            if (!empty($social_name))
            {
                $name = $social_name;
            }
            else
            {
                $name = $client_name. ' '.$last_name;
            }
            
        }
        $op .= '<option value="'.base64_encode($id).'">'.$name.'</option>';   
    }
    $list->free_result();
    $list->close();
    
    return ($list and $rows > 0) ? $op : false ;

}

function listContracts($client_id)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $contracts = $con->query("SELECT contract_id, type FROM contracts WHERE owner_id = '$client_id' OR tenant_id = '$client_id' ");
    $rows = $contracts->num_rows;
    
    if ($contracts and $rows > 0)
    {
        $item = '';
        while ($reg = $contracts->fetch_array())
        {
            if ($reg['type'] == 'rent')
            {
                $type = 'Contrato de aluguel';
            }
            else if ($reg['type'] == 'sale')
            {
                $type = 'Contrato de compra e venda';
            }
            else if ($reg['type'] == 'management')
            {
                $type = 'Contrato de administração de imóvel';
            }
            else
            {
                $type = 'Sem tipo definido';
            }
            $item .= '<a href="/app/admin/contracts_details/'.$reg['contract_id'].'" class="uppercase label bg-maroon flat pull-left" title="'.$type.'" style="margin:0 5px 5px 0;"><i class="fa fa-file-pdf-o"></i></a>';   
        }
        return $item;
    }
    return false;
}

/**
 * Apartir daquis as funções são para o módulo "properties".
 *
 */

// lista os imóveis
function listProperties()
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $list = $con->query("SELECT id, ref, address_street FROM properties ORDER BY id DESC");
    $rows = $list->num_rows;
    
    $op = '<option value="">Escolha uma opção</option>'; 
    while ($reg = $list->fetch_array())
    {
        $op .= '<option value="'.$reg['ref'].'">'.$reg['ref'].' - '.$reg['address_street'].'</option>'; 
    }
    return ($list and $rows > 0) ? $op : '<option value="">Nenhum imóvel cadastrado</option>' ;
}

/**
 * Apartir daquis as funções são para o módulo "clients".
 *
 */
// select client type by id
function getClientName($id)
{
    if (empty($id)) return false;
    
    $id = filterString($id, 'INT');
    
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $client = $con->prepare("SELECT client_type, client_social_name, client_fantasy_name, client_name, client_last_name FROM clients WHERE id = ?");
    $client->bind_param('i', $id);
    $client->execute();
    $client->store_result();
    $client->bind_result($type, $social_name, $name, $fantasy_name, $last_name);
    $client->fetch();
    $rows = $client->num_rows;
    $client->free_result();
    $client->close();
    
    if ($client and $rows > 0)
    {
        if ($type == 'physical') return $name.' '.$last_name;
        if ($type == 'juridical') return $fantasy_name;
        return 'indefinido';
    }
    
    return 'indefinido';
    
}

// soma valores dos recibos

function sumReceipts($contract_id, $return_type)
{
    if (empty($contract_id)) return false;
    
    if ($return_type === 'total')
    {
        $sql = "SELECT SUM(value_gross + addition - discount) as total FROM receipts WHERE contract_id = '$contract_id'";
    }
    else if ($return_type === 'billed')
    {
        $sql = "SELECT SUM(value_gross + addition - discount) as total FROM receipts WHERE contract_id = '$contract_id' AND situation = 'billed'";
    }
    else if ($return_type === 'expired')
    {
        $sql = "SELECT SUM(value_gross + addition - discount) as total FROM receipts WHERE contract_id = '$contract_id' AND due_date < NOW() AND situation != 'billed' AND situation != 'open'";
    }
    else if ($return_type === 'open')
    {
        $sql = "SELECT SUM(value_gross + addition - discount) as total FROM receipts WHERE contract_id = '$contract_id' AND situation != 'billed' AND due_date >= NOW()";
    }
    else
    {
        return false;
    }
    
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $values = $con->query($sql);
    $reg = $values->fetch_array();
    $total = $reg['total'];
    
    return ($values and $values->num_rows > 0) ? $total : false;
}

// notifição de recibos vencidos

function expiredReceiptsNotifications ()
{  
    $con_db = new config\connect_db();
    $con = $con_db->connect();

    $notifications = $con->query("SELECT * FROM receipts WHERE due_date <= NOW() AND situation != 'billed' ORDER BY due_date DESC");
    $total_reg = $notifications->num_rows;
    
    
    if ($notifications and $total_reg > 0 )
    {
        $today = strtotime(date('Y-m-d'));
        
        $alert  = '<a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown">';
        $alert .= '<I CLASS="fa fa-bell-o"></I><SPAN CLASS="label label-warning">'.$total_reg.'</SPAN>';
        $alert .= '</a>';
        $alert .= '<UL CLASS="dropdown-menu">';
        $alert .= '<LI CLASS="header">Você tem '.$total_reg.' notificações</LI>';
        $alert .= '<LI>';
        $alert .= '<UL CLASS="menu">';
        
        while ($reg = $notifications->fetch_array())
        {
            $due_date = strtotime($reg['due_date']);
            if (strtotime($reg['due_date']) < $today and $reg['situation'] != 'billed') 
            {
                $m = '<I CLASS="fa fa-exclamation-triangle text-red"></I> Recibo Nª <strong>'.$reg['id'].'</strong> <br> do contrato <strong>'.$reg['contract_id'].'</strong> está vencido.';
            }
            else if (strtotime($reg['due_date']) === $today) 
            {
                $m = '<I CLASS="fa fa-exclamation-triangle text-orange"></I>  Recibo Nª <strong>'.$reg['id'].'</strong> <br> do contrato <strong>'.$reg['contract_id'].'</strong> vence hoje.';
            }
            else
            {
                $m = '';
            }
            
            $alert .= '<LI><a href="/app/admin/contracts_details/'.$reg['contract_id'].'">';
            $alert .= $m;
            $alert .= '</a></LI>';
        }
        
        $alert .= '</UL>';
        $alert .= '</LI>';
        $alert .= '</UL>';
    }
    else
    {
        $alert  = '<a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown">';
        $alert .= '<I CLASS="fa fa-bell-o"></I>';
        $alert .= '</a>';
        $alert .= '<UL CLASS="dropdown-menu">';
        $alert .= '<LI CLASS="header">Nenhuma notificação</LI>';
        $alert .= '<LI>';
        $alert .= '<UL CLASS="menu">';
        $alert .= '<LI><a href="javscript:;">';
        $alert .= '<I CLASS="fa fa-smile-o text-success"></I> Está tudo certo';
        $alert .= '</a></LI>';
        $alert .= '</UL>';
        $alert .= '</LI>';
        $alert .= '</UL>';
    }
    
    return $alert;
    
}