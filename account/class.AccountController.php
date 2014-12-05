<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle account actions and rendering

// structures du tracking en session:
// $_SESSION['account']['id']
// $_SESSION['account']['email']

// structures des donnéees de formulaires:
// prefix '' de données POST

// - gender
// - firstname
// - lastname
// - telephone
// - cellphone
// - email
// - password
// - professionnal (Y/N)
// - company
// - birthdate
// - addr_1
// - addr_2
// - addr_3
// - zipcode
// - city
// - country


// include account model
include_once('class.AccountModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class AccountController extends BaseModuleController {

	var $mod_name = 'account';
	var $views_pile = null;		// various views may be used with this controller
	var $wrapped = false;

	// available process actions
	var $actions = Array(	'create_form',
				'create_success',
				'create_pending',
				'create_error',
				'activate_success',
				'activate_error',
				'login_form',
				'login_success',
				'login_error',
				'disconnect',
				'recover_form',
				'recover_success',
				'recover_error',
				'delete_success',
				'delete_error',
				'remind_outdating',
				'delete_outdated',
				'edit_form',
				'edit_success',
				'edit_error',

				// plugged jobs module
				'jobs_login_form',
				'jobs_recover_form',
				'jobs_recover_success');


	// constructor
	/**
	 * @param	$views		an optionnal array of rendering views depending on required action
	 * @return	void
	 */
	function AccountController ($views=null) {

		$this->models['account'] = new AccountModel();
		
		if ($views != null && is_array($views))
			$this->views_pile = $views;
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

		$translator =& TslManager::getInstance();

		$params = Array();
		if (!empty($_GET['error']))
			$params['error'] = $_GET['error'];

		if (isset($_POST['action']) && in_array($_POST['action'], $this->actions)) {
			if ($this->debug)
				echo "AccountController.build() with action : ".$_POST['action']."<br/>";
			// process action that belongs to preset list
			switch ($_POST['action']) {
				// process specific actions and checkings
				case 'login_success' :	$success = false;
								if(isset($_POST["account_email"]) && !isset($_POST["email"]))  $_POST["email"] = $_POST["account_email"] ;
								if(isset($_POST["shipto_email"]) && !isset($_POST["email"]))  $_POST["email"] = $_POST["shipto_email"] ;
								if(isset($_POST["account_password"]) && !isset($_POST["password"]))  $_POST["password"] = $_POST["account_password"] ;
								if(isset($_POST["shipto_password"]) && !isset($_POST["password"]))  $_POST["password"] = $_POST["shipto_password"] ;
							//if ($this->isValidForm($_POST)) {
								if ($this->isExistingAccount($_POST['email'])) { 
									$account = $this->getAuthAccount($_POST['email'], $_POST['password']);
									if (!is_null($account)) {
										// Sucessfull authentication, set account to session
										$_SESSION['account']['id'] = $account->get_id();
										$_SESSION['account']['email'] = $account->get_email();
										$this->setLastConnected($account);
										$success = true;
									} else {
										// Authentication error
										$_POST['action'] = 'login_error';
										$params['error'] = $translator->getText('Mot de passe erroné');
										header('Location: '.$_POST['return_url_error'].'?action=login_error');
										exit();
									}
								} else {
									// Authentication error
									$_POST['action'] = 'login_error';
									$params['error'] = $translator->getText('Il n\'existe pas de compte pour cette adresse e-mail');
									header('Location: '.$_POST['return_url_error'].'?action=login_error');
									exit();
								}
								if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
									// force page reload on login success or error
									if (!empty($params['error'])) {
										// add error message to return URL
										$url = parse_url($_POST['return_url']);
										parse_str($url['query'], $gets);
										$gets['error'] = $params['error'];
										$_POST['return_url'] = $url['scheme'].'://'.$url['host'].$url['path'];
										$args = Array();
										foreach ($gets as $arg => $val)
											$args[] = $arg."=".$val;
										$_POST['return_url'] .= '?'.implode('&', $args).$url['fragment'];
									}
									header('Location: '.$_POST['return_url']);
									exit();
								} else {
									$test = $this->views_pile[$_POST['action']]['render'];									
									if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')) {
										if (empty($params['error']))
											die("SUCCESS");
										else	die($params['error']);
									}
								}
							//} else {
							//	// Post error
							//	$_POST['action'] = 'form_error';
							//	$params['error'] = 'Invalid form';
							//	header("HTTP/1.0 403 Forbidden");
							//	die("403 Forbidden");
							//}
							break;				

				case 'disconnect' :	$_SESSION['account'] = null;
							unset($_SESSION['account']);
							// force page reload on logout
							if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
								// reload given page
								header('Location: '.$_POST['return_url']);
								exit();
							} elseif ($this->views_pile['disconnect']['render'] == '' || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$this->views_pile['disconnect']['render'].'.php')) {
								// reload homepage
								header('Location: http://'.$_SERVER['HTTP_HOST']);
								exit();
							}
							break;

				case 'create_success' :	if ($this->isValidForm($_POST)) {
								if (!$this->isExistingAccount($_POST['email'])) {
									$account = $this->createAccount();
									if (!is_null($account)) {
										
										if (ACCOUNT_USE_ADDRESS && !empty($_POST['addr_1']) && !empty($_POST['city'])) {
											// create linked address record
											
											$address = $this->createAddress($account);
											if (!$address->get_id() > 0) {
												// Address creation error
												$this->models['account']->deleteAccount($account);
												$_POST['action'] = 'create_error';
												$params['error'] = $translator->getText('Erreur de traitement en base de données');
												break;
											}
										}
										// Successfull creation
										$params['account'] = $account;
	                                               	
										if (ACCOUNT_ASYNC_ACTIVATE) {
											// Account should be activated by email confirmation
											$params['activate_key'] = $this->createActivationKey($account);
										} else {
											// No activation needed, set account to session
											$_SESSION['account']['id'] = $account->get_id();
											$_SESSION['account']['email'] = $account->get_email();
											$this->setLastConnected($account);
										}
										$params['account'] = $account;
										if (isset($_POST['return_url']) && $_POST['return_url'] != '')
											// Specific URL should be loaded upon confirmation email backtrack
											$params['return_url'] = $_POST['return_url'];
	                                               	
										// account creation email
										$render_class = $this->views_pile['create_email']['render'];
										if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
											require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
											$render = new $render_class();
											$message = $render->render($params);
										} else {
											// default email content
											$message = Array(	'subject'	=> $translator->getText('Création du compte'),
														'body'		=> $translator->getText('Votre compte a bien été créé') );
										}
										multiPartMail($account->get_email(), $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);								
									} else {
										// Creation error
										$_POST['action'] = 'create_error';
										$params['error'] = $translator->getText('Erreur de traitement en base de données');
									}
								} else {
									// Creation error
									$_POST['action'] = 'create_error';
									$params['error'] = $translator->getText('Un compte existe déjà avec cette adresse e-mail');
								}
							} else {
								// Post error
								$_POST['action'] = 'form_error';
								$params['error'] = 'Invalid form';
								header("HTTP/1.0 403 Forbidden");
								die("403 Forbidden");
							}
							break;
							
				case 'create_pending' :	if ($this->isValidForm($_POST)) {
								 
								if (!$this->isExistingAccount($_POST['email'])) {
									$account = $this->createAccount();
									if (!is_null($account)) { 
										if (ACCOUNT_USE_ADDRESS && !empty($_POST['addr_1']) && !empty($_POST['city'])) {
											// create linked address record 
											$address = $this->createAddress($account);
											if (!$address->get_id() > 0) {
												// Address creation error
												$this->models['account']->deleteAccount($account);
												$_POST['action'] = 'create_error';
												$params['error'] = $translator->getText('Erreur de traitement en base de données');
												break;
											}
										}
										// Successfull creation
										$params['account'] = $account;
	                                               	
										if (ACCOUNT_ASYNC_ACTIVATE) {
											// Account should be activated by email confirmation
											$params['activate_key'] = $this->createActivationKey($account);
										} else {
											// No activation needed, set account to session
											$_SESSION['account']['id'] = $account->get_id();
											$_SESSION['account']['email'] = $account->get_email();
										}
										$params['account'] = $account;
										if (isset($_POST['return_url']) && $_POST['return_url'] != '')
											// Specific URL should be loaded upon confirmation email backtrack
											$params['return_url'] = $_POST['return_url'];
	                                               	
										// account creation email
										/*
										$render_class = $this->views_pile['create_email']['render'];
										if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
											require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
											$render = new $render_class();
											$message = $render->render($params);
										} else {
											// default email content
											$message = Array(	'subject'	=> 'Account creation',
														'body'		=> 'Your account was created with success' );
										}
										multiPartMail($account->get_email(), $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);	
										*/							
									} else {
										// Creation error
										$_POST['action'] = 'create_error';
										$params['error'] = $translator->getText('Erreur de traitement en base de données');
									}
								} else {
									// Creation error
									$_POST['action'] = 'create_error';
									$params['error'] = $translator->getText('Un compte existe déjà avec cette adresse e-mail');
								}
							}else {
								// Post error
								$_POST['action'] = 'form_error';
								$params['error'] = 'Invalid form';
								header("HTTP/1.0 403 Forbidden");
								die("403 Forbidden");
							}
							break;
							
				case 'activate_success' :	if (is_get('source')){
								list($_GET['email'], $_GET['key'])=explode(';', base64_decode($_GET['source']));
							}
						
							if ($this->isExistingAccount($_GET['email'])) {
								$account = $this->getAccount($_GET['email']);
								if ($account->get_act_key() == $_GET['key']) {
									$update = Array(	'statut'	=> DEF_ID_STATUT_LIGNE );
									$this->models['account']->updateAccount($update, $account);
                                                       	
									// Successfull activation, set account to session
									$_SESSION['account']['id'] = $account->get_id();
									$_SESSION['account']['email'] = $account->get_email();
									$params['account'] = $account;
								} else {
									// recover error
									$_POST['action'] = 'activate_error';
									$params['error'] = $translator->getText('Clé d\'activation non valide');
								}
							} else {
								// recover error
								$_POST['action'] = 'activate_error';
								$params['error'] = $translator->getText('Il n\'existe pas de compte pour cette adresse e-mail');
							}
							break;

				case 'recover_success' :	//if ($this->isValidForm($_POST)) {
				case 'jobs_recover_success' :
								if(isset($_POST["account_email"]) && !isset($_POST["email"]))  $_POST["email"] = $_POST["account_email"] ;
								if(isset($_POST["shipto_email"]) && !isset($_POST["email"]))  $_POST["email"] = $_POST["shipto_email"] ;
								if ($this->isExistingAccount($_POST['email'])) { 
									$account = $this->getAccount($_POST['email']);
									$newpass = substr($this->generateRandomKey(16), 0, 10);
									$a_account = Array(	'id'	=> $account->get_id(),
												'pwd'	=> $newpass );
									$this->updateAccount($a_account);
									$params['account'] = $account;
									$params['password'] = $newpass;
									// account recover email
									$render_class = $this->views_pile['recover_email']['render'];
									if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
										require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
										$render = new $render_class();
										$message = $render->render($params);
									} else {
										// default email content
										$message = Array(	'subject'	=> $translator->getText('Récupération du mot de passe de votre compte'),
													'body'		=> $translator->getText('Un nouveau mot de passe a été créé pour votre compte') );
									}
									multiPartMail($account->get_email(), $message['subject'], htmlFormat($message['body']), html2text($message['body']), DEF_CONTACT_FROM);
									if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
										// force page reload on login success or error
										if (!empty($params['error'])) {
											// add error message to return URL
											$url = parse_url($_POST['return_url']);
											parse_str($url['query'], $gets);
											$gets['error'] = $params['error'];
											$_POST['return_url'] = $url['scheme'].'://'.$url['host'].$url['path'];
											$args = Array();
											foreach ($gets as $arg => $val)
												$args[] = $arg."=".$val;
											$_POST['return_url'] .= '?'.implode('&', $args).$url['fragment'];
										}
										header('Location: '.$_POST['return_url']);
										exit();
									} else {
										$test = $this->views_pile[$_POST['action']]['render'];
										if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')) {
											if (empty($params['error']))
												die("SUCCESS");
											else	die($params['error']);
										}
									}
								} else {
									// recover error
									if ($_POST['action'] == 'jobs_recover_success') {
										die($translator->getText('Il n\'existe pas de compte pour cette adresse e-mail'));
									} else {
										$_POST['action'] = 'recover_error';
										$params['error'] = $translator->getText('Il n\'existe pas de compte pour cette adresse e-mail');
									}
								}
							//} else {
							//	// Post error
							//	$_POST['action'] = 'form_error';
							//	$params['error'] = 'Invalid form';
							//	header("HTTP/1.0 403 Forbidden");
							//	die("403 Forbidden");
							//}
							break;

				case 'edit_form' :	// verify session is populated with account
							if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
								$_POST['action'] = 'edit_error';
								$params['error'] = $translator->getText('Vous devez être connecté pour mettre à jour votre compte');
							} else {
								$account = $this->getAccount($_SESSION['account']['email']);
								$params['account'] = $account;
								if (ACCOUNT_USE_ADDRESS && !empty($_POST['addr_1']) && !empty($_POST['city'])) {
									$address = $this->getAddress($account);
									$params['address'] = $address;
								}
							}
							break;

				case 'edit_success' :	// verify session is populated with account
							if(isset($_POST["account_password"]) && !isset($_POST["password"]))  $_POST["password"] = $_POST["account_password"] ;
							if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
								$_POST['action'] = 'edit_error';
								$params['error'] = $translator->getText('Vous devez être connecté pour mettre à jour votre compte');
								//'Can not update an unidentified account. Please log in first';
							} else {
								if ($this->isValidForm($_POST)) {
									$account = $this->getAccount($_SESSION['account']['email']);
									if (ACCOUNT_USE_ADDRESS && !empty($_POST['addr_1']) && !empty($_POST['city']))
										$address = $this->getAddress($account);
									$update = false;
									if ($_POST['email'] != $_SESSION['account']['email']) {
										// try modifying email for this account
										if (!$this->models['account']->isExistingAccount($_POST['email'])) {
											$this->updateAccount();
											$update = true;
										} else {
											// email change error
											$_POST['action'] = 'edit_error';
											$params['error'] = $translator->getText('Il existe déjà un compte pour cette adresse e-mail');
										}
									} else {
										// standard account update
										$this->updateAccount();
										$update = true;
									}
									if ($update) {
										if (ACCOUNT_USE_ADDRESS && !empty($_POST['addr_1']) && !empty($_POST['city'])) {
											$a_address = $this->prepareAddress($account);
											$a_address['id'] = $address->get_id();
											$this->updateAddress($account, $a_address);
										}
										// account update email
										$params['account'] = $account;
										if ($_POST['current_password'] != '******')
											// password was changed
											$params['password'] = $_POST['password'];
	                                                
										$render_class = $this->views_pile['edit_email']['render'];
										if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
											require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
											$render = new $render_class();
											$message = $render->render($params);
										} else {
											// default email content
											$message = Array(	'subject'	=> $translator->getText('Mise à jour du compte'),
														'body'		=> $translator->getText('Votre compte a bien été mis à jour') );
										}
										multiPartMail(ACCOUNT_ADMIN_EMAIL, $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);								
									}
								} else {
									// Post error
									$_POST['action'] = 'form_error';
									$params['error'] = 'Invalid form';
									header("HTTP/1.0 403 Forbidden");
									die("403 Forbidden");
								}
							}
							break;

				case 'delete_success' :	// verify session is populated with account
							if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
								$_POST['action'] = 'delete_error';
								$params['error'] = $translator->getText('Vous devez être connecté pour supprimer votre compte');
							} else {
								$account = $this->getAccount($_SESSION['account']['email']);
								$success = dbDelete($account, true, true);
								
								if ($success)
									unset($_SESSION['account']);
								else	$params['error'] = $translator->getText('Erreur de traitement en base de données lors de la suppression du compte');

								if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
									// force page reload on login success or error
									if (!empty($params['error'])) {
										// add error message to return URL
										$url = parse_url($_POST['return_url']);
										parse_str($url['query'], $gets);
										$gets['error'] = $params['error'];
										$_POST['return_url'] = $url['scheme'].'://'.$url['host'].$url['path'];
										$args = Array();
										foreach ($gets as $arg => $val)
											$args[] = $arg."=".$val;
										$_POST['return_url'] .= '?'.implode('&', $args).$url['fragment'];
									}
									header('Location: '.$_POST['return_url']);
									exit();
								} else {
									$test = $this->views_pile[$_POST['action']]['render'];									
									if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')) {
										if (empty($params['error']))
											die('{"status":"SUCCESS", "type":"DELETE"}');
										else	die('{"status":"ERROR", "type":"DELETE", "error":'.json_encode(utf8_encode($params['error'])).'}');
									}
								}
							}
							break;

				case 'remind_outdating' :	// Process outdating account reminders
							$aAccounts = $this->models['account']->getOutdatingAccounts();
							$render_class = $this->views_pile[$_POST['action']]['render'];
							if (!empty($aAccounts)) {
								foreach ($aAccounts as $account) {
									$this->setLastReminded($account);
									if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
																				// account update email
										$params['account'] = $account;
										require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
										$render = new $render_class();
										$message = $render->render($params);
									} else {
										// default email content
										$message = Array(	'subject'	=> $translator->getText('Mise à jour du compte'),
														'body'		=> $translator->getText('Votre compte a bien été mis à jour') );
									}
									multiPartMail($account->get_email(), $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);	
								}
							}
							exit;

				case 'delete_outdated' :	// Process outdated account deletion
							$aAccounts = $this->models['account']->getOutdatedAccounts();
							if (!empty($aAccounts)) {
								foreach ($aAccounts as $account)
									$success = dbDelete($account, true, true);
							}

							exit;
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
				echo "AccountController.build() with render : ".$view."<br/>";
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


	function isValidAccount ($mail, $pass) {

		return $this->models['account']->isValidAccount($mail, $pass); 
	}
	
	function isValidForm ($_POST) {
	 
		
		global $stack;
		
		if(isset($_POST["account_email"]) && !isset($_POST["email"]))  $_POST["email"] = $_POST["account_email"] ;
		if(isset($_POST["shipto_email"]) && !isset($_POST["email"]))  $_POST["email"] = $_POST["shipto_email"] ;
		
		$classeName= 'shp_client';
		
		eval("$"."oRes = new ".$classeName."();"); 
		if(!is_null($oRes->XML_inherited))
			$sXML = $oRes->XML_inherited;
		else
			$sXML = $oRes->XML;
			  
		xmlClassParse($sXML);   
	
		if(isset($stack[0]['attrs']['NAME'])){	 
			//backup le stack	
			$stackBack = $stack;
			
			$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
			
			if(is_file($sPathSurcharge)) xmlFileParse($sPathSurcharge);
		}
		 
		$aNodeToSort = $stack[0]["children"];
		
		for ($i=0;$i<count($aNodeToSort);$i++) {
			if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["OBLIG"] == "true") {				
				$a_fields_oblig [] = $aNodeToSort[$i]["attrs"]["NAME"]; 
			}
		}
		
		$is_form_valid = true;
		
		if ($a_fields_oblig!=NULL){
		
			foreach ($a_fields_oblig as $champ  ) {
				eval ('$'.'value = '.'$'.'_POST["'.$champ.'"];') ;  
				if ($champ == 'email') { 
					if ($value=='' || !preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $value))  { 
						$is_form_valid = false; 
					}
				}
				else if ($value == '' )  
					$is_form_valid = false;
				 
			} 
		}
		
	
		return $is_form_valid; 
	}


	function isExistingAccount ($mail) {

		return $this->models['account']->isExistingAccount($mail);
	}


	function getAccount ($mail) {

		return $this->models['account']->getAccount($mail); 
	}
	
	
	function getAccountByKey ($key) {

		return $this->models['account']->getAccountByKey($key); 
	}


	function getAuthAccount ($mail, $pass) {

		return $this->models['account']->getAuthAccount($mail, $pass); 
	}


	function getAddress ($account, $type='commune') {

		return $this->models['account']->getAddress($account, $type); 
	}


	function createAccount ($a_account=null) { 
		if ($a_account == null) { 
			return $this->models['account']->createAccount($this->prepareAccount());
		}
		else	return $this->models['account']->createAccount($a_account);
	}


	function createAddress ($account, $a_address=null) { 
		if ($a_address == null)
			$a_address = $this->prepareAddress($account, 'commune'); 
		return $this->models['account']->createAddress($a_address);
	}


	function updateAccount ($a_account=null) {
		
		// viewArray($_POST, 'post');
		//if (in_array($_POST['do'], Array('set_account','update_account','update_address'))) {
			// 'set_account' is generic for non editable customer accounts
			if (is_null($a_account)) {
				$a_account = $this->prepareAccount();
				$a_account['id'] = $_POST['id'];
			}
			//viewArray($a_account);
			$account = $this->models['account']->updateAccount($a_account);
			if ($this->debug)
				echo "AccountController.updateAccount > account was updated : ".$account->get_id()."<br/>";
			return $account;
	}


	function updateAddress ($account, $a_address=null) {
		
		// viewArray($_POST, 'post');
		//if (in_array($_POST['do'], Array('set_account','update_account','update_address'))) {
			// 'set_account' is generic for non editable customer accounts
			if (is_null($a_address)) {
				$a_address = $this->prepareAddress($account);
				$a_address['id'] = $_POST['id'];
			}
			$address = $this->models['account']->updateAddress($a_address);
			if ($this->debug)
				echo "AccountController.updateAddress > account address was updated : ".$address->get_id()."<br/>";
			return $address;
	}


	function deleteAddress ($address) { 
		
		//$address = new shp_adresse($_POST['id']);
		return $this->models['account']->deleteAddress($address);
	}


	function prepareAccount() {
		$account = Array();
		
		if (is_post('nom')){
			$account['nom'] = $_POST['nom'];
		}
		if (is_post('prenom')){
			$account['prenom'] = $_POST['prenom']; 
		}
		if (is_post('tel')){
			$account['tel'] = $_POST['tel'];
		}
		if (is_post('portable')){
			$account['portable'] = $_POST['portable'];
		}
		if (is_post('email')){
			$account['email'] = $_POST['email'];
		}
		if (is_post('adresse')){
			$account['adresse'] = $_POST['adresse'];
		}
		if (is_post('cp')){
			$account['cp'] = $_POST['cp'];
		}
		if (is_post('ville')){
			$account['ville'] = $_POST['ville'];
		}
		if (is_post('act_key')){
			$account['act_key'] = $_POST['act_key'];
		}
		if (is_post('civilite')){
			$account['civilite'] = $_POST['civilite'];
		}
		
		if (!empty($_POST['password']))
			$account['pwd'] = $_POST['password'];
		
		
		$account['statut'] = DEF_ID_STATUT_LIGNE;

		return $account;
	}


	function prepareAddress($account) {
		$address = Array();
		$address['id_client'] = $account->get_id();
		$address['id_pays'] = $_POST['country'];
		$address['type'] = 'commune';	// default type
		$address['civilite'] = $_POST['gender'];
		$address['nom'] = $_POST['lastname'];
		$address['prenom'] = $_POST['firstname'];
		$address['societe'] = $_POST['company'];
		$address['tel'] = $_POST['telephone'];
		$address['detail_1'] = $_POST['addr_1'];
		$address['detail_2'] = $_POST['addr_2'];
		$address['detail_3'] = $_POST['addr_3'];
		$address['ville'] = $_POST['city'];
		$address['cp'] = $_POST['zipcode'];
		$address['statut'] = DEF_ID_STATUT_LIGNE;

		return $address;
	}

	
	function getCountries ($lang='fr', $all = false) {

		return (Array) $this->models['account']->getCountryPile($lang, $all);
	}
	
	function getShippingCountries ($lang='fr', $all = false) {

		return (Array) $this->models['account']->getShippingCountryPile($lang, $all);
	}
	
	function getCountryLibelle ($lang='fr', $id_pays) {

		return  $this->models['account']->getCountryLibelle($lang, $id_pays);
	}


	private function setLastConnected ($account=null) {
		if (!is_null($account)) {
			$a_account = Array(	'id'		=> $account->get_id(),
						'last_connected'	=> date('Y-m-d H:i:s') );
			$account = $this->models['account']->updateAccount($a_account);

			if ($this->debug)
				echo "AccountController.updateAccount > account last connexion date was updated : ".$account->get_id()."<br/>";
		}
		return $account;
	}


	private function setLastReminded ($account=null) {
		if (!is_null($account)) {
			$a_account = Array(	'id'		=> $account->get_id(),
						'last_reminded'	=> date('Y-m-d H:i:s') );
			$account = $this->models['account']->updateAccount($a_account);

			if ($this->debug)
				echo "AccountController.updateAccount > account last reminder date was updated : ".$account->get_id()."<br/>";
		}
		return $account;
	}


	function createActivationKey ($account) {
		$key = $this->generateRandomKey(16);
		$update = Array(	'act_key'	=> $key,
				'statut'		=> DEF_ID_STATUT_ATTEN );
		$this->models['account']->updateAccount($update, $account);
		return $key; 
	}


	// generateRandomKey
	/**
	* Generate a unique random password or validation key
	* Uses both a random chain and the current timestamp in a md5 hash
	*
	* @param		Int	complexity (length) of the random part before hash 
	* @return	String	the random chain
	*/
	function generateRandomKey ($len) {
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		$key = '';
		for($i=0; $i<$len; $i++)
			$key .= $pattern{rand(0,35)};
		return md5($key.time());
	}





}

?>
