<?php
setlocale(LC_TIME, 'french');

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

include 'APIConnexion.php';
include 'CATFonctions.php';

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug);

?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<META HTTP-EQUIV="Refresh" CONTENT="10; URL=<?php echo 'CATPhotolab.php' . ArgumentURL(); ?>">
	<title id="GO-PHOTOLAB">PhotoLab : commandes en cours</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
	<link rel="stylesheet" type="text/css" href="css/CATPhotolab.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
	<!-- <script type="text/javascript" src="res/js/CATFonctions.js"></script>
	<script type="text/javascript" src="res/APIConnexion.js"></script>	 -->
	
</head>

<body>
<!-- 
<p align="center"><iframe width="600" height="137" scrolling= 'no' src="http://localhost/API_photolab/res/drop.php" frameborder="0"></iframe></p>
-->
<div class="logo">
	<a href="<?php echo 'index.php' . ArgumentURL(); ?>" title="Retour à l'acceuil"><img src="img/Logo.png" alt="Image de fichier"></a>
</div>

<?php

$lesRecommandes = '';
if (isset($_POST['lesRecommandes']) ){
	$lesRecommandes = $_POST['lesRecommandes'];
	if ($isDebug){
		echo ' ----------------- VOILA LES RECOMMANDES SELECTIONNEES  ------------- : ' . $lesRecommandes;
	}	
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
elseif (isset($_GET['BDDARBOwebfile'])) { // Renvoie les planches à générer du fichier lab en parametre
    BDDARBOwebfile($_GET['BDDARBOwebfile'], $_GET['BDDRECCode']);
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


<BR><BR><BR>
<div class="recherche">	
<a href="<?php echo 'CATHistorique.php' . ArgumentURL(); ?>" style="width:auto" 
	class="BoutonVoirhistorique" title="Rechercher dans l'historique des commandes">Voir l'historique des commandes
	<img src="img/LogoHistorique.png" style="width: 50px;" ></a>
</div>

<h1>Commandes en cours de cartonnage : <?php echo $nb_fichier; ?></H1>    
<div class="zoneTable" >
<!-- ////////// FIN de l'HTML Standard ////////// 
	<table class="Tableau" id="myTableLAB">-->
	<table id="commandes">
	  <tr class="header">
		<th style="width:127px;" onclick="sortTable(0)"><H3>Date</H3></th>
		<th  onclick="sortTable(1)"><H3>Commandes de planches pour laboratoire</H3></th>
		<th style="width:110px;"><H3>Etat</H3></th>
		<th  style="width:100px;" ><H3>Nb de Commandes</H3></th>	
		<th  style="width:100px;" ><H3>Nb de Planches</H3></th>	
		<th  style="width:90px;" ><H3>Planches crées</H3></th>
		<th  style="width:90px;" ><H3>Envoyées au labo</H3></th>
		<th  style="width:90px;" ><H3>Cartonnage en cours</H3></th>
		<th  style="width:90px;" ><H3>Colis prêt</H3></th>
		<th  style="width:40px;" ><H3>X</H3></th>
	  </tr>  
	<?php echo $affiche_Tableau; ?>
	</table>
</div>
	<BR>
	<?php // WEB
	$nb_fichier = 0;
	$affiche_Tableau = AfficheTableauCMDWEB($nb_fichier, true);
	?>
<!-- ////////// FIN de l'HTML Standard 	////////// -->

<div class="zoneTable" >
	<h1>Fichiers de présentation pour le WEB : <?php echo $nb_fichier; ?></H1>
	<!--<table class="Tableau" id="myTableWEB">-->
	<table id="commandes">
	  <tr class="header" >
		<th style="width:127px;" onclick="sortTable(0)"><H3>Date</H3></th>
		<th  onclick="sortTable(1)"><H3>Commandes de fichiers de presentation WEB</H3></th>
		<th style="width:110px;" onclick="sortTable(1)"><H3>Etat</H3></th>			
		<th style="width:100px;" onclick="sortTable(2)"><H3>Nb Fichiers</H3></th>					
		<th  style="width:182px;" ><H3>Fichiers Web crées</H3></th>		
		<th style="width:182px;" onclick="sortTable(2)"><H3>Déposés sur LUMYS</H3></th>
		<th  style="width:40px;" ><H3>X</H3></th>		
	  </tr>  
	<?php echo $affiche_Tableau; 
	//echo "isDebug " . $isDebug;
	?>	  
	</table>
	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</div>



</body>
</html>


