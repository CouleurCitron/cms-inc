<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
if (trim($include_append)!=''){
	include_once($include_append);
}
@$db->Disconnect();
?>