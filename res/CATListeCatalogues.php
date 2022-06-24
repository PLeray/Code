<?php
setlocale(LC_TIME, 'french');

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

include_once 'APIConnexion.php';
include_once 'CMDClassesDefinition.php';



$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'CATSources');

if (isset($_GET['versionDistante'])) { 
	//MAJ PHOTOLAB !!!
	if( $GLOBALS['VERSIONLOCAL'] < $_GET['versionDistante']){ 
		MAJPhotoLab($_GET['versionDistante']);
		if ($GLOBALS['isDebug']){echo 'Version en ligne : ' .$GLOBALS['VERSIONLOCAL'];}	
	}
}

$g_IsLocalMachine = IsLocalMachine();

?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="PHOTOLAB">Sources enregistrées</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'DEBUG':'PROD').'.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CATListeCatalogues.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/Menu.css');?>">
</head>

<body>
<?php AfficheMenuPage('listeCatalogues',$maConnexionAPI); ?>

<div class="logo">
	<a href="<?php echo $maConnexionAPI->Demonstration(); ?>" title="Voir informations sur PhotoLab"><img src="img/Logo-mini.png" alt="Informations sur PhotoLab"></a>
</div>




<BR>
<div class="zoneTable" >
	<?php
		$nb_fichier = 0;

		$SourcesCSV =  "../../GABARITS/ActionsScriptsPSP.csv";

		$affiche_Tableau = AfficheTableauCATALOGUES($nb_fichier,$SourcesCSV);		
	?>
	<h1>Catalogues produits disponibles : <?php echo $nb_fichier; ?></H1>    

<BR>

<table id="Sources">
	<tr class="header" >
		<th style="width:400px;"><H3>Dossier 'actions' dans Photoshop</H3></th>			
		<th style="width:135px;"><H3>Catalogue produit</H3></th>
		<th  align="left" class="titreProjet"><H4>Nom Catalogue</H4></th>		
	</tr>  
	<?php echo $affiche_Tableau; ?>	  
	</table>
	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</div>
<script type="text/javascript" src="<?php Mini('js/CATSources.js');?>"></script>
</body>
</html>

<?php

function AfficheTableauCATALOGUES(&$nb_fichier, $fichierCSV){ 
	$affiche_Tableau = '';
	if ($file = fopen($fichierCSV, "r")) {
		while(!feof($file)) {
			$line = fgets($file);

			if ($line != ''){
				$nb_fichier++;

				$NomDossiserScript = substr($line,0,strpos($line,';'));
				$NomCatalogue = 'Catalogue'.$NomDossiserScript;

				$ListeScripts = str_replace(';','<br>', substr($line,strpos($line,';')+1));

			//echo ("$line" . "<br>");   //strpos 

			$affiche_Tableau .=	'<tr>
			<td ><div class="tooltip">'. $NomDossiserScript. '
				<span class="tooltiptext">'. $ListeScripts . '</span></div></td>	';

			$affiche_Tableau .= '<td>'. CodeLienCatalogue($NomDossiserScript) . '</td>';
			/**/
			
			$affiche_Tableau .= '<td align="left" class="titreProjet" >
			
			<div class="tooltip">'. $NomCatalogue. '
				<span class="tooltiptext">'. $ListeScripts . '</span></div></td>';	

			$affiche_Tableau .=	'</tr>';	
			}
		}
		fclose($file);
	}  
	return $affiche_Tableau;
}

function CodeLienCatalogue($NomDossiserScript){	
	$ImageLien = 'src="img/btnCatalogue.png"';
	$LienPage = 'CMDCatalogueProduits.php'. ArgumentURL('&NomDossiserScript=' . urlencode($NomDossiserScript));

	return  '<a href="' . $LienPage .'" title="Voir le catalogue produits"><img ' . $ImageLien. 'class="imgArbo"></a>';
	

}



?>
