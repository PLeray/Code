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
	if ($isDebug){echo 'VOILA LES RECOMMANDES SELECTIONNEES  pour ' . $lesPhotoSelection;}	
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
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
    <title id="PHOTOLAB">Affichage :  <?php echo $monProjet->NomProjet ; 	?></title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDImgSource.css');?>">
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
if (!$MAJ){
	$RefSource = "&codeSource=" . urlencode($codeSource). "&anneeSource=" . urlencode($anneeSource);
	
	echo '<meta http-equiv="refresh" content="0; URL=CMDImgSource.php' . ArgumentURL($RefSource.'&MAJ=true') .'"> ';
}
?>
<div id="site"">
   <!-- Tout le site ici -->
	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">Remonter</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranSources($monProjet->Annee); ?>" title="Retour à la liste des sources de photos"><img src="img/Logo-Retour.png" alt="Image de fichier"></a>
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

<script type="text/javascript" src="<?php Mini('js/CMDImgSource.js');?>"></script>
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
				if ($codeProjet == $TabCSV[$i]["Code"]  && $anneeProjet == $TabCSV[$i]["Annee"]){
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

	CreationDossier($monProjet->Dossier . '/Cache');

	$affiche_Tableau = '<p>';

	$PrecedentEstGroupe = false;
		
	//$affiche_Tableau .= '				<table>';	
	foreach($files as $image){ 
		$FichierARefaire = false;
		$posDernier = 1 + strripos($image, '/');
		$FichierSource = substr($image, $posDernier);
		$FichierCache = 'Cache/'. $FichierSource;
		$Dossier = substr($image, 0, $posDernier);
		

		// Vérification que le fichier existe
		// Vérification que le fichier existe
		if(!file_exists($Dossier . $FichierCache)){
			$FichierARefaire = true	;	
		}
		else{
			//$origin = new DateTime('2023-10-10');
			//$target = date(filemtime($FichierImage));
			 if (filemtime($Dossier . $FichierSource) > filemtime($Dossier . $FichierCache)){
				 $FichierARefaire = true;		 
				 }
		}		
		if($FichierARefaire){
			resize_img($Dossier . $FichierSource, $Dossier . $FichierCache);	
		}


		 $mesSources = new CImgSource($FichierSource, $Dossier, $monProjet->CodeEcole,$monProjet->Annee,$monProjet->ScriptsPS);
		 
		
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
	//$RetourEcran = 'CMDImgSource.php'. ArgumentURL('&codeSource=' . $GLOBALS['codeSource'] . '&anneeSource=' . $GLOBALS['anneeSource']. '&MAJ=true') ;
	
	//$RetourEcran = 'CATSources.php' . ArgumentURL(($ParamAnnee != '')?'&AnneeScolaire='.$ParamAnnee :''); 

	$fichierARBO = NomCorrectionARBO($NomProjet);

	$CMDhttpLocal ='';			
	//$NBPlanches = NBfichiersARBOWEB($fichier);
	//$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
	$CMDhttpLocal = '&CMDdate=' . date("Y-m-d"); 
	//$CMDhttpLocal .= '&CMDwebArbo=' . $NBPlanches;
	$CMDhttpLocal .= '&CMDwebArbo='. urlencode('CORR');
	$CMDhttpLocal .= '&CodeEcole=' . $GLOBALS['codeSource'] . '&AnneeScolaire=' . $GLOBALS['anneeSource'] ;		
	$CMDhttpLocal .= '&BDDARBOwebfile=' . urlencode(utf8_encode($fichierARBO));	
	
	$retourMSG = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal);

	return $retourMSG ;
}	

function NomCorrectionARBO($NomProjet) {
	//return 'ARBO-' . date("Y-m-d") . '-' . $NomProjet. '.web';
	return 'CORR-' . $NomProjet. '.web';
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




/**
 * Fonction qui permet de redimensionner une image en conservant les proportions
 * @param  string  $image_path Chemin de l'image
 * @param  string  $image_dest Chemin de destination de l'image redimentionnée (si vide remplace l'image envoyée)
 * @param  integer $max_size   Taille maximale en pixels
 * @param  integer $qualite    Qualité de l'image entre 0 et 100
 * @param  string  $type       'auto' => prend le coté le plus grand
 *                             'width' => prend la largeur en référence
 *                             'height' => prend la hauteur en référence
 * @param  boleen  $upload 	   true si c'est une image uploadée, false si c'est le chemin d'une image déjà sur le serveur
 * @return string              'success' => redimentionnement effectué avec succès
 *                             'wrong_path' => le chemin du fichier est incorrect
 *                             'no_img' => le fichier n'est pas une image
 *                             'resize_error' => le redimensionnement a échoué
 */

function resize_img($image_path,$image_dest,$max_size = 300,$qualite = 100,$type = 'auto',$upload = false){

  // Vérification que le fichier existe
  if(!file_exists($image_path)):
    return 'wrong_path';
  endif;

  if($image_dest == ""):
    $image_dest = $image_path;
  endif;
  // Extensions et mimes autorisés
  $extensions = array('jpg','jpeg','png','gif');
  $mimes = array('image/jpeg','image/gif','image/png');

  // Récupération de l'extension de l'image
  $tab_ext = explode('.', $image_path);
  $extension  = strtolower($tab_ext[count($tab_ext)-1]);

  // Récupération des informations de l'image
  $image_data = getimagesize($image_path);

  // Si c'est une image envoyé alors son extension est .tmp et on doit d'abord la copier avant de la redimentionner
  if($upload && in_array($image_data['mime'],$mimes)):
    copy($image_path,$image_dest);
    $image_path = $image_dest;

    $tab_ext = explode('.', $image_path);
    $extension  = strtolower($tab_ext[count($tab_ext)-1]);
  endif;

  // Test si l'extension est autorisée
  if (in_array($extension,$extensions) && in_array($image_data['mime'],$mimes)):
    
    // On stocke les dimensions dans des variables
    $img_width = $image_data[0];
    $img_height = $image_data[1];

    // On vérifie quel coté est le plus grand
    if($img_width >= $img_height && $type != "height"):

      // Calcul des nouvelles dimensions à partir de la largeur
      if($max_size >= $img_width):
        return 'no_need_to_resize';
      endif;

      $new_width = $max_size;
      $reduction = ( ($new_width * 100) / $img_width );
      $new_height = round(( ($img_height * $reduction )/100 ),0);

    else:

      // Calcul des nouvelles dimensions à partir de la hauteur
      if($max_size >= $img_height):
        return 'no_need_to_resize';
      endif;

      $new_height = $max_size;
      $reduction = ( ($new_height * 100) / $img_height );
      $new_width = round(( ($img_width * $reduction )/100 ),0);

    endif;

    // Création de la ressource pour la nouvelle image
    $dest = imagecreatetruecolor($new_width, $new_height);

    // En fonction de l'extension on prépare l'iamge
    switch($extension){
      case 'jpg':
      case 'jpeg':
        $src = imagecreatefromjpeg($image_path); // Pour les jpg et jpeg
      break;

      case 'png':
        $src = imagecreatefrompng($image_path); // Pour les png
      break;

      case 'gif':
        $src = imagecreatefromgif($image_path); // Pour les gif
      break;
    }

    // Création de l'image redimentionnée
    if(imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height)):

      // On remplace l'image en fonction de l'extension
      switch($extension){
        case 'jpg':
        case 'jpeg':
          imagejpeg($dest , $image_dest, $qualite); // Pour les jpg et jpeg
        break;

        case 'png':
          $black = imagecolorallocate($dest, 0, 0, 0);
          imagecolortransparent($dest, $black);

          $compression = round((100 - $qualite) / 10,0);
          imagepng($dest , $image_dest, $compression); // Pour les png
        break;

        case 'gif':
          imagegif($dest , $image_dest); // Pour les gif
        break;
      }

      return 'success';
      
    else:
      return 'resize_error';
    endif;

  else:
    return 'no_img';
  endif;
}


?>