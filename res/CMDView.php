<!DOCTYPE html>
<?php
	include 'APIConnexion.php';
	include 'CMDLire.php';

	$myfileName = (isset($_GET['fichierLAB'])) ? $_GET['fichierLAB'] :'';
	
	$codeMembre = 0;
	if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
	$isDebug = false;
	if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}
	if ($isDebug){header("Cache-Control: no-cache, must-revalidate");	}

	$DefautNbCMDAffiche = 15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}
?>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
    <title id="GO-PHOTOLAB">PhotoLab : Cartonnage</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
    <link rel="stylesheet" type="text/css" href="css/CMD-View.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<script type="text/javascript" src="js/CMD-View.js"></script>
<?php
	$repertoireTirages = $GLOBALS['repTIRAGES'];
	$repertoireCMD = $GLOBALS['repCMDLABO'];
	$repertoireMiniatures = $GLOBALS['repMINIATURES'];

	$tabFICHIERLabo = LireFichierLab($repertoireCMD.$myfileName);

	$tabCMDLabo = InitTabCMDLabo($tabFICHIERLabo);
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';
	
	//$curEcole = '';
	$curEcole = new CEcole("___");

	$maCMDHtml = AffichageCMD($tabFICHIERLabo, $numeroCMD - 1, $curEcole, $NbCMDAffiche);
	
	$versionFichierLab = VersionFichierLab($tabFICHIERLabo);

	$etatFichierLab = AfficheEtatFichierLab(substr(strrchr($myfileName, '.'),4));
?>
</head>
<body>
	<button onclick="topFunction()" id="myBtn" title="Revenir en haut de la page">Remonter</button>
	<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes"><img src="img/retourPhotoLabCMD.png" alt="Image de fichier"></a></div>
	<div class="titreFichier">	
		<?php echo substr($myfileName,0,10) .
		'    ' . pathinfo(utf8_encode(substr($myfileName,11)))['filename'] . '  :  ' .$etatFichierLab ;?>
		<?php //echo urldecode(utf8_encode($myfileName)) .
		//'    ' . $etatFichierLab ;?>
	</div>


	<div class="recherche">	
		<p><STRONG><?php echo count($tabCMDLabo) . ' commandes au total';?></STRONG></p>
		<input type="text" placeholder="Recherche commandes..." id="mySearch" onclick="myFunction()" onkeyup="filterFunction()">
	</div>
	<div id="myDropdown" class="dropdown-content" onmouseout="myFunction()"	>
	
	<?php 
	//echo 'kdsjghskdhgf';
	echo LienMEGA($tabCMDLabo, $numeroCMD);
		//<a href="#about">About</a>
	?>
  
	</div>
	<?php 
		echo PaginatorCMD($tabCMDLabo, 1, $numeroCMD, 2, 2 + $NbCMDAffiche);//, 1, 1);
	?>
			
	<a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes" class="close">&times;</a>


	
	
<?php
	//echo PaginatorCMD($tabCMDLabo, 1, $numeroCMD, 2, 2 + $NbCMDAffiche, 1, 1);
	echo $maCMDHtml;
?>		

	
	<div class="affichageNBPage">
	    <p></p>
		<p> .</p>
		<p> Nombre de commandes affichées :</p><p> <a href="<?php echo LienAffichePlusMoins('Toutes','&fichierLAB='.urlencode($myfileName).'&numeroCMD=1' );?>" class="moinsplus">Toutes</a><big><B>
		
		<a href="<?php echo LienAffichePlusMoins('-','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">-</a><big><B> <?php if ($NbCMDAffiche<10000){echo $NbCMDAffiche;} ?> </B></big><a href="<?php echo LienAffichePlusMoins('+','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">+</a></p>
	</div> 

	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</body>
</html>
