<?php 
include_once 'APIConnexion.php';

$codeMembre = '0';
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'index');

$MsgRecupCode = RecupCODE($maConnexionAPI->URL);

$MessageBox = 
'<!DOCTYPE html>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <html>
    <head>
	<link rel="stylesheet" type="text/css" href="'. strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") . '">
    <link rel="stylesheet" type="text/css" href="'. strMini("css/APIDialogue.css") . '">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    </head>
    <body>
	<body onload="document.getElementById(\'apiReponse\').style.display=\'block\'"><div id="apiReponse" class="modal">
		<div class="modal-content animate" >
			<div class="imgcontainer">
				<br><h1>Mise à jour<br><br><br></h1>
				<img src="img/Logo-mini.png" alt="Image de fichier" class="apiReponseIMG">
			</div>';

$MessageBox .= '<div class="msgcontainer">';
$MessageBox = $MessageBox . '<br><h3>'.$MsgRecupCode.'</h3><br>';

if (isset($_GET['versionDistante'])){$MessageBox .=  '<br><h3> versionLocal : ' . $GLOBALS['VERSIONLOCAL'] . ' > version Distante : ' .  $_GET['versionDistante'].'</h3><br>'; }

//if ($GLOBALS['isDebug']){$MessageBox = $MessageBox . "<br><h3>".$Etat." (en Debug)<br><br></h3>";}
//$MessageBox = $MessageBox . "<br><h3>Améliorations !</h3><br>";

//$CMDhttpLocal = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod');
	
$MessageBox .= '<br><br>
		<a href="' . $GLOBALS['maConnexionAPI']->CallServeur('') . '" class="OK" title="Valider et retour écran général des commandes">OK</a>		
			<br><br><br>';

$MessageBox .= '
		</div>	  
	</div>
</div>
</body>
</html>';



//echo MiseAjour($maConnexionAPI->URL);


echo $MessageBox;	

function RecupCODE($urlBase){
	$commentaire ='';
	$msgTelechargement ='';
	if(TelechargerFichier('Code.zip', $urlBase, $msgTelechargement)){ 
		$commentaire .= " Fichier téléchargé avec succès" . '<br><br>' . $msgTelechargement; 				
	} 
	else { 
		$commentaire .= " !! Fichier NON téléchargé !! " . '<br><br>' . $msgTelechargement; 	
	}
	$commentaire .= '<br><br>';	
	return $commentaire;
}

//  header('Location: index.php'. ArgumentURL());

function TelechargerFichier($nomFichier, $urlBase, &$msgDezip){
	$retour = false;
	if ($nomFichier != ''){
		$url = $urlBase . '/installation/PhotoLab/' . $nomFichier ;
		//echo $url . ' : ';	

		//$fichier_contenu = file_get_contents($url);
		
		if(file_exists ('../../NO-MAJ-Auto.txt')){
			$dossier_enregistrement = "../../telechargement/";
		}else{
			$dossier_enregistrement = "../../";
		}
		CreationDossier(dirname($dossier_enregistrement . $nomFichier)).'<br><br>';

		//$retour = file_put_contents($dossier_enregistrement . $nomFichier, $fichier_contenu);
		$retour = TransfertFichier($url, $dossier_enregistrement . $nomFichier);
		
		//Supression des rep Css json_decode
		SuprArborescenceDossier($dossier_enregistrement . 'Code/res/css');
		SuprArborescenceDossier($dossier_enregistrement . 'Code/res/js');		
		
		$msgDezip = DezipperFichier($dossier_enregistrement , $nomFichier);
		if(!file_exists ('../../NO-MAJ-Auto.txt')){
			// A REMETTRE unlink($dossier_enregistrement . $nomFichier);
		}		
	}
	return $retour;
}

function TransfertFichier($urlInitiale, $urlFinale){
	$retour = Curl_get_file_contents($urlInitiale);
	if($retour){file_put_contents($urlFinale, $retour);}
	return $retour ; 
}

function Curl_get_file_contents( $URL ){
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$URL);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, 'PhotoLab');
	$query = curl_exec($curl_handle);
	curl_close($curl_handle);
	if( $query ) :
		return $query;
	else:
		return false;
	endif;	
}


function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}

function DezipperFichier($Dossier, $fichier) {
	$MSG ="";
	$zip = new ZipArchive;
	if ($zip->open($Dossier.$fichier) === TRUE) {
		$zip->extractTo($Dossier);
		$zip->close();
		$MSG = 'Extraction des fichiers : ok';
	} else {
		$MSG = 'Extraction des fichiers : échec';
	}
	return $MSG;
}	
	
	
	
	




?> 