<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle shopping kart content rendering through chosen rendering

// controller to handle webshop Customer Account actions
include_once('cms-inc/account/class.AccountController.php');

// needs to extend BaseModuleController
//include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');
// include Customer model
require_once('cms-inc/account/class.AccountModel.php');

// include Newsletter model
require_once('cms-inc/newsletter/class.NewsletterModel.php');

// include Jobs model
include_once('class.JobsModel.php');



class JobsController extends BaseModuleController {

	var $mod_name = 'jobs';

	var $current = null;

	// available process actions
	var $actions = Array(	'jobs_offer_search',
				'jobs_offer_list',
				'jobs_offer_sheet',
				'jobs_candidate_form',
				'jobs_create_success',
				'jobs_edit_success',
				'jobs_edit_email',
				'jobs_delete_success',
				'jobs_recover_success',
				'jobs_apply_form',
				'jobs_apply_success',
				'jobs_news_subs_form',
				'jobs_news_subscribed',
				'jobs_news_unsubscribe',
				'jobs_error');


	// constructor
	/**
	 * @param	$views		an optionnal array of rendering views depending on required action
	 * @return	void
	 */
	function JobsController ($views=null) {

		$this->models['jobs'] = new JobsModel();
		$this->models['account'] = new AccountModel();
		$this->models['newsletter'] = new NewsletterModel();
		if ($views != null && is_array($views))
			$this->views_pile = $views;
	}
	
	function build ($view=null) {
		$params = Array();
		if ($this->current > 0)
			$params['current'] = $this->current;
		if (!empty($_GET['error']))
			$params['error'] = $_GET['error'];

		$translator =& TslManager::getInstance();

		// process action that belongs to preset list
		if (isset($_POST['action']) && in_array($_POST['action'], $this->actions)) {
			if ($this->debug)
				echo "JobsController.build() with action : ".$_POST['action']."<br/>";
			// process specific actions and checkings
			switch ($_POST['action']) {

				case 'jobs_offer_search' :	$params['domains'] = $this->models['jobs']->getDomains($_POST['filter_domain']);
								$params['functions'] = $this->models['jobs']->getFunctions($_POST['filter_function']);
								$params['places'] = $this->models['jobs']->getPlaces($_POST['filter_place']);
								$params['types'] = $this->models['jobs']->getTypes($_POST['filter_type']);
								$params['experiences'] = $this->models['jobs']->getExperiences($_POST['filter_experience']);
								break;

				case 'jobs_offer_list' :		$params['offers'] = $this->models['jobs']->getOffers($_POST['filter_type'], $_POST['filter_place'], $_POST['filter_function'], $_POST['filter_experience'], $_POST['filter_text'], $_POST['filter_reference'], $_POST['filter_date_published'], $_POST['filter_date_start']);
								break;

				case 'jobs_offer_sheet' :		if (empty($_POST['offer'])) {
									$_POST['action'] = 'jobs_error';
									$params['error'] = 'invalid_offer';
								} else {
									$params['offer'] = $this->models['jobs']->getOffer($_POST['offer']);
									// Check already applied
									if (!empty($_SESSION['account']) && $_SESSION['account']['id'] > 0)
										$params['already'] = $this->models['jobs']->checkAlreadyApplied($_SESSION['account']['id'], $_POST['offer']);
								}
								break;

				case 'jobs_candidate_form' :	if (empty($_SESSION['account']) && !$_GET['create']) {
									// Should log in first
									header('Location: /modules/account/core/process.php?action=jobs_login_form'.(isset($_POST['offer']) ? '&offer='.$_POST['offer'] : ''));
									exit();
								} else {
									$content_dir = DEF_JOBS_APPLY_ROOT_UPLOAD; // Upoloaded files directory
									if (newSizeOf($_FILES)> 0) {
										// Handle file upload
										$key = key($_FILES);
										$tmp_file = $_FILES[$key]['tmp_name'];
										$ext = substr($_FILES[$key]['name'], strrpos($_FILES[$key]['name'], '.'));
										if ($tmp_file != "") {
											if (!is_uploaded_file($tmp_file) )
												exit("<h1 class=\"titre_emploi\">ERROR : Temporary uploaded file was not found</h1>");
											$name_file = substr($this->generateRandomKey(16), 0, 15).$ext;
											if (!move_uploaded_file($tmp_file, $content_dir.$name_file) )
												exit("<h1 class=\"titre_emploi\">ERROR : Temporary uploaded file could not be moved : {$content_dir}</h1>");
											die($name_file);
										}			
									} else { // verify session is populated with account
										if (!empty($_SESSION['account']) && $_SESSION['account']['id'] > 0) {
											$params['candidate'] = $this->models['jobs']->getCandidate($_SESSION['account']['id']);
											if (!empty($params['candidate']['fichier_cv'])) {
												$stat = stat($content_dir.$params['candidate']['fichier_cv']);
												if ($params['candidate']['fichier_cv'] != '')
													$params['candidate']['fichier_cv'] = Array(	'name'		=> $params['candidate']['fichier_cv'],
																			'size'		=> formatFileSize($stat['size']),
																			'created'	=> date('Y M D H:i:s',$stat['ctime']) );
											}
										}
										if ($_SESSION['id_langue'] == 2)
											$lang = 'en';
										else	$lang = 'fr';
										$params['countries'] = $this->models['account']->getCountryPile($lang);
										$params['domaines'] = $this->models['jobs']->getDomains($params['candidate']['domaine']);
										$params['types'] = $this->models['jobs']->getTypes($params['candidate']['contrat']);
										$params['qualifications'] = $this->models['jobs']->getQualifications();
										$params['experiences'] = $this->models['jobs']->getExperiences($params['candidate']['experience']);
										$params['add_ability'] = ($_POST['add_ability'] == 'true' ? true : false);
										$params['languages'] = $this->models['jobs']->getLanguages();
										$params['lang_levels'] = $this->models['jobs']->getLangLevels();
										$params['add_language'] = ($_POST['add_language'] == 'true' ? true : false);
										$params['functions'] = $this->models['jobs']->getFunctions($params['candidate']['metiers']);
										$params['places'] = $this->models['jobs']->getPlaces($params['candidate']['sites']);
										
										if (!empty($params['candidate']['salaire'])) {
											$params['candidate']['monnaie'] = substr($params['candidate']['salaire'], -3);
											$params['candidate']['salaire'] = substr($params['candidate']['salaire'], 0, -4);
										}
									}
								}
								break;

				case 'jobs_create_success' :	//if ($this->isValidForm($_POST)) {
									if (!$this->models['account']->isExistingAccount($_POST['email'])) {
										$account = $this->createAccount();
										if (!is_null($account)) {
			
											// No activation needed, set account to session
											$_SESSION['account']['id'] = $account->get_id();
											$_SESSION['account']['email'] = $account->get_email();
											// Handle associations
											$this->processAssociations($account->get_id());

											$params['candidate'] = $this->models['jobs']->getCandidate($account->get_id());
											//$params['candidate'] = $this->models['jobs']->getCandidate($account->get_id());
											if ($params['candidate']['pays'] > 0) {
												if ($_SESSION['id_langue'] == 2)
													$lang = 'en';
												else	$lang = 'fr';
												$params['candidate']['pays'] = Array(	'id'		=> $params['candidate']['pays'],
																	'libelle'	=> $this->models['account']->getCountryLibelle($lang, $params['candidate']['pays']) );
											}
											$contrat = new job_contrat($params['candidate']['contrat']);
											$params['candidate']['contrat'] = $contrat->get_libelle();
											$experience = new job_experience($params['candidate']['experience']);
											$params['candidate']['experience'] = $experience->get_libelle();
											$params['action'] = 'create';
			
											// Check for no delayed creation
											if ($_POST['keep_creating'] != 'true') {
												// account creation email
												$render_class = $this->views_pile['jobs_edit_email']['render'];
												if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
													require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
													$render = new $render_class();
													$message = $render->render($params);
												} else {
													// default email content
													$message = Array(	'subject'	=> $translator->getText('Création du compte'),
																'body'		=> $translator->getText('Votre compte a bien été créé'));
												}
												multiPartMail($account->get_email(), $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);
											}
											
											if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
												// force page reload on create success or error
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
														die('{"status":"SUCCESS", "type":"CREATE"}');
													else	die('{"status":"ERROR", "type":"CREATE", "error":'.json_encode(utf8_encode($params['error'])).'}');
												}
											}
										} else {
											// Creation error
											$_POST['action'] = 'jobs_error';
											$params['error'] = $translator->getText('Erreur de traitement en base de données');
										}
									} else {
										// Creation error
										$params['error'] = $translator->getText('Un compte existe déjà avec cette adresse e-mail');				

										$test = $this->views_pile[$_POST['action']]['render'];
										if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')){											
											die('{"status":"ERROR", "type":"CREATE", "error":'.json_encode(utf8_encode($params['error'])).'}');
										}
										else	$_POST['action'] = 'jobs_error';
									}
								//} else {
								//	// Post error
								//	$_POST['action'] = 'form_error';
								//	$params['error'] = 'Invalid form';
								//	header("HTTP/1.0 403 Forbidden");
								//	die("403 Forbidden");
								//}
								break;
							
				case 'jobs_edit_success' :		// verify session is populated with account
								if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
									$_POST['action'] = 'edit_error';
									$params['error'] = $translator->getText('Vous devez être connecté pour mettre à jour votre compte');
									//'Can not update an unidentified account. Please log in first';
								} else {
									//if ($this->isValidForm($_POST)) {
										$account = $this->models['account']->getAccount($_SESSION['account']['email']);
										//var_dump($account);
										$update = false;
										if ($_POST['email'] != $_SESSION['account']['email']) {
											// try modifying email for this account
											if (!$this->models['account']->isExistingAccount($_POST['email'])) {
												if ($_POST['password'] == '******')
													// We won't change password
													$_POST['password'] = "";
												$this->updateAccount();
												$update = true;
											} else {
												// email change error
												$_POST['action'] = 'edit_error';
												$params['error'] = $translator->getText('Il existe déjà un compte pour cette adresse e-mail');
											}
										} else {
											// standard account update
											if ($_POST['password'] == '******')
												// We won't change password
												$_POST['password'] = "";
											$this->updateAccount($account->get_id());
											$update = true;
										}
										if ($update) {
											// Handle associations
											$this->processAssociations($account->get_id());

											// account update email
											$params['candidate'] = $this->models['jobs']->getCandidate($account->get_id());
											if ($params['candidate']['pays'] > 0) {
												if ($_SESSION['id_langue'] == 2)
													$lang = 'en';
												else	$lang = 'fr';
												$params['candidate']['pays'] = Array(	'id'		=> $params['candidate']['pays'],
																	'libelle'	=> $this->models['account']->getCountryLibelle($lang, $params['candidate']['pays']) );
											}
											$contrat = new job_contrat($params['candidate']['contrat']);
											$params['candidate']['contrat'] = $contrat->get_libelle();
											$experience = new job_experience($params['candidate']['experience']);
											$params['candidate']['experience'] = $experience->get_libelle();
											$params['action'] = ($_POST['keep_creating'] == 'true' ? 'create' : 'edit');
											if ($_POST['full_save'] == 'true') {
												if ($_POST['account_password'] != '******')
													// password was changed
													$params['password'] = $_POST['account_password'];
	                                                	                        	
												$render_class = $this->views_pile['jobs_edit_email']['render'];
												if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
													require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
													$render = new $render_class();
													$message = $render->render($params);
												} else {
													// default email content
													$message = Array(	'subject'	=> ($_POST['keep_creating'] == 'true' ? $translator->getText('Création du compte') : $translator->getText('Mise à jour du compte')),
																'body'		=> ($_POST['keep_creating'] == 'true' ? $translator->getText('Votre compte a bien été créé') : $translator->getText('Votre compte a bien été mis à jour')	) );
												}
												multiPartMail($account->get_email(), $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);								
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
														die('{"status":"SUCCESS", "type":"'.($_POST['keep_creating'] == 'true' ? 'CREATE' : 'EDIT').'"}');
													else	die('{"status":"ERROR", , "type":"'.($_POST['keep_creating'] == 'true' ? 'CREATE' : 'EDIT').'", "error":'.json_encode(utf8_encode($params['error'])).'}');
												}
											}
										} else {
											// Update error
											$_POST['action'] = 'jobs_error';
											$params['error'] = $translator->getText('Erreur de traitement en base de données');
										}
									//} else {
									//	// Post error
									//	$_POST['action'] = 'form_error';
									//	$params['error'] = 'Invalid form';
									//	header("HTTP/1.0 403 Forbidden");
									//	die("403 Forbidden");
									//}
								}
								break;

				case 'jobs_delete_success' :	// verify session is populated with account
								if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
									$_POST['action'] = 'delete_error';
									$params['error'] = $translator->getText('Vous devez être connecté pour supprimer votre compte');
								} else {
									$account = $this->models['account']->getAccount($_SESSION['account']['email']);
									$success = dbDelete($account, true, true);
									
									if ($success) {
										if (defined('DEF_JOBS_NEWS_THEME_ID') && DEF_JOBS_NEWS_THEME_ID > 0) {
											// Unsubscribe to newsletter
											if (!$this->models['newsletter']->unsubscribe($_SESSION['account']['email'], DEF_JOBS_NEWS_THEME_ID))
												$params['error'] = $translator->getText('Erreur de traitement en base de données lors de l\'inscription à la newsletter');
										} else	$params['error'] = $translator->getText('Module Newsletter inactif ou thème non défini pour le module Jobs');
										// Clear session
										unset($_SESSION['account']);
									} else	$params['error'] = $translator->getText('Erreur de traitement en base de données lors de la suppression du compte');
                                                        	
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
				case 'jobs_apply_form' :		if (empty($_SESSION['account'])) {
									// Should log in first
									header('Location: /modules/account/core/process.php?action=jobs_login_form&apply=true&offer='.$_POST['offer']);
									exit();
								} else {
									if ($_POST['offer'] > 0)
										$params['offer'] = $this->models['jobs']->getOffer($_POST['offer']);
									// job_candidate inherits shp_client
									$params['candidate'] = $this->models['jobs']->getCandidate($_SESSION['account']['id']);
									if ($params['candidate']['pays'] > 0) {
										if ($_SESSION['id_langue'] == 2)
											$lang = 'en';
										else	$lang = 'fr';
										$params['candidate']['pays'] = Array(	'id'		=> $params['candidate']['pays'],
															'libelle'	=> $this->models['account']->getCountryLibelle($lang, $params['candidate']['pays']) );
									}
									$contrat = new job_contrat($params['candidate']['contrat']);
									$params['candidate']['contrat'] = $contrat->get_libelle();
									$experience = new job_experience($params['candidate']['experience']);
									$params['candidate']['experience'] = $experience->get_libelle();
									// Check already applied
									if (!empty($_POST['offer'])) {
										if ($_POST['apply_again'] == true)
											$params['already'] = false;
										else	$params['already'] = $this->models['jobs']->checkAlreadyApplied($_SESSION['account']['id'], $_POST['offer']);
									}
								}
								break;

				case 'jobs_apply_success' :	//if ($this->isValidForm($_POST)) {
									if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
										$_POST['action'] = 'jobs_error';
										$params['error'] = $translator->getText('Vous devez être connecté pour soumettre une candidature');
									//} elseif (empty($_POST['offer']) || !$_POST['offer'] > 0) {
									//	$_POST['action'] = 'jobs_error';
									//	$params['error'] = 'Can not apply to an unidentified offer. Please go back and select a job offer';
									} else {
										$account = $this->models['jobs']->getCandidate($_SESSION['account']['id']);
										$params['candidate'] = $account;
										//$contrat = new job_contrat($params['candidate']['contrat']);
										//$params['candidate']['contrat'] = $contrat->get_libelle();
										//$experience = new job_experience($params['candidate']['experience']);
										//$params['candidate']['experience'] = $experience->get_libelle();

										if ($_POST['offer'] > 0)
											$offer = $this->models['jobs']->getOffer($_POST['offer']);
										else 	$offer = Array(	'id'		=> -1,
													'bo_users'	=> -1,
													'type'		=> Array( 'id' => -1 ) );
										$application = $this->createApplication($account, $offer);
										if (!is_null($application)) {
											$params['application'] = $application;
											$params['offer'] = $offer;
											$params['action'] = 'create';

											// application creation email
											$render_class = $this->views_pile['jobs_apply_email']['render'];
											if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
												require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
												$render = new $render_class();
												$message = $render->render($params);
											} else {
												// default email content
												if ($_POST['offer'] > 0)
													$message = Array(	'subject'	=> $translator->getText('Dépôt de candidature'),
																'body'		=> $translator->getText('Votre candidature à l\'offre').' [id: '.$_POST['offer'].'] '. $translator->getText('a bien été enregistrée'));
												else	$message = Array(	'subject'	=> $translator->getText('Dépôt de candidature'),
																'body'		=> $translator->getText('Votre candidature spontanée a bien été enregistrée') );
											}
											multiPartMail($account['email'], $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);
											
											if ($_POST['offer'] > 0) {
												// application administrator email
												$recipients = $this->models['jobs']->getOfferRecipients($_POST['offer']);
												if (newSizeOf($recipients) > 0) {
													$render_class = $this->views_pile['jobs_admin_email']['render'];
													if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php')) {
														require_once('modules/'.$this->mod_name.'/custom/class.'.$render_class.'.php');
														$render = new $render_class();
														$message = $render->render($params);
													} else {
														// default email content
														$message = Array(	'subject'	=> $translator->getText('Dépôt de candidature'),
																	'body'		=> $translator->getText('Une candidature à l\'offre').' [id: '.$_POST['offer'].'] '.$translator->getText('a été déposée') );
													}
													multiPartMail(implode(',', $recipients), $message['subject'], $message['body'], '', ACCOUNT_ADMIN_EMAIL);
												}
											}

											if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
												// force page reload on create success or error
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
												//echo 'TEST test render '.$test.'<br/>';
												if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')) {
													//echo 'TEST test error '.$params['error'].'<br/>';
													if (empty($params['error']))
														die("SUCCESS");
													else	die($params['error']);
												}
											}
										} else {
											// Creation error
											$_POST['action'] = 'jobs_error';
											$params['error'] = $translator->getText('Erreur de traitement en base de données');
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

				case 'jobs_news_subs_form' :	if (empty($_SESSION['account'])) {
									// Should log in first
									header('Location: /modules/account/core/process.php?action=jobs_login_form&subscribe=true');
									exit();
								}
								break;

				case 'jobs_news_subscribed' :	if (empty($_SESSION['account']) || !$_SESSION['account']['id'] > 0) {
									$_POST['action'] = 'jobs_error';
									$params['error'] = $translator->getText('Vous devez être connecté pour vous abonner');
								//} elseif (empty($_POST['offer']) || !$_POST['offer'] > 0) {
								//	$_POST['action'] = 'jobs_error';
								//	$params['error'] = 'Can not apply to an unidentified offer. Please go back and select a job offer';
								} else {
									if (defined('DEF_JOBS_NEWS_THEME_ID') && DEF_JOBS_NEWS_THEME_ID > 0) {
								
										$account = $this->models['jobs']->getCandidate($_SESSION['account']['id']);
										$subscription = $this->createSubscription($account);
										
									} else	$params['error'] = $translator->getText('Module Newsletter inactif ou thème non défini pour le module Jobs');

									if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
										// force page reload on create success or error
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
										//echo 'TEST test render '.$test.'<br/>';
										if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')) {
											//echo 'TEST test error '.$params['error'].'<br/>';
											if (empty($params['error']))
												die("SUCCESS");
											else	die($params['error']);
										}
									}
								}
								break;

				case 'jobs_news_unsubscribe' :	if (empty($_SESSION['account'])) {
									// Should log in first
									header('Location: /modules/account/core/process.php?action=jobs_login_form&unsubscribe=true');
									exit();
								} else {
									if (defined('DEF_JOBS_NEWS_THEME_ID') && DEF_JOBS_NEWS_THEME_ID > 0) {
								
										$account = $this->models['jobs']->getCandidate($_SESSION['account']['id']);
										$this->models['newsletter']->unsubscribe($account['email'], DEF_JOBS_NEWS_THEME_ID);
										
									} else	$params['error'] = $translator->getText('Module Newsletter inactif ou thème non défini pour le module Jobs');

									if (isset($_POST['return_url']) && $_POST['return_url'] != '') {
										// force page reload on create success or error
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
										//echo 'TEST test render '.$test.'<br/>';
										if (empty($test) || !is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$test.'.php')) {
											//echo 'TEST test error '.$params['error'].'<br/>';
											if (empty($params['error']))
												die("SUCCESS");
											else	die($params['error']);
										}
									}
								}
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
				echo "JobsController.build() with render : ".$view."<br/>";
			// force a single specific view
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->wrapped = false;
		}
		
		if (isset($_POST['page']))
			$params['page'] = $_POST['page'];
		else	$params['page'] = null;					

		if (!is_null($this->view)) {
			return $this->view->render($params);

		} else	echo "No View could be found to display <br/>";
	}


	function createAccount () {

		$candidate = $this->models['account']->createAccount($this->prepareAccount());
		if ($candidate == null)
			echo "Error creating Candidate Account<br/>";
		elseif ($this->debug)
			echo "JobsController.createAccount > Candidate account was created : ".$candidate->get_id()."<br/>";
		
		return $candidate;
	}


	function updateAccount () {
		
		$a_account = $this->prepareAccount();
		$a_account['id'] = $_SESSION['account']['id'];
		
		//viewArray($a_account);
		$candidate = $this->models['account']->updateAccount($a_account);
		if ($candidate == null)
			echo "Error updating Candidate Account<br/>";
		elseif ($this->debug)
			echo "JobsController.updateAccount > Candidate account was updated : ".$candidate->get_id()."<br/>";
		return $candidate;
	}


	function prepareAccount() {
		$account = Array();
		$account['anonyme'] = ($_POST['account_anonyme'] == 'Y' ? 'Y' : 'N');
		$account['civilite'] = $_POST['account_gender'];
		$account['nom'] = $_POST['account_lastname'];
		$account['prenom'] = $_POST['account_firstname']; 
		$account['tel'] = $_POST['account_telephone'];
		$account['portable'] = $_POST['account_cellphone'];
		$account['professionnel'] = 'N';
		$account['societe'] = '';
		$account['email'] = $_POST['email'];
		$account['act_key'] = $_POST['account_act_key'];
		if (!empty($_POST['password']))
			$account['pwd'] = $_POST['password'];
		$account['naissance'] = $_POST['account_birthdate'];

		$account['situation'] = $_POST['candidate_situation'];
		$account['nationalite'] = $_POST['candidate_nationalite'];
		$account['adresse_1'] = $_POST['candidate_addr_1'];
		$account['adresse_2'] = $_POST['candidate_addr_2'];
		$account['adresse_3'] = $_POST['candidate_addr_3'];
		$account['ville'] = $_POST['candidate_city'];
		$account['cp'] = $_POST['candidate_zipcode'];
		$account['pays'] = $_POST['candidate_country'];
		$account['salaire'] = $_POST['candidate_salaire'];
		$account['experience'] = $_POST['candidate_experience'];
		$account['contrat'] = $_POST['candidate_contrat'];
		$account['parcours'] = $_POST['candidate_parcours'];
		$account['competences'] = $_POST['candidate_competences'];
		$account['interets'] = $_POST['candidate_interets'];
		$account['fichier_cv'] = $_POST['candidate_fichier_cv_name'];

		$account['langue'] = $_SESSION['id_langue'];
		$account['statut'] = DEF_CODE_STATUT_DEFAUT;

		return $account;
	}


	function createApplication ($_candidate, $_offer) {

		$application = $this->models['jobs']->createApplication($this->prepareApplication($_candidate, $_offer));
		if ($application == null)
			echo "Error creating Job Application<br/>";
		elseif ($this->debug)
			echo "JobsController.createApplication > Job Application was created : ".$application->get_id()."<br/>";
		
		return $application;
	}


	function prepareApplication($_candidate, $_offer) {
		$application = Array();
		$application['bo_users'] = $_offer['bo_users'];
		$application['offre'] = $_offer['id'];
		$application['contrat'] = $_offer['type']['id'];
		$application['candidat'] = $_candidate['id'];
		$application['reference'] = substr($this->generateRandomKey(16), 0, 12);
		$application['details'] = $_candidate['tel'].'<br/>'.($_candidate['anonyme'] == 'Y' ? $_candidate['email'] : '*** anonymous ***').'<br/><br/>'.$_candidate['adresse_1'].
			(!empty($_candidate['adresse_2']) ? '<br />'.$_candidate['adresse_2']: '').
			(!empty($_candidate['adresse_3']) ? '<br />'.$_candidate['adresse_3']: '').'<br/>
			'.$_candidate['cp'].' '.$_candidate['ville'].'<br/>
			'.$this->models['account']->getCountryLibelle(($_SESSION['id_langue'] == 2 ? 'en' : 'fr'), $_candidate['pays'])
			.(!empty($_candidate['fichier_cv']) ? '<br/>CV: <a href="'.DEF_JOBS_APPLY_UPLOAD.$_candidate['fichier_cv'].'" target="_blank">'.$_candidate['fichier_cv'].'</a>' : '');
		$application['motivation'] = $_POST['apply_motivation'];
		$application['date_dispo_debut'] = $_POST['apply_date_dispo_debut'];
		$application['date_dispo_fin'] = $_POST['apply_date_dispo_fin'];
		$application['fichier_motivation'] = $_POST['apply_fichier_motiv'];
		$application['statut'] = DEF_JOBS_APPLY_DEFAULT_STATUS;
		$application['commentaire'] = "";
		
		return $application;
	}


//	function isValidForm ($_POST) {
//		global $stack;
//		
//		$classeName= 'shp_client';
//		
//		eval("$"."oRes = new ".$classeName."();"); 
//		if(!is_null($oRes->XML_inherited))
//			$sXML = $oRes->XML_inherited;
//		else
//			$sXML = $oRes->XML;
//			  
//		xmlClassParse($sXML);   
//	
//		if(isset($stack[0]['attrs']['NAME'])){	 
//			//backup le stack	
//			$stackBack = $stack;
//			
//			$sPathSurcharge = $_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/'.$stack[0]['attrs']['NAME'].'.class.xml';
//			
//			if(is_file($sPathSurcharge)) xmlFileParse($sPathSurcharge);
//		}
//		 
//		$aNodeToSort = $stack[0]["children"];
//		
//		for ($i=0;$i<count($aNodeToSort);$i++) {
//			if ($aNodeToSort[$i]["name"] == "ITEM" && $aNodeToSort[$i]["attrs"]["OBLIG"] == "true") {				
//				$a_fields_oblig [] = $aNodeToSort[$i]["attrs"]["NAME"]; 
//			}
//		}
//		
//		$is_form_valid = true;
//		
//		if ($a_fields_oblig!=NULL){
//		
//			foreach ($a_fields_oblig as $champ  ) {
//				eval ('$'.'value = '.'$'.'_POST["'.$champ.'"];') ;  
//				if ($champ == 'email') { 
//					if ($value=='' || !preg_match("/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i", $value))  { 
//						$is_form_valid = false; 
//					}
//				}
//				else if ($value == '' )  
//					$is_form_valid = false;
//				 
//			} 
//		}
//	
//		return $is_form_valid; 
//	}
	
	function processAssociations ($_id=null) {
		// Delete ability request
		if ($_POST['del_ability'] > 0) {
			$a_delete = Array(	'job_id'		=> $_POST['del_ability'],
						'job_candidat'	=> $_id );
				
			if (!$this->models['jobs']->deleteCandidateAbility($a_delete))
				echo "Error deleting Job Candidate Ability<br/>";
			elseif ($this->debug)
				echo "JobsController.processAssociations > Job Candidate Ability was deleted<br/>";
			return;
		}
		// Delete language request
		if ($_POST['del_language'] > 0) {
			$a_delete = Array(	'job_id'		=> $_POST['del_language'] );
				
			if (!$this->models['jobs']->deleteCandidateLanguage($a_delete))
				echo "Error deleting Job Candidate Language<br/>";
			elseif ($this->debug)
				echo "JobsController.processAssociations > Job Candidate Language was deleted<br/>";
			return;
		}
		$existing_abilities = $this->models['jobs']->getCandidateAbilities($_id);
		$existing_languages = $this->models['jobs']->getCandidateLanguages($_id);
		//viewArray($existing_abilities);
		while (list($key, $val) = each($_POST)) {
			// Abilities
			if (preg_match('/^candidate_ability_/', $key)) {
				preg_match('/_[0-9]+$/', $key, $matches);
				$num = substr($matches[0], 1);
				if ($_POST['candidate_formation_'.$num] > 0 && $_POST['candidate_qualification_'.$num] > 0) {
					// Got ability declaration, but check existing first
					if (!empty($existing_abilities)) {
						$found = false;
						foreach ($existing_abilities as $stored) {
							if ($stored['asso_id'] == $_POST['candidate_ability_'.$num]) {
								// update existing
								$found = true;
								$a_ability = Array(	'id'		=> $stored['asso_id'],
											'candidat'	=> $_id,
											'domaine'	=> $_POST['candidate_formation_'.$num],
											'qualification'	=> $_POST['candidate_qualification_'.$num],
											'diplome'	=> $_POST['candidate_diplome_'.$num],
											'ecole'		=> $_POST['candidate_ecole_'.$num],
											'annee'		=> $_POST['candidate_annee_'.$num] );
								$ability = $this->models['jobs']->updateCandidateAbility($a_ability);
								if ($ability == null)
									echo "Error updating Job Candidate Ability<br/>";
								elseif ($this->debug)
									echo "JobsController.processAssociations > Job  Candidate Ability was updated : ".$ability->get_id()."<br/>";
							}
						}
						if (!$found) {
							// new ability and level
							$a_ability = Array(	'candidat'	=> $_id,
										'domaine'	=> $_POST['candidate_formation_'.$num],
										'qualification'	=> $_POST['candidate_qualification_'.$num],
										'diplome'	=> $_POST['candidate_diplome_'.$num],
										'ecole'		=> $_POST['candidate_ecole_'.$num],
										'annee'		=> $_POST['candidate_annee_'.$num] );
							$ability = $this->models['jobs']->createCandidateAbility($a_ability);
							if ($ability == null)
								echo "Error creating Job Candidate Ability<br/>";
							elseif ($this->debug)
								echo "JobsController.processAssociations > Job Candidate Ability was created : ".$ability->get_id()."<br/>";
						}
					} else {
						// new ability and level
						$a_ability = Array(	'candidat'	=> $_id,
									'domaine'	=> $_POST['candidate_formation_'.$num],
									'qualification'	=> $_POST['candidate_qualification_'.$num],
									'diplome'	=> $_POST['candidate_diplome_'.$num],
									'ecole'		=> $_POST['candidate_ecole_'.$num],
									'annee'		=> $_POST['candidate_annee_'.$num] );
						$ability = $this->models['jobs']->createCandidateAbility($a_ability);
						if ($ability == null)
							echo "Error creating Job Candidate Ability<br/>";
						elseif ($this->debug)
							echo "JobsController.processAssociations > Job Candidate Ability was created : ".$ability->get_id()."<br/>";
					}
				}
			}
			// Languages
			if (preg_match('/^candidate_language_/', $key)) {
				preg_match('/_[0-9]+$/', $key, $matches);
				$num = substr($matches[0], 1);
				if ($_POST['candidate_lang_nom_'.$num] > 0 && $_POST['candidate_lang_niveau_'.$num] > 0) {
					// Got ability declaration, but check existing first
					if (!empty($existing_languages)) {
						$found = false;
						foreach ($existing_languages as $stored) {
							if ($stored['asso_id'] == $_POST['candidate_language_'.$num]) {
								// update existing
								$found = true;
								$a_language = Array(	'id'		=> $stored['asso_id'],
											'candidat'	=> $_id,
											'langue'		=> $_POST['candidate_lang_nom_'.$num],
											'niveaulangue'	=> $_POST['candidate_lang_niveau_'.$num] );
								$language = $this->models['jobs']->updateCandidateLanguage($a_language);
								if ($language == null)
									echo "Error updating Job Candidate Language<br/>";
								elseif ($this->debug)
									echo "JobsController.processAssociations > Job  Candidate Language was updated : ".$language->get_id()."<br/>";
							}
						}
						if (!$found) {
							// new language and level
							$a_language = Array(	'candidat'	=> $_id,
										'langue'		=> $_POST['candidate_lang_nom_'.$num],
										'niveaulangue'	=> $_POST['candidate_lang_niveau_'.$num] );
							$language = $this->models['jobs']->createCandidateLanguage($a_language);
							if ($language == null)
								echo "Error creating Job Candidate Language<br/>";
							elseif ($this->debug)
								echo "JobsController.processAssociations > Job Candidate Language was created : ".$language->get_id()."<br/>";
						}
					} else {
						// new language and level
						$a_language = Array(	'candidat'	=> $_id,
									'langue'		=> $_POST['candidate_lang_nom_'.$num],
									'niveaulangue'	=> $_POST['candidate_lang_niveau_'.$num] );
						$language = $this->models['jobs']->createCandidateLanguage($a_language);
						if ($language == null)
							echo "Error creating Job Candidate Language<br/>";
						elseif ($this->debug)
							echo "JobsController.processAssociations > Job Candidate Language was created : ".$language->get_id()."<br/>";
					}
				}
			}
		}
		// Functions
		$existing_functions = $this->models['jobs']->getCandidateFunctions($_id);
		if (!empty($_POST['candidate_metier'])) {
			foreach ($_POST['candidate_metier'] as $metier) {
				if (!in_array($metier, $existing_functions)) {
					$a_metier = Array(	'candidat'	=> $_id,
								'metier'		=> $metier );
					$new = $this->models['jobs']->createCandidateFunction($a_metier);
					if ($new == null)
						echo "Error creating Job Candidate Function<br/>";
					elseif ($this->debug)
						echo "JobsController.processAssociations > Job Candidate Function was created : ".$new->get_id()."<br/>";
				}					
			}
		}
		foreach ($existing_functions as $id => $metier) {
			if (empty($_POST['candidate_metier']) || !in_array($id, $_POST['candidate_metier'])) {
				$a_delete = Array(	'job_candidat'	=> $_id,
							'job_metier'	=> $id );
				if (!$this->models['jobs']->deleteCandidateFunction($a_delete))
					echo "Error deleting Job Candidate Function<br/>";
				elseif ($this->debug)
					echo "JobsController.processAssociations > Job Candidate Function was deleted<br/>";
			}
		}

		// Places
		$existing_places = $this->models['jobs']->getCandidatePlaces($_id);
		if (!empty($_POST['candidate_lieu'])) {
			foreach ($_POST['candidate_lieu'] as $lieu) {
				if (!in_array($lieu, $existing_places)) {
					$a_lieu = Array(	'candidat'	=> $_id,
							'lieu'		=> $lieu );
					$new = $this->models['jobs']->createCandidatePlace($a_lieu);
					if ($new == null)
						echo "Error creating Job Candidate Place<br/>";
					elseif ($this->debug)
						echo "JobsController.processAssociations > Job Candidate Place was created : ".$new->get_id()."<br/>";
				}					
			}
		}
		foreach ($existing_places as $id => $lieu) {
			if (empty($_POST['candidate_lieu']) || !in_array($id, $_POST['candidate_lieu'])) {
				$a_delete = Array(	'job_candidat'	=> $_id,
							'job_lieu'	=> $id );
				if (!$this->models['jobs']->deleteCandidatePlace($a_delete))
					echo "Error deleting Job Candidate Place<br/>";
				elseif ($this->debug)
					echo "JobsController.processAssociations > Job Candidate Place was deleted<br/>";
			}
		}
	}

	function createSubscription ($_candidate) {

		$criteria = $this->prepareCriteria();
		$subscription = $this->models['newsletter']->addSubscription($_candidate['email'], $_candidate['nom'], $_candidate['prenom'], DEF_JOBS_NEWS_THEME_ID, $criteria);

		if ($subscription == null)
			echo "Error creating News Subscription<br/>";
		elseif ($this->debug)
			echo "JobsController.createSubscription > Job News Subscription was created : ".$subscription->get_id()."<br/>";
		
		return $subscription;
	}


	function prepareCriteria() {
		return Array(	'type'		=> $_POST['type'], 
				'place'		=> $_POST['place'],
				'function'	=> $_POST['function'],
				'experience'	=> $_POST['experience'],
				'text'		=> $_POST['text'],
				'reference'	=> $_POST['reference'],
				'date_published'	=> $_POST['date_published'],
				'date_start'		=> $_POST['date_start'] );
	}


	function generateRandomKey ($len) {
		$pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
		$key = '';
		for($i=0; $i<$len; $i++)
			$key .= $pattern{rand(0,35)};
		return md5($key.time());
	}


}

?>
