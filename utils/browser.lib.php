<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Utility class
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2004 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2004-11-11
// ================================================

class Browser_Util
{
  // checks browser compatibility with the control
  function checkBrowser()
  {
    $browser = $_SERVER['HTTP_USER_AGENT'];
    // check if msie
    if (preg_match("/MSIE[^;]*/msi",$browser,$msie))
    {
      // get version 
      if (preg_match("/[0-9]+\.[0-9]+/msi",$msie[0],$version))
      {
        // check version
        if ((float)$version[0]>=5.5)
        {
          // finally check if it's not opera impersonating ie
          if (!preg_match("/opera/msi",$browser))
          {
            return true;
          }
        }
      }
    }
    elseif (preg_match("/Gecko\/([0-9]*)/msi",$browser,$build))
    {
      // build date of version 1.3 is 20030312
      if ($build[1] > "20030312")
        return true;
      else
        return false;
    }
    return false;
  }
  
  // returns browser type
  function getBrowser()
  {
    $browser = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/opera/msi',$browser))
    {
      return 'Opera';
    }
    elseif (preg_match('/MSIE/msi',$browser))
    {
      return 'IE';
    }
    elseif (preg_match('/Gecko/msi',$browser))
    {
      return 'Gecko';
    }
    else
    {
      return 'Unknown';
    }
  }
}
?>