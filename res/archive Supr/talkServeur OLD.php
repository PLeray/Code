<?php
include 'CConnexionLOCAL.php';
//include 'BDD.php';
$codeMembre = false;
if (isset($_POST['codeMembre']) ){
	$codeMembre = ($_POST['codeMembre'] == 'OK');
}
if (isset($_GET['codeMembre'])) { // Test connexion l'API
	$codeMembre = ($_GET['codeMembre'] == 'OK');
}
//DEBUG ?
$isDebug = false;
if (isset($_POST['isDebug']) ){
	$isDebug = ($_POST['isDebug'] == 'Debug');
}
if (isset($_GET['isDebug'])) { // Test connexion l'API
	$isDebug = ($_GET['isDebug'] == 'Debug');
}
// Cas du changement d'etat de fichier
if (isset($_GET['apiChgEtat']) && isset($_GET['apiEtat'])) { 
	ChangeEtat($_GET['apiChgEtat'], $_GET['apiEtat']);
} 
//////////////////////////
$strGET = '<BR>';
$premier = true;
$CMDhttpLocal = '';
$_GET = array_map('htmlentities', $_GET); // on applique la fonction htmlentities() sur chaque donnée du tableau $_GET
foreach($_GET as $i =>$var) { // pour chaque valeur du tableau $_GET on crée une variable $var
	$strGET = $strGET  .'<BR>' . $i . '=>' .$var;
	if($premier) {
		$strGET = $strGET . '(Le Premier)';
		$CMDhttpLocal = $CMDhttpLocal .'?' .  $i . '=' . urlencode(($var));
		$premier = false;
	}
	else{
		$CMDhttpLocal = $CMDhttpLocal .'&' .  $i . '=' . urlencode(($var));
	}
}
$maConnexionLOCAL = new CConnexionLOCAL($codeMembre);
echo '	
	<html>
	<head>
	<title>SERVEUR</title>
	<meta http-equiv="refresh" content="0; URL=' . $maConnexionLOCAL->AdresseLOCAL($CMDhttpLocal) .'"> 
	</head>
	<body>'. 
		'LE SERVEUR appele le CLIENT LOCAL... 
		<BR> <BR> url qui est appellee    :  <BR>' . $maConnexionLOCAL->AdresseLOCAL($CMDhttpLocal) .		
		'<BR> <BR> PATIENTER ... 
	</body>
	</html>	';
function ChangeEtat($strFILELAB, $Etat){
	$repertoireCMD = "CMDEnregistrees/";
	$ExtensionRAcine = '.' . substr(strrchr($strFILELAB, '.'),1,3);
	//echo '<script>alert("Attente sur le serveur ... '.$strFILELAB .' ChangeEtat '.$Etat.'");</script>';
	if (file_exists($repertoireCMD . $strFILELAB)){
		rename($repertoireCMD . $strFILELAB, $repertoireCMD . substr($strFILELAB, 0, strpos($strFILELAB, $ExtensionRAcine)) . $ExtensionRAcine . $Etat);
		return 'OK';		
	}
	elseif (file_exists($repertoireCMD . substr($strFILELAB, 0, strpos($strFILELAB, $ExtensionRAcine)) . $ExtensionRAcine . '0')){
		copy($repertoireCMD . substr($strFILELAB, 0, strpos($strFILELAB, $ExtensionRAcine)) . $ExtensionRAcine . '0', $repertoireCMD . substr($strFILELAB, 0, strpos($strFILELAB, $ExtensionRAcine)) . $ExtensionRAcine . $Etat);
		return 'OK';
	}
	else {
		return "APIPhotoProd : erreur 44";
	}
}
?>