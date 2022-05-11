<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV-Lab.php';

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
				//$TabCSV = array();
				if (isFichierLumysCSV($_FILES["myfile"]["tmp_name"])) {					
					$RetourConversion = ConvertirLUMYSCMDcsvEnlab($_FILES["myfile"]["tmp_name"], $target_file);
				}else{
					$RetourConversion = ConvertirEXCELCMDcsvEnlab($_FILES["myfile"]["tmp_name"], $target_file);
				}
				if ($RetourConversion) {					
					$retourTraitementMSG .= "<h1>3) Créer les planches de la commande</h3>"  ;
					
					$retourTraitementMSG .= "<h3>" .	utf8_encode(substr($target_file ,14,-5)) . "</h3>";					
					$uploadOk = 2; // Flag test si OK
					$target_file_seul = substr($target_file, 14, -1); // Pour etre dans la même forme que . lab pas lab0
				}	
				else {					
					$retourTraitementMSG .= '<h3>' .	utf8_encode(substr($target_file ,14,-5)) . '</h3>';	
					$retourTraitementMSG .= '<h2>'. $GLOBALS['ERREUR_EnCOURS'] .'</h2>'  ;
					$target_file = '';
					$uploadOk = 0;
				}
			} 
			else {
				if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_file)) {
					$retourTraitementMSG .= "<h1>3) Créer les planches de la commande</h3>"  ;
					$retourTraitementMSG .= "<h3>" .	substr(basename($_FILES['myfile']['name']) ,0,-4) . " </h3>";
					$uploadOk = 2; // Flag test si OK
				}					
				else {
					$retourTraitementMSG = "APIPhotoLab : Probleme d'enregistrement de :" . $target_file;
					$uploadOk = 0;
				}				
			}
			$CMDhttpLocal ='';
			if ($uploadOk == 2) {				
				
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
					<a href="' . $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">OK ANCIEN</a>			
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
				<a href="index.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($target_file_seul) .'0" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
			</div>
			<h1><img src="img/AIDE.png" alt="Aide sur l\'étape" > Etape 1 : Vérification des scripts et fichiers source pour 
			<font size="-1">' . $target_file_seul .'0</font></h1>';
		$retourMSG .= '<table>
		<tr>
			<td width="50%">';	
		

			
			$retourMSG .= '	<div class="Planchecontainer">';

			if ($uploadOk == 2) {
				$retourMSG .= '<h1>1) Vérification des scripts Photoshop</h1>';
				$ProduitsManquant = 0;
				$retourMSG .= BilanScriptPhotoshop($target_file,$ProduitsManquant);
		
			}


			$retourMSG .= '</div>';
		$retourMSG .= '</td>
						<td width="50%">';	
		$retourMSG .= '	<div class="msgcontainer">';

		$retourMSG .= '<h1>2) Vérification des photos  "Sources"</h1>'; 
		echo $target_file_seul ;
		$retourMSG .= PhotosManquantes($target_file);

		$retourMSG .= "<h1>3) Créer les planches de la commande</h3>"  ;


			if ($uploadOk == 2) {		

				
		if($ProduitsManquant>0){
			$retourMSG .= '<h3>Vous ne pouvez pas créer votre commande, car un ou plusieurs produits de la commande ne sont pas définis!</h3>';
			$retourMSG .= "<h2>Corrigez les erreurs de produit</h2>";

			$retourMSG .= '<h3>cliquez sur le crayon en face le produit en rouge pour editer le produit</h3>';
		}else{
			$retourMSG .= "<h2>Démarrez le plug-in PhotoLab pour Photoshop</h2>";
			$retourMSG .= '<img src="img/LogoPSH.png" alt="Image de fichier" width="25%">';
			$retourMSG .= '<h3>Le plug-in PhotoLab (PLUGIN-PhotoLab.jsxbin) se trouve dans le dossier : /PhotoLab/Code</h3><br>';
		}




				if($ProduitsManquant==0){
					$retourMSG .= '<a href="' . $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) 
							.'" class="OK" title="Valider et retour écran général des commandes">OK</a>';
				}
			}else{
				$retourMSG .= $retourTraitementMSG;
			}
		
			
	
	$retourMSG .= '<br><br> </div>';	
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


function isFichierLumysCSV($fichierCSV) {
	$lines = file($fichierCSV);
	return (strpos($lines[0], 'Num de facture', 1) > 1);
	
}


?>