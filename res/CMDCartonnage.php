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
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'DEBUG':'PROD').'.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDCartonnage.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>

	
<?php
	
	$DefautNbCMDAffiche = 15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}

	$monGroupeCmdes = new CGroupeCmdes($GLOBALS['repCMDLABO'].$myfileName);

    $TotalCommandes = count($monGroupeCmdes->tabCMDLabo);
	
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';

	$EcoleEnCours = new CEcole("____",'2020-07-07');
	
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
<div id="site">
   <!-- Tout le site ici -->

	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">↑ Remonter ↑</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes groupées"><img src="img/Logo-Retour.png" alt="Image de fichier">
        </a>
		</div>		
		<div class="affichageInfoCMD">
            <?php 
            echo '<p>Il y a <font size="+1"><B>'. $TotalCommandes . '</B></font> commandes au total</p>

            <p  title="Afficher toutes les commandes sur une seule page">● Afficher tout : 

            <a href="'. LienAfficheToutesLesCommandes(($NbCMDAffiche > 0), '&fichierLAB='.urlencode($myfileName)) . '" 
            class="'. (($NbCMDAffiche < 0 )?'caseCheckCoche':'caseCheckVide'). '">✓</a>		
			</p>';

            if($NbCMDAffiche > 0){
                echo '<p>● <a href="'. LienAffichePlusMoins('-','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD ). '" class="moinsplus">-</a>
                <B><font size="+1"> ';
                if ($NbCMDAffiche < 10000){echo abs($NbCMDAffiche);}
                echo ' </font></B><a href="'. LienAffichePlusMoins('+','&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD ) .'" class="moinsplus">+</a> commandes / page</p>';
            }
            ?>
		</div> 	
		
		<div class="titreFichier">	
			<?php echo pathinfo(utf8_encode($myfileName))['filename'] ;?>
			<?php echo '<p style="color:Green;"><b>' . $etatFichierLab . '</b></p>';?>
			<br>
			<p>Affichage commandes :	
			<label class="contienLeBoutonRadio"  title="Afficher toutes les commandes ouvertes ou fermées selon l'avancement de la mise en pochette">mise en pochette
			<input type="radio" checked="checked" name="radio" id="modeCartonnage" onclick="fxModeAffichage(0)">
			<span class="boutonRadio"></span>
			</label>
			<label class="contienLeBoutonRadio"  title="Ouvrir toutes les commandes quelque soit leur état de mise en pochette
            (Ça ne changera pas l'enregistrement de la mise en pochette)">Toutes Dépliées
			<input type="radio" name="radio" id="modeDeplie" onclick="fxModeAffichage(1)">
			<span class="boutonRadio"></span>
			</label>
			<label class="contienLeBoutonRadio"  title="Fermer toutes les commandes quelque soit leur état de mise en pochette
            (Ça ne changera pas l'enregistrement de la mise en pochette)">Toutes Repliées
			<input type="radio" name="radio" id="modeReplie" onclick="fxModeAffichage(-1)">
			<span class="boutonRadio"></span>
			</label>
			</p>
		</div>

		
		<a href="<?php echo LienRecherche('&fichierLAB='.urlencode($myfileName).'&numeroCMD='. $numeroCMD );?>"><span id="loupe" title="Rechercher des commandes ou des planches ..."style="cursor:pointer"><p>Rechercher commande, planche :</p><img src="img/search-more.png"></span></a>
			
		<!--
		<span id="loupe" style="font-size:30px;cursor:pointer" onclick="openNav()"><p><?php // echo count($monGroupeCmdes->tabCMDLabo) . ' commandes au total';?><img src="img/search-more.png"></p></span>
		-->		
	</div>

	  <div id="main">

	<!-- -->
		<div id="zoneListePageCMD">
			<br><br>
			<div class="zonePagesCMD">

			<div id="sdf" class="ZoneZoomPhoto" onclick="FermerZoom();">
			</div>	

			
            <?php
                if ($NbCMDAffiche < 0 ){
                    echo $monGroupeCmdes->Affiche($TotalCommandes);
                }
                else
                {
                    echo 
                    '<div class="page_navigation"></div>		
                    <div class="items">	'			
                    . $monGroupeCmdes->Affiche($NbCMDAffiche) .	
                        
                    '</div>';

                }
                
            ?>
			</div>

	  </div>

		<div class="footer">
		  <p class="mention">	<?php echo VersionPhotoLab();?> </p>
		</div>

	</div>
 
</div>



<script type="text/javascript" src="<?php Mini('js/CMDCartonnage.js');?>"></script>
<script type="text/javascript" src="<?php Mini('js/purePajinate.js');?>"></script>

<script>
	//alert('ID ' );
	function intersect(a, b) {
	  var setB = new Set(b);
	  var setA = new Set(a);
	  return [...new Set(a)].filter(x => setB.has(x));
	}
	initPagination();
	//AfficheRechercheCMD(<?php //echo $isRECOmmandes;?>);
	
</script>

</body>
</html>
