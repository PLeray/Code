<?php
setlocale(LC_TIME, 'french');

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

include_once 'APIConnexion.php';
include_once 'CATFonctions.php';

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'CATPhotolab');

$tabFichiersEnCoursDeCompilation = array();

?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>

<?php 
if($isDebug){}
	header("Cache-Control: no-cache, must-revalidate");

	// ou header("Expires: -1");  ???

?>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="<?php // echo ($isDebug?'5':'10'); ?>; URL=<?php //echo 'CATPhotolab.php' . ArgumentURL(); ?>">-->
	<title id="PHOTOLAB">Commandes en cours</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CATPhotolab.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/Menu.css');?>">
	<script type="text/javascript" src="<?php Mini('js/CATFonctions.js');?>"></script>
	<script>
		InitAfficheErreur();	
		//alert('TEST ' ); 
	</script>	
</head>

<body>
<?php AfficheMenuPage('commandesEnCours',$maConnexionAPI); ?>
<!-- 
<p align="center"><iframe width="600" height="137" scrolling= 'no' src="http://localhost/API_photolab/res/drop.php" frameborder="0"></iframe></p>
-->
<div class="logo">
	<a href="<?php echo 'index.php' . ArgumentURL(); ?>" title="Retour à l'acceuil"><img src="img/Logo.png" alt="Image de fichier"></a>
</div>

<?php
	$g_IsLocalMachine = IsLocalMachine();
	//$GLOBALS['repTIRAGES'] = $GLOBALS['repTIRAGES'];
	//$GLOBALS['repCMDLABO'] = $GLOBALS['repCMDLABO'];
	//$GLOBALS['repMINIATURES'] = $GLOBALS['repMINIATURES'];
	
/*
	$monGroupeCmdes = new CGroupeCmdes($GLOBALS['repCMDLABO'].$myfileName);	
	$numeroCMD = (isset($_GET['numeroCMD'])) ? $_GET['numeroCMD'] :'1';
*/	

	$EcoleEnCours = new CEcole("___",'2020-07-07');

	$lesRecommandes = '';
	if (isset($_POST['lesRecommandes']) ){
		$lesRecommandes = $_POST['lesRecommandes'];
		if ($isDebug){
			echo 'VOILA LES RECO  pour : ' . $_POST['leFichierOriginal']  . ' : ' . $lesRecommandes;
		}	
		MAJRecommandes($_POST['leFichierOriginal'], $_POST['lesRecommandes']);
	}

	if (isset($_GET['OpenRep'])) { // OUVRIR REP !
		$leRep = str_replace("/","\\",$repTIRAGES. $_GET['OpenRep']);
		if ($isDebug){
			echo 'le rep  à ouvrir : explorer /select,"'.$leRep.'"' ;
		}
		execInBackground('explorer /select,"'.$leRep.'"');
	} 
	if (isset($_GET['BDDRECFileLab'])) { // Transformation de l'état d'un fichier lab 
		if ($GLOBALS['isDebug']){
			echo 'le fichier a transformer : ' . $_GET['BDDRECFileLab'] . ' en : ' . $_GET['BDDRECFileLab'] . '0';
			}
		BDDRECFileLab($_GET['BDDRECFileLab'], $_GET['BDDRECCode']);
	} 

	elseif (isset($_GET['apiCMDLAB'])) { // Renvoie les planches à générer du fichier lab en parametre
		//echo API_GetCMDLAB(($_GET['apiCMDLAB']));
	}
	elseif (isset($_GET['apiChgEtat']) && isset($_GET['apiEtat'])) { 
		ChangeEtat($_GET['apiChgEtat'], $_GET['apiEtat']);
	} 
	elseif (isset($_GET['apiSupprimer'])) { 
		SuprimeFichier($_GET['apiSupprimer']);
	} 
	//else echo 'Y A RIEN';

	$nb_fichier = 0;
	$affiche_Tableau = AfficheTableauCMDLAB($nb_fichier, true);
?>



<!-- <div class="recherche">	
<a href="<?php //echo 'CATHistorique.php' . ArgumentURL(); ?>" style="width:auto" 
	class="BoutonVoirhistorique" title="Rechercher dans l'historique des commandes">Groupes de commandes expédiées...
	<img src="img/LogoHistorique.png" style="width: 50px;" ></a>
</div>
-->
<div class="zoneTable" >
<h1>Groupes de commandes en cours de préparation : <?php echo $nb_fichier; ?></H1>    




<!-- ////////// FIN de l'HTML Standard ////////// 
	<table class="Tableau" id="myTableLAB">-->
	<table id="commandes">
	  <tr class="header">
		<th style="width:110px;"><H3>Etat</H3></th>			  
		<th style="width:110px;" onclick="sortTable(0)"><H3>Date</H3></th>

		<th  onclick="sortTable(1)"><H3>Groupes de commandes de planches</H3></th>
		<th  style="width:150px;" ><H3>Commandes<br><br>Planches</H3></th>	
		<th  style="width:90px;" ><H3>Planches crées</H3></th>
		<th  style="width:90px;" ><H3>Envoyées au labo</H3></th>
		<th  style="width:90px;" ><H3>Cartonnage en cours</H3></th>
		<th  style="width:90px;" ><H3>Colis prêt</H3></th>
		<th  style="width:40px;" ><H3>🗑</H3></th>
	  </tr>  
	<?php echo $affiche_Tableau; ?>
	</table>

	<BR>
	<?php // WEB
	$nb_fichier = 0;
	$affiche_Tableau = AfficheTableauCMDWEB($nb_fichier, true);
	?>
<!-- ////////// FIN de l'HTML Standard 	////////// -->


	<h1>Ensemble de fichiers pour présentation web : <?php echo $nb_fichier; ?></H1>
	<!--<table class="Tableau" id="myTableWEB">-->
	<table id="commandes">
	  <tr class="header" >
		<th style="width:110px;" onclick="sortTable(1)"><H3>Etat</H3></th>		  
		<th style="width:110px;" onclick="sortTable(0)"><H3>Type</H3></th>
			
		<th  onclick="sortTable(1)"><H3>Projet Source</H3></th>	
		<th style="width:100px;" onclick="sortTable(2)"><H3>Nb Fichiers</H3></th>					
		<th  style="width:122px;" ><H3>Fichiers Web crées</H3></th>		
		<th style="width:122px;" onclick="sortTable(2)"><H3>Déposés sur LUMYS</H3></th>
		<th style="width:122px;" onclick="sortTable(2)"><H3>Ventes en cours</H3></th>
		<th  style="width:40px;" ><H3>X</H3></th>		
	  </tr>  
	<?php echo $affiche_Tableau; 
	//echo "isDebug " . $isDebug;
	?>	  
	</table>
	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</div>

<?php
if($isDebug){
	echo '<button type="button" onclick="MAJAffichageBarres()">Change Content</button>';
}
?> 
<script>
setInterval(MAJAffichageBarres, 1000);

function MAJAffichageBarres() {
	//alert('ID ' );
	<?php // WEB /**/		
		for($i = 0; $i < count($tabFichiersEnCoursDeCompilation); $i++){
			echo "EtatBarreProgressionPour('". $tabFichiersEnCoursDeCompilation[$i] . "');";
		}
	?>  
}
</script>	

</body>
</html>