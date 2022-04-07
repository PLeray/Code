<?php 
/*
$url = 'https://photolab-site.fr/installation/PhotoLab/Code/installationFICHIERS.txt';



// Lit une page web dans un tableau.
$lines = file('https://photolab-site.fr/installation/PhotoLab/Code/installationFICHIERS.txt');

// Affiche toutes les lignes du tableau comme code HTML, avec les numéros de ligne
foreach ($lines as $line_num => $line) {
    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
}











$url = 'https://waytolearnx.com/wp-content/uploads/2018/09/cropped-logoWeb.png'; 
$url = 'http://localhost/API_photolab/installation/PhotoLab/Code/telechargement.zip';



$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n")); 
//Basically adding headers to the request
$context = stream_context_create($opts);
$fichier_contenu = file_get_contents($url,false,$context);

$fichier_contenu = file_get_contents($url);


$fichier_nom = basename($url);
$dossier_enregistrement = "../telechargement/";



echo $fichier_contenu;

if(file_put_contents($dossier_enregistrement . $fichier_nom, $fichier_contenu)) 
{ 
    echo "Fichier téléchargé avec succès"; 
} 
else 
{ 
    echo "Fichier non téléchargé"; 
} 
*/



// Lit une page web dans un tableau.
//$lines = file('https://photolab-site.fr/installation/PhotoLab/Code/installationFICHIERS.txt');
$lines = file( 'http://localhost/API_photolab/installation/PhotoLab/Code/installationFICHIERS.txt');
//$lines = file( 'http://localhost/API_photolab/installation/PhotoLab/Code/res/APIDialogue.php');
//$lines = file( 'http://localhost/API_photolab/installation/PhotoLab/Code/res/css/API_PhotoLab.css');
// Affiche toutes les lignes du tableau comme code HTML, avec les numéros de ligne
foreach ($lines as $line_num => $line) {
    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";

	if(TelechargerFichier(htmlspecialchars(trim($line)))) 
	{ 
		echo "Fichier téléchargé avec succès"; 
	} 
	else 
	{ 
		echo "Fichier non téléchargé"; 
	}/* */
}


function download_file33($url, $path) {
$file = 'http://3.bp.blogspot.com/-AGI4aY2SFaE/Tg8yoG3ijTI/AAAAAAAAA5k/nJB-mDhc8Ds/s400/rizal001.jpg';
$newfile = $_SERVER['DOCUMENT_ROOT'] . '/img/submitted/yoyo.jpg';
$file = $url;
$newfile = $path;
if ( copy($file, $newfile) ) {
    echo "Copy success!";
}else{
    echo "Copy failed.";
}
}
 
function download_file ($url, $path) {



  $newfilename = $path;
  $file = fopen ($url, "rb");
  if ($file) {
    $newfile = fopen ($newfilename, "wb");

    if ($newfile)
    while(!feof($file)) {
      fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8 );
    }
  }

  if ($file) {
    fclose($file);
  }
  if ($newfile) {
    fclose($newfile);
  }
  return true;
}
 
function TelechargerFichier($nomFichier){
	$url = 'https://photolab-site.fr';
	$url = 'http://localhost/API_photolab';
	$url = $url . '/installation/PhotoLab/Code/' . $nomFichier ;
	echo $url.'<br><br>';	
	
	/*$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n")); 
	//Basically adding headers to the request
	$context = stream_context_create($opts);
	$fichier_contenu = file_get_contents($url,false,$context);	
	*/
	$fichier_contenu = file_get_contents($url);

	
/*	
	$arrContextOptions=array(
	  "ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);  

$fichier_contenu = file_get_contents($url, false, stream_context_create($arrContextOptions));
	
	*/
	
	
	

	//$fichier_nom = basename($url);
	
	$dossier_enregistrement = "../telechargement/";
	echo $dossier_enregistrement . $nomFichier .'<br><br>';
	//echo dirname($dossier_enregistrement . $nomFichier) .'<br><br>';
	CreationDossier(dirname($dossier_enregistrement . $nomFichier)).'<br><br>';
	
	//return download_file ($url, $dossier_enregistrement . $nomFichier );
	//return download_file33 ($url, $dossier_enregistrement . $nomFichier );
	return file_put_contents($dossier_enregistrement . $nomFichier, $fichier_contenu);
}

function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}


?> 