<!DOCTYPE html>
<?php
	setlocale(LC_TIME, 'french');
	include_once 'APIConnexion.php';
	
	$codeMembre = 0;
	if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
	$isDebug = false;
	if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}
	if ($isDebug){header("Cache-Control: no-cache, must-revalidate");}	
?>	
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="PHOTOLAB">Sources enregistrées</title>

	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDAffichePlanche.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
</head>

<body>

<div class="bg">
	<img src="<?php echo $_GET['urlImage']; ?>" alt="Image de fichier">
	<a href="javascript:history.go(-1)" title="Fermer" class="close">&times;</a>
</div>
</body>
</html>