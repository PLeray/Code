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
	
	
$codeSource = 'qsd';
if (isset($_GET['codeSource'])) { // Test connexion l'API
	$codeSource = $_GET['codeSource'];
}
$anneeSource = 'qsd';
if (isset($_GET['anneeSource'])) { // Test connexion l'API
	$anneeSource = $_GET['anneeSource'];
}


	$affiche_Tableau = AfficheSOURCES("../../SOURCES/Sources.csv", $codeSource, $anneeSource);
	
	
	$DefautNbCMDAffiche = 15;
	$NbCMDAffiche = $DefautNbCMDAffiche;
	if (isset($_GET['nbCmd'])) { $NbCMDAffiche = $_GET['nbCmd'];}
?>
<?php 
if($isDebug){
	header("Cache-Control: no-cache, must-revalidate");
}


?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>


    <title id="PHOTOLAB"><?php echo substr($myfileName,0, -5) ?> : Préparation de commandes</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
    <link rel="stylesheet" type="text/css" href="css/CMDImgSource.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>

	


</head>

<body >
 <!-- 
<div id="chargement" style="width:150px;height:50px;position:absolute;top:0;left:0;color:red;font-weight:bold;font-size:14px;background:white;">
   Chargement ...
</div>-->
<?php



?>

<div id="site"">
   <!-- Tout le site ici -->

	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">Remonter</button>
	


	  <div id="main">
	  
		<div id="ImageClasse">	
		<br><br>
			<?php 	echo $affiche_Tableau;   ?>	
		</div>	<!-- -->


		<div class="footer">
		  <p class="mention">	<?php echo VersionPhotoLab();?> </p>
		</div>

	</div>
 
</div>


</body>
</html>


<?php 



function AfficheSOURCES($fichierCSV, $codeProjet, $anneeProjet){ 
	$affiche_Tableau = '';
	if (file_exists($fichierCSV)){
		$TabCSV = csv_to_array($fichierCSV, ';');

		$NbLignes=count($TabCSV);
		//echo 'nb ligne catalog source ' . $NbLignes;
		if ($NbLignes){

	
		$Dossier='';
		$ScriptsPS='';
		$NomProjet='';
		/*
		foreach ($TabCSV as $key => $row) {
			$NomProjet[$key] = $row['NomProjet'];
		}
		*/
		$NbLignes=count($TabCSV);
		for($i = 0; $i < $NbLignes; $i++){ 
			if ($codeProjet == $TabCSV[$i]["Code"]  && $anneeProjet == $TabCSV[$i]["Annee"]){
				$Annee=$TabCSV[$i]["Annee"];
				$CodeEcole=$TabCSV[$i]["Code"];
				$ScriptsPS=$TabCSV[$i]["Rep Scripts PS"];
				$Dossier=$TabCSV[$i]["Repertoire"];			
				$NomProjet=$TabCSV[$i]["NomProjet"];
				break;
			}
		}
		//$DossierTEST = "../../SOURCES/2020-2021/2021-02-31-Ecole-test-Max-Planck-NANTES" ;	
		//echo  $DossierTEST .'<br />';
		$Dossier = "../.." . urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
		$DossierTEST = urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
		echo  'Dossier Source : ' . $DossierTEST  .'<br />';


	//Quel les fichier avvec" -CADR-"
	$dir = $Dossier . '/*-CADR-*.*{jpg,jpeg}';
	$dir = $Dossier . '/*.*{jpg,jpeg}';
	$files = glob($dir,GLOB_BRACE);

	CreationDossier($Dossier . '/Cache');
	  
	//echo 'Listing des images du repertoire miniatures <br />';


	//<p> <span class=”malettrine”>U</span>ne lettrine historiée etc etc etc.</p>
		$affiche_Tableau = '<p>';
		
	$affiche_Tableau .= '
				<table>';	
	foreach($files as $image){ 
		$posDernier = 1 + strripos($image, '/');
		$FichierSource = substr($image, $posDernier);
		$FichierCache = 'Cache/'. $FichierSource;
		$Dossier = substr($image, 0, $posDernier);
		

		// Vérification que le fichier existe
		if(!file_exists($Dossier . $FichierCache)):
			resize_img($Dossier . $FichierSource, $Dossier . $FichierCache);
		endif;	 
		 

		 $mesSources = new CImgSource($FichierSource, $Dossier, $CodeEcole,$Annee,$ScriptsPS);
		 
		 /**/
		 if ($mesSources->isGroupe()){
			 //echo 'GROUPE';
			 $affiche_Tableau .= '<tr><td style=vertical-align:top>';
			 
		 }


		$affiche_Tableau .= $mesSources->Affiche();
		
		 if ($mesSources->isGroupe()){
			 //echo 'GROUPE';
			 $affiche_Tableau .= '</td><td>';
			 
		 }	
	}



	$affiche_Tableau .= '</td></tr></table>' ;


		//http://peter-msi/PhotoLab/SOURCES/2020-2021/2021-02-31-Ecole-test-Max-Planck-NANTES/Cache/Cache/2001.jpg
		
			$affiche_Tableau .= '</p>';
		}
	}
	return $affiche_Tableau;
}


function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
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