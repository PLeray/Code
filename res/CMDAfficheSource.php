<!DOCTYPE html>
<?php

	setlocale(LC_TIME, 'french');
	include_once 'APIConnexion.php';
	include_once 'CMDClassesDefinition.php';

	$myfileName = (isset($_GET['fichierLAB'])) ? $_GET['fichierLAB'] :'';
	
	$codeMembre = 0;
	if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
	$isDebug = false;
	if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}
	if ($isDebug){header("Cache-Control: no-cache, must-revalidate");}
	
	$sepFinLigne = '§';	
	
$codeSource = 'qsd';
if (isset($_GET['codeSource'])) { // Test connexion l'API
	$codeSource = $_GET['codeSource'];
}
$anneeSource = 'qsd';
if (isset($_GET['anneeSource'])) { // Test connexion l'API
	$anneeSource = $_GET['anneeSource'];
}

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'CATSources');

$MAJ = false;
if (isset($_GET['MAJ'])) { // Lancement apres
	$MAJ = ($_GET['MAJ']=='true')?true:false;
}

if($isDebug){
	header("Cache-Control: no-cache, must-revalidate");
}

$lesPhotoSelection = '';
if (isset($_POST['lesPhotoSelection']) ){
	$lesPhotoSelection = $_POST['lesPhotoSelection'];
	if ($isDebug){echo 'VOILA LES PHOTOS SELECTIONNEES  pour ' . $lesPhotoSelection;}	
	///MAJFichierCommandes($lesCommandes, $codeSource, $anneeSource);
}
$lesCommandes = '';
if (isset($_POST['lesCommandes']) ){
	$lesCommandes = $_POST['lesCommandes'];
	if ($isDebug){echo 'VOILA LES RECOMMANDES SELECTIONNEES  pour ' . $lesCommandes;}	
	MAJFichierCommandes($lesCommandes, $codeSource, $anneeSource);
}
$lesFichiersBoutique = '';
if (isset($_POST['lesFichiersBoutique']) ){
	$lesFichiersBoutique = $_POST['lesFichiersBoutique'];
	if ($isDebug){echo 'VOILA LES lesFichiersBoutique : ' . $lesFichiersBoutique;}	
	MAJFichierBoutique($lesFichiersBoutique, $codeSource, $anneeSource);
}

$monProjet = ChercherSOURCESEcole("../../SOURCES/Sources.csv", $codeSource, $anneeSource);

// echo $monProjet->NomProjet . $monProjet->NomProjet . $monProjet->NomProjet;
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
	<head>
		<title id="PHOTOLAB">Affichage :  <?php echo $monProjet->NomProjet ; 	?></title>
		<link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
		<link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDAfficheSource.css');?>">
		<link rel="shortcut icon" type="image/png" href="img/favicon.png">

		

	</head>

<body onload="EffacerChargement()">

<div id="MSGChargement" > 
	<div class="cs-loader">
	
	  <div class="cs-loader-inner">
	  <H5>Creation du cache des photographies de : <?php echo $monProjet->NomProjet ; 	?> ...</H5>
	  <br>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<br>
		<br>
		<H5>Patientez, selon le nombre de photos à traiter, cela peut durer quelques minutes ...</H5>
	  </div>
	</div>
</div> 

<?php 

/* */
if (!$MAJ){
	$RefSource = "&codeSource=" . urlencode($codeSource). "&anneeSource=" . urlencode($anneeSource);
	
	echo '<meta http-equiv="refresh" content="0; URL=CMDAfficheSource.php' . ArgumentURL($RefSource.'&MAJ=true') .'"> ';
}
?>
<div id="site"">
   <!-- Tout le site ici -->
	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">Remonter</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranSources($monProjet->AnneeScolaire); ?>" title="Retour à la liste des sources de photos"><img src="img/Logo-Retour.png" alt="Image de fichier"></a>
		</div>
		
		<div class="titreFichier">	
			<?php echo $monProjet->NomProjet ; 	?>
		</div>

		<?php echo  '<p>' . $monProjet->Dossier . '</p>';?>
		<div class="titreFichier">Afficher : 
			<span id="idAfficheTout" onclick=AfficheTout()>|Toutes les photos|</span>
			<span id="idAfficheGroupe" onclick=AfficheGroupe()>|Seulement les groupes|</span>	
			<span id="idAfficheIndivs" onclick=AfficheIndivs()>|Seulement les individuelles|</span>
			
		</div>		
	</div>

	  <div id="main">
		<div id="mySidenav" class="sidenav">
		
		
		<a href="javascript:void(0)" class="closebtn" onclick="BasculeAfficheSideBar()"><<</a>
			<div id="myRecommandes" class="infoRecommandes"><H1>Mes Commandes</H1><br>

				<a href=javascript:void(0); onclick=VoirPhotoSelection()>Afficher sélection pour tirage</a>	
				<div id="myListePhoto" class="ListeCommandes">		
					<?php echo str_replace($sepFinLigne, "<br>", $lesPhotoSelection); ?>      
				</div>					
	
				<div class="dropdown">
					
					<input type="text" placeholder="Sélectionner un produit..." id="ZoneSaisie" onclick="SelectionProduit()" onkeyup="filterProduits()">
					<div id="myDropdown" class="dropdown-content">
						<a href="#about">Agrandissement  20x20cm</a>
						<a href="#base">Quattro  20x20cm</a>
						<a href="#about">Agrandissement  20x20cm</a>
						<a href="#base">Quattro  20x20cm</a>
						<a href="#about">Agrandissement 20x20cm</a>
						<a href="#base">Quattro  20x20cm</a>
					</div>
				</div>

				<br><br>	
				<a href="javascript:void(0)" class="btnAjouterTirages" onclick="TransfererCMD()">Ajouter tirages</a><span id="SelectProduit" >Agrandissement  20x20cm</span>		 
				<br><br>
				<div id="myListeCommandes" class="ListeCommandes">		
					<?php echo str_replace($sepFinLigne, "<br>", $lesCommandes); ?>      
				</div>	
				<a href=javascript:void(0); onclick=VoirPhotoSelection()>Afficher sélection pour fichiers boutiques</a>	
				<div id="myListeFichiersBoutique" class="ListeCommandes">
					<?php echo str_replace($sepFinLigne, "<br>", $lesFichiersBoutique); ?>
				</div>
				
				<br><br><br>				
				<form name="FormEnvoieRecos" method="post" action="<?php echo ValidationCommandes($monProjet->NomProjet); ?>" enctype="multipart/form-data">	
					<input type="hidden" name="lesPhotoSelection" id="lesPhotoSelection" value="<?php echo $lesPhotoSelection; ?>" /> 
					<input type="hidden" name="lesCommandes" id="lesCommandes" value="<?php echo $lesCommandes; ?>" /> 					
					<input type="hidden" name="lesFichiersBoutique" id="lesFichiersBoutique" value="<?php echo $lesFichiersBoutique; ?>" /> 
	
					<button type="submit" class="btnEnregistrer">Quitter et enregistrer ces commandes</button>
				</form> 	
					
			</div>		
		</div>
	  
		<div id="zoneAffichagePhotoEcole">	
			<br><br>
			<?php //echo AfficheSOURCESEcole($monProjet); ?>	
		
			<?php  
			if ($MAJ){
				set_time_limit(2000);		
				echo AfficheSOURCESEcole($monProjet);
			}
			?>
		
			<div class="footer">
			  <p class="mention"><?php echo VersionPhotoLab();?> </p>
			</div>		
		
		
		</div>	
	</div>
</div>
<?php 

if (!extension_loaded('gd')) {
    echo '<!-- The Modal -->
<div id="myModal" class="modal-PBgd">

  <!-- Modal content -->
  <div class="modal-content-PBgd">
 Ce module permet l\'affichage de vos jpg sources qui sont en haute résolution.
 <br> <br> <br>
 Cependant pour fonctionner, il nécessaire que la librairie "gd" soit activée sur votre serveur local pour fonctionner.
 <br> <br> <br>
 Pour activer simplement la librairie "gd" , cliquez sur l\'icône ci-dessous et suivez les instructions?
 <br> <br> 
 <a href="'.$maConnexionAPI->URL .'/installation/PROCEDURE-PC-Installation-PhotoLab.pdf" title="Afficher les instruction"><img src="img/AIDE.png" alt="Afficher les instruction"></a>


  </div>

</div>';}



?>
<script type="text/javascript" src="<?php Mini('js/CMDAfficheSource.js');?>"></script>
<script>
<?php 
if ($MAJ){	
	$AffichePanneau = false;
	//echo "alert('MAJAffichage');";
	echo 'MAJAffichageSelectionPhotos(true);';
	
	//echo 'MAJEnregistrementSelectionPhotos();';
	if(($lesPhotoSelection != '') || ($lesCommandes != '') || ($lesFichiersBoutique != '')) {$AffichePanneau  = true; }
	echo 'EffacerChargement();';
	if($AffichePanneau) {
		echo 'AfficheRechercheCMD(true);';
	}else{
		echo 'AfficheRechercheCMD(false);';
	}
}





?>
	/*EffacerChargement();
	AfficheRechercheCMD(true);
	openNav();*/
</script>
<script type="text/javascript" src="<?php Mini('js/ClickDroit.js');?>"></script>
</body>
</html>


<?php 
function MAJFichierCommandes($ListeFichier, $codeSource, $anneeSource){ 
//echo 'VOILA LES MAJFichierCommandes : ' . $ListeFichier;
}

function MAJFichierBoutique($ListeFichier, $codeSource, $anneeSource){ 
//echo 'VOILA LES lesFichiersBoutique : ' . $ListeFichier;
}


function ChercherSOURCESEcole($fichierCSV, $codeProjet, $anneeProjet){ 
	$monProjet = '';
	if (file_exists($fichierCSV)){
		$TabCSV = csv_to_array($fichierCSV, ';');

		$NbLignes=count($TabCSV);
		//echo 'nb ligne catalog source ' . $NbLignes;
		if ($NbLignes){

			$NbLignes=count($TabCSV);
			for($i = 0; $i < $NbLignes; $i++){ 
				if ($codeProjet == $TabCSV[$i]["Code"]  && $anneeProjet == $TabCSV[$i]["AnneeScolaire"]){
					$Dossier = $TabCSV[$i]["DossierSources"];	
					$Dossier = "../.." . urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
					$monProjet = new CProjetSource($TabCSV[$i]["NomProjet"], 
												$Dossier, 
												$codeProjet,
												$anneeProjet,
												$TabCSV[$i]["Rep Scripts PS"]);
				break;
				}
			}
		}
	}
	return $monProjet;
}

function AfficheSOURCESEcole($monProjet){ 
	$dir = $monProjet->Dossier . '/*.*{jpg,jpeg}';
	$files = glob($dir,GLOB_BRACE);

	// SUPPRESSION DU CACHE CreationDossier($monProjet->Dossier . '/Cache');

	$affiche_Tableau = '<p>';

	$PrecedentEstGroupe = false;
		
	//$affiche_Tableau .= '				<table>';	
	foreach($files as $image){ 
		// SUPPRESSION DU CACHE $FichierARefaire = false;
		$posDernier = 1 + strripos($image, '/');
		$FichierSource = substr($image, $posDernier);
		// SUPPRESSION DU CACHE $FichierCache = 'Cache/'. $FichierSource;
		$Dossier = substr($image, 0, $posDernier);

		 $mesSources = new CImgSource($FichierSource, $Dossier, $monProjet->CodeEcole,$monProjet->AnneeScolaire,$monProjet->ScriptsPS);
		 
		 if ($mesSources->isGroupe() && !$PrecedentEstGroupe){
			 //echo 'GROUPE';
			 $affiche_Tableau .= '<div class="ligne_classe">'.NomClasseDepuisNomGroupe($mesSources->Fichier).'</div>';
			 $PrecedentEstGroupe = true;
			 //$affiche_Tableau .= '<tr><td style=vertical-align:top>';
		 }else{
			$PrecedentEstGroupe = false;	
		}
		$affiche_Tableau .= $mesSources->Affiche();
	}

	//$affiche_Tableau .= '</td></tr></table>' ;
			$affiche_Tableau .= '</p>';

	return $affiche_Tableau;
}

	
function RetourEcranSources($ParamAnnee = ''){
	$RetourEcran = 'CATSources.php?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	return $RetourEcran . '&AnneeScolaire=' . $ParamAnnee ;
}	

function ValidationCommandes($NomProjet, $ParamAnnee = ''){
	//$NBPlanches = NBfichiersARBOWEB($fichier);
	//$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
	$CMDhttpLocal = '&CMDdate=' . date("Y-m-d"); 
	//$CMDhttpLocal .= '&CMDwebArbo=' . $NBPlanches;
	$CMDhttpLocal .= '&CodeEcole=' . $GLOBALS['codeSource'] . '&AnneeScolaire=' . $GLOBALS['anneeSource'] ;		
	/*
	if ($GLOBALS['lesFichiersBoutique'] != ''){

	}
	if ($GLOBALS['lesCommandes'] != ''){

	}*/	

		$CMDhttpLocal .= '&CMDwebArbo='. urlencode('CORR');
		$CMDhttpLocal .= '&BDDARBOwebfile=' . urlencode(utf8_encode(NomCorrectionARBO($NomProjet)));	

		$CMDhttpLocal .= '&CMDLibre='. urlencode('LIBRE');
		$CMDhttpLocal .= '&BDDRECFileLab=' . urlencode(utf8_encode(NomFichierLIBRE($NomProjet)));	

	
	$retourMSG = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal);

	return $retourMSG ;
}	

function NomCorrectionARBO($NomProjet) {
	return 'CORR-' . $NomProjet. '.web';
}

function NomFichierLIBRE($NomProjet) {
	return date("Y-m-d"). '-TIRAGES LIBRES-' . $NomProjet. '.lab';
}

function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}


function NomClasseDepuisNomGroupe($strNOMdeClasse){
	$NomClasse = '';
	if (strpos(strtolower($strNOMdeClasse),'fratrie')){
		$NomClasse = 'Fratries';
	}else{
		$NomClasse = substr($strNOMdeClasse, 1 + strpos($strNOMdeClasse, '-', 5), -4);
	}
	return  '   ' . $NomClasse . '   ' ;
}


?>