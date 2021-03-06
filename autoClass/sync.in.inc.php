<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

function syncInObject($aObjectNodes, $oO){
	global $db;
	global $aImportLog;
	
	$aPrefixeExplode = 	array_intersect(	explode('_', $oO->getFieldPK()),	explode('_', $oO->getTable())	);
	$prefixe = (string)$aPrefixeExplode[0];			
				
	echo '		<strong>-- syncInObject</strong>('.$oO->getTable().' ID '.$aObjectNodes['attrs']['ID'].')<br />';		
	
	//deja fait ?
	if (isset($aImportLog[$oO->getTable()][$aObjectNodes['attrs']['ID']])){
		echo '-- deja imported<br />';	
		return false;
	}
	else{
		$aFields = array(); // date pour mysql
		
		foreach($aObjectNodes['children'] as $k => $aFieldNode){
			$fieldName = $prefixe.'_'.strtolower($aFieldNode['name']);
			$fieldValue=syncInField($aFieldNode, $oO);
			
			if($fieldValue!=NULL){
				$aFields[$fieldName] = to_dbstring($fieldValue);
			}		
		}

		
		$sql ='REPLACE '.$oO->getTable().' SET ';
		
		$aReplaces=array();
		
		foreach($aFields as $fieldName => $fieldValue){
			$aReplaces[]=$fieldName.'='. $fieldValue;
			
		}
		$sql .= implode(', ', $aReplaces).';';
		//echo '<br />'.$sql.'<br />';
		
		$rs = $db->Execute($sql);
		
		// on loggue
		$aImportLog[$oO->getTable()][$aObjectNodes['attrs']['ID']]=true;
		return true;
	}
}

function syncInField($aFieldNode, $oO){
	global $db;
	global $translator;
	
	echo '		-- syncInField('.$aFieldNode['name'].')<br />';
	
	//var_dump($aFieldNode);
	
	
	foreach($aFieldNode['children'] as $k => $aChildNode){
		
		if (class_exists($aChildNode['name'])){
			$oChild = new $aChildNode['name']();
			syncInObject($aChildNode, $oChild);
		}		
	}
	
	if ($aFieldNode['attrs']['TYPE']=='basic'){
		return $aFieldNode['cdata'];
	}
	elseif ($aFieldNode['attrs']['TYPE']=='excluded'){
		return $aFieldNode['cdata'];
	}
	elseif ($aFieldNode['attrs']['TYPE']=='fkey'){
		if (isset($aFieldNode['children'][0]['attrs']['ID'])){
			return 	$aFieldNode['children'][0]['attrs']['ID'];
		}
		else{
			return -1;
		}
	}
	elseif ($aFieldNode['attrs']['TYPE']=='translate'){
		// inserer la trad		
		return $translator->addReference($aFieldNode['cdata']);
	}

	
	
}
?>