<?php 
    require_once('header.php'); 
    $url = new app\controls\securePage();
?>

<div class="bg-top">
	<div class="container">
		<ol class="breadcrumb">
		  <li><a href="/home">Início</a></li>
		  <li class="active">Imóveis</li>
		</ol>
	</div>
</div>

<div class="container text-center">
	<div class="space-20"></div>
	<h1 class="text-uppercase"><b>imóveis</b></h1>
	<hr>
	<div class="space-20"></div>
</div>
<div class="space-40 clearfix"></div>


    <div class="container hidden">
        <div CLASS="row">
            <nav CLASS="navbar ">
               <!-- Brand and toggle get grouped for better mobile display -->
                <div CLASS="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" DATA-TOGGLE="collapse" DATA-TARGET="#bs-example-navbar-collapse-2" aria-expanded="false"> <span CLASS="sr-only">Toggle navigation</span> <span CLASS="icon-bar"></span> <span CLASS="icon-bar"></span> <span CLASS="icon-bar"></span> </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div CLASS="collapse navbar-collapse " ID="bs-example-navbar-collapse-2">
                    <ul CLASS="nav navbar-nav">
                        <li><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=venda&pagination=0'); ?>"> Imóveis a venda</a></li>
                        <li><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=aluguel&pagination=0'); ?>"> Imóveis para alugar</a></li> 
                        <li><a href="<?php echo $url->getVars($_SERVER['REQUEST_URI'], '&select=all&pagination=0'); ?>">Todos</a></li>
                    </ul>
                    
                </div>
                <!-- /.navbar-collapse --> 
            </nav>
    </div>
</div>
<div class="space-20 clearfix"></div>
<?php
 $where = "WHERE visibility = 'Y' AND status IS NULL";
        
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
    $limit = 24;
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

if (!empty($_GET['select']) or isset($_GET['select']))
{
    $select = filterString($_GET['select'], 'CHAR');
    if ($select === 'venda')
    {
        $where = "WHERE finality = 'venda' AND visibility = 'Y' AND status IS NULL";
    }
    else if ($select === 'aluguel')
    {
        $where = "WHERE finality = 'aluguel' AND visibility = 'Y' AND status IS NULL";
    }
    else
    {
        $where = "WHERE visibility = 'Y' AND status IS NULL";
    }
}
else 
{
	 $where = "WHERE visibility = 'Y' AND status IS NULL";
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

$con_db = new config\connect_db();
$con = $con_db->connect();  

$properties = $con->query("SELECT id, status, ref, situation, finality, segment, type, value_total, hidden_value_total, value_monthly, hidden_value_monthly, address_state, address_city, address_street, address_number, address_neighborhood, cover_image, featured, visibility, date_post, user_post FROM properties $where $order LIMIT $start,$limit");
$rows = $properties->num_rows;

if( $properties and $rows > 0)
{

    $item = '';
    $item .= '<div class="container">';
    $item .= '<div class="row">';

    while( $reg = $properties->fetch_assoc() )
    {

        $cover_image = (!empty($reg['cover_image'])) ? SUBDOMAIN_IMGS.'/docs/properties/'.$reg['id'].'/small/'.$reg['cover_image'] : '/app/images/no-image.png' ;

        if($reg['finality'] === 'venda-aluguel')
        {
            $finality = 'venda ou aluguel';

            if($reg['hidden_value_total'] === 'Y' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda( $reg['value_total']).' </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] === 'Y' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] === 'N' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda( $reg['value_total']).' </strong></p>';
            }
            else if($reg['hidden_value_total'] === 'N' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong></p>';
            }
            else
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> Indefinido </strong></p>';
            }

            if($reg['hidden_value_monthly'] === 'Y' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($reg['value_monthly']).' </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_monthly'] === 'Y' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informado </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_monthly'] === 'N' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($reg['value_monthly']).' </strong></p>';
            }
            else if($reg['hidden_value_monthly'] === 'N' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informado </strong></p>';
            }
            else
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Indefinido </strong></p>';
            }

        }
        else if( $reg['finality'] === 'venda' )
        {
            $finality = 'venda';
            if($reg['hidden_value_total'] == 'Y' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda( $reg['value_total']).' </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] == 'Y' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda: <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] == 'N' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda( $reg['value_total']).' </strong></p>';
            }
            else if($reg['hidden_value_total'] == 'N' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong></p>';
            }
            else
            {
                $venda = '<p>Venda:<strong class="text-iguacu"> Indefinido </strong></p>';
            }

            $aluguel = '';
        }
        else if( $reg['finality'] === 'aluguel' or $reg['finality'] === 'temporada' or $reg['finality'] === 'arrendamento')
        {
            $finality = 'aluguel';
            if($reg['hidden_value_monthly'] == 'Y' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($reg['value_monthly']).' </strong> - Sob consulta</p>';
            }
            else if($reg['hidden_value_monthly'] == 'Y' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:  <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_monthly'] == 'N' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($reg['value_monthly']).' </strong></p>';
            }
            else if($reg['hidden_value_monthly'] == 'N' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informado </strong></p>';
            }
            else
            {
                $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Indefinido </strong></p>';
            }

            $venda = '';

        }
        else
        {
            $finality = $reg['finality'];
            $venda = '<p>Indefinido</p>';
            $aluguel = '<p>Indefinido</p>';
        }

        
        //---------------------------

        $status = ( !empty($reg['status']) ) ? '<span class="sale-status text-capitalize">'.$reg['status'].'</span>' : '';
		
        $item .= '<div class="col-md-4 col-sm-6 col-xs-12" data-control="elem-filter">';
		$item .= '<a href="view_properties/'.base64_encode($reg['id']).'">';
        $item .= '<div class="card sr-icons animated zoomIn">';
        $item .= $status;
        $item .= '<div class="card-image" data-background="'.$cover_image.'" style="background-image: url(/app/images/load.gif);">';
		$item .= '<div class="card-for">';
		$item .= $finality;
		$item .= '</div>';
        $item .= '</div>';		
        $item .= '<div class="card-content card-properties text-canzi">';
		$item .= '<h4 class="text-iguacu text-capitalize"><b>'.$reg['type'].' para '.$finality.'</b></h4>';
        $item .= '<p class="text-darkgray text-uppercase">'.$reg['segment'].'</p>';
        $item .= $venda;
        $item .= $aluguel;
		$item .= '<p><b>Ref.:</b> '.$reg['ref'].'</p>';
        $item .= '</div>';
        $item .= '</div>';
		$item .= '</a>';
        $item .= '</div><!-- //. Card item -->';
		

    }// end while

    $item .= '</div>';
    $item .= '</section>';
    
    echo $item;

}
else
{
   
    
}

// paginação----------
        $busca_total = $con->query("SELECT COUNT(*) as `id` FROM properties $where");
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
        
         <div class="clearfix space-30"></div>

        <nav class="col-md-12 text-center">
          <ul CLASS="pagination" id="pagination-mysql">
            <?php echo @$pages  ?>
          </ul>
        </nav><!--//.pagination-->
        
        </div>

       



    

<?php require_once('footer.php'); ?>