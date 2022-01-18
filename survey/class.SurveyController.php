<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle survey form content rendering through chosen rendering

// include survey model
include_once('class.SurveyModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class SurveyController extends BaseModuleController {

	var $mod_name = 'survey';

	var $current = null;

	// available process actions
	var $actions = Array(	'vote_success',
				'vote_error',
				'list_results');


	// constructor
	/**
	 * @param	$views		an optionnal array of rendering views depending on required action
	 * @return	void
	 */
	function SurveyController ($views=null) {

		$this->models['survey'] = new SurveyModel();
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
				echo "SurveyController.build() with action : ".$_POST['action']."<br/>";
			// process specific actions and checkings
			switch ($_POST['action']) {

				case 'vote_success' :	if ($_POST['id_ask'] > 0)
								$params['voted'] = $this->models['survey']->handleVote($_POST['id_ask']);
							else	$params['voted'] = false;
							$params['list'] = $this->models['survey']->getLastVotes(null, false);
							break;

				case 'list_results' :	if (empty($_POST['idSite']) && empty($_SESSION['idSite'])) {
								$_POST['action'] = 'vote_error';
								$params['error'] = 'invalid_site';
							} else	$params['list'] = $this->models['survey']->getLastVotes($id, $voted);
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
				echo "SurveyController.build() with render : ".$view."<br/>";
			// force a single specific view
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->wrapped = false;
		}

		if (!is_null($this->view)) {
			$this->view->render($params);

			// check wether mod_opinion applies here
			if ($_POST['id_ask'] > 0 && in_array('mod_survey', explode(';', MOD_OPINION_AVAILABLE)))	{
				require_once('cms-inc/opinion/class.OpinionController.php');
				$controller = new OpinionController();
				$controller->setTarget('mod_survey', $_POST['id_ask']);
				$controller->build('renderSurveyOpinionAccess');
			}
		} else	echo "No View could be found to display <br/>";
	}


	// setCurrent
	/**
	 * Set current survey question
	 *
	 * @param	Int		$id		The survey question ID
	 * @return	void
	 */
	function setCurrent ($id) {

		$this->current = $id;
	}

	
	// getFormStructure
	/**
	 * Get generated survey question structure
	 *
	 * @param	Int		$id		The survey question ID
	 * @return	Array		Survey question and answers structure
	 */
	function getFormStructure ($id) {

		return $this->models['survey']->extract($id);
	}

}

?>
