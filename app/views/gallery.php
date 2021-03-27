<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['gallery_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Galeria</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=gallery"><I CLASS="fa fa-picture-o"></I> Galeria</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container">
  <DIV CLASS="row">
  
    <DIV CLASS="col-lg-5 col-md-5 col-sm-12 col-xs-12">
      <form name="create-album" id="create-album" action="/app/modules/gallery/create_album.php" method="POST" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-reload="true" data-action="submit-ajax" data-form-reset="reset">
      
          <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
          <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
      
          <DIV CLASS="box box-primary">
            <DIV CLASS="box-header ">
              <STRONG>Criar Álbum</STRONG>
            </DIV>        
            <DIV CLASS="box-body">           
              <DIV CLASS="form-group">
                <LABEL FOR="album-nome">Nome do álbum</LABEL>
                <input type="text" class="form-control" id="album-nome" name="album-nome" maxlength="250" form="create-album" REQUIRED>   
              </DIV>
            </DIV><!--//.box-body-->
            <DIV CLASS="box-footer">        
                <button type="submit" class="btn btn-primary btn-flat">Criar Álbum</button>
            </DIV>
          </DIV><!--//.box--> 
          
        </form>
    </DIV> <!--//.col-->
    
    <DIV CLASS="col-lg-7 col-md-7 col-sm-12 col-xs-12">
    
       <DIV CLASS="callout callout-info">
        <H4><I CLASS="icon fa fa-info"></I> Importante!</H4>
        <P>Para saber mais sobre cada ferramenta do nosso sistema, criamos uma página de suporte com tutoriais e video aulas!</P>
        <a href="admin.php?page=suporte">Ir para a página de suporte</a>
      </DIV> 
        
  </DIV><!--//.col-->
     
  </DIV>  
</SECTION>


<DIV CLASS="space30"></DIV>

<SECTION CLASS="container" >
  <DIV CLASS="row">
  
    <div class="col-md-12">
        <H3><i class="fa fa-camera-retro"></i><STRONG>Álbuns</STRONG></H3>
        <DIV CLASS="line-divisory"></DIV>
    </div>
    
    
    
    <DIV ID="albuns" data-control="data-reload">
  
      <?php 
   
        $pag = ( empty($_GET['pag']) or !isset($_GET['pag'])  ) ? 1 : $_GET['pag'] ; 
        
        if( empty($_GET['limite']) == true or !isset($_GET['limite']) )
        {
            $limite = (!isset($_SESSION['limite-galeria']) ) ? '12' : $_SESSION['limite-galeria'] ;
        }
        else if( $_GET['limite'] == 'all' )
        {
            $inicio = 0;
            $limite = 99999;
        }
        else 
        {
            $limite = filterString($_GET['limite'], 'INT');
            $_SESSION['limite-galeria'] = $limite;
        }
                 
        if( $pag and $pag != '' )
        {
            $inicio = ($pag - 1) * $limite;
        }
        else
        {
            $inicio = 0;
        }
        
        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $albuns = $con->query("SELECT * FROM `albuns` ORDER BY `id` DESC LIMIT $inicio,$limite");
        $total_reg = $albuns->num_rows;
        
        $print_albuns = ''; 
        
        while($reg = $albuns->fetch_array())
        {
          
            if( empty($reg['image_capa']) or !file_exists($_SERVER['DOCUMENT_ROOT'].$reg['image_capa']) )
            {
                $msg_capa = '<i class="fa fa-camera" aria-hidden="true"></i> Escolher imagem de capa';
                $capa     = '/assets/images/not-found.jpg';
            }
            else 
            {
                $msg_capa = '<i class="fa fa-camera" aria-hidden="true"></i> Alterar imagem de capa';
                $capa     = $reg['image_capa'];
            }
          
            $print_albuns .= '<div CLASS="col-lg-3 col-md-3 col-sm-6 col-xs-12 margin-bottom">';
            $print_albuns .= '<div CLASS="container-albuns">';
            $print_albuns .= '<div CLASS="container-img-capa" STYLE="background-image:url('.$capa.');">';
            $print_albuns .= '<div CLASS="alter-capa"><a class="color-branco" href="?page=images_album&album='.base64_encode($reg['name']).'&id='.base64_encode($reg['id']).'">'.$msg_capa.'</a></div>'; 
            $print_albuns .= '</div>';
            $print_albuns .= '<div STYLE="padding:5px; line-height:15px !important;">'; 
            $print_albuns .= '<p><a href="?page=images_album&album='.base64_encode($reg['dir']).'&id='.base64_encode($reg['id']).'" class="text-darkgray link " title="Ver imagens"><strong>'.$reg['name'].'</strong></a></p>'; 
            $print_albuns .= '<p>'.CountImagesAlbum($reg['id']).'</p>'; 
            $print_albuns .= '<p><STRONG>Criado em:</STRONG> '.inverteData(substr($reg['date_create'], 0, 10)).'</p>';
            $print_albuns .= '</div>';
            $print_albuns .= '<a href="javascript:;" class="btn btn-flat btn-del-album animation-scale-1" title="Excluir álbum?" data-album-id="'.$reg['id'].'" data-control="del-album"><i class="fa fa-trash fa-2x text-red"></i></a>';
            $print_albuns .= '</div> '; 
            $print_albuns .= '</div><!--//.item-->';
        }
                
        if( !$albuns or $total_reg <= 0 )
        {
            echo '<div class="alert alert-info alert-dismissible">
                <h4><i class="icon fa fa-info"></i>Ops!</h4>
                Até o momento você não tem nenhum álbum criado.<br>
              </div>';
        }
        else
        {
            echo $print_albuns;
        }
        
        // paginação----------
        $busca_total = $con->query("SELECT COUNT(*) as `id` FROM `albuns`  ");
        $total = $busca_total->fetch_array();
        $total = $total['id'];

        $prox = $pag + 1;
        $ant = $pag - 1;
        $ultima_pag = ceil($total / $limite);
        $penultima = $ultima_pag - 1;  
        @$adjacentes = 2;
        
        if( $pag>1 )
        {
            $paginacao = '<li><a href="?page=gallery&pag='.$ant.'"><i class="fa fa-arrow-left"></i></a>';
        }
          
        if( $ultima_pag <= 5 )
        {
            for( $i=1; $i< $ultima_pag+1; $i++ )
            {
                if( $i == $pag )
                {
                    @$paginacao .= '<li><a class="atual" href="?page=gallery&pag='.$i.'">'.$i.'</a>';        
                }
                else 
                {
                    @$paginacao .= '<li><a href="?page=gallery&pag='.$i.'">'.$i.'</a>';  
                }
            }
        }
        
        if( $ultima_pag > 5 )
        {
            if( $pag < 1 + (2 * $adjacentes) )
            {
                for( $i=1; $i< 2 + (2 * $adjacentes); $i++)
                {
                    if( $i == $pag)
                    {
                        @$paginacao .= '<li><a class="atual" href="?page=gallery&pag='.$i.'">'.$i.'</a>';        
                    } 
                    else 
                    {
                        @$paginacao .= '<li><a href="?page=gallery&pag='.$i.'">'.$i.'</a>';  
                    }
                }
                $paginacao .= '<li><a href="javascript:;">...</a></li>';
                $paginacao .= '<li><a href="?page=gallery&pag='.$penultima.'">'.$penultima.'</a></li>';
                $paginacao .= '<li><a href="?page=gallery&pag='.$ultima_pag.'">'.$ultima_pag.'</a></li>';
                
            }
            elseif( $pag > (2 * $adjacentes) && $pag < $ultima_pag - 3 )
            {
                $paginacao .= '<li><a href="?page=gallery&pag=1">1</a></li>';        
                $paginacao .= '<li><a href="javascript:;">...</a></li>';
                for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++)
                {
                    if($i == $pag)
                    {
                        $paginacao .= '<li><a class="atual" href="?page=gallery&pag='.$i.'">'.$i.'</a></li>';        
                    } 
                    else 
                    {
                        $paginacao .= '<li><a href="?page=gallery&pag='.$i.'">'.$i.'</a></li>';  
                    }
                }
                $paginacao .= '<li><a href="javascript:;">...</a></li>';
                $paginacao .= '<li><a href="?page=gallery&pag='.$penultima.'">'.$penultima.'</a></li>';
                $paginacao .= '<li><a href="?page=gallery&pag='.$ultima_pag.'">'.$ultima_pag.'</a></li>';
            }
            else
            {
                $paginacao .= '<li><a href="?page=gallery&pag=1">1</a></li>';        
                $paginacao .= '<li><a href="?page=gallery&pag=1">2</a></li>';  
                for ( $i = $ultima_pag - (2 + (2 * $adjacentes)); $i <= $ultima_pag; $i++ )
                {
                    if( $i == $pag )
                    {
                        $paginacao .= '<li><a class="atual" href="?page=gallery&pag='.$i.'">'.$i.'</a></li>';        
                    } 
                    else 
                    {
                        $paginacao .= '<li><a href="?page=gallery&pag='.$i.'">'.$i.'</a></li>';  
                    }
                }
            }
        }
        
        if( $prox <= $ultima_pag && $ultima_pag > 2 )
        {
            $paginacao .= '<li><a href="?page=gallery&pag='.$prox.'"><i class="fa fa-arrow-right"></i></a></li>';
        }
        
      ?>
        
      <DIV CLASS="clearfix"></DIV>
        
       <NAV>
        <UL CLASS="pagination">
          <?php echo @$paginacao  ?>
        </UL>
      </NAV><!--//.pagination-->
      
      </DIV><!--//.albuns-->
    
  </DIV>
</SECTION>
<!--Jquery Confirm -->
<script type="text/javascript" src="/plugins/jQueryConfirm/jquery.confirm.min.js" DEFER ASYNC></script>
<script src="/app/javascript/generatePreviewsImages.js"></script>
<script src="/app/javascript/gallery.js"></script>