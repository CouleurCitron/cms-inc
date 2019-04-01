<?php


function setNextExecutionTimeForScript($indexScript)
{ 
	global  $scripts, $a, $m, $j, $h, $min;
	
	$aNow = date("Y");
	$mNow = date("m");
	$jNow = date("d");
	$hNow = date("H");
	$minNow = date("i")+1;
	
	$a = $aNow;
	$m = $mNow - 1;
	
	
									

	while(prochainMois($indexScript) != -1)			/* on parcourt tous les mois de l'intervalle demandé */
	{							/* jusqu'à trouver une réponse convanable */
		if ($m != $mNow || $a != $aNow)			/*si ce n'est pas ce mois ci */
		{
			$j = 0;
			if (prochainJour($indexScript) == -1)	/* le premier jour trouvé sera le bon. */
			{					/*  -1 si l'intersection entre jour de semaine */
								/* et jour du mois est nulle */
				continue;			/* ...auquel cas on passe au mois suivant */
			}else{					/* s'il y a un jour */
				$h=-1;
				prochainHeure($indexScript);	/* la première heure et la première minute conviendront*/
				$min = -1;
				prochainMinute($indexScript);
				return mktime($h, $min, 0, $m, $j, $a);
			}
		}else{						/* c'est ce mois ci */
			$j = $jNow-1;					
			while(prochainJour($indexScript) != -1)	/* on cherche un jour à partir d'aujourd'hui compris */
			{
				if ($j > $jNow)			/* si ce n'est pas aujourd'hui */
				{				/* on prend les premiers résultats */
					$h=-1;
					prochainHeure($indexScript);
					$min = -1;
					prochainMinute($indexScript);
					return mktime($h, $min, 0, $m, $j, $a);
				}
				if ($j == $jNow)		/* même algo pour les heures et les minutes */
				{
					$h = $hNow - 1;
					while(prochainHeure($indexScript) != -1)
					{
						if ($h > $hNow)
						{
							$min = -1;
							prochainMinute($indexScript);
							return mktime($h, $min, 0, $m, $j, $a);
						}
						if ($h == $hNow)
						{
							$min = $minNow - 1;
							while(prochainMinute($indexScript) != -1)
							{ 
								
								if ($min >= $minNow) { 
								 	
									//echo "now ". date('Y-m-d H:i:s', mktime($h, $minNow, 0, $m, $j, $a))."<br />";
									//echo "next ".date('Y-m-d H:i:s',  mktime($h, $min, 0, $m, $j, $a))."<br />";
									//echo "lastdate ". $scripts[$indexScript]['lastExecution']."<br />";
									//echo "nextdate ". $scripts[$indexScript]['nextExecution']."<br />";
										
										
									if (mktime($h, $minNow, 0, $m, $j, $a) >= $scripts[$indexScript]['nextExecution'] || $scripts[$indexScript]['nextExecution']  == '') {
									 
									}
									else {
										
									}
									return mktime($h, $min, 0, $m, $j, $a); 
									//  
									
								}  
								
								/* si c'est maintenant, on l'éxécute directement */
								/*if ($min == $minNow)
								{ 
									fopen($scripts[$indexScript]['URLScript'], 'r');
								}*/
								
								/*if ($min <= $minNow)
								{ 
									fopen($scripts[$indexScript]['URLScript'], 'r');
								}*/
								
								
							}
						}						
					}
				}
			}
		}
	}
}


function parseFormat($min, $max, $intervalle)
{
	$retour = Array();
	
	if ($intervalle == '*')
	{
		for($i=$min; $i<=$max; $i++) $retour[$i] = TRUE;
		
		return $retour;
		
	}else{
		for($i=$min; $i<=$max; $i++) $retour[$i] = FALSE;
	}
	
	$intervalle = explode(',', $intervalle);
	foreach ($intervalle as $val)
	{
		$val = explode('-', $val);
		if (isset($val[0]) && isset($val[1]))
		{
			if ($val[0] <= $val[1])
			{
				for($i=$val[0]; $i<=$val[1]; $i++) $retour[$i] = TRUE;	/* ex : 9-12 = 9, 10, 11, 12 */
			}else{
				for($i=$val[0]; $i<=$max; $i++) $retour[$i] = TRUE;	/* ex : 10-4 = 10, 11, 12... */
				for($i=$min; $i<=$val[1]; $i++) $retour[$i] = TRUE;	/* ...et 1, 2, 3, 4 */
			}
		}else{
			$retour[$val[0]] = TRUE;
		}
	}
	return $retour;
}


function prochainMois($indexScript) { 
	global $a, $m, $scripts; 
	$valeurs = parseFormat(1, 12, $scripts[$indexScript]['mois']); 
	do { 
		$m++; 
		if ($m == 13) { $m=1; $a++; /*si on a fait le tour, on réessaye l'année suivante */ } 
	} while($valeurs[$m] != TRUE); 
}
 
function prochainJour($indexScript) { 

	global $a, $m, $j, $scripts; 
	$valeurs = parseFormat(1, 31, $scripts[$indexScript]['jour']); 
	$valeurSemaine = parseFormat(0, 6, $scripts[$indexScript]['jourSemaine']); 
	do { 
		$j++; /* si $j est égal au nombre de jours du mois + 1 */ 
		if ($j == date('t', mktime(0, 0, 0, $m, 1, $a))+1) { return -1; } 
		$js = date('w', mktime(0, 0, 0, $m, $j, $a)); 
	}while($valeurs[$j] != TRUE || $valeurSemaine[$js] != TRUE)  ;
}
 
function prochainHeure($indexScript) { 
	global $h, $scripts; 
	$valeurs = parseFormat(0, 23, $scripts[$indexScript]['heures']); 
	do { 
	$h++; if ($h == 24) { return -1; } 
	}while($valeurs[$h] != TRUE) ;
}
 
function prochainMinute($indexScript) { 
	global $min, $scripts; 
	$valeurs = parseFormat(0, 59, $scripts[$indexScript]['minutes']); 
	 
	do { 
		$min++; if ($min == 60) { return -1; } 
	} while($valeurs[$min] != TRUE); 
} 
 
function getNextExecutionTime() { global $scripts; foreach($scripts as $script) { if($script['prochain'] < $min || !(isset($min))) { $min = $script['prochain']; } } return $min; }
 
function getNextExecutionScript() { global $scripts; foreach($scripts as $index => $script) { if($script['prochain'] < $min || !(isset($min))) { $min = $script['prochain']; $minIndex = $index; } } return $minIndex; }

function buildScriptsNext()
{
	global $scripts;
	
	foreach($scripts as $index => $val)
	{
		$scripts[$index]['prochain'] = setNextExecutionTimeForScript($index); 
		 
		$aNow = date("Y");
		$mNow = date("m");
		$jNow = date("d");
		$hNow = date("H");
		$minNow = date("i")+1;
		if (mktime($hNow, $minNow, 0, $mNow, $jNow, $aNow) >= $scripts[$index]['nextExecution'] || $scripts[$index]['nextExecution']  == '') {
			$lastdate = date('Y-m-d H:i:s',$scripts[$index]['nextExecution']);
			$nextdate = date('Y-m-d H:i:s', $scripts[$index]['prochain']);
			//echo "attention, c'est passé, on lance<br />"; 
			//echo $scripts[$index]['URLScript'].'<br />'; 
			
			
			// execution du cron avec curl
			
			$str = $scripts[$index]['URLScript'];
			
			 
			?>
			<script src="/backoffice/cms/js/jquery-1.6.4.min.js" type="text/javascript"></script>
			<script type="text/javascript">
			function send_url()
			{  
				if(window.XMLHttpRequest) // FIREFOX
				xhr_object = new XMLHttpRequest();
				else if(window.ActiveXObject) // IE
				xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				else
				return(false); 
				xhr_object.open("GET", '<?php echo $str; ?>', true);
				xhr_object.send(null); 
			}
			
			$(document).ready(function(){  
				send_url();
			});
			
			</script>  
			 

			<?php
			/*if(function_exists(curl_init)){ 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $str);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
				
				
				//curl_setopt($ch, CURLOPT_POST, true); 
				//curl_setopt($ch, CURLOPT_POSTFIELDS, 'dowhat=adddemo&email='.$from); 
				
				$cRetour = curl_exec($ch);
		
				//$update = curl_getinfo($ch, CURLINFO_FILETIME);
		
				curl_close($ch);
		
				return $cRetour;
			}
			else{ 
				$res = fopen($str, "r"); 
			}*/ 
			
			//echo "lastdate ".$lastdate."<br />";
			//echo "nextdate ".$nextdate."<br />";
			
			$oCron = new Cms_cron ($index);
			$oCron->set_lastdate($lastdate);
			$oCron->set_nextdate($nextdate);
			dbUpdate ($oCron); 
		}				
									 
	}
}


function get_date_syntaxe_ymdhis ($mydate) { 
	// param 2010-07-12 13:17:00
	// expected date('Y-m-d H:i:s'); 
	if (preg_match("/([0-9]{4})\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", $mydate)){	
		$aaaa = preg_replace("/([0-9]{4})\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);	
		$mm = preg_replace("/[0-9]{4}\-([0-9]{2})\-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);
		$jj = preg_replace("/[0-9]{4}\-[0-9]{2}\-([0-9]{2}) [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);	
		
		$hh = preg_replace("/[0-9]{4}\-[0-9]{2}\-[0-9]{2} ([0-9]{2}):[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);
		$min = preg_replace("/[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:([0-9]{2}):[0-9]{2}/msi", "$1", $mydate);
		$ss = preg_replace("/[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}:[0-9]{2}:([0-9]{2})/msi", "$1", $mydate);					
	
	} 
	else if (preg_match("/([0-9]{2})\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}", $mydate)){	
		$aaaa = preg_replace("/[0-9]{2}\/[0-9]{2}\/([0-9]{4}) [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);	
		$mm = preg_replace("/[0-9]{2}\/([0-9]{2})\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);
		$jj = preg_replace("/([0-9]{2})\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);	
		
		$hh = preg_replace("/[0-9]{2}\/[0-9]{2}\/[0-9]{4} ([0-9]{2}):[0-9]{2}:[0-9]{2}/msi", "$1", $mydate);
		$min = preg_replace("/[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:([0-9]{2}):[0-9]{2}/msi", "$1", $mydate);
		$ss = preg_replace("/[0-9]{2}\/[0-9]{2}\/[0-9]{4} [0-9]{2}:[0-9]{2}:([0-9]{2})/msi", "$1", $mydate);					
	
	} 
	return  mktime($hh, $min, $ss, $mm , $jj , $aaaa) ; 
}	


?>