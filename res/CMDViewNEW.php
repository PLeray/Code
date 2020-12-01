<!DOCTYPE html>
<?php
	include 'APIConnexion.php';
	include 'CMDLireNEW.php';

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
    <title id="GO-PHOTOLAB">PhotoLab : Préparation de commandes</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
    <link rel="stylesheet" type="text/css" href="css/CMD-ViewNEW.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>

	
<?php
	$repertoireTirages = $GLOBALS['repTIRAGES'];
	$repertoireCMD = $GLOBALS['repCMDLABO'];
	$repertoireMiniatures = $GLOBALS['repMINIATURES'];

	$monGroupeCmdes = new CGroupeCmdes($repertoireCMD.$myfileName);
	
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';
	

	$EcoleEnCours = new CEcole("___");
	
	//$versionFichierLab = VersionFichierLab($tabFICHIERLabo);

	$etatFichierLab = AfficheEtatFichierLab(substr(strrchr($myfileName, '.'),4));
?>
</head>
<body>
	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">Remonter</button>
	<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes"><img src="img/retourPhotoLabCMD.png" alt="Image de fichier"></a>
	</div>
	<div class="titreFichier">	
		<?php echo substr($myfileName,0,10) .
		'    ' . pathinfo(utf8_encode(substr($myfileName,11)))['filename'] . '  :  ' .$etatFichierLab ;?>
		<?php //echo urldecode(utf8_encode($myfileName)) .
		//'    ' . $etatFichierLab ;?>
	</div>
	
	<div class="affichageNBPage">
	
		<p> </p>
		
		<a href="<?php echo LienAffichePlusMoins('-','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">-</a><B> <?php if ($NbCMDAffiche<10000){echo $NbCMDAffiche;} ?> </B><a href="<?php echo LienAffichePlusMoins('+','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">+</a>
		<p> Nombre de commandes affichées :</p><p> 
	</div> 
	<a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes" class="close">&times;</a>
<!-- 
	
	<div class="recherche">	
		<p><STRONG><?php echo count($monGroupeCmdes->tabCMDLabo) . ' commandes au total';?></STRONG></p>
		<input type="text" placeholder="Recherche commandes..." id="mySearch" onclick="myFunction2()" onkeyup="filterFunction()" title="indiquez des termes d'une commande/n ou son numéro">
	</div>   
-->	



	<div id="mySidenav" class="sidenav">
	  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	  
	<h3><?php echo count($monGroupeCmdes->tabCMDLabo);?> Commandes</h3>
		<input type="text" id="zoneRecherche" onkeyup="filterFunction()" placeholder="Rechercher .." title="Type in a category">  
	  
		<ul id="listeRechercheCMD">
			<?php echo $monGroupeCmdes->AfficheMenuCMD();?>	
		</ul>
	</div>

<div id="main">
  <span id="loupe" style="font-size:30px;cursor:pointer" onclick="openNav()"><p>Rechercher...<img src="img/searchicon.png"></p></span>
	<div id="zoneRechercheCMD">	
	<br><br><br><br><br>
		<?php 	echo $monGroupeCmdes->Affiche(false); ?>	
	</div>	<!-- -->
	<div id="zoneListePageCMD">
		<div class="zonePagesCMD">
			<div class="page_navigation"></div>		
			<div class="items">		
				<?php 	echo $monGroupeCmdes->Affiche(true); ?>	
			 	
			</div>
			<div class="laCMDTotale">		
			</div>
			<!--<div class="laCMDTotale">		
			</div>		-->	
		</div>
	</div>

</div>	
	
	<!-- 			-->	
	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
	
	


</body>

<script type="text/javascript" src="js/CMD-ViewNEW.js"></script>
<!-- <script src="js/purePajinate.js"></script>
-->	
<script src="js/purePajinate.min.js"></script>

</html>
