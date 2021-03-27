<?php
namespace app\controls;

class errors
{
  
  
   public static function errorMessage($id_error)
   {
     
     if(empty($id_error))
     {
       return 'Erro não reconhecido!';
     }
     else
     {
     
       if ($id_error === '[0x0003]')
       {
         return 'Erro ao carregar as informações pessoais!<br> O usuário não está definido. Tente se conectar novamente!';
       }
       else if ($id_error === '[0x0004]')
       {
         return 'Erro ao carregar as informações pessoais!<br> O usuário não está definido. Tente se conectar novamente!';
       }
       else
       {
         return 'Erro não reconhecido![C]';
       }
       
     }//if empty
     
   }//function
   
   
   public function errorTokenForm()
   {
     return '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Ação bloqueada!</h4> Para a segurança do sistema, bloqueamos a tentativa de envio de formulário. <br>O servidor identificou que o formulário que você está tentando enviar, veio de um local desconhecido!<br> Para mais informações entre em contato com a equipe de suporte.</div>';
   }
   
   public function errorUserId()
   {
     return '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Ação bloqueada!</h4> Para a segurança do sistema, bloqueamos a tentativa de envio de formulário. <br>Não conseguimos relacionar o seu pedido ao usuário desejado.<br> Para mais informações entre em contato com a equipe de suporte.</div>';
   }
   
   public function fieldRequired()
   {
     return '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-exclamation-triangle"></i> Atenção!</h4> Preencha todos os campos marcados como obrigatórios.</div>';
   }
   
   public function invalideEmail()
   {
     return '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-exclamation-triangle text-yellow"></i> Atenção!</h4> Forneça um endereço de e-mail válido.</div>';
   }
   
   public function defaultQuery()
   {
     return '<div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
        Desculpe-nos, mas não conseguimos completar a ação solicitada!
      </div>';
   }
   
   public function userNotAuthorized()
   {
     return '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> AÇÃO NEGADA!</h4> Você não tem permissão para concluir está ação.<br> Consulte o adminstrador do sistema.</div>';
   }
  
   public function notReferenceId()
   {
     return '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-exclamation-triangle"></i> Ação Incopleta!</h4> Não encontramos um ID de referência para concluir está ação.</div>';
   }
 
  public function notObejectImage()
  {
    return '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Ação Incopleta!</h4> Seleciona uma imagem para continuar.</div>';
  }
  
  public function notAction()
  {
    return '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Ops!</h4> Não conseguimos entender seu pedido.</div>';
  }
  
}// class



?>