<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// dedicated generation for the online payment procedure

class webaffaires_API {

	// constructor
	function webaffaires_API () {}


	// map webshop module DB fields getters with dedicated form field names
	function getMapping () {

		return Array(	'get_id'		=> 'order_id',
				'get_total_pay'	=> 'amount',
				'get_structure'	=> 'caddie',
				'get_id_client'	=> 'customer_id' );
	}


	// generate request data for the payment submission procedure
	function generateRequest ($params) {
		
		
		 
		$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		//$this_script = 'http://luc.raujolles.hephaistos.couleurcitron.ath.cx'.$_SERVER['PHP_SELF'];
		//$this_script = 'http://raujolles.couleur-citron.com'.$_SERVER['PHP_SELF'];

		// parse values for specific formatting
		$params['amount'] = round(floatval($params['amount'])*100);
		//$params['caddie'] = rawurlencode($params['caddie']);
		$params['caddie'] = '';

		// add custom payment solution fields
		$params['normal_return_url']	= $this_script."?action=success-".$_POST['pay_mode'];
		$params['cancel_return_url']	= $this_script."?action=cancel-".$_POST['pay_mode'];
		$params['automatic_response_url']	= $this_script."?action=ipn-".$_POST['pay_mode'];

		//viewArray($params, 'params');

		// Initialisation du chemin du fichier pathfile
		// -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
		// -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
		if (defined ('DEF_SHP_PATHFILE')) {
			$parm = "$parm pathfile=".DEF_SHP_PATHFILE;
		}
		else {
			$parm = "$parm pathfile=".$_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/TPI-API/param/pathfile";
		}
		
		$parm = "$parm pathfile=/var/www/recette/modules/webshop/TPI-API/param/pathfile";
		
		
		foreach ($params as $key => $val)
			$parm = "$parm ".$key."=".$val;

		//		Si aucun transaction_id n'est affecté, request en génère
		//		un automatiquement à partir de heure/minutes/secondes
		//		Référez vous au Guide du Programmeur pour
		//		les réserves émises sur cette fonctionnalité

		//$parm = "$parm transaction_id=123456";



		//		Affectation dynamique des autres paramètres
		// 		Les valeurs proposées ne sont que des exemples
		// 		Les champs et leur utilisation sont expliqués dans le Dictionnaire des données

		//		$parm = "$parm normal_return_url=".$this_script."?action=success";
		//		$parm = "$parm cancel_return_url=".$this_script."?action=cancel";
		//		$parm = "$parm automatic_response_url=".$this_script."?action=ipn";
		//		$parm = "$parm language=fr";
		//		$parm="$parm payment_means=CB,2,VISA,2,MASTERCARD,2";
		//		$parm="$parm header_flag=no";
		//		$parm="$parm capture_day=";
		//		$parm="$parm capture_mode=";
		//		$parm="$parm bgcolor=";
		//		$parm="$parm block_align=";
		//		$parm="$parm block_order=";
		//		$parm="$parm textcolor=";
		//		$parm="$parm receipt_complement=";
		//		$parm = "$parm caddie=";
		//		$parm = "$parm customer_id=";
		//		$parm = "$parm customer_email=";
		//		$parm="$parm customer_ip_address=";
		//		$parm="$parm data=";
		//		$parm="$parm return_context=";
		//		$parm="$parm target=";
		//		$parm = "$parm order_id=";

		// Initialisation du chemin de l'executable request
		// -> Windows : $path_bin = "c:\\repertoire\\bin\\request";
		// -> Unix    : $path_bin = "/home/repertoire/bin/request";
		if (defined ('DEF_SHP_REQUEST')) {
			$path_bin = DEF_SHP_REQUEST;
		}
		else {
			$path_bin = $_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/TPI-API/bin/request";
		}
		
		$path_bin = "/var/www/recette/modules/webshop/TPI-API/bin/request";
		//$path_bin = "/var/production/raujolles/www/modules/webshop/custom/TPI-API/bin/request";

		// Appel du binaire request
		//echo "$path_bin $parm";
		$result = exec("$path_bin $parm");

		// sortie de la fonction : $result=!code!error!buffer!
		// - code = 0	: la fonction génère une page html contenue dans la variable buffer
		// - code = -1 	: La fonction retourne un message d'erreur dans la variable error

		// On separe les differents champs et on les met dans une variable tableau
		$tableau = explode ("!", "$result");
		 

		// récupération des paramètres
		$code = $tableau[1];
		$error = $tableau[2];
		$message = $tableau[3];

		// analyse du code retour
		if (( $code == "" ) && ( $error == "" )) {
			echo "<br/><center>erreur appel request</center><br/>executable request non trouve $path_bin";
	 	} else if ($code != 0){
	 		// Erreur, affiche le message d'erreur
			echo "<center><b><h2>Erreur appel API de paiement.</h2></center></b><br/><br/><br/> message erreur : $error <br/>";
		} else {
			// OK, affiche le formulaire HTML
			echo "<br/><br/>";
			// OK, affichage du mode DEBUG si activé
			echo "$error <br/>";
			echo "$message <br/>";
		}
	}


	// generate response data for the payment procedure return
	function handleResponse () {

		// Récupération de la variable cryptée DATA
		$message = "message={$_POST['DATA']}";

		// Initialisation du chemin du fichier pathfile
		// -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
		// -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
		if (defined ('DEF_SHP_PATHFILE')) {
			$pathfile="pathfile=".DEF_SHP_PATHFILE;
		}
		else {
			$pathfile="pathfile=".$_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/TPI-API/param/pathfile";
		}

		$pathfile="pathfile=/var/www/recette/modules/webshop/TPI-API/param/pathfile";
		
		// Initialisation du chemin de l'executable response
		// -> Windows : $path_bin = "c:\\repertoire\\bin\\response";
		// -> Unix    : $path_bin = "/home/repertoire/bin/response";
		if (defined ('DEF_SHP_RESPONSE')) {
			$path_bin = DEF_SHP_RESPONSE;
		}
		else {
			$path_bin = $_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/TPI-API/bin/response";
		}
		
		$path_bin = "/var/www/recette/modules/webshop/TPI-API/bin/response";

		// Appel du binaire response
		
		//echo $path_bin.' '.$pathfile.' '.$message;
		$result = exec("$path_bin $pathfile $message");

		// sortie de la fonction : $result=!code!error!buffer!
		// - code = 0	: la fonction génère une page html contenue dans la variable buffer
		// - code = -1 	: La fonction retourne un message d'erreur dans la variable error

		// On separe les differents champs et on les met dans une variable tableau
		$tableau = explode ("!", "$result");

		// récupération des paramètres
		$code = $tableau[1];
		$error = $tableau[2];
		$merchant_id = $tableau[3];
		$merchant_country = $tableau[4];
		$amount = $tableau[5]/100;
		$transaction_id = $tableau[6];
		$payment_means = $tableau[7];
		$transmission_date= $tableau[8];
		$payment_time = $tableau[9];
		$payment_date = $tableau[10];
		$response_code = $tableau[11];
		$payment_certificate = $tableau[12];
		$authorisation_id = $tableau[13];
		$currency_code = $tableau[14];
		$card_number = $tableau[15];
		$cvv_flag = $tableau[16];
		$cvv_response_code = $tableau[17];
		$bank_response_code = $tableau[18];
		$complementary_code = $tableau[19];
		$complementary_info = $tableau[20];
		$return_context = $tableau[21];
		$caddie = rawurldecode($tableau[22]);
		$receipt_complement = $tableau[23];
		$merchant_language = $tableau[24];
		$language = $tableau[25];
		$customer_id = $tableau[26];
		$order_id = $tableau[27];
		$customer_email = $tableau[28];
		$customer_ip_address = $tableau[29];
		$capture_day = $tableau[30];
		$capture_mode = $tableau[31];
		$data = $tableau[32];

		// analyse du code retour
		if ($code == '' && $error == '')
			$track['error'] = "executable response bin $path_bin not found";
	 	else	$track['error'] =  $error;

		// récupération des paramètres
		$track['merchant_id'] = $tableau[3];
		$track['merchant_country'] = $tableau[4];
		$track['amount'] = $tableau[5]/100;
		$track['transaction_id'] = $tableau[6];
		$track['payment_means'] = $tableau[7];
		$track['transmission_date'] = $tableau[8];
		$track['payment_time'] = $tableau[9];
		$track['payment_date'] = $tableau[10];
		$track['response_code'] = $tableau[11];
		$track['payment_certificate'] = $tableau[12];
		$track['authorisation_id'] = $tableau[13];
		$track['currency_code'] = $tableau[14];
		$track['card_number'] = $tableau[15];
		$track['cvv_flag'] = $tableau[16];
		$track['cvv_response_code'] = $tableau[17];
		$track['bank_response_code'] = $tableau[18];
		$track['complementary_code'] = $tableau[19];
		$track['complementary_info'] = $tableau[20];
		$track['return_context'] = $tableau[21];
		$track['caddie'] = rawurldecode($tableau[22]);
		$track['receipt_complement'] = $tableau[23];
		$track['merchant_language'] = $tableau[24];
		$track['language'] = $tableau[25];
		$track['customer_id'] = $tableau[26];
		$track['order_id'] = $tableau[27];
		$track['customer_email'] = $tableau[28];
		$track['customer_ip_address'] = $tableau[29];
		$track['capture_day'] = $tableau[30];
		$track['capture_mode'] = $tableau[31];
		$track['data'] = $tableau[32]; 



		return (Array) Array(	'id_order'	=> $track['order_id'],
					'success'	=> ($track['response_code'] == '00' ? true : false),
					'error'		=> $track['error'],
					'track'		=> $track );

	}


	function handleIPN () {

		// Récupération de la variable cryptée DATA
		$message = "message={$_POST['DATA']}";

		// Initialisation du chemin du fichier pathfile
		// -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
		// -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
		if (preg_match('/hephaistos/', $_SERVER['HTTP_HOST']) == 1 ) {
			$pathfile="pathfile=".$_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/dev/TPI-API/param/pathfile";
		}
		else {
			$pathfile="pathfile=".$_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/TPI-API/param/pathfile";
		}
		 
		$pathfile = "pathfile=/var/www/recette/modules/webshop/TPI-API/param/pathfile";
		// Initialisation du chemin de l'executable response
		// -> Windows : $path_bin = "c:\\repertoire\\bin\\response";
		// -> Unix    : $path_bin = "/home/repertoire/bin/response"; 
		
		if (defined ('DEF_SHP_RESPONSE')) {
			$path_bin = DEF_SHP_RESPONSE;
		}
		else {
			$path_bin = $_SERVER['DOCUMENT_ROOT']."/modules/webshop/custom/TPI-API/bin/response";
		}
		
		$path_bin = "/var/www/recette/modules/webshop/TPI-API/bin/response";
		
		// Appel du binaire response
		$result = exec("$path_bin $pathfile $message");

		// sortie de la fonction : $result=!code!error!buffer!
		// - code = 0	: la fonction génère une page html contenue dans la variable buffer
		// - code = -1 	: La fonction retourne un message d'erreur dans la variable error

		// On separe les differents champs et on les met dans une variable tableau
		$tableau = explode ("!", "$result");

		$code = $tableau[1];
		$error = $tableau[2];
		$track = Array();

		if ($code == '' && $error == '')
			$track['error'] = "executable response bin $path_bin not found";
	 	else	$track['error'] =  $error;

		// récupération des paramètres
		$track['merchant_id'] = $tableau[3];
		$track['merchant_country'] = $tableau[4];
		$track['amount'] = $tableau[5]/100;
		$track['transaction_id'] = $tableau[6];
		$track['payment_means'] = $tableau[7];
		$track['transmission_date'] = $tableau[8];
		$track['payment_time'] = $tableau[9];
		$track['payment_date'] = $tableau[10];
		$track['response_code'] = $tableau[11];
		$track['payment_certificate'] = $tableau[12];
		$track['authorisation_id'] = $tableau[13];
		$track['currency_code'] = $tableau[14];
		$track['card_number'] = $tableau[15];
		$track['cvv_flag'] = $tableau[16];
		$track['cvv_response_code'] = $tableau[17];
		$track['bank_response_code'] = $tableau[18];
		$track['complementary_code'] = $tableau[19];
		$track['complementary_info'] = $tableau[20];
		$track['return_context'] = $tableau[21];
		$track['caddie'] = rawurldecode($tableau[22]);
		$track['receipt_complement'] = $tableau[23];
		$track['merchant_language'] = $tableau[24];
		$track['language'] = $tableau[25];
		$track['customer_id'] = $tableau[26];
		$track['order_id'] = $tableau[27];
		$track['customer_email'] = $tableau[28];
		$track['customer_ip_address'] = $tableau[29];
		$track['capture_day'] = $tableau[30];
		$track['capture_mode'] = $tableau[31];
		$track['data'] = $tableau[32];

		return (Array) Array(	'id_order'	=> $track['order_id'],
					'success'	=> ($track['response_code'] == '00' ? true : false),
					'error'		=> $track['error'],
					'track'		=> $track );
	}


}

?>
