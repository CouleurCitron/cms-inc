<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
/*
sponthus 08/06/2005

Mkl 07/06/2005
Correction Bug : Attention les classes (et id) des divs doivent commencer par une lettre
pour pouvoir appliquer un style à ces div (sinon bug de positionnement).


function getTamponGabarit($divArray, $oPage) 
function getTamponGabarit_init() 

*/

// contenu (tampon) qui sert à créer d'autres gabarits

function getTamponGabarit($divArray, $oPage) 
{
	// styles des briques -------------------------

	$tampon = '<!--GABARIT_GENERATED-->
<style type="text/css">
	.space {
		position:absolute;
		width: '.$oPage->getWidth_page().'px;
		height: '.$oPage->getHeight_page().'px;
		overflow: '.divOverflow().';
		overflow-x: '.divOverflow().';
		overflow-y: '.divOverflow().';		
		text-align: left;
	}

	.content {
		position:absolute;
		width: '.$oPage->getWidth_page().'px;
		height: '.$oPage->getHeight_page().'px;
		overflow: '.divOverflow().';
		overflow-x: '.divOverflow().';
		overflow-y: '.divOverflow().';
		text-align: left;
	}';
	
	foreach($divArray as $k => $v) {
		if(is_array($v)){
			$top=$v['top'];
			$left=$v['left'];
			$height=$v['height'];
			$width=$v['width'];

			if(!preg_match('/px$/',$top)) $top.='px';
			if(!preg_match('/px$/',$left)) $left.='px';
			if(!preg_match('/px$/',$width)) $width.='px';
			if(!preg_match('/px$/',$height)) $height.='px';

		// Laisser le .div pour éviter bug!
			$tampon.='
	.div'.$v['id'].'{
		position: absolute;
		overflow: visible;
		overflow-y: visible;
		overflow-x: visible;
		text-align: left;
		top:'.$top.';
		left:'.$left.';
		width:'.$width.';
		height:'.$height.';
		filter:'.$filter.';
		-moz-opacity: '.$v['-moz-opacity'].';
		z-index: '.$v['zIndex'].';
		visibility: visible;
	}';
		}
	}
	
	$tampon.='
</style>
	';

	return $tampon;
}


// gabarit de départ qui sert à créer les autres gabarits

function getTamponGabarit_init() 
{
	$tampon = "<!--DEBUTCMS-->
	<!--FINCMS-->";
	
	return($tampon);
}

?>