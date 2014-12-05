<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


process_si_contact_form();

// The form processor PHP code
function process_si_contact_form()
{ 
	 
	$value = strip_tags($_POST['ct_captcha']); 
	$_POST['ct_captcha'] = htmlspecialchars(stripslashes(trim($value))); 
	$captcha = $_POST['ct_captcha']; // the user's entry for the captcha code 
	$errors = array();  // initialize empty error array

	//pre_dump( $_SESSION['securimage_code_value']);
	if (sizeof($errors) == 0) {
		 
		require_once ('include/cms-inc/lib/securimage/securimage.php');
		$securimage = new Securimage();  
		if ($securimage->check($captcha) == false) {
			$errors['captcha_error'] = 'Incorrect security code entered';
		}
	}

	if (sizeof($errors) == 0) {  
		//$return = array('error' => 0, 'message' => 'OK');
		//die(json_encode($return));
		echo 0;
	} else {
		$errmsg = '';
		foreach($errors as $key => $error) {
			// set up error messages to display with each field
			$errmsg .= " - {$error}\n";
		}

		$return = array('error' => 1, 'message' => $errmsg);
		//die(json_encode($return));
		echo 1;
	} 
} // function process_si_contact_form()


?>