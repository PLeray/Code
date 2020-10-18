<?php
setlocale(LC_TIME, 'french');
//$codeMembre = false;  //MODIFIER SI NECESSAIRE


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
	<title id="GO-PHOTOLAB">PhotoLab : historique des commandes</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
	<link rel="stylesheet" type="text/css" href="css/CATPhotolab.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<script type="text/javascript" src="js/CATFonctions.js"></script>
	<!-- <script type="text/javascript" src="res/APIConnexion.js"></script>	 -->
</head>

<body>
<!-- 
<p align="center"><iframe width="600" height="137" scrolling= 'no' src="http://localhost/API_photolab/res/drop.php" frameborder="0"></iframe></p>
-->
<div class="logo">
<a href="<?php echo 'index.php' . ArgumentURL(); ?>"><img src="img/LogoHistorique.png" alt="Image de fichier"></a>
</div>

<?php

if (isset($_GET['RECFileLab'])) { // Transformation de l'état d'un fichier lab 
	if ($GLOBALS['isDebug']){echo 'le fichier a transformer : ' .$_GET['RECFileLab'] . ' en : ' .$_GET['RECFileLab']. '0';}
	RECFileLab($_GET['RECFileLab']);
} 
elseif (isset($_GET['apiCMDLAB'])) { // Renvoie les planches à générer du fichier lab en parametre
    //echo API_GetCMDLAB(($_GET['apiCMDLAB']));
}
elseif (isset($_GET['apiChgEtat']) && isset($_GET['apiEtat'])) { 
	ChangeEtat($_GET['apiChgEtat'], $_GET['apiEtat']);
} 

//else echo 'Y A RIEN';

$nb_fichier = 0;
$affiche_Tableau = AfficheTableauCMDLAB($nb_fichier, false);

?>
<BR>
<div class="recherche">	
	<a href="<?php echo 'CATPhotolab.php' . ArgumentURL(); ?>" style="width:auto" class="BoutonVoirenCours" title="Voir les Commandes en cours">Voir les commandes en cours</a>
	<h1>Historique des commandes (nombre : <?php echo $nb_fichier; ?>)</h1>
	<!-- RECHERCHE -->
	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Tapez les premières lettres, pour rechercher une commande..." title="Tapez les premières lettres...">
</div>
<BR>
<div class="zoneTable" >
<!-- ////////// FIN de l'HTML Standard ////////// 
	<table class="Tableau" id="myTableLAB">-->
	<table id="commandes">
	  <tr class="header">
  
		<th style="width:127px;" onclick="sortTable(0)"><H3>Date</H3></th>

		<th onclick="sortTable(1)"><H3>Fichiers de commandes laboratoire</H3></th>
		<th  style="width:110px;" ><H3>Etat</H3></th>
		<th  style="width:90px;" ><H3>Nb de Planches</H3></th>
		<th  style="width:90px;" ><H3>Planches crées</H3></th>
		<th  style="width:90px;" ><H3>Envoyées labo</H3></th>
		<th  style="width:90px;" ><H3>Cartonnage en cours</H3></th>
		<th  style="width:90px;" ><H3>Colis prêt</H3></th>
	  </tr>  
	<?php echo $affiche_Tableau; 
// WEB
	//$nb_fichier = 0;
	//$affiche_Tableau = AfficheTableauCMDWEB($nb_fichier);
	?>
<!-- ////////// FIN de l'HTML Standard 	
	</table>
	<br><br>
	<table class="Tableau" id="myTableWEB">
	  <tr class="header">
		<th style="width:10%;" onclick="sortTable(0)"><H3>Date</H3></th>
		<th style="width:10%;" onclick="sortTable(1)"></th>
		<th style="width:48%;" onclick="sortTable(1)"><H3>Fichiers de presentation, site de vente en ligne (nb : <?php echo $nb_fichier; ?>)</H3></th>
		<th style="width:8%;" onclick="sortTable(2)"><H3>Nb Fichiers</H3></th>		
		<th style="width:8%;" onclick="sortTable(2)"><H3>Uploadés ?</H3></th>
	  </tr>  ////////// -->
	<?php //echo $affiche_Tableau; 
	//echo "isDebug " . $isDebug;
	?>	  
	</table>
	<p>	<?php echo VersionPhotoLab();?> </p>
</div>



</body>
</html>


