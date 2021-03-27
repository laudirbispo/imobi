<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php');

function newsRight()
{
	$con_db = new DB();
	$con = $con_db->connect();
	$news = $con->query("SELECT `id`, `titulo`, `subtitulo` FROM `noticias` ORDER BY `id` DESC LIMIT 8, 10 ");

	$cont = 0;
	$print_news = '';

	while ($r_news = $news->fetch_array() )
	{
		if($cont === 0)
		{   
			$print_news .= '<div class="card card-line">';
			$print_news .= '<div class="card-content bg-white style-border">';
			$print_news .= '<div class="category">';
			$print_news .= '<a class="text-darkgray" href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">'.substr($r_news['titulo'], 0, 80).'...'.'</a>'; 
			$print_news .= '</div>';
			$print_news .= '<div class="text-lightgray">'.substr($r_news['subtitulo'], 0, 55).'...'.'</div>';
			$print_news .= '</div>';	
			$print_news .= '</div>';
			$cont++;
			continue;
		}
		if($cont === 1)
		{   
			$print_news .= '<div class="card card-line">';
			$print_news .= '<div class="card-content bg-white style-border">';
			$print_news .= '<div class="category">';
			$print_news .= '<a class="text-darkgray" href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">'.substr($r_news['titulo'], 0, 80).'...'.'</a>'; 
			$print_news .= '</div>';
			$print_news .= '<div class="text-lightgray">'.substr($r_news['subtitulo'], 0, 55).'...'.'</div>';
			$print_news .= '</div>';	
			$print_news .= '</div>';
			$cont++;
			continue;
		}
		if($cont === 2)
		{   
			$print_news .= '<div class="card no-margin">';
			$print_news .= '<div class="card-content bg-white style-border">';
			$print_news .= '<div class="category">';
			$print_news .= '<a class="text-darkgray" href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">'.substr($r_news['titulo'], 0, 80).'...'.'</a>'; 
			$print_news .= '</div>';
			$print_news .= '<div class="text-lightgray">'.substr($r_news['subtitulo'], 0, 55).'...'.'</div>';
			$print_news .= '</div>';	
			$print_news .= '</div>';
			$print_news .= '<div class="space-30 visible-sm visible-xs"></div>';
			$cont++;
			continue;
		}
	}
	if($news and $news->num_rows > 0)
	{
	  $ec =  $print_news;
	}
	else if ($news->num_rows <= 0)
	{
	   $ec = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="alert alert-danger" role="alert">Não há notícias cadastradas!</div></div>';
	}
	else
	{
	   $ec = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="alert alert-danger" role="alert">Falha ao buscar as últimas notícias!</div></div>';
	}

	$news->close();
	echo  $ec;
	flush();
	
}

function byCategory($category, $direct)
{
  
  $con_db = new DB();
  $con = $con_db->connect();

  $noticia = $con->query("SELECT `id`, `titulo`, `subtitulo`, `categoria`, `subcategoria`, `tags`, `capa`, `date_post` FROM `noticias` WHERE categoria='$category' order by id desc LIMIT 10; " );
  $rows = $noticia->num_rows;
  $cont = 0;
  $print_news = '';
 
  if($category == 'regionais'){
		$ad1 = 'h3';
		$ad2 = 'h4';
	    $ad3 = 'h5';
  }
  if($category == 'variedades'){
		$ad1 = 'h6';
		$ad2 = 'h7';
	    $ad3 = 'h8';
  }
  else if($category == 'esportes')
  {
	  $ad1 = 'h9';
	  $ad2 = 'h10';
	  $ad3 = 'h11';
  }
  else if($category == 'policial')
  {
	  $ad1 = 'h12';
	  $ad2 = 'h13';
	  $ad3 = 'h14';
  }
  else if($category == 'brasil')
  {
	  $ad1 = 'h15';
	  $ad2 = 'h16';
	  $ad3 = 'h17';
  }
  else if($category == 'falecimento')
  {
	  $ad1 = 'h18';
	  $ad2 = 'h19';
	  $ad3 = 'h20';
  }
  else if($category == 'mundo')
  {
	  $ad1 = 'h21';
	  $ad2 = 'h22';
	  $ad3 = 'h23';
  }
	
  while( $r_news = $noticia->fetch_array())
  {
	
    if($cont == '0')
	{   

		  $print_news .= '<div CLASS="col-lg-4 col-md-4 col-sm-6 col-xs-12">';
		  $print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		  $print_news .= '<div class="card">';
		  $print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		  $print_news .= '<div class="container-img medium" style="background-image:url('.$r_news['capa'].');">';
		  $print_news .= '<div class="card-caption text-white t26">';    
		  $print_news .= '<div class="category">';
		  $print_news .= $r_news['categoria'];    
		  $print_news .= '</div>';
		  $print_news .= substr($r_news['titulo'], 0, 85).'...'; 
		  $print_news .= '</div>';
		  $print_news .= '</div>';  
		  $print_news .= '</div>'; 
		  $print_news .= '</a>';
		  $print_news .= '</div>';	
		  $cont++;
		  
	}	
	else if($cont == '1')
	{   

		  $print_news .= '<div CLASS="col-lg-4 col-md-4 col-sm-6 col-xs-12">';
		  $print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		  $print_news .= '<div class="card">';
		  $print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		  $print_news .= '<div class="container-img medium" style="background-image:url('.$r_news['capa'].');">';
		  $print_news .= '<div class="card-caption text-white t26">';    
		  $print_news .= '<div class="category">';
		  $print_news .= $r_news['categoria'];    
		  $print_news .= '</div>';
		  $print_news .= substr($r_news['titulo'], 0, 85).'...'; 
		  $print_news .= '</div>';
		  $print_news .= '</div>';  
		  $print_news .= '</div>'; 
		  $print_news .= '</a>';
		  $print_news .= '</div>';
		  $print_news .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
		  $print_news .= '<div CLASS="advertising medium">';
		  $print_news .= '<span>'.$ad1.'</span>';
		  $print_news .= loadBanner($ad1, 'medium');
		  $print_news .= '</div>';
		  $print_news .= '</div>';
		  $cont++;
		  
	}
	else if($cont == '2')
	{   

		  $print_news .= '<div CLASS="col-lg-4 col-md-4 col-sm-6 col-xs-12">';
		  $print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		  $print_news .= '<div class="card">';
		  $print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		  $print_news .= '<div class="container-img medium" style="background-image:url('.$r_news['capa'].');">';
		  $print_news .= '<div class="card-caption text-white t26">';    
		  $print_news .= '<div class="category">';
		  $print_news .= $r_news['categoria'];    
		  $print_news .= '</div>';
		  $print_news .= substr($r_news['titulo'], 0, 85).'...'; 
		  $print_news .= '</div>';
		  $print_news .= '</div>';  
		  $print_news .= '</div>'; 
		  $print_news .= '</a>';
		  $print_news .= '</div>';	
		  $cont++;
		  
	}
	else if($cont == '3')
	{   

		  $print_news .= '<div CLASS="col-lg-4 col-md-4 col-sm-6 col-xs-12">';
		  $print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		  $print_news .= '<div class="card">';
		  $print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		  $print_news .= '<div class="container-img medium" style="background-image:url('.$r_news['capa'].');">';
		  $print_news .= '<div class="card-caption text-white t26">';    
		  $print_news .= '<div class="category">';
		  $print_news .= $r_news['categoria'];    
		  $print_news .= '</div>';
		  $print_news .= substr($r_news['titulo'], 0, 85).'...'; 
		  $print_news .= '</div>';
		  $print_news .= '</div>';  
		  $print_news .= '</div>'; 
		  $print_news .= '</a>';
		  $print_news .= '</div>';	
		  $print_news .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
		  $print_news .= '<div CLASS="advertising medium">';
		  $print_news .= '<span>'.$ad2.'</span>';
		  $print_news .= loadBanner($ad2, 'medium');
		  $print_news .= '</div>';
		  $print_news .= '</div>';
		  $cont++;
		  
	}
	else if($cont == '4')
	{   

		$print_news .= '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">';
		$print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		$print_news .= '<div class="card">';
		$print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		$print_news .= '<div class="container-img small1" style="background-image:url('.$r_news['capa'].');">';
		$print_news .= '</div>';
		$print_news .= '<div class="card-content bg-white text-darkgray">';
		$print_news .= '<div class="category ">';
		$print_news .= substr_replace($r_news['categoria'], (strlen($r_news['subcategoria']) > 20 ? '...' : ''), 20); 
		$print_news .= '</div>';
		$print_news .= substr($r_news['titulo'], 0, 55).'...';
		$print_news .= '</div>';	
		$print_news .= '</div>';
		$print_news .= '</a>';
		$print_news .= '</div>';
		$cont++;
		  
	}
	else if($cont == '5')
	{   

		$print_news .= '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">';
		$print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		$print_news .= '<div class="card">';
		$print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		$print_news .= '<div class="container-img small1" style="background-image:url('.$r_news['capa'].');">';
		$print_news .= '</div>';
		$print_news .= '<div class="card-content bg-white text-darkgray">';
		$print_news .= '<div class="category ">';
		$print_news .= substr_replace($r_news['categoria'], (strlen($r_news['subcategoria']) > 20 ? '...' : ''), 20); 
		$print_news .= '</div>';
		$print_news .= substr($r_news['titulo'], 0, 55).'...';
		$print_news .= '</div>';	
		$print_news .= '</div>';
		$print_news .= '</a>';
		$print_news .= '</div>';
		$cont++;
		  
	}
	else if($cont == '6')
	{   

		$print_news .= '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">';
		$print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		$print_news .= '<div class="card">';
		$print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		$print_news .= '<div class="container-img small1" style="background-image:url('.$r_news['capa'].');">';
		$print_news .= '</div>';
		$print_news .= '<div class="card-content bg-white text-darkgray">';
		$print_news .= '<div class="category ">';
		$print_news .= substr_replace($r_news['categoria'], (strlen($r_news['subcategoria']) > 20 ? '...' : ''), 20); 
		$print_news .= '</div>';
		$print_news .= substr($r_news['titulo'], 0, 55).'...';
		$print_news .= '</div>';	
		$print_news .= '</div>';
		$print_news .= '</a>';
		$print_news .= '</div>';	
		$cont++;
		  
	}
	else if($cont == '7')
	{   

		$print_news .= '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">';
		$print_news .= '<a href="noticia/'.$r_news['id'].'/'.create_slug($r_news['titulo']).'">';
		$print_news .= '<div class="card">';
		$print_news .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($r_news['date_post']).'</div>';
		$print_news .= '<div class="container-img small1" style="background-image:url('.$r_news['capa'].');">';
		$print_news .= '</div>';
		$print_news .= '<div class="card-content bg-white text-darkgray">';
		$print_news .= '<div class="category ">';
		$print_news .= substr_replace($r_news['categoria'], (strlen($r_news['subcategoria']) > 20 ? '...' : ''), 20); 
		$print_news .= '</div>';
		$print_news .= substr($r_news['titulo'], 0, 55).'...';
		$print_news .= '</div>';	
		$print_news .= '</div>';
		$print_news .= '</a>';
		$print_news .= '</div>';
		$print_news .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
		$print_news .= '<div CLASS="advertising medium">';
		$print_news .= '<span>'.$ad3.'</span>';
		$print_news .= loadBanner($ad3, 'medium');
		$print_news .= '</div>';
		$print_news .= '</div>';
		$cont++;
		  
	}
	else
	{
		continue;
	}
  
	  
  }
	if ($noticia and $rows <= 0)
	{
		return false;
	}
	else
	{
		return $print_news;
	}
  
}


//*******************************************************************

function maisLidas()
{
  $con_db = new DB();
  $con = $con_db->connect();
  
  $mais_lidas = $con->query("SELECT `id`, `titulo`, `subtitulo`, `categoria`, `subcategoria`, `tags`, `capa`, `date_post` FROM `noticias` WHERE `date_post` BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE() ORDER BY `views` DESC LIMIT 3");
  $rows = $mais_lidas->num_rows;
  $nt = '';
  $o = 1;
  $mais = '';
  while($reg = $mais_lidas->fetch_array())
  {

          $mais .= '<div CLASS="col-lg-4 col-md-4 col-sm-12 col-xs-12">';
		  $mais .= '<a href="noticia/'.$reg['id'].'/'.create_slug($reg['titulo']).'">';
		  $mais .= '<div class="card">';
	  	  $mais .= '<div class="tempo text-white"><I CLASS="fa fa-clock-o"></I> '.tempo_corrido($reg['date_post']).'</div>';
		  $mais .= '<div class="container-img small" style="background-image:url('.$reg['capa'].');">';
		  $mais .= '<div class="card-caption text-white t26">';    
		  $mais .= '<div class="category">';
		  $mais .= $reg['categoria'];    
		  $mais .= '</div>';
		  $mais .= $reg['titulo'];  
		  $mais .= '</div>';
		  $mais .= '</div>';  
		  $mais .= '</div>'; 
		  $mais .= '</a>';
		  $mais .= '</div>';	
    
   
     $o++;
  }
  
  $mais_lidas->close();
  
  if($mais_lidas and $rows > 0) 
  {
    echo $mais;
  }
  else if ($rows <= 0)
  {
    echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><li><div class="alert alert-danger" role="alert">Lista vazia!</div></li></div>';
  }
  else if (!$mais_lidas)
  {
    echo '<center>';
      echo '<img src="/assets/images/curl_off.png">';
      echo '<p class="clearfix text-danger"><strong>Isso é constrangedor!<br>Ocorreu um erro ao carregar o app.</strong></p>';
      echo '</center>';
  }
}
//*******************************************8

function mesmoAssunto($categoria, $id)
{
  $con_db = new DB();
  $con = $con_db->connect();
  
  $noticia = $con->query("SELECT `id`, `titulo`, `categoria`, `subcategoria`, `capa` FROM `noticias` WHERE `categoria` = '$categoria' AND `id` != '$id'  ORDER BY `id` DESC LIMIT 4 " );
  
  $nt = '';
  $nt .= '<DIV CLASS="panel panel-default ">';
  $nt .= '<DIV CLASS="panel-body">';
  $nt .= '<DIV>';
  $nt .= '<H3 CLASS="headings-detalhes"><STRONG>DO MESMO ASSUNTO</STRONG></H3>';
  $nt .= '</DIV>';
  $nt .= ' <BR>';
  
  while($reg = $noticia->fetch_array())
  {
    
    if(empty($reg['capa']))
    {
        $nt .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
		$nt .= '<a href="/noticia/'.$reg['id'].'/'.create_slug($reg['titulo']).'">';
		$nt .= '<div class="card">';
		$nt .= '<div class="card-content bg-lightgray text-darkgray">';
		$nt .= substr($reg['titulo'], 0, 60).'...'; 
		$nt .= '</div>';	
		$nt .= '</div>';
		$nt .= '</a>';
		$nt .= '</div>';
    }
    else 
    {
        $nt .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">';
		$nt .= '<a href="/noticia/'.$reg['id'].'/'.create_slug($reg['titulo']).'">';
		$nt .= '<div class="card">';
		$nt .= '<div class="container-img small1" style="background-image:url('.$reg['capa'].');">';
		$nt .= '</div>';
		$nt .= '<div class="card-content bg-lightgray text-darkgray">';
		$nt .= substr($reg['titulo'], 0, 60).'...'; 
		$nt .= '</div>';	
		$nt .= '</div>';
		$nt .= '</a>';
		$nt .= '</div>';
    }
   
  }
  
  $nt .= '</DIV>';
  $nt .= '</DIV>';
  
  if(!$noticia)
  {
    $retorno = '';
  }
  else if ($noticia->num_rows <= 0)
  {
    $retorno = '';
  }
  else
  {
    $retorno = $nt;
  }
  
  $noticia->close();
  echo $retorno;
  
}
//***********************************************

function vejaMaisNoticias()
{
  $con_db = new DB();
  $con = $con_db->connect();
  
  $noticia = $con->query("SELECT `id`, `titulo`, `categoria`, `subcategoria`, `capa` FROM `noticias` WHERE `date_post` BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ORDER BY RAND()  LIMIT 7 " );
  
  $nt = '';
  $nt .= '<DIV CLASS="panel panel-default ">';
  $nt .= '<DIV CLASS="panel-body">';
  $nt .= '<DIV>';
  $nt .= '<H3 CLASS="headings-detalhes"><STRONG>VEJA TAMBÉM</STRONG></H3>';
  $nt .= '</DIV>';
  $nt .= ' <BR>';
  
  while($reg = $noticia->fetch_array())
  {
    
    if(empty($reg['capa']))
    {
      $nt .= '<DIV CLASS="media item-noticia-small">';
      $nt .= '      <DIV CLASS=" col-md-12 col-sm-12 col-xs-12 padding-responsive">';
      $nt .= '       <a href="noticia.php?id='.$reg['id'].'">';
      $nt .= '          <H6 CLASS="media-heading text-capitalize"><STRONG>'.$reg['subcategoria'].'</STRONG></H6>';
      $nt .= '          <H5 CLASS="text-danger"><STRONG>'.$reg['titulo'].'</STRONG></H5>';
      $nt .= '        </a>';
      $nt .= '      </DIV>';
      $nt .= '    </DIV>';
    }
    else 
    {
      $nt .= '<DIV CLASS="media item-noticia-small">';
      $nt .= '<DIV CLASS="col-md-4 col-sm-4 col-xs-12 figure-noticia-home-small"  STYLE="background-image:url('.$reg['capa'].')"></DIV>';
      $nt .= '      <DIV CLASS=" col-md-8 col-sm-8 col-xs-12 padding-responsive">';
      $nt .= '       <a href="noticia.php?id='.$reg['id'].'">';
      $nt .= '          <H6 CLASS="media-heading  text-capitalize"><STRONG>'.$reg['subcategoria'].'</STRONG></H6>';
      $nt .= '          <H5 CLASS="text-danger"><STRONG>'.$reg['titulo'].'</STRONG></H5>';
      $nt .= '        </a>';
      $nt .= '      </DIV>';
      $nt .= '    </DIV>';
    }
   
  }
  
  $nt .= '</DIV>';
  $nt .= '</DIV>';
  
  if(!$noticia)
  {
    $retorno = '';
  }
  else if ($noticia->num_rows <= 0)
  {
    $retorno = '';
  }
  else
  {
    $retorno = $nt;
  }
  
  $noticia->close();
  echo $retorno;
  
}

//**************************************************************************************************

function getEmpregos()
{
  $con_db = new DB();
  $con = $con_db->connect();
   
  $noticia = $con->query("SELECT `id`, `titulo`, `categoria`, `subcategoria`, `capa` FROM `noticias` WHERE `categoria` = 'empregos' ORDER BY `id` DESC LIMIT 1");
  
  $nt = '';
  
  while($reg = $noticia->fetch_array())
  {
   
      if(empty($reg['capa']))
      {
        $capa = '';
      }
      else 
      {
        $capa = '<DIV CLASS="media-left"><a href="noticia.php?id='.$reg['id'].'"><IMG CLASS="media-object" SRC="'.$reg['capa'].'" height="80"></a></DIV>';
      }
      
      $nt .= '<DIV CLASS="media">';
      $nt .= $capa;
      $nt .= '    <DIV CLASS="media-body">';
      $nt .= '      <H6 CLASS="media-heading text-uppercase text-gray"><STRONG>'.$reg['subcategoria'].'</STRONG></H6>';
      $nt .= '      <h5><a href="noticia.php?id='.$reg['id'].'"><strong>'. substr_replace($reg['titulo'], (strlen($reg['titulo']) > 150 ? '...' : ''), 150).'</strong></a> </h5>';
      $nt .= '    </DIV>';
      $nt .= '  </DIV>';
   
  }
  
  if(!$noticia)
  {
    $retorno = '<div class="alert alert-danger" role="alert">Ocoreu uma falha ao listar as notícias!</div>';
  }
  else if ($noticia->num_rows < 0)
  {
    $retorno = '<div class="alert alert-danger" role="alert">Não há notícias cadastradas para esta categoria!</div>';
  }
  else
  {
    $retorno = $nt;
  }
  
  $noticia->close();
  echo $retorno;
  
}




