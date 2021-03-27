<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if($_SESSION['vehicles_read'] !== '1')
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}

if( empty($_GET['pag']) or !isset($_GET['pag']) )
{
    if( empty($_SESSION['pag-vehicles']) or !isset($_SESSION['pag-vehicles']) )
    {
        $pagination = '0';
        $_SESSION['pag-vehicles'] = $pagination;
    }
    else
    {
        $pagination = $_SESSION['pag-vehicles'];
    }
}
else
{
    $pagination = filterString($_GET['pag'], 'INT'); 
    $_SESSION['pag-vehicles'] = $pagination;
}
        
if( empty($_GET['order']) or !isset($_GET['order']) )
{
    if( empty($_SESSION['order-vehicles']) or !isset($_SESSION['order-vehicles']) )
    {
        $order = 'ORDER BY `id` DESC ';
        $_SESSION['order-vehicles'] = $order;
    }
    else
    {
        $order = $_SESSION['order-vehicles'];
    }
}
else
{
   if($_GET['order'] === 'date-asc') { $order = "ORDER BY `date_post` ASC" ; }
   else if($_GET['order'] === 'date-desc') { $order = "ORDER BY `date_post` DESC" ; }
   else if($_GET['order'] === 'views-desc') { $order = "ORDER BY `views` DESC" ; } 
   else if($_GET['order'] === 'views-asc') { $order = "ORDER BY `views` ASC" ; }
   else if($_GET['order'] === 'featured') { $order = "ORDER BY `featured` DESC" ; }
   else if($_GET['order'] === 'clear') { $order = "ORDER BY `id` DESC" ; } 
   else { $order = "ORDER BY `id` DESC" ; } 
   $_SESSION['order-vehicles'] = $order;
}

if( empty($_GET['limite']) or !isset($_GET['limite']) )
{
    if( empty($_SESSION['limite-vehicles']) or !isset($_SESSION['limite-vehicles']) )
    {
        $limite = '50';
    }
    else
    {
        $limite = $_SESSION['limite-vehicles'];
    }
}
else if ($_GET['limite'] == 'all')
{
    $inicio = 0;
    $limite = 99999;
}
else 
{
    $limite = filterString($_GET['limite'], 'INT');
    $_SESSION['limite-vehicles'] = $limite;
}
    
if ($pagination and $pagination != '')
{
  $inicio = ($pagination - 1) * $limite;
}
else
{
  $inicio = 0;
}

if( empty($_GET['categoria']) or !isset($_GET['categoria']) )
{
    if( empty($_SESSION['categoria-vehicles']) or !isset($_SESSION['categoria-vehicles']) )
    {
        $categoria = 'cars';
        $_SESSION['categoria-vehicles'] =  $categoria;
    }
    else
    {
        $categoria = $_SESSION['categoria-vehicles'];
    }
}
else
{
    $categoria = filterString($_GET['categoria'], 'CHAR');
    $_SESSION['categoria-vehicles'] =  $categoria;
} 

if( $categoria === 'cars' )
{
    $edit_page = 'edit_cars';
    $upload_images = 'images_car';
}
else if( $categoria === 'motorcycles' )
{
    $edit_page = 'edit_motorcycles';
    $upload_images = 'images_motorcycle';
}
else
{
    $edit_page = 'access_denied';
    $upload_images = '';
}
?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12 no-padding">
            <H4 CLASS="text-darkgray"><STRONG>Veículos</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home" ARIA-HIDDEN="true"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="callout callout-info visible-xs">
  <H4>Importante!</H4>
  <P>Para melhor navegação neste dispositivo, alguns elementos foram ocultados!<BR> Use um computador para ter acesso a todas as funcionalidades que este sistema disponibiliza!</P>
</DIV>

<DIV CLASS="clearfix"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
    <NAV CLASS="navbar">
      <DIV CLASS="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <DIV CLASS="navbar-header">
          <button type="button" class="navbar-toggle collapsed" DATA-TOGGLE="collapse" DATA-TARGET="#bs-example-navbar-collapse-1" aria-expanded="false"><i class="fa fa-bars"></i></button>
        </DIV>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <DIV CLASS="collapse navbar-collapse" ID="bs-example-navbar-collapse-1 text-white">
          <UL CLASS="nav navbar-nav">
            <LI CLASS="dropdown">
              <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ordernar Por: <SPAN CLASS="caret"></SPAN></a>
              <UL CLASS="dropdown-menu">
                <LI><a href="?page=vehicles&order=date-desc"><I CLASS="fa fa-sort-numeric-desc"></I> Data mais recente</a></LI>
                <LI><a href="?page=vehicles&order=date-asc"><I CLASS="fa fa-sort-numeric-asc"></I> Data mais antiga</a></LI>
                <LI><a href="?page=vehicles&order=views-desc"><I CLASS="fa fa-sort-amount-desc"></I> Mais acessados</a></LI>
                <LI><a href="?page=vehicles&order=views-asc"><I CLASS="fa fa-sort-amount-asc"></I> Menos acessados</a></LI>
                <LI><a href="?page=vehicles&order=featured"><I CLASS="fa fa-thumb-tack"></I> Ná minha página inicial</a></LI>
                <LI><a href="?page=vehicles&order=clear"><I CLASS="fa fa-bars"></I> Padrão</a></LI>
              </UL>
            </LI>
            <LI CLASS="dropdown">
              <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Número de Registros: <SPAN CLASS="caret"></SPAN></a>
              <UL CLASS="dropdown-menu">
                <LI><a href="?page=vehicles&limite=25&pag=1">25</a></LI>
                <LI><a href="?page=vehicles&limite=50&pag=1" title="Valor padrão!">50</a></LI>
                <LI><a href="?page=vehicles&limite=100&pag=1">100</a></LI>
                <LI><a href="?page=vehicles&limite=250&pag=1">250</a></LI>
                <LI><a href="?page=vehicles&limite=500&pag=1">500</a></LI>
                <LI><a href="?page=vehicles&limite=all&pag=1" title="O sistema pode ficar lento!">Todas(lento)</a></LI>
              </UL>
            </LI>
            <LI CLASS="dropdown">
              <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categoria <SPAN CLASS="caret"></SPAN></a>
              <UL CLASS="dropdown-menu">
                <LI><a href="?page=vehicles&categoria=cars&pag=1">Carros</a></LI>
                <LI><a href="?page=vehicles&categoria=motorcycles&pag=1">Motos</a></LI>
              </UL>
            </LI>
          </UL>
          
          <form class="navbar-form navbar-right" role="search">
            <DIV CLASS="form-group">
              <input type="text" class="form-control" id="filter-table" DATA-TABLE="table-cars" placeholder="Buscar registros" style="width:400px">
            </DIV>  
          </form>
          
        </DIV><!-- /.navbar-collapse -->
      </DIV><!-- /.container -->
    </NAV>
    
    <form action="/app/modules/vehicles/delete_vehicles.php" id="del-all-cars" data-action="submit-ajax" data-form-reset="noreset" enctype="application/x-www-form-urlencoded" data-reload="true">
    
    <input type="hidden" name="categoria" value="<?php echo $categoria ?>">
    
    <DIV CLASS="box">
      <DIV CLASS="box-header" STYLE="border-bottom:1px solid #F5F5F5">
        <H3 CLASS="box-title">Garagem</H3>
        <DIV CLASS="box-tools">     
          <a href="admin.php?page=add_cars" class="btn"><I CLASS="fa fa-car"></I><STRONG>Adicionar carros</STRONG></a>
          <a href="admin.php?page=add_motorcycles" class="btn"><I CLASS="fa fa-motorcycle"></I><STRONG>Adicionar motos</STRONG></a>
        </DIV>
      </DIV>
      <!-- /.box-header -->
      <DIV CLASS="box-body no-padding"> 
        <?php               
        
        $print_vehicles = '<TABLE CLASS="table table-striped table-responsive dataTable" ID="table-cars" data-control="data-reload"> ';
        $print_vehicles .= '<THEAD> ';
        $print_vehicles .= '  <TR> ';
        $print_vehicles .= '    <TH STYLE="width:5% !important;"><input type="checkbox" data-control="select-all"></TH>';
        $print_vehicles .= '    <TH CLASS="hidden-xs" STYLE="width:5% !important;">ID</TH>';
        $print_vehicles .= '    <TH STYLE="width:35% !important;">MARCA/MODELO</TH>';
        $print_vehicles .= '    <TH CLASS="hidden-xs" STYLE="width:5% !important;">ANO</TH> ';
        $print_vehicles .= '    <TH STYLE="width:5% !important;">CAPA</TH>';
        $print_vehicles .= '    <TH STYLE="width:10% !important;">VALOR</TH>  ';
        $print_vehicles .= '    <TH CLASS="hidden-xs" STYLE="width:5% !important;"><i class="fa fa-eye" title="Número de visualizações da página."></i></TH>  ';
        $print_vehicles .= '    <TH STYLE="width:25% !important;">AÇÕES</TH> ';
        $print_vehicles .= '  </TR> ';
        $print_vehicles .= '</THEAD>';
        $print_vehicles .= '<TBODY ID="table-cars-body">'; 
        
        $con_db = new connect_db();
        $con = $con_db->connect();
        
        $vehicles = $con->query("SELECT `id`, `marca`, `modelo`, `submodelo`, `ano_fab`, `ano_mod`, `valor`, `image_capa`, `featured`, `views` FROM $categoria $order LIMIT $inicio,$limite") or die($con_db->serverFailure());
        $total_reg = $vehicles->num_rows;
        
        while($reg = $vehicles->fetch_array())
        {
          
          $capa = (!empty($reg['image_capa'])) ? '<I CLASS="fa fa-check pull-right color-verde" ARIA-HIDDEN="true"  ROLE="button" DATA-PLACEMENT="left" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Tudo certo" DATA-CONTENT="A imagem de capa está definida!"></I>' : '<I CLASS="fa fa-exclamation pull-right color-red" ARIA-HIDDEN="true"  ROLE="button" DATA-PLACEMENT="left" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Atenção" DATA-CONTENT="Escolha uma imagem para a capa!"></I>' ;
          
          if ($reg['featured'] === '1') 
          {
            $featured = '<span class="label bg-violet btn btn-flat  btn-anime-1" STYLE="cursor:pointer;" role="BUTTON" data-control="featured-car" data-id="'.$reg['id'].'" data-action="unfeatured" data-categoria="'.$categoria.'" title="Retire este veículo da minha página inicial"><I CLASS="fa fa-thumb-tack"></I></span> ';
            $featured_icon = '<i class="fa fa-thumb-tack pull-right car-featured" aria-hidden="true" title="Veículo em destaque na página inicial."></i>';
          }
          else
          {
            $featured = '<span class="label bg-violet btn btn-flat  btn-anime-1" STYLE="cursor:pointer;" role="BUTTON" data-control="featured-car" data-id="'.$reg['id'].'" data-action="featured" data-categoria="'.$categoria.'" title="Destaque este veículo na minha página inicial"><I CLASS="fa fa-thumb-tack"></I></span> ';
            $featured_icon = '<i class="fa fa-thumb-tack pull-right" aria-hidden="true" title="Veículo em destaque na página inicial."></i>';
          }
          
          $print_vehicles .= '<TR>';
          $print_vehicles .= '  <TD><input type="checkbox" name="del[]" value="'.$reg['id'].'" data-control="checkebox-del"></TD>';
          $print_vehicles .= '  <TD CLASS="hidden-xs">'.$reg['id'].'</TD> ';
          $print_vehicles .= '  <TD>'.$reg['marca'].' - '.$reg['modelo'].' '.$reg['submodelo'].' '.$featured_icon.' </TD>';
          $print_vehicles .= '  <TD CLASS="hidden-xs">'.$reg['ano_fab'].'/'.$reg['ano_mod'].'</TD>';
          $print_vehicles .= '  <TD>'.$capa.'</TD>'; 
          $print_vehicles .= '  <TD>'.decimalMoeda($reg['valor']).'</TD>'; 
          $print_vehicles .= '  <TD CLASS="hidden-xs">'.$reg['views'].'</TD> ';
          $print_vehicles .= '  <TD><a href="?page='.$upload_images.'&id='.base64_encode($reg['id']).'" class="label label-success btn btn-flat  btn-anime-1" title="Editar imagens do veículo"><i class="fa fa-picture-o"></i></a> ';
          $print_vehicles .= '<a href="?page='.$edit_page.'&id='.base64_encode($reg['id']).'" class="label label-info btn btn-flat btn-anime-1" title="Editar informações do veículo"><I CLASS="fa fa-edit"></I></a> ';
          $print_vehicles .= $featured; 
          $print_vehicles .= '<span class="label label-danger btn btn-flat btn-anime-1" data-control="delete-vehicles" role="BUTTON"  data-id="'.$reg['id'].'" data-categoria="'.$categoria.'"><I CLASS="fa fa-trash-o"></I></span></TD>'; 
          $print_vehicles .= '</TR>'; 
        }
        $print_vehicles .= '</TBODY>';
        $print_vehicles .= '</TABLE>';
        
        if($vehicles and $total_reg > 0)
        {
          echo $print_vehicles;
        }
        else if($total_reg <= 0)
        {
          echo '<div class="alert alert-info"><h4>Sem resultados!</h4>Não encontramos nenhum veículo cadastrado para está categoria.</div>';
        }
        else if(!$vehicles)
        {
          echo ($con_db->serverFailure());
        }
        
        // paginação----------
        $busca_total = $con->query("SELECT COUNT(*) as `id` FROM $categoria ");
        $total = $busca_total->fetch_array();
        $total = $total['id'];

        $prox = $pagination + 1;
        $ant = $pagination - 1;
        $ultima_pag = ceil($total / $limite);
        $penultima = $ultima_pag - 1;  
        @$adjacentes = 2;
        
        
        if ($pagination>1)
        {
          $paginationinacao = '<li><a href="?page=vehicles&pag='.$ant.'"><i class="fa fa-arrow-left"></i></a>';
        }
          
        if ($ultima_pag <= 5)
        {
          for ($i=1; $i< $ultima_pag+1; $i++)
          {
            if ($i == $pagination)
            {
              @$paginationinacao .= '<li><a class="atual" href="?page=vehicles&pag='.$i.'">'.$i.'</a>';        
            } else {
              @$paginationinacao .= '<li><a href="?page=vehicles&pag='.$i.'">'.$i.'</a>';  
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
                @$paginationinacao .= '<li><a class="atual" href="?page=vehicles&pag='.$i.'">'.$i.'</a>';        
              } else {
                @$paginationinacao .= '<li><a href="?page=vehicles&pag='.$i.'">'.$i.'</a>';  
              }
            }
            $paginationinacao .= '<li><a href="javascript:;">...</a></li>';
            $paginationinacao .= '<li><a href="?page=vehicles&pag='.$penultima.'">'.$penultima.'</a></li>';
            $paginationinacao .= '<li><a href="?page=vehicles&pag='.$ultima_pag.'">'.$ultima_pag.'</a></li>';
          }
          
          elseif($pagination > (2 * $adjacentes) && $pagination < $ultima_pag - 3)
          {
            $paginationinacao .= '<li><a href="?page=vehicles&pag=1">1</a></li>';        
            $paginationinacao .= '<li><a href="javascript:;">...</a></li>';
            for ($i = $pagination-$adjacentes; $i<= $pagination + $adjacentes; $i++)
            {
              if ($i == $pagination)
              {
                $paginationinacao .= '<li><a class="atual" href="?page=vehicles&pag='.$i.'">'.$i.'</a></li>';        
              } else {
                $paginationinacao .= '<li><a href="?page=vehicles&pag='.$i.'">'.$i.'</a></li>';  
              }
            }
            $paginationinacao .= '<li><a href="javascript:;">...</a></li>';
            $paginationinacao .= '<li><a href="?page=vehicles&pag='.$penultima.'">'.$penultima.'</a></li>';
            $paginationinacao .= '<li><a href="?page=vehicles&pag='.$ultima_pag.'">'.$ultima_pag.'</a></li>';
          }
          else {
            $paginationinacao .= '<li><a href="?page=vehicles&pag=1">1</a></li>';        
            $paginationinacao .= '<li><a href="?page=vehicles&pag=1">2</a></li>';  
            for ($i = $ultima_pag - (2 + (2 * $adjacentes)); $i <= $ultima_pag; $i++)
            {
              if ($i == $pagination)
              {
                $paginationinacao .= '<li><a class="atual" href="?page=vehicles&pag='.$i.'">'.$i.'</a></li>';        
              } else {
                $paginationinacao .= '<li><a href="?page=vehicles&pag='.$i.'">'.$i.'</a></li>';  
              }
            }
          }
        }
        if ($prox <= $ultima_pag && $ultima_pag > 2)
        {
          $paginationinacao .= '<li><a href="?page=vehicles&pag='.$prox.'"><i class="fa fa-arrow-right"></i></a></li>';
        }
        
      ?>

      </DIV><!-- /.box-body -->
      
    </DIV>
      
      <DIV CLASS="tfoot nav navbar-nav"> 
        <button class="btn btn-flat btn-danger navbar-left" data-control="submit-button" id="del-vehicles" DISABLED type="submit"><I CLASS="fa fa-trash-o"></I> Excluir Ítens Selecionados</button>        
      </DIV> 
        
      </form>
    
    <NAV>
      <UL CLASS="pagination">
        <?php echo @$paginationinacao  ?>
      </UL>
    </NAV><!--//.pagination-->
    
    
</DIV>  
</SECTION>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/vehicle.js"></script>