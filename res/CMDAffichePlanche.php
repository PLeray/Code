<!DOCTYPE html>
<?php
	setlocale(LC_TIME, 'french');
	include_once 'APIConnexion.php';
	
	$codeMembre = 0;
	if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
	$isDebug = false;
	if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}
	if ($isDebug){header("Cache-Control: no-cache, must-revalidate");}	


	$codeSource = 'qsd';
	if (isset($_GET['codeSource'])) { // Test connexion l'API
		$codeSource = $_GET['codeSource'];
	}
	$anneeSource = 'qsd';
	if (isset($_GET['anneeSource'])) { // Test connexion l'API
		$anneeSource = $_GET['anneeSource'];
	}

	$lesPhotoSelection = '';
	if (isset($_POST['lesPhotoSelection']) ){
		$lesPhotoSelection = $_POST['lesPhotoSelection'];
		if ($isDebug){echo 'VOILA LES lesPhotoSelection  : ' . $lesPhotoSelection;}	
	}
	$lesCmdesLibres = '';
	if (isset($_POST['lesCmdesLibres']) ){
		$lesCmdesLibres = $_POST['lesCmdesLibres'];
		if ($isDebug){echo 'VOILA LES RECOMMANDES   : ' . $lesCmdesLibres;}	
	}
	$lesFichiersBoutique = '';
	if (isset($_POST['lesFichiersBoutique']) ){
		$lesFichiersBoutique = $_POST['lesFichiersBoutique'];
		if ($isDebug){echo 'VOILA LES lesFichiersBoutique : ' . $lesFichiersBoutique;}	
	}


?>	
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="PHOTOLAB">Sources enregistrées</title>

	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDAffichePlanche.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CouleursDEBUG.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
</head>

<body>

<div class="bg">
<img src="<?php echo $_GET['urlImage']; ?>" alt="Image de fichier">




<form name="RetourSourceEcole" method="post" action="CMDAfficheSource.php<?php echo ArgumentURL('&codeSource=' . urlencode($codeSource)
												. '&anneeSource=' . urlencode($anneeSource)
												.'&MAJ=true'
												.'#' . substr($_GET['urlImage'], 1+strripos($_GET['urlImage'], '/'))
												) ?>" enctype="multipart/form-data">	
	<input type="hidden" name="lesPhotoSelection" id="lesPhotoSelection" value="<?php echo $lesPhotoSelection; ?> " /> 
	<input type="hidden" name="lesCmdesLibres" id="lesCmdesLibres" value="<?php echo $lesCmdesLibres; ?> " /> 
	<input type="hidden" name="lesFichiersBoutique" id="lesFichiersBoutique" value="<?php echo $lesFichiersBoutique; ?> " />		

	
	<button type="submit" title="Fermer" class="close">&times;</a>

	</button>



</form>


<!--  	<a href="javascript:history.go(-1)" title="Fermer" class="close">&times;</a>-->





</div>


</form>
</body>
</html>