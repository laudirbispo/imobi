<?php 
include_once 'header.php';
?>

<SECTION>
  <DIV CLASS="container">
     <div class="space-40 clearfix"></div> 
     <div class="space-20"></div>
	<h1 class="text-uppercase text-center"><b>PEsquisa</b></h1>
	<hr>
	<div class="space-40"></div>
    <!-- div da esquerda notícias-->
    <div CLASS="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-branco">
    <div class="row">
      <?php 
      $con_db = new config\connect_db();
$con = $con_db->connect();  
      
      
      if( empty($_GET['pag']) or !isset($_GET['page']))
      {
        $pag = '0';
      }
      else
      {
        $pag = filter_var($_GET['pag'], FILTER_SANITIZE_NUMBER_INT);
      }
      
      if(empty($_GET['limite']) == true or !isset($_GET['limite']))
      {
        $limite = '25';
      }
      else if ($_GET['limite'] == 'all')
      {
        $inicio = 0;
        $limite = 99999;
      }
      else 
      {
        $limite = filter_var($_GET['limite'], FILTER_SANITIZE_NUMBER_INT);
      }
      
      if ($pag and $pag != '')
      {
        $inicio = ($pag - 1) * $limite;
      }
      else
      {
        $inicio = 0;
      }
      
      
      if( empty($_GET['search']) or !isset($_GET['search']))
      {
        $where = '';
        $search = '';
      }
      else
      {
        $search = filter_var($_GET['search'], FILTER_SANITIZE_SPECIAL_CHARS);
        $where = "WHERE MATCH(`situation`, `finality`, `ref`, `segment`, `type`, `address_state`, `address_city`, `address_street`, `address_number`, `address_neighborhood`) AGAINST ('$search' IN BOOLEAN MODE) LIMIT $inicio,$limite " ; 
      }
     
      // alterar aqui depois que tiver bstante noticias
      $properties = $con->query("SELECT * FROM `properties` $where ") or die (mysqli_error($con));
      
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
		$item .= '<p class="text-darkgray"><b><i class="fa fa-map-marker" style="color: #ED3237 !important; margin-right: 10px;"></i> '.$reg['address_street'].', '.$reg['address_number'].','.$reg['address_neighborhood'].', '.$reg['address_city'].'-'.$reg['address_state'].'</b></p>';
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
  echo('<div class="alert alert-danger" role="alert">Não encontramos nenhum registro para <b>'.$search.'.</b> Tente outra pesquisa.</div>');
    
}
      
   ?>
    </DIV> <!-- div da esquerda notícias--> 
   </div>
   
    </div>
  </DIV>
</SECTION>


<?php 
include_once 'footer.php';
?>
<!--****************************************************************** -->
</body></html>