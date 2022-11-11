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
	
	$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'index');



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
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/CMDRecherche.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	
<?php
	

	

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
<div id="site">
   <!-- Tout le site ici -->

	<button onclick="topFunction()" id="btnRemonter" title="Revenir en haut de la page">↑ Remonter ↑</button>
	
	<div id="Entete">	
		<div class="logo"><a href="<?php echo RetourEcranFichier($myfileName); ?>" title="Retour à la liste des commandes"><img src="img/Logo-Retour.png" alt="Image de fichier"></a>
		</div>
		

		
		<div class="titreFichier">	
			<?php 	
				echo pathinfo($myfileName)['filename'];
				//echo $isRECOmmandes?'true':'false'. " sgsfdgdfg" ; 				
			?>
			
		</div>
		


	</div>

	  

		<div id="main">
		<div id="zoneRechercheCMD">	
		<br><br>

			<img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg"><img src="img/gros.jpg">
			
			
			
		<br><br>
		</div>	

		<div class="footer">
		  <p class="mention">	<?php echo VersionPhotoLab();?> </p>
		</div>

	</div>
 
</div>



<script type="text/javascript" src="<?php Mini('js/CMDRecherche.js');?>"></script>
<!--<script type="text/javascript" src="<?php //Mini('js/purePajinate.js');?>"></script>-->



</body>
</html>

<h3><?php RecupCODE($maConnexionAPI->URL) ?></h3><br>

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
