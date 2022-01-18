<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données client

// Reformule certaines actions du module account

// structures des donnéees de formulaires:
// prefix 'customer_' (compte client), 'shipto_' (expédition en mode non autonome - sans carnet d'adresse)

// - customer_gender / shipto_gender
// - customer_firstname / shipto_firstname
// - customer_lastname / shipto_lastname
// - customer_telephone / shipto_telephone
// - customer_cellphone / shipto_cellphone
// - customer_email
// - customer_password
// - customer_professionnal (Y/N)
// - customer_company
// - customer_birthdate
// - customer_addr_1 / shipto_addr_1
// - customer_addr_2 / shipto_addr_3
// - customer_addr_3 / shipto_addr_3
// - customer_zipcode / shipto_zipcode
// - customer_city / shipto_city
// - customer_country / shipto_country

// - ship_to_default

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/account/class.AccountModel.php');


class CustomerModel extends AccountModel {

	// constructor
	function CustomerModel () {

		//AccountModel::__construct();

	}


	function getAllAccountAddresses ($account) {
		$sql = "	SELECT	*
			FROM	shp_adresse
			WHERE	shp_adr_id_client = '".$account->get_id()."'
			AND	shp_adr_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY	shp_adr_cdate;";  
		//echo $sql."<br/>";
		return dbGetObjectsFromRequete('shp_adresse', $sql); 
	}


	function getCommonAddress ($address) {

		$sql = "	SELECT	count(*)
			FROM	shp_adresse
			WHERE	shp_adr_id_client = ".$address->get_id_client()."
			AND	shp_adr_type = 'commune'
			ORDER BY	shp_adr_cdate;"; 
		//echo $sql."<br/>";
		$eCount = dbGetUniqueValueFromRequete($sql); 
		if ($eCount > 0) {
			$sql = str_replace ("count(*)", "shp_adr_id", $sql);
			$id_common = dbGetUniqueValueFromRequete($sql); 
			return new shp_adresse($id_common);
		} else	return null;
	}

	
	function getOrderingAddress ($customer) {
		$sql = "	SELECT	shp_adr_id
			FROM	shp_adresse
			WHERE	shp_adr_id_client = ".$customer->get_id()."
			AND	(shp_adr_type = 'commune' OR shp_adr_type = 'facturation')
			ORDER BY	shp_adr_cdate;";
		//echo $sql."<br/>";
		$id_ordering = dbGetUniqueValueFromRequete($sql); 
		if ($id_ordering > 0)
			return new shp_adresse($id_ordering);
		else	return null;
	}


	function getDefaultShippingAddress ($customer) {

		$sql = "	SELECT	shp_adr_id
			FROM	shp_adresse
			WHERE	shp_adr_id_client = ".$customer->get_id()."
			AND	(shp_adr_type = 'commune' OR shp_adr_type = 'expédition')
			AND	shp_adr_isaddressdefault > -1
			ORDER BY	shp_adr_cdate;"; 
		//echo $sql."<br/>";
		$id_shipping = dbGetUniqueValueFromRequete($sql); 
		if ($id_shipping > 0)
			return new shp_adresse($id_shipping);
		else	return null;
	}
	
	
	function getAllShippingAddresses ($customer) {

		$sql = "	SELECT	*
			FROM	shp_adresse
			WHERE	shp_adr_id_client = ".$customer->get_id()."
			AND	shp_adr_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY	shp_adr_nom, shp_adr_prenom;";  
		// echo $sql."<br/>";
		$res = dbGetObjectsFromRequete('shp_adresse', $sql);
		if (newSizeOf($res) > 0)
			return $res;
		else	return false;
	}
	

	function setOrderingAddress ($customer, $id_ordering) {
		// get current 'ordering' address type
		$address = $this->getOrderingAddress($customer);
		$type = $address->get_type();
		
		// set all addresses to type 'shipping'
		$sql = "	UPDATE	shp_adresse
		SET	shp_adr_type = 'expédition'
		WHERE	shp_adr_id_client = ".$customer->get_id().";";  
		$success = dbExecuteQuery($sql);
		if ($success) {
			$ordering = new shp_adresse($id_ordering);
			$ordering->set_type($type);
			$ordering->set_mdate(date('Y-m-d H:i:s'));
			$id = dbUpdate($ordering);
			if ($id > 0)
				return true;
			else	return false;
		} else {
			// set back current ordering address as one is required
			$address->set_type($type);
			$id = dbUpdate($address);
			return false;
		}
	}


	function setDefaultShippingAddress ($customer, $id_shipping) {
		
		// clean up all other possible defaults
		$sql = "	UPDATE	shp_adresse
			SET	shp_adr_isaddressdefault = -1
			WHERE	shp_adr_id_client = ".$customer->get_id().";";  
		$success = dbExecuteQuery($sql);
		if ($success) {
			$shipping = new shp_adresse($id_shipping);
			$shipping->set_isaddressdefault(1);
			// if shipping and ordering are the same, verify type is common
			$ordering = $this->getOrderingAddress($customer);
			if ($ordering->get_type() == 'facturation') {
				if ($this->debug)
					echo "CustomerModel.setShippingAddress() is switching ordering address type to 'commune' as is is set to default shipping address<br/>";
				$shipping->set_type('commune');
			}
			$shipping->set_mdate(date('Y-m-d H:i:s'));
			$id = dbUpdate($shipping);
			if ($id > 0)
				return true;
			else	return false;
		} else	return false;
	}


}

?>
