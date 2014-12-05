<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
// sponthus 31/05/2005
// réécriture des procédures stocquées en modules php
// pour assurer la compatbilité d'ADEQUATION avec les differentes BDD

/*

function proc_storecomposant($oContent)
function proc_deletecomposant($oContent)
function proc_movecomposant($oContent)
function proc_storepage($oPage)
function proc_storestruct($oStruct)

*/


// enregistrement d'un composant (objet cms_content)
function proc_storecomposant($oContent)
{
/*
//------------------------------------
// PROCEDURE stockée :: storecomposant
//------------------------------------
DECLARE    nameContent     ALIAS FOR $1;
	   typeContent     ALIAS FOR $2;
	   widthContent	   ALIAS FOR $3;
	   heightContent   ALIAS FOR $4;
	   validContent	   ALIAS FOR $5;
	   actifContent	   ALIAS FOR $6;
	   htmlContent	   ALIAS FOR $7;
	   nodeIdContent   ALIAS FOR $8;
	   idContent	   ALIAS FOR $9;
    	   count           integer;
BEGIN    
	UPDATE cms_content
	SET   name_content = nameContent,
	      type_content = typeContent,
	     width_content = widthContent,
	    height_content = heightContent,
	     valid_content = validContent,
	     actif_content = actifContent,
	      html_content = htmlContent,
	    nodeid_content = nodeIdContent
	WHERE id_content = idContent;
	GET DIAGNOSTICS count = ROW_COUNT;
	IF count = 0 THEN
	    INSERT INTO cms_content (id_content, name_content, type_content, width_content, height_content, valid_content, actif_content, html_content, nodeid_content)
	    SELECT nextval('seq_cms_content'), nameContent, typeContent, widthContent, heightContent, validContent, actifContent, htmlContent, nodeIdContent;
 	    GET DIAGNOSTICS count = ROW_COUNT;
	END IF;
	return count;
END;
*/

	if ($oContent->getId_content() != "")
		$eIdContent = getCount("cms_content", "*", "id_content", $oContent->getId_content());
	else 
		$eIdContent = 0;

	if ($eIdContent == 0) {
		// nouvelle valeur de clé
		$oContent->setId_content(getNextVal("cms_content", "id_content"));

		// INSERT
		$result = $oContent->cms_content_insert();
	} else {
		// UPDATE
		$result = $oContent->cms_content_update();
	}

	return $result;
}


// suppression d'un composant (objet cms_content)
// pas de suppression en BDD, actif_content='false'
function proc_deletecomposant($oContent)
{
/*
//------------------------------------
// PROCEDURE stockée :: deletecomposant
//------------------------------------
DECLARE    id     ALIAS FOR $1;
BEGIN    
	UPDATE cms_content 
	set actif_content='false'
	where id_content=id;
	return TRUE;
END;
*/ 
	// DELETE
	$result = $oContent->cms_content_delete();

	return $result;

}

// déplacement d'un composant (objet cms_content)
function proc_movecomposant($oContent)
{
/*
//------------------------------------
// PROCEDURE stockée :: movecomposant
//------------------------------------
DECLARE    id     ALIAS FOR $1;
  new_node_id     ALIAS FOR $2;
BEGIN    
	UPDATE cms_content 
	set nodeid_content=new_node_id
	where id_content=id;
	return TRUE;
END;
*/
	// UPDATE
	$result = $oContent->cms_content_move();

	return $result;
}


// enregistrement d'une page
function proc_storepage($oPage)
{


	if ($oPage->getId_page() != "")
		$eIdPage = getCount("cms_page", "*", "id_page", $oPage->getId_page());
	else 
		$eIdPage = 0;
		
		
	if ($eIdPage == 0) {
		// nouvelle valeur de clé
		$oPage->setId_page(getNextVal("cms_page", "id_page"));		
		// INSERT
		$result = $oPage->cms_page_insert();
	} else {
		// UPDATE
		$result = $oPage->cms_page_update();
	}

	return $result;
}

// enregistrement d'une structure
function proc_storestruct($oStruct_page)
{
/*
//------------------------------------
// PROCEDURE stockée :: storestruct
//------------------------------------
DECLARE    idPage         ALIAS FOR $1;
	   idContent	  ALIAS FOR $2;
	   widthcontent  ALIAS FOR $3;
	   heightcontent ALIAS FOR $4;
	   topcontent    ALIAS FOR $5;
	   leftcontent   ALIAS FOR $6;
	   count	  integer;
BEGIN    
	    INSERT INTO cms_struct_page (id_struct,id_page, id_content, width_content, height_content, top_content, left_content)
	    SELECT nextval('seq_cms_struct_page'), idPage,idContent,widthcontent,heightcontent,topcontent,leftcontent;
 	    GET DIAGNOSTICS count = ROW_COUNT;
	return currval('seq_cms_struct_page');
END;
*/
	// clé suivante
	$oStruct_page->setId_struct(getNextVal("cms_struct_page", "id_struct"));

	// INSERT
	$result = $oStruct_page->cms_struct_page_insert();

	return $result;
}

?>