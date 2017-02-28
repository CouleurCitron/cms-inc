<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// dedicated generation for the online payment procedure

class PayPal {

	var $last_error;                 // holds the last error encountered

	var $remove_quotes;              // bool: remove quotes from paypal post?
	var $ipn_log;                    // bool: log IPN results to text file?

	var $ipn_log_file;               // filename of the IPN log
	var $ipn_response;               // holds the IPN response from paypal   
	var $ipn_data = array();         // array contains the POST values for IPN

	var $fields = array();           // array holds the fields to submit to paypal

	// constructor
	function PayPal () { 
		
		// connexion BDD
		if ((preg_match('/hephaistos/', $_SERVER['HTTP_HOST'])==1)||(preg_match('/couleur-citron/', $_SERVER['HTTP_HOST'])==1)){
			$this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';		// dev test url	 
		}
		else{ // prod
			$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';	 
		}
  
		$this->last_error = '';

		$this->remove_quotes = true;
		$this->ipn_response = '';
	}


	// map webshop module DB fields getters with dedicated form field names
	function getMapping () {

		return Array(	'get_id'		=> 'invoice',
				'get_total_ht'	=> 'amount',
				'get_structure'	=> 'caddie',
				'get_id_client'	=> 'custom',
				'get_port'	=> 'shipping',
				'get_tva'	=> 'tax' );
	}


	function generateRequest ($params) {

		$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		//$this_script = 'http://luc.raujolles.hephaistos.couleurcitron.ath.cx'.$_SERVER['PHP_SELF'];

		$form = '<form method="post" id="paypal_form" name="paypal_form" action="'.$this->paypal_url.'">
			<input type="hidden" id="cmd" name="cmd" value="_xclick" />'.		// Direct to payment page
			'<input type="hidden" id="redirect_cmd" name="redirect_cmd" value="_xclick" />
			<input type="hidden" id="rm" name="rm" value="2" />
			<input type="hidden" id="bn" name="bn" value="PP-BuyNowBF" />
			<input type="hidden" id="no_note" name="no_note" value="1"/>
			';
			
			foreach ($params as $key => $val)
				$form .= "\n".'<input type="hidden" name="'.$key.'" value="'.$val.'" />';

//			<input type="hidden" name="image" value="'.$params['abs_uri'].'/images/Fr-fr/order_pay_btn.gif"/>
//			<input type="hidden" name="no_shipping" value="1"/>

		$form .= '<input type="hidden" name="return" value="'.$this_script.'?action=success-'.$_POST['pay_mode'].'"/>
			<input type="hidden" name="cancel_return" value="'.$this_script.'?action=cancel-'.$_POST['pay_mode'].'"/>
			<input type="hidden" name="notify_url" value="'.$this_script.'?action=ipn-'.$_POST['pay_mode'].'"/>
			';
			// Ajout pour remplissage auto de l'adresse de facturation
/*
			<input type="hidden" name="last_name" value="'.$adao->eco_addresses_LastName().'">
			<input type="hidden" name="first_name" value="'.$adao->eco_addresses_FirstName().'">
			<input type="hidden" name="address1" value="'.$adao->getAddressLine(1).'">
			<input type="hidden" name="address2" value="'.$adao->getAddressLine(2).$adao->getAddressLine(3).'">
			<input type="hidden" name="city" value="'.$adao->eco_addresses_City().'">
			<input type="hidden" name="zip" value="'.$adao->eco_addresses_Zipcode().'">
			<input type="hidden" name="email" value="'.$cdao->eco_customers_Email().'">
			<input type="hidden" name="night_phone_b" value="'.$adao->eco_addresses_Telephone().'">';

*/
		$form .= '</form>';

		echo $form;
	}


	// generate response data for the payment procedure return
	function handleResponse () {

		$stage = explode('-', $_GET['action']);
		$track = Array(	'order_id'	=> $_POST['invoice'],
				'customer_id'	=> $_POST['custom'],
				'transaction_id'	=> $_POST['txn_id'],
				'status'		=> $_POST['payment_status'],
				'amount'		=> $_POST['amount']);

		return (Array) Array(	'id_order'	=> $_POST['invoice'],
					'success'	=> ($stage[0] == 'success' ? true : false),
					'error'		=> '',
					'track'		=> $track );

	}

	function handleIPN () {

		// parse the paypal URL
		$url_parsed = parse_url($this->paypal_url);        
		// generate the post string from the _POST vars aswell as load the
		// _POST vars into an array so we can play with them from the calling
		// script.
		$post_string = '';    
		#viewArray($_POST, 'test POST response');
		foreach ($_POST as $field => $value) {
			$this->ipn_data[$field] = $value;
			$post_string .= $field.'='.urlencode($value).'&'; 
		}
		$post_string .= "cmd=_notify-validate"; // append ipn command

		// open the connection to paypal
		$fp = fsockopen($url_parsed['host'], '80', $err_num, $err_str, 30); 
		if (!$fp) {
			// could not open the connection. 
			// If loggin is on, the error message will be in the log.
			$this->last_error = "fsockopen error no. $errnum: $errstr";
			$this->logIPN(false);       
			return false;
		} else { 
			// Post the data back to paypal
			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
			fputs($fp, "Host: $url_parsed[host]\r\n"); 
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
			fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
			fputs($fp, "Connection: close\r\n\r\n"); 
			fputs($fp, $post_string . "\r\n\r\n"); 

			// loop through the response from the server and append to variable
			while(!feof($fp))
				$this->ipn_response .= fgets($fp, 1024); 
			fclose($fp); // close connection
		}
		if (preg_match("/VERIFIED/msi", $this->ipn_response)) {

			if ($_POST['payment_status'] == "Completed") {
				// vérifier que txn_id n'a pas été précédemment traité: Créez une fonction qui va interroger votre base de données
				//if (VerifIXNID($txn_id) == 0) {
					// vérifier que receiver_email est votre adresse email PayPal principale
					if ($_POST['receiver_email'] == SHP_PAYPAL_EMAIL) {
						// track payment amount for later verification
						$this->ipn_data['amount'] = $_POST['mc_gross'];
						// Valid transaction.
						return Array(	'id_order'	=> $_POST['invoice'],
								'success'	=> true,
								'error'		=> '',
								'track'		=> $this->ipn_data );
					} else {
						// Invalid PAYPAL account email address
						return Array(	'id_order'	=> $_POST['invoice'],
								'success'	=> false,
								'error'		=> 'IPN Validation Failed : Invalid PAYPAL account email address',
								'track'		=> $this->ipn_data );
					}
				//} else {
					// ID de transaction déjà utilisé
				//}
			} else {
				// Payment status : Failed
				return Array(	'id_order'	=> $_POST['invoice'],
						'success'	=> false,
						'error'		=> 'IPN Validation Failed : Failed status',
						'track'		=> $this->ipn_data );
			}
		} else {
			// Invalid IPN transaction.  Check the log for details.
			return Array(	'id_order'	=> $_POST['invoice'],
					'success'	=> false,
					'error'		=> 'IPN Validation Failed : Invalid response',
					'track'		=> $this->ipn_data );
		}
	}


}


?>
