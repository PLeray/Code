<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV.php';

//$repCommandesLABO = "../../CMDLABO/";

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');
	
if(is_uploaded_file($_FILES["myfile"]["tmp_name"])) { // Recup le fichier lab uploadé par DROP (15 octobre)
		echo  API_PostFILELAB();
}	

function API_PostFILELAB() {//upload de fichier par DROP (15 octobre)	
	$retourTraitementMSG = '';	
	//$target_file_seul = utf8_decode(basename($_FILES['myfile']['name']));
	$target_file_seul = SUPRAccents(basename($_FILES['myfile']['name']));
	$target_file = $GLOBALS['repCMDLABO'] . $target_file_seul . "0";// 0 etat : uploadé / enregistré
	//echo $target_file;
	//$target_file = $target_file . "0"; // 0 etat : uploadé / enregistré
	$uploadOk = 1;
	$extensionsAutorisee = array('.lab', '.web', '.csv');
	$extension = strrchr($_FILES['myfile']['name'], '.'); 
	//Début des vérifications de sécurité...
	if(!in_array($extension, $extensionsAutorisee))	{ //Si l'extension n'est pas dans le tableau
		$retourTraitementMSG .= "APIPhotoLab : Vous devez sélectionner un fichier de type .lab, .web ou .csv ...";
		$uploadOk = 0;			 
	}
	// Check file size
	if ($_FILES["myfile"]["size"] > 500000) {
		$retourTraitementMSG .= "APIPhotoLab : Le fichier est trop gros, vérifiez...";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$retourTraitementMSG .= "APIPhotoLab : Ce fichier non valide.";
	} 
	else {// if everything is ok, try to upload file
		if (file_exists($_FILES["myfile"]["tmp_name"])){
			if ($extension == '.csv') {
				//Verif si fichier de commande web iso ou groupe ou pas bon !
				if (ConvertirCMDcsvEnlab($TabCSV, $_FILES["myfile"]["tmp_name"], $target_file) != '') {					
					$retourTraitementMSG .= "<h3>Pour créer les planches de la commande : '. $target_file .'</h3>"  ;
					$retourTraitementMSG .= "<h2>" .	utf8_encode(substr($target_file ,14,-5)) . "</h2>";					
					$uploadOk = 2; // Flag test si OK
					$target_file_seul = substr($target_file, 14, -1); // Pour etre dans la même forme que . lab pas lab0
				}	
				else {					
					$retourTraitementMSG .= '<h2>' .	utf8_encode(substr($target_file ,14,-5)) . '</h2>';	
					$retourTraitementMSG .= '<h4>'. $GLOBALS['ERREUR_EnCOURS'] .'</h4>'  ;
					$target_file = '';
					//$uploadOk = 0;
				}
			} 
			else {
				if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_file)) {
					$retourTraitementMSG .= "<h3>Pour créer les planches de la commande : </h3>"  ;
					$retourTraitementMSG .= "<h2>" .	substr(basename($_FILES['myfile']['name']) ,0,-4) . " </h2>";
					$uploadOk = 2; // Flag test si OK
				}					
				else {
					$retourTraitementMSG = "APIPhotoLab : Probleme d'enregistrement de :" . $target_file;
				}				
			}
			$CMDhttpLocal ='';
			if ($uploadOk == 2) {				
				$retourTraitementMSG .= '<h3>Démarrez le plug-in PhotoLab pour Photoshop<br>(PLUGIN-PhotoLab.jsxbin).</h3>';			
				$retourTraitementMSG .= '<img src="img/LogoPSH.png" alt="Image de fichier" width="25%">';
				//$CMDhttpLocal = '?RECFileLab=' . urlencode(basename($_FILES['myfile']['name']));	
			
				
				$mesInfosFichier = new CINFOfichierLab($target_file); 
				//$CMDAvancement ='';
				
				//$Compilateur = '';				
				$NBPlanches = $mesInfosFichier->NbPlanches;

				//$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
				//echo "Apres move_uploaded_file";
				$CMDhttpLocal = '&CMDdate=' . substr($target_file_seul, 0, 10);	
				$CMDhttpLocal .= '&CMDnbPlanches=' . $NBPlanches;
				$CMDhttpLocal .= '&BDDFileLab=' . urlencode(utf8_encode(basename($target_file_seul)));	
				
				$retourTraitementMSG .= '<br><br>
					<a href="' . $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">OK</a>			
					<br><br>';				
			}		
		}
		else{
			$retourTraitementMSG = "APIPhotoLab : Erreur " . $target_file . " est manquant !";
		}
	}
	$retourMSG = 
		'<!DOCTYPE html>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<html>
		<head>
		<link rel="stylesheet" type="text/css" href="'. strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") . '">
		<link rel="stylesheet" type="text/css" href="'. strMini("css/APIDialogue.css") . '">
		</head>
		<body>
		<body onload="document.getElementById(\'apiReponse\').style.display=\'block\'">';

		$retourMSG .= '<div id="apiReponse" class="modal">
		<div class="modal-content animate" >
			<div class="imgcontainer">
				<a href="CATPhotolab.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($target_file_seul) .'0" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
			</div>
			<h1><img src="img/AIDE.png" alt="Aide sur l\'étape" > Etape 1 : Vérification des scripts Photoshop et fichiers source</h1>';	
		$retourMSG .= '<table>
		<tr>
			<td width="50%">';	

			
			$retourMSG .= '	<div class="Planchecontainer">
			<h1>scripts Photoshop</h1>';
			//$retourMSG .= $monGroupeCmdes->tabCMDLabo;	


			// A REMETTRE !!! 
			$monGroupeCmdes = new CGroupeCmdes($target_file);
			// A REMETTRE !!! 
			$retourMSG .= $monGroupeCmdes->AffichePlancheAProduire(); 

			$retourMSG .= '<h1>Photos necessaires</h1>';
			$retourMSG .= 'Photos manquantes : 0';

			$retourMSG .= '</div>';
		$retourMSG .= '</td>
						<td width="50%">';	
		$retourMSG .= '	<div class="msgcontainer">';
		
			$retourMSG .= $retourTraitementMSG;
	
	$retourMSG .= ' </div>';	
	$retourMSG .= '</td>
	</tr>
 </table>	';	
	$retourMSG .= '	  
			</div>
		</div>
    </body>
    </html>';
/*	
	$retourMSG = 
	'<div id="apiReponse" class="modal">
		<div class="modal-content animate" >
			<div class="imgcontainer">
				<a href="CATPhotolab.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($target_file_seul).'0" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				<br>
			</div>' . $retourMSG;*/
	
	return $retourMSG;	
}

?>