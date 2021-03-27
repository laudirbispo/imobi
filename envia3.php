<?php
// Tamanho do display do DEBUG
ini_set('xdebug.var_display_max_depth', '10000');
ini_set('xdebug.var_display_max_children', '1000000');
ini_set('xdebug.var_display_max_data', '2000000');

require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';

$mail = new PHPMailer;

// resgatando os dados passados pelo form

$nomeusuario = htmlspecialchars(trim($_POST['nome']));
$emailusuario = htmlspecialchars(trim($_POST['email']));
$telefone = htmlspecialchars(trim($_POST['telefone']));
$mensagem = htmlspecialchars(trim($_POST['mensagem']));
$assunto = 'Proposta enviada através do site.';
$link = htmlspecialchars(trim($_POST['link']));
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
//$mail->SMTPDebug = 2;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.bohnimoveisrealeza.com.br';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'noreply@bohnimoveisrealeza.com.br';                 // SMTP username
$mail->Password = 'Bohn123#';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to
$mail->CharSet = 'UTF-8';

$mail->setFrom('noreply@bohnimoveisrealeza.com.br', 'E-mail');
$mail->addAddress('noreply@bohnimoveisrealeza.com.br');     // Add a recipient
$mail->addAddress('bolinhabohn@hotmail.com');
$mail->addAddress('bolinhabohn@gmail.com');

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $assunto;
$html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/proposta.html", true);
$html = str_replace('{MENSAGEM}', $mensagem, $html);
$html = str_replace('{NOME}', $nomeusuario, $html);
$html = str_replace('{EMAIL}', $emailusuario, $html);
$html = str_replace('{FONE}', $telefone, $html);
$html = str_replace('{LINK}', $link, $html);
//$html = sprintf($html, $mensagem, $nomeusuario, $emailusuario, $telefone,$telefone);

$mail->Body = $html;
//$mail->Body .= "<p><strong>E-mail enviado por:</strong> $nomeusuario</p><br>";
//$mail->Body .= "<p><strong>E-mail:</strong> $emailusuario</p><br>";
//$mail->Body .= "<p><strong>Telefone:</strong> $telefone</p><br>";
//$mail->Body .= "<p><strong>Mensagem:</strong><br> $mensagem<p>";

if(!$mail->send()) {
    echo '<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Ops, sua mensagem não foi enviada, tente novamente.</strong></div>';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo '<div class="alert alert-success alert-dismissible" role="alert"><strong>Sucesso! O e-mail foi enviado, logo entramos em contato.</strong> </div>';
}