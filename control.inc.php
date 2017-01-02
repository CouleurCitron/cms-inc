<?php
function check_field ($_POST) {	 
	$aError = array (); 
	$is_form_ok = true; 
	if (preg_match ( "/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER'])  && $_POST["protect"] == '' )   {
		if (is_post("fields_control")) {
			$fields_control = explode (",", $_POST["fields_control"]);			  
			
		}
		else{ // fallback si pas de $_POST["fields_control"]
			$fields_control=array();
			foreach($_POST as $k => $v){
				$fields_control[]=$k;
			}
		}		
		
		if (isset($fields_control)){		
			foreach ($fields_control as $field_control ) {				 
				eval ('$'.'my_field = '.'$'.'_POST["'.$field_control.'"];'); 
				$my_field = trim ($my_field) ;
				//$my_field = mb_convert_encoding($my_field, "ISO-8859-1", "auto");
				
				if ($field_control!='') {					
					if (preg_match ('/country/', $field_control)) {					 	
						if ($my_field == -1 || $my_field == '') {
							array_push($aError, "country vide");
							$is_form_ok = false; 
						}
					 }
					 elseif (preg_match ('/pays/', $field_control)) { 
						if ($my_field == '' || $my_field == -1) { 
							array_push($aError, "pays == -1 ");
							$is_form_ok = false; 
						}
					 } 
					elseif (preg_match ('/prenom/', $field_control)) {					 	
						if ($my_field == '') {
							array_push($aError, "prenom vide");
							$is_form_ok = false; 
						}
						elseif (!preg_match('/^(\pL+[\.\- \']?)*$/msiu', $my_field)){
							array_push($aError, "prenom non valide = ".$my_field);
							$is_form_ok = false; 
						}
						if (!preg_match('/[aeuioyéàèêâôîûAEUIOYÔÛÊÂ]+/msi', $my_field)){
							array_push($aError, "prenom non valide - aucune voyelle");
							$is_form_ok = false; 
						}
						if (preg_match('/^[a-z\-]+\/[a-z\-\/]+\.[a-z]+$/msi', $my_field)){
							array_push($aError, "prenom non valide - file path");
							$is_form_ok = false; 
						}
					 }
				 	elseif (preg_match ('/nom/', $field_control)) {					 	
						if ($my_field == '') {
							array_push($aError, "nom vide");
							$is_form_ok = false; 
						}
						elseif (!preg_match('/^(\pL+[\.\- \']?)*$/msiu', $my_field)){
							array_push($aError, "nom non valide = ".$my_field);
							$is_form_ok = false; 
					 } 
						if (!preg_match('/[aeuioyéàèêâôîûAEUIOYÔÛÊÂ]+/msi', $my_field)){
							array_push($aError, "nom non valide - aucune voyelle");
							$is_form_ok = false; 
						}
						if (preg_match('/^[a-z\-]+\/[a-z\-\/]+\.[a-z]+$/msi', $my_field)){
							array_push($aError, "prenom non valide - file path");
							$is_form_ok = false; 
						}
					 } 
					elseif (preg_match ('/email/', $field_control)) {
						if ($my_field == '' || !preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", strtolower($my_field))) {
							array_push($aError, "email mauvaise syntaxe");
							$is_form_ok = false; 
						}
						if (preg_match("/^.*tst$/i", strtolower($my_field))) {
							array_push($aError, "email jetable");
							$is_form_ok = false; 
						}						
					 } 
					elseif (preg_match ('/mail/', $field_control)) {
						if ($my_field == '' || !preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", strtolower($my_field))) {
							array_push($aError, "email mauvaise syntaxe");
							$is_form_ok = false; 
						}
						if (preg_match("/^.*tst$/i", strtolower($my_field))) {
							array_push($aError, "email jetable");
							$is_form_ok = false; 
						}	
					 }
					elseif (preg_match ('/tel/', $field_control)	||	preg_match ('/phone/', $field_control)	||	preg_match ('/portable/', $field_control)	||	preg_match ('/^cell$/', $field_control)) { 
						if ($my_field == '123456') { 
							array_push($aError, "phone mauvaise syntaxe");
							$is_form_ok = false; 
						}
						if (preg_match ('/^1234/', $my_field)) { 
							array_push($aError, "tel mauvaise syntaxe 1234");
							$is_form_ok = false; 
						}
						if (strlen($my_field) > 15) { 
							array_push($aError, "tel mauvaise syntaxe > 15");
							$is_form_ok = false; 
						}
						if ($my_field!=''	&&	!preg_match('/^[0-9\-\.\+ ]+$/msi', $my_field)){
							array_push($aError, "tel non valide : ".$field_control." = ".$my_field);
							$is_form_ok = false; 
						}
					 } 
					 else if (preg_match ('/cp/', $field_control)) {					 	
						if ($my_field == '') {
							array_push($aError, "cp vide");
							$is_form_ok = false; 
						}
						elseif (preg_match('/^[0-9AB]{4,5}$/msi', $my_field)){

							// zip code fr
						}
						elseif( preg_match('/^\d{5}(?:\-\d{4})?$/i', $my_field) ||   preg_match('/^[a-z]\d[a-z] ?\d[a-z]\d$/i', $my_field) ||  preg_match('/^[a-z]{1,2}\d{1,2} ?\d[a-z]{2}$/i', $my_field)){
							// zip code us ca uk
						}
						else{
							array_push($aError, "cp non valide : ".$my_field);
							$is_form_ok = false; 
						}
					 } 
					elseif (preg_match ('/adresse$/', $field_control)) {					 	
						/*if ($my_field == '') {
							array_push($aError, "adresse vide");
							$is_form_ok = false; 
						}*/
						if (preg_match('/^[0-9]+$/msi', $my_field)){
							array_push($aError, "adresse non valide - only numbers");
							$is_form_ok = false; 
						}
					 } 
					 /*else if (preg_match ('/adresse2/', $field_control)) {					 	
						if (preg_match('/^[0-9]+$/msi', $my_field)){
							array_push($aError, "adresse2 non valide - only numbers");
							$is_form_ok = false; 
						}
					 } */
					 else if (preg_match ('/civilite/', $field_control)) { 
						if ($my_field == '' || $my_field == -1) {
							array_push($aError, "civilite == -1 ");
							$is_form_ok = false; 
						}
					 }  
					 else {
						if (($my_field == '') && is_post("fields_control"))  {
							array_push($aError, $field_control. " : champs vide");
							$is_form_ok = false; 
						}
					 }
				}
			}						
		}		
		
		// test si les noms et prénoms sont différents FR
		if (isset ($_POST["nom"]) && isset ($_POST["prenom"]) && $_POST["nom"] != ''  && $_POST["prenom"] != ''  ) {
			if ( $_POST["nom"] == $_POST["prenom"] ) {
				array_push($aError, " nom & last_name identiques");
				$is_form_ok = false;  
			}
		} 
		if (isset ($_POST["nom"]) && $_POST["nom"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["prenom"])) {
				array_push($aError, "nom mauvaise syntaxe");
				$is_form_ok = false; 
			}
		 }  
		 if (isset ($_POST["prenom"]) && $_POST["prenom"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["prenom"])) {
				array_push($aError, "prenom mauvaise syntaxe");
				$is_form_ok = false; 
			}
		 } 		
		// test si les noms et prénoms sont différents 
		if (isset ($_POST["name"]) && isset ($_POST["last_name"]) && $_POST["name"] != ''  && $_POST["last_name"] != ''  ) {
			if ( $_POST["name"] == $_POST["last_name"] ) {
				array_push($aError, " name & last_name identiques");
				$is_form_ok = false;  
			}
		}
		if (isset ($_POST["account_firstname"]) && isset ($_POST["account_lastname"]) && $_POST["account_firstname"] != ''  && $_POST["account_lastname"] != ''  ) {
			if ( $_POST["account_firstname"] == $_POST["account_lastname"] ) {
				array_push($aError, " account_firstname & account_lastname identiques");
				$is_form_ok = false;  
			}
		}
		if (isset ($_POST["phone"]) && $_POST["phone"] != ''  ) {
			if (!preg_match("/[0-9]+/i", $_POST["phone"]) && $_POST["phone"]!= '123456' ) { 
				array_push($aError, "phone non oblig mauvaise syntaxe ");
				$is_form_ok = false;  
			}
		}
		if (isset ($_POST["tel"]) && $_POST["tel"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["tel"])) { 
				array_push($aError, "tel mauvaise syntaxe");
				$is_form_ok = false; 
			}
			if (preg_match ('/1234/',$_POST["tel"])) { 
				array_push($aError, "tel mauvaise syntaxe 1234");
				$is_form_ok = false; 
			}
			if (strlen($_POST["tel"]) > 15) { 
				array_push($aError, "tel mauvaise syntaxe > 15");
				$is_form_ok = false; 
			}
		} 
		if (isset ($_POST["account_telephone"]) && $_POST["account_telephone"] != ''  ) {
			if (!preg_match("/[0-9]+/i", $_POST["account_telephone"]) && $_POST["account_telephone"]!= '123456' ) { 
				array_push($aError, "account_telephone non oblig mauvaise syntaxe ");
				$is_form_ok = false;  
			}
		}
		if (isset ($_POST["zipcode"]) && $_POST["zipcode"] != ''  ) {
			if (!preg_match("/[0-9]+/i", $_POST["zipcode"]) && $_POST["zipcode"] != '123456') { 
				array_push($aError, "zipcode non oblig mauvaise syntaxe ");
				$is_form_ok = false;  
			}
		}
		if (isset ($_POST["cp"]) && $_POST["cp"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["cp"])) {
				array_push($aError, "cp mauvaise syntaxe");
				$is_form_ok = false; 
			}
		}
		if (isset ($_POST["ville"]) && $_POST["ville"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["ville"])) {
				array_push($aError, "ville mauvaise syntaxe");
				$is_form_ok = false; 
			}
		}
		if (isset ($_POST["comments"]) && $_POST["comments"] != ''  ) {
			if (preg_match('/viagra/', strtolower($_POST["comments"] )) || preg_match('/url/', strtolower($_POST["comments"] ))) { 
				array_push($aError, "comments mauvaise syntaxe");
				$is_form_ok = false; 
			}
		 }  
		if (isset ($_POST["message"]) && $_POST["message"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["message"])) { 
				array_push($aError, "message mauvaise syntaxe");
				$is_form_ok = false; 
			}
		} 
		if (isset ($_POST["message"]) && $_POST["message"] != ''  ) {
			if (preg_match('/^[0-9]+$/', $_POST["message"])) { 
				array_push($aError, "message mauvaise syntaxe - only number");
				$is_form_ok = false; 
			}
		} 
		if (isset ($_POST["address"]) && $_POST["address"] != ''  ) {
			if (preg_match('/http:\/\//', $_POST["address"])) {
				array_push($aError, "address mauvaise syntaxe");
				$is_form_ok = false; 
			}
			else if (sizeof ($aAdd) < 2) {
				array_push($aError, "address mauvaise syntaxe");
				$is_form_ok = false; 
			}
		}
		if (isset ($_POST["adresse"]) && $_POST["adresse"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["adresse"])) { 
				array_push($aError, "adresse mauvaise syntaxe");
				$is_form_ok = false; 
			}
		} 
		if (isset ($_POST["company"]) && $_POST["company"] != ''  ) {
			if (preg_match('/google/', $_POST["company"])) { 
				array_push($aError, "company mauvaise syntaxe");
				$is_form_ok = false; 
			}
		} 
		if (isset ($_POST["societe"]) && $_POST["societe"] != ''  ) {
			if (preg_match('/google|viagra|url|http:\/\//', $_POST["societe"])) {
				array_push($aError, "societe mauvaise syntaxe");
				$is_form_ok = false; 
			}
		 } 
		if (isset ($_POST["job_title"]) && $_POST["job_title"] != ''  ) {
			if (preg_match('/viagra/', strtolower($_POST["job_title"]))) { 
				array_push($aError, "job_title mauvaise syntaxe");
				$is_form_ok = false; 
			}
		}		
	}
	else {
		array_push($aError, "pas de preg_match _SERVER['HTTP_HOST'], _SERVER['HTTP_REFERER'] / protect vide ");
		$is_form_ok = false;
	}
	
	if (! $is_form_ok )  {
		$spost = 'http://'.$_SERVER["HTTP_HOST"].''.$_SERVER["PHP_SELF"]."<br /><br />";
		foreach ($_POST as $name => $value)  {
			$spost.= $name. ' : ' . $value."<br />";
		}
		$spost.= "<br />";
		$spost.= "Erreur : <br />".join ("<br /> ", $aError )."<br />";
		$spost.= "SERVER['HTTP_HOST'] : ".$_SERVER['HTTP_HOST']."<br />";
		$spost.= "SERVER['HTTP_REFERER'] : ".$_SERVER['HTTP_REFERER']."<br />";
		
		$envoi = multiPartMail('technique@couleur-citron.com', $_SERVER['HTTP_HOST']." form : mauvais remplissage ", $spost, $spost, DEF_CONTACT_FROM_EMAIL, '','','localhost');	
	}
	return $is_form_ok;
}
?>