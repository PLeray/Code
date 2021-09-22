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
	
	
$codeSource = '';
if (isset($_GET['codeSource'])) { // Test connexion l'API
	$codeSource = $_GET['codeSource'];
}
$anneeSource = '';
if (isset($_GET['anneeSource'])) { // Test connexion l'API
	$anneeSource = $_GET['anneeSource'];
}


	//$affiche_Tableau = AfficheSOURCES("../../SOURCES/Sources.csv", $codeSource, $anneeSource);
	
	
	/*$DefautNbCMDAffiche = 15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}*/
?>
<?php 
if($isDebug){
	header("Cache-Control: no-cache, must-revalidate");
}

//$monProjet = ChercherSOURCESEcole("../../SOURCES/Sources.csv", $codeSource, $anneeSource);
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>


    <title id="PHOTOLAB">Affichage :  <?php echo $monProjet->NomProjet ; 	?></title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDImgSource.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>

	

</head>

<body onload="EffacerChargement()">

<div id="MSGChargement" onclick="EffacerChargement()"> 
	<div class="cs-loader">
	
	  <div class="cs-loader-inner">
	  <H5>Mise en cache des photos</H5>
	  <br>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<br>
		<br>
		<H5>Patientez ...</H5>
	  </div>
	</div>
</div> 
<div id="site"">
   <!-- Tout le site ici -->
	<div class="footer">
		  <p class="mention"><?php echo VersionPhotoLab();?> </p>
		</div>	
	<div id="Entete">	
		</div>
		<?php  
			set_time_limit(2000);
		
			GenererCacheDesProjetsPhoto("../../SOURCES/Sources.csv", $codeSource, $anneeSource);

		?>		
		<div class="titreFichier">	
			Mise en cache des photographies Terminée
			<br><br>
			<a href="<?php echo RetourEcranSources(); ?>" title="Retour à la liste des sources de photos"><img src="img/Logo-Retour.png" alt="Image de fichier"></a><br><br>		
			Vous pouvez retourner à la liste des sources de photos en cliquant sur l'icône ci dessus.
					


		</div>
	</div>

	  <div id="main">
<br>



	</div>
 
</div>

<script type="text/javascript" src="<?php Mini('js/CMDImgSource.js');?>"></script>
<script>
	EffacerChargement();
	AfficheRechercheCMD(true);
</script>

</body>
</html>


<?php 



function GenererCachePhoto($monProjet){ 
	$dir = $monProjet->Dossier . '/*.*{jpg,jpeg}';
	$files = glob($dir,GLOB_BRACE);

	CreationDossier($monProjet->Dossier . '/Cache');

	$affiche_Tableau = '<p>';
		
	//$affiche_Tableau .= '				<table>';	
	foreach($files as $image){ 
		$FichierARefaire = false;
		$posDernier = 1 + strripos($image, '/');
		$FichierSource = substr($image, $posDernier);
		$FichierCache = 'Cache/'. $FichierSource;
		$Dossier = substr($image, 0, $posDernier);
		

		// Vérification que le fichier existe
		if(!file_exists($Dossier . $FichierCache)){
			$FichierARefaire = true	;	
		}
		else{
			//$origin = new DateTime('2023-10-10');
			//$target = date(filemtime($FichierImage));
			 if (filemtime($Dossier . $FichierSource) > filemtime($Dossier . $FichierCache)){
				 $FichierARefaire = true;
					 echo '<br>Source O :'.filemtime($Dossier . $FichierSource);
					 echo '<br>Source C :'.filemtime($Dossier . $FichierCache);				 
				 }
		}		
		if($FichierARefaire){
			resize_img($Dossier . $FichierSource, $Dossier . $FichierCache);	
		}
			 //echo '<br>Source'.filemtime($Dossier . $FichierSource);
			 //echo '<br>Cache'.filemtime($Dossier . $FichierCache);		
	}
}
function GenererCacheDesProjetsPhoto($fichierCSV='../../SOURCES/Sources.csv', $codeProjet='', $anneeProjet=''){ 
	$monProjet = '';
	//En priorité !!
	if ($codeProjet!='' && $anneeProjet!=''){
		$monProjet = ChercherSOURCESEcole($fichierCSV, $codeProjet, $anneeProjet);
		GenererCachePhoto($monProjet);
		//En priorité !!
	}

	if (file_exists($fichierCSV)){
		$TabCSV = csv_to_array($fichierCSV, ';');

		$NbLignes=count($TabCSV);
		//echo 'nb ligne catalog source ' . $NbLignes;
		if ($NbLignes){

			$NbLignes=count($TabCSV);
			for($i = 0; $i < $NbLignes; $i++){ 
				$Dossier = $TabCSV[$i]["DossierSources"];	
				$Dossier = "../.." . urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
				$monProjet = new CProjetSource($TabCSV[$i]["NomProjet"], 
											$Dossier, 
											$codeProjet,
											$anneeProjet,
											$TabCSV[$i]["Rep Scripts PS"]);
				GenererCachePhoto($monProjet);										

			}
		}
	}
	return $monProjet;
}


function ChercherSOURCESEcole($fichierCSV, $codeProjet, $anneeProjet){ 
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
	return $monProjet;		
	}
}

	
function RetourEcranSources(){
	$RetourEcran = 'CATSources.php';	
	return $RetourEcran . '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') ;
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