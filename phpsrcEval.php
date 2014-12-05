<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
function phpsrcEval($phphtmlcode){
	$buffer=1024;   // buffer de lecture de 1Ko
	$tmpFile= md5(uniqid(rand())).'.tmp.php';
	$tmpPath = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$tmpFile;
	dirExists("/tmp/");
	
	$tmpfilehandle = fopen($tmpPath,'w') or (error_log("Soit ecriture impossible dans ".$tmpPath));
	fputs($tmpfilehandle,$phphtmlcode);
	fclose($tmpfilehandle);
	
	if (function_exists('ob_get_contents')){	
        ob_start();
       	include('tmp/'.$tmpFile);
        $sEvaledSRC = ob_get_contents();
        ob_end_clean(); 
	}
	else{
		$evalPath = 'http://'.$_SERVER['HTTP_HOST'].'/tmp/'.$tmpFile;
		$evalh = fopen($evalPath, 'r+') or (die('lecture impossible de '.$evalPath));
		$sEvaledSRC='';
		if ($evalh){	
			while(!feof($evalh)) {
				$sEvaledSRC.=fgets($evalh);
			}
		}
	}	
	unlink($tmpPath);
	return $sEvaledSRC;
}
?>