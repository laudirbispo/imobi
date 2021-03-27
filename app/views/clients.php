<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['clients_view']) )
{ 
    $url = new app\controls\securePage();

?>
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Clientes Cadastrados</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/clients"><i class="fa fa-users"></i> Clientes</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <NAV CLASS="navbar">
               <!-- Brand and toggle get grouped for better mobile display -->
                <DIV CLASS="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" DATA-TOGGLE="collapse" DATA-TARGET="#bs-example-navbar-collapse-1" aria-expanded="false"><i class="fa fa-bars"></i></button>
                </DIV>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <DIV CLASS="collapse navbar-collapse" ID="bs-example-navbar-collapse-1">
                    <UL CLASS="nav navbar-nav">
                        <LI CLASS="dropdown"> <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ordernar Por: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=date-desc'); ?>"><I CLASS="fa fa-sort-numeric-desc"></I> Data mais recente</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=date-asc'); ?>"><I CLASS="fa fa-sort-numeric-asc"></I> Data mais antiga</a></LI>
                                <LI class="hidden"><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=views-desc'); ?>"><I CLASS="fa fa-sort-amount-desc"></I> Mais acessadas</a></LI>
                                <LI class="hidden"><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=views-desc'); ?>"><I CLASS="fa fa-sort-amount-asc"></I> Menos acessadas</a></LI>
                                <LI class="hidden"><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=views-desc'); ?>"><I CLASS="fa fa-star"></I> Marcadas como favoritas</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&order=vclear'); ?>"><I CLASS="fa fa-bars"></I> Padrão</a></LI>
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
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=person-physical&pagination=0'); ?>">Pessoas Físicas</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=person-juridical&pagination=0'); ?>">Pessoas Jurídicas</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=all&pagination=0'); ?>"><I CLASS="fa fa-retweet"></I> Todos</a></LI>
                            </UL>
                        </LI>
                    </UL>
                    <form class="navbar-form navbar-right" role="search">
                        <DIV CLASS="form-group">
                            <input type="text" class="form-control" id="filter-table" DATA-TABLE="table-clients" placeholder="Buscar registros" style="width:400px">
                        </DIV>
                    </form>
                </DIV>
                <!-- /.navbar-collapse --> 
            </NAV>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
  
    <div class="col-md-12">
       
        <a href="/app/admin/add_clients" class="btn btn-success btn-flat pull-right margin-left"><I CLASS="fa fa-user-plus"></I> <STRONG>Cadastrar Cliente</STRONG></a>
        <a href="/app/admin/add_contracts" class="btn btn-success btn-flat pull-right"><I CLASS="fa fa-plus"></I> <STRONG>Novo Contrato</STRONG></a>
        
        <div class="clearfix space-20"></div>

        <DIV CLASS="box box-solid">
          <DIV CLASS="box-header">
            <H3 CLASS="box-title">CLIENTES</H3>
          </DIV>
          <!-- /.box-header -->
          <DIV CLASS="box-body no-padding"> 
            <?php        

            $where = '';

            if (empty($_GET['pagination']) or !isset($_GET['pagination']) or !is_numeric($_GET['pagination']))
            {
                $pagination = '0';
            }
            else
            {
                $pagination = filterString($_GET['pagination'], 'INT'); 
            }

            if (empty($_GET['limit']) or !isset($_GET['limit']))
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
                if ($select === 'person-physical')
                {
                    $where = "WHERE cl.client_type = 'physical'";
                }
                else if ($select === 'person-juridical')
                {
                    $where = "WHERE cl.client_type = 'juridical'";
                }
                else if ($select === 'owner')
                {
                    $where = "WHERE cl.client_part = 'owner'";
                }
                else if ($select === 'tenant')
                {
                    $where = "WHERE cl.client_part = 'tenant'";
                }
                else
                {
                    $where = '';
                }
            }

            if (!empty($_GET['order']) and isset($_GET['order']))
            {
                $order = filterString($_GET['order'], 'CHAR');
                if ($order === 'date-desc')
                {
                    $order = 'ORDER by id DESC';
                }
                else if ($order === 'date-asc')
                {
                    $order = 'ORDER by id ASC';
                }
                else
                {
                    $order = 'ORDER by id DESC';
                }
            }
            else
            {
                $order = 'ORDER by id DESC';
            }

            $query_before = array_sum(explode(' ', microtime()));
    
            $con_db = new config\connect_db();
            $con = $con_db->connect();

            $clients = $con->query("SELECT cl.id, cl.client_type, cl.client_name, cl.client_last_name, cl.client_fantasy_name FROM clients cl $where $order LIMIT $start,$limit");
            $total_reg = $clients->num_rows;

            $item = '<TABLE CLASS="table table-striped table-responsive dataTable" id="table-clients"> ';
            $item .= '<THEAD> ';
            $item .= '  <TR>';
            $item .= '    <TH CLASS="hidden-xs" STYLE="width:10% !important;">CLIENT_ID</TH>';
            $item .= '    <TH STYLE="width:30%!important;">CLIENTE</TH>';
            $item .= '    <TH CLASS="hidden-xs" STYLE="width:20% !important;">TIPO DE CLIENTE</TH> ';
            $item .= '    <TH STYLE="width:20% !important;">CONTRATOS</TH> ';
            $item .= '    <TH STYLE="width:20% !important;">AÇÕES</TH> ';
            $item .= '  </TR> ';
            $item .= '</THEAD>';
            $item .= '<TBODY ID="table-cars-body">'; 

            while($reg = $clients->fetch_array())
            {

              if($reg['client_type'] === 'physical')
              {
                  $client = $reg['client_name'].' '.$reg['client_last_name'];
                  $client_type = 'Pessoa Física';
              }
              else if ($reg['client_type'] === 'juridical')
              {
                  $client = $reg['client_fantasy_name'];
                  $client_type = 'Pessoa Jurídica';
              }
              else
              {
                  if (empty($reg['client_name']) and empty($reg['client_fantasy_name']))
                  {
                      $client = 'Não informado';
                  }
                  else if (!empty($reg['client_name']))
                  {
                      $client = $reg['client_name'].' '.$reg['client_last_name'];
                  }
                  else if (!empty($reg['client_fantasy_name']))
                  {
                      $client = $reg['client_fantasy_name'];
                  }
                  else
                  {
                      $client = 'Não informado';
                  }

                  $client_type = 'Pessoa Física/Jurídica';
              }

              $item .= '<TR id="tr-'.$reg['id'].'">';
              $item .= '<TD CLASS="hidden-xs">'.$reg['id'].'</TD> ';
              $item .= '<TD><a class="link" title="'.$client.'"><strong>'.substr_replace($client, (strlen($client) > 20 ? '...' : ''), 20).'</strong></a>';
              $item .= '<a class="pull-right uppercase label label-info flat" data-action="load-info-client" data-client-id="'.base64_encode($reg['id']).'" role="button" tabindex="1" title="Informações básicas do cliente"><i class="fa fa-info"></i></a>';
              $item .= '</TD>';
              $item .= '<TD CLASS="hidden-xs">'.$client_type.'</TD>';
              $item .= '<TD>'.listContracts($reg['id']).'</TD>';
              $item .= '<TD>';
              $item .= '<div class="btn-group">';
              $item .= '<button type="button" class="btn btn-default btn-sm hidden-xs">Ações</button>';
              $item .= '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">';
              $item .= '<span class="caret"></span>';
              $item .= '<span class="sr-only">Toggle Dropdown</span>';
              $item .= '</button>';
              $item .= '<ul class="dropdown-menu pull-right" role="menu">';
              $item .= '<li><a href="/app/admin/edit_clients/'.base64_encode($reg['id']).'"><i class="fa fa-edit text-green"></i> Editar informações</a></li>';
              $item .= '<li class="divider"></li>';
              $item .= '<li><a href="#" role="button" data-action="del-client" data-client-id="'.$reg['id'].'"><i class="fa fa-trash text-red"></i> Deletar cliente</a></li>';
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
              echo '<div class="alert alert-info"><h4>Sem resultados!</h4>Não encontramos nenhum cliente cadastrado para está categoria.</div>';
            }
            else if(!$clients)
            {
              echo '<div class="alert alert-warning"><h4>Atenção!</h4>Não encontramos nenhum cliente cadastrado para está categoria.</div>';
            }
            
            // crônometro da query
            $query_after = array_sum(explode(' ', microtime()));
            $time = $query_after - $query_before ;
            $executionTime = substr($time, 0, 6);

            // paginação----------
            $total = $total_reg;
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
                  @$pages .= '<li><a class="atual" href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a>';  
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
    </div> <!-- /.col-12 -->
    
    <div class="clearfix col-md-12">
        <p class="text-info"><?php echo ($_SESSION['user_type'] == 'suporte') ? '<strong>'.$executionTime.'</strong> segundos para encontrar': '' ; ?> <strong><?php echo $total ?></strong> resultados para esta busca.</p>
    </div>

    <NAV class="col-md-12">
      <UL CLASS="pagination" id="pagination-mysql">
        <?php echo @$pages  ?>
      </UL>
    </NAV><!--//.pagination-->

  </DIV>  
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/clients.js"></script>
<?php
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
