<?php
setlocale(LC_TIME, 'french');

$TemporisationDebug = 10;

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

include_once 'APIConnexion.php';
include_once 'CATFonctions.php';

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'CATPhotolab');

$tabFichiersEnCoursDeCompilation = array();

if($isDebug){ 
	header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache');	
}
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>	
	<title id="PHOTOLAB">Commandes en cours de préparation</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'DEBUG':'PROD').'.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CATPhotolab.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/Favicon.png">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/Menu.css');?>">
	<script type="text/javascript" src="<?php Mini('js/CATFonctions.js');?>"></script>
</head>
<?php
	$RechargerPage = false;
	$g_IsLocalMachine = IsLocalMachine();

	$EcoleEnCours = new CEcole("____",'2020-07-07');



	$nb_fichier = 0;
	$affiche_TableauCommandes = AfficheBilanLABOEnCours($nb_fichier, true);
?>



<body>

<?php 
	AfficheMenuPage('commandesEnCours',$maConnexionAPI); 
?>
<!-- 
<p align="center"><iframe width="600" height="137" scrolling= 'no' src="http://localhost/API_photolab/res/drop.php" frameborder="0"></iframe></p>
-->
<div class="logo">
	<a href="<?php echo $maConnexionAPI->Demonstration(); ?>" title="Voir informations sur PhotoLab"><img src="img/Logo-mini.png" alt="Informations sur PhotoLab"></a>
</div>

<!-- <div class="recherche">	
<a href="<?php //echo 'CATHistorique.php' . ArgumentURL(); ?>" style="width:auto" 
	class="BoutonVoirhistorique" title="Rechercher dans l'historique des commandes">Groupes de commandes expédiées...
	<img src="img/LogoHistorique.png" style="width: 50px;" ></a>
</div>
-->
<div class="zoneTable" >
<h1>Groupes de commandes en cours : <?php echo $nb_fichier; ?></H1>    
<!-- ////////// FIN de l'HTML Standard ////////// 
	<table class="Tableau" id="myTableLAB">-->
	<table id="commandes">
	  <tr class="header">
			  
		<th style="width:130px;" onclick="sortTable(0)"><H3>Date</H3></th>

		<th  onclick="sortTable(1)"><H3>Groupes de commandes de produits photo</H3></th>
	
		<th ><H3>Détails par formats </H3></th>	


	  </tr>  
	<?php echo $affiche_TableauCommandes; ?>
	</table>

	<BR>
	<?php // WEB
	$nb_fichier = 0;
	//$affiche_Tableau = AfficheTableauCMDWEB($nb_fichier, true);
	?>
<!-- ////////// FIN de l'HTML Standard 	////////// -->

<!-- ////////// FIN de l'HTML Standard ////////// 
	<table class="Tableau" id="myTableLAB">
	<table id="commandes">
	  <tr class="header">
		<th style="width:110px;"><H3>Etat</H3></th>			  
		<th style="width:110px;" onclick="sortTable(0)"><H3>Date</H3></th>

		<th  onclick="sortTable(1)"><H3>Groupes de commandes de produits photo</H3></th>
	
		<th  style="width:150px;" ><H3>Commandes<br><br>Planches</H3></th>	
		<th  style="width:100px;" ><H3>Mise en pochette</H3></th>	
		<th  style="width:100px;" ><H3>Rechercher dans la commande</H3></th>	
		<th  style="width:90px;" ><H3>Produits crées</H3></th>
		<th  style="width:90px;" ><H3>Envoyées au labo</H3></th>
		<th  style="width:90px;" ><H3>Cartonnage en cours</H3></th>
		<th  style="width:90px;" ><H3>Colis prêt</H3></th>
		<th  style="width:40px;" ><H3>🗑</H3></th>
	  </tr>  
	<?php //echo $affiche_Tableau; ?>
	</table>-->


<!-- ////////// FIN de l'HTML Standard 	////////// -->



	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</div>

<?php
/*
if($isDebug){
	echo '<button type="button" onclick="MAJAffichageBarres()">Raffraichir Barres progressions</button>';
} */
?> 
	<script>
		InitAfficheErreur();	
		//alert('TEST ' ); 
	</script>	
<!-- 
<script>
	// AJAX POUR BARRE DE DEFILEMENT 
	
	setInterval(MAJAffichageBarres, 1000);

function MAJAffichageBarres() {
	//
	console.log('MAJAffichageBarres() ');	
	<?php // WEB
	 /*		
		for($i = 0; $i < count($tabFichiersEnCoursDeCompilation); $i++){ // Récupérer lors de l'affichage du tableau de commandes
			echo "EtatBarreProgressionPour('". $tabFichiersEnCoursDeCompilation[$i] . "');";
		}*/
	?>  
}
</script>	
-->

</body>
</html>