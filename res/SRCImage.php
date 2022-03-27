<?php
// Le fichier
$filename = '';//'SOURCES/test.jpg';
$MaxTaille = 300;

// Content type
header('Content-Type: image/jpeg');

if (isset($_GET['fichierImage'])) { // recup $fichierImage
	$filename = $_GET['fichierImage'];
    ImageCache($filename ,$MaxTaille);
}

function ImageCache($NomImage,$MaxTaille){
	$strNomImageCache = str_replace('SOURCES', 'SOURCES/cache', $NomImage);
	$FichierCacheARefaire = false;	
	if (!file_exists($strNomImageCache)){
		$FichierCacheARefaire = true;	
	}else{
		if (filemtime($NomImage) > filemtime($strNomImageCache)){
			$FichierCacheARefaire = true;		 
		 }		
	}

	if ($FichierCacheARefaire){
		//Avant il faut verifier dossier existe et le creer
		CreationDossier(substr($strNomImageCache, 0, strripos($strNomImageCache, "/")));
		//CreationDossier('SOURCES/cache');

		$width = $MaxTaille;
		$height = $MaxTaille;
		// Cacul des nouvelles dimensions
		list($width_orig, $height_orig) = getimagesize($NomImage);
		$ratio_orig = $width_orig/$height_orig;
		if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
		} else {
		   $height = $width/$ratio_orig;
		}
		// Redimensionnement
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($NomImage);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);		
		$image=$image_p;		
		imagejpeg($image, $strNomImageCache, 100);
	}
	else{
		$image = imagecreatefromjpeg($strNomImageCache);
	}
	// Affichage
	imagejpeg($image, null, 100);	
}

function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}


?>