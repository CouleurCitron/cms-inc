<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle shopping kart content rendering through chosen rendering

// include ShoppingKart model
include_once('class.ShoppingKartModel.php');
// include Order model
include_once('class.OrderModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class OrderPaymentController extends BaseModuleController {

	var $mod_name = 'webshop';
	var $views_pile = null;
	// available process stages
	var $stages = Array('process', 'success', 'cancel', 'ipn', 'email');
	// available payment modes
	var $pay_modes = Array('CREDIT_CARD', 'PAYPAL', 'CHECK', 'TRANSFER', 'DIRECT', 'FREE');

	// constructor
	function OrderPaymentController ($views) {

		$this->models['kart'] = new ShoppingKartModel();
		$this->models['order'] = new OrderModel();

		if ($views != null && is_array($views))
			$this->views_pile = $views;

	}

	function build () {

		if ((strpos($_SERVER['REQUEST_URI'], "/content/") === false )  && (strpos($_SERVER['PHP_SELF'], "/content/") === false ) && ($_SERVER['REQUEST_URI']!='/')) {} else {
		
			// first check request
			$stage = explode('-', $_GET['action']);
			if (!in_array($stage[0], $this->stages))
				die("ERROR : wrong action request for this order");
 
			// process required accions according to payment mode
			switch ($stage[0]) {
				case 'process'	:	//viewArray($_POST, 'POST');
							// verify form and process consistency at this stage
							if ($_POST['do'] != 'confirm_order' || !in_array($_POST['pay_mode'], $this->pay_modes))
								die("ERROR : wrong process action required at this stage");
							// verify POST vs SESSION customer tracking
							$customer = new shp_client($_POST['customer_id']);

							if ($_POST['customer_id'] == $_SESSION['order']['customer']) {
								if (($_POST['total_pay']*1) == 0 && $_POST['pay_mode']!= 'DIRECT')
									// bypass payment on empty amount (coupons/deductions)
									$_POST['pay_mode'] = 'FREE';

								//if (empty($_SESSION['order']['id'])) { // supprimer pour pouvoir modifier l'objet shp_commande
									// FROM CUSTOM WEBSHOP LIBRARY
									// process possible custom action before freezing shopping kart
									if (WEBSHOP_CUSTOM_LIB != '' && function_exists('order_pre_process'))
										order_pre_process($customer);
									// freeze shopping kart content
									$structure = $this->models['kart']->freeze();
									// record order in case of first payment request
									$_SESSION['order']['id'] = $this->models['order']->record($structure);
								//} // supprimer pour pouvoir modifier l'objet shp_commande
								
								$order_id = $_SESSION['order']['id'];
								
							} else	die("ERROR : wrong customer identifier for this order");

							if ($_POST['pay_mode'] == 'FREE') {
								
								// bypass payment 
								$_POST['pay_mode'] = 'FREE';
								$response = Array( 	'success'	=> true,
											'id_order'	=> $order_id,
											'track'		=> Array ( 'special' => "Order was entirely paid with coupons/deductions")
										);

								// Validate transaction and order
								$order = $this->models['order']->validate($response);

								$render_class = $this->views_pile['email'];
								require_once('modules/webshop/custom/class.'.$render_class.'.php');
								$render = new $render_class();
                                                        	
								if (WEBSHOP_STOCK_DEDUCE && WEBSHOP_STOCK_ON_PAY) {
									// do update stock values
									$this->models['order']->updateStock($order);
								}
                                                        	
								// FROM CUSTOM WEBSHOP LIBRARY
								// process possible custom action after payment confirmed OK
								if (WEBSHOP_CUSTOM_LIB != '' && function_exists('order_post_process'))
									order_post_process($order, $customer);
                                                        	
								// emails rendering params
								$params = Array ('order'		=> $order,
										'customer'	=> $customer,
										'success'	=> true,
										'pay_mode'	=> $order->get_mode_paiement());

								// customer confirmation email
								$message = $render->render($params);
								multiPartMail($customer->get_email() , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
                                                        	
								// service confirmation email
								$params['service'] = true;
								$message = $render->render($params);
								if (defined(SHP_ADMIN_ORDER_EMAIL))	
									multiPartMail(SHP_ADMIN_ORDER_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
								if (SHP_ADMIN_PACK_EMAIL != '')
									multiPartMail(SHP_ADMIN_PACK_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
								if (SHP_ADMIN_SHIP_EMAIL != '')
									multiPartMail(SHP_ADMIN_SHIP_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
               	
								unset($_SESSION['shopping_kart']);
								//unset($_SESSION['order']); 
								unset($_SESSION['order']['id']);
								unset($_SESSION['order']['cust_address']); 
								unset($_SESSION['order']['ship_address']);

							} else {
								// standard payment process
								if (in_array($_POST['pay_mode'], Array('CHECK', 'TRANSFER', 'DIRECT'))) {
									// case DIRECT mod (via email)
									$order = new shp_commande($order_id);

									// confirmation emails
									$render_class = $this->views_pile['email'];
									
									//echo $render_class;
									include_once($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$render_class.'.php');
									$render = new $render_class($this);

									// emails rendering params
									$params = Array ('order'		=> $order,
											'customer'	=> $customer,
											'success'	=> null,
											'pay_mode'	=> $_POST['pay_mode']);

									// customer confirmation email
									$message = $render->render($params);
									multiPartMail($customer->get_email() , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);

									// service confirmation email
									$params['service'] = true;
									$message = $render->render($params);
									if (defined(SHP_ADMIN_ORDER_EMAIL))	
										multiPartMail(SHP_ADMIN_ORDER_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
		
									// free shopping kart for security and order ubiquity reasons
									unset($_SESSION['shopping_kart']);
									//unset($_SESSION['order']);
									unset($_SESSION['order']['id']);
									unset($_SESSION['order']['cust_address']); 
									unset($_SESSION['order']['ship_address']);
								}
							}
							break;

				case 'success'	:	// confirm on payment return call
							$response = $this->handleResponse();
							if ($response['success']) {
								// only in case of success 
								$order_id = $response['id_order'];
								$this->models['order']->confirm($order_id);
								break;
							}

				case 'cancel'	:	// cancel on payment return call
							$response = $this->handleResponse();
							$order_id = $response['id_order'];
							$this->models['order']->cancel($order_id);
							break;

				case'ipn'	:	// Definetly validate payment operation
							$ipn = $this->handleIPN();							 
							$order = new shp_commande($ipn['id_order']);
							
							// Verify payment amount
							if (floatval($order->get_total_pay()) != floatval($ipn['track']['amount'])) {
								// Invalid amount was paid
								$ipn['success'] = false;
								$ipn['error'] = 'Invalid amount was paid : '.$ipn['track']['amount'];
								error_log('Invalid amount was paid : '.$ipn['track']['amount'].' / '.floatval($order->get_total_pay()));
							}

							// Validate transaction and order
							$order = $this->models['order']->validate($ipn);

							$customer = new shp_client($order->get_id_client());

							// emails rendering params
							$params = Array ('order'		=> $order,
									'customer'	=> $customer,
									'pay_mode'	=> $order->get_mode_paiement(),
									'success'	=> $ipn['success'] );

							// confirmation emails
							$render_class = $this->views_pile['email'];
							require_once('modules/webshop/custom/class.'.$render_class.'.php');
							$render = new $render_class($this);
							if ($ipn['success']) {
								if (WEBSHOP_STOCK_DEDUCE && WEBSHOP_STOCK_ON_PAY) {
									// do update stock values
									$this->models['order']->updateStock($order);
								}
								// FROM CUSTOM WEBSHOP LIBRARY
								// process possible custom action after payment confirmed OK
								if (defined('WEBSHOP_CUSTOM_LIB')) {
									if (WEBSHOP_CUSTOM_LIB != '' && function_exists('order_post_process')) {
										order_post_process($order, $customer); 
									}
									
								} 
								// customer confirmation email
								$message = $render->render($params); 
								multiPartMail($customer->get_email() , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);

							} else	error_log('ipn failed');

							// service confirmation email
							$params['service'] = true;
							$params['error'] = $ipn['error'];
							$message = $render->render($params); 
							if (defined(SHP_ADMIN_ORDER_EMAIL))	
								multiPartMail(SHP_ADMIN_ORDER_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
							if ($ipn['success']) {
								if (SHP_ADMIN_PACK_EMAIL != '')
									multiPartMail(SHP_ADMIN_PACK_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
								if (SHP_ADMIN_SHIP_EMAIL != '')
									multiPartMail(SHP_ADMIN_SHIP_EMAIL , $message['subject'] , $message['body'] , '', SHP_AUTO_EMAIL);
							}
							break;

			}

			// process required accions according to payment mode
			if ($stage[0] == 'process')
				$view = $this->views_pile['process'][$_POST['pay_mode']];
			else	$view = $this->views_pile[$stage[0]];

			//echo "TEST view : ".$view."<br/>";
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/webshop/custom/class.'.$view.'.php')) {
				include_once('modules/webshop/custom/class.'.$view.'.php');
				$this->view = new $view($this);
				$this->view->render($order_id);
			} else	echo "OrderPaymentController.build > Incorrect View given for display : /modules/webshop/custom/class.".$view.".php<br/>Actions were processed though, but rendering is broken.";

			if ($response['success'] || $ipn['success']) {
				// check define(ALLOW_MULTIPLE_TRY);
				// free shopping kart for security and order ubiquity reasons
				unset($_SESSION['shopping_kart']);
				//unset($_SESSION['order']);
				unset($_SESSION['order']['id']);
				unset($_SESSION['order']['cust_address']); 
				unset($_SESSION['order']['ship_address']);
			}
		}

	}


	// generateRequest
	/**
	 * Use the appropriate generator class to request payment
	 *
	 * @param	Array		$params		Parameters to forward to the generator
	 * @return	String		The generated request
	 */
	function generateRequest ($params) { 
		$order = new shp_commande($_SESSION['order']['id']); 
		// get the generator instance
		if ($_POST['pay_mode'] == 'CREDIT_CARD'){
			// get the generator instance			
			// insert clas selector
			//$generator = new e_transactions_APIv6();
			$cbAPI = WEBSHOP_CBAPI;
			$generator = new $cbAPI();
		}
		elseif ($_POST['pay_mode'] == 'PAYPAL')
			$generator = new PayPal();

		// get variable names and populate generator parameters
		$vars = $generator->getMapping();
		foreach ($vars as $key => $val)
			$params[$val] = $order->$key();

		// set a transaction ID for the payment request
		$params['transaction_id'] = $this->models['order']->makeRandomKey('numeric', 6);
		$this->models['order']->prepare($_SESSION['order']['id'], $params['transaction_id']);

		return (String)	$generator->generateRequest($params);
	}


	// handleResponse
	/**
	 * Use the appropriate generator class to handle payment return call
	 *
	 * @return	Array		The generated response
	 */
	function handleResponse () {

		// get the generator instance
		$gen_class = $this->identifyPaymentMode();
		if (!is_null($gen_class)) {
			$generator = new $gen_class(); 
			return (Array)	$generator->handleResponse();
		} else {
			// error on return call
			logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/payment.log');
			$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/payment.log', 'a+');
			fwrite($f, "\n".'['.date('d/m/Y H:i:s').'] - Paiement : '.$pay_mode.' - Erreur sur retour de paiement');
			fclose($f);
		}
	}


	// handleIPN
	/**
	 * Use the appropriate generator class to handle IPN direct call
	 *
	 * @return	Array		The generated response
	 */
	function handleIPN() {
		// get the generator instance
		$gen_class = $this->identifyPaymentMode();
		if (!is_null($gen_class)) {
			$generator = new $gen_class();

			return (Array)	$generator->handleIPN();
		} else {
			// error on IPN call
			logCheckFileExists($_SERVER['DOCUMENT_ROOT'].'/log/payment.log');
			$f = fopen($_SERVER['DOCUMENT_ROOT'].'/log/payment.log', 'a+');
			fwrite($f, "\n".'['.date('d/m/Y H:i:s').'] - Paiement : '.$pay_mode.' - Erreur sur appel IPN');
			fclose($f);
		}
	}


	// identifyPaymentMode
	/**
	 * Select and verify conformity of paiement service call
	 *
	 * @return	String		The generator class
	 */
	function identifyPaymentMode() {
		$stage = explode('-', $_GET['action']);
		if ($stage[1] == 'CREDIT_CARD' && !empty($_POST[WEBSHOP_POSTTEST]))
			//return 'e_transactions_APIv6';
			return WEBSHOP_CBAPI;
		if ($stage[1] == 'PAYPAL' && isset($_POST['txn_id']))
			return 'PayPal';
		return null;
	}


}

?>