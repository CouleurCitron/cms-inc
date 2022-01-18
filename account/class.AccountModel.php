<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données de compte

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_client.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_adresse.class.php');

// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');


class AccountModel extends BaseModuleModel {


	// constructor
	function AccountModel () {}


	function isValidAccount ($mail, $pass) {
		$sql = "	SELECT	count(*)
			FROM	shp_client
			WHERE	shp_clt_email = '{$mail}'
			AND	shp_clt_pwd = '{$pass}';";
		if (dbGetUniqueValueFromRequete($sql) > 0)
			return true;
		else	return false; 
	}  


	function isExistingAccount ($mail) {
/*		if ($mail!='' && preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $mail)) {
		$sql = "	SELECT	count(*)
			FROM	shp_client
			WHERE	shp_clt_email = '{$mail}';"; 
			
			echo $sql;
		if (dbGetUniqueValueFromRequete($sql) > 0)
			return true;
		else	return false;  
		}
		else {
			return false;  
		}*/
		$sql = "	SELECT	count(*)
			FROM	shp_client
			WHERE	shp_clt_email = '{$mail}';";
		if (dbGetUniqueValueFromRequete($sql) > 0)
			return true;
		else	return false; 

	}

	
	function isUniqueAccount ($mail) {
		$aCustomer = dbGetObjectsFromFieldValue('shp_client', Array('get_email'), Array($mail), '');
		if (newSizeOf($aCustomer) == 1)
			return true;
		elseif (newSizeOf($aCustomer) == 0)
			return null;
		else	return false;  
	}

	
	function getAccount ($mail) {
		$aCustomer = dbGetObjectsFromFieldValue('shp_client', Array('get_email'), Array($mail), '');
		if (newSizeOf($aCustomer) > 0)
			return $aCustomer[0];
		else	return null;
	}
	
	
	function getAccountByKey ($key) {
		$aCustomer = dbGetObjectsFromFieldValue('shp_client', Array('get_act_key'), Array($key), '');
		if (newSizeOf($aCustomer) > 0)
			return $aCustomer[0];
		else	return null;
	}


	function getAuthAccount ($mail, $pass) {
		if (defined('ACCOUNT_PWD_ENCRYPT')){
			// apply encryption
			$pass = $this->applyEncryption($pass);
		}
		$aCustomer = dbGetObjectsFromFieldValue('shp_client', Array('get_email', 'get_pwd', 'get_statut'), Array($mail, $pass, DEF_ID_STATUT_LIGNE), '');
		if (newSizeOf($aCustomer) > 0)
			return $aCustomer[0];
		else	return null;
	}


	function getOutdatedAccounts () {
		$length = 1;
		$sql = "	SELECT	*
			FROM	shp_client
			WHERE	shp_clt_statut = ".DEF_CODE_STATUT_DEFAUT."
			AND	TIMESTAMPDIFF(MONTH, shp_clt_last_reminded, NOW()) >= {$length}
			AND	TIMESTAMPDIFF(MONTH, shp_clt_last_connected, NOW()) >= {$length};";
		
		return dbGetObjectsFromRequete("shp_client", $sql);
	}


	function getOutdatingAccounts () {
		$r_length = 1;
		$c_length = 3;
		$sql = "	SELECT	*
			FROM	shp_client
			WHERE	shp_clt_statut = ".DEF_CODE_STATUT_DEFAUT."
			AND	(TIMESTAMPDIFF(MONTH, shp_clt_last_reminded, NOW()) >= {$r_length} OR shp_clt_last_reminded = '0000-00-00 00:00:00')
			AND	TIMESTAMPDIFF(YEAR, shp_clt_last_connected, NOW()) >= {$c_length};";
		
		return dbGetObjectsFromRequete("shp_client", $sql);
	}


	function getAddress ($account, $type='commune') {
		$aAddress = dbGetObjectsFromFieldValue('shp_adresse', Array('get_id_client', 'get_type'), Array($account->get_id(), $type), '');
		if (newSizeOf($aAddress) > 0)
			return $aAddress[0];
		else	return null;
	}


	function createAccount ($a_account) { 
		$account = new shp_client ();
		foreach ($a_account as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			if ($champ == 'pwd' && defined('ACCOUNT_PWD_ENCRYPT'))
				// password encryption
				$value = $this->applyEncryption($value);
			//$account->$setter($value);
			if ( method_exists ( $account , $setter ) ) $account->$setter($value);
		}
		
		if (dbInsertWithAutoKey($account))
			return $account;
		else	return null;
	}
	

	function createAddress ($a_address) {
		
		$address = new shp_adresse ();
		foreach ($a_address as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$address->$setter($value);
		}
		if (dbInsertWithAutoKey($address))
			return $address;
		else	return null;
	}


	function updateAccount ($a_account, $account=null) { 
		if ($account == null)
			$account = new shp_client($a_account['id']);
		foreach ($a_account as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			if ($champ == 'pwd') {
				if (defined('ACCOUNT_PWD_ENCRYPT'))
					// password encryption
					$test = $this->applyEncryption($value);
				else	$test = $value;
				if ($test != $account->get_pwd())
					$value = $test;
				else	$value = $account->get_pwd();
			}
			if ( method_exists ( $account , $setter ) ) $account->$setter($value);
		}
		if (dbUpdate($account)) {
			if ($this->debug)
				echo "AccountModel.updateAccount > account was updated : ".$account->get_id()."<br/>";
			return $account;
		} else	return false;
	}
	
	
	function updateAddress ($a_address, $address=null) {
		
		if ($address == null)
			$address = new shp_adresse($a_address['id']);
		//viewArray($_POST);
		//viewArray($a_address);
		foreach ($a_address as $champ => $value) {
			//echo $champ." ".$value."<br />";
			$setter = 'set_'.$champ;
			$address->$setter($value);
		}
		if (dbUpdate($address)) {
			if ($this->debug)
				echo "AccountModel.updateAddress > account address was updated : ".$address->get_id()."<br/>";
			return $address;
		} else	return false;
	}
	

	function deleteAccount($account) {
	
	 	if ($account != null) {
			if ($this->debug)
				echo "AccountModel.deleteAccount > compte client supprimé : ".$account->get_id()."<br/>";
			return dbDelete($account);
		} else	return false;
	}


	function deleteAddress($address) {
	
	 	if ($address != null) {
			if ($this->debug)
				echo "AccountModel.deleteAddress > adresse client supprimée : ".$address->get_id()."<br/>";
			return dbDelete($address);
		} else	return false;
	}


	function applyEncryption ($value) {		
		switch (ACCOUNT_PWD_ENCRYPT) {
			case 'HASH' :	$value = password_hash($value, PASSWORD_DEFAULT);
					break;
			case 'MD5' :	$value = md5($value);
					break;
			case 'SHA1' :	$value = sha1($value);
					break;
		}
		return $value;
	}


	function getCountryPile ($lang, $all = false) {
		global $db, $country_pile;

		$pile = Array();
		$sql = "	SELECT	cms_pay_id as id,
				cms_pay_nom_{$lang} as name
			FROM	`cms_pays`";
			
		if ($all) {	
			if (newSizeOf($country_pile) > 0)
				$sql .= "
				WHERE	cms_pay_id IN (".implode(',', $country_pile).")
				";
		}
		$sql .= "ORDER BY cms_pay_nom_{$lang} ASC;";
		if ($this->debug)
			echo "AccountModel.getCountryPile : SQL > ".$sql."<br/>";
		$rs = $db->Execute($sql);
		$pile = Array();
		if ($rs) {
			while(!$rs->EOF) {
				$pile[] = Array(	'id'	=> $rs->fields['id'],
						'name'	=> $rs->fields['name'] );
				$rs->MoveNext();
			}
		}
		return (Array) $pile;
	}
	
	
	function getShippingCountryPile ($lang, $all = false) {
		global $db, $country_pile;

		$pile = Array();
		$sql = "	SELECT	DISTINCT cms_pay_id as id,
				cms_pay_nom_{$lang} as name
			FROM	`cms_pays`, shp_frais_port_grille";
		
		$aWhere = array ();
			
		$aWhere[]= " shp_frais_port_grille.shp_fpg_id_pays = cms_pay_id";
		
		if ($all) {	
			if (newSizeOf($country_pile) > 0)
				$aWhere[]= " 	cms_pay_id IN (".implode(',', $country_pile).") ";
		}
		
		if (newSizeOf($aWhere) > 0) $sql .= " WHERE (".implode(' AND ', $aWhere).") ";
		
		$sql .= "ORDER BY cms_pay_nom_{$lang} ASC;";
		
		//echo $sql;
		if ($this->debug)
			echo "AccountModel.getCountryPile : SQL > ".$sql."<br/>";
		$rs = $db->Execute($sql);
		$pile = Array();
		if ($rs) {
			while(!$rs->EOF) {
				$pile[] = Array(	'id'	=> $rs->fields['id'],
						'name'	=> $rs->fields['name'] );
				$rs->MoveNext();
			}
		}
		return (Array) $pile;
	}


	function getCountryLibelle ($lang, $id_pays) {
		
		$sql = "	SELECT	*
			FROM	`cms_pays`
			WHERE	cms_pay_id = ".$id_pays.";";

		$aPays = dbGetObjectsFromRequete('cms_pays', $sql); 
		if (newSizeOf($aPays) > 0) {
			eval ("$"."libelle =". "$"."aPays[0]->"."get_nom_".$lang."();");
			return $libelle;
		} else	return false;	 
	}


/*
	function createAddress($id_address) {
	  
		$res = $this->isExistsAdress();
		
		if ($id_address!="") {
			echo "j'update mon adress";
			$address = new shp_adresse($id_address);
			$address->set_id_client($_POST["id_client"]);
			$address->set_id_pays($_POST['account_country']);
			$address->set_civilite($_POST['account_gender']); 
			$address->set_nom($_POST['account_lastname']);
			$address->set_prenom($_POST['account_firstname']);
			$address->set_societe($_POST['account_company']);
			$address->set_tel($_POST['account_telephone']);
			$address->set_detail_1($_POST['account_addr_1']);
			$address->set_detail_2($_POST['account_addr_2']);
			$address->set_detail_3($_POST['account_addr_3']);
			$address->set_ville($_POST['account_city']);
			$address->set_cp($_POST['account_zipcode']);
			$address->set_commentaires($_POST['account_commentaires']);
			$address->set_statut(DEF_ID_STATUT_LIGNE);
			$a_id = dbUpdate($address);
			$_SESSION['order']['cust_address'] = $a_id;
			
			if ($this->debug)
				echo "### nouvelle adresse de facturation : {$a_id}<br/>";
			
		} else if (!$res && $id_address == "") {

			echo "<br />je crée mon adresse ".DEF_ID_STATUT_LIGNE." <br /> ";
			$address = new shp_adresse();
			$address->set_id_client($_POST["id_client"]);
			$address->set_id_pays($_POST['account_country']);
			$address->set_civilite($_POST['account_gender']);
			$address->set_type('commune'); 
			$address->set_nom($_POST['account_lastname']);
			$address->set_prenom($_POST['account_firstname']);
			$address->set_societe($_POST['account_company']);
			$address->set_tel($_POST['account_telephone']);
			$address->set_detail_1($_POST['account_addr_1']);
			$address->set_detail_2($_POST['account_addr_2']);
			$address->set_detail_3($_POST['account_addr_3']);
			$address->set_ville($_POST['account_city']);
			$address->set_cp($_POST['account_zipcode']);
			$address->set_commentaires($_POST['account_commentaires']);
			$address->set_statut(DEF_ID_STATUT_LIGNE);
			
			
			$sql = "select count(*) from shp_adresse where shp_adr_id_client = '".$_POST["id_client"]."' "; 
			$eCount = dbGetUniqueValueFromRequete($sql); 
			
			if ($eCount == 0) $address->set_isaddressdefault(1);
			else $address->set_isaddressdefault(0);
			
			
			$a_id = dbInsertWithAutoKey($address);
			$_SESSION['order']['cust_address'] = $a_id;
			
			if ($this->debug)
				echo "### nouvelle adresse de facturation : {$a_id}<br/>";
				
			
		} else if ($res && $id_address == "") {
			
			$a_id = $res[0]->get_id();
			echo "je crée pas mon adress".$a_id;
			$_SESSION['order']['cust_address'] = $a_id;
		}
		
		return $a_id;	
		
		/*if ($_POST['ship_to_default'] != 'true') {
			$address = new shp_adresse();
			$address->set_id_client($c_id);
			$address->set_id_pays($_POST['shipto_country']);
			$address->set_civilite($_POST['shipto_gender']);
			$address->set_type('expédition');
			$address->set_nom($_POST['shipto_lastname']);
			$address->set_prenom($_POST['shipto_firstname']);
			$address->set_societe($_POST['shipto_company']);
			$address->set_tel($_POST['shipto_telephone']);
			$address->set_detail_1($_POST['shipto_addr_1']);
			$address->set_detail_2($_POST['shipto_addr_2']);
			$address->set_detail_3($_POST['shipto_addr_3']);
			$address->set_ville($_POST['shipto_city']);
			$address->set_cp($_POST['shipto_zipcode']);
			$address->set_statut(4);
			$a_id = dbInsertWithAutoKey($address);
			$_SESSION['order']['ship_address'] = $a_id;
			if ($this->debug)
				echo "### nouvelle adresse d'expédition : {$a_id}<br/>";
		}*/
		
/*		 
	}
	*/

}

?>
