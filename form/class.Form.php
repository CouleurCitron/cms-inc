<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle  form content rendering through chosen rendering

 

 


class Form  {
 

	var $type = null; 
	
	var $hmtl = ''; 
	var $nameForm = ''; 

	var $params = array();
	var $attributes = array();
	
	
	
	function getParams() {
		return $this->params; 
	}
	
	function getAttributes() {
		return $this->attributes; 
	}
	
	function setParams($params) {
		return $this->params; 
	}
	
	function setAttributes($attributes) {
		return $this->attributes; 
	}
	
	function setNameform($nameForm) {
		$this->nameForm = $nameForm ; 
	}
	 
	
	
	// getFields
	/**
	 * Set Form target type and ID
	 *
	 * @param	String		$type		The opinion attachment type
	 * @param	Int		$id		The attachment target ID
	 * @return	void
	 */
	function getFields ($file) {
	
		if (is_file ($file)) {
		$fh = fopen($file,'r');
		if ($fh) {
			while(!feof($fh)) {
				$sBodyXML.=fgets($fh);
			}
			 
			 
			global $stack;
			xmlStringParse($sBodyXML);
			
			$nodeList = $stack[0]["children"][0]["children"];
			
			$myAttributes = array ();
			
			
			// on récupère les attributs du formulaire
			 
			// nom du formulaire
			if (isset ($stack[0]["attrs"]["NAME"]) && $stack[0]["attrs"]["NAME"] != '' ) {
				$form_name = $stack[0]["attrs"]["NAME"]; 
				$myAttributes["NAME"] = $form_name;
				$this->setNameform($form_name);
			}
			else {
				$this->setNameform("myForm");
			}
			
			// email du destinataire des mails
			if (isset ($stack[0]["attrs"]["EMAIL"]) && $stack[0]["attrs"]["EMAIL"] != '' ) {
				$form_email = $stack[0]["attrs"]["EMAIL"];
				$myAttributes["EMAIL"] = $form_email;
			}
			
			// post du formulaire, si renseigné, on renvoie sur cette page
			if (isset ($stack[0]["attrs"]["POST"]) && $stack[0]["attrs"]["POST"] != '' ) {
				$form_post = $stack[0]["attrs"]["POST"]; 
				$myAttributes["POST"] = $form_post;
			}
			else { 
				$myAttributes["POST"] = $_SERVER['REQUEST_URI'];
			}
			
			// post du formulaire, si renseigné, on renvoie sur cette page
			if (isset ($stack[0]["attrs"]["MINISITE"]) && $stack[0]["attrs"]["MINISITE"] != '' ) {
				$form_minisite = $stack[0]["attrs"]["MINISITE"];
				$myAttributes["MINISITE"] = $form_minisite;
			}
			
			// texte à afficher après validation   
			if (isset ($stack[0]["children"][1]) && $stack[0]["children"][1]["name"] == "TEXTEOK" ) { 
				$form_texteok = $stack[0]["children"][1]["cdata"];  
				$myAttributes["TEXTEOK"] = $form_texteok;
			}
			
			// texte d'accusé de réception 
			if (isset ($stack[0]["children"][2]) && $stack[0]["children"][2]["name"] == "TEXTEAR" ) {
				$form_textear = $stack[0]["children"][2]["cdata"]; 
				$myAttributes["TEXTEAR"] = $form_textear;
			}
			
			 
			array_push ( $this->attributes , $myAttributes )	;	
			
			
			
			// on parcourt le fichier xml pour récupérer les champs et les paramètres
			foreach ($nodeList as $node)  {  
			
				$option = '';
				
				//pre_dump($node);
				$cheminreponse =  $node["attrs"]["VALUE"];
				
				
				if (isset ($node["children"])) {
					$nodeOptions = $node["children"];
					
					$aItems = array ();
					$myClass = '';
					$myClassField = '';
					$myClassConditions = array ();
					$myClassOrderBy = '';
					$myClassOrderBySens = '';
					
					
					
					foreach ($nodeOptions as $nodeLigne) {
					
						/* label pas besoin
						if ($nodeLigne["name"] == "LABEL") {
							$classLabel = $nodeLigne["attrs"]["CLASS"];
							 
						}*/
						
						// items
						if ($nodeLigne["name"] == "ITEM") { 
							//$aItems[$nodeLigne["attrs"]["VALUE"]] = $nodeLigne["attrs"]["LIBELLE"];
							$aOption["ID"] = $nodeLigne["attrs"]["VALUE"];
							$aOption["LIBELLE"] = $nodeLigne["attrs"]["LIBELLE"];
							if (!isset($nodeLigne["attrs"]["POSITION"]) || $nodeLigne["attrs"]["POSITION"] == "pre") 
								$aOption["POSITION"] = "pre";
							else 
								$aOption["POSITION"] = "post";
							array_push ($aItems, $aOption);
							 
						}
						
						
						// items
						if ($nodeLigne["name"] == "OPTION") { 
							
							foreach ($nodeLigne["attrs"] as $myOptionName => $myOptionValue ) {
								$option.=   ''.$myOptionName. ' ="'. $myOptionValue.'" ';
								
							}
							  
							 
						}
						
						if ($nodeLigne["name"] == "CLASS") { 
							
							
							
							foreach ($nodeLigne["attrs"] as $myOptionName => $myOptionValue ) {
								//echo $myOptionName." ".$myOptionValue."<br />";
								if ($myOptionName == "NAME") $myClass = $myOptionValue;
								else if ($myOptionName == "ITEM") $myClassField = $myOptionValue;
								else if ($myOptionName == "ORDERBY") $myClassOrderBy = $myOptionValue;
								else if ($myOptionName == "ORDERBYSENS") $myClassOrderBySens = $myOptionValue; 
								else array_push ( $myClassConditions , strtolower($myOptionName)." = ".$myOptionValue );
								
							}
							 
							  
							 
						}
					
					
					} 
					
				}
				   
				
				
				$myField = array (	'name' =>  $node["attrs"]["NAME"], 
						'id' => $node["attrs"]["ID"], 
						'type' => $node["attrs"]["TYPE"], 
						'values' => $aItems, 
						'oblig' => $node["attrs"]["OBLIG"], 
						//'label_options' => $classLabel, 
						'class' => ''.$node["attrs"]["CLASS"].'', 
						'option' => $option, 
						'small' => ''.$node["attrs"]["SMALL"].'', 
						'br' => $node["attrs"]["BR"]  ,
						'default' => $node["attrs"]["DEFAULT"]  ,
						'display' => $node["attrs"]["DISPLAY"] ,
						'pubkey' => $node["attrs"]["PUBKEY"] , // capctha
						'privkey' => $node["attrs"]["PRIVKEY"] , // capctha
						'theme' => $node["attrs"]["THEME"] , // capctha
						'lang' => $node["attrs"]["LANG"] , // capctha
						'pattern' => $node["attrs"]["PATTERN"]  ,
						'myClass' => $myClass, 
						'myClassField' => $myClassField,
						'myClassOrderBy' => $myClassOrderBy ,
						'myClassOrderBySens' => $myClassOrderBySens ,
						'myClassConditions' => $myClassConditions       
						
						);
						
						
				
				array_push ( $this->params , $myField )	;	
								
			}
		}
		else {
			print ("erreur lors de l'ouverture du fichier xml");
		}
		}
		else {
			print ("le fichier xml : ".$file." n'existe pas.");
		}
	}
	
	
	
	
	
	function prepareJS() { 
		
		echo $this->prepareJS_init(); 
	}
	
	function prepareJSSansEcho() { 
		return $this->prepareJS_init(); 
	}
	
	
	
	function prepareJS_init() {

		 
		$params  = $this->params;  
		$nameForm = $this->nameForm;
		
		
		
		$this->js.= '<script type="text/javascript">

		var captcha_ok = false;
		var go_submit = true; ';
	
		foreach ($params as $myField) {
				
				if($myField["type"] == "captcha") {
					$this->js.= 'var RecaptchaOptions = {';
					if ($myField["theme"] != "") $this->js.=  "			theme : '".$myField["theme"]."',\r ";
					if ($myField["lang"] != "") $this->js.=  "			lang : '".$myField["lang"]."'\r ";
					$this->js.= '};'; 
			
			}
				 
		}
		
	 
	$this->js.= '
	// custom validation calls for the given form
	function validate_form () { 
		window.messages = \'\';
		captcha_ok = false;
		go_submit = true;
		do_validate();
	}
	
	function reloadCaptcha()
	{
		document.getElementById(\'siimage\').src = \'/include/cms-inc/lib/securimage/securimage_show.php?sid=\' + Math.random();
	}
	
	
	function processForm()
    {  
		$.ajax({ 
			url: \'/include/cms-inc/lib/securimage/test_securimage.php\',
			type: "POST",
			data : $("#'.$nameForm.'").serialize(),
			success: function(transport) {  
				if (transport == 0) {
					//$(\'success_message\').show();
					//$("#'.$nameForm.'").reset();
					reloadCaptcha();
					setTimeout("validate_securimage(1)", 1000);
					return true;
				} else {
					setTimeout("validate_securimage(0)", 1000);
					return false;
				}
				 
			},
			error: function(err) {
				//alert("Ajax request failed");
			}
		});

		return false;
	}
	
	
	function validate_securimage(retour)
    { 
		if (retour) { 
			validateOK($("#'.$nameForm.' input[name=\'ct_captcha\']"));
			captcha_ok = true ; 
		}
		else { 
			validateKO($("#'.$nameForm.' input[name=\'ct_captcha\']"), "invalid_captcha"); 
			captcha_ok = true ;
			go_submit = false;  
		}
		do_validate();
		 
	}
	
	
	// custom validation calls for the given form
	function do_validate () {
		
		$ = jQuery.noConflict();
		 
		if (!captcha_ok) {
		
			';
		
		
		 
		foreach ($params as $myField) {
			
			
			
			$this->js.= $this->getJS($myField);
					 

		
		 
	
			
		}
		
				$this->js.= ' 
		}
		
		 
		if (captcha_ok) {  
			if (go_submit ) {
				 
				//setDialogMessage(\'error\', \'<div id="popup_contact"><br /><div class="popup_centre">\'+validationMessages["send_message"]+\'</div><br /></div>\');
				$("#'.$nameForm.'").submit();
			}
			else  {
			 
				setDialogMessage(\'error\', window.messages); 
			}
		}
		else {   
			if (window.messages == \'\') {
				$("#'.$nameForm.'").submit();
			}
			else {
				setDialogMessage( \'error\', window.messages);
			}
		} 
		 
	}
	
	 
 
	</script>';
	
		
		return $this->js;
		 
	}
	
	
	
	
	
	
	function getJS($field) {
		 
		if ($field['oblig'] == "true" ) {
			 
			switch ($field['type']) { 
				
					case 'text' :  
						$myJs = $this->getJs_text($field);
						break;
						
					case 'integer' :  
						$myJs = $this->getJs_integer($field);
						break;
						
					case 'phone' :  
						$myJs = $this->getJs_phone($field);
						break; 
						 
					case 'textarea' :  
						$myJs = $this->getJs_textarea($field);
						break;
						
					case 'email' :  
						$myJs = $this->getJs_email($field);
						break;
					
					case 'email_confirm' :  
						$myJs = $this->getJs_email_confirm($field);
						break;
					
					case 'file' :  
						$myJs = $this->getJs_text($field);
						break;
						
					case 'captcha' :  
						$myJs = $this->getJs_captcha($field);
						break;	
					
					case 'securimage' :  
						$myJs = $this->getJs_securimage($field);
						break;				 
						
					case 'radio' :  
						$myJs = $this->getJs_radio($field);
						break;
					
					case 'select' :  
						$myJs = $this->getJs_select($field);
						break;
						
					case 'checkbox' :  
						$myJs = $this->getJs_checkbox($field);
						break;
						
			}
			
			
			
		}
		else {
			switch ($field['type']) {  
				case 'integer' :  
					$myJs = $this->getJs_integer_not_oblig($field);
					break;
				case 'phone' :  
					$myJs = $this->getJs_phone_not_oblig($field);
					break; 
				case 'email' :  
						$myJs = $this->getJs_email_not_oblig($field);
						break;
					
				
				 
			}
		}
		
		 
		return $myJs;
	
	
	}
	
	
	
	
	function getJs_text ($field) {
		
		//echo $field["pattern"];
		 
		$nameForm = $this->nameForm;
		
		if (isset($field["pattern"]) && ($field["pattern"] != '')) {  
		
			 
			
			$js.= " var eCountPattern = 0;\n";
			$js.= " var sMessagePattern;\n";
			$js.= " sMessagePattern=\"\";\n";
			
			 
			$js.= "var filter=/^".$field["pattern"]."$/;\n";  
			  
			$js.= "if (!filter.test($(\"#".$nameForm." input[name='".$field["id"]."']\").val())){\n";
			$js.= " eCountPattern++;\n";
			$js.= " sMessagePattern+= \"Le champs ".$field["id"]." ne doit contenir que  les expressions suivantes: ".$field["pattern"]."\\n\";\n";
			$js.= "}\n"; 
			$js.= "if (eCountPattern == 0) {\n";  
			$js.= "validateOK.call($(\"#".$nameForm." input[name='".$field["id"]."']\"), $(\"#".$nameForm." input[name='".$field["id"]."']\"));";  
			$js.= "}\n";
			$js.= "else{\n"; 
			$js.= "	validateKO.call($(\"#".$nameForm." input[name='".$field["id"]."']\"), $(\"#".$nameForm." input[name='".$field["id"]."']\"), 'invalid_syntaxe');"; 
			$js.= "	go_submit = false\r";
			$js.= "}\n";
	 
		}
		else {
			$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r";
		}
		
		 
		return $js ;
		 
	}
	
	function getJs_textarea ($field) {
		
		 $nameForm = $this->nameForm;
		$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." textarea[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r";
		
		 
		return $js ;
		 
	}
	
	function getJs_select ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if (!formNotEmptySelect($(\"#".$nameForm." select[name='".$field["id"]."']\"), $(\"#".$nameForm." select[name='".$field["id"]."'] option:selected\"), validateOK , validateKO))\r
			go_submit = false\r";
			
			//$js = "alert($(\"select[name='".$field["id"]."'] option:selected\").val());\r";
		
		 
		return $js ;
		 
	}
	
	function getJs_checkbox ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if (!verifyCheckAllCheckbox($(\"#".$nameForm." input[name='".$field["id"]."']\"), \"".$field["id"]."\", validateOK , validateKO)) {\r
		
			go_submit = false\r
			
			}";
			
			//$js = "alert($(\"select[name='".$field["id"]."'] option:selected\").val());\r";
		
		 
		return $js ;
		 
	}
	
	function getJs_integer ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r
			
			else if (!formCheckValidInteger($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK, validateKO))\r
				go_submit = false;\r
				
				";
		
		 
		return $js ;
		 
	}
	
	function getJs_integer_not_oblig ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if ($(\"#".$nameForm." input[name='".$field["id"]."']\").val()!= '') {\r
		 if (!formCheckValidInteger($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK, validateKO))\r
				go_submit = false;\r
				}
				";
		
		 
		return $js ;
		 
	}
	 
	
	function getJs_phone ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r
			
			else if (!formCheckValidPhone($(\"#".$nameForm." input[name='".$field["id"]."']\"), true, validateOK, validateKO))\r
				go_submit = false;\r
				
				";
		
		 
		return $js ;
		 
	}
	
	function getJs_phone_not_oblig ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if ($(\"#".$nameForm." input[name='".$field["id"]."']\").val()!= '') {\r
				  if (!formCheckValidPhone($(\"#".$nameForm." input[name='".$field["id"]."']\"), true, validateOK, validateKO))\r
					go_submit = false;\r
				}
				";
		
		 
		return $js ;
		 
	}
	
	
	
	function getJs_radio ($field) {
		
		$nameForm = $this->nameForm; 
		$js =  "if (!formCheckNotEmptyCheckbox($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r";
		
		 
		return $js ;
		 
	}
	
 
	
	function getJs_email ($field) {
		
		 $nameForm = $this->nameForm;
			$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r
			
			else if (!formCheckValidEmail($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK, validateKO))\r
				go_submit = false;\r
			
			";
		
		 
		return $js ;
		 
	}
	
	function getJs_email_confirm ($field) {
		
		 $nameForm = $this->nameForm;
			$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK , validateKO))\r
			go_submit = false\r
			
			else if (!formCheckValidEmail($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK, validateKO))\r
				go_submit = false;\r
			else if (!formCheckConfirmEquals($(\"#".$nameForm." input[name='".$field["id"]."']\"), $(\"#".$nameForm." input[name='".str_replace("_confirm", "", $field["id"])."']\"),  validateOK, validateKO))\r
				go_submit = false;\r
			
			"; 
		 
		return $js ;
		 
	}
	 
	function getJs_email_not_oblig ($field) {
		
		 	$nameForm = $this->nameForm;
			$js =  "if ($(\"#".$nameForm." input[name='".$field["id"]."']\").val()!= '') {\r
					
				  if (!formCheckValidEmail($(\"#".$nameForm." input[name='".$field["id"]."']\"), validateOK, validateKO))\r
				go_submit = false;\r
				}
				";
				 
		
		 
		return $js ;
		 
	}
	
	function getJs_captcha ($field) {
		
		 $nameForm = $this->nameForm;
		 if ($field["display"] == "true") {
		 	
			
			$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='recaptcha_response_field']\"), validateOK, validateKO)) {\r
			go_submit = false; \r
		}\r
		else {\r
			
		 
			if (!captcha_ok) {\r  
				
				 $.post(\"/backoffice/cms/formulaire/captcha.php\", { privatekey: $(\"#".$nameForm." input#privatekey\").val(),  recaptcha_response_field: $(\"#".$nameForm." input#recaptcha_response_field\").val(), recaptcha_challenge_field: $(\"#".$nameForm." input#recaptcha_challenge_field\").val()},  \r
					function success(data){  \r 
						
						if(data == 1){  \r
							 validateOK($(\"#".$nameForm." input[name='recaptcha_response_field']\"));\r
							 captcha_ok = true ; \r
						
						}else{  \r 
							validateKO($(\"#".$nameForm." input[name='recaptcha_response_field']\"), \"invalid_captcha\"); \r
							captcha_ok = true ;\r
							go_submit = false;  \r
							 
						}\r
						do_validate();\r
						
					}\r
				);\r
				return;\r
			}\r
  
				
				
		}";
		
		}
		else {
			$js =  "captcha_ok = true ;\r";
			 
		}
		
		 
		return $js ;
		 
	}
	
	
	function getJs_securimage ($field) {
		
		 $nameForm = $this->nameForm;
		 if ($field["display"] == "true") {
		 	
			
			$js =  "if (!formCheckNotEmpty($(\"#".$nameForm." input[name='ct_captcha']\"), validateOK, validateKO)) {\r
			go_submit = false; \r
		}\r
		else {\r
			
		 
			if (!captcha_ok) {\r  
				
				
				processForm();
				
			}\r
  			return;\r
				
				
		}";
		
		}
		else {
			$js =  "captcha_ok = true ;\r";
			 
		}
		
		 
		return $js ;
		 
	}
	

 	function prepareFormAll() {
		$params  = $this->params;
		$nameForm = $this->nameForm;
		$attributes = $this->attributes; 
		$action = $attributes[0]["POST"] ; 
		$action .= '?source='.$nameForm;
		echo '<form id="'.$nameForm.'" name="'.$nameForm.'" action="'.$action.'" method="post" enctype="multipart/form-data">'."\n";
		$this->prepareForm();
		echo "\n".'</form>'."\n";
	}
	
	
	function prepareForm() {

		
		$params  = $this->params;
		
		 
		foreach ($params as $myField) {
			$this->html.= $this->getHtml($myField);
			
		}
		
		echo $this->html;
		 
	}
	
	function prepareFormSansEcho() {

		
		$params  = $this->params;
		
		 
		foreach ($params as $myField) {
			$this->html.= $this->getHtml($myField);
			
		}
		
		return $this->html;
		 
	}
	
	
	
	function getHtml($field) {

		
		  
		switch ($field['type']) { 
			
				case 'text' :  
					$myHtml = $this->getHtml_text($field);
					break;
				case 'integer' :  
					$myHtml = $this->getHtml_text($field);
					break;
				case 'phone' :  
					$myHtml = $this->getHtml_text($field);
					break;
				case 'textarea' :  
					$myHtml = $this->getHtml_textarea($field);
					break;
					
				case 'email' :  
					$myHtml = $this->getHtml_email($field);
					break;
					
				case 'email_confirm' :  
					$myHtml = $this->getHtml_email($field);
					break;
				
				case 'file' :  
					$myHtml = $this->getHtml_file($field);
					break;
					
				case 'captcha' :  
					$myHtml = $this->getHtml_captcha($field);
					break;
					
				case 'securimage' :  
					$myHtml = $this->getHtml_securimage($field);
					break;
				
				case 'submit' :  
					$myHtml = $this->getHtml_submit($field);
					break;
					
				case 'radio' :  
					$myHtml = $this->getHtml_radio($field);
					break;
					
				case 'select' :  
					$myHtml = $this->getHtml_select($field);
					break;
					
				case 'checkbox' :  
					$myHtml = $this->getHtml_checkbox($field);
					break;
					
				case 'hidden' :  
					$myHtml = $this->getHtml_hidden($field);
					break;
					
				case 'html' :  
					$myHtml = $this->getHtml_html($field);
					break;
					
		}
		
		 
		return $myHtml;
		
		 
	}
	
	
	function getHtml_text ($field) {
		//pre_dump($field);
		 
		
		 
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">'."\n";
		} else {
			$html = '<div class="default">'."\n";
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>'."\n";
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>'."\n";		
		
		$html.= '</label>'."\n";
		
		if ($field["br"] ==  "true") $html.= ' <br /> '."\n";
		
		$html.= '<input type="text" name="'.$field["id"].'" id="'.$field["id"].'" '.$field["option"].' ';
 

		if (isset($_POST[$field["id"]]) && $_POST[$field["id"]] != '' ) $html.= ' value="'.$_POST[$field["id"]].'" ';
		else if ($field["default"]) $html.= ' value="'.$field["default"].'" ';
		
		$html.= '/>'."\n";		
		
		$html.= '</div>'."\n";
		
		return $html ;
		 
	}
	
	 
	
	
	
	
	function getHtml_textarea ($field) {
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">';
		} else {
			$html = '<div class="default">';
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>';
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>';		
		
		$html.= '</label>';
		
		if ($field["br"] ==  "true") $html.= ' <br /> ';
		
		$html.= '<textarea  name="'.$field["id"].'" id="'.$field["id"].'"  '.$field["option"].' ' ;		
		
		$html.= '/>';
		
		if ($field["default"]) $html.= $field["default"];
		
		$html.= '</textarea>';
		
		$html.= '</div>';
		
		return $html ;
		 
	}
	
	function getHtml_email ($field) {
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">'."\n";
		} else {
			$html = '<div class="default">'."\n";
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>'."\n";
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>'."\n";		
		
		$html.= '</label>'."\n";
		
		if ($field["br"] ==  "true") $html.= ' <br /> '."\n";
		
		$html.= '<input type="text" name="'.$field["id"].'" id="'.$field["id"].'"  '.$field["option"].' ' ;
		
		if ($field["class"]) $html.= ' class="'.$field["class"].'" ';
		
		if (isset($_POST[$field["id"]]) && $_POST[$field["id"]] != '' ) $html.= ' value="'.$_POST[$field["id"]].'" ';
		else if ($field["default"]) $html.= ' value="'.$field["default"].'" ';
		
		$html.= '/>';		
		
		$html.= '</div>'."\n";
		
		return $html ;
		 
	}
	
	function getHtml_file ($field) {
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">';
		} else {
			$html = '<div class="default">';
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>';
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>';		
		
		$html.= '</label>';
		
		if ($field["br"] ==  "true") $html.= ' <br /> ';
		
		$html.= '<input type="file" name="'.$field["id"].'" id="'.$field["id"].'"  '.$field["option"].' ' ;
		
		if ($field["class"]) $html.= ' class="'.$field["class"].'" ';
		
		$html.= '/>';		
		
		$html.= '</div>';
		
		return $html ;
		 
	}
	
	/*
	function getHtml_captcha ($field) {
		
		 
		if ($field["display"] == "true") {
			
			//Ajout de la div du bloc
			if($field["class"]) {
				$html = '<div class="default '.$field["class"].'">';
			} else {
				$html = '<div class="default captcha">';
			}
					
			$html.= '<label for="'.$field["id"].'" ';
			
			$html.= '>'.$field["name"];
			
			if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>';					
			
			$html.= '</label>';
			
			if ($field["br"] ==  "true") $html.= ' <br /> ';
				
			$html.= '<span><img src="/include/cms-inc/lib/jhfcaptcha/jhfcaptcha.php?function=captchaimage&amp;random='.mt_rand(10000,99999).'" alt="captcha" /></span>';
		
			if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>';
		
			$html.= '<input type="text" name="'.$field["id"].'" id="'.$field["id"].'"  '.$field["option"].' ' ;
		
			$html.= '/>';
		
			$html.= '</div>';
		
		}
		
		return $html ;
		 
	}*/
	function getHtml_captcha ($field) {
		
		 
		if ($field["display"] == "true") {
			
			//Ajout de la div du bloc
			if($field["class"]) {
				$html = '<div class="default '.$field["class"].'">';
			} else {
				$html = '<div class="default captcha">';
			}
					
			$html.= '<label for="'.$field["id"].'" ';
			
			$html.= '>'.$field["name"];
			
			if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>';	
			
			if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>';						
			
			$html.= '</label>';
			
			if ($field["br"] ==  "true") $html.= ' <br /> ';
			
			$html.= recaptcha_get_html($field['pubkey']);
		
			$html.= '</div>';
			$html.= '<input type="hidden" id="privatekey" name="privatekey" value="'.$field["privkey"].'" />';
		
		}
		
		return $html ;
		 
	}
	
	function securimage_get_html() {
	
		$html = '<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="/include/cms-inc/lib/securimage/securimage_show.php?sid='.md5(uniqid()).'" alt="CAPTCHA Image" align="left"> 
    &nbsp;
    <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="reloadCaptcha(); this.blur(); return false"><img src="/include/cms-inc/lib/securimage/images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br /> 
    <input type="text" name="ct_captcha" size="12" maxlength="8" />';
	
		return $html ; 
	
	}
	 
	 
	 
	function getHtml_securimage ($field) {
		
		 
		if ($field["display"] == "true") {
			
			//Ajout de la div du bloc
			if($field["class"]) {
				$html = '<div class="default '.$field["class"].'">';
			} else {
				$html = '<div class="default captcha">';
			}
					
			$html.= '<label for="'.$field["id"].'" ';
			
			$html.= '>'.$field["name"];
			
			if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>';	
			
			if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>';						
			
			$html.= '</label>';
			
			if ($field["br"] ==  "true") $html.= ' <br /> ';
			
			$html.= $this->securimage_get_html();
		
			$html.= '</div>'; 
		
		}
		
		
		
		
		return $html ;
		 
	}
	
	
	 
	 
	function getHtml_radio ($field) {
	 
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">'."\n";
		} else {
			$html = '<div class="default">'."\n";
		}
				
		$html.= '<label for="'.$field["id"].'" '."\n";
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>'."\n";
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>';		
		
		$html.= '</label>'."\n";
		
		if ($field["br"] ==  "true") $html.= ' <br /> '."\n";
		
		if ($field["values"] && sizeof ($field["values"]) > 0 ) {
			
			$html.= '<div class="radio">'."\n";
			
			foreach ($field["values"] as $aValue => $aName) {
				
				$html.= '<span><input type="radio" name="'.$field["id"].'" value="'.$aName["ID"].'" '; 
				if (isset($field["default"]) && $field["default"] == $field["id"] ) $html.= ' checked ';
				$html.= '/>&nbsp;'.$aName["LIBELLE"].'</span>'."\n";				
			
			} 			  
			
			$html.= '</div>'."\n";
		
		}		
		
		$html.= '</div>'."\n";
		
		return $html ;
		 
	}
	
	
	function getHtml_select ($field) {
	 
	 	
		$translator =& TslManager::getInstance();
		$langpile = $translator->getLanguages();
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">'."\n";
		} else {
			$html = '<div class="default">'."\n";
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>'."\n";
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>'."\n";		
		
		$html.= '</label>'."\n";
		
		if ($field["br"] ==  "true") $html.= ' <br /> '."\n";
		
		 
		
					
		if ( $field["myClass"] == "" ) {
			
			
			if ($field["values"] && sizeof ($field["values"]) > 0 ) {
							
				$html.= '<select id="'.$field["id"].'"  name="'.$field["id"].'"  >'."\n";
				//$html.= '<option  value="-1">'.$field["name"].'</option>';
				
				foreach ($field["values"] as $aValue => $aName) { 
					$html.= '<option  value="'.$aName["ID"].'" ';
					if ( isset($field["default"]) && $field["default"] == $aName["ID"] ) $html.= ' selected ';
					$html.= '>'.$aName["LIBELLE"].'</option>'."\n";
					
				} 
			
				$html.= '</select>'."\n";			
			
			}		
			
		}
		else {
		 	
			global $stack;
			 
			eval("$"."oRes = new ".$field["myClass"]."();");
			
			$sXML = $oRes->XML;
			xmlStringParse($sXML);
			 
			$classePrefixe = $stack[0]["attrs"]["PREFIX"] ;
			$aNodeToSort = $stack[0]["children"];
			 
			
			
			$sql = "select * from ".$field["myClass"]." ";
			
			if (sizeof ($field["myClassConditions"] ) > 0 ) {
				foreach ( $field["myClassConditions"] as $myCondition => $myConditionValue) { 
					$field["myClassConditions"][$myCondition] = $classePrefixe."_".$myConditionValue;
				
				}
				$sql.= " WHERE ".join (" AND ", $field["myClassConditions"]);
			} 
			
			
			if ($field["myClassOrderBy"] !="") $sql.= " ORDER BY ".$classePrefixe."_". $field["myClassOrderBy"];
			if ($field["myClassOrderBySens"] !="") $sql.= " ". $field["myClassOrderBySens"];
			
			//echo $sql;
			
			$aRes = dbGetObjectsFromRequete($field["myClass"], $sql);  
			
			
			if (sizeof ($aRes) > 0) {
			
				$html.= '<select id="'.$field["id"].'"  name="'.$field["id"].'"  >';
				
				
				
				// valeur par défaut
				if ( $field["values"] &&  sizeof($field["values"]) > 0) {
					foreach ($field["values"] as $aValue => $aName) {
						if ($aName["POSITION"] == "pre") $html.= '<option  value="'.$aName["ID"].'" ';
						if ( isset($field["default"]) && $field["default"] == $aName["ID"] ) $html.= ' selected ';
						$html.= ' >'.$aName["LIBELLE"].'</option>';
					} 
				}
				else {
					if ($aName["POSITION"] == "pre") $html.= '<option  value="-1"> '.$field["name"].'</option>';
				}
				
				foreach ($aRes as $oRes) {
					
					eval("$"."myValue = "."$"."oRes->get_".$field["myClassField"]."();");
					eval("$"."myId = "."$"."oRes->get_"."id"."();");
					 
					
					
					if (isFieldTranslate($oRes, $field["myClassField"])){
					 
						$myValueTranslated = $translator->getByID($myValue, $_SESSION["id_langue"]);	
						
						$html.= '<option  value="'.$myId.'" ';
						if ( isset($field["default"]) && $field["default"] == $myId ) $html.= ' selected ';
						$html.= '>'.$myValueTranslated.'</option>';
						
					}
					else {
						$html.= '<option  value="'.$myId.'" ';
						if ( isset($field["default"]) && $field["default"] == $myId ) $html.= ' selected ';
						$html.= '>'.$myValue.'</option>';
					}
					
							
					 
				}
				
				
				if ( $field["values"] &&  sizeof($field["values"]) > 0) {
					foreach ($field["values"] as $aValue => $aName) {
						if ($aName["POSITION"] == "post") $html.= '<option  value="'.$aName["ID"].'" ';
						if ( isset($field["default"]) && $field["default"] == $aName["ID"] ) $html.= ' selected ';
						$html.= ' >'.$aName["LIBELLE"].'</option>'."\n";
					} 
				}
				else {
					if ($aName["POSITION"] == "post") $html.= '<option  value="-1"> '.$field["name"].'</option>'."\n";
				}
				
				
				
				$html.= '</select>';				
			}

		}
		
		$html.= '</div>';	
		
		return $html ;
		 
	}
	
	
	function getHtml_checkbox ($field) {
		 
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">'."\n";
		} else {
			$html = '<div class="default">'."\n";
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>'."\n";
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>'."\n";		
		
		$html.= '</label>'."\n";
		
		if ($field["br"] ==  "true") $html.= ' <br /> '."\n";
		 
	 
		if ($field["values"] && sizeof ($field["values"]) > 0 ) {
			$html.= '<div class="checkbox">';
						
			foreach ($field["values"] as $aValue => $aName) {
				if (sizeof($field["values"]) > 1) {
					$html.= '<span><input type="checkbox" name="'.$field["id"]."_".$aName["ID"].'" id="'.$field["id"]."_".$aName["ID"].'" value="'.$aName["ID"].'" ';
				}
				else {
					$html.= '<span><input type="checkbox" name="'.$field["id"].'" id="'.$field["id"].'" value="'.$aName["ID"].'" ';
				}
				
				if ($field["class"]) $html.= ' class="'.$field["class"].'" ';
				if ($field["default"]) $html.= ' checked="'.$field["default"].'" ';
				 
				$html.= ''.'  />&nbsp;'.$aName["LIBELLE"].'</span>';
			}
						  
			$html.= '</div>'."\n";
		
		}		
		
		$html.= '</div>'."\n";		
		
		return $html ;
		 
	}
	
	function getHtml_hidden ($field) {
				
		$html= '<input type="hidden" name="'.$field["id"].'" id="'.$field["id"].'" '.$field["option"].' />';		
		
		return $html;
		 
	}
	
	function getHtml_submit ($field) {
	
		$html= '<div class="default submit"><a id="loginbtn" href="javascript:validate_form();">'.$field["name"].'</a></div>';
				
		return $html ;
		 
	}
	
	function getHtml_html ($field) { 
		
		//Ajout de la div du bloc
		if($field["class"]) {
			$html = '<div class="default '.$field["class"].'">'."\n";
		} else {
			$html = '<div class="default">'."\n";
		}
				
		$html.= '<label for="'.$field["id"].'" ';
		
		$html.= '>'.$field["name"];
		
		if ($field["small"]) $html.= '<br /><small>'.$field["small"].'</small>'."\n";
		
		if ($field["oblig"] ==  "true") $html.= '<sup>*</sup>'."\n";		
		
		$html.= '</label>'."\n";
		
		if ($field["br"] ==  "true") $html.= ' <br /> '."\n";
		
		
		
		foreach ($field["values"] as $aValue => $aName) {
			$html.= $aName["LIBELLE"];
		}
		$html.= ''."\n";			
		
		$html.= '</div>'."\n";
		
		return $html ;
		 
	}
	

}

?>