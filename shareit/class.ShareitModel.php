<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données share it ("envoi à un ami")


// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shr_track.class.php');

class ShareitModel extends BaseModuleModel {

	// constructor
	function ShareitModel () {}


	// track
	/**
	 * Track Shareit data in database
	 *
	 * @param	String		$email		The visitor email
	 * @param	Bool		$trackit		The visitor wants to register
	 * @param	String		$lastname	The visitor last name
	 * @param	String		$firstname	The visitor first name
	 * @return	Array	Shareit question and answers structure
	 */
	function track ($email, $trackit, $lastname, $firstname) {
		
		$existing = dbGetObjectsFromFieldValue('shr_track', Array('get_email'), Array($email));
		if (!empty($existing)) {
			// User is already tracked
			if (newSizeOf($existing) > 1){
				// Error : multiple records
				return false;
			} else {
				$trk = $existing[0];
				$trk->set_count($trk->get_count()+1);
				$trk->set_updated(date("YYYY/mm/dd HH:ii:ss"));

				if ($this->debug)
					echo "New Shareit tracking for visitor {$email}<br/>";

				return dbUpdate($trk);
			}
		} else {
			// New Shareit user
			$trk = new shr_track();
			$trk->set_email($email);
			$trk->set_tracked(($trackit ? 'Y' : 'N'));
			$trk->set_lastname($lastname);
			$trk->set_firstname($firstname);
			$trk->set_count(1);
			$trk->set_created(date("YYYY/mm/dd HH:ii:ss"));

			if ($this->debug)
				echo "New Shareit tracking for visitor {$email}<br/>";

			return dbInsertWithAutoKey($trk);
		}
	}


}
