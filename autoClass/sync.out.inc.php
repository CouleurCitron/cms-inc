<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

function syncOutObject($aObjectNodes,  $aFieldData, $oO, $sExcludeField=NULL){
	global $db;
	
	$aPrefixeExplode = 	array_intersect(	explode('_', $oO->getFieldPK()),	explode('_', $oO->getTable())	);
	$prefixe = (string)$aPrefixeExplode[0];
	
	echo '<'.$oO->getTable().' id="'.$aFieldData[$oO->getFieldPK()].'">'."\n";
			
	// fields
	for ($i=0;$i<count($aObjectNodes);$i++){		
		$fieldName=$prefixe.'_'.$aObjectNodes[$i]['attrs']['NAME'];
		
	/*	if ($aObjectNodes[$i]['name'] == 'ITEM'	&&	$fieldName!=$sExcludeField) {
			syncOutField($aObjectNodes[$i], $aFieldData[$fieldName]);			 
		}
		elseif($aObjectNodes[$i]['name'] == 'ITEM'	&&	$fieldName==$sExcludeField) {
			echo '<'.$aObjectNodes[$i]['attrs']['NAME'].' type="excluded"><![CDATA['.$aFieldData[$oO->getFieldPK()].']]></'.$aObjectNodes[$i]['attrs']['NAME'].'>'."\n";
		}*/
		
		
		
		if ($aObjectNodes[$i]['name'] == 'ITEM'){
			syncOutField($aObjectNodes[$i], $aFieldData[$fieldName], $sExcludeField);
		}
	}
	
	// assos
	for ($j=0; $j<count($aObjectNodes); $j++) {
		if ($aObjectNodes[$j]["name"] == "ITEM") {			 
			if (isset($aObjectNodes[$j]["attrs"]["ASSO"])){
				
				echo '<assos>'."\n";
				$aAssos = explode(',', $aObjectNodes[$j]["attrs"]["ASSO"]);
				
				foreach($aAssos as $kX => $sAsso){
					
					$oX = new $sAsso();
		
					if(!is_null($oX->XML_inherited))
						$sXML = $oX->XML_inherited;
					else
						$sXML = $oX->XML;
					
					$stack = xmlClassParse($sXML);
					$aNodeToSort = $stack[0]['children'];
					
					$sql = 'SELECT * FROM '.$sAsso.' WHERE '.$oO->getTable().' = '.$aFieldData[$oO->getFieldPK()].';';
					$rs = $db->Execute($sql);
					if($rs) {				
						while(!$rs->EOF) {						
							syncOutObject($aNodeToSort, $rs->fields, $oX, $oO->getTable());
							$rs->MoveNext();
						}
					}
				}
				echo '</assos>'."\n";
			}
		}
	}
	
	echo '</'.$oO->getTable().'>'."\n";
}

function syncOutField($aFieldNode, $sData, $sExcludeField){
	global $db;
	
	echo '<'.$aFieldNode['attrs']['NAME'];
	
	if (isset($aFieldNode['attrs']['FKEY'])	&&	$aFieldNode['attrs']['FKEY']==$sExcludeField){		
		unset($aFieldNode['attrs']['FKEY']);
	}
	
	
	if (isset($aFieldNode['attrs']['FKEY'])){		
	
		echo ' type="fkey">'."\n";
		
		$oO = new $aFieldNode['attrs']['FKEY']();
		
		if(!is_null($oO->XML_inherited))
			$sXML = $oO->XML_inherited;
		else
			$sXML = $oO->XML;
		
		$stack = xmlClassParse($sXML);
		$aNodeToSort = $stack[0]['children'];

		$sql = 'SELECT * FROM '.$aFieldNode['attrs']['FKEY'].' WHERE '.$oO->getFieldPK().' = '.$sData;
		$rs = $db->Execute($sql);
		if($rs) {	
			while(!$rs->EOF) {		
				syncOutObject($aNodeToSort, $rs->fields, $oO);
				$rs->MoveNext();
			}
		}
	}
	elseif (isset($aFieldNode['attrs']['TRANSLATE'])	&&	$aFieldNode['attrs']['TRANSLATE']=='reference'){
		
		echo ' type="translate">'."\n";
		
		$sql = 'SELECT cms_crf_chaine FROM cms_chaine_reference WHERE cms_crf_id = '.$sData;
		$rs = $db->Execute($sql);
		if($rs) {	
			while(!$rs->EOF) {	
				echo '<![CDATA['.mb_convert_encoding ($rs->fields['cms_crf_chaine'], 'UTF-8').']]>';	
				$rs->MoveNext();
			}
		}
	}
	else{
		echo ' type="basic">'."\n";
		
		echo '<![CDATA['.mb_convert_encoding ($sData, 'UTF-8').']]>';	
	}
	
	echo '</'.$aFieldNode['attrs']['NAME'].'>'."\n";
}
?>