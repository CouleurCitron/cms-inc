<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/newsletter/functions.lib.php');

$bPear = @include('Mail.php');

if ($bPear===false){
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/pear/mime.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/pear/mail.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/pear/Mail/mail.php');
	include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/pear/Mail/smtp.php');
}
else{
	include_once('Mail/mime.php');
	include_once('Mail/mail.php');
	include_once('Mail/smtp.php');
}

/*
function rewriteNewsletterSubject($sSubject, $bUseCriteres=0, $lang=1){	
function rewriteNewsletterBody($sBodyHTML, $eIns=0, $eNews=0, $theme=0, $bUseCriteres=0, $bUseMultiple=0, $lang=1, $sSubject=NULL){
function mailAdmin($sujet , $text, $from=''){
function multiPartMail($to , $sujet , $html , $text, $from, $attach='', $typeAttach='text/plain', $host=DEF_MAIL_HOST){
function get_extension($filename)
function multiPartMail_file($to , $sujet , $html , $text, $from, $attach='', $sName_file, $typeAttach='text/plain', $host=DEF_MAIL_HOST){
function htmlmail($f = "UNSPECIFIED",$t,$s,$h,$b = "UNSPECIFIED") { 
function multiPartMail_image
function get_mimeType($ext);
*/

if (!defined('DEF_SENDMAILPATH')){
	define('DEF_SENDMAILPATH', ini_get('sendmail_path'));	
}

if (!defined('DEF_MAIL_ENGINE')){
	define('DEF_MAIL_ENGINE', 'sendmail');
}

if (!defined('DEF_USEPHPMAILFUNCTION')){
	define('DEF_USEPHPMAILFUNCTION', '0');
}

if (!defined('DEF_USEPHPMAILFUNCTION')){
	define('DEF_USEPHPMAILFUNCTION', '0');
}

if (!defined('DEF_CDN_NEWSLETTER')){
	define('DEF_CDN_NEWSLETTER', 'OFF');
}

if (!defined('DEF_REWRITE_NEWSLETTER')){
	define('DEF_REWRITE_NEWSLETTER', 'OFF');
}

if (!defined('DEF_MAIL_PORT')){
	define('DEF_MAIL_PORT', 25);
}

if (!defined('DEF_MAIL_LOGIN')){
	define('DEF_MAIL_LOGIN', '');
}

if (!defined('DEF_MAIL_PASS')){
	define('DEF_MAIL_PASS', '');
}


function rewriteNewsletterSubject($sSubject, $bUseCriteres=0, $lang=1, $oCriNlter){
	
	if($bUseCriteres==1){
		// si traitement par critères, donc custom, report sur objet metier			
		$_SESSION['id_langue']=$lang;
		$translator =& TslManager::getInstance();			
		
		//$oCriNlter = new critereNewsletter();
		
		$oCriNlter->bUseCriteres=$bUseCriteres;
		$oCriNlter->lang=$lang;
		
		if (method_exists($oCriNlter, 'getSubject')){
			$sSubject = $oCriNlter->getSubject($sSubject, $aOs, $translator);			
		}
		
	}
	
	$sSubject = html_entity_decode(accent2Html($sSubject, true)); // accent2Html avec param true execute html2accent
	
	return $sSubject;
}

function rewriteNewsletterBody($sBodyHTML, $eIns=0, $eNews=0, $theme=0, $bUseCriteres=0, $bUseMultiple=0, $lang=1, $sSubject=NULL, $oCriNlter=NULL){	
	global $db;
	error_log('rewriteNewsletterBody('.$eNews.')');
	error_reporting(A_LL);

	if($bUseCriteres==1){
		// newsletter inscrit	
		$sql = 'SELECT * FROM news_assoinscrittheme WHERE xit_statut = '.DEF_ID_STATUT_LIGNE.' AND xit_news_inscrit = '.$eIns.' AND xit_news_theme = '.$theme.';';	
	 
 		$aX = dbGetObjectsFromRequete("news_assoinscrittheme", $sql);
		$sSRCcriteres='';
		
		if (defined('DEF_JOBS_NEWS_THEME_ID') && DEF_JOBS_NEWS_THEME_ID == $theme) {
			$aOs=array();
			
			include_once('cms-inc/jobs/class.JobsController.php');
			$views = array('jobs_offer_list'	=> array(	'mode'	=> 'raw',
							'render'	=> 'renderOfferObjects'));
			$jb = new JobsController($views);
			$_POST['action']='jobs_offer_list';
			
			$_SESSION['id_langue']=$lang;
			$translator =& TslManager::getInstance();
			$jm = $jb->models['jobs'];
			$jm->debug = false;// false / true
						
			foreach($aX as $k => $oX){
				$aCriteres = unserialize($oX->get_criteres());
				$aCriteres['date_published']=date('Y-m-d');

				if(is_array($aCriteres)){					
					$aRes = $jm->getOffers ($aCriteres['type'], $aCriteres['place'], $aCriteres['function'], $aCriteres['experience'], $aCriteres['text'], $aCriteres['reference'], $aCriteres['date_published'], $aCriteres['date_start']);

					foreach($aRes as $kRes => $oRes){// dedoublonnage
						$aOs[$oRes["id"]]=$oRes;
					}
				}
				else{
					$aRes = $jm->getOffers (-1, $aCriteres['place'], -1, -1, '', '', $aCriteres['date_published'], '');
						
					foreach($aRes as $kRes => $oRes){// dedoublonnage
						$aOs[$oRes["id"]]=$oRes;
					}
				}
			}			
		}
		else{
			$aOs=NULL;
		}
		
		// si traitement par critères, donc custom, report sur objet metier
		if (defined('DEF_CRITERE_LIB') && is_file(DEF_CRITERE_LIB)) {
			include_once(DEF_CRITERE_LIB);
			
			if (!isset($translator)){
				$_SESSION['id_langue']=$lang;
				$translator =& TslManager::getInstance();
			}
				
			if ($oCriNlter==NULL){ // si n'est pas passé en param
				$oCriNlter = new critereNewsletter();
			}			
			
			$oCriNlter->eIns=$eIns;
			$oCriNlter->eNews=$eNews;
			$oCriNlter->theme=$theme;
			$oCriNlter->bUseCriteres=$bUseCriteres;
			$oCriNlter->bUseMultiple=$bUseMultiple;
			$oCriNlter->lang=$lang;
			
			if (method_exists($oCriNlter, 'getBody')){
				$sBodyHTML = $oCriNlter->getBody($sBodyHTML, $aOs, $translator, $sSubject);
			}
			
			if ($sBodyHTML==false){
				return false; // pas de body, on sort
			}
		}
		else{ // pas de lib de traitement, on sort
			error_log('alert = pas de lib de traitement de la newsletter criteres');
			return false; 
		}		
	}

	$sBodyHTML = preg_replace('/http:\/\/[^\/]+\.interne\//msi', '/', $sBodyHTML);	
	$sBodyHTML = str_replace('http://'.$_SERVER['HTTP_HOST'].'/', '/', $sBodyHTML);

	if (defined('DEF_REWRITE_NEWSLETTER')	&&	(DEF_REWRITE_NEWSLETTER == true)){	
		// ici rewirte pour taggage ins=300-6805-MD5
		
		//$sBodyHTML = str_replace('href="http://'.$_SERVER['HTTP_HOST'].'/', 'href="/', $sBodyHTML);	
		//$sBodyHTML = str_replace('href="/', 'href="http://'.$_SERVER['HTTP_HOST'].'/newsletter/'.$eNews.'/'.$eIns.'/', $sBodyHTML);
		
		preg_match_all('/href="([^"]+)"/msi', $sBodyHTML, $aAllLinks);
		
		if(isset($aAllLinks[1])){
			
			$aAllLinks = $aAllLinks[1];
			
			foreach($aAllLinks as $k => $link){
				
				if(!preg_match('/google/msi', $link)){
				
					$sql='SELECT count(*) FROM news_links WHERE news_md5 = "'.md5($link).'"';
					$rs = $db->Execute($sql);
					$linkDejaMD5=0;
					if($rs){
						while(!$rs->EOF) {
							$linkDejaMD5 = $rs->fields[0];
							break;
						}					
					}
					if($linkDejaMD5==0){
						$oO = new news_links();
						$oO->set_url($link);
						$oO->set_md5(md5($link));
						dbSauve($oO);					
					}

					if ($link!=''	&&	!preg_match('/^mailto/msi', $link)	&&	!preg_match('/^tel/msi', $link)	&&	$link!='#'	&&	!preg_match('/^#/msi', $link)){
						$sBodyHTML = str_replace('href="'.$link, 'href="http://'.$_SERVER['HTTP_HOST'].'/frontoffice/newsletter/?ins='.$eNews.'-'.$eIns.'-'.md5($link).'"', $sBodyHTML);
					}	

				}
				
			}
			
			
		}
		
		
	}
	else{
		$sBodyHTML = str_replace('href="/', 'href="http://'.$_SERVER['HTTP_HOST'].'/', $sBodyHTML);
	}
	
	if (defined('DEF_CDN_NEWSLETTER')	&&	(DEF_CDN_NEWSLETTER == 'ON') && (preg_match('/\.interne$/', $_SERVER['HTTP_HOST'])==0)){
		//pas possible en interne
		// ici rewrite pour CDN -- .nyud.net
		$sBodyHTML = preg_replace('/src="\//',  'src="http://'.$_SERVER['HTTP_HOST'].'.nyud.net/', $sBodyHTML);
		$sBodyHTML = preg_replace('/background="\//',  'background="http://'.$_SERVER['HTTP_HOST'].'.nyud.net/', $sBodyHTML);
		$sBodyHTML = preg_replace('/url\(\//',   'url(http://'.$_SERVER['HTTP_HOST'].'.nyud.net/', $sBodyHTML);
		
	}
	else{
		$sBodyHTML = preg_replace('/src="\//',  'src="http://'.$_SERVER['HTTP_HOST'].'/', $sBodyHTML);
		$sBodyHTML = preg_replace('/background="\//',  'background="http://'.$_SERVER['HTTP_HOST'].'/', $sBodyHTML);
		$sBodyHTML = preg_replace('/url\(\//',   'url(http://'.$_SERVER['HTTP_HOST'].'/', $sBodyHTML);
	}		
	
	// desabo
	// <a href="frontoffice/coulisses/desinscription.php" title="desincription" style="color: rgb(67, 50, 68);">	
	$sBodyHTML = preg_replace('/<a href="([^"]+)" title="desincription"/',  '<a href="$1?ins='.$eIns.'" title="desincription"', $sBodyHTML);
	
	$sBodyHTML = str_replace("XX-MAIL-REPLYTO-XX", getReplyTo($eNews), $sBodyHTML);
	$dateFY = date ('F Y');
	
	if ($_SESSION["site_langue"] == "fr") {
		$mois_EN = array (
		"january" , 
		"february" 	,
		"march", 
		"april"	,
		"may" , 
		"june" 	,
		"july", 
		"august"	,
		"september",  
		"october" ,  
		"november" ,  
		"december"  
		);
		
		$mois_FR = array (
		"janvier", 
		"février"	,
		"mars", 
		"avril"	,
		"mai", 
		"juin"	,
		"juillet", 
		"août"	,
		"septembre",  
		"octobre",  
		"novembre",  
		"décembre" 
		);
		 
		$dateFY = ucfirst(str_replace ($mois_EN, $mois_FR, strtolower($dateFY))) ; 
	}

	$sBodyHTML = str_replace("XX-MAIL-DATE-FY-XX", $dateFY, $sBodyHTML);
	if($eIns>0){
		$sBodyHTML = str_replace("XX-ID-INSCRIT-XX", md5($eIns), $sBodyHTML);
	}
	$sBodyHTML = str_replace("XX-IDNEW-XX", $eNews, $sBodyHTML); 
	$sBodyHTML = str_replace("XX-RN-XX", "\r\n", $sBodyHTML); 
	$sBodyHTML = str_replace("lien=1", "lien=1&idInslien=".md5($eIns), $sBodyHTML);	
	
	// sujet
	if ($sSubject!=NULL){
		$sBodyHTML = str_replace("XX-SUJET-XX", $sSubject, $sBodyHTML); 
	}

	wordwrap($text, 20, "<br />\n");
	// taggage ouverture de la letter	
	if(preg_match('/<\/body>/msi', $sBodyHTML)){
		$sBodyHTML= str_replace('</body>', '<img src="http://'.$_SERVER['HTTP_HOST'].'/frontoffice/newsletter/?ins='.$eNews.'-'.$eIns.'" style="display:none" width="1" height="1" /></body>', $sBodyHTML);
	}
	else{
		$sBodyHTML.= '<img src="http://'.$_SERVER['HTTP_HOST'].'/frontoffice/newsletter/?ins='.$eNews.'-'.$eIns.'" style="display:none" width="1" height="1" />';
	}	
	$sBodyHTML =  wordwrap($sBodyHTML, 78, "\r\n");
	return $sBodyHTML;
}

function mailAdmin($sujet , $text, $from=DEF_USERMAIL){
	$to = 'mail de l admin';
	return multiPartMail($to , $sujet , $text , html2text($text), $from);
	//SID
}


if(!defined('MAIL_LIB_PHP') || MAIL_LIB_PHP == 'default'){
    /* gestion des mails par defaut */
    
    
    function multiPartMail($to , $sujet , $html , $text, $from, $attach='', $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){
			$sujetRaw = $sujet;
            $sujet = '=?iso8859-1?B?'.base64_encode($sujetRaw).'?=';


            if (DEF_MAIL_ENGINE=='sendmail'){
                    $from = preg_replace('/.*<([\-\._a-zA-Z0-9]+@[\-\.a-zA-Z0-9]+\.[a-zA-Z]+)>.*/msi', '$1', $from);
            }

            $from = mb_encode_mimeheader($from);	

            if ((ini_get('safe_mode')==1)	||	(DEF_USEPHPMAILFUNCTION=='1')) {
                    return (htmlmail($from,$to,$sujet,$html));
            }
            else {
                    if(is_array($to)) $to = implode(', ', $to);

            //	$parammulti['content_type'] = 'multipart/mixed';
                    $parammulti['content_type'] = 'multipart/alternative';	

                    $email = new Mail_mimePart('',$parammulti);


                    $parammulti['content_type'] = 'text/plain';
                    $parammulti['encoding'] = '8bit';
                    $parammulti['disposition'] = 'inline';
                    $text = $email->addSubpart($text, $parammulti);

                    if(strlen($html)) {
                            $parammulti['content_type'] = 'text/html';
                            $parammulti['encoding'] = '8bit';
                            $parammulti['disposition'] = 'inline';

                            $html = & $email->addSubPart($html, $parammulti);
                    }

                    if(strlen($attach)) {
                            $paramattach=array(
                                    'content_type' => $typeAttach,
                                            'encoding' => '8bit',
                                     'disposition' => 'attachment',
                                       'dfilename' => $attach
                            );
                            $piecejointe = & $email->addSubPart($attach, $paramattach);
                    }

                    $message = $email->encode(); 

                    $destinataire = $to;
                    error_log('to = '.$destinataire.' | sujet = '.$sujetRaw.' | from = '.$from.' | '.DEF_MAIL_ENGINE.': '.$host.':'.DEF_MAIL_PORT);

                    $entetes = $message['headers'];

                    $entetes["From"]= $from; 
                    $entetes["Return-Path"] = preg_replace('/.*<([\-\._a-zA-Z0-9]+@[\-\.a-zA-Z0-9]+\.[a-zA-Z]+)>.*/msi', '$1', $from);
                    $entetes["To"] = $to;
                    $entetes["Subject"] = $sujet;
                    $entetes["MIME-Version"] = "1.0";
                    if ($replyto !='') $entetes["Reply-To"] = $replyto;

                    $params = array(
                      "host" => $host,
                      "port" => DEF_MAIL_PORT 
                    );

                    if (DEF_MAIL_LOGIN != ''){
                            $params['auth'] = 'LOGIN';
                            $params['username'] = DEF_MAIL_LOGIN;
                            if (DEF_MAIL_PASS != ''){
                                    $params['password'] = DEF_MAIL_PASS;
                            }		

                            //$params["debug"]=true;
                            if (preg_match('/localhost/msi', $host)){
                                    $params["localhost"] = 'localhost';
                            }
                            else{
                                    $params["localhost"] = $_SERVER['HTTP_HOST'];
                            }
                            $params["timeout"] = 30;
                    }
                    else {
                            $params['auth'] = false;
                    }

					if (DEF_MAIL_ENGINE=='smtp'){
						$mailObj = new Mail_smtp($params);					
					}
					else{
                    $mailObj = &Mail::factory(DEF_MAIL_ENGINE, $params); 
						//$mailObj = new Mail_mail(DEF_MAIL_ENGINE.' '.implode(' ', $params));
					}
                    $result = $mailObj->send($destinataire,$entetes,$message['body']);
                    if($result===true) {
                            return true;			
                    }
                    else {
						if(method_exists($result, 'getMessage')){
                            error_log($result->getMessage());
                            if (preg_match('/email\.php$/msi', $_SERVER['PHP_SELF'])){
                                    echo $result->message;
                            }
						}
                            return false;
                    }	 
            }
    }



    function multiPartMail_AR($to , $sujet , $html , $text, $from, $attach='', $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){
            $sujet = '=?iso8859-1?B?'.base64_encode($sujet).'?=';
            //$from = mb_convert_encoding($from, "UTF-8");
            $from = mb_encode_mimeheader($from);

            if (DEF_MAIL_ENGINE=='sendmail'){
                    $from = preg_replace('/.*<([\-\._a-zA-Z0-9]+@[\-\.a-zA-Z0-9]+\.[a-zA-Z]+)>.*/msi', '$1', $from);
            }

            if (ini_get('safe_mode')==1) {

                    return (htmlmail($from,$to,$sujet,$html));
            }
            else {

                    if(is_array($to)) $to = join(', ', $to);

                    if(is_array($to)) $to = join(', ', $to);

            //	$parammulti['content_type'] = 'multipart/mixed';
                    $parammulti['content_type'] = 'multipart/alternative';


                    $email = new Mail_mimePart('',$parammulti);


                    $parammulti['content_type'] = 'text/plain';
                    $parammulti['encoding'] = '8bit';
                    $parammulti['disposition'] = 'inline';
                    $text = $email->addSubpart($text, $parammulti);

                    if(strlen($html)) {
                            $parammulti['content_type'] = 'text/html';
                            $parammulti['encoding'] = '8bit';
                            $parammulti['disposition'] = 'inline';

                            $html = & $email->addSubPart($html, $parammulti);
                    }

                    if(strlen($attach)) {
                            $paramattach=array(
                                    'content_type' => $typeAttach,
                                            'encoding' => '8bit',
                                     'disposition' => 'attachment',
                                       'dfilename' => $attach
                            );
                            $piecejointe = & $email->addSubPart($attach, $paramattach);
                    }

                    $message = $email->encode(); 



                    $destinataire = $to;
                    error_log("to = $destinataire");

                    $entetes = $message['headers'];

                    $entetes["From"]= $from;
                    $entetes["Disposition-Notification-To"] = $from;
                    $entetes["Return-Path"] = $from;
                    $entetes["To"] = $to;
                    $entetes["Subject"] = $sujet;
                    $entetes["MIME-Version"] = "1.0";
                    if ($replyto !='') $entetes["Reply-To"] = $replyto;

                    $params = array(
                      "host" => $host,
                      "port" => DEF_MAIL_PORT 
                    );

                    if (DEF_MAIL_LOGIN != ''){
                            $params['auth'] = true;
                            $params['username'] = DEF_MAIL_LOGIN;
                            if (DEF_MAIL_PASS != ''){
                                    $params['password'] = DEF_MAIL_PASS;
                            }		
                    }
                    else {
                            $params['auth'] = false;
                    }

                    $mailObj = &Mail::factory(DEF_MAIL_ENGINE, $params); 
                    $result = $mailObj->send($destinataire,$entetes,$message['body']);
                    if($result!=true) {
                            error_log($result->getMessage());
                            return false;
                    } else {
                            return true;
                    }	 
            }
    }

    function multiPartMail_file($to , $sujet , $html , $text, $from, $attach, $aName_file, $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){
            $sujet = '=?iso8859-1?B?'.base64_encode($sujet).'?=';
            $//$from = mb_convert_encoding($from, "UTF-8");
            $from = mb_encode_mimeheader($from);

            if (DEF_MAIL_ENGINE=='sendmail'){
                    $from = preg_replace('/.*<([\-\._a-zA-Z0-9]+@[\-\.a-zA-Z0-9]+\.[a-zA-Z]+)>.*/msi', '$1', $from);
            }

            $parametres['content_type'] = 'multipart/mixed';
            $email = new Mail_mimePart('', $parametres);

            // Ici nous ajoutons une section texte au multipart que nous avons d&eacute;j&agrave;
            // C'est au programmeur de s'assurer que $corpDuMessage est du texte simple
            //$parametres['content_type'] = 'text/plain';
            $parametres['content_type'] = 'text/html';
            $parametres['encoding']   = '7bit';
            $text = $email->addSubPart($html, $parametres);

            for ($i=0; $i<sizeof($aName_file); $i++)
            {
                    if ($aName_file[$i] != "") {		

                            $extension=get_extension($aName_file[$i]);
                            $typeAttach="text/plain";

                            if($extension == "doc")
                            {
                               $typeAttach="text/vnd.ms-word";
                            }
                            else if($extension == "xls")
                            {
                               $typeAttach="text/vnd.ms-excel";
                            }
                            else if($extension == "jpg")
                            {
                               $typeAttach="image/jpeg";
                            }
                            else if($extension == "gif")
                            {
                               $typeAttach="image/gif";
                            }
                            else if($extension == "xls")
                            {
                               $typeAttach="text/vnd.ms-excel";
                            }
                            else if($extension == "pdf")
                            {
                               $typeAttach="application/pdf";
                            }
                            else if($extension == "png")
                            {
                               $typeAttach="image/png";
                            }
                            else if($extension == "tiff")
                            {
                               $typeAttach="image/tiff";
                            }
                            $parametres['content_type'] = $typeAttach;
                            $parametres['encoding']     = 'base64';
                            $parametres['disposition']  = 'attachment';
                            $parametres['dfilename']    = $aName_file[$i];
                            $fichier=file_get_contents($attach.$aName_file[$i]); 
                            $pieceJointe =& $email->addSubPart($fichier, $parametres);
                    }
            }


            $message = $email->encode(); 

            $destinataire = $to;
            error_log('to = '.$destinataire.' | sujet = '.$sujet.' | from = '.$from);

            $entetes = $message['headers'];

        //$aFrom = explode("<", $from);
        //$from = '=?iso8859-1?B?'.base64_encode($aFrom[0]).'?= '."<".$aFrom[1]."";
            $entetes["From"]= $from;
            $entetes["Return-Path"] = $from;
            $entetes["To"] = $to;
            $entetes["Subject"] = $sujet;
            $entetes["MIME-Version"] = "1.0";
            if ($replyto !='') $entetes["Reply-To"] = $replyto;

            $params = array(
              "host" => $host,
              "port" => DEF_MAIL_PORT 
            );

            if (DEF_MAIL_LOGIN != ''){
                            $params['auth'] = 'LOGIN';
                    $params['username'] = DEF_MAIL_LOGIN;
                    if (DEF_MAIL_PASS != ''){
                            $params['password'] = DEF_MAIL_PASS;
                    }		

                            //$params["debug"]=true;
                            if (preg_match('/localhost/msi', $host)){
                                    $params["localhost"] = 'localhost';
                            }
                            else{
                                    $params["localhost"] = $_SERVER['HTTP_HOST'];
                            }
                            $params["timeout"] = 30;
            }
            else {
                    $params['auth'] = false;
            }

            $mailObj = &Mail::factory(DEF_MAIL_ENGINE, $params);
            $result = $mailObj->send($destinataire, $entetes, $message['body']);
            if($result!=true) {
                    error_log($result->getMessage());
                    return false;
            } else {
                    return true;
            }	

    }
} else if(MAIL_LIB_PHP == 'swift') {
    /* gestion grace a swiftmailer */
    require __DIR__ . '/lib/vendor/autoload.php';
    require __DIR__ . '/lib/MailAdapter/Mail.php';
    
    /**
    * Envoie de mail complexe avec fichiers joint et HTML
    * 
    * @param string $to
    * @param string $sujet
    * @param string $html
    * @param string $text
    * @param string $from
    * @param string $attach
    * @param string $typeAttach
    * @param string $host
    * @param string $replyto
    * @return interger nombre d'email envoyés
    * @throws \Exception si un erreur se produit lors de l'envoie
    */
   function multiPartMail($to , $sujet , $html , $text, $from, $attach='', $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){

       if(defined('DEF_MAIL_LOGIN')){
           $login = DEF_MAIL_LOGIN;
       } else {
           $login = ''; 
       }

       if(defined('DEF_MAIL_PASS')){
           $pwd = DEF_MAIL_PASS;
       } else {
           $pwd = ''; 
       }

       $mail = new \lib\MailAdapter\Mail(DEF_MAIL_ENGINE, $host, $login, $pwd);

       $mail->setSubject($sujet);
       $mail->setFrom($from, '', $replyto);

       if($html){
           $mail->addMessage($html);
           $mail->setHeaders('ContentType', 'text/html');
       }
       else $mail->addMessage($text);

       $mail->setTo($to);
       if($attach) $mail->addAttachment($attach);

       error_log('to = '.$destinataire.' | sujet = '.$sujet.' | from = '.$from);

       return $mail->send();
       exit;
   }


   /**
    * Envoie de mail complexe avec fichiers joint et HTML avec accusé de reception
    * 
    * @param string $to
    * @param string $sujet
    * @param string $html
    * @param string $text
    * @param string $from
    * @param string $attach
    * @param string $typeAttach
    * @param string $host
    * @param string $replyto
    * @return interger nombre d'email envoyés
    * @throws \Exception si un erreur se produit lors de l'envoie
    */
   function multiPartMail_AR($to , $sujet , $html , $text, $from, $attach='', $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){

       if(defined('DEF_MAIL_LOGIN')){
           $login = DEF_MAIL_LOGIN;
       } else {
           $login = ''; 
       }

       if(defined('DEF_MAIL_PASS')){
           $pwd = DEF_MAIL_PASS;
       } else {
           $pwd = ''; 
       }

       $mail = new \lib\MailAdapter\Mail(DEF_MAIL_ENGINE, $host, $login, $pwd);

       $mail->setSubject($sujet);
       $mail->setFrom($from, '', $replyto);

       if($html){
           $mail->addMessage($html);
           $mail->setHeaders('ContentType', 'text/html');
       }
       else $mail->addMessage($text);


       $mail->setHeaders('ReadReceiptTo', $from);

       $mail->setTo($to);
       if($attach) $mail->addAttachment($attach);

       error_log('to = '.$destinataire.' | sujet = '.$sujet.' | from = '.$from);

       return $mail->send();
       exit;
   }

   /**
    * Envoie de mail avec des pièces jointes multiples.
    * 
    * @param string $to
    * @param string $sujet
    * @param string $html
    * @param string $text
    * @param string $from
    * @param string $attach
    * @param array $aName_file
    * @param string $typeAttach
    * @param string $host
    * @param string $replyto
    * @return integer nombre d'email envoyés
    * @throws \Exception si un erreur se produit lors de l'envoie
    */
   function multiPartMail_file($to , $sujet , $html , $text, $from, $attach, $aName_file, $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){

       if(defined('DEF_MAIL_LOGIN')){
           $login = DEF_MAIL_LOGIN;
       } else {
           $login = ''; 
       }

       if(defined('DEF_MAIL_PASS')){
           $pwd = DEF_MAIL_PASS;
       } else {
           $pwd = ''; 
       }

       $mail = new \lib\MailAdapter\Mail(DEF_MAIL_ENGINE, $host, $login, $pwd);

       $mail->setSubject($sujet);
       $mail->setFrom($from, '', $replyto);

       if($html){
           $mail->addMessage($html);
           $mail->setHeaders('ContentType', 'text/html');
       }
       else $mail->addMessage($text);


       $mail->setTo($to);
       foreach($aName_file as $k => $pathfile){
           if($pathfile) $mail->addAttachment($pathfile);
       }


       error_log('to = '.$destinataire.' | sujet = '.$sujet.' | from = '.$from);

       return $mail->send();
       exit;

   }
}


function get_extension($filename)
{
   $parts = explode('.',$filename);
   $last = count($parts) - 1;
   $ext = $parts[$last];
   return $ext;
}

function multiPartMailFormat_file($toName , $toEmail, $sujet , $html , $text, $fromName, $fromEmail, $attach, $aName_file, $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){
$sujet = '=?iso8859-1?B?'.base64_encode($sujet).'?=';
$parametres['content_type'] = 'multipart/alternative';
$email = new Mail_mimePart('', $parametres);

// Ici nous ajoutons une section texte au multipart que nous avons d&eacute;j&agrave;
// C'est au programmeur de s'assurer que $corpDuMessage est du texte simple
//$parametres['content_type'] = 'text/plain';
$parametres['content_type'] = 'text/html';
$parametres['encoding']   = '7bit';
$text = $email->addSubPart($html, $parametres);

// Maintenant on ajoute la pi&egrave;ce jointe
// C'est au programmeur de s'assurer que $contenuDePieceJointe 
// contient bel et bien le coprs de la pi&egrave;ce &agrave; joindre

for ($i=0; $i<sizeof($aName_file); $i++)
{
	if ($aName_file[$i] != "") {
		$extension=get_extension($aName_file[$i]);
		$typeAttach="text/plain";
	
		if($extension == "doc")
		{
		   $typeAttach="text/vnd.ms-word";
		}
		else if($extension == "xls")
		{
		   $typeAttach="text/vnd.ms-excel";
		}
		else if($extension == "jpg")
		{
		   $typeAttach="image/jpeg";
		}
		else if($extension == "gif")
		{
		   $typeAttach="image/gif";
		}
		else if($extension == "xls")
		{
		   $typeAttach="text/vnd.ms-excel";
		}
		else if($extension == "pdf")
		{
		   $typeAttach="application/pdf";
		}
		else if($extension == "png")
		{
		   $typeAttach="image/png";
		}
		else if($extension == "tiff")
		{
		   $typeAttach="image/tiff";
		}
		$parametres['content_type'] = $typeAttach;
		$parametres['encoding']     = 'base64';
		$parametres['disposition']  = 'attachment';
		$parametres['dfilename']    = $aName_file[$i]; 
		$fichier=file_get_contents($attach.$aName_file[$i]); 
		$pieceJointe =& $email->addSubPart($fichier, $parametres);
	}
}

// Finalement on construit l'email 
// Notez que encode retourne un tableau associatif contenant deux 
// &eacute;l&eacute;ments le corps et un tableau des en-t&ecirc;tes.
// Vous completerez par d'autres en-t&ecirc;te avant l'envoi
// (par ex. Mime-Version)

//$email = $message->encode();
//$email['headers']['Mime-Version'] = '1.0';


	$message = $email->encode(); 

	//$destinataire = "=?iso-8859-1?Q?".base64_encode($toName)."?= <".$toEmail.">";
	//$from = "=?iso-8859-1?Q?".base64_encode($fromName)."?= <".$fromEmail.">";
	
	$destinataire = '=?iso8859-1?B?'.base64_encode($toName).'?= <'.$toEmail.'>';
	$from = '=?iso8859-1?B?'.base64_encode($fromName).'?= <'.$fromEmail.'>';
	
	if (DEF_MAIL_ENGINE=='sendmail'){
		$from = preg_replace('/.*<([\-\._a-zA-Z0-9]+@[\-\.a-zA-Z0-9]+\.[a-zA-Z]+)>.*/msi', '$1', $from);
	}
	
	error_log("to = $destinataire");
	
	$entetes = $message['headers'];
	$entetes["From"]= $from;
	$entetes["Return-Path"] = $from;
	$entetes["To"] = $destinataire;
	$entetes["Subject"] = $sujet;
	$entetes["MIME-Version"] = "1.0";
	if ($replyto !='') $entetes["Reply-To"] = $replyto;
	
	$params = array(
	  "host" => $host,
	  "port" => DEF_MAIL_PORT 
	);
	
	if (DEF_MAIL_LOGIN != ''){
		$params['auth'] = true;
		$params['username'] = DEF_MAIL_LOGIN;
		if (DEF_MAIL_PASS != ''){
			$params['password'] = DEF_MAIL_PASS;
		}		
	}
	else {
		$params['auth'] = false;
	}

	$mailObj = &Mail::factory(DEF_MAIL_ENGINE, $params);

	$result = $mailObj->send($destinataire, $entetes, $message['body']);
	if($result!=true) {
		error_log($result->getMessage());
		return false;
	} else {
		return true;
	}	

}



function htmlmail($f = "UNSPECIFIED",$t,$s,$h,$b = "UNSPECIFIED") { 
	// Where textbody is optional an if omitted will be replaced with a plain text version of the htmlbody line 
	// (from,to,subject,htmlbody,textbody) 	
	
	if ($f == "UNSPECIFIED") { $f = "technique@couleur-citron.com"; } 
	if ($b == "UNSPECIFIED") { $b = strip_tags($h); } 
	
	$headers = "From: $f\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$q = uniqid("WblQ"); 
	$headers .= "Content-Type: multipart/alternative" . "; boundary = $q\n\n"; 
	$headers .= "This is a MIME encoded message.\n\n"; 
	$headers .= "--$q\n" . "Content-Type: text/plain; charset=ISO-8859-1\n" . "Content-Transfer-Encoding: base64\n\n"; 
	$headers .= chunk_split(base64_encode($b)); 
	$headers .= "--$q\n" . "Content-Type: text/html; charset=ISO-8859-1\n" . "Content-Transfer-Encoding: base64\n\n"; 
	$headers .= chunk_split(base64_encode($h)); 
	return mail($t, $s, "", $headers);
	
	//return multiPartMail($t , $s, $h , $b, $f, $attach='', $typeAttach='text/plain', $host='localhost');
}



function multiPartMail_image($to , $sujet , $html , $text, $from, $attachPath, $aName_file, $typeAttach='text/plain', $host=DEF_MAIL_HOST, $replyto=''){

	$parametres['content_type'] = 'multipart/related';
	$email = new Mail_mimePart('', $parametres);
	
	if (DEF_MAIL_ENGINE=='sendmail'){
		$from = preg_replace('/.*<([\-\._a-zA-Z0-9]+@[\-\.a-zA-Z0-9]+\.[a-zA-Z]+)>.*/msi', '$1', $from);
	}
	
	// Ici nous ajoutons une section texte au multipart que nous avons d&eacute;j&agrave;
	// C'est au programmeur de s'assurer que $corpDuMessage est du texte simple
	//$parametres['content_type'] = 'text/plain';
	$parametres['content_type'] = 'text/html';
	$parametres['encoding']   = '7bit';
	$text = $email->addSubPart($html, $parametres);
	
	// Maintenant on ajoute la pi&egrave;ce jointe
	// C'est au programmeur de s'assurer que $contenuDePieceJointe 
	// contient bel et bien le coprs de la pi&egrave;ce &agrave; joindre
	
	
	
	for ($i=0; $i<sizeof($aName_file); $i++)
	{
		if ($aName_file[$i] != "") {
			$parametres['content_type'] = get_mimeType(get_extension($aName_file[$i]));
			$parametres['encoding']     = 'base64';
			$parametres['cid']     		= 'part'.$aName_file[$i];
			$parametres['disposition']  = 'inline';
			$parametres['dfilename']    = $aName_file[$i];
			//echo $attachPath.$aName_file[$i];
			if (is_file($attachPath.$aName_file[$i])){
				$fs = fopen($attachPath.$aName_file[$i],'r');
				$attachPathBody = "";
				while(!feof($fs)) {
					$attachPathBody.=fgets($fs);
				}
			$pieceJointe =& $email->addSubPart($attachPathBody, $parametres);
			}
			
		}
	}



	$message = $email->encode(); 


	
 	$destinataire = $to;
	error_log("to = $destinataire");
	
	$entetes = $message['headers'];
	$from = ereg_replace("(.*) <(.*)>", "\"\\1\" <\\2>", $from);

	$entetes["From"]= $from;
	

	$entetes["Return-Path"] = $from;
	$entetes["To"] = $to;
	$entetes["Subject"] = $sujet;
	$entetes["MIME-Version"] = "1.0";
	if ($replyto !='') $entetes["Reply-To"] = $replyto;
	 
	$params = array(
	  "host" => $host,
	  "port" => DEF_MAIL_PORT 
	);
	
	if (DEF_MAIL_LOGIN != ''){
		$params['auth'] = true;
		$params['username'] = DEF_MAIL_LOGIN;
		if (DEF_MAIL_PASS != ''){
			$params['password'] = DEF_MAIL_PASS;
		}		
	}
	else {
		$params['auth'] = false;
	}

	$mailObj = &Mail::factory(DEF_MAIL_ENGINE, $params);
	
	

	$result = $mailObj->send($destinataire, $entetes, $message['body']);
	if($result!=true) {
		error_log($result->getMessage());
		return false;
	} else {
		return true;
	}	 
}




function get_mimeType($ext)
{
   if ($ext == "pdf"){
   		$mimeType = "application/pdf";
   }
   elseif ($ext == "gif"){
   		$mimeType = "image/gif";
   }
   elseif ($ext == "jpg"){
   		$mimeType = "image/jpeg";
   }
   else{
   		$mimeType = "unkown/forcedownload";
   }
   return $mimeType;
}


function getHTMLTemplate ($file, $idsite = 0) {
	
	if ($idsite != 0) {
	
		$site = new Cms_site ($idsite);
		$url = 'http://'.$_SERVER['HTTP_HOST'].'/backoffice/';
		$desc = $site->get_desc();
		$rep = $site->get_rep();
		
	} 
	
	$dirTemplateSite_HTML = $_SERVER['DOCUMENT_ROOT']."/custom/template/".$rep."";
	$dirTemplate_HTML = $_SERVER['DOCUMENT_ROOT']."/backoffice/cms/template";
	
	dirExists($dirTemplateSite_HTML);
	dirExists($dirTemplate_HTML); 
			  
			
	if (is_file ($dirTemplateSite_HTML."/".$file)) {
		$fh = @fopen($dirTemplateSite_HTML."/".$file,'r');
		if ($fh){
			while(!feof($fh)) {
				$sBodyHTML.=fgets($fh);
			}
		}
		else{
			echo '<p><strong>pas de template disponible</strong> à l\'adresse : <br />'.$dirTemplateSite_HTML."/".$file.'</p>';
		}
		
	}
	else if (is_file ($dirTemplate_HTML."/".$file)) {
		$fh = @fopen($dirTemplate_HTML."/".$file,'r');
		if ($fh){
			while(!feof($fh)) {
				$sBodyHTML.=fgets($fh);
			}
		}
		else{
			echo '<p><strong>pas de template disponible</strong> à l\'adresse : <br />'.$dirTemplateSite_HTML."/".$file.'</p>';
		}
	} 
	// message text
	else {
		$sBodyHTML = $message_html;
	}
	 
	$sBodyHTML = rewriteNewsletterBody($sBodyHTML, 0, 0, 0, 0, 0, 1); 
	
	return $sBodyHTML;
 
}


function getTXTTemplate ($file, $idsite = 0) {
	
	if ($idsite != 0) {
	
		$site = new Cms_site ($idsite);
		$url = 'http://'.$_SERVER['HTTP_HOST'].'/backoffice/';
		$desc = $site->get_desc();
		$rep = $site->get_rep();
		
	} 
	
	$dirTemplateSite_HTML = $_SERVER['DOCUMENT_ROOT']."/custom/template/".$rep."";
	$dirTemplate_HTML = $_SERVER['DOCUMENT_ROOT']."/backoffice/cms/template";
	
	dirExists($dirTemplateSite_HTML);
	dirExists($dirTemplate_HTML); 
			  
			
	if (is_file ($dirTemplateSite_HTML."/".$file)) {
		$fh = @fopen($dirTemplateSite_HTML."/".$file,'r');
		if ($fh){
			while(!feof($fh)) {
				$sBodyTXT.=fgets($fh);
			}
		}
		else{
			echo '<p><strong>pas de template disponible</strong> à l\'adresse : <br />'.$dirTemplateSite_HTML."/".$file.'</p>';
		}
		
	}
	else if (is_file ($dirTemplate_HTML."/".$file)) {
		$fh = @fopen($dirTemplate_HTML."/".$file,'r');
		if ($fh){
			while(!feof($fh)) {
				$sBodyTXT.=fgets($fh);
			}
		}
		else{
			echo '<p><strong>pas de template disponible</strong> à l\'adresse : <br />'.$dirTemplateSite_HTML."/".$file.'</p>';
		}
	} 
	// message text
	else {
		$sBodyTXT = $message_txt;
	}
	 
	$sBodyTXT = rewriteNewsletterBody($sBodyTXT, 0, 0, 0, 0, 0, 1); 
	
	return $sBodyTXT;
 
}
?>