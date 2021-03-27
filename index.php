<?php require_once('header.php'); ?>

<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img class="img-responsive" src="/assets/images/slides1.png">
      <div class="carousel-caption">
        <p>Encontrar sua casa está muito mais fácil agora!</p>
      </div>
    </div>
    <div class="item">
      <img class="img-responsive" src="/assets/images/slides2.png">
      <div class="carousel-caption">
        <p>Nós o ajudamos a encontrar a melhor opção<br> para o que você precisa em apenas alguns clicks.</p>
      </div>
    </div>
    <div class="item">
      <img class="img-responsive" src="/assets/images/slides3.png">
      <div class="carousel-caption">
        <p>Nós não encontramos apenas grandes negócios. Nós os criamos!</p>
      </div>
    </div>
  </div>

  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
 
<div class="space-40 clearfix"></div>

 

<div class="container text-center">
	<form id="search-form" action="/search.php" method="GET" enctype="APPLICATION/X-WWW-FORM-URLENCODED">
		<fieldset>
			<input name="search" type="search" placeholder="Oque você procura ?"/>
			<button class="btn-form" type="submit" value="Ok">BUSCAR</button>
		</fieldset>
	</form>

	<div class="space-40 clearfix"></div>

	<h1 class="text-uppercase"><b>imóveis em <span style="color: #ED3237;">destaque</span></b></h1>
	<hr>
	<div class="space-20"></div>
    </div>
 
<div class="space-40 clearfix"></div>
<form class="navbar-form navbar-right hidden" role="search">
                        <DIV CLASS="form-group">
                            <input type="text" class="form-control" data-control="search-filter"  placeholder="Buscar registros" style="width:400px">
                        </DIV>
                    </form>
<?php
$con_db = new config\connect_db();
$con = $con_db->connect();  

$properties = $con->query("SELECT id, status, ref, situation, finality, segment, type, value_total, hidden_value_total, value_monthly, hidden_value_monthly, address_state, address_city, address_street, address_number, address_neighborhood, cover_image, featured, visibility, date_post, user_post FROM properties WHERE featured = 'Y' AND visibility = 'Y' AND status IS NULL  ORDER BY id DESC LIMIT 6");
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
                $venda = '<p>Venda:<strong class="text-canzi"> R$ '.decimalMoeda( $reg['value_total']).' </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] === 'Y' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-canzi"> Não informado </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] === 'N' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-canzi"> R$ '.decimalMoeda( $reg['value_total']).' </strong></p>';
            }
            else if($reg['hidden_value_total'] === 'N' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-canzi"> Não informado </strong></p>';
            }
            else
            {
                $venda = '<p>Venda:<strong class="text-canzi"> Indefinido </strong></p>';
            }

            if($reg['hidden_value_monthly'] === 'Y' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> R$ '.decimalMoeda($reg['value_monthly']).' </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_monthly'] === 'Y' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:  <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_monthly'] === 'N' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> R$ '.decimalMoeda($reg['value_monthly']).' </strong></p>';
            }
            else if($reg['hidden_value_monthly'] === 'N' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> Não informado </strong></p>';
            }
            else
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> Indefinido </strong></p>';
            }

        }
        else if( $reg['finality'] === 'venda' )
        {
            $finality = 'venda';
            if($reg['hidden_value_total'] == 'Y' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-canzi"> R$ '.decimalMoeda( $reg['value_total']).' </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] == 'Y' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:  <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_total'] == 'N' and !empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-canzi"> R$ '.decimalMoeda( $reg['value_total']).' </strong></p>';
            }
            else if($reg['hidden_value_total'] == 'N' and empty( $reg['value_total']))
            {
                $venda = '<p>Venda:<strong class="text-canzi"> Não informado </strong></p>';
            }
            else
            {
                $venda = '<p>Venda:<strong class="text-canzi"> Indefinido </strong></p>';
            }

            $aluguel = '';
        }
        else if( $reg['finality'] === 'aluguel' or $reg['finality'] === 'temporada' or $reg['finality'] === 'arrendamento')
        {
            $finality = 'aluguel';
            if($reg['hidden_value_monthly'] == 'Y' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> R$ '.decimalMoeda($reg['value_monthly']).' </strong> - Sob consulta</p>';
            }
            else if($reg['hidden_value_monthly'] == 'Y' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> Não informado </strong> - <small>Sob consulta</small></p>';
            }
            else if($reg['hidden_value_monthly'] == 'N' and !empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> R$ '.decimalMoeda($reg['value_monthly']).' </strong></p>';
            }
            else if($reg['hidden_value_monthly'] == 'N' and empty($reg['value_monthly']))
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> Não informado </strong></p>';
            }
            else
            {
                $aluguel = '<p>Aluguel:<strong class="text-canzi"> Indefinido </strong></p>';
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
        if($reg['visibility'] == 'Y')
        {
            $visibility= '<a class="view text-violet" data-action="hidden" href="javascript:;" title="Visível no meu site/tornar invisível" data-tid="'.$reg['id'].'"><i class="fa fa-eye"></i></a>';
        }
        else if($reg['visibility'] == 'N')
        {
            $visibility = '<a class="view text-violet" data-action="show" href="javascript:;" title="Oculto no meu site/tornar visível" data-tid="'.$reg['id'].'"><i class="fa fa-eye-slash"></i></a>';
        }
        else
        {
            $visibility = '';
        }

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

    }
    
    echo $item;

}
else
{
    echo '';
}

?>
</div>
	<div class="space-20 clearfix"></div>
	<div class=" text-center">
		<a class="btn btn-primary" href="/imoveis">Ver todos</a>
	</div>
	<div class="space-40 clearfix"></div>

</div>

<div class="space-40 clearfix"></div>

<script>
	$(function(){
    var elemSearch = '[data-control="elem-filter"]';
    $(document).on('keyup', '[data-control="search-filter"]', function(){
        var stringPesquisa = $(this).val();
        $(elemSearch).find('.animated').removeClass('animated');
        if( stringPesquisa !== ""){
            $(elemSearch).hide();
            $(elemSearch+':containsi('+stringPesquisa+')').show();
        } 
        else{
            $(elemSearch+':containsi('+stringPesquisa+')').show();
        } 
        
    });  
});
$.extend($.expr[':'], {
  'containsi': function(elem, i, match, array)
  {
    return (elem.textContent || elem.innerText || '').toLowerCase()
    .indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});
</script>

<?php require_once('footer.php'); ?>