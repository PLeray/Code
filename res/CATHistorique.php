<?php
setlocale(LC_TIME, 'french');
//$codeMembre = false;  //MODIFIER SI NECESSAIRE


$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

include_once 'APIConnexion.php';
include_once 'CATFonctions.php';

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'CATHistorique');

?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="PHOTOLAB">Historique des commandes</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CATPhotolab.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<script type="text/javascript" src="<?php Mini('js/CATFonctions.js');?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/Menu.css');?>">
</head>

<body>
<?php AfficheMenuPage('commandesExpediees',$maConnexionAPI); ?>
<div class="logo">
	<a href="<?php echo 'index.php' . ArgumentURL(); ?>" title="Retour à l'acceuil"><img src="img/Logo.png" alt="Image de fichier"></a>
</div>
<!-- 
<p align="center"><iframe width="600" height="137" scrolling= 'no' src="http://localhost/API_photolab/res/drop.php" frameborder="0"></iframe></p>

<div class="logo">
<a href="<?php //echo 'index.php' . ArgumentURL(); ?>"><img src="img/LogoHistorique.png" alt="Image de fichier"></a>
</div>-->

<?php
	$g_IsLocalMachine = IsLocalMachine();
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

<div class="recherche">	
	<!-- <a href="" style="width:auto" class="BoutonVoirenCours" title="Voir les Commandes en cours">Voir les commandes en cours</a> -->

	<!-- RECHERCHE -->
	<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Tapez les premières lettres, pour rechercher une commande..." title="Tapez les premières lettres...">
</div>
<BR><BR><BR>
<div class="zoneTable" >
	<h1>Historique des groupes de commandes (nombre : <?php echo $nb_fichier; ?>)</h1>
<!-- ////////// FIN de l'HTML Standard ////////// 
	<table class="Tableau" id="myTableLAB">-->
	<table id="commandes">
	  <tr class="header">
		<th  style="width:110px;" ><H3>Etat</H3></th>  
		<th style="width:127px;" onclick="sortTable(0)"><H3>Date</H3></th>

		<th onclick="sortTable(1)"><H3>Groupes de commandes de planches</H3></th>
		<th  style="width:100px;" ><H3>Commandes<br><br>Planches</H3></th>	
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


