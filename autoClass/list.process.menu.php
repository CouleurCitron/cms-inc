<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

// Filter from main menu restrictions

// First check/clear session stored filters
if (!empty($_SESSION["menuFilters"])) {
	$stored = array_keys($_SESSION["menuFilters"]);
	foreach ($stored as $_class) {
		if (!preg_match('/^'.$classePrefixe.'/', $_class))
			unset($_SESSION["menuFilters"][$_class]);
	}
}

//viewArray(($_SESSION["menuFilters"]), 'Session before');

$aMenuFilters = Array();

if (isset($_GET['param'])&&$_GET['param']!="")
	$aMenuFilters[$classeName][] = Array(	'field'	=> $_GET['param'],
						'comp'	=> urldecode($_GET['comparateur']),
						'value'	=> $_GET[$_GET['param']] );

$k = 2;
while ($k != -1) {
	if (isset($_GET['param'.$k]) && $_GET['param'.$k]!="") {

		$aMenuFilters[$classeName][] = Array(	'field'	=> $classePrefixe.'_'.$_GET['param'.$k],
							'comp'	=> urldecode($_GET['comparateur'.$k]),
							'value'	=> $_GET[$_GET['param'.$k]] );
		if ($classeName != $_GET['paramtype'.$k])
			$aMenuFilters[$classeName][] = Array(	'field'	=> $classePrefixe.'_'.$_GET['paramtype'.$k],
								'comp'	=> urldecode($_GET['comparateur'.$k]),
								'value'	=> $_GET[$_GET['param'.$k]].'_id' );
		$k++;	
	} else	$k = -1;
}
 

//paramètre   ---- report des methode de export
$k=0;
while (isset($_GET['champ'.$k])&& $_GET['champ'.$k]!=""){ 
	if (isset($_GET['operateur'.$k]) && $_GET['operateur'.$k]!="" && isset($_GET['valeur'.$k]) && $_GET['valeur'.$k]!="") 
		$aMenuFilters[$classeName][] = Array(	'field'	=> $_GET['champ'.$k],
							'comp'	=> urldecode($_GET['operateur'.$k]),
							'value'	=> $_GET['valeur'.$k] );
	$k++;
}



if (empty($aMenuFilters) && !empty($_SESSION["menuFilters"]))
	$aMenuFilters = $_SESSION["menuFilters"];

if (!empty($aMenuFilters)) {
	$aRechMenu = Array();
	$cnt = 0;
	foreach ($aMenuFilters as $k_class => $filters) {
		foreach ($filters as $filtered) {
			$aRechMenu[$cnt] = new dbRecherche();				
			$aRechMenu[$cnt]->setValeurRecherche("declencher_recherche");
			$aRechMenu[$cnt]->setTableBD($k_class);
			$aRechMenu[$cnt]->setJointureBD(" {$k_class}.{$filtered['field']} {$filtered['comp']} {$filtered['value']} ");
			$aRechMenu[$cnt]->setPureJointure(1);
			$aRecherche[] = $aRechMenu[$cnt];
			$cnt++;
		}
	}
	// Store it for further list page return
	$_SESSION["menuFilters"] = $aMenuFilters;
	// Free...
	unset($aMenuFilters);
}

//viewArray(($_SESSION["menuFilters"]), 'Session after');

// End filter from main menu restrictions

?>