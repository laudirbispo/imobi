<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['contracts_view']) )
{ 
    $url = new app\controls\securePage();

?>
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Contratos</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/contracts"><i class="fa fa-file-text-o"></i> Contratos</a></LI>
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
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=todos'); ?>">Todas</a></LI>
                            </UL>
                        </LI>
                        <LI CLASS="dropdown"> 
                           <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Visualizar: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=rent&pagination=0'); ?>">Contratos de aluguel</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=sale&pagination=0'); ?>">Contratos de venda</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=management&pagination=0'); ?>">Contratos de administração</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=other&pagination=0'); ?>">Outros</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=all&pagination=0'); ?>"><I CLASS="fa fa-retweet"></I> Todos</a></LI>
                            </UL>
                        </LI>
                    </UL>
                    <form class="navbar-form navbar-right" role="search">
                        <DIV CLASS="form-group">
                            <input type="text" class="form-control" data-control="search-filter" placeholder="Buscar registros" style="width:400px">
                        </DIV>
                    </form>
                </DIV>
                <!-- /.navbar-collapse --> 
            </NAV>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid" data-control="data-reload">
    <DIV CLASS="row">
  
        <div class="col-md-12">
            <a href="/app/admin/add_contracts" class="btn btn-success btn-flat pull-right"><I CLASS="fa fa-plus"></I> <STRONG>Novo Contrato</STRONG></a>
        </div> <!-- /.col-12 -->
        <div class="clearfix space-20"></div>

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

        if (empty($_GET['limit']) or !isset($_GET['limit']) or !is_numeric($_GET['limit']))
        {
            $limit = 10;
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
            if ($select === 'rent')
            {
                $where = "WHERE type = 'rent'";
            }
            else if ($select === 'sale')
            {
                $where = "WHERE type = 'sale'";
            }
            else if ($select === 'management')
            {
                $where = "WHERE type = 'management'";
            }
            else if ($select === 'other')
            {
                $where = "WHERE type = 'other'";
            }
            else
            {
                $where = '';
            }
        }
        else
        {
            $where = '';
        }
    
        if (!empty($_GET['order']) and isset($_GET['order']))
        {
            $order = filterString($_GET['order'], 'CHAR');
            if ($order === 'date-desc')
            {
                $order = 'ORDER BY date_post DESC';
            }
            else if ($order === 'date-asc')
            {
                $order = 'ORDER BY date_post ASC';
            }
            else
            {
                $order = 'ORDER BY date_post DESC';
            }
        }
        else
        {
            $order = 'ORDER BY date_post DESC';
        }

        $con_db = new config\connect_db();
        $con = $con_db->connect();  
        
        $query_before = array_sum(explode(' ', microtime()));

        $contracts = $con->query("SELECT contract_id, owner_id, tenant_id, property_ref, type, valid_start FROM contracts $where $order LIMIT $start,$limit");
        $rows = $contracts->num_rows;

        if( $contracts and $rows > 0)
        {
            $item = '';

            while( $reg = $contracts->fetch_assoc() )
            {   
                if ($reg['type'] === 'rent')
                {
                    $part_2 = '<STRONG>Locatário:</STRONG> '.getClientName($reg['tenant_id']).'</br>';
                    $contract_type = '<STRONG>Tipo:</STRONG> Contrato de aluguel</P>';
                }
                else if ($reg['type'] === 'sale')
                {
                    $part_2 = '<STRONG>Comprador:</STRONG>'.getClientName($reg['tenant_id']).'</br>';
                    $contract_type = '<STRONG>Tipo:</STRONG> Contrato de compra e venda</P>';
                }
                else if ($reg['type'] === 'management')
                {
                    $part_2 = '<STRONG>Comprador:</STRONG> '.getClientName($reg['owner_id']).'</br>';
                    $contract_type = '<STRONG>Tipo:</STRONG> Administração</P>';
                }
                else 
                {
                    $part_2 = '';
                    $contract_type = '';
                }
                
                $item .= '<DIV CLASS="col-lg-3 col-xs-6" data-control="elem-filter">';
                $item .= '<DIV CLASS="card animated zoomIn">';
                $item .= '<DIV CLASS="card-content text-muted">';
                $item .= '<H4><STRONG>Contrato:</STRONG> '.$reg['contract_id'].'</H4>';
                $item .= '<P><STRONG>Locador:</STRONG> '.getClientName($reg['owner_id']).'</br>';
                $item .= $part_2 ;
                $item .= '<STRONG>Imóvel REF:</STRONG> '.$reg['property_ref'].'</br>';
                $item .= $contract_type;
                $item .= '</DIV>';
                $item .= '<DIV CLASS="card-footer">';
                $item .= '<span CLASS="" title=""></span>';
                $item .= '<div class="pull-right droped">';
                $item .= '<a role="button" class="dropdown-toggle text-mediumgray" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cogs"></i></a>';
                $item .= '<ul class="dropdown-menu pull-right" role="menu">';
                $item .= '<li><a HREF="/app/admin/contracts_details/'.$reg['contract_id'].'" ><I CLASS="fa fa-external-link-square text-blue"></I> Ver mais</a></li>';
                $item .= '<li class="divider"></li>';
                $item .= '<li><a HREF="javascript:;" role="BUTTON" data-control="del-contract" data-contract-id="'.$reg['contract_id'].'"><I CLASS="fa fa-trash text-red"></I> Deletar contrato</a></li>';
                $item .= '</ul>';
                $item .= '</DIV>';
                $item .= '</DIV>';
                $item .= '</DIV>';
                $item .= '</DIV>';

            }// end while

            echo $item;
        }
        else
        {
            echo '<div class="col-md-12 clearfix">
                 <div class="callout callout-info">
                 <h4>Nenhum contrato encontrado para essa busca.</h4>
                 </div></div>';
        }
    
        $query_after = array_sum(explode(' ', microtime()));
        $time = $query_after - $query_before ;
        $executionTime = substr($time, 0, 6);

        // paginação----------
        $total = $rows;
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

        <div class="clearfix"></div>
        <div class="col-md-12">
            <p class="text-info"><?php echo ($_SESSION['user_type'] == 'suporte') ? '<strong>'.$executionTime.'</strong> segundos para encontrar': '' ; ?> <strong><?php echo $total ?></strong> resultados para esta busca.</p>
        </div>

        <NAV class="col-md-12">
          <UL CLASS="pagination" id="pagination-mysql">
            <?php echo @$pages  ?>
          </UL>
        </NAV><!--//.pagination-->

  </DIV>  
</SECTION>
<!-- Modal para dar baixa em boleto-->
<div class="modal fade" id="modal-del-contract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Excluir contrato</h4>
      </div>
      <div class="modal-body">
         
          <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-info"></i> Atenção!</h4>
            Esta ação requer autenticação, nos informe sua senha para continuar.<br>
            Após o fim do processo os dados não poderão ser recuperados.
          </div>
          
          <div class="clearfix space-20"></div>
      
          <form name="form-delete-contracts" method="POST" id="form-delete-contracts" action="/app/modules/contracts/delete_contract.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-form-reset="reset" data-action="submit-ajax" data-reload="true" autocomplete="off">

              <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
              <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token']; ?>">
              <input type="HIDDEN" name="contract-id" id="contract-id" value="">
              
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

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/clients.js"></script>
<script src="/app/javascript/contracts.js"></script>
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
