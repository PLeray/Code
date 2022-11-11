<?php
include_once 'APIConnexion.php';

$codeMembre = '0';
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'index');


?>

<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="PHOTOLAB">PhotoLab : accueil</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'DEBUG':'PROD').'.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/MAJPhotoLab.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/Menu.css');?>">
</head>



<body onload="EffacerChargement()">
<div id="MSGChargement" onclick="EffacerChargement()"> 
	<div class="cs-loader">
	
	  <div class="cs-loader-inner">
	  <H5>Chargement de la commande <?php //echo $myfileName;?></H5>
	  <br>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<label>●</label>
		<br>
		<br>
		<H5>Cliquez pour voir :  <?php //echo $myfileName;?></H5>
	  </div>
	</div>
</div> 
<div id="site2">
   <!-- Tout le site ici -->


<?php AfficheMenuPage('',$maConnexionAPI); ?>

<div class="logo">
	<a href="<?php echo $maConnexionAPI->Demonstration(); ?>" title="Voir informations sur PhotoLab"><img src="img/Logo-mini.png" alt="Informations sur PhotoLab"></a>
</div>


	<center>
	<h1>Phot<img src="img/Logo.png" width="20">Lab <?php echo $GLOBALS['ANNEE'] ?></h1>
		
		<?php 

	if ($isDebug ) { 
		echo "GLOBALS[VERSIONLOCAL] (Version actuelle) : " . $GLOBALS['VERSIONLOCAL'] . " >>>> GET[versionDistante]: (y a t il une version a recupérer ?)  ";
		if (isset($_GET['versionDistante'])) { 	echo $_GET['versionDistante'];} 
		else { echo 'PAS DE VERSION';}

			$localURL =  '<br> http//'.$_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] ;	
			echo $localURL;
			echo '<br> ServeurLocal : ' . ServeurLocal();	

			
		} 
		?>
		
		
		

		<img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg">
		
		<br><br>


		<h3><?php //echo RecupCODE($maConnexionAPI->URL) ?></h3><br>

		


<br><br>
		<p>	<?php echo VersionPhotoLab();?> </p>
	</center>
	<!-- <script src="js/drop.js"></script> -->
	
	</div>	


	<script type="text/javascript" src="<?php Mini('js/MAJPhotoLab.js');?>"></script>
	
</body>
</html>

<?php 
function RecupCODE($urlBase){
	$commentaire ='';
	$msgTelechargement ='';
	if(TelechargerFichier('Code.zip', $urlBase, $msgTelechargement)){ 
		$commentaire .= "Téléchargement des fichiers : OK " . '<br><br>' . $msgTelechargement; 				
	} 
	else { 
		$commentaire .= "Téléchargement des fichiers : Echec ! " . '<br><br>' . $msgTelechargement; 	
	}
	$commentaire .= '<br><br>';	
	return $commentaire;
}

//  header('Location: index.php'. ArgumentURL());

function TelechargerFichier($nomFichier, $urlBase, &$msgDezip){
	$retour = false;
	if ($nomFichier != ''){
		$url = $urlBase . '/installation/PhotoLab/' . $nomFichier ;
		//echo $url . ' : ';	

		//$fichier_contenu = file_get_contents($url);
		
		if(file_exists ('../../NO-MAJ-Auto.txt')){
			$dossier_enregistrement = "../../telechargement/";
		}else{
			$dossier_enregistrement = "../../";
		}
		CreationDossier(dirname($dossier_enregistrement . $nomFichier)).'<br><br>';

		//$retour = file_put_contents($dossier_enregistrement . $nomFichier, $fichier_contenu);
		$retour = TransfertFichier($url, $dossier_enregistrement . $nomFichier);
		
		//Supression des rep Css json_decode
		SuprArborescenceDossier($dossier_enregistrement . 'Code/res/css');
		SuprArborescenceDossier($dossier_enregistrement . 'Code/res/js');		
		
		$msgDezip = DezipperFichier($dossier_enregistrement , $nomFichier);
		if(!file_exists ('../../NO-MAJ-Auto.txt')){
			// A REMETTRE unlink($dossier_enregistrement . $nomFichier);
		}		
	}
	return $retour;
}

function TransfertFichier($urlInitiale, $urlFinale){
	$retour = Curl_get_file_contents($urlInitiale);
	if($retour){file_put_contents($urlFinale, $retour);}
	return $retour ; 
}

function Curl_get_file_contents( $URL ){
	$curl_handle=curl_init();
	curl_setopt($curl_handle, CURLOPT_URL,$URL);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_handle, CURLOPT_USERAGENT, 'PhotoLab');
	$query = curl_exec($curl_handle);
	curl_close($curl_handle);
	if( $query ) :
		return $query;
	else:
		return false;
	endif;	
}


function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}

function DezipperFichier($Dossier, $fichier) {
	$MSG ="";
	$zip = new ZipArchive;
	if ($zip->open($Dossier.$fichier) === TRUE) {
		$zip->extractTo($Dossier);
		$zip->close();
		$MSG = 'Extraction des fichiers : OK ';
	} else {
		$MSG = 'Extraction des fichiers : Echec ! ';
	}
	return $MSG;
}	

?> 
