<?php

include_once('cms-inc/cron/functions.php');
/*$scripts[0]['minutes'] = '30';
$scripts[0]['heures'] = '8';
$scripts[0]['jour'] = '*';
$scripts[0]['jourSemaine'] = '1-5';
$scripts[0]['mois'] = '1-6,9-12';
$scripts[0]['URLScript'] = 'http://thao.tnt.hephaistos.interne/include/cms-inc/cron/send_newsletter.php';

$scripts[1]['minutes'] = '0,15,30,45';
$scripts[1]['heures'] = '*';
$scripts[1]['jour'] = '*';
$scripts[1]['jourSemaine'] = '1-6';
$scripts[1]['mois'] = '*';
$scripts[1]['URLScript'] = 'http://thao.tnt.hephaistos.interne/include/cms-inc/cron/send_newsletter.php';*/

//echo  date('Y-m-d H:i:s').'<br />';
	
//include_once('cms-inc/include_cms.php'); 
//include_once('cms-inc/include_class.php');

$sql = 'select * from cms_cron where cms_statut = '.DEF_ID_STATUT_LIGNE.' and cms_cms_site = '.$_SESSION['idSite'].' and 
		CURDATE() BETWEEN cms_date_pub_debut AND cms_date_pub_fin 
		order by cms_ordre ASC '; 
 

$aObj = dbGetObjectsFromRequete("cms_cron", $sql); 

if (sizeof($aObj) > 0 && $aObj != false) {
	
	foreach ( $aObj as $obj) {  
	
		$script = array();
		$script['minutes'] = $obj->get_mm();
		$script['heures'] = $obj->get_hh();
		$script['jour'] = $obj->get_jj();
		$script['jourSemaine'] = $obj->get_jjj();
		$script['mois'] = $obj->get_mmm();
		//$script['URLScript'] = 'http://thao.tnt.hephaistos.interne/include/cms-inc/cron/send_newsletter.php';
		
		$file = $obj->get_file();
		if (!preg_match("/^http|ftp|https]:\/\/.*/si", $file)) {
			$file = "http://".$_SERVER['HTTP_HOST'].$file ;
		}
		   
		$script['URLScript'] = $file;
		//$script['lastExecution'] = get_date_syntaxe_ymdhis ($obj->get_lastdate()) ;
		
		//echo $obj->get_lastdate()."<br />";
		//echo "lastExecution ".date('Y-m-d H:i:s', $script['lastExecution'])."<br />";
		
		if ($obj->get_nextdate()!= NULL && $obj->get_nextdate()!= '') {
			$script['nextExecution'] = get_date_syntaxe_ymdhis ($obj->get_nextdate()) ;
			//echo "nextExecution ".date('Y-m-d H:i:s', $script['nextExecution'])."<br />";
		}
		else {
			$script['nextExecution'] = '' ;
		} 
		
		$scripts[$obj->get_id()] = $script;
	
	}  
	  
	buildScriptsNext(); 
	 
}	

	

?>