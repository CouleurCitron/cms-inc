<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour gérer les données de compte

include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_client.class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/class/shp_adresse.class.php');

// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');

 


class NewsModel extends BaseModuleModel {

	
	// constructor
	function NewsModel () {}


	// retrieve
	/**
	 * get subtree for a given root or all tree if none was given
	 * recurse from top to bottom
	 *
	 * @param	Array	$getters		a list of conditionnal fields
	 * @param	Array	$operands	a list of conditionnal operands
	 * @param	Array	$values		a list of conditionnal values
	 * @param	Array	$orders		a list of order fields
	 * @param	Array	$directions	a list of order directions ('ASC' or 'DESC')
	 * @param	String	$dt_start	a start from date
	 * @param	String	$dt_end		a end at date
	 * @return	Array		a list of news records
	 */
	function retrieve ($getters=null, $operands=null, $values=null, $orders=null, $directions=null, $dt_start=null, $dt_end=null ) {
		$pile = Array();

		if (!is_null($dt_start)) {
			if (is_null($operands) && newSizeOf($operands) < newSizeOf($getters))
				$operands = array_pad($operands, newSizeOf($getters), 'equals');
			$operands[] = 'lower_equals';
			$getters[] = 'get_date_pub_debut';
			$values[] = $dt_start;
		}
		if (!is_null($dt_end)) {
			if (is_null($operands) && newSizeOf($operands) < newSizeOf($getters))
				$operands = array_pad($operands, newSizeOf($getters), 'equals');
			$operands[] = 'higher_equals';
			$getters[] = 'get_date_pub_fin';
			$values[] = $dt_end;
		}
		if (empty($orders)) {
			$orders = Array('get_ordre');
			$directions = Array('DESC');
		}
		if ($this->debug) {
			echo "NewsModel.retrieve() with action : ".$_POST['action']."<br/>";
			viewArray($getters, 'GETTERS');
			viewArray($operands, 'OPERANDS');
			viewArray($values, 'VALUES');
			viewArray($orders, 'ORDERS');
			viewArray($directions, 'DIRECTIONS');
		}
		
		if (!is_null($pager_rows_per_page)) {
		}
		
		/*pre_dump($getters);
		pre_dump($operands);
		pre_dump($values);
		pre_dump($orders);
		pre_dump($directions);*/
		
		
		$res = dbGetObjectsFromFieldValue3('nws_content', $getters, $operands, $values, $orders, $directions);
		
		 
		return $res;
	}
	
	function get_pagination ($db, $pager_first, $pager_last, $pager_prev, $pager_next, $pager_separator, $pager_rows_per_page,$getters=null, $operands=null, $values=null, $orders=null, $directions=null, $dt_start=null, $dt_end=null) {
		
		
		$pile = Array();

		if (!is_null($dt_start)) {
			if (is_null($operands) && newSizeOf($operands) < newSizeOf($getters))
				$operands = array_pad($operands, newSizeOf($getters), 'equals');
			$operands[] = 'lower_equals';
			$getters[] = 'get_date_pub_debut';
			$values[] = $dt_start;
		}
		if (!is_null($dt_end)) {
			if (is_null($operands) && newSizeOf($operands) < newSizeOf($getters))
				$operands = array_pad($operands, newSizeOf($getters), 'equals');
			$operands[] = 'higher_equals';
			$getters[] = 'get_date_pub_fin';
			$values[] = $dt_end;
		}
		if (empty($orders)) {
			$orders = Array('get_ordre');
			$directions = Array('DESC');
		}
		if ($this->debug) {
			echo "NewsModel.retrieve() with action : ".$_POST['action']."<br/>";
			viewArray($getters, 'GETTERS');
			viewArray($operands, 'OPERANDS');
			viewArray($values, 'VALUES');
			viewArray($orders, 'ORDERS');
			viewArray($directions, 'DIRECTIONS');
		}
		
		/*pre_dump($getters);
		pre_dump($operands);
		pre_dump($values);
		pre_dump($orders);
		pre_dump($directions); */
		
		 
		$sql = str_replace (";", "", dbGetSQLFromFieldValue3('nws_content', $getters, $operands, $values, $orders, $directions));
		 
		$pager = new Pagination($db, $sql, $sParam, $idSite);  
		
		$pager->first=  $pager_first;
		$pager->last= $pager_last;
		$pager->prev= $pager_prev;
		$pager->next= $pager_next;
		$pager->showResults = false;
		$pager->showFooter = false;
		$pager->separator = $pager_separator;
		$rows_per_page= $pager_rows_per_page; // nombre de résultats par page
		$pager->Render ($rows_per_page); 
		
		
		$aId = $pager->aResult; 
		$aListe_res = array();
		for ($m=0; $m<newSizeOf($aId); $m++)
		{
			eval("$"."aListe_res[] = new "."nws_content"."($"."aId[$"."m]);");
		}
		$pager->aResult = $aListe_res;
		
		return $pager;
	}

}

?>
