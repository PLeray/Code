<?php 
include_once 'APIConnexion.php';

$codeMembre = '0';
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'index');



$EnteteHTML = 
    '<!DOCTYPE html>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <html>
    <head>
	<link rel="stylesheet" type="text/css" href="'. strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") . '">
    <link rel="stylesheet" type="text/css" href="'. strMini("css/API_PhotoLab.css") . '">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    </head>
    <body>
	<body onload="document.getElementById(\'apiReponse\').style.display=\'block\'">';
	
	
$BotomHTML = '
    </body>
    </html>';

$DebutMessageBox =
'<div id="apiReponse" class="modal">
	<div class="modal-content animate" >
		<div class="imgcontainer">
			<a href="../index.php" class="close" title="Annuler et retour écran général des commandes">&times;</a>
			<img src="img/Logo-Go-PhotoLab.png" alt="Image de fichier" class="apiReponseIMG">
		</div>';




	$retourMSG = $DebutMessageBox;
	$retourMSG .= '	<div class="msgcontainer">';
$retourMSG .= "<br><h3>MiSE a Jour .<br><br><br></h3>";
		break;		
	case "2":
		$retourMSG .= "<br><h3>Les planches ont été envoyés au laboratoire ?<br><br><br></h3>";
		break;
	case "3":
		$retourMSG .= "<br><h3>Les photos sont tirées au laboratoire ?<br><br><br></h3>";
		break;		
	case "4":
		$retourMSG .= "<br><h3>Les photos sont mise en carton. Fin<br><br><br></h3>";
		break;	
	}

	$retourMSG .=  "<br><h1>".utf8_encode(substr($strAPI_fichierLAB,0,-5))."</h1>";
	
	
	if ($GLOBALS['isDebug']){$retourMSG = $retourMSG . "<br><h3>".$Etat." (en Debug)<br><br></h3>";}
	$retourMSG = $retourMSG . "<br><h3>Si oui valider !</h3><br>";

	$CMDhttpLocal = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod');
	$CMDhttpLocal = $CMDhttpLocal . '&apiFichierChgEtat='. urlencode(utf8_encode($strAPI_fichierLAB)) .'&apiEtat=' . $Etat;
	
	$retourMSG .= '<br><br>
		<a href="../index.php" class="KO" title="Valider et retour écran général des commandes">Annuler</a>
		<a href="' . $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">Valider</a>		
			<br><br><br>';

	$retourMSG .= '
		</div>	  
	</div>
</div>';	













		

echo $EnteteHTML ;	

echo MiseAjour($maConnexionAPI->URL);

echo $BotomHTML;	




//  header('Location: index.php'. ArgumentURL());

function MiseAjour($urlBase){
	//$urlBase = 'https://www.photolab-site.fr'; 
	//$URLLBase = 'http://localhost/API_photolab';
	$commentaire = '';
	$lines = file( $urlBase. '/installation/installationFICHIERS.txt');
	foreach ($lines as $line_num => $line) {
		if (trim($line) != ''){
			$commentaire .= "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) ;
			if(TelechargerFichier(htmlspecialchars(trim($line)), $urlBase)) 
			{ 
				$commentaire .= " Fichier téléchargé avec succès"; 				
			} 
			else 
			{ 
				$commentaire .= " !! Fichier NON téléchargé !! "; 
			}
			$commentaire .= '<br><br>';
		}
	}
	return $commentaire;
}

function TelechargerFichier($nomFichier, $urlBase){
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
		//echo $dossier_enregistrement . $nomFichier ;
		//echo dirname($dossier_enregistrement . $nomFichier) .'<br><br>';
		CreationDossier(dirname($dossier_enregistrement . $nomFichier)).'<br><br>';

		//$retour = file_put_contents($dossier_enregistrement . $nomFichier, $fichier_contenu);
		$retour = TransfertFichier($url, $dossier_enregistrement . $nomFichier);
		DezipperFichier($dossier_enregistrement , $nomFichier);
		
		if(!file_exists ('../../NO-MAJ-Auto.txt')){
			unlink($dossier_enregistrement . $nomFichier);
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

function TransfertFichierOLD($urlInitiale, $urlFinale){
	$retour = file_put_contents($urlFinale, file_get_contents($urlInitiale));
}

function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}

function DezipperFichier($Dossier, $fichier) {
	$zip = new ZipArchive;
	if ($zip->open($Dossier.$fichier) === TRUE) {
		$zip->extractTo($Dossier);
		$zip->close();
		echo 'ok';
	} else {
		echo 'échec';
	}
	
}	
	
	
	
	




?> 