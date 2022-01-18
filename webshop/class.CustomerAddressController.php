<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 


// controller to handle customer addressbook actions and displays

// structures des donnéees de formulaires:
// prefix 'address_' de données POST

// pour les propriétés (type et défaut), utiliser en suffixe l'ID
// address_ordering_<ID>
// address_shipping_<ID>

// controller to handle webshop Customer Account actions
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/webshop/class.CustomerAccountController.php');

// include Customer model
include_once('class.CustomerModel.php');
 
class CustomerAddressController extends CustomerAccountController {

	var $mod_name = 'webshop';
	var $views_pile = null;		// various views may be used with this controller
	var $wrapped = false;

	// available process actions
	// if this list should be changed, create a custom controller inheriting this one
	var $actions = Array(	'list_all_addresses',
				'create_address_form',
				'create_address_success',
				'create_addresses_success',
				'create_address_error',
				'edit_address_form',
				'edit_address_success',
				'edit_address_error',
				'delete_address_success',
				'delete_address_error',
				'set_addresses_props',
				'set_props_error');


	// constructor
	function CustomerAddressController ($views=null) {

		$this->models['account'] = new CustomerModel();
		if ($views != null && is_array($views))
			$this->views_pile = $views;

		// private area security check
		// (custom agelys session structure => to update)
		if (defined('ACCOUNT_EDITABLE_IN_BASKET') && ACCOUNT_EDITABLE_IN_BASKET) {
			 
		}
		else if (defined('ACCOUNT_EDITABLE') && ACCOUNT_EDITABLE) {
			if (empty($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client']) && empty($_SESSION['_'.strtoupper($_SESSION['dbname'])]['id_client'])) {
				// rediret to homepage
				echo '<script language="javascript" type="text/javascript">
					window.location.href="/";
				</script>';
				exit();
			}
		}
		// disconnection check
		if ($_POST['action'] == 'disconnect_account') {
			// custom agelys session structure => to update
			if (!empty($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client'])) {
				unset($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client']);
				unset($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['email_client']);
			} else {
				unset($_SESSION['_'.strtoupper($_SESSION['dbname'])]['id_client']);
				unset($_SESSION['_'.strtoupper($_SESSION['dbname'])]['email_client']);
			}
			unset($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]);
		 	unset($_SESSION['order']['customer']);
			// end custom agelys session structure => to update
			unset($_SESSION['account']);
			// rediret to homepage
			echo '<script language="javascript" type="text/javascript">
				window.location.href="/";
			</script>';
			exit();
		}


	}
	
	// build
	/**
	 * Build the Controller and launch desired view rendering
	 * May be called for preset action processsing
	 * or just to launch given render class with given wrap mode
	 *
	 * @param	$view		the name of a specific render class to bypass action selector
	 * @param	$wrapped		in case a specific render class is given, wrap it or not with HTML
	 * @return	void
	 */
	function build ($view=null, $wrapped=false) {
	
		
		$params = Array();
		if (!empty($_GET['error']))
			$params['error'] = $_GET['error'];
		
		// get current customer for private area pages
		// (custom agelys session structure => to update)
		if (!empty($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client']))
			$customer = new shp_client($_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client']);
		else if (!empty($_SESSION['_'.strtoupper($_SESSION['dbname'])]['id_client']))	
			$customer = new shp_client($_SESSION['_'.strtoupper($_SESSION['dbname'])]['id_client']);
		else { 
			/*$customer = $this->models['account']->createAccount($this->prepareAccount()); 
			// mettre en session 
			if ($_SESSION['rep_travail'] != "") {
				$_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client'] = $customer->get_id();  
				$_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['email_client'] = $customer->get_email(); 
			} else {
				$_SESSION['_'.strtoupper($_SESSION['dbname'])]['id_client'] = $customer->get_id();  
				$_SESSION['_'.strtoupper($_SESSION['dbname'])]['email_client'] = $customer->get_email(); 
			}*/
		}
		 
		$params['customer'] = $customer;

		// check if browsing account during shopping process
		global $courant;
		$params['shopping_kart'] = ($courant['id'] == 1046 ? true : false);

		if (isset($_POST['action']) && in_array($_POST['action'], $this->actions)) {
			if ($this->debug)
				echo "CustomerAddressController.build() with action : ".$_POST['action']."<br/>";
			// process action that belongs to preset list
			switch ($_POST['action']) {
				// process specific actions and checkings
				case 'list_all_addresses' :	$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
								break;

				case 'create_address_form' : 	$params['address'] = null;
								break;

				case 'create_address_success' : 	
				
								 
								$address = $this->createAddress($customer);
				 				 
								if (!$address) {
									$_POST['action'] = 'create_address_error';
									$params['error'] = 'db_creation_error';
								} else {
									$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
									if (newSizeOf($params['list']) == 1 && empty($_SESSION['order']['cust_address']))
										$_SESSION['order']['cust_address'] = $params['list'][0]->get_id();
								}
								break;
								
				case 'create_addresses_success' : 	   
								//echo "create_addresses_success";
								
								
								
								
								if ($_POST["shipto_email"]!= '' ) $_POST['email'] = $_POST["shipto_email"]; // car particulier
								else $_POST['email'] = $_POST["shipto_email"] = $_POST["account_email"]; // car particulier
								
								if (!isset($_POST['account_gender'])) $_POST['account_gender'] = $_POST['shipto_gender'];
								if (!isset($_POST['account_lastname'])) $_POST['account_lastname'] = $_POST['shipto_lastname'];
								if (!isset($_POST['account_firstname'])) $_POST['account_firstname'] = $_POST['shipto_firstname']; 
								if (!isset($_POST['account_telephone'])) $_POST['account_telephone'] = $_POST['shipto_telephone'];
								if (!isset($_POST['account_cellphone'])) $_POST['account_cellphone'] = $_POST['shipto_cellphone'];
								if (!isset($_POST['account_professionnal'])) $_POST['account_professionnal'] = $_POST['shipto_professionnal'];
								if (!isset($_POST['account_company'])) $_POST['account_company'] = $_POST['shipto_company']; 
								if (!isset($_POST['account_password'])) $_POST['account_password'] = $_POST['shipto_password'];
								if (!isset($_POST['account_act_key'])) $_POST['account_act_key'] = $_POST['shipto_act_key'];
								 
								if (!isset($_POST['account_addr_1'])) $_POST['account_addr_1'] = $_POST['shipto_addr_1'];
								if (!isset($_POST['account_addr_2'])) $_POST['account_addr_2'] = $_POST['shipto_addr_2'];
								if (!isset($_POST['account_addr_3'])) $_POST['account_addr_3'] = $_POST['shipto_addr_3'];
								if (!isset($_POST['account_city'])) $_POST['account_city'] = $_POST['shipto_city'];
								if (!isset($_POST['account_zipcode'])) $_POST['account_zipcode'] = $_POST['shipto_zipcode'];
								if (!isset($_POST['account_country'])) $_POST['account_country'] = $_POST['shipto_country'];
								
								
								if (!isset($_POST['shipto_gender'])) $_POST['shipto_gender'] = $_POST['account_gender'];
								if (!isset($_POST['shipto_lastname'])) $_POST['shipto_lastname'] = $_POST['account_lastname'];
								if (!isset($_POST['shipto_firstname'])) $_POST['shipto_firstname'] = $_POST['account_firstname']; 
								if (!isset($_POST['shipto_telephone'])) $_POST['shipto_telephone'] = $_POST['account_telephone'];
								if (!isset($_POST['shipto_cellphone'])) $_POST['shipto_cellphone'] = $_POST['account_cellphone'];
								if (!isset($_POST['shipto_professionnal'])) $_POST['shipto_professionnal'] = $_POST['account_professionnal'];
								if (!isset($_POST['shipto_company'])) $_POST['shipto_company'] = $_POST['account_company']; 
								if (!isset($_POST['shipto_password'])) $_POST['shipto_password'] = $_POST['account_password'];
								if (!isset($_POST['shipto_act_key'])) $_POST['shipto_act_key'] = $_POST['account_act_key'];
								 
								if (!isset($_POST['shipto_addr_1'])) $_POST['shipto_addr_1'] = $_POST['account_addr_1'];
								if (!isset($_POST['shipto_addr_2'])) $_POST['shipto_addr_2'] = $_POST['account_addr_2'];
								if (!isset($_POST['shipto_addr_3'])) $_POST['shipto_addr_3'] = $_POST['account_addr_3'];
								if (!isset($_POST['shipto_city'])) $_POST['shipto_city'] = $_POST['account_city'];
								if (!isset($_POST['shipto_zipcode'])) $_POST['shipto_zipcode'] = $_POST['account_zipcode'];
								if (!isset($_POST['shipto_country'])) $_POST['shipto_country'] = $_POST['account_country'];
								
								 
								
								$_POST['country'] = $_POST["account_country"];
								$_POST['civilite'] = $_POST['gender']= $_POST["account_gender"];
								$_POST['nom'] = $_POST['lastname']= $_POST["account_lastname"];
								$_POST['prenom'] = $_POST['firstname']= $_POST["account_firstname"];
								$_POST['company']= $_POST["account_company"];
								$_POST['tel'] = $_POST['telephone']= $_POST["account_telephone"];
								$_POST['addr_1']= $_POST["account_addr_1"];
								$_POST['addr_2']= $_POST["account_addr_2"];
								$_POST['addr_3']= $_POST["account_addr_3"];
								$_POST['city']= $_POST["account_city"];
								$_POST['zipcode']= $_POST["account_zipcode"]; 
								$_POST['password']= $_POST["account_password"]; 
								$_POST['act_key']= $_POST["account_act_key"]; 
								//print ( "create_addresses_success<br />"); 
								//echo $_POST["shipto_email"]."<br />". $customer->get_email()."<br />".$_POST['address_shipto_id']."<br />";
								
								$bExistingEmail = false;   
								if ($this->models['account']->isExistingAccount($_POST["shipto_email"])) { 
									
									if (isset($customer)) { 
										if (isset($_POST['address_shipto_id']) && $_POST['address_shipto_id']!= '' && $_POST["shipto_email"] != $customer->get_email()) {
											$bExistingEmail = true;	 
										  }
										else {
											$bExistingEmail = false; 
										}
									}
									else {
										$bExistingEmail = true;	 
									}	 
								}
								else {
									$bExistingEmail = false;	 
								}
								
								if (!$bExistingEmail) { 
									
									if (is_null($customer)) {
										 
										$customer = $this->models['account']->createAccount($this->prepareAccount()); 
										 
										// mettre en session 
										if ($_SESSION['rep_travail'] != "") {
											$_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['id_client'] = $customer->get_id();  
											$_SESSION['_'.strtoupper($_SESSION['rep_travail'])]['email_client'] = $customer->get_email(); 
										} else {
											$_SESSION['_'.strtoupper($_SESSION['dbname'])]['id_client'] = $customer->get_id();  
											$_SESSION['_'.strtoupper($_SESSION['dbname'])]['email_client'] = $customer->get_email(); 
										}
									}
									
									
									
									$_SESSION['order']['customer'] = $customer->get_id();
								
									if (isset($_POST["account_lastname"]) && isset($_POST["shipto_lastname"]) && $_POST['ship_to_default'] == 'true') {
										//echo "idem";
										$address = $this->models['account']->getAddress($customer);  
										if (!$address) {
											$address = $this->createAddress($customer);
										}
										else {
											$_POST['id'] = $address->get_id();
											$address = $this->updateAddress($customer);			
										}
										$_SESSION['order']['cust_address'] = $_SESSION['order']['ship_address'] = $address->get_id(); 
									}
									else {   
										//echo "pas idem";
										
										
										if ( $_POST['ship_to_default'] == 'true') {
											// echo "idem2";
											$_POST['country'] = $_POST["shipto_country"];
											$_POST['gender']= $_POST["shipto_gender"];
											$_POST['lastname']= $_POST["shipto_lastname"];
											$_POST['firstname']= $_POST["shipto_firstname"];
											$_POST['company']= $_POST["shipto_company"];
											$_POST['telephone']= $_POST["shipto_telephone"];
											$_POST['addr_1']= $_POST["shipto_addr_1"];
											$_POST['addr_2']= $_POST["shipto_addr_2"];
											$_POST['addr_3']= $_POST["shipto_addr_3"];
											$_POST['city']= $_POST["shipto_city"];
											$_POST['zipcode']= $_POST["shipto_zipcode"];
											$address = $this->models['account']->getAddress($customer);  
											if (!$address) {
												$address = $this->createAddress($customer);
											}
											else {
												$_POST['id'] = $address->get_id(); 
												$address = $this->updateAddress($customer);		 
											} 
											
											$_SESSION['order']['cust_address'] = $_SESSION['order']['ship_address'] = $address->get_id();
										}
										else {
											//echo "diff";
											 
											
											$address = $this->models['account']->getAddress($customer,'commune');  
											
											if ($address) {
												$this->models['account']->deleteAddress ($address);
											}
											 
											
											if (isset($_POST["account_lastname"])) {
												$address = $this->models['account']->getAddress($customer,'facturation');  
												 
												if (!$address) {
													 
													$address = $this->models['account']->createAddress($this->prepareAddress($customer, 'facturation'));
													$_SESSION['order']['cust_address'] = $address->get_id();
												}
												else {
													$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'facturation'), $address); 
													$_SESSION['order']['cust_address'] = $address->get_id();
													if ($this->debug)
														echo "AccountController.updateAccount > common address was updated (switched to administrative address) : ".$address->get_id()."<br/>";
												}				
											}	 
											// new shipping address
											$address = $this->models['account']->getAddress($customer, 'expédition');  
											if (!$address) {
												//echo "créer adresse expédition"; 
												$address = $this->models['account']->createAddress($this->prepareAddress($customer, 'expédition'));
												$_SESSION['order']['ship_address'] = $address->get_id();
											}
											else {
												$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'expédition'), $address);
												$_SESSION['order']['ship_address'] = $address->get_id();
												if ($this->debug)
													echo "AccountController.updateAccount > new shipping address : ".$address->get_id()."<br/>";
											}
										}
										
									}
										
										
										
									if (!$address) {
										$_POST['action'] = 'create_address_error';
										$params['error'] = 'db_creation_error';
									} else { 
										$_POST['action'] = 'create_address_form';
									} 
									
									
									
									
									
									
									
									
									
									
									
									
									
								}
								else { // le compte existe déjà 
									//print ( "compte existatnt<br />");
									$customer = $this->models['account']->getAccount($_POST["shipto_email"]);  
									$_POST['action'] = 'create_address_form';
									$params['error'] = 'existing_email'; 
								}
									
								
								
								
								
								
								break;				

				case 'edit_address_form' : 	if ($_POST['address_id']  > 0) {
									$params['address'] = new shp_adresse($_POST['address_id']);
								} else {
									$_POST['action'] = 'edit_address_error';
									$params['error'] = 'invalid_id_error';
								}
								break;

				case 'edit_address_success' : 	
				 
								if ($_POST['address_id']  > 0) {
									
									$_POST['id'] = $_POST['address_id'];
									$address = $this->updateAddress($customer);
									if (!$address) {
										$_POST['action'] = 'create_address_error';
										$params['error'] = 'db_update_error';
									} else	$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
								} 
								else if ($_POST['address_shipto_id']  > 0 || $_POST['address_account_id']  > 0) {  
									 
									if ( $_POST['ship_to_default'] == 'true') {
										$address = $this->models['account']->getAddress($customer, 'commune');
										$_POST['country'] = $_POST["shipto_country"];
										$_POST['gender']= $_POST["shipto_gender"];
										$_POST['lastname']= $_POST["shipto_lastname"];
										$_POST['firstname']= $_POST["shipto_firstname"];
										$_POST['company']= $_POST["shipto_company"];
										$_POST['telephone']= $_POST["shipto_telephone"];
										$_POST['addr_1']= $_POST["shipto_addr_1"];
										$_POST['addr_2']= $_POST["shipto_addr_2"];
										$_POST['addr_3']= $_POST["shipto_addr_3"];
										$_POST['city']= $_POST["shipto_city"];
										$_POST['zipcode']= $_POST["shipto_zipcode"];
										
										$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'commune'), $address);
										$_SESSION['order']['cust_address'] = $_SESSION['order']['ship_address'] = $address->get_id();
										if ($this->debug)
											echo "AccountController.updateAccount > common address was updated : ".$address->get_id()."<br/>";	
									}
									else {
										if ($_POST['address_shipto_id'] > 0) {
											 
											$address = $this->models['account']->getAddress($customer, 'expédition');
											$_POST['country'] = $_POST["shipto_country"];
											$_POST['gender']= $_POST["shipto_gender"];
											$_POST['lastname']= $_POST["shipto_lastname"];
											$_POST['firstname']= $_POST["shipto_firstname"];
											$_POST['company']= $_POST["shipto_company"];
											$_POST['telephone']= $_POST["shipto_telephone"];
											$_POST['addr_1']= $_POST["shipto_addr_1"];
											$_POST['addr_2']= $_POST["shipto_addr_2"];
											$_POST['addr_3']= $_POST["shipto_addr_3"];
											$_POST['city']= $_POST["shipto_city"];
											$_POST['zipcode']= $_POST["shipto_zipcode"];
											
											$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'expédition'), $address);
											$_SESSION['order']['ship_address'] = $address->get_id();
											if ($this->debug)
												echo "AccountController.updateAccount > common address was updated : ".$address->get_id()."<br/>";
										}
										else {
											$address = $this->models['account']->getAddress($customer, 'facturation');
											$_POST['country'] = $_POST["account_country"];
											$_POST['gender']= $_POST["account_gender"];
											$_POST['lastname']= $_POST["account_lastname"];
											$_POST['firstname']= $_POST["account_firstname"];
											$_POST['company']= $_POST["account_company"];
											$_POST['telephone']= $_POST["account_telephone"];
											$_POST['addr_1']= $_POST["account_addr_1"];
											$_POST['addr_2']= $_POST["account_addr_2"];
											$_POST['addr_3']= $_POST["account_addr_3"];
											$_POST['city']= $_POST["account_city"];
											$_POST['zipcode']= $_POST["account_zipcode"];
											
											$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'facturation'), $address);
											$_SESSION['order']['cust_address'] = $address->get_id();
											if ($this->debug)
												echo "AccountController.updateAccount > common address was updated (switched to administrative address) : ".$address->get_id()."<br/>";
										}
									}
									$_POST['id'] = $address->get_id(); 
									if (!$address) {
										$_POST['action'] = 'create_address_error';
										$params['error'] = 'db_update_error';
									} else	{
										$_POST['action'] = 'create_address_form';
									 	//$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
									} 
									
									
								}	 
								else if ($_POST['address_shipto_id']  > 0 && $_POST['address_account_id']  > 0) {
									$address = $this->models['account']->getAddress($customer);
									/* ------------------------------------------------ */
									if ($_POST['ship_to_default'] != 'true') { 
										// customer address is not common anymore
										
										$_POST['country'] = $_POST["account_country"];
										$_POST['gender']= $_POST["account_gender"];
										$_POST['lastname']= $_POST["account_lastname"];
										$_POST['firstname']= $_POST["account_firstname"];
										$_POST['company']= $_POST["account_company"];
										$_POST['telephone']= $_POST["account_telephone"];
										$_POST['addr_1']= $_POST["account_addr_1"];
										$_POST['addr_2']= $_POST["account_addr_2"];
										$_POST['addr_3']= $_POST["account_addr_3"];
										$_POST['city']= $_POST["account_city"];
										$_POST['zipcode']= $_POST["account_zipcode"];
										
										$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'facturation'), $address);
										$_SESSION['order']['cust_address'] = $address->get_id();
										if ($this->debug)
											echo "AccountController.updateAccount > common address was updated (switched to administrative address) : ".$address->get_id()."<br/>";
										 
										// new shipping address
										$address2 = $this->models['account']->updateAddress($this->prepareAddress($customer, 'expédition'), $address);
										$_SESSION['order']['ship_address'] = $address2->get_id();
										if ($this->debug)
											echo "AccountController.updateAccount > new shipping address : ".$address->get_id()."<br/>";
									} else {
										 
										// customer address is now common 
										
										$_POST['country'] = $_POST["shipto_country"];
										$_POST['gender']= $_POST["shipto_gender"];
										$_POST['lastname']= $_POST["shipto_lastname"];
										$_POST['firstname']= $_POST["shipto_firstname"];
										$_POST['company']= $_POST["shipto_company"];
										$_POST['telephone']= $_POST["shipto_telephone"];
										$_POST['addr_1']= $_POST["shipto_addr_1"];
										$_POST['addr_2']= $_POST["shipto_addr_2"];
										$_POST['addr_3']= $_POST["shipto_addr_3"];
										$_POST['city']= $_POST["shipto_city"];
										$_POST['zipcode']= $_POST["shipto_zipcode"];
										
										$address = $this->models['account']->updateAddress($this->prepareAddress($customer, 'commune'), $address);
										$_SESSION['order']['cust_address'] = $_SESSION['order']['ship_address'] = $address->get_id();
										if ($this->debug)
											echo "AccountController.updateAccount > common address was updated : ".$address->get_id()."<br/>";
											
										/* -------------------------------------------------- */ 
									}
									$_POST['id'] = $address->get_id(); 
									if (!$address) {
										$_POST['action'] = 'create_address_error';
										$params['error'] = 'db_update_error';
									} else	{
										$_POST['action'] = 'create_address_form';
									 	//$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
									} 
									
								} else {
									$_POST['action'] = 'update_address_error';
									$params['error'] = 'invalid_id_error';
								}
								break;

				case 'delete_address_success' :	$success = $this->deleteAddress(new shp_adresse($_POST['address_id']));
								if (!$success) 
									$_POST['action'] = 'delete_address_error';
								else	$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
								break;

				case 'set_addresses_props' :	$update = $this->prepareAddressProps($customer);
								if ($update['ordering'] > 0) {
									$success = $this->models['account']->setOrderingAddress($customer, $update['ordering']);
									if ($success) {
										if (!empty($_SESSION['order']['cust_address']))
											$_SESSION['order']['cust_address'] = $update['ordering'];
									} else {
										$_POST['action'] = 'set_props_error';
										$params['error'] = 'set_ordering_error';
									}
								}
								if ($update['shipping'] > 0) {
									$success = $this->models['account']->setDefaultShippingAddress($customer, $update['shipping']);
									if ($success) {
										// update order shipping address
										if (!empty($_SESSION['order']['ship_address']))
											$_SESSION['order']['ship_address'] = $update['shipping'];
									} else {
										$_POST['action'] = 'set_props_error';
										$params['error'] = 'set_shipping_error';
									}
								}
								$params['list'] = $this->models['account']->getAllAccountAddresses($customer);
								break;
			}

			// get View from preset list
			$view = $this->views_pile[$_POST['action']]['render'];

			// verify View render class exists
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
				include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
				$this->view = new $view($this);
			} else	die("ERROR : wrong render class for this process : modules/".$this->mod_name."/custom/class.".$view.".php");

		} elseif (!is_null($view) && is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
			if ($this->debug)
				echo "AddressController.build() with render : ".$view."<br/>";
			// get account from session
			if (!empty($_SESSION['account']) || $_SESSION['account']['id'] > 0)
				$params['account'] = $this->getAccount($_SESSION['account']['email']);
			// force a single specific view
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->wrapped = false;
		}

		if (!is_null($this->view))
			$this->view->render($params);
		else	echo "No View could be found to display <br/>";
	}


	function prepareAddressProps($customer) {
		
		$update = Array();

		//existing props
		$ordering = $this->models['account']->getOrderingAddress($customer);
		$shipping = $this->models['account']->getDefaultShippingAddress($customer);
		
		// possible changes
		$ordering_id = $shipping_id = 0;
		foreach ($_POST as $key => $val) {
			if (preg_match('/^address_ordering_/msi', $key)) {
				$reg = Array();
				preg_match('/_[0-9]+$/msi', $key, $reg);
				$ordering_id = substr($reg[0], 1);
			}
			if (preg_match('/^address_shipping_/', $key)) {
				$reg = Array();
				preg_match('/_[0-9]+$/msi', $key, $reg);
				$shipping_id = substr($reg[0], 1);
			}
		}
		if ($ordering_id != $ordering->get_id()) {
			$update['ordering'] = $ordering_id;
			if ($this->debug)
				echo "AddressController.prepareAddressProps() change ordering address<br/>";
		}
		if ($shipping_id != $shipping->get_id()) {
			$update['shipping'] = $shipping_id;
			if ($this->debug)
				echo "AddressController.prepareAddressProps() change default shipping address<br/>";
		}
		return $update;
	}


	function getCountries ($lang='fr', $all = false) {
		return (Array) $this->models['account']->getCountryPile($lang, $all);
	}


} 

?>
