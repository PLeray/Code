<!DOCTYPE html>
<?php
	setlocale(LC_TIME, 'french');
	include_once 'APIConnexion.php';
	include_once 'CMDLire.php';

	$myfileName = (isset($_GET['fichierLAB'])) ? $_GET['fichierLAB'] :'';
	
	$codeMembre = 0;
	if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
	$isDebug = false;
	if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}
	if ($isDebug){header("Cache-Control: no-cache, must-revalidate");}
	
	


	
	$DefautNbCMDAffiche = 15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}
?>

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>

<?php 
if($isDebug){
	header("Cache-Control: no-cache, must-revalidate");
}


?>
    <title id="PHOTOLAB"><?php echo substr($myfileName,0, -5) ?> : Préparation de commandes</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/CMD-View.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>

	
<?php


	$monGroupeCmdes = new CGroupeCmdes($GLOBALS['repCMDLABO'].$myfileName);
	
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';
	

	$EcoleEnCours = new CEcole("___",'2020-07-07');
	
	//$versionFichierLab = VersionFichierLab($tabFICHIERLabo);

	$etatFichierLab = AfficheEtatFichierLab($myfileName);
	
	
$isRECOmmandes = (stripos($myfileName, $GLOBALS['FichierDossierRECOMMANDE']) !== false);
	
	
?>

</head>

<body onload="EffacerChargement()">
<div id="MSGChargement" onclick="EffacerChargement()"> 
	<div class="cs-loader">
	
	  <div class="cs-loader-inner">
	  <H5>Chargement de la commande <?php echo $myfileName;?></H5>
	  <br>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<br>
		<br>
		<H5>Cliquez pour voir :  <?php echo $myfileName;?></H5>
	  </div>
	</div>
</div> 
<div id="site"">
   <!-- Tout le site ici -->

	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">Remonter</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes"><img src="img/Logo-Retour.png" alt="Image de fichier"></a>
		</div>
		
		<div class="affichageNBPage">
			<p> Nombre de commandes par page</p>	
				<a href="<?php echo LienAffichePlusMoins('-','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">-</a><B> <?php if ($NbCMDAffiche<10000){echo $NbCMDAffiche;} ?> </B><a href="<?php echo LienAffichePlusMoins('+','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>" class="moinsplus">+</a>
				<?php echo '<p>'. count($monGroupeCmdes->tabCMDLabo) . ' commandes au total</p>';?>
		</div> 	
		
		<div class="titreFichier">	
			<?php 
				
				echo pathinfo(utf8_encode($myfileName))['filename']; ?>
			<?php //echo urldecode(utf8_encode($myfileName)) .
			//'    ' . $etatFichierLab ;?>
			<?php echo  '<p>' . $etatFichierLab . '</p>';?>
		</div>

		
		<a href="<?php echo LienRecherche('&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>"><span id="loupe" style="font-size:30px;cursor:pointer"><p><img src="img/search-more.png"></p></span></a>
			
		<!--
		<span id="loupe" style="font-size:30px;cursor:pointer" onclick="openNav()"><p><?php // echo count($monGroupeCmdes->tabCMDLabo) . ' commandes au total';?><img src="img/search-more.png"></p></span>
		-->		
	</div>

	  <div id="main">

	<!-- -->
		<div id="zoneListePageCMD">
			<br><br>
			<div class="zonePagesCMD">
				<div class="page_navigation"></div>		
				<div class="items">					
					<?php 	echo $monGroupeCmdes->Affiche($NbCMDAffiche); ?>	
					
				</div>
			</div>

	  </div>

		<div class="footer">
		  <p class="mention">	<?php echo VersionPhotoLab();?> </p>
		</div>

	</div>
 
</div>



<script type="text/javascript" src="<?php Mini('js/CMD-View.js');?>"></script>
<script type="text/javascript" src="<?php Mini('js/purePajinate.js');?>"></script>

<script>
	function intersect(a, b) {
	  var setB = new Set(b);
	  var setA = new Set(a);
	  return [...new Set(a)].filter(x => setB.has(x));
	}
	initPagination();
	AfficheRechercheCMD(<?php echo $isRECOmmandes;?>);
	
</script>

</body>
</html>
