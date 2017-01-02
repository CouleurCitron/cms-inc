<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');


ini_set ('max_execution_time', 0); // Aucune limite d'execution
ini_set("memory_limit","-1");


if (defined("DEF_NEWS_MAX_ENVOI") ) {
	$nb_envoi_max_lots = DEF_NEWS_MAX_ENVOI;
}
else {
	$nb_envoi_max_lots = 250;
}
 
$aTabNews = array();



$sql = 'select * from news_queue where news_statut = '.DEF_ID_STATUT_ATTEN.' order by news_inscrit ASC LIMIT 0,'.$nb_envoi_max_lots;    

//echo $sql;

$aObj = dbGetObjectsFromRequete("news_queue", $sql); 


$sql_redact = 'select * from news_queue where news_statut = '.DEF_ID_STATUT_REDACT.' order by news_inscrit';     
$aObj_redact = dbGetObjectsFromRequete("news_queue", $sql_redact); 

if (sizeof($aObj_redact) > 0) {
	$bSend = (bool)  multiPartMail_file('technique@couleur-citron.com' , $_SERVER['HTTP_HOST'].' : Envoi de la newsletter, '.sizeof($aObj_redact).' messages en attente' , $html , html2text($html), $from, $attachPath, $aName_file, $typeAttach='text/plain', DEF_MAIL_HOST, $replyto);

}
else {
	
	if (sizeof($aObj) > 0 && $aObj != false) {
	
		//echo "<p>En attente : ".sizeof($aObj)." newsletter(s)</p>";
		
		
		$aCache = array();
		
		foreach ( $aObj as $obj) {  
			
			$id_queue =  $obj->get_id();
			$idnews =  $obj->get_newsletter();
			$idIns =  $obj->get_inscrit();
			$addy = $obj->get_to() ;
			$sSubject = $obj->get_subject() ;
			$headers = $obj->get_headers() ;
			$message_html = $obj->get_html() ; 
			$from = $obj->get_from() ; 
			$attachPath = $_SERVER['DOCUMENT_ROOT'].'/custom/upload/cms_pdf/'; // dossier où sera déplacé le fichier
			$aName_file = unserialize($obj->get_files());  
			$replyto = $obj->get_replyto() ;
			
			
			$message_text = "Si vous n'arrivez pas à lire cet email, copiez-coller ce lien :\n";
			$message_text.= "http://".$_SERVER['HTTP_HOST']."/frontoffice/newsletter/read_newsletter.php?idnew=".$idnews."&idInslien=1&ins=".md5($idIns);	
			
			$cache = array();
			
			$cache["id_queue"] = $id_queue;
			$cache["idnews"] = $idnews;
			$cache["idIns"] = $idIns;
			$cache["addy"] = $addy;
			$cache["sSubject"] = $sSubject;
			$cache["headers"] = $headers;
			$cache["message_html"] = $message_html;
			$cache["from"] = $from;
			$cache["attachPath"] = $attachPath;
			$cache["aName_file"] = $aName_file;
			$cache["replyto"] = $replyto;
			$cache["message_text"] = $message_text;
			
			$aCache[] =  $cache;
			
			if (!in_array ($idnews, $aTabNews)) {
				$aTabNews[] = $idnews;
			}
			
			$obj->set_statut(DEF_ID_STATUT_REDACT) ; // en cours de traitement (évite qu'on l'envoie plusieurs fois);
			$b = dbUpdate ($obj);
			
		}
		
		$aQSent = array();
		
		
		foreach ( $aCache as $cache) {    
			
			$id_queue = $cache["id_queue"]; 
			$idnews = $cache["idnews"];
			$idIns = $cache["idIns"];
			$addy = $cache["addy"];
			$sSubject = $cache["sSubject"];
			$headers = $cache["headers"];
			$message_html = $cache["message_html"];
			$from = $cache["from"];
			$attachPath = $cache["attachPath"];
			$aName_file = $cache["aName_file"];
			$replyto = $cache["replyto"];
			$message_text = $cache["message_text"];
			
			if (defined("DEF_USEPHPMAILFUNCTION") && (strval(DEF_USEPHPMAILFUNCTION)=="1")){
				$bSend = (bool)  mail($addy, $sSubject, $message, $headers);
			}
			else{
				//$bSend = (bool)  multiPartMail($addy , $sSubject , $message_html , $message_text, $from, "", "", DEF_MAIL_HOST);
				$bSend = (bool)  multiPartMail_file($addy , $sSubject , $message_html , $message_text, $from, $attachPath, $aName_file, $typeAttach='text/plain', DEF_MAIL_HOST, $replyto);
					
			}
			
			//error_log("to = ".$addy." | sujet = ".$sSubject."", 0); 
			
			if ($bSend) { 
				//$aQSent[] = $id_queue;
				//echo "envoyé<br />";
				$oQ = new News_queue ($id_queue);
				$oQ->set_statut (DEF_ID_STATUT_LIGNE);
				$oQ->set_date_send (date("Y-m-d H:i:s"));
				$b = dbUpdate ($oQ);
				
				//$sql = 'update news_queue set news_statut = '.DEF_ID_STATUT_LIGNE.'  AND news_date_send = \''.date("Y-m-d H:i:s").'\' WHERE news_id = '.$id_queue;   
				//dbExecuteQueryQuiet($sql);
				
			}
		
		}
		
		/*foreach ($aQSent as $q) {
			$oQ = new News_queue ($q);
			$oQ->set_statut (DEF_ID_STATUT_LIGNE);
			$oQ->set_date_send (date("Y-m-d H:i:s"));
			$b = dbUpdate ($oQ);
		
		}*/
		
		
		
		
		
	}
	
	else {
	
	
	}
}

 

// envoi de mail test admin ---------------------------------------



foreach ($aTabNews as $news) {
	$sql = 'select count(*) from news_queue where news_statut = '.DEF_ID_STATUT_ATTEN.' and news_newsletter = '.$news; 
	$nb_queue = dbGetUniqueValueFromRequete($sql);
	
	$sql = 'select count(*) from news_queue where news_statut = '.DEF_ID_STATUT_LIGNE.' and news_newsletter = '.$news; 
	$nb_sent = dbGetUniqueValueFromRequete($sql);
	
	//echo "nb_queue".$nb_queue  ;
	$oNews = new Newsletter($news);
	if ($nb_queue == 0 && $oNews->get_statut() == DEF_ID_STATUT_LIGNE)  {
	
		$oNews->set_statut(DEF_ID_STATUT_ARCHI);
		 
		dbUpdate ($oNews);		 
		
		
		// envoi mail 
		$html = 'http://'.$_SERVER['HTTP_HOST'].'/<br />';
		$html.= 'La newsletter '.$oNews->get_libelle().' a bien été envoyée à '.$nb_sent.' inscrit(s).';
		
		$from = DEF_CONTACT_FROM;
		if (defined("DEF_CONTACT_NO_REPLY")) {
			$replyto = DEF_CONTACT_NO_REPLY;
		}
		else {
			$replyto = DEF_CONTACT_FROM;
		} 
		
		$bSend = (bool)  multiPartMail_file(DEF_CONTACT_TO_ADMIN , $_SERVER['HTTP_HOST'].' : Envoi de la newsletter '.$oNews->get_libelle()." finalisée" , $html , html2text($html), $from, $attachPath, $aName_file, $typeAttach='text/plain', DEF_MAIL_HOST, $replyto);	
		
		if (!preg_match('/hephaistos/', $_SERVER['HTTP_HOST']) == 1 ) {
			$bSend = (bool)  multiPartMail_file("technique@couleur-citron.com" , $_SERVER['HTTP_HOST'].' : Envoi de la newsletter '.$oNews->get_libelle()." finalisée" , $html , html2text($html), $from, $attachPath, $aName_file, $typeAttach='text/plain', DEF_MAIL_HOST, $replyto); 
		}
                
                
                /* Une fois que le mail a été complètement envoyé il faut vider la BDD */
                
                //on vérifie que la table de queue n'est pas vide
                $aQueue = dbGetObjects("news_queue");

                if(count($aQueue)){
                    /* on vide la table de BDD une fois que tous les envois ont été faits */
                    $sSqlVidage = "DELETE FROM news_queue WHERE news_statut = ".DEF_ID_STATUT_LIGNE."";
                    dbExecuteQueryQuiet($sSqlVidage);
                }
                
                
	}	
	

}


// on nettoie un peu ---------------------------------------
// supprime toutes les newsletter en queue datant de moins de 3 mois;
 /*
$sql = "delete from news_queue where news_statut = '.DEF_ID_STATUT_LIGNE.'  AND news_date_send  < DATE_ADD(CURDATE(),  INTERVAL -1 MONTH) order by news_date_send  ASC';   
dbExecuteQueryQuiet($sql);*/

?>