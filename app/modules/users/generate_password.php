<?php
/**
* Função para gerar senhas aleatórias
*
* @param integer $tamanho Tamanho da senha a ser gerada
* @param boolean $maiusculas Se terá letras maiúsculas
* @param boolean $numeros Se terá números
* @param boolean $simbolos Se terá símbolos
*
* @return string A senha gerada
*/
function generatePassword($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = true)
{
    $lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '1234567890';
    $simb = '!@#$%*-';
  
  // a var $retorno é uma gambiara para ter certeza que terá 1 caracter de cada categoria. kkkkkkkkkkkkk
    $retorno = substr(str_shuffle($lmin),0,1).substr(str_shuffle($lmai),0,1).substr(str_shuffle($num),0,1).substr(str_shuffle($simb),0,1);
    $caracteres = '';
  
    $caracteres .= $lmin;
    if($maiusculas) $caracteres .= $lmai;
    if($numeros) $caracteres .= $num;
    if($simbolos) $caracteres .= $simb;
    $len = strlen($caracteres); 
  
    for ($n = 5; $n <= $tamanho; $n++) 
    {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand-1];
    }
  
    return str_shuffle($retorno);
  
}

echo generatePassword(8, true, true, true);