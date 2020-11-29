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

	$DefautNbCMDAffiche = 3;//15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}
?>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
    <title id="GO-PHOTOLAB">PhotoLab : Cartonnage</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
    <link rel="stylesheet" type="text/css" href="css/CMD-ViewNEW.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<script type="text/javascript" src="js/CMD-ViewNEW.js"></script>
	
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
	
	<div class="affichageNBPage">
	    <p></p>
		<p> .</p>
		<p> Nombre de commandes affichées :</p><p> <a href="<?php echo LienAffichePlusMoins('Toutes','&fichierLAB='.urlencode($myfileName).'&numeroCMD=1' );?>" class="moinsplus">Toutes</a><big><B>
		
		<a href="<?php echo LienAffichePlusMoins('-','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">-</a><big><B> <?php if ($NbCMDAffiche<10000){echo $NbCMDAffiche;} ?> </B></big><a href="<?php echo LienAffichePlusMoins('+','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">+</a></p>
	</div> 
	<a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes" class="close">&times;</a>
	<div class="recherche">	
		<p><STRONG><?php echo count($tabCMDLabo) . ' commandes au total';?></STRONG></p>
		<input type="text" placeholder="Recherche commandes..." id="mySearch" onclick="myFunction()" onkeyup="filterFunction()" title="indiquez des termes d'une commande/n ou son numéro">
	</div>
	<div id="myDropdown" class="dropdown-content" onmouseout="myFunction()"	>

	</div>


<?php
	//echo PaginatorCMD($tabCMDLabo, 1, $numeroCMD, 2, 2 + $NbCMDAffiche, 1, 1);
	echo 'AFFICHAGE NEW';
	
	$monGroupeCmdes = new CGroupeCmdes($repertoireCMD.$myfileName);
	echo $monGroupeCmdes->Affiche($NbCMDAffiche);
	echo 'AFFICHAGE FIN NEW';


?>	



	<div class="zonePagesCMD">
	<div class="page_navigation"></div>
		<div class="items">
			
			<div class="pageCMD">
			Slide 1<br>
			Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>
			
			</div>
		
			<div class="pageCMD">
			Slide 2<br>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1<br>Slide 1
			
			</div>
			
			<div class="pageCMD">Slide 3</div>
	
	
	
	

		</div>
		<div class="page_navigation"></div>
	</div>
<?php
	//echo PaginatorCMD($tabCMDLabo, 1, $numeroCMD, 2, 2 + $NbCMDAffiche, 1, 1);
	//echo $maNewCMDHtml;
	
	$monGroupeCmdes = new CGroupeCmdes($repertoireCMD.$myfileName);
	echo $monGroupeCmdes->Affichage($NbCMDAffiche);
	


?>	











		
	
	
	
<?php 
	//echo 'kdsjghskdhgf';
	echo LienMEGA($tabCMDLabo, $numeroCMD);
	//<a href="#about">About</a>
?>


<?php 
	echo PaginatorCMD($tabCMDLabo, 1, $numeroCMD, 2, 2 + $NbCMDAffiche);//, 1, 1);
	echo $maCMDHtml;
?>		

	


	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</body>
<script src="js/purePajinate.min.js"></script>
</html>
