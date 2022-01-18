<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


class NewsletterModel {

	// constructor
	function NewsletterModel () {}


	// getTheme
	/**
	 * Checks for an exixting subscriptor
	 *
	 * @param	Int		$_theme_id	Theme ID
	 * @param	String		$_value		Match valuee
	 */
	function getTheme ($_theme_id) {

		switch ($_type) {
			case "md5":
				$sql = "	SELECT	COUNT(*)
					FROM	news_inscrit
					WHERE	md5(ins_id) = '{$_value}'"; 
				break; 
			case "mail":
				$sql = "SELECT	COUNT(*)
					FROM	news_inscrit
					WHERE	ins_mail = '{$_value}'"; 
				break; 
		}
		$eCount = dbGetUniqueValueFromRequete($sql);
		
		if ($eCount == 0) {
			// Subscriptor not found
			return false;   
		} else { 
			$sql = str_replace ("COUNT(*)", "*", $sql); 
			$aIns = dbGetObjectsFromRequete("news_inscrit", $sql);
			$oIns = $aIns[0];
			
			return $oIns;  
		}
	}
	
	
	// getAllTheme
	/**
	 * Checks for all existing thema
	 *
	 */
	function getAllTheme () {

		
		$sql = "	SELECT	COUNT(*)
			FROM	news_theme
			WHERE	theme_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY theme_libelle"; 
		//echo $sql;
			 
		$eCount = dbGetUniqueValueFromRequete($sql);
		
		if ($eCount == 0) {
			// Thema not found
			return false;   
		} else { 
			$sql = str_replace ("COUNT(*)", "*", $sql); 
			$aTh = dbGetObjectsFromRequete("news_theme", $sql); 
			
			return $aTh;  
		}
	}


	// getSubscriptor
	/**
	 * Checks for an exixting subscriptor
	 *
	 * @param	String		$_type		Match type
	 * @param	String		$_value		Match valuee
	 * @return	Object		Found subscriptor or false
	 */
	function getSubscriptor ($_type, $_value) {

		switch ($_type) {
			case "md5":
				$sql = "	SELECT	COUNT(*)
					FROM	news_inscrit
					WHERE	md5(ins_id) = '{$_value}'"; 
				break; 
			case "mail":
				$sql = "SELECT	COUNT(*)
					FROM	news_inscrit
					WHERE	ins_mail = '{$_value}'"; 
				break; 
		}
		//echo $sql;
		$eCount = dbGetUniqueValueFromRequete($sql);
		
		if ($eCount == 0) {
			// Subscriptor not found
			return false;   
		} else { 
			$sql = str_replace ("COUNT(*)", "*", $sql); 
			$aIns = dbGetObjectsFromRequete("news_inscrit", $sql);
			$oIns = $aIns[0];
			
			return $oIns;  
		}
	}


	// addSubscriptor
	/**
	 * Add a new subscriptor
	 *
	 * @param	String		$_email		Subscriptor email
	 * @param	String		$_lastname	Subscriptor lastname
	 * @param	String		$_firstname	Subscriptor firstname
	 * @param	Int		$_theme_id	News Theme ID
	 * @param	Array		$_criteria	Subscription criteria
	 * @return	Int		Subscription ID or ERROR CODE :	 0 (no action)
	 								-1 (error creating subscriptor)
	 								-2 (inactive subscription)
	 								-3 (already subscribed)
	 */
	function addSubscription ($_email, $_lastname, $_firstname, $_theme_id=1, $_criteria=Array()) {
		
		if (isset($_SESSION["idSite"]))
			$idSite = $_SESSION["idSite"];
		else	$idSite = 1;  

		$oTheme = new news_theme($_theme_id);
		$oIns = $this->getSubscriptor('mail', $_email);
		
		if ($oIns === false) {
			$oIns = new news_inscrit (); 
			$oIns->set_nom($_lastname);
			$oIns->set_prenom($_firstname); 
			$oIns->set_mail($_email);  
			$oIns->set_cms_site($idSite); 
			
			if (dbInsertWithAutoKey($oIns)) {
			
				$oX = new news_assoinscrittheme();
				$oX->set_news_inscrit($oIns->get_id());  
				$oX->set_news_theme($_theme_id); 
				if ($oTheme->get_abon_criteres() == 'Y')
					$oX->set_criteres(serialize($_criteria));
				$oX->set_statut(DEF_ID_STATUT_LIGNE); 
				return dbInsertWithAutoKey($oX);
			} else	return -1; 
		} else { 
			if ($oIns->get_cms_site() != $idSite) {
				// refresh site and language for re-subscription
				$oIns->set_cms_site($idSite);
				if (dbUpdate($oIns))
					return -1;
			}
			$aX = $this->getSubscriptions($oIns, $_theme_id);
			if (!empty($aX)) {
				if ($oTheme->get_abon_multiple() == 'Y') {
					$found = false;
					foreach ($aX as $subscription) {
						// Compare criteria
						if ($this->checkCriteriaVariation($subscription, $_criteria)) {
							$found = true;
							$oX = $subscription;
							$oX->set_statut(DEF_ID_STATUT_LIGNE); 
							$r = dbUpdate($oX);
							break;
						}
					}
					if ($found) {
						// reject already subscribed
						return -3;
					} else	$mode = 'create';
				} else {
					$oX = $aX[0];
					if ($this->checkCriteriaVariation($oX, $_criteria)) {
						// reject already subscribed
						return -3;
					} else {
						$mode = 'update';
						$update_id = $subscription->get_id();
						$oX->set_statut(DEF_ID_STATUT_LIGNE); 
						$r = dbUpdate($oX);
					}
				}
			} else 	$mode = 'create';
				
			if ($mode == 'create') {
				$oX = new news_assoinscrittheme();
				$oX->set_news_inscrit($oIns->get_id());  
				$oX->set_news_theme($_theme_id); 
				if ($oTheme->get_abon_criteres() == 'Y')
					$oX->set_criteres(serialize($_criteria));
				$oX->set_statut(DEF_ID_STATUT_LIGNE); 
				return dbInsertWithAutoKey($oX);
			} elseif ($mode == 'edit') {
				if ($oX->get_statut() == DEF_ID_STATUT_LIGNE) {
					if ($oTheme->get_abon_criteres() == 'Y')
						$oX->set_criteres(serialize($_criteria));
					$oX->set_statut(DEF_ID_STATUT_ATTEN);
					return dbUpdate($oX);
				} elseif ($oX->get_statut() == DEF_ID_STATUT_ATTEN)
					return $oIns->get_id();
				elseif ($oX->get_statut() == DEF_ID_STATUT_ARCHI) 
					return -2;
			} 
		}
		return 0;
	} 


	// unsubscribe
	/**
	 * Disable subscriptor
	 *
	 * @param	String		$_email		Subscriptor email
	 * @param	String		$_theme_id	Subscription theme ID
	 * @param	Array		$_criteria	Subscription criteria
	 * @return	Object		Found subscriptor or false
	 */
	function unsubscribe ($_email, $_theme_id, $_criteria=Array()) {

		$oTheme = new news_theme($_theme_id);
		$oIns = $this->getSubscriptor('mail', $_email);
		if ($oIns === false)
			return true;		// Should it change in case no subscriptor was found ? 
		//else	return setStatut($oIns, DEF_ID_STATUT_ARCHI);

		// Disable subscription
		$aX = $this->getSubscriptions($oIns, $_theme_id);
		if (!empty($aX)) {
			if ($oTheme->get_abon_multiple() == 'Y') {
				$success = true;
				foreach ($aX as $subscription) {
					// Handle selective unsubscription : NOT YET !
					// Compare criteria
					//if ($this->checkCriteriaVariation($subscription, $_criteria)) {
					//	$found = true;
					//	break;
					//}
					$subscription->set_statut(DEF_ID_STATUT_ARCHI);
					if (!dbUpdate($subscription))
						$success = false;
				}
				return $success;
			} else {
				$subscription = $aX[0];
				$subscription->set_statut(DEF_ID_STATUT_ARCHI);
				return dbUpdate($subscription);
			}
		}
		return true;		// Should it change in case no subscription was found ?
	} 


	// getSubscriptions
	/**
	 * Get existing subscriptions for theme
	 *
	 * @param	Object		$_subscriptor	news_inscrit instance
	 * @param	String		$_theme_id	Subscription theme ID
	 * @return	Array		List of Subscription instances or false
	 */
	function getSubscriptions ($_subscriptor, $_theme_id=1) {
	
		$sql = "	SELECT	COUNT(*)
			FROM	news_assoinscrittheme
			WHERE	xit_news_inscrit  = ".$_subscriptor->get_id()."
			AND	xit_news_theme = {$_theme_id};"; 
		//echo $sql;
		$eCount = dbGetUniqueValueFromRequete($sql);
		
		if ($eCount == 0) {
			// inscrit non trouvé  
			return false;   
		} else { 
			$sql = str_replace ("COUNT(*)", "*", $sql); 
			$aX = dbGetObjectsFromRequete("news_assoinscrittheme", $sql);

			return $aX;  
		}
	
	} 
	
	// getAllSubscriptionsTrue
	/**
	 * Get existing/validated subscriptions for one subscriptor
	 *
	 * @param	Object		$_subscriptor	news_inscrit instance 
	 * @return	Array		List of Subscription instances or false
	 */
	function getAllSubscriptionsTrue ($_subscriptor) {
	
		$sql = "	SELECT	COUNT(*)
			FROM	news_assoinscrittheme
			WHERE	xit_news_inscrit  = ".$_subscriptor->get_id()." 
			AND 	xit_statut = ".DEF_ID_STATUT_LIGNE.";"; 
		//echo $sql;
		$eCount = dbGetUniqueValueFromRequete($sql);
		
		if ($eCount == 0) {
			// inscrit non trouvé  
			return false;   
		} else { 
			$sql = str_replace ("COUNT(*)", "DISTINCT *", $sql); 
			$aX = dbGetObjectsFromRequete("news_assoinscrittheme", $sql);

			return $aX;  
		}
	
	} 


	function checkCriteriaVariation ($_subscription, $_criteria) {
		$reference = unserialize($_subscription->get_criteres());
		
		return newSizeOf(array_intersect_assoc($reference, $_criteria)) == newSizeOf($reference);
	}


}

?>
