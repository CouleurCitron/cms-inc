<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

/**
 * Helper function to foarmat prices and such display
 *
 * PHP versions 4 > 5
 *
 * @category	library
 * @author	Luc Thibault <luc@suhali.net>
 *
 */


// ceiling
/**
 * Quick ceil function with precision capability (like round() precision)
 *
 * @param	Int	$val		the value
 * @param	Int	$precision	the precision level
 * @return	Void
 */
function ceiling ($val, $precision=0) {
	return ceil($val * pow(10, $precision)) / pow(10, $precision);
}

function correctCeilDisplay ($val) {
	if (ceiling($val) == $val)
		return $val.'.00';
	elseif (ceiling($val, 1) == $val)
		return $val.'0';
	else	return ceiling($val, 2);
}

function correctRoundDisplay ($val) {
	
	$val = number_format(round($val,2), '2', '.', '');	
	
	return $val; 
	/*if (round($val) == $val)   {
		return $val.'.00';
	}
	elseif (round($val, 2) == $val){ 
		return $val;
	}
	elseif (round($val, 1) == $val){ 
		return $val.'0';
	}
	else	{ 
		return round($val, 2);
	}*/
}

function correctFullDisplay ($val) {
	if (round($val) == $val)
		return round($val);
	else	return round($val, 2);
}

function friendlyRoundDisplay($val) {
	if (round($val) == $val)
		return round($val);
	elseif (round($val, 1) == $val)
		return round($val, 1);
	else	return round($val, 2);
}

function calculateTTC($val, $tva) {
	return correctRoundDisplay($val*(1+$tva/100));	
}


function calculateHT($val, $tva) {
	return correctRoundDisplay($val/(1+$tva/100));	
}

function calculateTVA($val, $tva) {
	return correctRoundDisplay($val - ($val/(1+$tva/100)));	
}

?>
