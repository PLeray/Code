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
    <title id="GO-PHOTOLAB">PhotoLab : Cartonnage</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
    <link rel="stylesheet" type="text/css" href="css/CMD-ViewNEW.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	
	
<?php
	$repertoireTirages = $GLOBALS['repTIRAGES'];
	$repertoireCMD = $GLOBALS['repCMDLABO'];
	$repertoireMiniatures = $GLOBALS['repMINIATURES'];

	//$tabFICHIERLabo = LireFichierLab($repertoireCMD.$myfileName);
	$monGroupeCmdes = new CGroupeCmdes($repertoireCMD.$myfileName);
	//$tabCMDLabo = InitTabCMDLabo($tabFICHIERLabo);
	
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';
	
	//$EcoleEnCours = '';
	$EcoleEnCours = new CEcole("___");

	//$maCMDHtml = AffichageCMD($tabFICHIERLabo, $numeroCMD - 1, $EcoleEnCours, $NbCMDAffiche);
	

	
	
	//$versionFichierLab = VersionFichierLab($tabFICHIERLabo);

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
<!-- 
	
	<div class="recherche">	
		<p><STRONG><?php echo count($monGroupeCmdes->tabCMDLabo) . ' commandes au total';?></STRONG></p>
		<input type="text" placeholder="Recherche commandes..." id="mySearch" onclick="myFunction2()" onkeyup="filterFunction()" title="indiquez des termes d'une commande/n ou son numéro">
	</div>   
-->	

<div class="affichageCommande">
<div class="row">
  <div class="left" style="background-color:#bbb;">
    <h2><?php echo count($monGroupeCmdes->tabCMDLabo);?> Cmdes</h2>
    <input type="text" id="mySearch" onkeyup="filterFunction()" placeholder="Search.." title="Type in a category">
    <ul id="myMenu">
		<?php echo $monGroupeCmdes->AfficheMenuCMD();?>	
    </ul>
  </div>
  
  <div class="right" style="background-color:blue;">
<?php 
	//echo 'kdsjghskdhgf';
	//echo LienMEGA($monGroupeCmdes->tabCMDLabo, $numeroCMD);
	//<a href="#about">About</a>
?>



	<div class="zonePagesCMD">
	<div class="page_navigation"></div>
		<div class="items">			
			<?php 	echo $monGroupeCmdes->Affiche(); ?>	
		</div>
		<div class="page_navigation"></div>
	</div>

	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
	
</div>	
</div>

<script>
function myFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("mySearch");
  filter = input.value.toUpperCase();
  ul = document.getElementById("myMenu");
  li = ul.getElementsByTagName("li");
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("a")[0];
    if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}
</script>
	
	
	
	



</body>
<script type="text/javascript" src="js/CMD-ViewNEW.js"></script>
<script src="js/purePajinate.min.js"></script>
</html>
