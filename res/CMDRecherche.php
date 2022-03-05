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

	/*
	$DefautNbCMDAffiche = 15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}
	*/
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
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDRecherche.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	
<?php
	
				//echo '<br>sdfsdfsdfsdfsdffsd<br>'. $GLOBALS['repTIRAGES'] ;
			//echo $GLOBALS['repMINIATURES'] . '<br><br>';

	$monGroupeCmdes = new CGroupeCmdes($GLOBALS['repCMDLABO'].$myfileName);
	
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';
	

	$EcoleEnCours = new CEcole("____",'2020-07-07');
	
	//$versionFichierLab = VersionFichierLab($tabFICHIERLabo);

	$etatFichierLab = AfficheEtatFichierLab($myfileName);
	
	
$isRECOmmandes = (stripos($myfileName, $GLOBALS['FichierDossierRECOMMANDE']) !== false);
	

?>

</head>

<body onload="EffacerChargement()">
 <!-- 
<div id="chargement" style="width:150px;height:50px;position:absolute;top:0;left:0;color:red;font-weight:bold;font-size:14px;background:white;">
   Chargement ...
</div>-->
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

	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">↑ Remonter ↑</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes"><img src="img/Logo-Retour.png" alt="Image de fichier"></a>
		</div>
		

		
		<div class="titreFichier">	
			<?php 
				
				echo pathinfo(utf8_encode($myfileName))['filename'];
				//echo $isRECOmmandes?'true':'false'. " sgsfdgdfg" ; 				
					?>
			<?php //echo urldecode(utf8_encode($myfileName)) .
			//'    ' . $etatFichierLab ;?>
			
		</div>
		<?php echo  '<p>' . $etatFichierLab . '</p>';?>

		<span id="loupe" style="font-size:30px;cursor:pointer" onclick="openNav()"><p><?php echo count($monGroupeCmdes->tabCMDLabo) . ' commandes au total';?><img src="img/search-more.png"></p></span>	
	</div>

	  
		<div id="mySidenav" class="sidenav">
		<?php 
			if (!$isRECOmmandes){
				echo '
				<a href="'.RetourEcranFichier($myfileName).'" class="closebtn">&times;</a>
				<H1>Passage de recommandes</H1>
				<br>
			<div id="myRecommandes" class="infoRecommandes">
				
				<a href=javascript:void(0); id ="CaseVoirCommandes" onclick=VoirPhotoSelection() class="caseCheckVide" > ✓ </a>  Afficher sélection des recommandes	

				<form name="FormEnvoieRecos" method="post" action="'. EnregistrerFichier($myfileName).'" enctype="multipart/form-data">	
					<input type="hidden" name="lesRecommandes" id="lesRecommandes" value="" /> 
					<input type="hidden" name="leFichierOriginal" id="leFichierOriginal" value="'. $myfileName .'" /> 		
					<button type="submit" id="btnEnregistrerCMD" title="Enregistrer ces recommandes">Enregistrer ces recommandes</button>
				</form> 	
			</div>';
			}
		?>	
		<br><br><br>
		<H1>Recherche par n° Commandes</H1>
		<h6>il y a <?php echo count($monGroupeCmdes->tabCMDLabo);?> commandes,<br>  indiquez un numéro de commande : </h6>
		<!--<input type="text" id="zoneRecherche"  placeholder="Rechercher .." title="Commencer à taper... par exemple pour savoir dans quelle commande se trouve la planche 'P0006.-CADR-CM2(...).jpg', tapez juste 'P0006'...">  
		<button onclick="filterFunction()" id="btnRechercher" title="Rechercher"><img src="img/searchicon.png"></button>-->
		<input type="text" id="zoneRecherche" onkeyup="filterCommandes()" placeholder="n° de commande .." title="Commencer à taper... par exemple pour savoir dans quelle commande se trouve la planche 'P0006.-CADR-CM2(...).jpg', tapez juste 'P0006'...">  		  
		<h6>il y a <?php echo count($monGroupeCmdes->tabCMDLabo);?> commandes : </h6>		
		<ul id="listeRechercheCMD">
			<?php echo $monGroupeCmdes->AfficheMenuCMD();?>	
		</ul>		  
		<br><br><br>
		<H1>Recherche de planches par critère</H1>
		<h6>
		Indiquez un produit, un nom de fichier (visible au dos de la photo), une classe, ...
		</h6>			

		<!--<input type="text" id="zoneRecherche"  placeholder="Rechercher .." title="Commencer à taper... par exemple pour savoir dans quelle commande se trouve la planche 'P0006.-CADR-CM2(...).jpg', tapez juste 'P0006'...">  
		<button onclick="filterFunction()" id="btnRechercher" title="Rechercher"><img src="img/searchicon.png"></button>-->
		<input type="text" id="zoneRecherchePlanche" onkeyup="filterPlanches()" placeholder="Rechercher .." title="Commencer à taper... par exemple pour savoir dans quelle commande se trouve la planche 'P0006.-CADR-CM2(...).jpg', tapez juste 'P0006'...">  		  
		  

		</div>
		<div id="main">
		<div id="zoneRechercheCMD">	
		<br><br>
			<?php 	echo $monGroupeCmdes->Affiche(0); ?>	
		</div>	

		<div class="footer">
		  <p class="mention">	<?php echo VersionPhotoLab();?> </p>
		</div>

	</div>
 
</div>



<script type="text/javascript" src="<?php Mini('js/CMDRecherche.js');?>"></script>
<!--<script type="text/javascript" src="<?php Mini('js/purePajinate.js');?>"></script>-->

<script>
	function intersect(a, b) {
	  var setB = new Set(b);
	  var setA = new Set(a);
	  return [...new Set(a)].filter(x => setB.has(x));
	}
	//AfficheRechercheCMD(true);
	openNav();
</script>

</body>
</html>
