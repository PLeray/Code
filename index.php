<?php
setlocale(LC_TIME, 'french');
//$isAMP = false;  //MODIFIER SI NECESSAIRE


$isAMP = file_exists ('amp.ini');
//if ($isAMP){$isDebug = false;}
$isDebug = file_exists ('debug.txt'); 


include 'res/CATConnexionAPI.php';


$maConnexionAPI = new CConnexionAPI($isAMP,$isDebug);
	
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="GO-PHOTOLAB">PhotoLab : accueil</title>
    <link rel="stylesheet" type="text/css" href="res/css/Couleurs<?php echo ($isAMP)?'AMP':''; ?>.css">
	<link rel="stylesheet" type="text/css" href="res/css/index.css">
	<link rel="shortcut icon" type="image/png" href="res/img/favicon.png"/>
	<!-- <script type="text/javascript" src="res/js/CATFonctions.js"></script>
	<script type="text/javascript" src="res/APIConnexion.js"></script>	 -->
</head>
<!-- <div class="logo">	
		<img src="res/img/Logo.png" alt="Image de fichier">
	</div> -->
<body><center>

<div class="recherche">	
<h1>Phot<img src="res/img/Logo-Ultra-mini.png" width="20">Lab <?php echo $GLOBALS['VERSION'] ?></h1>
</div>
<!-- 
<p align="center"><iframe width="600" height="137" scrolling= 'no' src="http://localhost/API_photolab/res/drop.php" frameborder="0"></iframe></p>
-->


<br><br>

	<a href="<?php echo 'res/PhotolabCMD.php' . ArgumentURL(); ?>"><img src="res/img/PhotoLabCMD.png" alt="Commandes en cours"></a>  
	<a href="<?php echo 'res/Historique.php' . ArgumentURL(); ?>"><img src="res/img/PhotoLabHISTO.png" alt="Historique des commandes"></a>
	<a href="<?php echo $maConnexionAPI->Adresse(); ?>"><img src="res/img/PhotoLabFACT.png" alt="Commandes Enregistrées"></a>	
	<br><br><br><br>
		<!-- <div id="ajoutFICHIER" style="text-align:center">-->
			<div id="dropArea"><br>Glisser déposer un fichier commandes dans cette zone.<br>
				Soit un fichier (.lab ou .web) créé par ProdEcole.<br>
				Soit un fichier (.csv) issu du site de vente en ligne Lumys.
				<span id="count"></span>
				<div id="result"></div>				
			</div>
			<script src="res/js/drop.js"></script>
			
		<!-- <div id="dropArea">
			Déposer un fichier de commandes (.lab) ici.
			<?php //echo 'maConnexionAPI->Adresse = ' . $maConnexionAPI->Adresse(); ?>
			<div id="result"></div>
			<canvas width="500" height="20"></canvas>			
		</div>
		<div>-->
		<br><br><br><p>	<?php echo VersionPhotoLab();?> </p>
	</center></body>
</html>
