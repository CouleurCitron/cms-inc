<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle Shareit form content rendering through chosen rendering

// structures des donnéees de formulaires:
// prefix 'shareit_' de données POST

// - shareit_sender_firstname
// - shareit_sender_lastname
// - shareit_sender_email
// - shareit_dest_firstname
// - shareit_dest_lastname
// - shareit_dest_email
// - shareit_message
// - shareit_add_URL


// include Shareit model
include_once('class.ShareitModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class ShareitController extends BaseModuleController {

	var $mod_name = 'shareit';

	var $current = null;

	// available process actions
	var $actions = Array(	'share_form',
				'share_success',
				'share_error');


	// constructor
	/**
	 * @param	$views		an optionnal array of rendering views depending on required action
	 * @return	void
	 */
	function ShareitController ($views=null) {

		$this->models['shareit'] = new ShareitModel();
		if ($views != null && is_array($views))
			$this->views_pile = $views;
	}


	// build
	/**
	 * Build the Controller and launch desired view rendering
	 * May be called for preset action processsing
	 * or just to launch given render class with given wrap mode
	 *
	 * @param	$view		the name of a specific render class to bypass action selector
	 * @param	$wrapped		in case a specific render class is given, wrap it or not with HTML
	 * @return	void
	 */
	function build ($view=null, $wrapped=false) {

		$params = Array();
		if ($this->current > 0)
			$params['current'] = $this->current;
		if (!empty($_GET['error']))
			$params['error'] = $_GET['error'];

		// process action that belongs to preset list
		if (isset($_POST['action']) && in_array($_POST['action'], $this->actions)) {
			if ($this->debug)
				echo "ShareitController.build() with action : ".$_POST['action']."<br/>";
			// process specific actions and checkings
			switch ($_POST['action']) {

				case 'share_success' :	// send email
							$params = Array(	'firstnamedest'	=> utf8_decode($_POST['shareit_dest_firstname']),
									'lastnamedest'	=> utf8_decode($_POST['shareit_dest_lastname']),
									'firstname'	=> utf8_decode($_POST['shareit_sender_firstname']),
									'lastname'	=> utf8_decode($_POST['shareit_sender_lastname']),
									'message'	=> utf8_decode($_POST['shareit_message']));
							include_once('modules/shareit/custom/class.renderShareitEmail.php');
							$render = new renderShareitEmail($this);
							$class = $_POST['refclass'];
							$params['refobj'] = new $class($_POST['refid']);
							$params['local'] = $_POST['local'];
							if (isset($_POST['page']))
								$params['page'] = $_POST['page'];
							else	$params['page'] = null;
							$message = $render->render($params);
							if (!multiPartMail($_POST['shareit_dest_email'] , $message['subject'] , $message['body'] , '', $_POST['shareit_sender_email']))
								$_POST['action'] = 'share_error';
							// prepare confirmation message
							$params['firstname'] = $_POST['shareit_dest_firstname'];
							$params['lastname'] = $_POST['shareit_dest_lastname'];
							$params['email'] = $_POST['shareit_dest_email'];
							
							// Tracking
							if (SHR_DB_TRACK) {
								$trackit = ($_POST['shareit_trackit'] == 'true' ? true : false);
								$this->models['shareit']->track($_POST['shareit_sender_email'], $trackit, $_POST['shareit_sender_lastname'], $_POST['shareit_sender_firstname']);
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
				echo "ShareitController.build() with render : ".$view."<br/>";
			// force a single specific view
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->wrapped = false;
		}

		$params['refclass'] = $_POST['refclass'];
		$params['refid'] = $_POST['refid'];
		$params['local'] = $_POST['local'];
		
		if(isset($_POST['page'])){
			$params['page'] = $_POST['page'];
		}else{
			$params['page'] = null;
		}					

		if (!is_null($this->view)) {
			$this->view->render($params);

		} else	echo "No View could be found to display <br/>";
	}


}

?>
