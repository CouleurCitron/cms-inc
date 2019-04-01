<?php
 
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');
error_reporting(0);

// ##### jhfCAPTCHA
//       31-01-2006 J.H. Fitié
//              johanfitie.com


function captchaimage () {

 // ##### IMAGE SETTINGS
 $width=240;
 $height=90;
 
 define ('NB_LETTRES',2);


 // ##### SET UP IMAGE AND COLORS
 $image=imagecreatetruecolor($width,$height);
 imagesetthickness($image,1);
 imagealphablending($image,true);
 $color_black=imagecolorallocatealpha($image,0,0,0,0);
 $color_black_semi=imagecolorallocatealpha($image,0,0,0,115);
 $color_white=imagecolorallocatealpha($image,255,255,255,0);
 $color_grey =imagecolorallocatealpha($image,200,200,200,0);
 imagefill($image,0,0,$color_grey);
 //imagecolortransparent($image,$color_white);


 // ##### BUILD RANDOM PASSWORD
 $acceptedCharsV="AEIOUY";
 $acceptedCharsC="BCDFGHJKLMNPQRSTVWXZ";
 $wordbuild=array(
    "cvcc","ccvc","ccvcc","cvccc", // monosyllabic nominal stems
    "cvcvc","cvcv","cvccv","ccvcv" // disyllabic nominal stems
 );
 $thisword=$wordbuild[mt_rand(0,sizeof($wordbuild)-1)];
 $stringlength=strlen($thisword);
 for($i=0;$i<$stringlength;$i++) {
  if ($thisword[$i]=="c") {$password.=$acceptedCharsC{mt_rand(0,strlen($acceptedCharsC)-1)};}
  if ($thisword[$i]=="v") {$password.=$acceptedCharsV{mt_rand(0,strlen($acceptedCharsV)-1)};}
 }


 // ##### DRAW RANDOM LETTERS
/*
 for($i=0;$i<NB_LETTRES;$i++) {
  $color=imagecolorallocatealpha($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255),110);
  imagestring($image,mt_rand(1,3),mt_rand(-$width*0.25,$width*1.25),mt_rand(-$height*0.25,$height*1.25),
    $acceptedCharsC{mt_rand(0,strlen($acceptedCharsC)-1)},$color);
  imagestring($image,mt_rand(1,3),mt_rand(-$width*0.25,$width*1.25),mt_rand(-$height*0.25,$height*1.25),
    $acceptedCharsV{mt_rand(0,strlen($acceptedCharsV)-1)},$color);
 }
*/
  
   // ##### DRAW ELLIPSES

 for($i=0;$i<12;$i++) {
  $color=imagecolorallocatealpha($image,mt_rand(180,240),mt_rand(190,230),mt_rand(195,220),110);
  imagefilledellipse($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(10,40),mt_rand(10,40),$color);
 }


 // ##### DRAW PASSWORD
 for($i=0;$i<$stringlength;$i++) {
  $buffer=imagecreatetruecolor(50,50);
  imagefill($buffer,0,0,$color_white);
  imagecolortransparent($buffer,$color_white);

  $buffer2=imagecreatetruecolor(50,50);
  imagefill($buffer2,0,0,$color_white);
  imagecolortransparent($buffer2,$color_white);

  $red=0;$green=0;$blue=0;
  /*
  while ($red+$green+$blue<400||$red+$green+$blue>450) {
   $red = mt_rand(0,255);
   $green = mt_rand(0,255);
   $blue = mt_rand(0,255);
  }
*/
  $color=imagecolorallocate($buffer,$red,$green,$blue);
  imagestring($buffer,2,0,0,substr($password,$i,1),$color);

  //phpinfo();exit;
  
  imagecopyresized($buffer2,$buffer,2,-5,0,0,mt_rand(30,40),mt_rand(30,40),10,14);
  //$buffer=imagerotate($buffer2,mt_rand(-25,25),$color_white);

  $xpos=$i/$stringlength*($width-30)+(($width-30)/$stringlength/2)+5+mt_rand(-8,8);
  $ypos=(($height-50)/2)+5+mt_rand(-8,8);
 
  imagecolortransparent($buffer,$color_white);

  imagecopymerge($image,$buffer,$xpos,$ypos,0,0,imagesx($buffer),imagesy($buffer),100);
  imagedestroy($buffer);
  imagedestroy($buffer2);
 }




 // ##### DRAW LINES
  /*
 for($i=0;$i<12;$i++) {
  $color=imagecolorallocatealpha($image,mt_rand(0,200),mt_rand(0,200),mt_rand(0,200),110);
  imagesetthickness($image,mt_rand(8,20));
  imageline($image,mt_rand(-$width*0.25,$width*1.25),mt_rand(-$height*0.25,$height*1.25),
    mt_rand(-$width*0.25,$width*1.25),mt_rand(-$height*0.25,$height*1.25), $color);  
  imagesetthickness($image,1);
 }
*/

/*
 // ##### TAG
 $color=imagecolorallocatealpha($image,255,255,255,90);
 imagefilledrectangle($image,1,1,146,8,$color);
 $color=imagecolorallocatealpha($image,0,0,0,100);
 imagestring($image,1,2,1,"jhfCAPTCHA: find ".strlen($password)." characters",$color);
*/

 // ##### STORE PASSWORD
//setcookie ("captcha",substr(md5($password),4,6),time()+3600);

  // Problème avec l'utilisation des cookies > on passe par une session
  $_SESSION["captcha"] = substr(md5($password),4,6);

 // ##### OUTPUT
 header('Content-Type: image/png');
 imagepng($image);
 imagedestroy($image);

}


function checkcaptcha ($captchareply) {

 $captchaok=0;
 if ($_COOKIE['captcha']==substr(md5(strtoupper($captchareply)),4,6)&&$_COOKIE['captcha']){$captchaok=1;}
 return($captchaok);

}


function captchaform () {

 if (isset($_POST["captchareply"])) {
  if (checkcaptcha($_POST["captchareply"])) {$checked="OK";} else {$checked="WRONG";}
 } else {$checked="";}

 print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
 print "<html>\n";
 print " <head>\n";
 print "  <title>jhfCAPTCHA demo</title>\n";
 print " </head>\n";
 print " <body>\n";
 print "  <div>\n";
 print "   <form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
 print "    <h3>jhfCAPTCHA demo</h3>\n";
 if ($checked) {print "    <p>".$checked."</p>\n";}
 print "    <p><img src=\"".$_SERVER['PHP_SELF']."?function=captchaimage&amp;random=".mt_rand(10000,99999)."\" alt=\"captcha\" /></p>\n";
 print "    <p>\n";
 print "     <input name=\"captchareply\" type=\"text\" value=\"\" />\n";
 print "     <input type=\"submit\" value=\"Submit\" />\n";
 print "    </p>\n";
 print "   </form>\n";
 print "  </div>\n";
 print " </body>\n";
 print "</html>\n";

}


switch ($_GET['function']) {
 case captchaimage: captchaimage(); break;
 default: captchaform(); break;
}


?>