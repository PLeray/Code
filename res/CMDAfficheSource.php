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
	
$CodeEcole = 'qsd';
if (isset($_GET['CodeEcole'])) { // Test connexion l'API
	$CodeEcole = $_GET['CodeEcole'];
}
$AnneeScolaire = 'qsd';
if (isset($_GET['AnneeScolaire'])) { // Test connexion l'API
	$AnneeScolaire = $_GET['AnneeScolaire'];
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
}
$lesCmdesLibres = '';
if (isset($_POST['lesCmdesLibres']) ){
	$lesCmdesLibres = $_POST['lesCmdesLibres'];
	if ($isDebug){echo 'VOILA LES RECOMMANDES SELECTIONNEES  pour ' . $lesCmdesLibres;}	
	MAJFichierCommandes($lesCmdesLibres, $CodeEcole, $AnneeScolaire);
}
$lesFichiersBoutique = '';
if (isset($_POST['lesFichiersBoutique']) ){
	$lesFichiersBoutique = $_POST['lesFichiersBoutique'];
	if ($isDebug){echo 'VOILA LES lesFichiersBoutique : ' . $lesFichiersBoutique;}	
	MAJFichierBoutique($lesFichiersBoutique, $CodeEcole, $AnneeScolaire);
}

//$monProjet = RecupProjetSourceEcole("../../SOURCES/Sources.csv", $CodeEcole, $AnneeScolaire);

$monProjet = new CProjetSource($CodeEcole, $AnneeScolaire);



// echo $monProjet->NomProjet . $monProjet->NomProjet . $monProjet->NomProjet;
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
	<head>
		<title id="PHOTOLAB">Affichage :  <?php echo $monProjet->NomProjet ; 	?></title>
		<link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'DEBUG':'PROD').'.css');?>">
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
	$RefSource = "&CodeEcole=" . urlencode($CodeEcole). "&AnneeScolaire=" . urlencode($AnneeScolaire);
	
	echo '<meta http-equiv="refresh" content="0; URL=CMDAfficheSource.php' . ArgumentURL($RefSource.'&MAJ=true') .'"> ';
}
?>
<div id="site"">
   <!-- Tout le site ici -->
	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">↑ Remonter ↑</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranSources($monProjet->AnneeScolaire); ?>" title="Retour à la liste des sources de photos"><img src="img/Logo-Retour.png" alt="Image de fichier"></a>
		</div>

		<div class="btnCatalogue"><a href="<?php echo AccesCatalogue($monProjet); ?>" title="Retour à la liste des sources de photos"><img src="img/btnCatalogue.png" alt="Image de fichier"></a>
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
	  <div id="zoneAffichagePhotoEcole">	
			<?php //echo AfficheSOURCESEcole($monProjet); ?>	
			<div id="sdf" class="ZoneZoomPhoto" onclick="FermerZoom();">
			</div>		
	
			<?php  
			if ($MAJ){
				set_time_limit(2000);		
				echo AfficheSOURCESEcole($monProjet);
			}
			?>
	
		</div>

		<div id="mySidenav" class="sidenav">
			<div class="tab">
				<button class="tablinks" onclick="BasculeOnglet(event, 'ZoneCommandesTirages')" id="defaultOngletOuvert">Tirages</button>
				<button class="tablinks" onclick="BasculeOnglet(event, 'ZoneCommandesFichierBoutiques')" >Fichiers boutique</button>
			</div>
			<a href="javascript:void(0)" id="closeSidenav" class="closebtn" onclick="BasculeAfficheSideBar()"> + </a>	

			<div id="myRecommandes" class="infoRecommandes">
			
				<div id="ZoneCommandesTirages" class="tabcontent">
				<H1>Mes Commandes</H1><br>	

				<div>
					<span class = "SelectionToutePlanche">Sélectionner toutes les planches <a href=javascript:void(0); id ="CaseSelectionnerTiragesAffiche" onclick=SelectionnerCommandeTiragesAffiche() class="caseCheckVide" > ✓ </a></span >
				</div>	
				<br>
					<div class="dropdown">
						
					<img src="img/searchicon.png" id="LoupeInitZoneSaisie" title="Initialiser la liste de produits" onclick="SelectionProduitInitialisation()">
					<input type="text" placeholder="Sélectionner un produit..." id="ZoneSaisie" onclick="SelectionProduit()" onkeyup="filterProduits()">
						<div id="myDropdown" class="dropdown-content">							
							<table >
								<tr with =100%>
									<?php echo RemplissageDropScriptFormat($monProjet); ?>
								</tr>
									<?php //echo RemplissageDropScriptFormat($monProjet); ?>
									<?php //echo RemplissageDropProduit($monProjet); ?>								<tr>								
								</tr>
							</table>

							
						</div>
					</div>
					<br><br>
					<!-- Tirages ici 
					<a href=javascript:void(0); onclick=VoirPhotoSelection()>Afficher (A FAIRE) uniquement sélection pour tirage</a>	-->
		
						
					<div id="myListePhoto" class="ListeCommandes">		
						<?php echo str_replace($sepFinLigne, "<br>", $lesPhotoSelection); ?>      
					</div>	
					<p>Traiter les photos ci-dessus pour faire le produit :</p>
					<button id="btnAjouterTirages" Code="" class="btnAjouterTirages" onclick="CreationCommandeProduitDepuisPhoto()" disabled></button>
																				 
					<br>
					<div id="myListeCommandes" class="ListeCommandes">		
						<?php echo str_replace($sepFinLigne, "<br>", $lesCmdesLibres); ?>      
					</div>	
					<form name="FormEnvoieRecos" method="post" action="<?php echo ValidationCommandesLIBRES($monProjet->NomProjet); ?>" enctype="multipart/form-data">	
						<input type="hidden" name="lesPhotoSelection" id="lesPhotoSelection" value="<?php echo $lesPhotoSelection; ?>" /> 
						<input type="hidden" name="lesCmdesLibres" id="lesCmdesLibres" value="<?php echo $lesCmdesLibres; ?>" /> 					

						<button type="submit" id="btnCmdesLibres" class="btnEnregistrer" disabled >Quitter et enregistrer ces commandes LIBRES</button>
					</form>     
				</div>	
				
				<div id="ZoneCommandesFichierBoutiques" class="tabcontent">
				<H1>Mes FichiersBoutique</H1><br>		
				<div>
					<span class = "SelectionToutePlanche">Sélectionner toutes les planches <a href=javascript:void(0); id ="CaseSelectionnerBoutiqueAffiche" onclick=SelectionnerCommandesBoutiquesAffiche() class="caseCheckVide" > ✓ </a></span >
				</div>	
				<br>
					<!-- FICHIERBOUTIQUES ici 
					<a href=javascript:void(0); onclick=VoirPhotoSelection()>Afficher (A FAIRE) uniquement sélection pour fichiers boutiques</a>	-->

					
					<div id="myListeFichiersBoutique" class="ListeCommandes">
						<?php echo str_replace($sepFinLigne, "<br>", $lesFichiersBoutique); ?>
					</div>				
					<form name="FormEnvoieRecos" method="post" action="<?php echo ValidationCommandesFICHIERBOUTIQUES($monProjet->NomProjet); ?>" enctype="multipart/form-data">					
						<input type="hidden" name="lesFichiersBoutique" id="lesFichiersBoutique" value="<?php echo $lesFichiersBoutique; ?>" /> 
		
						<button type="submit" id="btnFichiersBoutique" class="btnEnregistrer" disabled>Quitter et enregistrer ces commandes de FICHIERBOUTIQUES</button>
					</form>     
				</div>		
						
			</div>	
			
			<div class="footer">
		<p><?php echo VersionPhotoLab();?> </p>
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
 <a href="'.$maConnexionAPI->URL .'/installation/PROCEDURE-Activez-la-librairie-gd-pour-la-gestion-des-images.pdf" title="Afficher les instruction"><img src="img/AIDE.png" alt="Afficher les instruction"></a>


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
	if(($lesPhotoSelection != '') || ($lesCmdesLibres != '') || ($lesFichiersBoutique != '')) {$AffichePanneau  = true; }
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

</body>
</html>


<?php 
function MAJFichierCommandes($ListeFichier, $CodeEcole, $AnneeScolaire){ 
//echo 'VOILA LES MAJFichierCommandes : ' . $ListeFichier;
}

function MAJFichierBoutique($ListeFichier, $CodeEcole, $AnneeScolaire){ 
//echo 'VOILA LES lesFichiersBoutique : ' . $ListeFichier;
}

function RemplissageDropScriptFormat($monProjet){ 
	$monCatalogueScriptPS = $GLOBALS['repGABARITS'] . $monProjet->NomCatalogue();
	$CataloguePRODUITS = array();
	//$PremiereLigneARefaire = false;	
	if (file_exists($monCatalogueScriptPS)){ 
		$file = fopen($monCatalogueScriptPS, "r");
		if ($file) {
			while(!feof($file)) {
				$line = trim(fgets($file));
				if (strpos($line, ';') > 1){
					array_push($CataloguePRODUITS, $line);
				}
			}
			fclose($file);	
		}
		$maBibliothequeScriptPS = $GLOBALS['repGABARITS'] . 'ActionsScriptsPSP.csv';
		if (filemtime($maBibliothequeScriptPS) > filemtime($monCatalogueScriptPS)){
			//$PremiereLigneARefaire = true;		 
		 }	
			
	}else {
		//$PremiereLigneARefaire = true;
	}


	//if(count($CataloguePRODUITS) < 1){echo 'lkdsfgfdgd dfgggggggggggggggggggggggg jlkj';array_push($CataloguePRODUITS,'lkj');}
	//array_push($CataloguePRODUITS,RecupScriptSelonNomDossier($monProjet->ScriptsPS));
	//If date fichier ActionsScriptsPSP.csv + recente que $monCatalogueScriptPS alors mettre à jour 1ere ligne
/*
	$file = fopen($monCatalogueScriptPS, 'w');
	if ($PremiereLigneARefaire){
		fputs($file, RecupScriptSelonNomDossier($monProjet->ScriptsPS). "\n");
	}
	else{ fputs($file, $CataloguePRODUITS[0]. "\n");}
	for($i = 1; $i < count($CataloguePRODUITS) ; $i++){
		fputs($file, $CataloguePRODUITS[$i]. "\n");
	} 	
	fclose($file);

	*/
	$line =  '';
	for($i = 1; $i < count($CataloguePRODUITS) ; $i++){
		if (strpos($CataloguePRODUITS[$i], ';') > 1){
			$morceau = explode(";",  $CataloguePRODUITS[$i]);
			$line .= '<a href=javascript:void(0); Code="'.$morceau[1].'" onclick="CliqueDropDown(this)">'.$morceau[0].'</a>';
		}
	} 	
	return $line;
}

function RecupScriptSelonNomDossier($moDossierScriptsPS){ 
	$maBibliothequeScriptPS = $GLOBALS['repGABARITS'] . 'ActionsScriptsPSP.csv';
	$strScripts = '';

	if (file_exists($maBibliothequeScriptPS)){ 
		$file = fopen($maBibliothequeScriptPS, "r");
		if ($file) {
			while(!feof($file)) {
				$strScripts = trim(fgets($file));
				$morceau = explode(";", $strScripts);
				if ($morceau[0] == $moDossierScriptsPS){					
					$maBibliothequeScriptPS = $strScripts;
				}
			}
			fclose($file);	
		}
	}
	return $maBibliothequeScriptPS;
	//return 'oih';
}

/*
function RecupProjetSourceEcole($fichierCSV, $codeProjet, $anneeProjet){ 
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
					//echo '<br>'.$Dossier;
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
}*/ 

function AfficheSOURCESEcole($monProjet){ 
	$dir = $monProjet->Dossier . '/*.*{jpg,jpeg}';
	$files = glob($dir,GLOB_BRACE);

	// SUPPRESSION DU CACHE CreationDossier($monProjet->Dossier . '/Cache');

	$affiche_Tableau = '<p>';

	//$PrecedentIdentifiantClasse = '';
	$PrecedentNumeroClasse = '';
		
	//$affiche_Tableau .= '				<table>';	
	foreach($files as $image){ 
		// SUPPRESSION DU CACHE $FichierARefaire = false;
		//echo $image;

		//$file_name = 'gfg.html';
		//$extension = pathinfo($image, PATHINFO_EXTENSION);
		//echo $extension;

		//$FichierSource = substr($image,0, strpos($image,$extension));

		

		$posDernier = 1 + strripos($image, '/');
		$FichierSource = substr($image, $posDernier);
		// SUPPRESSION DU CACHE $FichierCache = 'Cache/'. $FichierSource;
		$Dossier = substr($image, 0, $posDernier);

		 $mesSources = new CImgSource($FichierSource, $Dossier, $monProjet->CodeEcole,$monProjet->AnneeScolaire,$monProjet->ScriptsPS);
		
		
		if ($mesSources->isGroupe()){
			$mesInfoichierGroupe = new CNomFichierGroupe($mesSources->Fichier);
			if($PrecedentNumeroClasse != $mesInfoichierGroupe->Numero){   //$mesInfoichierGroupe->NomClasse
				//echo 'GROUPE';
				//$affiche_Tableau .= '<div class="ligne_classe">'.NomClasseDepuisNomGroupe($mesSources->Fichier).'</div>';
				$affiche_Tableau .= '<div class="ligne_classe">'.$mesInfoichierGroupe->NomClasse.'</div>';
				$PrecedentNumeroClasse = $mesInfoichierGroupe->Numero;		
			}
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

function AccesCatalogue($monProjet){
	$RetourEcran = 'CMDCatalogueProduits.php'. ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire);
	return $RetourEcran  ;	
}	

//function ValidationCommandes($NomProjet, $ParamAnnee = ''){
function ValidationCommandesFICHIERBOUTIQUES($NomProjet){
	$CMDhttpLocal = '&CMDdate=' . date("Y-m-d"); 
	$CMDhttpLocal .= '&CodeEcole=' . $GLOBALS['CodeEcole'] . '&AnneeScolaire=' . $GLOBALS['AnneeScolaire'] ;		
	$CMDhttpLocal .= '&CMDwebArbo='. urlencode('CORR');
	$CMDhttpLocal .= '&BDDARBOwebfile=' . urlencode(utf8_encode(NomCorrectionARBO($NomProjet)));	

	$retourMSG = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal);
	return $retourMSG ;
}	

function ValidationCommandesLIBRES($NomProjet){
	$CMDhttpLocal = '&CMDdate=' . date("Y-m-d"); 
	$CMDhttpLocal .= '&CodeEcole=' . $GLOBALS['CodeEcole'] . '&AnneeScolaire=' . $GLOBALS['AnneeScolaire'] ;		
	$CMDhttpLocal .= '&CMDLibre='. urlencode('LIBRE');
	$CMDhttpLocal .= '&BDDRECFileLab=' . urlencode(utf8_encode($NomProjet));	

	$retourMSG = 'APIDialogue.php'. ArgumentURL($CMDhttpLocal) ;

	return $retourMSG ;
}	

function NomCorrectionARBO($NomProjet) {
	return 'CORR-' . $NomProjet. '.web';
}
/*function NomFichierLIBRE($NomProjet) {
	return date("Y-m-d"). '-TIRAGES LIBRES-' . $NomProjet. '.lab';
} */


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
		$mesInfoichierGroupe = new CNomFichierGroupe($strNOMdeClasse);
		$NomClasse = $mesInfoichierGroupe->NomClasse;
		//$NomClasse = substr($strNOMdeClasse, 1 + strpos($strNOMdeClasse, '-', 5), -4);
	}
	return  '   ' . $NomClasse . '   ' ;
}


?>