<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// controller to handle News actions and rendering

// structures du tracking en session:
// $_SESSION['News']['id']
// $_SESSION['News']['email']

// structures des donnéees de formulaires:
// prefix 'News_' de données POST

// - news_gender
// - news_firstname
// - news_lastname
// - news_telephone
// - news_cellphone
// - news_email
// - news_password
// - news_professionnal (Y/N)
// - news_company
// - news_birthdate
// - news_addr_1
// - news_addr_2
// - news_addr_3
// - news_zipcode
// - news_city
// - news_country


// include News model
include_once('class.NewsModel.php');

// needs to extend BaseModuleController
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleController.php');


class NewsController extends BaseModuleController {

	var $mod_name = 'news';
	var $views_pile = null;		// various views may be used with this controller
	var $wrapped = false;

	
	// available process actions
	var $actions = Array(	'list_all_news',
				'home_excerpt',
				'search_news', 'list_one_news');


	// constructor
	/**
	 * @param	$views		an optionnal array of rendering views depending on required action
	 * @return	void
	 */
	function NewsController ($views=null) {

		$this->models['news'] = new NewsModel();
		
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
	
		global $db; 
		$params = Array();
		if (!empty($_GET['error']))
			$params['error'] = $_GET['error'];
			 

		if (isset($_POST['action']) && in_array($_POST['action'], $this->actions)) {
			if ($this->debug)
				echo "NewsController.build() with action : ".$_POST['action']."<br/>";
			// process action that belongs to preset list
			switch ($_POST['action']) {
				// process specific actions and checkings
				case 'list_one_news' :	$success = false;
							$query = $this->prepareOne($_POST["id"]);
							$params['list'] = $this->models['news']->retrieve($query['getters'], $query['operands'], $query['values'], $query['orders'], $query['directions']); 
							$params['news'] = $params['list'][0];
							break;
				case 'list_all_news' :	$success = false;
							
							$query = $this->prepareList();
							if ($_POST["pagination"]) {
							
								$params['pagination'] = $this->models['news']->get_pagination($db,  
																		$_POST["pager_first"],
																		$_POST["pager_last"],
																		$_POST["pager_prev"],
																		$_POST["pager_next"],
																		$_POST["pager_separator"] ,
																		$_POST["pager_rows_per_page"],
																		$query['getters'], $query['operands'], $query['values'], $query['orders'], $query['directions'], $query['dt_start'], $query['dt_end']);
							}
							$params['list'] = $this->models['news']->retrieve($query['getters'], $query['operands'], $query['values'], $query['orders'], $query['directions'], $query['dt_start'], $query['dt_end']);
							break;

				case 'home_excerpt' :	$success = false;
							$query = $this->prepareExcerpt();
							$list = $this->models['news']->retrieve($query['getters'], $query['operands'], $query['values'], $query['orders'], $query['directions']);  
							$params['news'] = $list; 
							break;

				case 'search_news' :	$success = false;
							break;

			} 
			// get View from preset list
			if ($this->views_pile != NULL) $view = $this->views_pile[$_POST['action']]['render']; 
			
			// verify View render class exists
			if (is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
				include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
				$this->view = new $view($this);
			} else	die("ERROR : wrong render class for this process : modules/".$this->mod_name."/custom/class.".$view.".php");

		} elseif (!is_null($view) && is_file($_SERVER['DOCUMENT_ROOT'].'/modules/'.$this->mod_name.'/custom/class.'.$view.'.php')) {
			if ($this->debug)
				echo "NewsController.build() with render : ".$view."<br/>";
			// get News from session
			if (!empty($_SESSION['News']) || $_SESSION['News']['id'] > 0)
				$params['News'] = $this->getNews($_SESSION['News']['email']);
			// force a single specific view
			include_once('modules/'.$this->mod_name.'/custom/class.'.$view.'.php');
			$this->view = new $view($this);
			$this->wrapped = false;
		}

		if (!is_null($this->view))
			$this->view->render($params);
		else	echo "No View could be found to display <br/>";
	}


	function prepareOne ($id) {
		return Array(	'getters'	=> Array("get_id"),
				'operands'	=> Array('equals'),
				'values'		=> Array($id),
				'orders'		=> Array('get_date_pub_debut'),
				'directions'	=> Array('DESC') );					
	}
	
	function prepareList () {
		return Array(	'getters'	=> Array("get_statut"),
				'operands'	=> Array('equals'),
				'values'		=> Array(DEF_ID_STATUT_LIGNE),
				'orders'		=> Array('get_date_pub_debut'),
				'directions'	=> Array('DESC'), 
				'dt_start'	=>  "'".date("Y-m-d H:i:s")."'",
				'dt_end'	=> "'".date("Y-m-d H:i:s")."'" );					
	}


	function prepareExcerpt () {
		return Array(	'getters'	=> Array('get_remontee'),
				'operands'	=> Array('equals'),
				'values'		=> Array('Y'),
				'orders'		=> Array('get_date_pub_debut'),
				'directions'	=> Array('DESC') );					
	}

}

?>
