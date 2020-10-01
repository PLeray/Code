<?php
include 'res/CConnexionLOCAL.php';
include 'res/CATConnexionAPI.php';
include 'res/CATFonctions.php';
include 'res/ConvertCSV.php';

$repCommandesLABO = "../CMDLABO/";
//$GLOBALS['repCommandesLABO']
//include 'res/BDD.php';
//AMP ?
$isAMP = false;
/*if (isset($_POST['isAMP']) ){
	$isAMP = ($_POST['isAMP'] == 'OK');
}
if (isset($_GET['isAMP'])) { // Test connexion l'API
	$isAMP = ($_GET['isAMP'] == 'OK');
}
//DEBUG ?



if (isset($_POST['isDebug']) ){
	$isDebug = ($_POST['isDebug'] == 'Debug');
}
if (isset($_GET['isDebug'])) { // Test connexion l'API
	$isDebug = ($_GET['isDebug'] == 'Debug');
}*/
//echo  "AMP ? : " . $isAMP . " Debug ? : " . $isDebug;
$isDebug = file_exists ('debug.txt');

$isAMP = file_exists ('amp.ini');
//if ($isAMP){$isDebug = false;}
$isDebug = file_exists ('debug.txt'); 


$maConnexionLOCAL = new CConnexionLOCAL($isAMP, $isDebug);
$maConnexionAPI = new CConnexionAPI($isAMP, $isDebug);

$EnteteHTML = 
    '<!DOCTYPE html>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <html>
    <head>
	<link rel="stylesheet" type="text/css" href="res/css/Couleurs' . (($isAMP == 'OK')?'AMP':'') .'.css">
    <link rel="stylesheet" type="text/css" href="res/css/API_PhotoLab.css">
    </head>
    <body>
	<body onload="document.getElementById(\'apiReponse\').style.display=\'block\'">';
	
$ScriptJS = 
    '<script>
		document.getElementById(\'myfile\').onchange = function () {
		document.getElementById(\'SelectUploadFiles\').value = this.value;
};

</script>';
	
$BotomHTML = 
    '</body>
    </html>';

$DebutMessageBox =
'<div id="apiReponse" class="modal">
	<div class="modal-content animate" >
		<div class="imgcontainer">
			<a href="'.$maConnexionLOCAL->AdresseLOCAL('').'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
			<br>
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
elseif (isset($_FILES['fileToDrop'])) {
	echo API_DropFILELAB();
} 

else {
	if(is_uploaded_file($_FILES["myfile"]["tmp_name"])) { // Recup le fichier lab uploadé
		echo $EnteteHTML . API_PostFILELAB();
	} 
	else echo 'Rien à Afficher pas de parametres ?! !';		
}

///////////////////////////////////////////////////////////////
///////////// Les Fonctions selon les cas ...  ////////////////
///////////////////////////////////////////////////////////////

function API_GetCMDLAB($strAPI_CMDLAB){
	if ($strAPI_CMDLAB == "TEST"){
		return "OK";
	}
	else {
		
		if (file_exists($GLOBALS['repCommandesLABO'] . $strAPI_CMDLAB)){
			$strCMDLabo = RecupPlanchesFichierLab($GLOBALS['repCommandesLABO'] . $strAPI_CMDLAB);
			return 'OK' . $strCMDLabo;
		}
		else {
			return " le fichier " .$GLOBALS['repCommandesLABO'] . $strAPI_CMDLAB . " est manquant !";
			return "APIPhotoProd : erreur 33";
		}		
	}
}

function API_GetFILELAB($strAPI_FILELAB){
	
	if (file_exists($GLOBALS['repCommandesLABO'] . $strAPI_FILELAB)){
		$strCMDLabo = RecupFichierLabTotal($GLOBALS['repCommandesLABO'] . $strAPI_FILELAB);
		return 'OK' . $strCMDLabo;
	}
	else {
		return " le fichier " .$GLOBALS['repCommandesLABO'] . $strAPI_FILELAB . " est manquant !";
		return "APIPhotoProd : erreur 55";
	}		
}

function API_PostFILELAB() {//upload de fichier
	$retourMSG = '';
	$retourMSG .= '	<div class="msgcontainer">';
	
	//$target_file_seul = utf8_decode(basename($_FILES['myfile']['name']));
	$target_file_seul = SUPRAccents(basename($_FILES['myfile']['name']));
	$target_file = $GLOBALS['repCommandesLABO'] . $target_file_seul;
	//echo $target_file;
	$target_file = $target_file . "0"; // 0 etat : uploadé / enregistré
	$uploadOk = 1;
	$extensionsAutorisee = array('.lab', '.web', '.csv');
	$extension = strrchr($_FILES['myfile']['name'], '.'); 
	//Début des vérifications de sécurité...
	if(!in_array($extension, $extensionsAutorisee))	{ //Si l'extension n'est pas dans le tableau
		$retourMSG .= "APIPhotoProd : Vous devez sélectionner un fichier de type .lab, .web ou .csv ...";
		$uploadOk = 0;			 
	}
	// Check file size
	if ($_FILES["myfile"]["size"] > 500000) {
		$retourMSG .= "APIPhotoProd : Le fichier est trop gros, vérifiez...";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$retourMSG .= "APIPhotoProd : Ce fichier non valide.";
	} 
	else {// if everything is ok, try to upload file
		if (file_exists($_FILES["myfile"]["tmp_name"])){
			if ($extension == '.csv') {
				//Verif si fichier de commande web iso ou groupe ou pas bon !
				if (ConvertirCMDcsvEnlab($TabCSV, $_FILES["myfile"]["tmp_name"], $target_file) != '') {					
					$retourMSG .= "<h3>Pour créer les planches de la commande : </h3>"  ;
					$retourMSG .= "<h2>" .	utf8_encode(substr($target_file ,11,-5)) . "</h2>";					
					$uploadOk = 2; // Flag test si OK
					$target_file_seul = substr($target_file, 8, -1); // Pour etre dans la même forme que . lab pas lab0
				}				
			} 
			else {
				if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_file)) {
					$retourMSG .= "<h3>Pour créer les planches de la commande : </h3>"  ;
					$retourMSG .= "<h2>" .	substr(basename($_FILES['myfile']['name']) ,0,-4) . " </h2>";
					$uploadOk = 2; // Flag test si OK
				}					
				else {
					$retourMSG = "APIPhotoLab : Probleme d'enregistrement de :" . $target_file;
				}				
			}
			if ($uploadOk == 2) {

				$retourMSG .= '<img src="res/img/LogoPSH.png" alt="Image de fichier" width="25%">';
				$retourMSG .= '<h3>Démarrer sur PC le plug-in PhotoLab pour Photoshop<br>(PhotoLab-AUTO.jsxbin).</h3>';			
				
				//$CMDhttpLocal = '?RECFileLab=' . urlencode(basename($_FILES['myfile']['name']));					
				$CMDAvancement ='';
				$CMDhttpLocal ='';
				$Compilateur = '';				
				$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
				//echo "Apres move_uploaded_file";
				$CMDhttpLocal = '&CMDdate=' . substr($target_file_seul, 0, 10);	
				$CMDhttpLocal .= '&CMDnbPlanches=' . $NBPlanches;
				$CMDhttpLocal .= '&BDDFileLab=' . urlencode(utf8_encode(basename($target_file_seul)));	
				
				$retourMSG .= '<br>
					<a href="' . $GLOBALS['maConnexionAPI']->TalkServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">OK</a>			
					<br><br>';				
			}		
		}
		else{
			$retourMSG = "APIPhotoProd : Erreur " . $target_file . " est manquant !";
		}
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
				<a href="'.$GLOBALS['maConnexionAPI']->TalkServeur($CMDhttpLocal).'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				<br>
			</div>' . $retourMSG;
	
	return $retourMSG;	
	//header('Location: ' .$maLocation); 
}
/*
function API_DropFILELAB2() {//upload de fichier

	if (isset($_FILES['fileToDrop'])) {
		$sFileName = $_FILES['fileToDrop']['name'];
		$sFileSize = $_FILES['fileToDrop']['size'];
	 
		move_uploaded_file($_FILES['fileToDrop']['tmp_name'], 'CMDLABO/' . $_FILES['fileToDrop']['name']."0");	

		echo '
		<div class="dropAreaRESULT">
			<p>La commande : ' . $sFileName .' a été correctement transférée.</p>
			<p>Taille : '.$sFileSize.'</p>
		</div>';
	} 
	else {
		echo '<div class="dropAreaRESULT">Une erreur s\'est produite</div>';
	}

}

function API_DropFILELAB() {//upload de fichier
	$sFileName = $_FILES['fileToDrop']['name'];
	$sFileSize = bytesToSize1024($_FILES['fileToDrop']['size']);
	$target_file = '../CMDLABO/' . $_FILES['fileToDrop']['name']."FF";
	move_uploaded_file($_FILES['fileToDrop']['tmp_name'], $target_file);	
	
	$NBPlanches = NBPlanchesFichierLab($target_file);
	//echo "Apres move_uploaded_file";
	$CMDhttpLocal = '&CMDdate=' . substr($sFileName, 0, 10);	
	$CMDhttpLocal = $CMDhttpLocal . '&CMDnbPlanches=' . $NBPlanches;
	$CMDhttpLocal = $CMDhttpLocal . '&BDDFileLab=' . urlencode(basename($sFileName));	
	
	echo '
	<div class="dropAreaRESULT">
		<p>La commande : ' . $sFileName .' a été correctement transférée. </p>
		<p> => Taille : '.$sFileSize.'</p>';

	//echo $CMDhttpLocal ;
	$maLocation = $GLOBALS['maConnexionAPI']->TalkServeur($CMDhttpLocal);
	echo $maLocation;
	echo '</div>';
	header('Location: ' .$maLocation); 
}

*/

function bytesToSize1024($bytes) {
	$unit = array('B','KB','MB');
	return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 1).' '.$unit[$i];
}

function API_UISelectFILELAB($strAPI_SelectFILELAB){
	$Formulaire =
	'<!-- UPLOAD FILE -->
	<div id="apiReponse" class="modal">
	  <form class="modal-content animate" action="www_photolab.php" method="post" enctype="multipart/form-data">
		<div class="imgcontainer">
				<a href="'.$GLOBALS['maConnexionLOCAL']->AdresseLOCAL('').'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				<img src="res/img/RecLabFile.png" alt="Image de fichier" class="apiReponseIMG">
		  <br><br><h1>Gestionnaire de tirages photos</h1>
		  <h3>Integration d\'un fichier ".lab ou .webZZZ"</h3>
		  <input type="text" id="isAMP" name="isAMP" value="' . ($GLOBALS['isAMP']=='OK' ?'OK':'Debug') . '"/>
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
	$retourMSG = $retourMSG . '	<div class="msgcontainer">';
	switch ($Etat) {
	case "1":
		$retourMSG = $retourMSG . "<br><h3>Les planches sont crées.<br><br><br></h3>";
		break;		
	case "2":
		$retourMSG = $retourMSG . "<br><h3>Les planches ont été envoyés au laboratoire ?<br><br><br></h3>";
		break;
	case "3":
		$retourMSG = $retourMSG . "<br><h3>Les photos sont tirées au laboratoire ?<br><br><br></h3>";
		break;		
	case "4":
		$retourMSG = $retourMSG . "<br><h3>Les photos sont mise en carton. Fin<br><br><br></h3>";
		break;	
	}

	$retourMSG = $retourMSG . "<br><h1>".utf8_encode(substr($strAPI_fichierLAB,0,-5))."</h1>";
	
	
	if ($GLOBALS['isDebug']){$retourMSG = $retourMSG . "<br><h3>".$Etat."<br><br></h3>";}
	$retourMSG = $retourMSG . "<br><h3>Si oui valider !</h3><br>";

	$CMDhttpLocal = '?isAMP=' . ($GLOBALS['isAMP'] ? 'OK' : 'KO') . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod');
	$CMDhttpLocal = $CMDhttpLocal . '&apiChgEtat='. urlencode(utf8_encode($strAPI_fichierLAB)) .'&apiEtat=' . $Etat;
	
	//A suprimer
	//$retourMSG = $retourMSG . $GLOBALS['maConnexionLOCAL']->TalkServeur($CMDhttpLocal);
	//Fin supre
	
	$retourMSG = $retourMSG .'<br><br>
		<a href="' . $GLOBALS['maConnexionLOCAL']->AdresseLOCAL('') . '" class="KO" title="Valider et retour écran général des commandes">Annuler</a>
		<a href="' . $GLOBALS['maConnexionLOCAL']->TalkServeur($CMDhttpLocal) . '" class="OK" title="Valider et retour écran général des commandes">Valider</a>		
			<br><br><br>';

	$retourMSG = $retourMSG . '
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
				<a href="'. $GLOBALS['maConnexionLOCAL']->AdresseLOCAL('').'" class="close" title="Annuler et retour écran général des commandes">&times;</a>
				<BR><BR><img src="res/img/ImgPhotoLabPS.jpg" alt="Image de fichier" width="90%" class="apiReponseIMG">
			</div>';	
		
	$retourMSG = $retourMSG . '	<div class="msgcontainer">';
	$retourMSG = $retourMSG . "<br><h3>Ouvrir PhotoLab pour Photoshop</h3><br><h1>"  ;
	$retourMSG = $retourMSG . "<h1>".utf8_encode(substr($strAPI_fichierLAB,0,-5))."</h1>";
	$retourMSG = $retourMSG . "<br><h3>pour créer les planches de cette commande.</h3><br>";
	$retourMSG = $retourMSG .'<br><br>
		<a href="' . $GLOBALS['maConnexionLOCAL']->AdresseLOCAL('') . '" class="OK" title="Retour écran général des commandes">OK</a>	
			<br><br><br>';
	$retourMSG = $retourMSG . '
		</div>	  
	</div>
</div>';	
	return $retourMSG;
}
	
?>