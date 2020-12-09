<!DOCTYPE html>
<?php
	setlocale(LC_TIME, 'french');
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
	

	$EcoleEnCours = new CEcole("___",'2020-07-07');
	
	//$versionFichierLab = VersionFichierLab($tabFICHIERLabo);

	$etatFichierLab = AfficheEtatFichierLab($myfileName);
?>

</head>

<body onload="chargement()">
 <!-- 
<div id="chargement" style="width:150px;height:50px;position:absolute;top:0;left:0;color:red;font-weight:bold;font-size:14px;background:white;">
   Chargement ...
</div>-->
<div id="chargement"> 
	<div class="cs-loader">
	  <div class="cs-loader-inner">
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
	  </div>
	</div>
</div> 
<div id="site" style="visibility:hidden;">
   <!-- Tout le site ici -->

	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">Remonter</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes"><img src="img/Logo-mini.png" alt="Image de fichier"></a>
		</div>
		
		<div class="affichageNBPage">
			<p> Nombre de commandes par page</p>	
				<a href="<?php echo LienAffichePlusMoins('-','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">-</a><B> <?php if ($NbCMDAffiche<10000){echo $NbCMDAffiche;} ?> </B><a href="<?php echo LienAffichePlusMoins('+','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">+</a>
		</div> 	
		
		<div class="titreFichier">	
			<?php 
				echo  '<p>' . $etatFichierLab . '</p>';
				echo pathinfo(utf8_encode($myfileName))['filename']; ?>
			<?php //echo urldecode(utf8_encode($myfileName)) .
			//'    ' . $etatFichierLab ;?>
		</div>
		

		<span id="loupe" style="font-size:30px;cursor:pointer" onclick="openNav()"><p><?php echo count($monGroupeCmdes->tabCMDLabo) . ' commandes au total';?><img src="img/search-more.png"></p></span>	
	</div>

	  <div id="main">
		<div id="mySidenav" class="sidenav">
		  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
		  <p> <STRONG>Recherche de commandes par critère</STRONG></p>
		  <p>Indiquez un produit, un nom de fichier (visible au dos de la photo), une classe, un nom de client, un numéro de commande, ...</p>
		  <p>Commencer à taper... par exemple pour savoir dans quelle commande se trouve la planche 'P0006.-CADR-CM2(...).jpg', tapez juste 'P0006'</p>
		  <p>> tapez juste 'P0006'</p>
		  

		<h3>il y a <?php echo count($monGroupeCmdes->tabCMDLabo);?> Commandes</h3>
			<input type="text" id="zoneRecherche" onkeyup="filterFunction()" placeholder="Rechercher .." title="Taper le début d'un critère...">  
		  
			<ul id="listeRechercheCMD">
				<?php echo $monGroupeCmdes->AfficheMenuCMD();?>	
			</ul>
		</div>
	  
		<div id="zoneRechercheCMD">	
		<br><br>
			<?php 	echo $monGroupeCmdes->Affiche(false); ?>	
		</div>	<!-- -->
		<div id="zoneListePageCMD">
			<br><br>
			<div class="zonePagesCMD">
				<div class="page_navigation"></div>		
				<div class="items">					
					<?php 	echo $monGroupeCmdes->Affiche(true); ?>	
					
				</div>
			</div>

	  </div>

		<div class="footer">
		  <p class="mention">	<?php echo VersionPhotoLab();?> </p>
		</div>

	</div>
 
</div>



<script type="text/javascript" src="js/CMD-ViewNEW.js"></script>
<!-- <script src="js/purePajinate.js"></script>
-->	
<script src="js/purePajinate.min.js"></script>
</body>
</html>
