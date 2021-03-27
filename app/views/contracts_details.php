<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['contracts_view']))
{
    if(!isset($_GET['actionid']) or empty($_GET['actionid']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum contrato identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else
    {
        $actionid = filterString($_GET['actionid'], 'CHAR');

        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $contract = $con->prepare("SELECT co.owner_id, co.tenant_id, co.property_ref, co.valid_start, co.type, co.value, co.date_post, co.user_post, do.file_path, pr.id, pr.ref, pr.status, pr.type, pr.finality, pr.address_state, pr.address_city, pr.address_street, pr.address_number, pr.address_neighborhood, pr.address_postal_code, pr.cover_image, se.login, se.id, up.user_name, up.user_profile_photo FROM contracts co LEFT JOIN contracts_docs do ON (co.contract_id = do.contract_id) LEFT JOIN properties pr ON (pr.ref = co.property_ref) LEFT JOIN sec_users se ON (se.id = co.user_post) LEFT JOIN user_profile up ON (up.user_id = co.user_post) WHERE co.contract_id = ? ");
        $contract->bind_param('s', $actionid);
        $contract->execute();
        $contract->store_result();
        $contract->bind_result($owner_id, $tenant_id, $property_ref, $valid_start, $type, $value, $date_post, $user_post, $file_path, $property_id, $property_ref, $property_status, $property_type, $property_finality, $property_state, $property_city, $property_street, $property_number, $property_neighborhood, $property_postal_code, $property_cover_image, $user_login, $user_id, $user_name, $user_photo);
        $contract->fetch();
        $affr = $contract->num_rows;
        $contract->free_result();
        $contract->close();

       
        if($contract and $affr > 0)
        {  
            $url = new app\controls\securePage();
            
            if ($type === 'rent')
            {
                $type = 'Contrato de aluguel';
            }
            else if ($type === 'sale')
            {
                $type = 'Contrato de compra e venda<';
            }
            else if ($type === 'management')
            {
                $type = 'Administração';
            }
            else 
            {
                $type = 'Undefined';
            }
            
            $user_photo = (fileRemoteExist(SUBDOMAIN_IMGS.$user_photo) === true) ? SUBDOMAIN_IMGS.$user_photo : SUBDOMAIN_IMGS.'/defaults/default-user.png'; 

            $address = array();
            $address[] = $property_street;
            $address[] = ($property_number !== NULL) ? $property_number : 's/nº';
            $address[] = $property_neighborhood;
            $address[] = $property_city;
            $address[] = $property_state;
            $address = implode(', ', $address);
            $address = (strlen($address) > 50) ? substr($address, 0, 50).'...' : $address ;
            
            $user_name = (empty($user_name)) ? $user_login : $user_name ;

?>
<link href="/plugins/carouseller/css/carouseller.css" rel="stylesheet" type="text/css"> 
<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Contrato Detalhes</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/contracts"><i class="fa fa-file-text-o"></i> Contratos</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
        
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="box box-solid" style="min-height: 145px;">
                <div class="box-header">
                    <STRONG>Informações do contrato</STRONG>
                </div>
                <div class="box-body">
                    <div class="media">
                      <div class="media-left media-middle hidden-sm">
                        <a href="#">
                          <img class="media-object" src="/app/images/pdf-ico.png" alt="Ícone PDF">
                        </a>
                      </div>
                      <div class="media-body">
                        <h5 class="media-heading"><strong>Contrato ID:</strong> <?php echo $actionid; ?></h5>
                        <p title="ASDAS"><strong>Tipo de contrato:</strong> <?php echo $type; ?></p>
                        <p><a href="<?php echo SUBDOMAIN_IMGS.$file_path; ?>" target="new">Ver documento</a></p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="box box-solid" style="min-height: 145px;">
                <div class="box-header">
                    <STRONG>Imóvel</STRONG>
                </div>
                <div class="box-body">
                    <div class="media">
                      <div class="media-body">
                        <h5 class="media-heading"><strong>Imóvel REF:</strong> <?php echo $property_ref; ?></h5>
                        <p> <?php echo $address; ?></p>
                        <p><a href="/app/admin/preview_property/<?php echo base64_encode($property_id); ?>" target="new">Ver mais</a></p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="box box-solid" style="min-height: 145px;">
                <div class="box-header">
                    <STRONG>Criado por:</STRONG>
                </div>
                <div class="box-body">
                     <div class="media">
                      <div class="media-left media-middle">
                          <img class="media-object img-circle" width="64" height="64" src="<?php echo $user_photo; ?>" alt="Imagem de perfil">
                      </div>
                      <div class="media-body">
                        <h5 class="media-heading"><?php echo $user_name; ?></h5>
                      </div>
                    </div>
                </div>
            </div>
        </div>

  </DIV>  
</SECTION>

<div class="clearfix"></div>

<SECTION CLASS="container-fluid">
    <div class="row">
       
       <div class="col-md-12">
            <a href="javascript:;" class="btn btn-primary btn-flat text-white" data-toggle="modal" data-target="#add-receipts"><i class="fa fa-plus"></i> Adicionar recibos</a>
            
            <button type="button" class="btn btn-flat btn-primary hidden" data-toggle="em-modal" data-page="gerando-recibos" data-title="Gerando recibos"><i class="fa fa-question-circle"></i> Obter ajuda</button>
       </div>
       
       <div class="clearfix space-20"></div>
       
        <div class="col-md-12">
            <NAV CLASS="navbar">
               <!-- Brand and toggle get grouped for better mobile display -->
                <DIV CLASS="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" DATA-TOGGLE="collapse" DATA-TARGET="#bs-example-navbar-collapse-1" aria-expanded="false"> <i class="fa fa-bars"></i> </button>
                </DIV>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <DIV CLASS="collapse navbar-collapse" ID="bs-example-navbar-collapse-1">
                    <UL CLASS="nav navbar-nav">
                        <LI CLASS="dropdown"> <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ordernar Por: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=date-asc'); ?>"> Lançamentos antigos</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=date-desc'); ?>"> Lançamentos futuros</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=number-asc'); ?>"><I CLASS="fa fa-sort-numeric-asc"></I> Número do recibo menor</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=number-desc'); ?>"><I CLASS="fa fa-sort-numeric-asc"></I> Número do reecibo maior</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=clear'); ?>"><I CLASS="fa fa-bars"></I> Padrão</a></LI>
                            </UL>
                        </LI>
                        <LI CLASS="dropdown"> 
                           <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Número de Registros: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=25'); ?>">25</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=50'); ?>">50</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=100'); ?>">100</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=250'); ?>">250</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=500'); ?>">500</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=all'); ?>">Todas</a></LI>
                            </UL>
                        </LI>
                        <LI CLASS="dropdown"> 
                           <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Visualizar: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=open&pagination=0'); ?>">Recibos em aberto</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=expired&pagination=0'); ?>">Recibos vencidos</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=billed&pagination=0'); ?>">Recibos faturados</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=all&pagination=0'); ?>"><I CLASS="fa fa-retweet"></I> Todos</a></LI>
                            </UL>
                        </LI>
                    </UL>
                    <form class="navbar-form navbar-right" role="search">
                        <DIV CLASS="form-group">
                            <input type="text" class="form-control" id="filter-table" DATA-TABLE="table-receipts" placeholder="Buscar registros" style="width:400px">
                        </DIV>
                    </form>
                </DIV>
                <!-- /.navbar-collapse --> 
            </NAV>
        </div>
    </div>
</SECTION>

<div class="clearfix"></div>

<SECTION CLASS="row" data-control="data-reload">
    <DIV CLASS="container-fluid">
        
        <div class="clearfix space-20"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-purple">R$</span>
            <div class="info-box-content">
              <span class="info-box-text" title="Valor total do contrato">Valor total do contrato</span>
              <span class="info-box-number"><?php echo 'R$ '.decimalMoeda(sumReceipts($actionid, 'total')); ?></span>
            </div><!-- /.info-box-content -->           
          </div><!-- /.info-box -->
        </div><!-- /.col-->

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">R$</span>
            <div class="info-box-content">
              <span class="info-box-text" title="Total recebido">Total recebido</span>
              <span class="info-box-number"><?php echo 'R$ '.decimalMoeda(sumReceipts($actionid, 'billed')); ?></span>
            </div><!-- /.info-box-content -->           
          </div><!-- /.info-box -->
        </div><!-- /.col-->

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red">R$</span>
            <div class="info-box-content">
              <span class="info-box-text" title="Em atraso">Em atraso</span>
              <span class="info-box-number"><?php echo 'R$ '.decimalMoeda(sumReceipts($actionid, 'expired')); ?></span>
            </div><!-- /.info-box-content -->           
          </div><!-- /.info-box -->
        </div><!-- /.col-->

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">R$</span>
            <div class="info-box-content">
              <span class="info-box-text" title="A receber">A receber</span>
              <span class="info-box-number"><?php echo 'R$ '.decimalMoeda(sumReceipts($actionid, 'open')); ?></span>
            </div><!-- /.info-box-content -->           
          </div><!-- /.info-box -->
        </div><!-- /.col-->

        <div class="clearfix space-20"></div>
            
        <DIV CLASS="col-md-12">
            <DIV CLASS="box box-solid">
              <DIV CLASS="box-header">
                <H3 CLASS="box-title">Recibos</H3>
              </DIV>
              <!-- /.box-header -->
              <DIV CLASS="box-body no-padding"> 
                <?php        

                if (empty($_GET['pagination']) or !isset($_GET['pagination']) or !is_numeric($_GET['pagination']))
                {
                    $pagination = '0';
                }
                else
                {
                    $pagination = filterString($_GET['pagination'], 'INT'); 
                }

                if (empty($_GET['limit']) or !isset($_GET['limit']) )
                {
                    $limit = 25;
                }
                else if ($_GET['limit'] == 'all')
                {
                    $start = 0;
                    $limit = 99999;
                }
                else 
                {
                    $limit = filterString($_GET['limit'], 'INT');
                }

                if ($pagination and $pagination != '')
                {
                    $start = ($pagination - 1) * $limit;
                }
                else
                {
                    $start = 0;
                }

                if (!empty($_GET['select']) and isset($_GET['select']))
                {
                    $select = filterString($_GET['select'], 'CHAR');
                    if ($select === 'expired')
                    {
                        $where = "WHERE contract_id = '$actionid' AND due_date < NOW() ";
                    }
                    else if ($select === 'open')
                    {
                        $where = "WHERE contract_id = '$actionid' AND situation != 'canceled' AND situation != 'billed' AND due_date >= NOW() ";
                    }
                    else if ($select === 'billed')
                    {
                        $where = "WHERE contract_id = '$actionid' AND situation = 'billed'";
                    }
                    else
                    {
                        $where = "WHERE contract_id = '$actionid'";
                    }
                }
                else
                {
                    $where = "WHERE contract_id = '$actionid'";
                }

                if (!empty($_GET['order']) and isset($_GET['order']))
                {
                    $order = filterString($_GET['order'], 'CHAR');
                    if ($order === 'date-desc')
                    {
                        $order = 'ORDER BY contract_id DESC, due_date DESC';
                    }
                    else if ($order === 'date-asc')
                    {
                        $order = 'ORDER BY contract_id ASC, due_date ASC';
                    }
                    else if ($order === 'number-asc')
                    {
                        $order = 'ORDER BY id ASC';
                    }
                    else if ($order === 'number-desc')
                    {
                        $order = 'ORDER BY id DESC';
                    }
                    else
                    {
                        $order = 'ORDER BY due_date ASC, contract_id ASC';
                    }
                }
                else
                {
                    $order = 'ORDER BY due_date ASC, contract_id ASC';
                }

                $con_db = new config\connect_db();
                $con = $con_db->connect();

                $query_before = array_sum(explode(' ', microtime()));

                $clients = $con->query("SELECT * FROM receipts $where $order LIMIT $start,$limit");
                $total_reg = $clients->num_rows;

                $item = '<TABLE CLASS="table table-striped table-responsive dataTable" ID="table-receipts"> ';
                $item .= '<THEAD> ';
                $item .= '  <TR>';
                $item .= '    <TH STYLE="width:5% !important;">Nº</TH>';
                $item .= '    <TH class="hidden-xs" STYLE="width:15% !important;">VALOR BRUTO</TH>';
                $item .= '    <TH class="hidden-xs" STYLE="width:15% !important;">DESCONTOS</TH>';
                $item .= '    <TH class="hidden-xs" STYLE="width:15% !important;">ACRÉSCIMOS</TH>';
                $item .= '    <TH STYLE="width:15% !important;">LÍQUIDO</TH> ';
                $item .= '    <TH STYLE="width:15% !important;">VENCIMENTO</TH>';
                $item .= '    <TH class="hidden-xs" STYLE="width:10% !important;">SITUAÇÃO</TH> ';
                $item .= '    <TH STYLE="width:15% !important;">AÇÕES</TH> ';
                $item .= '  </TR> ';
                $item .= '</THEAD>';
                $item .= '<TBODY>'; 

                $today = strtotime(date('Y-m-d'));
                $tr_bg = '';

                while($reg = $clients->fetch_array())
                {
                  
                  if ($reg['situation'] === 'open' and strtotime($reg['due_date']) >= $today)
                  {
                      $situation = '<span class="label label-primary flat">Em aberto</spam>';
                      $write_dow = '<li><a href="javascript:;" data-action="write-dow-receipts" data-receipt-id="'.$reg['id'].'" data-contract-id="'.$actionid.'" data-discount="'.decimalMoeda($reg['discount']).'" data-discount-cause="'.$reg['discount_cause'].'" data-addition="'.decimalMoeda($reg['addition']).'" data-addition-cause="'.$reg['addition_cause'].'" data-observations="'.$reg['observations'].'" ><i class="fa fa-edit text-blue"></i>Editar informações</a></li>';
                      $text_bg = '';
                  }
                  else if ($reg['situation'] === 'expired' and strtotime($reg['due_date']) < $today)
                  {
                      $situation = '<span class="label label-danger flat">Atrasado</spam>';
                      $write_dow = '<li><a href="javascript:;" data-action="write-dow-receipts" data-receipt-id="'.$reg['id'].'" data-contract-id="'.$actionid.'" data-discount="'.decimalMoeda($reg['discount']).'" data-discount-cause="'.$reg['discount_cause'].'" data-addition="'.decimalMoeda($reg['addition']).'" data-addition-cause="'.$reg['addition_cause'].'" data-observations="'.$reg['observations'].'" ><i class="fa fa-edit text-blue"></i>Editar informações</a></li>';
                      $text_bg = 'text-red';
                  }
                  else if ($reg['situation'] === 'open' and strtotime($reg['due_date']) < $today)
                  {
                      $situation = '<span class="label label-danger flat">Atrasado</spam>';
                      $write_dow = '<li><a href="javascript:;" data-action="write-dow-receipts" data-receipt-id="'.$reg['id'].'" data-contract-id="'.$actionid.'" data-discount="'.decimalMoeda($reg['discount']).'" data-discount-cause="'.$reg['discount_cause'].'" data-addition="'.decimalMoeda($reg['addition']).'" data-addition-cause="'.$reg['addition_cause'].'" data-observations="'.$reg['observations'].'" ><i class="fa fa-edit text-blue"></i>Editar informações</a></li>';
                      $text_bg = 'text-red';
                  }
                  else if ($reg['situation'] === null and strtotime($reg['due_date']) < $today)
                  {
                      $situation = '<span class="label label-danger flat">Atrasado</spam>';
                      $write_dow = '<li><a href="javascript:;" data-action="write-dow-receipts" data-receipt-id="'.$reg['id'].'" data-contract-id="'.$actionid.'" data-discount="'.decimalMoeda($reg['discount']).'" data-discount-cause="'.$reg['discount_cause'].'" data-addition="'.decimalMoeda($reg['addition']).'" data-addition-cause="'.$reg['addition_cause'].'" data-observations="'.$reg['observations'].'" ><i class="fa fa-edit text-blue"></i>Editar informações</a></li>';
                      $text_bg = 'text-red';
                  }
                  else if ($reg['situation'] === 'billed')
                  {
                      $situation = '<span class="label label-success flat">Faturado</spam>';
                      $write_dow = '';
                      $text_bg = '';
                  }
                  else
                  {
                      $situation = '<span class="label label-warning flat">Indefinido</spam>';
                      $write_dow = '';
                      $text_bg = '';
                  }
                    
                  $ico_observations = ($reg['observations'] !== null) ? '<a href="javascript:;" CLASS="pull-right ico-help label label-info flat" tabindex= "0" DATA-CONTROL="popover-hover" DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Observações" DATA-CONTENT="'.$reg['observations'].'"><i class="fa fa-eye"></i></a>' : '' ;
                  
                  $ico_addition_cause = ($reg['addition_cause'] !== null) ? '<a href="javascript:;" CLASS="pull-right ico-help" tabindex= "0" DATA-CONTROL="popover-hover" DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Observações" DATA-CONTENT="'.$reg['addition_cause'].'"><i class="fa fa-exclamation-circle"></i></a>' : '' ;
                    
                  $ico_discount_cause = ($reg['discount_cause'] !== null) ? '<a href="javascript:;" CLASS="pull-right ico-help" tabindex= "0" DATA-CONTROL="popover-hover" DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Observações" DATA-CONTENT="'.$reg['discount_cause'].'"><i class="fa fa-exclamation-circle"></i></a>' : '' ;

                  $item .= '<TR class="'.$text_bg.'">';
                  $item .= '<TD id="'.$reg['id'].'">'.$reg['id'].'</TD>';
                  $item .= '<TD class="hidden-xs">R$ '.decimalMoeda($reg['value_gross']).' '.$ico_observations .'</TD>';
                  $item .= '<TD class="hidden-xs">R$ '.decimalMoeda($reg['discount']).' '.$ico_discount_cause.'</TD>';
                  $item .= '<TD class="hidden-xs">R$ '.decimalMoeda($reg['addition']).' '.$ico_addition_cause.'</TD>';
                  $item .= '<TD>R$ '.decimalMoeda($reg['value_gross'] + $reg['addition'] - $reg['discount'] ).'</TD>'; 
                  $item .= '<TD>'.inverteData($reg['due_date']).'</TD>'; 
                  $item .= '<TD class="hidden-xs">'.$situation.'</TD>'; 
                  $item .= '<TD>';
                  $item .= '<div class="btn-group">';
                  $item .= '<button type="button" class="btn btn-default btn-sm hidden-xs">Ações</button>';
                  $item .= '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">';
                  $item .= '<span class="caret"></span>';
                  $item .= '</button>';
                  $item .= '<ul class="dropdown-menu pull-right" role="menu">';
                  $item .= $write_dow;
                  $item .= '<li><a href="javascript:;" data-action="low-receipt" data-receipt-id="'.$reg['id'].'" data-contract-id="'.$reg['contract_id'].'"><i class="fa fa-check text-success"></i> Dar baixa</a></li>';
                  $item .= '<li><a href="javascript:;" data-action="print-receipt" data-receipt-id="'.$reg['id'].'" data-contract-id="'.$actionid.'"><i class="fa fa-print text-maroon"></i> Gerar impressão</a></li>';
                  $item .= '</ul>';
                  $item .= '</div>';
                  $item .= '</TD>'; 
                  $item .= '</TR>'; 
                }
                $item .= '</TBODY>';
                $item .= '</TABLE>';

                if($clients and $total_reg > 0)
                {
                  echo $item;
                }
                else if($total_reg <= 0)
                {
                  echo '<div class="alert alert-info"><h4>Sem resultados!</h4>Não encontramos nenhum recibo cadastrado para está categoria.</div>';
                }
                else if(!$clients)
                {
                  echo '<div class="alert alert-warning"><h4>Atenção!</h4>Não encontramos nenhum recibo cadastrado para está categoria.</div>';
                }

                $query_after = array_sum(explode(' ', microtime()));
                $time = $query_after - $query_before ;
                $executionTime = substr($time, 0, 6);

                // paginação----------
                $busca_total = $con->query("SELECT COUNT(*) as id FROM receipts $where ");
                $total = $busca_total->fetch_array();
                $total = $total['id'];

                $prox = $pagination + 1;
                $ant = $pagination - 1;
                $ultima_pag = ceil($total / $limit);
                $penultima = $ultima_pag - 1;  
                @$adjacentes = 2;


                if ($pagination>1)
                {
                    $pages = '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$ant).'"><i class="fa fa-arrow-left"></i></a>';
                }

                if ($ultima_pag <= 5)
                {
                  for ($i=1; $i< $ultima_pag+1; $i++)
                  {
                    if ($i == $pagination)
                    {
                      @$pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a>';        
                    } else {
                      @$pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a>';  
                    }
                  }
                }

                if ($ultima_pag > 5)
                {
                  if ($pagination < 1 + (2 * $adjacentes))
                  {
                    for ($i=1; $i< 2 + (2 * $adjacentes); $i++)
                    {
                      if ($i == $pagination)
                      {
                        @$pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a>';        
                      } else {
                        @$pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a>';  
                      }
                    }
                    $pages .= '<li><a href="javascript:;">...</a></li>';
                    $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$penultima).'">'.$penultima.'</a></li>';
                    $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$ultima_pag).'">'.$ultima_pag.'</a></li>';
                  }

                  elseif($pagination > (2 * $adjacentes) && $pagination < $ultima_pag - 3)
                  {
                    $pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination=1').'">1</a></li>';       
                    $pages .= '<li><a href="javascript:;">...</a></li>';
                    for ($i = $pagination-$adjacentes; $i<= $pagination + $adjacentes; $i++)
                    {
                      if ($i == $pagination)
                      {
                        $pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a></li>';        
                      } else {
                        $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a></li>';  
                      }
                    }
                    $pages .= '<li><a href="javascript:;">...</a></li>';
                    $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$penultima).'">'.$penultima.'</a></li>';
                    $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$ultima_pag).'">'.$ultima_pag.'</a></li>';
                  }
                  else {
                    $pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination=1').'">1</a></li>';        
                    $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination=2').'">2</a></li>';  
                    for ($i = $ultima_pag - (2 + (2 * $adjacentes)); $i <= $ultima_pag; $i++)
                    {
                      if ($i == $pagination)
                      {
                        $pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a></li>';        
                      } else {
                        $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a></li>';  
                      }
                    }
                  }
                }
                if ($prox <= $ultima_pag && $ultima_pag > 2)
                {
                  $pages .= '<li><a href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$prox).'"><i class="fa fa-arrow-right"></i></a></li>';
                }
                ?>

              </DIV>

            </DIV><!-- /.box-body -->
        
        </DIV><!-- /.col-12 -->
        
        <div class="clearfix col-md-12">
            <p class="text-info"><?php echo ($_SESSION['user_type'] == 'suporte') ? '<strong>'.$executionTime.'</strong> segundos para encontrar': '' ; ?> <strong><?php echo $total_reg ?></strong> resultados para esta busca.</p>
        </div>

        <NAV class="col-md-12">
          <UL CLASS="pagination" id="pagination-mysql">
            <?php echo @$pages  ?>
          </UL>
        </NAV><!--//.pagination-->

  </DIV>  
</SECTION>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="add-receipts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Gerar recibos</h4>
      </div>
      <div class="modal-body">
      
        <p>Adicionar/remover recibos</p>
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-6">
                <div class="input-group input-group-sm" >
                    <div class="input-group-btn">
                        <a href="javascript:;" class="btn btn-success btn-sm btn-flat text-white" onClick="createRowsReceipts();" tabindex="1" title="Clique para adicionar"><i class="fa fa-plus"></i></a>
                    </div>
                    <input class="form-control" value="1" min="1" type="number" id="quantidade-receipts">
                    <div class="input-group-btn">
                        <a class="btn btn-danger btn-sm btn-flat" role="button" onClick="removeRowsReceipts();" tabindex="1" title="Remover recibos"><i class="fa fa-minus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="clearfix space-20"></div>
        
        <form name="form-add-receipts" method="POST" id="form-add-receipts" action="/app/modules/contracts/insert_receipts.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-form-reset="reset" data-action="submit-ajax" data-reload="true" autocomplete="off">

            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token']; ?>">
            <input type="HIDDEN" name="contract-id" value="<?php echo $actionid; ?>">
            
            <div id="container-receipts">
                <div class="receipts-itens" tabindex="1">
                    <div class="receipts-number label label-info flat ">1</div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <DIV CLASS="form-group has-feedback no-margin">
                            <LABEL><span class="required">*</span> Valor total do recibo</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group input-group-sm">
                                  <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                                  <input class="form-control" type="text" name="receipts-gross[]" data-control="mask-money" required>
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        </DIV>
                        <div class="clearfix space-20"></div>
                        <DIV CLASS="form-group has-feedback no-margin">
                            <LABEL><span class="required">*</span> Data de vencimento</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group date input-group-sm" data-control="datepicker">
                                  <SPAN CLASS="input-group-addon hidden-xs"><i class="fa fa-calendar" style="height: 10px !important;"></i></SPAN>
                                  <input class="form-control" type="text" name="receipts-due[]" data-control="mask-date" required>
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        </DIV>
                    </div>
                    
                    <div class="col-md-4 col-sm-12 col-xs-12">      
                        <DIV CLASS="form-group has-feedback no-margin">
                            <LABEL> Desconto</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group input-group-sm">
                                  <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                                  <input class="form-control" type="text" name="receipts-discount[]" data-control="mask-money">
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        </DIV>
                        <div class="clearfix space-20"></div>
                        <DIV CLASS="form-group has-feedback no-margin">
                            <LABEL>Motivo do descontos</LABEL>
                            <DIV CLASS="msg-validation">
                                <TEXTAREA NAME="discount-cause[]" class="form-control" STYLE="resize:none; min-height: 50px"></TEXTAREA>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        </DIV>
                    </div>
                            
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <DIV CLASS="form-group has-feedback no-margin">
                            <LABEL>Acréscimo</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group input-group-sm">
                                  <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                                  <input class="form-control" type="text" name="receipts-addition[]" data-control="mask-money">
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        </DIV>
                        <div class="clearfix space-20"></div>
                        <DIV CLASS="form-group has-feedback no-margin">
                            <LABEL>Motivo do acréscimo</LABEL>
                            <DIV CLASS="msg-validation">
                                <TEXTAREA NAME="additions-cause[]" class="form-control" STYLE="resize:none; min-height: 50px"></TEXTAREA>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        </DIV>
                    </div>
                    <div class="clearfix"></div>
                </div> <!--/. item--> 
                              
            </div><!--/. row-->
            
            <div class="clearfix space-30"></div>
            
            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary btn-flat">Salvar mudanças</button>
        </form>
        
      </div>
    </div>
  </div>
</div>


<!-- Modal para edição de recibo -->
<div class="modal fade bs-example-modal-lg" id="modal-write-dow-receipts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Vista da impressão</h4>
      </div>
      <div class="modal-body">
      
          <form name="form-update-receipts" method="POST" id="form-update-receipts" action="/app/modules/contracts/update_receipts.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-form-reset="reset" data-action="submit-ajax" data-reload="true" autocomplete="off">

              <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
              <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token']; ?>">
              
              <div class="row">
              
              <div class="col-md-6 col-sm-6 col-xs-6">
                  <DIV CLASS="form-group has-feedback disabled">
                    <LABEL FOR="receipt-id">Recibo número:</LABEL>
                    <DIV CLASS="msg-validation">
                        <input class="form-control" type="text" name="receipt-id" id="receipt-id">
                    </DIV>
                    <SPAN CLASS="fa form-control-feedback"></SPAN>
                    <DIV CLASS="help-block with-errors"></DIV> 
                 </DIV>
              </div>
              
              <div class="col-md-6 col-sm-6 col-xs-6">
                  <DIV CLASS="form-group has-feedback disabled">
                    <LABEL FOR="contract-id">Contrato ID:</LABEL>
                    <DIV CLASS="msg-validation">
                        <input class="form-control" type="text" name="contract-id" id="contract-id">
                    </DIV>
                    <SPAN CLASS="fa form-control-feedback"></SPAN>
                    <DIV CLASS="help-block with-errors"></DIV> 
                 </DIV>
              </div>
              
              <div class="clearfix"></div>
              
              <div class="col-md-6 col-sm-12 col-xs-12">      
                  <DIV CLASS="form-group has-feedback no-margin">
                        <LABEL for="receipt-discount"> Desconto</LABEL>
                        <DIV CLASS="msg-validation">
                          <DIV CLASS="input-group input-group-sm">
                              <SPAN CLASS="input-group-addon">$</SPAN>
                              <input class="form-control" type="text" name="receipt-discount" id="receipt-discount" data-control="mask-money">
                          </DIV>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>
                    </DIV>
                    <div class="clearfix space-20"></div>
                    <DIV CLASS="form-group has-feedback no-margin">
                        <LABEL for="receipt-discount-cause">Motivo do descontos</LABEL>
                        <DIV CLASS="msg-validation">
                            <TEXTAREA NAME="receipt-discount-cause" id="receipt-discount-cause" class="form-control" STYLE="resize:none; min-height: 50px"></TEXTAREA>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>
                    </DIV>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12">
                    <DIV CLASS="form-group has-feedback no-margin">
                        <LABEL for="receipt-addition">Acréscimo</LABEL>
                        <DIV CLASS="msg-validation">
                          <DIV CLASS="input-group input-group-sm">
                              <SPAN CLASS="input-group-addon">$</SPAN>
                              <input class="form-control" type="text" name="receipt-addition" id="receipt-addition" data-control="mask-money">
                          </DIV>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>
                    </DIV>
                    <div class="clearfix space-20"></div>
                    <DIV CLASS="form-group has-feedback no-margin">
                        <LABEL for="receipt-addition-cause">Motivo do acréscimo</LABEL>
                        <DIV CLASS="msg-validation">
                            <TEXTAREA NAME="receipt-addition-cause" id="receipt-addition-cause" class="form-control" STYLE="resize:none; min-height: 50px"></TEXTAREA>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"</SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>
                    </DIV>
                </div>
                
                <div class="clearfix space-20"></div>
                
                <div class="col-md-12">
                    <DIV CLASS="form-group has-feedback">
                        <LABEL for="receipt-observations"> Observações</LABEL>
                        <DIV CLASS="msg-validation">
                            <TEXTAREA NAME="receipt-observations" id="receipt-observations" class="form-control count-caractere" DATA-MAX-CARACTERE="2048" STYLE="resize:none; min-height: 50px"></TEXTAREA>
                            <DIV CLASS="restante-caractere"></DIV>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>
                    </DIV>
                </div>
    
              
              </div><!--//.row-->
              <div class="clearfix space-30"></div>
              
              <button type="submit" class="btn btn-primary btn-flat">Salvar mudanças</button>
              <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Fechar</button>
              
          </form>
      
      </div>
    </div>
  </div>
</div>

<!-- Modal para impressão de boleto  -->
<div class="modal fade bs-example-modal-lg" id="modal-print-receipts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Impimir recibo</h4>
      </div>
      <div class="modal-body">
            
            <div class="overlay text-center">
              <br>
              Aguarde um momento, estamos gerando o recibo para impressão.
              <br>
              <i class="fa fa-refresh fa-spin"></i> Carregando...
            </div>
                    
      </div>
      <div class="modal-footer">
          <button role="button" class="btn btn-primary btn-flat" data-action="print"><i class="fa fa-print"></i></button>
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Fechar</button>    
      </div>
    </div>
  </div>
</div>

<!-- Modal para dar baixa em boleto-->
<div class="modal fade" id="modal-low-r" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Dar baixa no recibo</h4>
      </div>
      <div class="modal-body">
      
          <form name="form-update-receipts" method="POST" id="form-update-receipts" action="/app/modules/contracts/write_down_receipt.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-form-reset="reset" data-action="submit-ajax" data-reload="true" autocomplete="off">

              <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
              <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token']; ?>">
              <input type="HIDDEN" name="contract-id" value="<?php echo $actionid; ?>">
              <input type="HIDDEN" name="receipt-id" id="write-receipt-id" value="" required>
              
              <DIV CLASS="form-group has-feedback">
                <LABEL><SPAN CLASS="required">*</SPAN> Dar baixa como:</LABEL>
                <div class="clearfix"></div>
                <div class="radio">
                    <div class="no-padding">
                      <label class="no-bold">
                        <input name="low-situation" value="del" type="radio" required>Deletar
                      </label>
                    </div>
                    <div class="no-padding">
                      <label class="no-bold">
                        <input name="low-situation" value="billed" type="radio" required>Marcar como pago
                      </label>
                    </div>
                </div>
                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                <DIV CLASS="help-block with-errors"></DIV>            
              </DIV>
              
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> Atenção!</h4>
                Esta ação requer autenticação, nos informe sua senha para continuar.
              </div>
              
              <div class="clearfix space-20"></div>
              
              <DIV CLASS="form-group has-feedback">
               <LABEL FOR="user-password" CLASS="control-label"><SPAN CLASS="required">*</SPAN> Informe sua senha </LABEL>
               <DIV CLASS="msg-validation">
                 <input type="password" autocomplete="OFF" class="form-control" name="user-password" id="user-password" REQUIRED maxlength="16" DATA-MINLENGTH="8" >
               </DIV>
               <SPAN CLASS="fa form-control-feedback"></SPAN>
               <DIV CLASS="help-block with-errors"></DIV>
              </DIV>
              
              <div class="clearfix space-30"></div>
              
              <button type="submit" class="btn btn-primary btn-flat">Continuar</button>
              <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cancelar</button>
              
          </form>
      </div>

    </div>
  </div>
</div>

<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/app/javascript/control_forms.js"></script>
<script src="/app/javascript/contracts.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
<script>
$(document).ready(function() {
    $('[data-control="datepicker"]').datepicker({
        format: 'dd/mm/yyyy',
    });
});
</script>
<script>
$(document).on('click', '[data-action="print"]', function(){
   printDiv(); 
});   
    
function printDiv() 
{
    //pega o Html da DIV
 var divElements = document.getElementById('print').innerHTML;
    //pega o HTML de toda tag Body
    var oldPage = document.body.innerHTML;

    //Alterna o body
    document.body.innerHTML = "<html><head>  <title>Tela de impressão</title> </head> <body>  " + divElements + "</body>";
    //Imprime o body atual
    window.print();
    //Retorna o conteudo original da página.
    document.body.innerHTML = oldPage;
}
</script>

<?php
        }
        else
        {
            echo '<div class="alert alert-warning">
                 <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
                 Nenhum contrato identificado com essas credenciais.<br>
                 Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
                 </div>';    
        }//. Se o select encontrou o usuário
        
    }//.se existe $_GET['actionid']  
}
else
{
    echo '<DIV CLASS="error-page">',
    '<P CLASS=" headline text-yellow"> <I CLASS="fa fa-lock fa-2x" ARIA-HIDDEN="true"></I></P>',
    '<DIV CLASS="error-content">',
    '<H3><I CLASS="fa fa-warning text-yellow"></I> Oops! Você não tem permissão para acessar esta página.</H3>',
    '<P>Você não tem privilégio suficiente para acessar esta página!<BR>
        Retorne a página <a href="/app/index/home">inicial</a>.',
    '</P>',
    '</DIV> ',
    '</DIV>';
}
?>