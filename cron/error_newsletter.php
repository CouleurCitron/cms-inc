<?php

/*
 * 
 * Script permettant de black lister automatiquement les mails retournés en erreur lors de l'envoie de la newsletter.
 * 02/05/2013 Raphaël v1.0 du script
 * 
 */
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');

//pre_dump($_SESSION); die();

//limit si -1, aucune limite de mail à supprimer
if(!is_array($domain_to_ignore)){ //a definir dans le fichier config.php
    $domain_to_ignore = array();
} 

if (defined("DEF_NEWS_ERRORS") ) {
	$limit = DEF_NEWS_ERRORS;
}
else {
	$limit = 100;
}



//fonction de recherche d'email dans un message
function mail_from_str($sChaine) {
  if(false !== preg_match_all('`\w(?:[-_.]?\w)*@\w(?:[-_.]?\w)*\.(?:[a-z]{2,4})`', $sChaine, $aEmails)) {
    if(is_array($aEmails[0]) && sizeof($aEmails[0])>0) {
      return array_unique($aEmails[0]);
    }
  }
  return null;
}



if(defined('DEF_NEWS_IMAP_SERVER') && defined('DEF_NEWS_IMAP_LOGIN') && defined('DEF_NEWS_IMAP_PWD')){ //a définir dans le fichier config.php
    //on se connecte à l'adresse où se trouvent les mails de retour
    $flux_mail = imap_open(DEF_NEWS_IMAP_SERVER, DEF_NEWS_IMAP_LOGIN, DEF_NEWS_IMAP_PWD);

    //pre_dump(imap_errors());

    //pre_dump($flux_mail);

    $mails = imap_search($flux_mail, 'UNSEEN');

    $mail_to_delete = array();


	$k = 0;

    foreach($mails AS  $mail){
        $header = imap_fetchheader($flux_mail, (int)$mail);

        //pre_dump($mail);
        //pre_dump($header); die();

        if(preg_match("#report-type=delivery-status#", $header) && $limit != -1 && $k <= $limit || preg_match("#report-type=delivery-status#", $header) && $limit == -1){
            //dans le cas d'un email renvoyé concernant un mail non délivré
            $data = imap_body($flux_mail, (int)$mail);
            $info = mail_from_str($data);
			$k++;
    //        $delete_mail = imap_delete($flux_mail, (int)$mail);
            //pre_dump($info);
        } else  {
            $info = array();
        }

        $mail_to_delete = array_unique(array_merge($info, $mail_to_delete));

        //die();
    }

    foreach($mail_to_delete as $k => $mailToDelete){
        foreach($domain_to_ignore as $ignore){


            if(preg_match('#'.$ignore.'#', $mailToDelete)){
                //dans l'ignore list
                unset($mail_to_delete[$k]);
            } else {
                $sSqlInscrit = "SELECT * FROM news_inscrit WHERE ins_mail = '".$mailToDelete."'";

                $aToDelete = dbGetObjectsFromRequete('news_inscrit', $sSqlInscrit);

                if(count($aToDelete)>0){

                    $id = $aToDelete[0]->get_id();

                    //on supprime les associations ainsi que l'inscrit de la BDD
    //                dbDeleteId('news_inscrit', 'ins_id', $id);
    //                dbDeleteId('news_assoinscrittheme', 'xit_news_inscrit', $id);

                    $sSqlUpdate = "UPDATE news_assoinscrittheme SET xit_statut = ".DEF_ID_STATUT_ARCHI." WHERE xit_news_inscrit = ".$id;
                    //pre_dump($sSqlUpdate);
                    dbExecuteQueryQuiet($sSqlUpdate);

                    $aIdDeleted[] = $id;

                }
            }


        }
    }

	//pre_dump($aIdDeleted);

    if(count($aIdDeleted) > 0){
        //on envoie un report de mail
        if(defined("DEF_NEWS_EMAIL_REPORT") && DEF_NEWS_EMAIL_REPORT != ""){ 
            $toMail = DEF_NEWS_EMAIL_REPORT;
            $subject = "[".$_SESSION['site_host']."] Reporting email delete in newsletter's database";
            $report = count($aIdDeleted)." items have been deleted from the newsletter database.<br /><br /><a href='mailto:technique@couleur-citron.com'>Ccitron Technical Admin</a>";
            $from = "technique@couleur-citron.com";
            $replyTo = $from;
            
            //mail($toMail, $subject, $report, $headers);
            //envoie du mail si pas d'erreur de taille de documentation 
			
			if (defined("DEF_USEPHPMAILFUNCTION") && (strval(DEF_USEPHPMAILFUNCTION)=="1")){
				$bSend = (bool)  mail($toMail, $subject, $report, $headers); 
			}
			else{
				//$bSend = (bool)  multiPartMail($addy , $sSubject , $message_html , $message_text, $from, "", "", DEF_MAIL_HOST); 
				$bSend = (bool)  multiPartMail_file($toMail , $subject , $report , strip_tags($report), $from, $attachPath, $aName_file, $typeAttach='text/plain', DEF_MAIL_HOST, $replyto); 
					
			}
			
			
        }
    }
     


    imap_close($flux_mail);
} else {
    error_log("'DEF_NEWS_IMAP_SERVER' and 'DEF_NEWS_IMAP_LOGIN' and 'DEF_NEWS_IMAP_PWD' are not defined in the config.php file.");
    die("'DEF_NEWS_IMAP_SERVER' and 'DEF_NEWS_IMAP_LOGIN' and 'DEF_NEWS_IMAP_PWD' are not defined in the config.php file.");
}
        
?>