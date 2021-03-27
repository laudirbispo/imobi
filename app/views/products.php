<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['products_view']) )
{ 
    $url = new app\controls\securePage();
?>
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>SHOP</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/products"><i class="fa fa-shopping-cart"></i> Shop</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <NAV CLASS="navbar flat">
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
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&limit=todos'); ?>">Todas</a></LI>
                            </UL>
                        </LI>
                        <LI CLASS="dropdown"> 
                           <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Visualizar: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=featured&pagination=0'); ?>"><I CLASS="fa fa-star text-yellow"></I> Destaques na minha página</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=hide&pagination=0'); ?>"><I CLASS="fa fa-eye-slash text-violet"></I> Elementos ocultos</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=sold&pagination=0'); ?>"><I CLASS="fa fa-tag text-mediumgray"></I> Imóveis vendidos</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=rented&pagination=0'); ?>"><I CLASS="fa fa-tag text-mediumgray"></I> Imóveis alugados</a></LI>
                                <LI><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=all&pagination=0'); ?>"><I CLASS="fa fa-retweet"></I> Todos</a></LI>
                            </UL>
                        </LI>
                    </UL>
                    <form class="navbar-form navbar-right" role="search">
                        <DIV CLASS="form-group">
                            <input type="text" class="form-control" data-control="search-filter" placeholder="Buscar registros" style="width:250px">
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
        <a href="/app/admin/add_products" class="btn btn-success btn-flat pull-right"><I CLASS="fa fa-cart-plus"></I> <STRONG>Adicionar Produtos</STRONG></a>
    </div>
    
    <div class="clearfix space-20"></div>
    
    <DIV CLASS="" id="table-products">
       
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
            if ($select === 'featured')
            {
                $where = "WHERE featured = 'Y'";
            }
            else if ($select === 'hide')
            {
                $where = "WHERE visibility = 'N'";
            }
            else if ($select === 'rented')
            {
                $where = "WHERE status = 'alugado'";
            }
            else if ($select === 'sold')
            {
                $where = "WHERE visibility = 'rented'";
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

        $properties = $con->query("SELECT id, status, ref, situation, finality, segment, type, value_total, hidden_value_total, value_monthly, hidden_value_monthly, address_state, address_city, address_street, address_number, address_neighborhood, cover_image, featured, visibility, date_post, user_post FROM properties $where $order LIMIT $start,$limit");
        $rows = $properties->num_rows;

        if( $properties and $rows > 0)
        {

            $item = '';

            while( $reg = $properties->fetch_assoc() )
            {

                $cover_image = (!empty($reg['cover_image'])) ? SUBDOMAIN_IMGS.'/docs/properties/'.$reg['id'].'/small/'.$reg['cover_image'] : '/app/images/no-image.png' ;

                $item .= '<div class="col-md-4 col-sm-6 col-xs-12" data-control="elem-filter">';
                $item .= '<div class="card card-products flat animated zoomIn">';
                $item .= '<div class="card-image" data-background="'.$cover_image.'" style="background-image: url(/app/images/loading-circle.gif);">';
                $item .= '<a class="featured text-yellow" data-action="featured" href="javascript:;" title="Marcar como destaque" data-tid="'.$reg['id'].'"><i class="fa fa-2x fa-star-o"></i></a>';
                $item .= '</div>';
                $item .= '<div class="card-content">';
                $item .= '<a class="view text-violet" data-action="hidden" href="javascript:;" title="Visível no meu site/tornar invisível" data-id="'.$reg['id'].'"><i class="fa fa-eye"></i></a>';
                $item .= '<p class="text-capitalize text-substr card-title">Product name</p>';
                $item .= '<small><strong>REF:</strong>5616atdGE</small>';
                $item .= '</div>';
                $item .= '<div class="card-footer">';
                $item .= '<p class="pull-left">R$ 180,00</p>';
                $item .= '<div class="pull-right">';
                $item .= '<a role="button" class="dropdown-toggle text-mediumgray" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cogs"></i></a>';
                $item .= '<ul class="dropdown-menu pull-right" role="menu">';
                $item .= '<li><a HREF="/app/admin/edit_properties/'.base64_encode($reg['id']).'" ><I CLASS="fa fa-edit text-green"></I> Editar informações</a></li>';
                $item .= '<li><a HREF="/app/admin/preview_property/'.base64_encode($reg['id']).'" ><I CLASS="fa fa-external-link-square text-blue"></I> Prever</a></li>';
                $item .= '<li><a HREF="/app/admin/images_properties/'.base64_encode($reg['id']).'" ><I CLASS="fa fa-picture-o text-orange"></I> Imagens</a></li>';
                $item .= '<li class="divider"></li>';
                $item .= '<li><a href="javascript:;" data-control="change-status-properties" data-control-value="vendido" data-tid="'.$reg['id'].'"><I CLASS="fa fa-tag text-mediumgray"></I> Marcar como vendido</a></li>';
                $item .= '<li><a href="javascript:;" data-control="change-status-properties" data-control-value="alugado" data-tid="'.$reg['id'].'"><I CLASS="fa fa-tag text-mediumgray"></I> Marcar como alugado</a></li>';
                $item .= '<li><a href="javascript:;" data-control="change-status-properties" data-control-value="remove" data-tid="'.$reg['id'].'"><I CLASS="fa fa-times text-mediumgray"></I> Remover marcação</a></li>';
                $item .= '<li class="divider"></li>';
                $item .= '<li><a HREF="javascript:;" data-control="del-properties" data-tid="'.$reg['id'].'"><I CLASS="fa fa-trash"></I> Deletar produto</a></li>';
                $item .= '</ul>';
                $item .= '</div>';
                $item .= '</div>';
                $item .= '</div>';
                $item .= '</div><!-- //. Card item -->';

            }// end while

            echo $item;

        }
        else
        {
            echo '<div class="col-md-12 clearfix">
                 <div class="callout callout-info">
                 <h4>Nenhum imóvel encontrado para essa busca.</h4>
                 </div></div>';
        }
    
        // crônometro da query
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
              @$pages .= '<li><a class="atual"  href="'.$url->getVars($_SERVER['REQUEST_URI'], '&pagination='.$i).'">'.$i.'</a>';  
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
        
        </div>

        <div class="clearfix col-md-12">
            <p class="text-info"><?php echo ($_SESSION['user_type'] == 'suporte') ? '<strong>'.$executionTime.'</strong> segundos para encontrar': '' ; ?> <strong><?php echo $total ?></strong> resultados para esta busca.</p>
        </div>

        <NAV class="col-md-12">
          <UL CLASS="pagination" id="pagination-mysql">
            <?php echo @$pages  ?>
          </UL>
        </NAV><!--//.pagination-->


    </div>
</SECTION>

<script src="/app/javascript/properties.js"></script>
<script>
$(document).ready(function(){
    
   //$('#table-properties').load("/app/modules/properties/query_properties.php", function(){
      // new EagerImageLoader();
   //});
function getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
    
var foo = getParameterByName('foo'); // "lorem"
var bar = getParameterByName('bar'); // "" (present with empty value)
var baz = getParameterByName('baz'); // "" (present with no value)
var qux = getParameterByName('qux'); // null (absent)
   
    url = window.location.href;
  //location.href = url + '&teste=laudir'; 

});
</script>
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
