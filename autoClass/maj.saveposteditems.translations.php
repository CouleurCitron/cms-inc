<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/autoClass/lib.inc.php');
// translation data
// Added by Luc - 6 oct. 2009			

if (DEF_APP_USE_TRANSLATIONS && $aNodeToSort[$i]["attrs"]["TRANSLATE"]) {
	if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int") {
		if ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'reference') {
			// chercher la premiere langue saisie
			foreach ($langpile as $lang_id => $lang_props) {
				if (is_post($form_field."_".$lang_props['libellecourt'])){
					$tsl_placeholder = 'no input // '. $lang_props['libellecourt']. ' = '.rewriteIfNeeded($_POST[$form_field."_".$lang_props['libellecourt']]);
					break;
				}
			}
			// default language
			//echo "TEST : ".$tsl_default." : ".$form_field."_".$langpile[DEF_APP_LANGUE]['libellecourt']."<br />";
			$tsl_default = rewriteIfNeeded($_POST[$form_field."_".$langpile[DEF_APP_LANGUE]['libellecourt']]);
			if ($tsl_default == '') {// pas de saisie de la langue defautl								
				// chercher la premiere langue saisie
				$tsl_default = $tsl_placeholder;
				//echo 'pas de saisie de la langue defautl = '.$tsl_default;
			}
								
			if ($tsl_default != ''){ // la langue par défaut a été saisie
				//echo ' la langue par défaut a été saisie';
				$tsl_table = Array();
				foreach ($langpile as $lang_id => $lang_props) {
					if ($lang_id != DEF_APP_LANGUE) {
						//echo $lang_id. " [form_fieldlang_props['libellecourt'] : ".$_POST[$form_field."_".$lang_props['libellecourt']]."<br />";
						//if ($_POST[$form_field."_".$lang_props['libellecourt']] != '')
						//if (!isset($_POST[$form_field."_".$lang_props['libellecourt']]))
						if ($_POST[$form_field."_".$lang_props['libellecourt']]==''){
							$tsl_table[$lang_id] = rewriteIfNeeded($tsl_placeholder);
						}
						else{
							$tsl_table[$lang_id] = rewriteIfNeeded($_POST[$form_field."_".$lang_props['libellecourt']]);
						}
							
					}
				}
				//error_log('addTranslation maj '.$form_field);
				$_POST[$form_field] = $translator->addTranslation($tsl_default, $tsl_table);
				
				unset($tsl_placeholder);
				unset($tsl_default);
				
			} else {
				// unsset reference when updating to an empty text
				$_POST[$form_field] = -1;
			}
		} elseif ($aNodeToSort[$i]["attrs"]["TRANSLATE"] == 'value') {
			//echo $aNodeToSort[$i]["attrs"]["LENGTH"];
		}
	} elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
	
	}		
} // end translation data
?>