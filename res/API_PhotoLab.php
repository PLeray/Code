<?php
//include 'CConnexionLOCAL.php';
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
//AMP ?
$codeMembre = false;
if (isset($_POST['codeMembre']) ){
	$codeMembre = $_POST['codeMembre'];
}
if (isset($_GET['codeMembre'])) { // Test connexion l'API
	$codeMembre = $_GET['codeMembre'];
}
//DEBUG ?NBPlanchesFichierLab

$isDebug = file_exists ('../debug.txt');
if ($isDebug) echo 'MODE DEBUG';
//else echo 'MODE NORMAL'; 

if (isset($_POST['isDebug']) ){
	$isDebug = ($_POST['isDebug'] == 'Debug');
}
if (isset($_GET['isDebug'])) { // Test connexion l'API
	$isDebug = ($_GET['isDebug'] == 'Debug');
}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');

$EnteteHTML = 
    '<!DOCTYPE html>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <html>
    <head>
	<link rel="stylesheet" type="text/css" href="'. strMini("css/Couleurs" . ($GLOBALS['isDebug']?"":"AMP") . ".css") . '">
    <link rel="stylesheet" type="text/css" href="'. strMini("css/API_PhotoLab.css") . '">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
    </head>
    <body>
	<body onload="document.getElementById(\'apiReponse\').style.display=\'block\'">';
	
$ScriptJS = 
    '<script>
		document.getElementById(\'myfile\').onchange = function () {
		document.getElementById(\'SelectUploadFiles\').value = this.value;
};

</script>';
	
$BotomHTML = '
    </body>
    </html>';

$DebutMessageBox =
'<div id="apiReponse" class="modal">
	<div class="modal-content animate" >
		<div class="imgcontainer">
			<a href="CATPhotolab.php' . ArgumentURL() .'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
			<img src="img/Logo.png" alt="Image de fichier" class="apiReponseIMG">
		</div>';
		

	
if (isset($_GET['apiTEST'])) { // Test connexion l'API
    echo API_TEST($_GET['apiTEST']);
} 
elseif (isset($_GET['apiCMDLAB'])) { // Renvoie les planches à générer du fichier lab en parametre
    echo API_GetCMDLAB($_GET['apiCMDLAB']);
} 
elseif (isset($_GET['apiFILELAB'])) { // Renvoie un fichier lab (De .lab a lab0)????
    echo API_GetFILELAB($_GET['apiFILELAB']);
} 
elseif (isset($_GET['apiUI_SELECTFILELAB'])) { // Formulaire de selection d'un fichier lab a enregistrer
	echo $EnteteHTML . API_UISelectFILELAB($_GET['apiUI_SELECTFILELAB']) . $BotomHTML;	
}
elseif (isset($_GET['apiUI_CONFIRMEtat']) && isset($_GET['apiEtat'])) {       
	echo $EnteteHTML . API_UIConfirmation($_GET['apiUI_CONFIRMEtat'], $_GET['apiEtat']) . $BotomHTML;	
}
elseif (isset($_GET['apiChgEtat']) && isset($_GET['apiEtat'])) { 
	ChangeEtat($_GET['apiChgEtat'], $_GET['apiEtat']);
} 
elseif (isset($_GET['apiPhotoshop'])) { 
	echo $EnteteHTML . API_Photoshop($_GET['apiPhotoshop']). $BotomHTML;	
} 
elseif (isset($_GET['apiDemandeNOMCommande'])) { 
	echo $EnteteHTML . API_DemandeNOMComamnde(). $BotomHTML;	
} 
elseif (isset($_FILES['fileToDrop'])) {
	echo API_DropFILELAB();
}
elseif (isset($_POST['lesRecommandes']) ){
	if ($isDebug){
		echo 'VOILA LES RECO  pour : ' . $_POST['leFichierOriginal']  . ' : ' . $_POST['lesRecommandes'];
	}	

		echo API_EnregistrerCommandes();
}


else {
	if(is_uploaded_file($_FILES["myfile"]["tmp_name"])) { // Recup le fichier lab uploadé
		echo $EnteteHTML . API_PostFILELAB() . $BotomHTML;
	} 
	else echo 'Rien à Afficher pas de parametres ?! !';		

}

///////////////////////////////////////////////////////////////
///////////// Les Fonctions selon les cas ...  ////////////////
///////////////////////////////////////////////////////////////

/* Supression LE 18 Fev 2022 
function API_GetCMDLAB($strAPI_CMDLAB){
	if ($strAPI_CMDLAB == "TEST"){
		return "OK";
	}
	else {
		$GLOBALS['repCMDLABO'] = "CMDLABO/";
		if (file_exists($GLOBALS['repCMDLABO'] . $strAPI_CMDLAB)){
			$strCMDLabo = RecupPlanchesFichierLab($GLOBALS['repCMDLABO'] . $strAPI_CMDLAB);
			return 'OK' . $strCMDLabo;
		}
		else {
			return " le fichier " .$GLOBALS['repCMDLABO'] . $strAPI_CMDLAB . " est manquant !";
			return "APIPhotoLab : erreur 33";
		}		
	}
}
*/
function API_GetFILELAB($strAPI_FILELAB){
	$GLOBALS['repCMDLABO'] = "CMDLABO/";
	if (file_exists($GLOBALS['repCMDLABO'] . $strAPI_FILELAB)){
		$strCMDLabo = RecupFichierLabTotal($GLOBALS['repCMDLABO'] . $strAPI_FILELAB);
		return 'OK' . $strCMDLabo;
	}
	else {
		return " le fichier " .$GLOBALS['repCMDLABO'] . $strAPI_FILELAB . " est manquant !";
		return "APIPhotoLAb : erreur 55";
	}		
}

function API_DropFILELAB() {//upload de fichier
	$sFileName = $_FILES['fileToDrop']['name'];
	$sFileSize = bytesToSize1024($_FILES['fileToDrop']['size']);
	$target_file = '../CMDLABO/' . $_FILES['fileToDrop']['name']."0";
	move_uploaded_file($_FILES['fileToDrop']['tmp_name'], $target_file);	
	
	$NBPlanches = ($target_file);
	//echo "Apres move_uploaded_file";
	$CMDhttpLocal = '&CMDdate=' . substr($sFileName, 0, 10);	
	$CMDhttpLocal = $CMDhttpLocal . '&CMDnbPlanches=' . $NBPlanches;
	$CMDhttpLocal = $CMDhttpLocal . '&BDDFileLab=' . urlencode(basename($sFileName));	
	
	echo '
	<div class="dropAreaRESULT">
		<p>La commande : ' . $sFileName .' a été correctement transférée. </p>
		<p> => Taille : '.$sFileSize.'</p>';

	//echo $CMDhttpLocal ;
	$maLocation = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal);
	echo $maLocation;
	echo '</div>';
	header('Location: ' .$maLocation); /**/
}


function bytesToSize1024($bytes) {
	$unit = array('B','KB','MB');
	return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 1).' '.$unit[$i];
}

function API_UISelectFILELAB($strAPI_SelectFILELAB){
	$Formulaire =
	'<!-- UPLOAD FILE -->
	<div id="apiReponse" class="modal">
	  <form class="modal-content animate" action="API_photolab.php" method="post" enctype="multipart/form-data">
		<div class="imgcontainer">
				<a href="../index.php" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				<img src="img/Logo-Go-PhotoLab-Catalog.png" alt="Image de fichier" class="apiReponseIMG">
		  <br><br><h1>Gestionnaire de tirages photos</h1>
		  <h3>Integration d\'un fichier ".lab ou .web"</h3>
		  <input type="text" id="isDebug" name="isDebug" value="' . ($GLOBALS['isDebug']== true ?'Debug':'Prod') . '"/>
		</div>
		<div class="container">
			<div class="Select-bouton-wrapper">
				<button class="Selectbtn">Selectionne un fichier .lab ou .web</button>
				<input  type="file" accept=".lab, .web" class="upload" name="myfile" id="myfile">
				<br>
			</div>
			<input id="SelectUploadFiles"  class="SelectUploadFiles" disabled="disabled" value="aucun fichier">
			<button type="submit">Envoie dans le gestionnaire</button>
		</div>
	  </form>
	</div>
	<script>
	document.getElementById("myfile").onchange = function () {
	document.getElementById("SelectUploadFiles").value = this.value.substring(12);
};
	</script>
	';
	return $Formulaire;
}

function API_UIConfirmation($strAPI_fichierLAB, $Etat){
	$retourMSG = $GLOBALS['DebutMessageBox'];
	$retourMSG .= '	<div class="msgcontainer">';
	switch ($Etat) {
	case "1":
		$retourMSG .= "<br><h3>Les planches sont crées.<br><br><br></h3>";
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
	$CMDhttpLocal = $CMDhttpLocal . '&apiChgEtat='. urlencode(utf8_encode($strAPI_fichierLAB)) .'&apiEtat=' . $Etat;
	
	$retourMSG .= '<br><br>
		<a href="../index.php" class="KO" title="Valider et retour écran général des commandes">Annuler</a>
		<a href="' . $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">Valider</a>		
			<br><br><br>';

	$retourMSG .= '
		</div>	  
	</div>
</div>';	
	return $retourMSG;
	
}

function API_Photoshop($strAPI_fichierLAB){
	$retourMSG = 
	'<div id="apiReponse" class="modal">
		<div class="modal-content animate" >
			<div class="imgcontainer">
				<a href="CATPhotolab.php' . ArgumentURL() .'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				
			</div>';	

		
	$retourMSG .= '	<div class="msgcontainer">';
	$retourMSG .= "<br><br><br><h3>Pour créer les planches de la commande : </h3>"  ;
	$retourMSG .= "<h1>".utf8_encode(substr($strAPI_fichierLAB,0,-1))."</h1>";
	$retourMSG .= '<BR><BR><img src="img/LogoPSH.png" alt="Image de fichier" width="25%">';
	$retourMSG .= '<h3>Démarrez le plug-in PhotoLab pour Photoshop<br>(PLUGIN-PhotoLab.jsxbin) sur PC.</h3><br>';
	$retourMSG .= '<br><br>
		<a href="CATPhotolab.php' . ArgumentURL() .'" class="OK" title="Retour écran général des commandes">OK</a>	
			<br><br><br>';
	$retourMSG .= '
		</div>	  
	</div>
</div>';	
	return $retourMSG;
}

function API_EnregistrerCommandes() {// function API_PostFILELAB() upload de fichier par DROP (15 octobre)
	//$RechargerPage = true;
	$lesRecommandes = $_POST['lesRecommandes'];

	

	$target_file_seul = MAJRecommandes($_POST['leFichierOriginal'], $_POST['lesRecommandes']);


	$retourMSG = 
		'<!DOCTYPE html>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<html>
		<head>
		<link rel="stylesheet" type="text/css" href="'. strMini("css/Couleurs" . ($GLOBALS['isDebug']?"":"AMP") . ".css") . '">
		<link rel="stylesheet" type="text/css" href="'. strMini("css/API_PhotoLab.css") . '">
		</head>
		<body>
		<body onload="document.getElementById(\'apiReponse\').style.display=\'block\'">';
	
	$retourMSG .= '	<div class="msgcontainer">';

	$target_fichier = $GLOBALS['repCMDLABO'] . $target_file_seul;
	// if everything is ok, try to upload file
	$retourMSG .= '<h3>ENREGISTRER LA CMADES</h3>';			
	$retourMSG .= '<img src="img/Logo.png" alt="Image de fichier" width="25%">';	
	if (file_exists($target_fichier)){
		$CMDhttpLocal ='';
				
		$retourMSG .= '<h4>ENcvxvxcvcx LA CMADES</h4>';	
		$retourMSG .= ' <br>$target_fichier' . $target_fichier;	
			//$CMDhttpLocal = '?RECFileLab=' . urlencode(basename($_FILES['myfile']['name']));	
		
			
			$mesInfosFichier = new CINFOfichierLab($target_fichier); 
			//$CMDAvancement ='';
			
			//$Compilateur = '';				
			$NBPlanches = $mesInfosFichier->NbPlanches;
			$retourMSG .= '<br>->Fichier  ' . $mesInfosFichier->Fichier;
			$retourMSG .= '<br>->NbPlanches  ' . $mesInfosFichier->NbPlanches;
			//$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
			//echo "Apres move_uploaded_file";
			$CMDhttpLocal = '&CMDdate=' . substr($mesInfosFichier->Fichier, 0, 10);	
			$CMDhttpLocal .= '&CMDnbPlanches=' . $NBPlanches;
			$CMDhttpLocal .= '&BDDFileLab=' . urlencode(utf8_encode(substr(basename($mesInfosFichier->Fichier),0,-1) ));	 // Il faut enlever le "0" de .lab pour demander anregistrement !
			
			

			$retourMSG .= '<br><br>
				<a href="' . $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">OK</a>			
				<br><br>';				
			
	}
	else{
		$retourMSG = "APIPhotoProd : Erreur " . $target_fichier . " est manquant !";
	}	
	//echo "<br><br> Fermer la fenetre (faire un bouton!)";
	$retourMSG .= '
				</div>	  
			</div>
		</div>
    </body>
    </html>';
/**/	
	$retourMSG = 
	'<div id="apiReponse" class="modal">
		<div class="modal-content animate" >
			<div class="imgcontainer">
				<a href="'.$GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal).'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				<br>
			</div>' . $retourMSG;
	
	return $retourMSG;	
}

function API_DemandeNOMComamnde(){
	$retourMSG = $GLOBALS['DebutMessageBox'];
	$retourMSG .= '	<div class="msgcontainer">';
	if ($GLOBALS['isDebug']){$retourMSG = $retourMSG . "<br><h3> (en Debug)<br><br></h3>";}
	$retourMSG .=  "<h1>nom de votre commande</h1>";
	$retourMSG .=  "<h3>Ajustez le nom de votre commande pour le laboratoire ci dessous :</h3>";
	
		
	
	//Enregistrement du fichier avec son nouveau nom
	$leDossierTirage = $GLOBALS['FichierDossierRECOMMANDE'] ;
	$leFichierLab = $leDossierTirage . '.lab2';
	
	$retourMSG .= ' <br>$target_fichier' . $leFichierLab;	
	//$CMDhttpLocal = '?RECFileLab=' . urlencode(basename($_FILES['myfile']['name']));	

	
	$mesInfosFichier = new CINFOfichierLab($GLOBALS['repCMDLABO'] . $leFichierLab); 
	//$CMDAvancement ='';
	
	//$Compilateur = '';				
	$NBPlanches = $mesInfosFichier->NbPlanches;
	$retourMSG .= '<br>->Fichier  ' . $mesInfosFichier->Fichier;
	$retourMSG .= '<br>->NbPlanches  ' . $mesInfosFichier->NbPlanches;
	//$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
	//echo "Apres move_uploaded_file";
	$DateCommande = date('Y-m-d') ; 
	$CMDhttpLocal = '&CMDdate=' . $DateCommande;	
	$CMDhttpLocal .= '&CMDnbPlanches=' . $NBPlanches;
	$CMDhttpLocal .= '&BDDFileLab=RECOMMANDES' ;	 // Il faut enlever le "0" de .lab pour demander anregistrement !
	
	$ActionServeur = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal) ;
	echo $ActionServeur;
	//$ActionServeur = 'CATPhotolab.php' . ArgumentURL() ;
	
	$retourMSG .= '<form  action="' . $ActionServeur .'" method="post">';

	$retourMSG .= '<h4>'. $DateCommande .'-'; 

	$retourMSG .= '<input type="text" id="zoneTexteNomCommande" placeholder="Nom de votre commande..." name="apiNomCommande" required>
	</h4><br><br><br>
	<table>
		  <tr>
			  <td><a href="CATPhotolab.php' . ArgumentURL() .'" class="KO" title="Valider et retour écran général des commandes">Annuler</a></td>
			  <td><button type="submit" class="OK">OK</button></td>
		  </tr>
		</table>

    </div>

  </form>';


	$retourMSG .= '<br><br>';

	$retourMSG .= '
		</div>	  
	</div>
</div>';	
	return $retourMSG;
	
}

	
?>