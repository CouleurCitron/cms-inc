<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');

function
flvThumb($file,$width=50,$height=50,$hour=00,$min=00,$sec=01,$append="") {
 if ($append == "") {
  $append = time();
 }

 if ($file == "" or !is_file($file)) {
 	echo "no file";
  return false;
 }

 $width = eregi_replace("[^0-9]","",$width);
 $height = eregi_replace("[^0-9]","",$height);

 if ($width == "") {
  $width = 50;
 }
 if ($height == "") {
  $height = 50;
 }

 $hour = eregi_replace("[^0-9]","",$hour);
 $min = eregi_replace("[^0-9]","",$min);
 $sec = eregi_replace("[^0-9]","",$sec);

 if (strlen($hour) == 1) {
  $hour = "0" . $hour;
 }
 if (strlen($min) == 1) {
  $min = "0" . $min;
 }
 if (strlen($sec) == 1) {
  $sec = "0" . $sec;
 }

 $try = explode(".",$file);
 $ext = array_pop($try);
 $desig = implode(".",$try);
 $thumbname = $desig . "_" . $append . "." . "jpg";

 $systemCall  = "ffmpeg -i $file -vcodec png -vframes 1 -an -f rawvideo -s " . $width . "x" . $height . " -ss " . "$hour" . ":" ."$min" . ":" . "$sec" . " -y " . "$thumbname";
 //echo $systemCall;
 $varmake = @system($systemCall,$retval);
 $tmp = @stat($thumbname);
 if ($tmp['size'] == 0) {
  @unlink($thumbname);
  return false;
 }
 if ($retval != 0) {
  return false;
 } else {
  return $thumbname;
 }

}

?>