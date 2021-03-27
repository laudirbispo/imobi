<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' )
{
?>
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Usuários cadastrados</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/users"><I CLASS="fa fa-users"></I> Usuários</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">    
        <div class="col-md-12">
            <a href="/app/admin/new_user" class="btn btn-success btn-flat pull-left"><I CLASS="fa fa-user-plus"></I> <STRONG>NOVO USUÁRIO</STRONG></a>
        
            <DIV CLASS="form-group pull-right">
                <input type="text" class="form-control" data-control="search-filter" placeholder="Pesquisar usuários" style="width:250px">
            </DIV>
                   
        </div>
     
        <div class="clearfix space-20"></div>
    
        <DIV CLASS="">
                <?php
                $con_db = new config\connect_db();
                $con = $con_db->connect();
    
                $select_users = $con->query("SELECT su.id, su.login, su.type, su.active, su.date_register, up.user_name, up.user_profile_photo FROM sec_users su LEFT JOIN user_profile up ON (su.id = up.user_id) ORDER BY su.id DESC");
                $num_rows = $select_users->num_rows;
                
                $card_user = ''; 
                
                while( $reg = $select_users->fetch_assoc() )
                {
                    if( $reg['type'] == 'suporte') {continue;}
                    
                    if( $reg['type'] == 'suporte' or $reg['type'] == 'administrador')
                    {
                        $edit_perms_action = '';
                    }
                    else
                    {
                        $edit_perms_action = '<li><a HREF="/app/admin/user_edit_perms/'.base64_encode($reg['id']).'" ><I CLASS="fa fa-address-card text-blue"></I> Definir permissões</a></li>';
                    }
                    
                    if (empty($reg['user_profile_photo']) || null === $reg['user_profile_photo'])
                    {
                        $profile_photo = SUBDOMAIN_IMGS.'/app/images/default-user.png';
                    }
                    else
                    {
                       $profile_photo = (fileRemoteExist(SUBDOMAIN_IMGS.$reg['user_profile_photo']) === true) ? SUBDOMAIN_IMGS.$reg['user_profile_photo'] : SUBDOMAIN_IMGS.'/defaults/default-user.png'; 
                    }
                    
                    $user_name = (empty($reg['user_name'])) ? $reg['login'] : $reg['user_name'] ;
                    if($reg['active'] == 'Y')
                    {
                        $situation_icon = '<SPAN CLASS="label label-success flat" data-control="user-state">Ativo</SPAN>';
                        $situation_action = '<a HREF="javascript:;" data-control="user-bloq" data-user-id="'.$reg['id'].'" data-action="user-lock"><I CLASS="fa fa-lock text-red"></I> Bloquear este usuário</a>';
                    }
                    else
                    {
                        $situation_icon = '<SPAN CLASS="label label-danger flat" data-control="user-state">Inativo</SPAN>';
                        $situation_action = '<a HREF="javascript:;" data-control="user-bloq" data-user-id="'.$reg['id'].'" data-action="user-unlock"><I CLASS="fa fa-unlock text-green"></I> Desbloquear este usuário</a>';
                    }
                                     
                    $card_user .= '<div class="col-md-3 col-sm-4 col-xs-12" data-control="elem-filter">';
                    $card_user .= '<div class="box box-widget widget-user-2">';
                    $card_user .= '<div class="widget-user-header bg-gradient-3 text-white">';
                    $card_user .= '<div class="widget-user-image">';
                    $card_user .= '<img class="img-circle" src="'.$profile_photo.'" alt="Imagem de perfil">';
                    $card_user .= '</div>';
                    $card_user .= '<h5 class="widget-user-username">'.$user_name.'</h5>';
                    $card_user .= '<p class="widget-user-desc text-uppercase"><small>'.$reg['type'].'</small></p>';
                    $card_user .= '</div>';
                    $card_user .= '<div class="box-footer">';
                    $card_user .=  $situation_icon;
                    $card_user .= '<div class="btn-group pull-right">';
                    $card_user .= '<a role="button" class="dropdown-toggle text-mediumgray" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cogs"></i></a>';
                    $card_user .= '<ul class="dropdown-menu pull-right" role="menu">';
                    $card_user .= '<li>';
                    $card_user .= $situation_action ;
                    $card_user .= '</li>';
                    $card_user .= '<li>';
                    $card_user .= '<a HREF="/app/admin/admin_redefine_password/'.base64_encode($reg['id']).'" ><I CLASS="fa fa-key text-orange"></I> Redefinir senha</a>';
                    $card_user .= '</li>';
                    $card_user .= $edit_perms_action;
                    $card_user .= '<li class="divider"></li>';
                    $card_user .= '<li>';
                    $card_user .= '<a HREF="javascript:;" role="BUTTON" data-function="del-user" data-user-id="'.$reg['id'].'"><I CLASS="fa fa-trash text-red"></I> Deletar usuário</a>';
                    $card_user .= '</li>';
                    $card_user .= '</ul>';
                    $card_user .= '</div>';  
                    $card_user .= '</div>';
                    $card_user .= '</div>';
                    $card_user .= '</div>';
                    
                }
    
                if( $select_users and $num_rows > 1 )
                {
                    echo $card_user;
                }
                else
                {
                    echo '<div class="callout callout-info">
                         <h4>Nenhum usuário cadastrado até o momento!</h4>
                         <p>Cadastre usuários para lhe ajudar nas tarefas de gerenciamento do seu Web Site ou Sistema.</p>
                         </div>';
                }

                ?>

            <DIV CLASS="clearfix space-30"></DIV>
          
        </DIV>

  </DIV>
</SECTION>
<DIV CLASS="clearfix space-30"></DIV>
<script src="/app/javascript/users.js"></script>
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