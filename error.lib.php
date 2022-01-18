<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
//error_reporting(E_ALL);

//error handler function
function AWScustomError($errno, $errmsg, $filename, $linenum, $vars){
  $errortype = array (
                1      => array('name' => 'Error', 'fatal' => 1),
                2      => array('name' => 'Warning', 'fatal' => 0),
                4      => array('name' => 'Parsing Error', 'fatal' => 1),
                8      => array('name' => 'Notice', 'fatal' => 0),
                16     => array('name' => 'Core Error', 'fatal' => 1),
                32     => array('name' => 'Core Warning', 'fatal' => 0),
                64     => array('name' => 'Compile Error', 'fatal' => 1),
                128    => array('name' => 'Compile Warning', 'fatal' => 0),
                256    => array('name' => 'User Error', 'fatal' => 1),
                512    => array('name' => 'User Warning', 'fatal' => 0),
                1024   => array('name' => 'User Notice', 'fatal' => 0),
                2048   => array('name' => 'Strict', 'fatal' => 0),
                4096   => array('name' => 'Catchable Fatal Error', 'fatal' => 1),
				8192   => array('name' => 'Deprecated', 'fatal' => 0),
				16384  => array('name' => 'User-generated warning message', 'fatal' => 0),
				30719  => array('name' => 'All', 'fatal' => 0)
                );
				
	$sOutput = '['.$errortype[$errno]['name'].'] '."\n".$errmsg."\n".$filename."\n".'line '.$linenum."\n".'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	
	if($errortype[$errno]['fatal'] == 1){
  		echo '<pre>'.$sOutput.'</pre>';  // echo only fatals
		$headers = 'From: '.DEF_CONTACT_FROM."\r\n".'Reply-To: '.DEF_CONTACT_FROM."\r\n".'X-Mailer: PHP/'.phpversion();
		mail('technique@couleur-citron.com', '['.$errortype[$errno]['name'].'] '.$_SERVER['HTTP_HOST'], $sOutput, $headers);
	}
	elseif (DEF_MODE_DEBUG==true){ // ou si mode debug
		if (preg_match('/([^\.]+)\.[^\.]+\.hephaistos.*/', $_SERVER['HTTP_HOST'], $matches) ==1){
			echo '<pre>'.$sOutput.'</pre>'; 
		}
	}
}

//set error handler
set_error_handler("AWScustomError");

//echo($test);

?>