<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle opinion form content rendering through chosen rendering

// structures des donnéees de formulaires:
// prefix 'opinion_' de données POST

// - opinion_firstname
// - opinion_lastname
// - opinion_email
// - opinion_company
// - opinion_function
// - opinion_message

// include opinion model
include_once('class.OpinionModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class OpinionController extends BaseModuleController {

	var $mod_name = 'opinion';

	var $type = null;
	var $current = null;

	// available process actions
	var $actions = Array(	'page_add_form',
				'page_add_success',
				'page_add_error',
				'page_list_all',
				'news_add_form',
				'news_add_success',
				'news_add_error',
				'news_list_all',
				'survey_add_form',
				'survey_add_success',
				'survey_add_error',
				'survey_list_all' );


	// constructor
	function OpinionController ($views=null) {

		$this->models['opinion'] = new OpinionModel();
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

		if (!is_null($this->type))
			$_POST['target_type'] = $this->type;
		$params['type'] = $_POST['target_type'];

		if ($this->current > 0)
			$_POST['target_id'] = $this->current; 
		$params['current'] = $_POST['target_id'];

		if (!empty($_GET['error']))
			$params['error'] = $_GET['error'];

		// process action that belongs to preset list
		if (isset($_POST['action']) && in_array($_POST['action'], $this->actions)) {
			if ($this->debug)
				echo "OpinionController.build() with action : ".$_POST['action']."<br/>";
			// process specific actions and checkings
			switch ($_POST['action']) {

				case 'page_add_form' :
				case 'news_add_form' :
				case 'survey_add_form' :		if (empty($_POST['target_type']) || empty($_POST['target_id'])) {
									// list error
									$parts = explode('_', $_POST['action']);
									$_POST['action'] = $parts[0].'_add_error';
									$params['error'] = 'No target type and ID were specified for the opinion form to display';
								}
								break;
				case 'page_add_success' :
				case 'news_add_success' :
				case 'survey_add_success' :	if (!empty($_POST['target_type']) && !empty($_POST['target_id'])) {
									if (!empty($_POST['opinion_email']) || !empty($_POST['opinion_message'])) {
										$a_opinion = $this->prepareOpinion();
										$result = $this->models['opinion']->handleComment($_POST['target_type'], $_POST['target_id'], $a_opinion);
										if ($result > 0) {
											$params['submitted'] = true;
											$params['values'] = $a_opinion;
											$params['result'] = $result;
											$params['list'] = $this->models['opinion']->getPublishedComments($_POST['target_type'], $_POST['target_id']);
											
											// visitor email
											include_once('modules/opinion/custom/class.renderSurveyOpinionEmail.php');
											$render = new renderSurveyOpinionEmail($this);
											$message = $render->render($params);
											multiPartMail($values['mailcontact'] , $message['subject'] , $message['body'] , '', MOD_OPINION_WEBMASTER);
                								
											// moderator email
											include_once('modules/opinion/custom/class.renderOpinionModeratorEmail.php');
											$render = new renderOpinionModeratorEmail($this);
											$message = $render->render($params);
											multiPartMail(MOD_OPINION_MODERATOR , $message['subject'] , $message['body'] , '', MOD_OPINION_WEBMASTER);
                                                        	
											$parts = explode('_', $_POST['action']);
											$_POST['action'] = $parts[0].'_list_all';
										} else {
											// add error
											$parts = explode('_', $_POST['action']);
											$_POST['action'] = $parts[0].'_add_error';
											$params['error'] = 'add_submit_error';
										}
									} else {
										// add error
										$parts = explode('_', $_POST['action']);
										$_POST['action'] = $parts[0].'_add_error';
										$params['error'] = 'add_no_email_or_msg';
									}
								} else {
									// add error
									$parts = explode('_', $_POST['action']);
									$_POST['action'] = $parts[0].'_add_error';
									$params['error'] = 'add_no_type_and_id';
								}
								break;

				case 'page_list_all' :
				case 'news_list_all' :
				case 'survey_list_all' :		if (empty($_POST['target_type']) || empty($_POST['target_id'])) {
									// list error
									$parts = explode('_', $_POST['action']);
									$_POST['action'] = $parts[0].'_list_error';
									$params['error'] = 'list_no_type_and_id';
								} else	$params['list'] = $this->models['opinion']->getPublishedComments($_POST['target_type'], $_POST['target_id']);
								break;
			}
			// get View from preset list
			$view = $this->views_pile[$_POST['action']]['render'];

			// verify View render class exists
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
				include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
				$this->view = new $view($this);
			} else	die("ERROR : wrong render class for this process : modules/'.$this->mod_name.'/custom/class.".$view.".php");

		} elseif (!is_null($view) && is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
			if ($this->debug)
				echo "OpinionController.build() with render : ".$view."<br/>";
			// force a single specific view
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->wrapped = false;
		}

		if (!is_null($this->view))
			$this->view->render($params);
		else	echo "No View could be found to display <br/>";
	}


	// setTarget
	/**
	 * Set Opinion target type and ID
	 *
	 * @param	String		$type		The opinion attachment type
	 * @param	Int		$id		The attachment target ID
	 * @return	void
	 */
	function setTarget ($type, $id) {

		$this->type = $type;
		$this->current = $id;
	}

	
	function prepareOpinion() {

		$opinion = Array();
		$opinion['nomcontact'] = ($_POST['opinion_firstname'] != '' ? $_POST['opinion_firstname']." " : '' ).$_POST['opinion_lastname'];
		$opinion['mailcontact'] = $_POST['opinion_email']; 
		$opinion['fonction'] = $_POST['opinion_function'];
		$opinion['texte'] = $_POST['opinion_message'];

		return $opinion;
	}

}

?>
