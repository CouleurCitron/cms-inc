<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php'); 
// ================================================
// Fonctions de manipulation des images
// ================================================
if(PHP_VERSION >= 5)
    include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/lib/bmp-php/BMP.php');

/*

function imagecreatefrombmp($p_sFile){
	//    Load the image into a string
	$file    =    fopen($p_sFile,"rb");
	$read    =    fread($file,10);
	while(!feof($file)&&($read<>""))
		$read    .=    fread($file,1024);
   
	$temp    =    unpack("H*",$read);
	$hex    =    $temp[1];
	$header    =    substr($hex,0,108);
   
	//    Process the header
	//    Structure: http://www.fastgraph.com/help/bmp_header_format.html
	if (substr($header,0,4)=="424d")
	{
		//    Cut it in parts of 2 bytes
		$header_parts    =    str_split($header,2);
	   
		//    Get the width        4 bytes
		$width            =    hexdec($header_parts[19].$header_parts[18]);
	   
		//    Get the height        4 bytes
		$height            =    hexdec($header_parts[23].$header_parts[22]);
	   
		//    Unset the header params
		unset($header_parts);
	}
   
	//    Define starting X and Y
	$x                =    0;
	$y                =    1;
   
	//    Create newimage
	$image            =    imagecreatetruecolor($width,$height);
   
	//    Grab the body from the image
	$body            =    substr($hex,108);

	//    Calculate if padding at the end-line is needed
	//    Divided by two to keep overview.
	//    1 byte = 2 HEX-chars
	$body_size        =    (strlen($body)/2);
	$header_size    =    ($width*$height);

	//    Use end-line padding? Only when needed
	$usePadding        =    ($body_size>($header_size*3)+4);
   
	//    Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption
	//    Calculate the next DWORD-position in the body
	for ($i=0;$i<$body_size;$i+=3)
	{
		//    Calculate line-ending and padding
		if ($x>=$width)
		{
			//    If padding needed, ignore image-padding
			//    Shift i to the ending of the current 32-bit-block
			if ($usePadding)
				$i    +=    $width%4;
		   
			//    Reset horizontal position
			$x    =    0;
		   
			//    Raise the height-position (bottom-up)
			$y++;
		   
			//    Reached the image-height? Break the for-loop
			if ($y>$height)
				break;
		}
	   
		//    Calculation of the RGB-pixel (defined as BGR in image-data)
		//    Define $i_pos as absolute position in the body
		$i_pos    =    $i*2;
		$r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]);
		$g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]);
		$b        =    hexdec($body[$i_pos].$body[$i_pos+1]);
	   
		//    Calculate and draw the pixel
		$color    =    imagecolorallocate($image,$r,$g,$b);
		imagesetpixel($image,$x,$height-$y,$color);
	   
		//    Raise the horizontal position
		$x++;
	}
   
	//    Unset the body / free the memory
	unset($body);
   
	//    Return image-object
	return $image;
} */


// Fonction générique pour remonter infos image
function imgData($visuel, $niveau=0){	
	$visuel = str_replace(array('{', '}'), '', $visuel);		
	$aVisuel = explode(';', $visuel);
	if($niveau){
		$fichier = explode('[', str_replace(']', '', $aVisuel[$niveau]));
	} else {
		if(isset($aVisuel[1])) $fichier = explode('[', str_replace(']', '', $aVisuel[1]));
                else  $fichier = explode('[', str_replace(']', '', $aVisuel[0]));
	}
	$aTab = explode('[', str_replace(']', '', $visuel));
        //pre_dump($aTab);
	// Savoir si l'image possède des datas
	if(count($aTab)<=1){
		//Cas juste fichier img pas de data
		$aImg["fichier"] = $fichier[0];
		
	} else {
		//Cas datas
		$aImg["fichier"] = $fichier[0];
		$titre = explode('::', $aTab[1]);
		$aImg["titre"] = $titre[1];
		$meta = explode('::', $aTab[2]);
		$aImg["meta"] = $meta[1];
		$url = explode('::', $aTab[3]);
		$aImg["url"] = $url[1];
	}

	return $aImg;
}


function imageoutputtoAnyFile($oIm, $newImgPath){
	if (preg_match ('/.*\.png/i',  basename($newImgPath))==1){ // png
		//error_log('png : '.basename($newImgPath));
		 return imagepng($oIm, $newImgPath);
	}
	elseif (preg_match ('/.*\.gif/i',  basename($newImgPath))==1){ // gif
		//error_log('gif : '.basename($newImgPath));
		return imagegif($oIm, $newImgPath);					
	}
	elseif (preg_match ('/.*\.[jpeg|jpeg|bmp]/i',  basename($newImgPath))==1){ // jpg et les BMP !!
		//error_log('jpg : '.basename($newImgPath));	
		return imagejpeg($oIm, $newImgPath, 80);				
	}
	else{
		return false;	
	}
}

function imagecreatefromAnyFile($newImgPath){
	if (preg_match ('/.*\.png/i',  basename($newImgPath))==1){ // png
		//error_log('png : '.basename($newImgPath));
		$im = imagecreatefrompng($newImgPath);
                 
		$background = imagecolorallocate($im, 0, 0, 0);
		// removing the black from the placeholder
		imagecolortransparent($im, $background);

		// turning off alpha blending (to ensure alpha channel information 
		// is preserved, rather than removed (blending with the rest of the 
		// image in the form of black))

		imagealphablending($im, true);

		// turning on alpha channel information saving (to ensure the full range 
		// of transparency is preserved)
		imagesavealpha($im, true);
	}
	elseif (preg_match ('/.*\.gif/i',  basename($newImgPath))==1){ // gif
		//error_log('gif : '.basename($newImgPath));
		$im = imagecreatefromgif($newImgPath);					
	}
	elseif (preg_match ('/.*\.[jpeg|jpeg]/i',  basename($newImgPath))==1){ // jpg
		//error_log('jpg : '.basename($newImgPath));	
		$im = imagecreatefromjpeg($newImgPath);			
	}
	elseif (preg_match ('/.*\.[bmp]/i',  basename($newImgPath))==1){ // jpg
		//error_log('bmp : '.basename($newImgPath));	
		$im = imagecreatefrombmp($newImgPath);				
	}	
	else{
		//error_log('unkown image type : '.basename($newImgPath));
		$im = imagecreatetruecolor(100,100);		
	}
	return $im;
}


function capImageObjectToMxDim($oIm, $eMaxDim){
	//error_log('capImageObjectToMxDim(oIm, '.$eMaxDim.')');
	
	$imW = imagesx($oIm);
	$imH = imagesy($oIm);
	
	//chercher la petite dim
	if ($imW > $imH){// paysage
		if ($imH>$eMaxDim){
			$ratio = $eMaxDim/$imH;
		}
		elseif($imW>$eMaxDim){
			$ratio = $eMaxDim/$imW;
		}
		else{ // ras
			return $oIm;
		}
	}
	else{// portrait
		if ($imW>$eMaxDim){
			$ratio = $eMaxDim/$imH;
		}
		elseif($imH>$eMaxDim){
			$ratio = $eMaxDim/$imW;
		}	
		else{ // ras
			return $oIm;
		}
	}

	$new_width = floor($imW * $ratio);
	$new_height = floor($imH * $ratio);
	
	// Resample
	$oNewIm = imagecreatetruecolor($new_width, $new_height);
        
	imagecolortransparent($oNewIm, imagecolorallocatealpha($oNewIm, 0, 0, 0, 127));
	imagealphablending($oNewIm, false);
	imagesavealpha($oNewIm, true);
        
	imagecopyresampled($oNewIm, $oIm, 0, 0, 0, 0, $new_width, $new_height, $imW, $imH);

	return $oNewIm;
}

function cropImageObject($oIm, $eLeft, $eTop, $eWidth, $eHeight){
	//error_log('cropImageObject(oIm, '.$eWidth.', '.$eHeight.')');	
	$oCrop = imagecreatetruecolor($eWidth, $eHeight);

	imagealphablending($oCrop, false);
	imagesavealpha($oCrop, true);  
	imagealphablending($oIm, true);
	
	$bres = imagecopy ($oCrop, $oIm, 0, 0, $eLeft, $eTop, $eWidth, $eHeight);
	return $oCrop;
}

function resizeImageObjectWidthHeightStrict($oIm, $eWidth, $eHeight){
	error_log('resizeImageObjectWidthHeightStrict(oIm, '.$eWidth.', '.$eHeight.')');
	
	$imW = imagesx($oIm);
	$imH = imagesy($oIm);
	
	// Resample
	$oNewIm = imagecreatetruecolor($eWidth, $eHeight);
        
        imagecolortransparent($oNewIm, imagecolorallocatealpha($new, 0, 0, 0, 127));
        imagealphablending($oNewIm, false);
        imagesavealpha($oNewIm, true);
        
	imagecopyresampled($oNewIm, $oIm, 0, 0, 0, 0, $eWidth, $eHeight, $imW, $imH);

	return $oNewIm;
}

function resizeImageObjectWidthHeightWise($oIm, $eWidth, $eHeight){
	//error_log('resizeImageObjectWidthHeightWise(oIm, '.$eWidth.', '.$eHeight.')');
	
	$imW = imagesx($oIm);
	$imH = imagesy($oIm);
	
	$ratioW = $eWidth/$imW;
	$ratioH = $eHeight/$imH;
	
	if($ratioW<$ratioH){ // on garde le ratio le plus sévère
		$ratio=$ratioW;
	}
	else{
		$ratio=$ratioH;
	}

	$new_width = floor($imW * $ratio);
	$new_height = floor($imH * $ratio);
	
	// Resample
	$oNewIm = imagecreatetruecolor($new_width, $new_height);
        
        imagecolortransparent($oNewIm, imagecolorallocatealpha($oNewIm, 0, 0, 0, 127));
        imagealphablending($oNewIm, false);
        imagesavealpha($oNewIm, true);
        
	imagecopyresampled($oNewIm, $oIm, 0, 0, 0, 0, $new_width, $new_height, $imW, $imH);

	return $oNewIm;
}

function resizeImageObjectWidthWise($oIm, $eWidth){
	//error_log('resizeImageObjectWidthWise(oIm, '.$eWidth.')');
	
	$imW = imagesx($oIm);
	$imH = imagesy($oIm);
	
	$ratio = $eWidth/$imW;

	$new_width = $eWidth;
	$new_height = floor($imH * $ratio);
	
	// Resample
	$oNewIm = imagecreatetruecolor($new_width, $new_height);
        
        imagecolortransparent($oNewIm, imagecolorallocatealpha($oNewIm, 0, 0, 0, 127));
        imagealphablending($oNewIm, false);
        imagesavealpha($oNewIm, true);
        
	imagecopyresampled($oNewIm, $oIm, 0, 0, 0, 0, $new_width, $new_height, $imW, $imH);

	return $oNewIm;
}

function resizeImageObjectHeightWise($oIm, $eHeight){
	//error_log('resizeImageObjectHeightWise(oIm, '.$eHeight.')');
	
	$imW = imagesx($oIm);
	$imH = imagesy($oIm);
	
	$ratio = $eHeight/$imH;

	$new_width = floor($imW * $ratio);
	$new_height = $eHeight;
	
	// Resample
	$oNewIm = imagecreatetruecolor($new_width, $new_height);
        
        
        imagecolortransparent($oNewIm, imagecolorallocatealpha($oNewIm, 0, 0, 0, 127));
        imagealphablending($oNewIm, false);
        imagesavealpha($oNewIm, true);
        
	imagecopyresampled($oNewIm, $oIm, 0, 0, 0, 0, $new_width, $new_height, $imW, $imH);

	return $oNewIm;
}

function ResizeImg($imgSource, $width=100, $height=100, $imgDest=null) {
// Redimensionne une image "$imgSource"
// dans les dimensions précisées (par défaut 100)
// Ecrit la nouvelle image soit dans un fichier
// soit directement en flux dans le navigateur si $imgDest=null
// EX: ResizeImg("index.png",100,100,"index_thumb.png");

// Récup des infos images (dimensions et type mime)
$aInfos = getimagesize($imgSource);
$width_orig = $aInfos[0];
$height_orig = $aInfos[1];
$orig_image_mimetype = $aInfos["mime"];

//si les dimension passée en parametres sont -1 et -1, on ne redimmensionne pas l'image
if ($width==-1 && $height==-1){
$width=$width_orig;
$height=$height_orig;
}
// Content type
if($imgDest==null) header('Content-type: '.$orig_image_mimetype);


//------------------------------
// dimensions

// la taille et la hauteur d'origine width_orig et height_orig 
// doivent être réduites pour atteindre width et height

// premier filtre appliqué -> réduction largeur
// calcul du pourcentage de réduction
$eReducWidth = $width / $width_orig;
// la hauteur suit proportionnellement la réduction pour ne pas déformer l'image
$width_calc = $eReducWidth * $width_orig;
$height_calc = $eReducWidth * $height_orig;

// deuxième filtre appliqué -> réduction hauteur
// il se peut que la hauteur soit malgré tout trop grande
if ($height < $height_calc) {
	// calcul du pourcentage de réduction
	$eReducHeight = $height / $height_calc;
	// la largeur suit proportionnellement la réduction pour ne pas déformer l'image
	$width_calc = $eReducHeight * $width_calc;
	$height_calc = $eReducHeight * $height_calc;
}      

// dimensions finales
$width = $width_calc;
$height = $height_calc;
//------------------------------

// Redimensionnement
$image_p = imagecreatetruecolor($width, $height);


   //Get file type
   $mime = $orig_image_mimetype;
   $type = substr($mime, strpos($mime,'/')+1);
   switch($type) {
       case 'jpeg':
       case 'pjpeg':
			$image = imagecreatefromjpeg($imgSource);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			imagejpeg($image_p, $imgDest, 100); // Affichage
			break;
       case 'png':
       case 'x-png':
			$image = imagecreatefrompng($imgSource);
           
                        imagealphablending( $image_p, false );
                        imagesavealpha( $image_p, true );
                        
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			imagepng($image_p, $imgDest, 100); // Affichage
			break;
       case 'gif':
			$image = imagecreatefromgif($imgSource);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			imagegif($image_p, $imgDest, 100); // Affichage
			break;
       default:
			return FALSE;
			break;
   }

}

function blur (&$image, $dist) {
    $imagex = imagesx($image);
    $imagey = imagesy($image);

	//$dist = 1;  // original setting

    for ($x = 0; $x < $imagex; ++$x) {
        for ($y = 0; $y < $imagey; ++$y) {
            $newr = 0;
            $newg = 0;
            $newb = 0;

            $colours = array();
            $thiscol = imagecolorat($image, $x, $y);

            for ($k = $x - $dist; $k <= $x + $dist; ++$k) {
                for ($l = $y - $dist; $l <= $y + $dist; ++$l) {
                    if ($k < 0) { $colours[] = $thiscol; continue; }
                    if ($k >= $imagex) { $colours[] = $thiscol; continue; }
                    if ($l < 0) { $colours[] = $thiscol; continue; }
                    if ($l >= $imagey) { $colours[] = $thiscol; continue; }
                    $colours[] = imagecolorat($image, $k, $l);
                }
            }

            foreach($colours as $colour) {
                $newr += ($colour >> 16) & 0xFF;
                $newg += ($colour >> 8) & 0xFF;
                $newb += $colour & 0xFF;
            }

            $numelements = count($colours);
            $newr /= $numelements;
            $newg /= $numelements;
            $newb /= $numelements;

            $newcol = imagecolorallocate($image, $newr, $newg, $newb);
            imagesetpixel($image, $x, $y, $newcol);
        }
    }
} 

function nonProgressiveJPEG($imgFile){
	$im = @imagecreatefromjpeg($imgFile);
	imageinterlace($im, 0);
	return imagejpeg($im, $imgFile, 60);
}

?>