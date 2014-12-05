<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle webshop Customer Account actions
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/account/class.AccountController.php');

// include Customer model
require_once('class.CustomerModel.php');

class CustomerAccountController extends AccountController {

	var $mod_name = 'webshop';

	// constructor
	function CustomerAccountController ($views=null) {

		$this->models['account'] = new CustomerModel();
		if ($views != null && is_array($views))
			$this->views_pile = $views;
	}


	function createAccount () {

		$customer = AccountController::createAccount($this->prepareAccount());
		if ($customer != null)
			$_SESSION['order']['customer'] = $customer->get_id();
		else	echo "Error creating new Account<br/>";
		
		return $customer;
	}


	function createAddress ($account, $a_address=null) { 
		if (is_null($a_address)) {
			if (defined('ACCOUNT_EDITABLE') && ACCOUNT_EDITABLE) {
				$ordering = $this->models['account']->getOrderingAddress($account);
				$a_address = $this->prepareAddress($account, 'commune');
				if (!is_null($ordering))
					$a_address['type'] = 'expédition';
				else	$a_address['isaddressdefault'] = 1;
			} else {
				if ($_POST['ship_to_default'] == 'true')
					$a_address = $this->prepareAddress($account, 'commune');
				else	$a_address = $this->prepareAddress($account, 'facturation');
			}
		}
		return $this->models['account']->createAddress($a_address);
	}


	function updateAccount ($a_customer=null) {
			 
		// viewArray($_POST, 'post');
		//if (in_array($_POST['do'], Array('set_account','update_account','update_address'))) {
			// 'set_account' is generic for non editable customer accounts
			
			if ($_POST['account_email']!='') $myEmail = $_POST['account_email'];
			else if ($_POST['customer_email']!='') $myEmail = $_POST['customer_email'];
			
			if (is_null($a_customer))
				$a_customer = $this->prepareAccount();
               	
			//viewArray($_POST, 'post');
			if (defined('ACCOUNT_EDITABLE') && ACCOUNT_EDITABLE) {
				// generic account
				if ($this->debug)
					echo "AccountController.updateAccount > generic mode for update <br/>";
				$a_customer['id'] = $_POST['id'];
				$customer = $this->models['account']->updateAccount($a_customer);
				if ($this->debug)
					echo "AccountController.updateAccount > customer account was updated : ".$customer->get_id()."<br/>";
				return $customer;

			} else {
				// webshop without public customer account management
				if (!$this->models['account']->isExistingAccount($myEmail) && empty($_SESSION['order'])) {

					$this->createAutoAccount($a_customer);

				} else {
					// auto managed account
					if ($this->models['account']->isUniqueAccount($_POST['account_email'])) {

						$customer = $this->models['account']->getAccount($myEmail);
	
						if (empty($_SESSION['order'])) {
							// new order for existing customer
							$status = 'new_order';
							// check customer validity
							if ($_SESSION['order']['customer'] > 0 && $_SESSION['order']['customer'] != $customer->get_id()) {
								// form email has changed during procedure and new given customer already exist
								echo 'ERROR customer session does not match given email';
								// update email ?
							} else	$_SESSION['order']['customer'] = $customer->get_id();
						} else {
							// back on editing account data
							$status = 'back_in';
							// in case customer email(>account) has changed
							$_SESSION['order']['customer'] = $customer->get_id();
						}
						// update customer account
						$this->models['account']->updateAccount($a_customer, $customer);
	
						// auto handle address
						// possible common address
						$address = $this->models['account']->getAddress($customer);
						 
						if ($address!= NULL) 
						if ($address->get_id() > 0  ) {
							// found a common address
							if ($status = 'new_order') {
								$ref_id = $address->get_id();
								$_SESSION['order']['cust_address'] = $ref_id;
							} else {
								// check $_SESSION['order']['cust_address' ID should be the same as $_res[0]->get_id()
								$ref_id = $_SESSION['order']['cust_address'];
							}
	
							if ($_POST['ship_to_default'] != 'true') {
	
								// customer address is not common anymore
								$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'facturation'), $address);
								if ($this->debug)
									echo "AccountController.updateAccount > common address was updated (switched to administrative address) : ".$address->get_id()."<br/>";
	
								// new shipping address
								$address = $this->models['account']->createAddress($this->prepareAddress($customer, 'expédition'));
								$_SESSION['order']['ship_address'] = $address->get_id();
								if ($this->debug)
									echo "AccountController.updateAccount > new shipping address : ".$address->get_id()."<br/>";
							} else {
	
								// customer address is now common
								$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'commune'), $address);
								if ($this->debug)
									echo "AccountController.updateAccount > common address was updated : ".$address->get_id()."<br/>";
							}
	
						} else {
	
							// found no common address
							// try getting administrative address
							$address = $this->models['account']->getAddress($customer, 'facturation');
	
							if ($address->get_id() > 0) {
					
								// found customer address
								if ($status = 'new_order') {
									$ref_id = $address->get_id();
									$_SESSION['order']['cust_address'] = $ref_id;
								} else {
									// check $_SESSION['order']['cust_address' ID should be the same as $_res[0]->get_id()
									$ref_id = $_SESSION['order']['cust_address'];
								}
	
								// update existing customer address
								if ($_POST['ship_to_default'] == 'true') {
									// customer address is now common
									$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'commune'), $address);
									unset($_SESSION['order']['ship_address']);
									if ($this->debug)
										echo "AccountController.updateAccount > administrative address was updated (switched to common) : ".$address->get_id()."<br/>";
	
								} else {
	
									// customer address is not common
									$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'facturation'), $address);
									if ($this->debug)
										echo "AccountController.updateAccount > common address was updated (switched to administrative) : ".$address->get_id()."<br/>";
	
									// new shipping address
									$address = $this->models['account']->createAddress($this->prepareAddress($customer, 'expédition'));
									$_SESSION['order']['ship_address'] = $address->get_id();
									if ($this->debug)
										echo "AccountController.updateAccount > new shipping address : ".$address->get_id()."<br/>";
								}
							}
						}
					} elseif (is_null($this->models['account']->isUniqueAccount($myEmail))) {
						// form email has changed during procedure and new given customer email does not exist
						// create new account
						$this->createAutoAccount($a_customer);
					} else {
						// back to customer form edition with defined order and customer session vars
						echo "ERROR - many customer accounts share the same email {$myEmail} !!";
					}		
				}
				return $customer;
			}
		//}
	}


	function createAutoAccount($a_account) {

		// create new account
		$customer = $this->models['account']->createAccount($a_account);
		$_SESSION['order']['customer'] = $customer->get_id();
		if ($this->debug)
			echo "AccountController.updateAccount > new customer account : ".$customer->get_id()."<br/>";

		//pre_dump($customer);

		// create new address
		if ($_POST['ship_to_default'] == 'true')
			$a_address = $this->prepareAddress($customer, 'commune');
		else	$a_address = $this->prepareAddress($customer, 'facturation');
		$address = $this->models['account']->createAddress($a_address);
		$_SESSION['order']['cust_address'] = $address->get_id();
		if ($this->debug)
			echo "AccountController.createAutoAccount > new administrative address : ".$address->get_id()."<br/>";

		if ($_POST['ship_to_default'] != 'true') {
			$address = $this->models['account']->createAddress($this->prepareAddress($customer, 'expédition'));
			$_SESSION['order']['ship_address'] = $address->get_id();
			if ($this->debug)
				echo "AccountController.createAutoAccount > new shipping address : ".$address->get_id()."<br/>";
		}
	}

	function getAllAccountAddresses ($account) {

		return $this->models['account']->getAllAccountAddresses($account); 
	}


	function getOrderingAddress ($account) {

		return  $this->models['account']->getOrderingAddress($account); 
	}


	function setOrderingAddress ($account, $id_address) {

		return  $this->models['account']->setOrderingAddress($account, $id_address); 
	}


	function getDefaultShippingAddress ($account) {

		return  $this->models['account']->getDefaultShippingAddress($account, $id_address); 
	} 
				

	function setDefaultShippingAddress ($account, $id_address) {

		return  $this->models['account']->setDefaultShippingAddress($account, $id_address); 
	}


	function prepareAddress($account, $type='commune') {
		
		if ($type == 'expédition') {
			$address = Array();
			$address['id_client'] = $account->get_id();
			$address['id_pays'] = $_POST['shipto_country'];
			$address['type'] = $type;
			$address['civilite'] = $_POST['shipto_gender'];
			$address['nom'] = $_POST['shipto_lastname'];
			$address['prenom'] = $_POST['shipto_firstname'];
			$address['societe'] = $_POST['shipto_company'];
			$address['tel'] = $_POST['shipto_telephone'];
			$address['detail_1'] = $_POST['shipto_addr_1'];
			$address['detail_2'] = $_POST['shipto_addr_2'];
			$address['detail_3'] = $_POST['shipto_addr_3'];
			$address['ville'] = $_POST['shipto_city'];
			$address['cp'] = $_POST['shipto_zipcode'];
			$address['statut'] = DEF_ID_STATUT_LIGNE;

			return $address;
		} 
		else if ($type == 'facturation') {
			$address = Array();
			$address['id_client'] = $account->get_id();
			$address['id_pays'] = $_POST['account_country'];
			$address['type'] = $type;
			$address['civilite'] = $_POST['account_gender'];
			$address['nom'] = $_POST['account_lastname'];
			$address['prenom'] = $_POST['account_firstname'];
			$address['societe'] = $_POST['account_company'];
			$address['tel'] = $_POST['account_telephone'];
			$address['detail_1'] = $_POST['account_addr_1'];
			$address['detail_2'] = $_POST['account_addr_2'];
			$address['detail_3'] = $_POST['account_addr_3'];
			$address['ville'] = $_POST['account_city'];
			$address['cp'] = $_POST['account_zipcode'];
			$address['statut'] = DEF_ID_STATUT_LIGNE;

			return $address;
		} else	return AccountController::prepareAddress($account);
	}

	
	function deleteAddress($address) {
		if ($address->get_type() == 'commune' && $address->get_isaddressdefault() == 1) {
			// s'il s'agissait de l'adresse par défaut, on en choisit une autre
			$common = $this->models['account']->getCommonAddress($address);
			$a_address = Array(	'isaddressdefault'	=> 1,
						'mdate'			=> date('Y-m-d H:i:s') );
			$this->models['account']->updateAddress($a_address, $common);
		}
		return $this->models['account']->deleteAddress($address);
	}




}

?>
