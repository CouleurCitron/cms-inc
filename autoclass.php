<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

//function generateClasseFromXMLfile($file){
//function generateClasseFromXMLstring($file){
//function generateAS2FromXMLString($sBodyXML){

function generateClasseFromXMLfile($file){

	$fh = fopen($file,'r');
	while(!feof($fh)) {
		$sBodyXML.=fgets($fh);
	}
	
	generateClasseFromXMLString($sBodyXML);
	
}

function generateClasseFromXMLString($xmlstr){
	global $stack;
	xmlStringParse($xmlstr);
	
	
	//-------------------------------------------------------
	
	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$classeDisplay = $stack[0]["attrs"]["DISPLAY"];
	$classeAbstract = $stack[0]["attrs"]["ABSTRACT"];
	$aNodeToSort = $stack[0]["children"];
	
	$classeSRC = "";
	
	$classeSRC .= "<?php\n";
	$classeSRC .= "/* [Begin patch] */\n";
	$classeSRC .= "/* [End patch] */\n";

	$classeSRC .= "if(file_exists(\$_SERVER['DOCUMENT_ROOT'].'/include/bo/class/".$classeName.".class.php')  && (strpos(__FILE__,'/include/bo/class/".$classeName.".class.php')===FALSE) ){\n";
	$classeSRC .= "		include_once(\$_SERVER['DOCUMENT_ROOT'].'/include/bo/class/".$classeName.".class.php');\n";
	$classeSRC .= "}else{\n";
	
	$classeSRC .= "/*======================================\n\n";
	$classeSRC .= "objet de BDD ".$classeName." :: class ".$classeName."\n\n";
	
	// ----------- MySQL  --------------------------
	$eIsindex = 0;
	$classeSRC .= "SQL mySQL:\n\n";
	
	$classeSRC .= "DROP TABLE IF EXISTS ".$classeName.";\n";
	$sMySql = "CREATE TABLE ".$classeName."\n";
	$sMySql .= "(\n";
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
			$sMySql .=  "	".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."			";
			$sMySql .=  $aNodeToSort[$i]["attrs"]["TYPE"];
			if ($aNodeToSort[$i]["attrs"]["LENGTH"]){$sMySql .=  " (".$aNodeToSort[$i]["attrs"]["LENGTH"].")";}
			if ($aNodeToSort[$i]["attrs"]["ISPRIMARY"]){$sMySql .=  " PRIMARY KEY";}
			if ($aNodeToSort[$i]["attrs"]["ISINDEX"]){$eIsindex++;}
			if ($aNodeToSort[$i]["attrs"]["NOTNULL"]){$sMySql .=  " not null";}
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum" && $aNodeToSort[$i]["attrs"]["DEFAULT"]!=""){$sMySql .= " default '".$aNodeToSort[$i]["attrs"]["DEFAULT"]."'";}
			elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" && $aNodeToSort[$i]["attrs"]["DEFAULT"]!=""){$sMySql .= " default ".$aNodeToSort[$i]["attrs"]["DEFAULT"];}
			
			
			if ($aNodeToSort[$i+1]["name"] == "ITEM" || $eIsindex>0){	
				$sMySql .=  ",\n";
			}
			else{
				$sMySql .=  "\n";
			}
		}
	}
	
	// liste des index
	if ($eIsindex) {
		$cptIsindex = 0;
		for ($i=0;$i<count($aNodeToSort);$i++){
			if ($aNodeToSort[$i]["name"] == "ITEM"){	
				 
				if ($aNodeToSort[$i]["attrs"]["ISINDEX"]){
					$sMySql .=  "	INDEX ".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." ( ".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"].")";
					if ($cptIsindex < ($eIsindex-1)){	
						$sMySql .=  ",\n";
					}
					else{
						$sMySql .=  "\n";
					}
					$cptIsindex++;
				}
				 
				
			}
		}
	}
	$sMySql .=  ")\n\n";
	
	$classeSRC .= $sMySql;
	
	// ----------- oracle  --------------------------
	$eIsindex = 0;
	$classeSRC .= "SQL Oracle:\n\n";
	
	$aMySQL = array("int", "varchar");
	$aOracleSQL = array("number", "varchar2");
	
	$classeSRC .= "DROP TABLE ".$classeName."\n";
	$classeSRC .= "CREATE TABLE ".$classeName."\n";
	$classeSRC .= "(\n";
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
			$classeSRC .= "	".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."			";
			$classeSRC .= str_replace($aMySQL, $aOracleSQL, $aNodeToSort[$i]["attrs"]["TYPE"]);
			if ($aNodeToSort[$i]["attrs"]["LENGTH"]){$classeSRC .= " (".$aNodeToSort[$i]["attrs"]["LENGTH"].")";}
			if ($aNodeToSort[$i]["attrs"]["ISPRIMARY"]){$classeSRC .= " constraint ".$classePrefixe."_pk PRIMARY KEY";}
			if ($aNodeToSort[$i]["attrs"]["ISINDEX"]){$eIsindex++;}			
			if ($aNodeToSort[$i]["attrs"]["NOTNULL"]){$classeSRC .= " not null";}
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum" && $aNodeToSort[$i]["attrs"]["DEFAULT"]!=""){$classeSRC .= " default '".$aNodeToSort[$i]["attrs"]["DEFAULT"]."'";}
			if ($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" && $aNodeToSort[$i]["attrs"]["DEFAULT"]!=""){$classeSRC .= " default ".$aNodeToSort[$i]["attrs"]["DEFAULT"];}
			
			
			if ($aNodeToSort[$i+1]["name"] == "ITEM" || $eIsindex>0){	
				$classeSRC .= ",\n";
			}
			else{
				$classeSRC .= "\n";
			}
		}
	}
	
	if ($eIsindex) {
		$cptIsindex = 0;
		for ($i=0;$i<count($aNodeToSort);$i++){
			if ($aNodeToSort[$i]["name"] == "ITEM"){	
				 
				if ($aNodeToSort[$i]["attrs"]["ISINDEX"]){
					$classeSRC .=  "	INDEX ".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]." ( ".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"].")";
					if ($cptIsindex < ($eIsindex-1)){	
						$classeSRC .=  ",\n";
					}
					else{
						$classeSRC .=  "\n";
					}
					$cptIsindex++;
				}
				 
				
			}
		}
	}
	$classeSRC .= ")\n";
	
	// ------------ XML --------------------------------
	$classeSRC .= "\n\n";
	$classeSRC .= $xmlstr;
	$classeSRC .= "\n\n";
	
	$classeSRC .= "\n==========================================*/\n\n";
	
	
	//-  ----- début classe -------------------
	$classeSRC .= "class ".$classeName."\n";
	$classeSRC .= "{\n";
	
	//On ajoute de façon permanente la variable listant des enfants possible de la classe
	$classeSRC .= "var \$inherited_list = array();\n";
	$classeSRC .= "var \$inherited = array();\n";
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
			$classeSRC .= "var $".$aNodeToSort[$i]["attrs"]["NAME"].";\n";
		}
	}
	$classeSRC .= "\n\n";
	
	//-------  XML String var -------------------------------
	
	//$classeSRC .= "var $"."XML = \"".addslashes($xmlstr)."\";";
	// Keep simple quotes unslashed
	// Added by Luc - 5 oct. 2009
	$classeSRC .= "var $"."XML = \"".str_replace('"', '\"', $xmlstr)."\";";
	$classeSRC .= "\n\n";
	
	
	//-------  XML2 String var -------------------------------
	// Added by nico - 7 dec. 2010
	$classeSRC .= "var $"."XML_inherited = null;";
	$classeSRC .= "\n\n";
		
	//-------  MySQL String var -------------------------------
	//$classeSRC .= "var $"."sMySql = \"".addslashes($sMySql)."\";";
	// Keep simple quotes unslashed
	// Added by Luc - 5 oct. 2009
	$classeSRC .= "var $"."sMySql = \"".str_replace('"', '\"', $sMySql)."\";";
	$classeSRC .= "\n\n";
	
	//-  ----- constructeur -------------------
	$classeSRC .= "// constructeur\n";
	$classeSRC .= "function ".$classeName."($"."id=null)\n";
	$classeSRC .= "{\n";
	$classeSRC .= "	if (istable(get_class($"."this)) == false){\n";
	$classeSRC .= "		dbExecuteQuery($"."this->sMySql);\n";
	$classeSRC .= "	}\n\n";
	$classeSRC .= "	if($"."id!=null) {\n";
	$classeSRC .= "		$"."tempThis = dbGetObjectFromPK(get_class($"."this), $"."id);\n";
	$classeSRC .= "		foreach ($"."tempThis as $"."tempKey => $"."tempValue){\n";
	$classeSRC .= "			$"."this->$"."tempKey = $"."tempValue;\n";
	$classeSRC .= "		}\n";
	$classeSRC .= "		$"."tempThis = null;\n";
	$classeSRC .= "		if(array_key_exists('0',\$this->inherited_list)){\n";
	$classeSRC .= "			foreach(\$this->inherited_list as \$class){\n";
	$classeSRC .= "				if(!is_object(\$class))\n";
	$classeSRC .= "					\$this->inherited[\$class] = dbGetObjectFromPK(\$class, \$id);\n";
	$classeSRC .= "			}\n";
	$classeSRC .= "		}\n";
	$classeSRC .= "	} else {\n";
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
		
			$classeSRC .= "		$"."this->".$aNodeToSort[$i]["attrs"]["NAME"]." = ";
			if ($aNodeToSort[$i]["attrs"]["DEFAULT"]){
				if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int"){
					$classeSRC .= $aNodeToSort[$i]["attrs"]["DEFAULT"];
				}
				// decimal type
				// Added by Luc - 9 oct. 2009
				elseif (($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")){
					$classeSRC .= str_replace(',', '.', $aNodeToSort[$i]["attrs"]["DEFAULT"]);
				}
				elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
					$classeSRC .= "\"".$aNodeToSort[$i]["attrs"]["DEFAULT"]."\"";
				} 
				elseif($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" || $aNodeToSort[$i]["attrs"]["TYPE"] == "datetime") {
					if ($aNodeToSort[$i]["attrs"]["DEFAULT"] != '')
						$classeSRC .= "'".$aNodeToSort[$i]["attrs"]["DEFAULT"]."'";
					else	$classeSRC .= "date('Y-m-d H:i:s')";
				} 
				else{ // date
					$classeSRC .= "\"".$aNodeToSort[$i]["attrs"]["DEFAULT"]."\"";
				}
			} else {
				if ($aNodeToSort[$i]["attrs"]["TYPE"] == "int"){
					$classeSRC .= "-1";
				}
				// decimal type
				// Added by Luc - 9 oct. 2009
				elseif (($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")) {

					$test =preg_match ("/([0-9]*),([0-9]*)/msi", $aNodeToSort[$i]["attrs"]["LENGTH"], $regs);
					$classeSRC .=" 0."; 
					$cptdecimal = 0;
					while ($cptdecimal <$regs[2]) {
						$classeSRC .="0";
						$cptdecimal++;
					}
					 
				}
				elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum"){
					$classeSRC .= "\"\"";
				}
				elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
					$classeSRC .= "\"\"";
				} 
				elseif($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" || $aNodeToSort[$i]["attrs"]["TYPE"] == "datetime") {
					if ($aNodeToSort[$i]["attrs"]["DEFAULT"] != '')
						$classeSRC .= "'".$aNodeToSort[$i]["attrs"]["DEFAULT"]."'";
					else	$classeSRC .= "date('Y-m-d H:i:s')";
				} else{ // date
					$classeSRC .= "date(\"d/m/Y\")";
				}
			}
			$classeSRC .= ";\n";
		}
	}
	$classeSRC .= "		if(array_key_exists('0',\$this->inherited_list)){\n";
	$classeSRC .= "			foreach(\$this->inherited_list as \$class){\n";
	$classeSRC .= "				if(!is_object(\$class))\n";
	$classeSRC .= "					\$this->inherited[\$class] = new \$class();\n";
	$classeSRC .= "			}\n";
	$classeSRC .= "		}\n";
	
	$classeSRC .= "	}\n";
	$classeSRC .= "}\n";
	$classeSRC .= "\n\n";
	
	//-  ----- liste des champs -------------------
	$classeSRC .= "// liste des champs de l'objet\n";
	$classeSRC .= "function getListeChamps()\n";
	$classeSRC .= "{\n";
	$classeSRC .= "	// ATTENTION, respecter l'ordre des champs de la table dans la base de données pour construire laListeChamp\n";
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
		
			$classeSRC .= "	$"."laListeChamps[]=new dbChamp(\"".ucfirst($classePrefixe)."_".$aNodeToSort[$i]["attrs"]["NAME"]."\", ";			
			
			if (($aNodeToSort[$i]["attrs"]["TYPE"] == "int")){
					$classeSRC .= "\"entier\"";
			}
			// decimal type
			// Added by Luc - 9 oct. 2009
			elseif (($aNodeToSort[$i]["attrs"]["TYPE"] == "decimal") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")) {
					$classeSRC .= "\"decimal\"";
			}
			elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") || ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
				$classeSRC .= "\"text\"";
			}
			elseif($aNodeToSort[$i]["attrs"]["TYPE"] == "timestamp" || $aNodeToSort[$i]["attrs"]["TYPE"] == "datetime"){ // timestamp
				$classeSRC .= "\"date_formatee_timestamp\"";
			}
			// enum type
			// Added by Luc - 20 oct. 2009
			elseif ($aNodeToSort[$i]["attrs"]["TYPE"] == "enum") {
				$classeSRC .= "\"text\"";
			}
			else{ // date
					$classeSRC .= "\"date_formatee\"";
			}			
			
			$classeSRC .= ", \"get_".$aNodeToSort[$i]["attrs"]["NAME"]."\", \"set_".$aNodeToSort[$i]["attrs"]["NAME"]."\");\n";
		}
	}
	$classeSRC .= "	return($"."laListeChamps);\n";
	$classeSRC .= "}\n";
	$classeSRC .= "\n\n";
	
	//-  ----- getters -------------------
	$classeSRC .= "// getters\n";
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
			$classeSRC .= "function get_".$aNodeToSort[$i]["attrs"]["NAME"]."() { return($"."this->".$aNodeToSort[$i]["attrs"]["NAME"]."); }\n";
		}
	}
	$classeSRC .= "\n\n";
	
	//-  ----- setters -------------------
	$classeSRC .= "// setters\n";
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
			$classeSRC .= "function set_".$aNodeToSort[$i]["attrs"]["NAME"]."($"."c_".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"].") { return($"."this->".$aNodeToSort[$i]["attrs"]["NAME"]."=$"."c_".$classePrefixe."_".$aNodeToSort[$i]["attrs"]["NAME"]."); }\n";
		}
	}
	$classeSRC .= "\n\n";
	
	//-  ----- autres getters -------------------
	$classeSRC .= "// autres getters\n";
	$classeSRC .= "function getGetterPK() { return(\"get_id\"); }\n";
	$classeSRC .= "function getSetterPK() { return(\"set_id\"); }\n";
	$classeSRC .= "function getFieldPK() { return(\"".$classePrefixe."_id\"); }\n";
	
	$classeSRC .= "// statut\n";
	$getGetterStatut = "function getGetterStatut() {return(\"none\"); }\n";
	$getFieldStatut = "function getFieldStatut() {return(\"none\"); }\n";
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){	
			if ($aNodeToSort[$i]["attrs"]["NAME"] == "statut"){		
				$getGetterStatut = "function getGetterStatut() {return(\"get_statut\"); }\n";
				$getFieldStatut = "function getFieldStatut() {return(\"".$classePrefixe."_statut\"); }\n";
			}
		}
	}
	$classeSRC .= $getGetterStatut;
	$classeSRC .= $getFieldStatut;
	$classeSRC .= "//\n";
	$classeSRC .= "function getTable() { return(\"".$classeName."\"); }\n";
	$classeSRC .= "function getClasse() { return(\"".$classeName."\"); }\n";
	$classeSRC .= "function getPrefix() { return(\"".$classePrefix."\"); }\n";
	$classeSRC .= "function getDisplay() { return(\"".$classeDisplay."\"); }\n";
	$classeSRC .= "function getAbstract() { return(\"".$classeAbstract."\"); }\n";
	
	
	//-  ----- fin classe ------------------- 
	$classeSRC .= "\n\n";
	$classeSRC .= "} //class\n";
	$classeSRC .= "\n\n";
	
	$classeSRC .= "// ecriture des fichiers\n";
	
	if (($classePrefixe == 'job')||($classePrefixe == 'cms')||($classePrefixe == 'shp')||(preg_match('/job_.+/', $classeName)==1)||(preg_match('/cms_.+/', $classeName)==1)||(preg_match('/shp_.+/', $classeName)==1)){
		$basePath = '/backoffice/cms/'.$classeName;
	}
	else{
		$basePath = '/backoffice/'.$classeName;
	}
	
	$classeSRC .= "if (!is_dir(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."\")){\n";
		
	$classeSRC .= "	mkdir(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."\");\n";
	$classeSRC .= "	\$list = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/list_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$listContent = \"<\".\"?php
include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
\\\$\".\"script = explode('/',\\\$\".\"_SERVER['PHP_SELF']);
\\\$\".\"script = \\\$\".\"script[sizeof(\\\$\".\"script)-1];

if (is_file(\\\$\".\"_SERVER['DOCUMENT_ROOT'].'/include/bo/cms/prepend.'.\\\$\".\"script))
	require_once('include/bo/cms/prepend.'.\\\$\".\"script);

include('cms-inc/autoClass/list.php');
?\".\">\";\n";
	$classeSRC .= "	fwrite(\$list, \$listContent);\n";
	$classeSRC .= "	fclose(\$list);\n";
	
	$classeSRC .= "	\$maj = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/maj_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$majContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/maj.php'); ?\".\">\";\n";
	$classeSRC .= "	fwrite(\$maj, \$majContent);\n";
	$classeSRC .= "	fclose(\$maj);\n";
		
	$classeSRC .= "	\$show = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/show_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$showContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/show.php'); ?\".\">\";\n";
	$classeSRC .= "	fwrite(\$show, \$showContent);\n";
	$classeSRC .= "	fclose(\$show);\n";
	
	$classeSRC .= "	\$rss = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/rss_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$rssContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/rss.php'); ?\".\">\";\n";
	$classeSRC .= "	fwrite(\$rss, \$rssContent);\n";
	$classeSRC .= "	fclose(\$rss);\n";
		
	$classeSRC .= "	\$xml = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/xml_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$xmlContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xml.php'); ?\".\">\";\n";
	$classeSRC .= "	fwrite(\$xml, \$xmlContent);\n";
	$classeSRC .= "	fclose(\$xml);\n";
	
	$classeSRC .= "	\$xmlxls = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/xlsx_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$xmlxlsContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/xlsx.php'); ?\".\">\";\n";
	$classeSRC .= "	fwrite(\$xmlxls, \$xmlxlsContent);\n";
	$classeSRC .= "	fclose(\$xmlxls);\n";
		
	//$classeSRC .= "	\$export = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/export_".$classeName.".php\", \"w\");\n";
	//$classeSRC .= "	\$exportContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/export.php'); ?\".\">\";\n";
	//$classeSRC .= "	fwrite(\$export, \$exportContent);\n";
	//$classeSRC .= "	fclose(\$export);\n";
		
	$classeSRC .= "	\$import = fopen(\$_SERVER['DOCUMENT_ROOT'].\"".$basePath."/import_".$classeName.".php\", \"w\");\n";
	$classeSRC .= "	\$importContent = \"<\".\"?php include_once(\\\$_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); include('cms-inc/autoClass/import.php'); ?\".\">\";\n";
	$classeSRC .= "	fwrite(\$import, \$importContent);\n";
	$classeSRC .= "	fclose(\$import);\n";
	$classeSRC .= "}\n";
	$classeSRC .= "}\n";
	
	$classeSRC .= "?".">";
	
	return $classeSRC;
}

function generateAS2FromXMLString($sBodyXML){
	global $stack;
	xmlFileParse($sBodyXML);
	//-------------------------------------------------------
	
	$classeName = $stack[0]["attrs"]["NAME"];
	$classePrefixe = $stack[0]["attrs"]["PREFIX"];
	$classeDisplay = $stack[0]["attrs"]["DISPLAY"];
	$classeAbstract = $stack[0]["attrs"]["ABSTRACT"];
	$aNodeToSort = $stack[0]["children"];
	
	$classeSRC = "";
	
	//-  ----- début classe -------------------
	$classeSRC .= "class _adequatClass.".$classeName." {\n";	
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){	
			$classeSRC .= "	var ".$aNodeToSort[$i]["attrs"]["NAME"].":";
		
			if (($aNodeToSort[$i]["attrs"]["TYPE"] == "int") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")){
				$classeSRC .= "Number";
			}
			elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
				$classeSRC .= "String";
			}
			else{ // date
				$classeSRC .= "Date()";
			}		
			$classeSRC .= ";\n";
		}
	}
	$classeSRC .= "\n";
	
	//-  ----- constructeur -------------------
	$classeSRC .= "	// constructeur\n";
	$classeSRC .= "	function ".$classeName."() {\n";
	
	for ($i=0;$i<count($aNodeToSort);$i++){
		if ($aNodeToSort[$i]["name"] == "ITEM"){			
		
			$classeSRC .= "		".$aNodeToSort[$i]["attrs"]["NAME"]." = ";
			if ($aNodeToSort[$i]["attrs"]["DEFAULT"]){
				if (($aNodeToSort[$i]["attrs"]["TYPE"] == "int") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")){
					$classeSRC .= $aNodeToSort[$i]["attrs"]["DEFAULT"];
				}
				elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
					$classeSRC .= "\"".$aNodeToSort[$i]["attrs"]["DEFAULT"]."\"";
				}
				else{ // date
					$classeSRC .= "\"".$aNodeToSort[$i]["attrs"]["DEFAULT"]."\"";
				}
			}
			else{
				if (($aNodeToSort[$i]["attrs"]["TYPE"] == "int") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "float")){
					$classeSRC .= "-1";
				}
				elseif(($aNodeToSort[$i]["attrs"]["TYPE"] == "varchar") or ($aNodeToSort[$i]["attrs"]["TYPE"] == "text")){
					$classeSRC .= "\"\"";
				}
				else{ // date
					$classeSRC .= "Date()";
				}
			}
			$classeSRC .= ";\n";
		}
	}

	$classeSRC .= "	}\n";
	$classeSRC .= "}\n\n";
	
	return $classeSRC;
}
?>