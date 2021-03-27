<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' )
{
    if(!isset($_GET['actionid']) or empty($_GET['actionid']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum usuário identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else if(base64_decode($_GET['actionid']) == $_SESSION['user_id'])
    {
        echo '<div class="alert alert-danger">
              <h4><i class="icon fa fa-ban"></i> Ação bloqueada!</h4>
              Você não pode alterar suas próprias permissões!<br>
              </div>';
    }
    else
    {
        $actionid = filterString(base64_decode($_GET['actionid']), 'INT');

        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $user_perms = $con->prepare("SELECT su.type, su.login, up.perms, pro.user_name, pro.user_profile_photo FROM sec_users su LEFT JOIN user_perms up ON (up.user_id = su.id) LEFT JOIN user_profile pro ON (pro.user_id = su.id) WHERE su.id = ?");
        $user_perms->bind_param('i', $actionid);
        $user_perms->execute();
        $user_perms->store_result();
        $user_perms->bind_result($type, $login, $perms, $user_name, $user_profile_photo);
        $user_perms->fetch();
        $rows = $user_perms->affected_rows;
        $user_perms->free_result();
        $user_perms->close();
       
        if($user_perms and $rows > 0)
        {    
            $error = ($type == 'administrador' or $type == 'suporte') ? 'Você não tem permissão para executar está ação!' : '' ; 

            $perms = explode(',', $perms);
            foreach($perms as $value)
            {
                $p = $value;
                $$p = $p;                
            }
            
            $user_profile_photo = (empty($user_profile_photo)) ? SUBDOMAIN_IMGS.'/defaults/default-user.png' : SUBDOMAIN_IMGS.$user_profile_photo ;

?>
<SECTION CLASS="container-fluid">
    <div class=row>
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Permissões </STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/users"><I CLASS="fa fa-users"></I> Usuários</a></LI>
                <LI><a href="/app/admin/user_edit_perms/<?php echo base64_encode($actionid); ?>"><I CLASS="fa fa-address-card"></I> Editar permissões</a></LI>
                <LI><a href="/app/admin/user_edit_perms/<?php echo base64_encode($actionid); ?>"><I CLASS="fa fa-user"></I> <?php echo (empty($user_name)) ? $login : $user_name ; ?></a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="container-fluid">
  <DIV CLASS="row">
    
    <form action="/app/modules/users/user_update_perms.php" method="POST" id="form-update-perms" name="form-update-perms" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-action="submit-ajax" data-form-reset="noreset">

        <input type="HIDDEN" name="actionid" value="<?php echo @$actionid ?>">
        <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
        <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
        
        
        <div class="col-md-7 col-sm-7 col-xs-12">
                
            <div class="box box-solid">
                <div class="box-header">
                    <STRONG>Permissões</STRONG>
                </div>
                <div class="box-body no-padding">

                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th width="40%">Módulo</th>
                                <th><span title="Visualizar"><i class="fa fa-eye"></i></span></th>
                                <th><span title="Editar"><i class="fa fa-edit"></i></span></th>
                                <th><span title="Criar"><i class="fa fa-plus"></i></span></th>
                                <th><span title="Deletar"><i class="fa fa-trash"></i></span></th>
                                <th><span title="Configurar"><i class="fa fa-wrench"></i></span></th>
                            </tr>
                            <tr class="hidden">
                                <td>Notícias</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($news_view)) ? "checked value=$news_view" : 'value="news_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($news_edit)) ? "checked value=$news_edit" : 'value="news_edit"' ; ?>>
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($news_create)) ? "checked value=$news_create" : 'value="news_create"' ; ?> >   
                                </td>
                                <td>   
                                    <input type="checkbox" name="perms[]" <?php echo (isset($news_delete)) ? "checked value=$news_delete" : 'value="news_delete"' ; ?> >
                                </td>
                                <td>   
                                    <input type="checkbox" name="perms[]" <?php echo (isset($news_configure)) ? "checked value=$news_configure" : 'value="news_configure"' ; ?> >
                                </td>
                            </tr>
                            <tr class="hidden">
                                <td>Recados</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($scraps_view)) ? 'checked value="scraps_view"' : 'value="scraps_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($scraps_edit)) ? 'checked value="scraps_edit"' : 'value="scraps_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($scraps_create)) ? 'checked value="scraps_create"' : 'value="scraps_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($scraps_delete)) ? 'checked value="scraps_delete"' : 'value="scraps_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($scraps_configure)) ? 'checked value="scraps_configure"' : 'value="scraps_configure"' ; ?> >
                                </td>
                            </tr>
                            <tr class="hidden">
                                <td>Galeria</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($gallery_view)) ? 'checked value="gallery_view"' : 'value="gallery_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($gallery_edit)) ? 'checked value="gallery_edit"' : 'value="gallery_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($gallery_create)) ? 'checked value="gallery_create"' : 'value="gallery_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($gallery_delete)) ? 'checked value="gallery_delete"' : 'value="gallery_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($gallery_configure)) ? 'checked value="gallery_configure"' : 'value="gallery_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Top List</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($top_view)) ? 'checked value="top_view"' : 'value="top_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($top_edit)) ? 'checked value="top_edit"' : 'value="top_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($top_create)) ? 'checked value="top_create"' : 'value="top_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($top_delete)) ? 'checked value="top_delete"' : 'value="top_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($top_configure)) ? 'checked value="top_configure"' : 'value="top_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Publicidade</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($advertising_view)) ? 'checked value="advertising_view"' : 'value="advertising_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($advertising_edit)) ? 'checked value="advertising_edit"' : 'value="advertising_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($advertising_create)) ? 'checked value="advertising_create"' : 'value="advertising_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($advertising_delete)) ? 'checked value="advertising_delete"' : 'value="advertising_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($advertising_configure)) ? 'checked value="advertising_configure"' : 'value="advertising_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Eventos</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($events_view)) ? 'checked value="events_view"' : 'value="events_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($events_edit)) ? 'checked value="events_edit"' : 'value="events_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($events_create)) ? 'checked value="events_create"' : 'value="events_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($events_delete)) ? 'checked value="events_delete"' : 'value="events_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($events_configure)) ? 'checked value="events_configure"' : 'value="events_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Veículos</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($vehicles_view)) ? 'checked value="vehicles_view"' : 'value="vehicles_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($vehicles_edit)) ? 'checked value="vehicles_edit"' : 'value="vehicles_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($vehicles_create)) ? 'checked value="vehicles_create"' : 'value="vehicles_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($vehicles_delete)) ? 'checked value="vehicles_delete"' : 'value="vehicles_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($vehicles_configure)) ? 'checked value="vehicles_configure"' : 'value="vehicles_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Slides</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($slides_view)) ? 'checked value="slides_view"' : 'value="slides_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($slides_edit)) ? 'checked value="slides_edit"' : 'value="slides_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($slides_create)) ? 'checked value="slides_create"' : 'value="slides_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($slides_delete)) ? 'checked value="slides_delete"' : 'value="slides_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($slides_configure)) ? 'checked value="slides_configure"' : 'value="slides_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr>
                                <td>Clientes</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($clients_view)) ? 'checked value="clients_view"' : 'value="clients_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($clients_edit)) ? 'checked value="clients_edit"' : 'value="clients_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($clients_create)) ? 'checked value="clients_create"' : 'value="clients_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($clients_delete)) ? 'checked value="clients_delete"' : 'value="clients_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($clients_configure)) ? 'checked value="clients_configure"' : 'value="clients_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Produtos</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($products_view)) ? 'checked value="products_view"' : 'value="products_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($products_edit)) ? 'checked value="products_edit"' : 'value="products_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($products_create)) ? 'checked value="products_create"' : 'value="products_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($products_delete)) ? 'checked value="products_delete"' : 'value="products_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($products_configure)) ? 'checked value="products_configure"' : 'value="products_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Análise & Estatísticas</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($access_report_view)) ? 'checked value="access_report_view"' : 'value="access_report_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($access_report_edit)) ? 'checked value="access_report_edit"' : 'value="access_report_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($access_report_create)) ? 'checked value="access_report_create"' : 'value="access_report_create"' ; ?> disabled >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($access_report_delete)) ? 'checked value="access_report_delete"' : 'value="access_report_delete"' ; ?> disabled>
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($access_report_configure)) ? 'checked value="access_report_configure"' : 'value="access_report_configure"' ; ?> disabled>
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Promoções</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($promotions_view)) ? 'checked value="promotions_view"' : 'value="promotions_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($promotions_edit)) ? 'checked value="promotions_edit"' : 'value="promotions_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($promotions_create)) ? 'checked value="promotions_create"' : 'value="promotions_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($promotions_delete)) ? 'checked value="promotions_delete"' : 'value="promotions_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($promotions_configure)) ? 'checked value="promotions_configure"' : 'value="promotions_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Enquetes</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($survey_view)) ? 'checked value="survey_view"' : 'value="survey_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($survey_edit)) ? 'checked value="survey_edit"' : 'value="survey_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($survey_create)) ? 'checked value="survey_create"' : 'value="survey_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($survey_delete)) ? 'checked value="survey_delete"' : 'value="survey_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($survey_configure)) ? 'checked value="survey_configure"' : 'value="survey_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr class="hidden">
                                <td>Banners </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($banners_view)) ? 'checked value="banners_view"' : 'value="banners_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($banners_edit)) ? 'checked value="banners_edit"' : 'value="banners_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($banners_create)) ? 'checked value="banners_create"' : 'value="banners_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($banners_delete)) ? 'checked value="banners_delete"' : 'value="banners_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($banners_configure)) ? 'checked value="banners_configure"' : 'value="banners_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr>
                                <td>Imóveis</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($properties_view)) ? 'checked value="properties_view"' : 'value="properties_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($properties_edit)) ? 'checked value="properties_edit"' : 'value="properties_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($properties_create)) ? 'checked value="properties_create"' : 'value="properties_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($properties_delete)) ? 'checked value="properties_delete"' : 'value="properties_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($properties_configure)) ? 'checked value="properties_configure"' : 'value="properties_configure"' ; ?> >
                                </td>
                            </tr> 
                            <tr>
                                <td>Contratos e Recibos</td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($contracts_view)) ? 'checked value="contracts_view"' : 'value="contracts_view"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($contracts_edit)) ? 'checked value="contracts_edit"' : 'value="contracts_edit"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($contracts_create)) ? 'checked value="contracts_create"' : 'value="contracts_create"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($contracts_delete)) ? 'checked value="contracts_delete"' : 'value="contracts_delete"' ; ?> >
                                </td>
                                <td>
                                    <input type="checkbox" name="perms[]" <?php echo (isset($contracts_configure)) ? 'checked value="contracts_configure"' : 'value="contracts_configure"' ; ?> >
                                </td>
                            </tr>     
                        </tbody>
                    </table>

                </div>
            </div><!--//.box-->


        </div><!--//.col-left-->
        
        <div class="col-md-5 col-sm-5 col-xs-12">
                
            <div class="box box-solid">
                <div class="box-header">
                    <i class="fa fa-support"></i>
                    <STRONG>Ajuda & Suporte</STRONG>
                </div>
                <div class="box-body">
                    <dl>
                        <dt>Permissão de Visualização <i class="fa fa-eye"></i></dt>
                        <dd>Permite ao usuário apenas visualizar as páginas que contêm por exemplo a lista de produtos da sua loja.</dd>
                        <br>
                        <dt>Permissão de Edição <i class="fa fa-edit"></i></dt>
                        <dd>Permite ao usuário alterar as informações. Como por exemplo, alterar o nome ou valor de um produto após o mesmo já cadastrado no banco de dados.</dd>
                        <br>
                        <dt>Permissão de Criação <i class="fa fa-plus"></i></dt>
                        <dd>Permite ao usuário cadastrar por exemplo, um novo produto na loja.</dd>
                        <br>
                        <dt>Permissão de Exclusão <i class="fa fa-trash"></i></dt>
                        <dd>Permite ao usuário excluir itens cadastrados no banco de dados, como por exemplo remover por completo todas as informações, arquivos, imagens, e outros dados de um produto da loja.</dd>
                        <br>
                        <dt>Permissão de Configuração <i class="fa fa-wrench"></i></dt>
                        <dd>Permite ao usuário alterar as configurações dos módulos. <b>Existem configurações mais importantes que somente administradores possuem acesso.</b></dd>
                    </dl>
                </div>
            </div>
            
            <div class="callout callout-info">
                <h4>Nota</h4>
                <p>Para mais informações sobre o sistema e o funcionamentos dos módulos e permissões, acesse a página de <a href="/app/admin/support">Ajuda & Suporte</a>.</p>
            </div>
            
        </div><!--//.col-right-->
        
        <DIV CLASS="clearfix space-30"></DIV>
      
        <DIV CLASS="col-md-12">
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object img-circle" src="<?php echo $user_profile_photo ?>" alt="Imagem de perfil" width="64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading"><?php echo (empty($user_name)) ? $login : $user_name ; ?></h4>
                <div class="form-group">
                    <div class="checkbox">
                      <label>
                         <input type="checkbox" name="user_confirm" required> Este é o usuário que estou alterando as permissões
                      </label>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
               </div>
            </div>
            <DIV CLASS="clearfix "></DIV>
            <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-floppy-o"></i> Salvar alterações</button>
        </DIV>
        
    </form>
    
  </DIV>
</SECTION>

<DIV CLASS="clearfix space-30"></DIV>

<script src="/app/javascript/users.js"></script>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<?php
        }
        else
        {
            echo '<div class="alert alert-warning">
                 <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
                 Nenhum usuário identificado com essas credenciais.<br>
                 Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
                 </div>';    
        }//. Se o select encontrou o usuário
        
    }//.se existe $_GET['actionid']
    
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