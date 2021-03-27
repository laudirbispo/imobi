<?php
use config\connect_db;
use app\controls\errors;
use app\controls\perms;

$errors = new errors();
$user_perms   = new perms();

if( ($_SESSION['user_master_perms'] != 'administrador') )
{
    if($_SESSION['top_read'] != '1')
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>

<SECTION CLASS="col-md-12 no-padding">
  <H4 CLASS="text-darkgray"><STRONG>Estoque de carros</STRONG></H4>
  <OL CLASS="breadcrumb bg-white">
    <LI><a href="admin.php"><I CLASS="fa fa-home" ARIA-HIDDEN="true"></I></a></LI>
    <LI><a href="admin.php?page=top"><I CLASS="fa fa-car" ARIA-HIDDEN="true"></I> Estoque de carros</a></LI>
  </OL>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
    <DIV CLASS="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
         <H3 CLASS="box-title"> <STRONG>Alterar top list</STRONG></h3>
        </DIV>        
        <DIV CLASS="box-body">
            
          <DIV CLASS="form-group">
            <LABEL FOR="top-cantor">Nome Cantor</LABEL>
            <input type="text" class="form-control" id="top-cantor" name="top-cantor" form="top-music" REQUIRED>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="top-musica">Nome da Música </LABEL>
            <input type="text" class="form-control" id="top-musica" name="top-musica" form="top-music" REQUIRED>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="top-link">Link Externo</LABEL>
            <input type="url" class="form-control" id="top-link" name="top-link" form="top-music" REQUIRED>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL>Imagem </LABEL>
            <DIV CLASS="form-group">
              <LABEL FOR="top-img"><a class="btn btn-primary btn-flat"><I CLASS="fa fa-upload" ARIA-HIDDEN="true"></I> Selecionar Imagem</a></LABEL>
              <input type="file" id="top-img" name="top-img" form="top-music" style=" display:none">
            </DIV>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="top-position">Enviar imagens para o álbum</LABEL>
            <SELECT CLASS="form-control" ID="top-position" NAME="top-position" FORM="top-music" REQUIRED>
            <OPTION VALUE="0">Escolha a posição na lista</OPTION>
              <OPTION VALUE="1">1</OPTION>
              <OPTION VALUE="2">2</OPTION>
              <OPTION VALUE="3">3</OPTION>
              <OPTION VALUE="4">4</OPTION>
              <OPTION VALUE="5">5</OPTION>
              <OPTION VALUE="6">6</OPTION>
              <OPTION VALUE="7">7</OPTION>
              <OPTION VALUE="8">8</OPTION>
              <OPTION VALUE="9">9</OPTION>
              <OPTION VALUE="10">10</OPTION>
            </SELECT>
          </DIV>
    
        </DIV><!--//.box-body-->
        <DIV CLASS="box-footer">
          <form name="top-music" id="top-music" method="POST" enctype="MULTIPART/FORM-DATA">
            <button type="submit" class="btn btn-primary btn-flat pull-left" id="top-post" > Atualizar</button>
          </form>
        </DIV>
      </DIV><!--//.box-->
           
      
    </DIV> <!--//.col left-->
    
    <DIV CLASS="col-lg-8 col-md-8 col-sm-12 col-xs-12">
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header" STYLE="border-bottom:1px solid #F5F5F5">
          <H3 CLASS="box-title"><strong>Top list</strong></H3>
        </DIV>
        <!-- /.box-header -->
        <DIV CLASS="box-body no-padding">
      
          <?php
      
          $con_db = new config\connect_db();
          $con = $con_db->connect();
          
          $top = $con->query("SELECT * FROM `top_music` ORDER BY `id` ASC LIMIT 10");
          $total_reg = $top->num_rows;
          
          $print_top = '<TBODY>'; 
          $print_top  .= '<TABLE CLASS="table table-striped" ID="table-top"> '; 
          $print_top  .= '<THEAD> '; 
          $print_top  .= '  <TR  CLASS="bg-primary"> '; 
          $print_top  .= '    <TH STYLE="width:10% !important;">POSIÇÃO</TH> '; 
          $print_top  .= '    <TH STYLE="width:30% !important;">CANTOR</TH>'; 
          $print_top  .= '    <TH STYLE="width:30% !important;">MÚSIA</TH> '; 
          $print_top  .= '    <TH STYLE="width:30% !important;">LINK</TH> '; 
          $print_top  .= '  </TR> '; 
          $print_top  .= '</THEAD> '; 
          
          while($reg = $top->fetch_array())
          {          
              $print_top  .= '<TR>';
              $print_top  .= '  <TD CLASS="color-red"><strong>'.$reg['id'].' º</strong></TD>';
              $print_top  .= '  <TD>'.$reg['cantor'].'</TD> ';
              $print_top  .= '  <TD>'.$reg['musica'].'</TD>'; 
              $print_top  .= '  <TD>'.$reg['link'].'</TD>';           
              $print_top  .= '</TR>'; 
          }
          $print_top  .= '</TBODY>';
          $print_top  .= '</TABLE>';
          
          if( !$top )
          {
              echo '<div class="alert alert-warning alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>FALHA!</strong> O sistema falhou ao obter a lista.</div>';
          }
          else if( $total_reg <= 0 )
          {
              echo '<div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-info"></i> Opss!</h4>
                     Parece que sua lista está vazia! Que tal começarmos a criar o top 10?
                  </div>';
          }
          else 
          {
              echo $print_top;
          }
          ?>
      
        </DIV><!-- /.box-body -->
      </DIV>
    
    </DIV><!--//.col-->
     
  </DIV>  
</SECTION>