<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_cms.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/include_class.php');  

$translator =& TslManager::getInstance(); 	
	
if(is_post('password_request')){
	$emaillogin = inputFilter($_POST["password_email"]);
	
	if ($emaillogin != "") { 

		if (isEmail($emaillogin)){
			$sql = "select * from bo_users where user_login = '".$emaillogin."' OR user_mail = '".$emaillogin."';";
		}
		elseif (isLogin($emaillogin)){
			$sql = "select * from bo_users where user_login = '".$emaillogin."';";			
		}
		else{
			echo 0;
			error_log('hack attempt from '.$_SERVER['REMOTE_ADDR'].' onto bo_password.php ');
		}

		$aUser = dbGetObjectsFromRequete('bo_users', $sql); 
		
		if ($aUser	&&	(sizeof ($aUser) > 0)) {						
			
			$user = $aUser[0] ;
			
			$email = $user->get_mail();
			if (trim($email)!=''){
			
				$new_pass = makeRandomKey ('alpha', 7);
				
				$user->set_passwd(password_hash($new_pass, PASSWORD_DEFAULT));
				
				$r = dbUpdate ($user); 
				
				if (isset($_SESSION["idSite"]) && $_SESSION ["idSite"]!= '') {
					$idSite = $_SESSION ["idSite"];
				}
				else {
					$sql = " SELECT MIN(cms_id) FROM cms_site";
					$idSite = (int)dbGetUniqueValueFromRequete($sql);
				}
				
				$site = new Cms_site ($idsite);
				$url = 'http://'.$_SERVER['HTTP_HOST'].'/backoffice/';
				$desc = $site->get_desc();
				$rep = $site->get_rep(); 
				
				
				
				
				// To send HTML mail, the Content-type header must be set
				$limite = "_----------=_parties_".md5(uniqid (rand()));
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type:  multipart/alternative; charset=iso-8859-1 ;boundary="'.$limite.'"' . "\r\n";  
		
				// Additional headers
				$headers .= 'From: '.DEF_CONTACT_FROM.'' . "\r\n";
				
				$message_html = "<table style='font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 11px; width: 80%; border: 0px solid #000000; padding: 5px;' align='left' cellspacing='0'>";
				//$message_html .= "<tr ><td width='40%'><img src='http://".$_SERVER['HTTP_HOST']."/custom/img/fr/logo_adequat.jpg' alt='logo adequat' /></td><td width='60%'>&nbsp;</td></tr>";					
				$message_html .= "<tr><td colspan='2'><br /><br />"; 
				
				
				$message_html .= "Bonjour,<br /><br />";
				$message_html .= "Vous avez demandé à recevoir un nouveau mot de passe pour vous connecter en toute sécurité à votre outil « ".$desc." ».<br /><br />";
				$message_html .= "Adresse de connexion : <a href='http://".$url."'>http://".$url."</a><br />";
				$message_html .= "Rappel de votre login : ".$user->get_login()." <br />"; 
				$message_html .= "Rappel de votre mot de passe : ".$new_pass." <br /><br />";
				$message_html .= "Vous en souhaitant bonne réception, <br /><br />";
				$message_html .= "Ce message vous est adressé automatiquement. Nous vous remercions de ne pas répondre, ni d'utiliser cette adresse email.<br /><br />"; 
				$message_html .= "</td></tr>";
				$message_html .="</table>"; 
				
		 
				//echo $message_html;
				
				 
				$message_text  = "Bonjour,<br /><br />";
				$message_text .= "Vous avez demandé à recevoir un nouveau mot de passe pour vous connecter en toute sécurité à votre outil « ".$desc." ».<br /><br />";
				$message_text .= "Adresse de connexion : http://".$url."<br />"; 
				$message_text .= "Rappel de votre logi : ".$user->get_login()."\n"; 
				$message_text .= "Rappel de votre mot de passe : ".$new_pass."\n\n\n";
				$message_text .= "Vous en souhaitant bonne réception, \n\n";
				$message_text .= "Ce message vous est adressé automatiquement. Nous vous remercions de ne pas répondre, ni d'utiliser cette adresse email.\n\n";   
		
		
		
				//echo $message_text;
				
				//-----------------------------------------------
				 //MESSAGE TEXTE
				 //-----------------------------------------------
				 $message = "";
				 $message .= "--".$limite."\n";
				 $message .= "Content-Type: text/plain\n";
				 $message .= "charset=\"iso-8859-1\"\n";
				 $message .= "Content-Transfer-Encoding: 8bit\n\n";
				 $message .= $message_text."\n\n";
			
				 //-----------------------------------------------
				 //MESSAGE HTML
				 //-----------------------------------------------
				 $message .= "\n\n--".$limite."\n";
				 $message .= "Content-Type: text/html; ";
				 $message .= "charset=\"iso-8859-1\"; ";
				 $message .= "Content-Transfer-Encoding: 8bit;\n\n";
				 $message .= $message_html."\n\n";
			 
				
				
				$message .= "\n--".$limite."--";
				$message_erreur = "Votre compte a bien été mis à jour."; 
				
				
				
				
				$fileHTML = 'bopassword_template.html';
				$fileTXT = 'bopassword_template.txt';
				
				$sBodyHTML = getHTMLTemplate ($fileHTML, $idSite); 
				$sBodyHTML = str_replace("XX-DESC-XX", $desc, $sBodyHTML); 
				$sBodyHTML = str_replace("XX-URL-XX", $url, $sBodyHTML); 
				$sBodyHTML = str_replace("XX-LOGIN-XX", $user->get_login() , $sBodyHTML); 
				$sBodyHTML = str_replace("XX-MDP-XX", $new_pass, $sBodyHTML);  	
				
				
				 
				$sBodyTXT = getTXTTemplate ($fileTXT, $idSite); 
				$sBodyTXT = str_replace("XX-DESC-XX", $desc, $sBodyTXT); 
				$sBodyTXT = str_replace("XX-URL-XX", $url, $sBodyTXT); 
				$sBodyTXT = str_replace("XX-LOGIN-XX", $user->get_login(), $sBodyTXT); 
				$sBodyTXT = str_replace("XX-MDP-XX", $new_pass, $sBodyTXT); 
				 
			 
				echo preg_replace('/.{2}@.{2}/msi', '**@**', $email);
				
				//echo '<p>Votre mot de passe a été envoyé à l\'adresse suivante : <br />'.$email.'.</p>'; 
				// Mail it
				//mail($user->get_email(), $subject, $message, $headers); 
				
				// subject
				if (defined("DEF_SUBJECT_BO_PASSWORD") &&  (DEF_SUBJECT_BO_PASSWORD)){
					$subject = DEF_SUBJECT_BO_PASSWORD; 
				}
				else {
					$subject = 'Backoffice '.$_SERVER['HTTP_HOST'].' : votre mot de passe'; 
				}
				
				
				
				if (defined("DEF_CONTACT_FROM_BO_PASSWORD") &&  (DEF_CONTACT_FROM_BO_PASSWORD)){
					$from = DEF_CONTACT_FROM_BO_PASSWORD;
				}
				else {
					$from = DEF_CONTACT_FROM;
				}
				  
				multiPartMail_file($email, $subject , $sBodyHTML, $sBodyTXT, $from, $chemin_destination, $aName_file)	;
				//multiPartMail_file('thao@couleur-citron.com', $subject , $sBodyHTML, $sBodyTXT, $from, $chemin_destination, $aName_file)	;
				
				
				unset($_POST["do"]);
				unset($_POST["password_email"]); 
			}
			else{ // pas de mail en db pour ce login
				echo 0;
			}
		}
		else {
			echo 0;
			//echo 'Aucun compte n\'a été créé avec cet email.';
		}		
				
				
				
		
	}
		
	
	}
	else {
	
	?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>Identification backoffice</title>
	<script src="/backoffice/cms/js/fojsutils.js" type="text/javascript"></script>
	<script src="/backoffice/cms/js/validForm.js" type="text/javascript"></script>
	<script src="/backoffice/cms/js/cufon-yui.js" type="text/javascript"></script>
	<script src="/backoffice/cms/js/Helvetica_Neue.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/backoffice/cms/css/login-bo.css" type="text/css"></link>
	<script src="<?php echo $URL_ROOT;?>/backoffice/cms/js/jquery-1.6.4.min.js" type="text/javascript"></script>
	<!-- appel d'une autre feuille de style pour l'accueil-->
	<?php
		if (getCount_where("cms_site", array("cms_url"), array($_SERVER["HTTP_HOST"]), array("TEXT"))) {	
			$sql = "select * from cms_site where cms_url ='".$_SERVER["HTTP_HOST"]."' order by cms_id";
			$aSite = dbGetObjectsFromRequete("cms_site", $sql);
			$oSite = $aSite[0];  
			$_SESSION['idSite'] = $oSite->get_id();
			if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/css/home_".strtolower($oSite->get_name()).".css")){ ?>
				<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"];?>/custom/css/home_<?php echo strtolower($oSite->get_name()); ?>.css" type="text/css"></link>
			<?php
			}
			if (is_file($_SERVER['DOCUMENT_ROOT']."/custom/css/bo_".strtolower($oSite->get_name()).".css")){ ?>
				<link rel="stylesheet" href="http://<?php echo $_SERVER["HTTP_HOST"];?>/custom/css/bo_<?php echo $oSite->get_name(); ?>.css" type="text/css"></link>
				<?php
			}
		}
		
		// fancybox
		if (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js')){
			echo '	<script src="/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js" type="text/javascript"></script>
		<link href="/backoffice/cms/lib/fancybox-1.3.4/jquery.fancybox-1.3.4.css" media="screen" rel="stylesheet" type="text/css" /> ';
		}
		elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.pack.js')){
			echo '	<script src="/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>
		<link href="/backoffice/cms/lib/fancybox-1.3.1/jquery.fancybox-1.3.1.css" media="screen" rel="stylesheet" type="text/css" /> ';
		}
		elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.pack.js')){
			echo '	<script src="/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>
		<link href="/backoffice/cms/lib/fancybox/jquery.fancybox-1.3.1.css" media="screen" rel="stylesheet" type="text/css" /> ';
		}
		else{
			// ben, rien.
		}
	?>
	<script src="/backoffice/cms/js/cufon-yui.js" type="text/javascript"></script>
	<script src="/backoffice/cms/js/Helvetica_Neue.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">
	
		
		Cufon.replace('#center_fancybox h2', {fontFamily: 'Helvetica Neue LT Std'})
		Cufon.replace('#center_fancybox p.valider a', {fontFamily: 'Helvetica Neue LT Std'})

		function checkValidMail (val, empty) {
			if (empty && val == '')
				return true;
			return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i.test(val);
		
		}
		
		
		function validPasswordForm(){
			var erreur=0;
			var lib="Champs Obligatoires : \n";
		
			if(document.password_form.password_email.value==""){
				erreur++;
				lib ="<?php $translator->echoTransByCode('Vous_devez_saisir_votre_identifiant_login'); ?>\n";	
				var el = document.getElementById('blocerreur_email');
				el.style.display="block";	
				el.innerHTML = lib; 	
			}
			/*else  if (!checkValidMail(document.password_form.password_email.value, false)) { 
				erreur++;
				lib ="La syntaxe de l\'email est incorrecte\n";	
				var el = document.getElementById('blocerreur_email');
				el.style.display="block";	
				el.innerHTML = lib; 	
			}
			*/
			else {
				lib ="";	
				var el = document.getElementById('blocerreur_email');
				el.style.display="none";	
				el.innerHTML = lib; 	
			}	
			if(erreur>0){
				//el = document.getElementById('blocerreur_all');	
				//el.innerHTML = "Veuillez renseigner tous les champs obligatoires"; 	
			}
			else { 
				//document.password_form.do.value = "send_password";
				//document.password_form.submit();
				
				var frm = document.forms['password_form']; 
				var password_email = frm.password_email.value; 	 
			
			
				$.ajax({
					type	: "POST", 
					data	: { "password_email" : password_email, "password_request" : "true"} ,
					url	: "/include/cms-inc/bo_password.php",
					success	: function(_data) {
						//alert(_data);
						
						if (_data == 0) {
							$('#blocerreur_email').css('display', 'block');
							$('#blocerreur_email').html('Aucun compte n\'a été créé avec cet identifiant.');
						}
						else  {
							$('#center_fancybox').html('<p>Votre mot de passe a été envoyé à l\'adresse suivante : <br />'+_data+'</p>');
						}	
					}
				});
			}
		}
		</script> 
	</head>
	<body style="background-color: #697993;">
	<div id="center_fancybox"> 	
	<form id="password_form" name="password_form" method="POST">
		<h2><?php $translator->echoTransByCode('Mot_de_passe_oublie'); ?></h2>
		<input type="hidden" name="do" id="do" value="send_password"/>
		<p><label for="password_email"><?php $translator->echoTransByCode('Identifiant'); ?></label><input type="text" name="password_email" id="password_email" /> <span id="blocerreur_email" class="erreur_form"></span> </p>		
		<p class="valider"><a class="valider" href="#" onclick="validPasswordForm();"><?php $translator->echoTransByCode('Valider'); ?></a></p>
	</form>  
	</div>
	</body>
	</html>
	<?php
	 
	}
	
	?>