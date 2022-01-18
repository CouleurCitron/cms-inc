<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// dedicated generation for the online payment procedure

class systempay_APIv1 {

	// constructor
	function systempay_APIv1 () {
		$this->systempay_url = 'https://systempay.cyberpluspaiement.com/vads-payment/';
	}


	// map webshop module DB fields getters with dedicated form field names
	function getMapping () {

		return Array(	
				'get_id'		=> 'order_id',
				'get_total_pay'	=> 'amount',
				//'get_structure'	=> 'order_info',
				'get_id_client'	=> 'cust_id'
				);
	}


	// generate request data for the payment submission procedure
	function generateRequest ($params) {

		$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		
		// saisir votre certificat
		//$params['key'] = WEBSHOP_SPAY_CERTIF;
		if (WEBSHOP_CB_API_MODE == 'PROD')
			$params['ctx_mode'] = "PRODUCTION";
		elseif (WEBSHOP_CB_API_MODE == 'TEST')
			$params['ctx_mode'] = "TEST";
		else	$params['ctx_mode'] = "TEST";		// default

		$params['amount']=floor(100*$params['amount']);
		$params['capture_delay'] = "";
		$params['currency'] = "978";
		$params['payment_cards'] = "";
		$params['payment_config'] = "SINGLE";		
		// Saisir votre identifiant boutique
		//$params['site_id'] = WEBSHOP_SPAY_ID;
		$params['trans_date'] = gmdate("YmdHis", time());
		$params['trans_id'] = $params['transaction_id'];
		$params['validation_mode'] = "";
		$params['version'] = "V1";
		$params['url_return'] = $this_script.'?action=success-'.$_POST['pay_mode'];
		$params['url_success'] = $params['url_return'];
		$params['url_cancel'] = $this_script.'?action=cancel-'.$_POST['pay_mode'];
		$params['url_referral'] = $params['url_cancel'];
		$params['url_refused'] = $params['url_cancel'];
		$params['url_error'] = $params['url_cancel'];
		
		//notify_url $this_script.'?action=ipn-'.$_POST['pay_mode']
		
		$signature_contents = $params['version'] . "+" . $params['site_id'] . "+" . $params['ctx_mode'] . "+"
		. $params['trans_id'] . "+" . $params['trans_date'] . "+" . $params['validation_mode'] . "+"
		. $params['capture_delay'] . "+" . $params['payment_config'] . "+" . $params['payment_cards'] . "+"
		. $params['amount'] . "+" . $params['currency'] . "+" . $params['key'];
		
		$params['signature'] = sha1($signature_contents);
		
		unset($params['key']);
		unset($params['transaction_id']);

		$form = '<form method="post" id="systempay_form" name="systempay_form" action="'.$this->systempay_url.'">
			';
			
			foreach ($params as $key => $val)
				$form .= "\n".'<input type="hidden" name="'.$key.'" id="'.$key.'" value="'.$val.'" />';


		/*
		$form .= '<input type="hidden" name="return" value="'.$this_script.'?action=success-'.$_POST['pay_mode'].'"/>
			<input type="hidden" name="cancel_return" value="'.$this_script.'?action=cancel-'.$_POST['pay_mode'].'"/>
			<input type="hidden" name="notify_url" value="'.$this_script.'?action=ipn-'.$_POST['pay_mode'].'"/>
			';*/
		
		$form .= '</form>';

		echo $form;
	}


	// generate response data for the payment procedure return
	function handleResponse () {
		error_log('*********** handleResponse');
		// valeur du certificat.
		// Ici cette valeur est ecrite en dur mais vous devez la lire depuis votre base dedonnées
		$key=WEBSHOP_SPAY_CERTIF;
		//--------------------------------------------------------------------------------------------------------------------------
		//Calcul de la signature pour ensuite la vérifier avec celle reçue
		//--------------------------------------------------------------------------------------------------------------------------
		//vérification reception variable hash
		// hash reçu => alors reception dépuis URL Serveur ( auto réponse )
		// Attention vous devez renseigner l'URL serveur dans l'outil de gestion de caisse.
		if (isset($_POST['hash'])) {
		$chaine=$_POST['version'] . "+" . $_POST['site_id'] . "+" .
		$_POST['ctx_mode'] . "+"
		. $_POST['trans_id'] . "+" . $_POST['trans_date'] . "+" . $_POST['validation_mode']
		. "+"
		. $_POST['capture_delay'] . "+" . $_POST['payment_config'] . "+" .
		$_POST['card_brand'] ."+" . $_POST['card_number'] . "+"
		. $_POST['amount'] . "+" . $_POST['currency'] ."+" . $_POST['auth_mode'] ."+" .
		$_POST['auth_result'] ."+" . $_POST['auth_number'] ."+"
		. $_POST['warranty_result'] ."+" . $_POST['payment_certificate'] ."+" .
		$_POST['result'] ."+" . $_POST['hash'] . "+" . $key;
		}
		// hash pas reçu => alors reception depuis le click retour à la boutique
		else {
		$chaine=$_POST['version'] . "+" . $_POST['site_id'] . "+" .
		$_POST['ctx_mode'] . "+"
		. $_POST['trans_id'] . "+" . $_POST['trans_date'] . "+" . $_POST['validation_mode']
		. "+"
		. $_POST['capture_delay'] . "+" . $_POST['payment_config'] . "+" .
		$_POST['card_brand'] ."+" . $_POST['card_number'] . "+"
		. $_POST['amount'] . "+" . $_POST['currency'] ."+" . $_POST['auth_mode'] ."+" .
		$_POST['auth_result'] ."+" . $_POST['auth_number'] ."+"
		. $_POST['warranty_result'] ."+" . $_POST['payment_certificate'] ."+" .
		$_POST['result'] ."+" . $key;
		}
		$signature_shop=sha1($chaine);
		
		//--------------------------------------------------------------------------------------------------------------------------
		// comparaison de la signature reçue et celle calculée
		//--------------------------------------------------------------------------------------------------------------------------
		if ($_POST['signature']==$signature_shop) {
		// ok traitement de la commande
		error_log( "rsp = Controle Signature ok - Traitement commande");
		// le paiement est-il accepté?
		if ($_POST['result']=="00"){
		error_log( "rsp = paiement ok");
		}
		else{
		error_log( "rsp = paiement refus autorisation");
		}
		}
		else {
		// nok ne pas traiter la commande risque de fraude
		error_log( "rsp = Controle signature Nok - risque de fraude");
		}
		
		$track['version'] = $_POST['version'];
		$track['site_id'] = $_POST['site_id'];
		$track['ctx_mode'] = $_POST['ctx_mode'];
		$track['trans_id'] = $_POST['trans_id'];
		$track['trans_date'] = $_POST['trans_date'];
		$track['validation_mode'] = $_POST['validation_mode'];
		$track['capture_delay'] = $_POST['capture_delay'];
		$track['payment_config'] = $_POST['payment_config']; 
		$track['card_brand'] = $_POST['card_brand'];
		$track['card_number'] = $_POST['card_number']; 
		$track['amount'] = $_POST['amount'];
		$track['currency'] = $_POST['currency'];
		$track['auth_mode'] = $_POST['auth_mode']; 
		$track['auth_result'] = $_POST['auth_result'];
		$track['auth_number'] = $_POST['auth_number'];
		$track['warranty_result'] = $_POST['warranty_result'];
		$track['payment_certificate'] = $_POST['payment_certificate'];
		$track['result'] = $_POST['result'];
		$track['hash'] = $_POST['hash'];
		$track['order_id'] = $_POST['order_id'];	

		return (Array) Array(	'id_order'	=> $track['order_id'],
					'success'	=> ($track['result'] == '00' ? true : false),
					//'error'		=> $track['error'],
					'track'		=> $track );

	}


	function handleIPN () {
		error_log('*********** handleIPN');
		// valeur du certificat.
		// Ici cette valeur est ecrite en dur mais vous devez la lire depuis votre base dedonnées
		$key=WEBSHOP_SPAY_CERTIF;
		//--------------------------------------------------------------------------------------------------------------------------
		//Calcul de la signature pour ensuite la vérifier avec celle reçue
		//--------------------------------------------------------------------------------------------------------------------------
		//vérification reception variable hash
		// hash reçu => alors reception dépuis URL Serveur ( auto réponse )
		// Attention vous devez renseigner l'URL serveur dans l'outil de gestion de caisse.
		if (isset($_POST['hash'])) {
		$chaine=$_POST['version'] . "+" . $_POST['site_id'] . "+" .
		$_POST['ctx_mode'] . "+"
		. $_POST['trans_id'] . "+" . $_POST['trans_date'] . "+" . $_POST['validation_mode']
		. "+"
		. $_POST['capture_delay'] . "+" . $_POST['payment_config'] . "+" .
		$_POST['card_brand'] ."+" . $_POST['card_number'] . "+"
		. $_POST['amount'] . "+" . $_POST['currency'] ."+" . $_POST['auth_mode'] ."+" .
		$_POST['auth_result'] ."+" . $_POST['auth_number'] ."+"
		. $_POST['warranty_result'] ."+" . $_POST['payment_certificate'] ."+" .
		$_POST['result'] ."+" . $_POST['hash'] . "+" . $key;
		}
		// hash pas reçu => alors reception depuis le click retour à la boutique
		else {
		$chaine=$_POST['version'] . "+" . $_POST['site_id'] . "+" .
		$_POST['ctx_mode'] . "+"
		. $_POST['trans_id'] . "+" . $_POST['trans_date'] . "+" . $_POST['validation_mode']
		. "+"
		. $_POST['capture_delay'] . "+" . $_POST['payment_config'] . "+" .
		$_POST['card_brand'] ."+" . $_POST['card_number'] . "+"
		. $_POST['amount'] . "+" . $_POST['currency'] ."+" . $_POST['auth_mode'] ."+" .
		$_POST['auth_result'] ."+" . $_POST['auth_number'] ."+"
		. $_POST['warranty_result'] ."+" . $_POST['payment_certificate'] ."+" .
		$_POST['result'] ."+" . $key;
		}
		$signature_shop=sha1($chaine);
		
		//--------------------------------------------------------------------------------------------------------------------------
		// comparaison de la signature reçue et celle calculée
		//--------------------------------------------------------------------------------------------------------------------------
		if ($_POST['signature']==$signature_shop) {
			// ok traitement de la commande
			error_log( "ipn = Controle Signature ok - Traitement commande");
			// le paiement est-il accepté?
			if ($_POST['result']=="00"){
				error_log( "ipn = paiement ok");
			}
			else{
				error_log( "ipn = paiement refus autorisation");
			}
		}
		else {
			// nok ne pas traiter la commande risque de fraude
			error_log( "ipn = Controle signature Nok - risque de fraude");
		}
		
		$track['version'] = $_POST['version'];
		$track['site_id'] = $_POST['site_id'];
		$track['ctx_mode'] = $_POST['ctx_mode'];
		$track['trans_id'] = $_POST['trans_id'];
		$track['trans_date'] = $_POST['trans_date'];
		$track['validation_mode'] = $_POST['validation_mode'];
		$track['capture_delay'] = $_POST['capture_delay'];
		$track['payment_config'] = $_POST['payment_config']; 
		$track['card_brand'] = $_POST['card_brand'];
		$track['card_number'] = $_POST['card_number']; 
		$track['amount'] = correctRoundDisplay($_POST['amount']/100);
		$track['currency'] = $_POST['currency'];
		$track['auth_mode'] = $_POST['auth_mode']; 
		$track['auth_result'] = $_POST['auth_result'];
		$track['auth_number'] = $_POST['auth_number'];
		$track['warranty_result'] = $_POST['warranty_result'];
		$track['payment_certificate'] = $_POST['payment_certificate'];
		$track['result'] = $_POST['result'];
		$track['hash'] = $_POST['hash'];
		$track['order_id'] = $_POST['order_id'];
		
		return (Array) Array(	'id_order'	=> $track['order_id'],
					'success'	=> ($track['result'] == '00' ? true : false),
					'error'		=> $track['result'],
					'track'		=> $track );

	}
}

?>