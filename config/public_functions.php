<?php

/**
 * FilterAccent para nomes de diretórios e outros
 * Retorna codificação utf-8 
 *
 */
 
function FilterAccent($string)
{
    $string = ltrim($string);
    $string = rtrim($string);
    $string = preg_replace( '/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//TRANSLIT', $string ) ); 
    $string = preg_replace("/\s+/","-", $string);   
    return $string;
}
/**
 * Function inverteData
 * Se a entrada for formato de banco de dados retorna dd/mm/yyyy
 * ao contrário retorna yyyy-mm-dd
 */
function inverteData($data)
{
    $data = substr($data, 0, 10);
  
    if(count(explode("/",$data)) > 1)
    {
        return implode("-",array_reverse(explode("/",$data)));
    }
    elseif(count(explode("-",$data)) > 1)
    {
        return implode("/",array_reverse(explode("-",$data)));
    }   
}


function returnTime($date_time)
{
    $time = substr($date_time, -8);
    return $time;
}

function moedaDecimal($value) 
{
    $source = array('.', ',');
    $replace = array('', '.');
    $valor = str_replace($source, $replace, $value); 
    return $valor; 
}

function decimalMoeda($valor)
{ 
    $valor = number_format($valor, 2, ',', '.');
    return $valor;
}

function isChecked($value)
{
    $check = ($value != '1') ? '' : 'checked';
    return $check;
}

function tempo_corrido($time) {

    $now = strtotime(date('m/d/Y H:i:s'));
    $time = strtotime($time);
    $diff = $now - $time;

    $seconds = $diff;
    $minutes = round($diff / 60);
    $hours = round($diff / 3600);
    $days = round($diff / 86400);
    $weeks = round($diff / 604800);
    $months = round($diff / 2419200);
    $years = round($diff / 29030400);

    if ($seconds <= 60) return"agora há pouco";
    else if ($minutes <= 60) return $minutes==1 ?'1 min atrás':$minutes.' min atrás';
    else if ($hours <= 24) return $hours==1 ?'1 horas atrás':$hours.' horas atrás';
    else if ($days <= 7) return $days==1 ?'1 dia atras':$days.' dias atrás';
    else if ($weeks <= 4) return $weeks==1 ?'1 semana atrás':$weeks.' semanas atrás';
    else if ($months <= 12) return $months == 1 ?'1 mês atrás':$months.' meses atrás';
    else return $years == 1 ? 'um ano atrás':$years.' anos atrás';
 }

/**
 * Verifica se a data é válida
 * @param $date = a data a ser verificada
 * @param $format = formato da data a ser analizada
 * @return true se a data dor valida e false se a data não exisitir
 * 
 * @examples:
 *
 * var_dump(validateDate('2012-02-28 12:12:12')); # true
 * var_dump(validateDate('2012-02-28 12:12:12')); # true
 * var_dump(validateDate('2012-02-30 12:12:12')); # false
 * var_dump(validateDate('2012-02-28', 'Y-m-d')); # true
 * var_dump(validateDate('28/02/2012', 'd/m/Y')); # true
 * var_dump(validateDate('30/02/2012', 'd/m/Y')); # false
 * var_dump(validateDate('14:50', 'H:i')); # true
 * var_dump(validateDate('14:77', 'H:i')); # false
 * var_dump(validateDate(14, 'H')); # true
 * var_dump(validateDate('14', 'H')); # true
 *
 */
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function dataExtenso($date = null )
{
    $date = ($date === null) ? date('Y-m-d') : $date ;
    $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sabado');
    $diasemana_numero = date('w', strtotime($date));
    
    $mes = array('Jan' => 'Janeiro', 'Feb' => 'Fevereiro', 'Mar' => 'Marco', 'Apr' => 'Abril', 'May' => 'Maio', 'Jun' => 'Junho', 'Jul' => 'Julho', 'Aug' => 'Agosto', 'Nov' => 'Novembro', 'Sep' => 'Setembro', 'Oct' => 'Outubro', 'Dec' => 'Dezembro');
    $mes_number = date('M', strtotime($date));
	$dia_mes = date('d', strtotime($date));
   
    $ano = date('Y', strtotime($date));
    
    return $diasemana[$diasemana_numero].', '.$dia_mes.' de '.$mes[$mes_number].' de '. $ano;    
}
    
/**
 * Gera ID único
 * @example -> uniqueAlfa(8);
 */

function uniqueAlfa($length=16)
{
    $chars = "0123456789";
    $len = strlen($chars);
    $string = '';
    mt_srand(10000000*(double)microtime());
    for ($i = 0; $i < $length; $i++)
    {
       $string .= $chars[mt_rand(0,$len - 1)];
    }
    return $string;
}


function filterString($string, $type)
{
    $string = strip_tags($string);

    if($type === 'INT')
    {  
        $string = filter_var($string, FILTER_SANITIZE_STRING);
        return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
    }
    if($type === 'CHAR')
    {
        $string = filter_var($string, FILTER_SANITIZE_STRING);
        return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}

// gera lista de anos de 1960 até o ano atual
function listYears($ini, $fim)
{ 
  $data1 = '1960';
  $data2 = date('Y')+1;

  $year = '';
  while( $data2 >= $data1)
  {
    $year .= '<OPTION value="'.$data2.'">'.$data2.'</OPTION>';
    $data2--;
  }
  
  return $year; 
}


function cleanCpfCnpj($string)
{
    $string = trim($string);
    $string = str_replace(".", "", $string);
    $string = str_replace(",", "", $string);
    $string = str_replace("-", "", $string);
    $string = str_replace("/", "", $string);
    return $string;
}

/** Função para calcular o copyright
 * 
 * @param $startYear = inicio da contagem, se omitido o retorno será somente o ano atual
 * @return String = 'Ano inicial - ano atual
 *
 */
function auto_copyright($startYear = null) 
{
	$thisYear = date('Y');  
    if (!is_numeric($startYear)) {
		$year = $thisYear; 
	} else {
		$year = intval($startYear);
	}
	if ($year == $thisYear || $year > $thisYear) { 
		echo "&copy; $thisYear"; 
	} else {
		echo "&copy; $year&ndash;$thisYear";
	}   
 }


/**
 * Mask values
 *
 * @param $val = valor a mascarar
 * @param $mask = tipo da mascara 
 * @Exemplo de usuo = echo valToMask($cnpj,'##.###.###/####-##');
 * @Exemplo de usuo = echo valToMask($cpf,'###.###.###-##');
 * @Exemplo de usuo = echo valToMask($rg,'##.###.###.#');
 * @Exemplo de usuo = echo valToMask($cep,'#####-###');
 * @Exemplo de usuo = echo valToMask($data,'##/##/####');
 *
 */
function valToMask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++)
    {
        if($mask[$i] == '#')
        {
            if(isset($val[$k]))
            $maskared .= $val[$k++];
        }
        else
        {
            if(isset($mask[$i]))
            $maskared .= $mask[$i];
        }
    } 
 return $maskared;
}

/**
 * Converte string para url legivel
 *
 * @param $string = Texto  ser convertido.
 * @param $charset = Codificação de caracteres de saída.
 * @param creturn $string convertida.
 * @example - echo create_slug("This is a sample test")."<br>"; //returns 'This-is-a-sample-test'
 * @example - echo create_slug("L'été? est là &amp; &eacute;");// returns 'L-ete-est-la-e'
 *
 */

function create_slug($string, $charset = 'utf-8')
{
    // convert accented characters to entities
	$string = htmlentities($string, ENT_NOQUOTES, $charset, false); 
	// strip unwanted parts of entities to leave unaccented character
    $string = preg_replace('~&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);~', '\1', $string);
    $string = preg_replace('~&([A-za-z]{2})(?:lig);~', '\1', $string);
    // remove other entities
    $string = preg_replace('~&[^;]+;~', '', $string); 
    // replace spaces and illegal characters with hyphens
    return preg_replace('~[\s!*\'();:@&=+$,/?%#[\]]+~', '-', $string); 
}


/**
 * Verefica se determinado arquivo existe em um servidor HTTP.
 *
 * @param $filename = Caminho completo do arquivo -.
 * @preturn boolean .
 * @example - fileRemoteExist ('http://domain.com.br/images/example.png').
 * 
 */

function fileRemoteExist ($filename)
{       
    $file_headers = @get_headers($filename);

    if($file_headers[0] == 'HTTP/1.1 404 Not Found')
    {
         return false;
    } 
    else if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found')
    {
        return false;
    } 
    else 
    {
        return true;
    }

}




