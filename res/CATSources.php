<?php
setlocale(LC_TIME, 'french');

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

include_once 'APIConnexion.php';
include_once 'CMDClassesDefinition.php';



$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug, 'CATSources');

if (isset($_GET['version'])) { 
	//MAJ PHOTOLAB !!!
	if( $GLOBALS['VERSION'] < $_GET['version']){ MAJPhotoLab($_GET['version']);}
	
}

$g_IsLocalMachine = IsLocalMachine();

?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="PHOTOLAB">Sources enregistrées</title>
    <link rel="stylesheet" type="text/css" href="<?php Mini('css/Couleurs'.($isDebug?'':'AMP').'.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/CATSources.css');?>">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
	<link rel="stylesheet" type="text/css" href="<?php Mini('css/Menu.css');?>">
</head>

<body>
<?php AfficheMenuPage('sourcePhotos',$maConnexionAPI); ?>

<div class="logo">
	<a href="<?php echo 'index.php' . ArgumentURL(); ?>" title="Retour à l'acceuil"><img src="img/Logo.png" alt="Image de fichier"></a>
</div>

<?php
if (isset($_GET['OpenRep'])) { // OUVRIR REP !
	$leRep = str_replace("/","\\",$_GET['OpenRep']);
	if ($GLOBALS['isDebug']){
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
	BDDARBOwebfile($_GET['BDDARBOwebfile'], $_GET['BDDRECCode'], $_GET['CodeEcole']);
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

$affiche_Tableau = AfficheTableauSOURCES($nb_fichier, "../../SOURCES/Sources.csv");

?>



<div class="zoneTable" >
<h1>Sources écoles référencées : <?php echo $nb_fichier; ?></H1>    
	<!--<table class="Tableau" id="myTableWEB">-->
	<table id="commandes">
	  <tr class="header" >
		<th style="width:90px;" onclick="sortTable(1)"><H3>Ref Ecole</H3></th>	
		<th style="width:165px;" onclick="sortTable(2)"><H3>Dossier 'actions' Photoshop</H3></th>		
		<th style="width:105px;" onclick="sortTable(0)"><H3>Année scolaire</H3></th>
		<th  onclick="sortTable(1)"><H3>Nom projet</H3></th>	
		<th style="width:200px;" onclick="sortTable(0)"><H3>Contenus Projet<span class="mini"><br><a href="<?php echo 'CMDGenererCacheSource.php' . ArgumentURL(); ?>" title="Mettre à jour le cache des Photos"  target="_blank">>Mettre à jour le cache<</a></span></H3></th>		
		<th  style="width:180px;" ><H3>Fichiers de présentation Web</H3></th>		
	  </tr>  
	<?php echo $affiche_Tableau; 
	//echo "isDebug " . $isDebug;
	?>	  
	</table>
	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</div>

</body>
</html>

<?php


function AfficheTableauSOURCES(&$nb_fichier, $fichierCSV){ 
	$affiche_Tableau = '';
	$Dossier = '';
	if (file_exists($fichierCSV)){
		$TabCSV = csv_to_array($fichierCSV, ';');

		$NbLignes=count($TabCSV);
		//echo 'nb ligne catalog source ' . $NbLignes;
		if ($NbLignes){
			/* A quoi ca sert dsans tri .?*/
			foreach ($TabCSV as $key => $row) {
				$NomProjet[$key] = $row['NomProjet'];
			}
			
			// supr pour Mac qu'en y en a qu'un NE FONCTIONNE PAS 
			array_multisort($NomProjet, SORT_DESC, $TabCSV); // Tri par nom de projet

			$nb_fichier = $NbLignes;
			for($i = 0; $i < $NbLignes; $i++)
			{ 
				$Dossier =$TabCSV[$i]["DossierSources"];
				$Dossier = "../.." . urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
				$affiche_Tableau .=	'<tr>			
			<td class="mini" ><div class="tooltip">' . $TabCSV[$i]["Code"] .'
						<span class="tooltiptext">'. $Dossier . '</span></div></td>				
			<td class="mini" ><div class="tooltip">' . $TabCSV[$i]["Rep Scripts PS"] .'
						<span class="tooltiptext">'. $Dossier . '</span></div></td>	
			<td class="titreCommande" ><div class="tooltip">' . $TabCSV[$i]["Annee"] .'
						<span class="tooltiptext">'. $Dossier . '</span></div></td>		
			<td align="left" class="titreCommande" ><div class="tooltip"><a href="' . LienImgSource($TabCSV[$i]["Code"], $TabCSV[$i]["Annee"]) . '" >' . $TabCSV[$i]["NomProjet"] .'</a>
						<span class="tooltiptext">'. $Dossier . '</span></div></td>						
			<td>'. CodeLienImageDossier($Dossier) . '</td>';				
					
				$isArbo	= file_exists($GLOBALS['repCMDLABO'] .  NomfichierARBO($TabCSV[$i]["NomProjet"]) . '2');
				
				$affiche_Tableau .= '<td><a href="' . LienEtatArbo($TabCSV[$i]["NomProjet"], $TabCSV[$i]["Code"], $isArbo).'"  title="Faire un ensemble de fichier pour presentation web">' . LienImageArbo($isArbo) . '</a></td>';	


					
				$affiche_Tableau .=	'</tr>';	
			}
		}	
	}
	return $affiche_Tableau;
}

function CodeLienImageDossier($Dossier){	
/*	*/
	$Lien = $GLOBALS['g_IsLocalMachine'] ;
	$DossierOK =($Lien )? 'OK':'KO' ;
	$codeHTML = '<div class="containerCMDPLanche">
		<div class="txtCMDPLanche">' . NBfichiersDOSSIER($Dossier) . ' <span class="mini">photos</span><br>' . NBClassesDOSSIER($Dossier) .  ' <span class="mini">classes</span></div>'. '<img src="img/Dossier' . $DossierOK . '.png"></div>';	
	
	if ($Lien) {
		$codeHTML = '<div class="tooltip">
		<a href="' . LienOuvrirDossierOS($Dossier,'CATSources') . '" >' . $codeHTML . '</a>
		<span class="tooltiptext"><br>Cliquez pour aller vers le dossier des photos sources<br><br></span></div>';
		
	}
	$codeHTML = $codeHTML ;
	
	return $codeHTML;

	//<td><div class="tooltip"><a href="' . LienOuvrirDossierOS($Dossier,'CATSources') . '" >' . NBfichiersDOSSIER($Dossier) . '</a></td>';
	
}


function LienImgSource($codeProjet, $anneeProjet) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');

	$LienFichier = "#";

	$LienFichier = "CMDImgSource.php". $Environnement . "&codeSource=" . urlencode($codeProjet). "&anneeSource=" . urlencode($anneeProjet);
	//$isDebug = true;
	return $LienFichier;
}



function NomfichierARBO($NomProjet) {
  return 'ARBO-' . date("Y-m-d") . '-' . $NomProjet. '.web';
}

function NBfichiersDOSSIER($Dossier) {
	$dir = $Dossier . '/*.*{jpg,jpeg}';
	$files = glob($dir,GLOB_BRACE);	
	$NbFicher = count($files);/* Variable $compteur pour compter (count) les fichiers lister ($files) dans le dossier */
	return $NbFicher;
}

function NBClassesDOSSIER($Dossier) {
	$dir = $Dossier . '/*-*-*.*{jpg,jpeg}';
	$files = glob($dir,GLOB_BRACE);	
	$NbFicher = count($files);/* Variable $compteur pour compter (count) les fichiers lister ($files) dans le dossier */
	return $NbFicher;
}
function LienEtatArbo($fichier , $CodeEcole, $isDone ) {
	$fichierARBO = NomfichierARBO($fichier);
	$retourMSG = '#';
	if(! $isDone){
		//$mesInfosFichier = new CINFOfichierLab($target_file); 
		//$CMDAvancement ='';
		$CMDhttpLocal ='';
		//$Compilateur = '';				
		$NBPlanches = NBfichiersDOSSIER($fichier);

		//$NBPlanches = INFOsurFichierLab($target_file, $CMDAvancement, $CMDhttpLocal, $Compilateur);
		//echo "Apres move_uploaded_file";
		$CMDhttpLocal = '&CMDdate=' . substr($fichierARBO, 5, 10);	
		$CMDhttpLocal .= '&CMDwebArbo=' . $NBPlanches;
		$CMDhttpLocal .= '&CodeEcole=' . $CodeEcole;	
		$CMDhttpLocal .= '&BDDFileLab=' . urlencode(utf8_encode(basename(SUPRAccents($fichierARBO))));	
		
		$retourMSG = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal);				
	}	
	return $retourMSG;	

}

function SUPRAccents($str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );    
    return $str;
}

function LienImageArbo($isOK){
	$Lien = ($isOK?'src="img/ArboOK.png" alt="Oui"':'src="img/ArboKO.png" alt="Non"'). ' class="OKKOIMG"';
	//return $Lien;
	return '<img ' . $Lien . '>';
} 

function BDDARBOwebfile($NewFichier, $BDDRECCode, $CodeEcole){

	if ($GLOBALS['isDebug']){
		echo "<br> STOP !<br> ";
		echo '<br><br>' . $NewFichier;
	}
	
	$line ='';
	$strURL_NewFichier = $GLOBALS['repCMDLABO'] . utf8_decode($NewFichier) . "0";	
	
	$file = fopen($strURL_NewFichier, 'w');
		$ligne = '[Version : 2.0' . $BDDRECCode . "\n";
		fputs($file, $ligne);
		$ligne = '{Etat 1 :0%%En Cours....}' . "\n";
		fputs($file, $ligne);    //{Etat 1 :1%%Le groupe de commandes comp....}
		$nomFichier = utf8_decode($NewFichier);
		$ligne =  '@'. substr($nomFichier, 5, 10) .'_'.substr($nomFichier, 16, -4).'_'.$CodeEcole.'_Ecole web !@' . "\n";
		//$ligne =  '@2021-02-26_L2-Ecole TEST-MAROU_'.$CodeEcole.'_Ecole web !@' . "\n";
		fputs($file, $ligne);	 //@2021-02-26_L2-Ecole TEST-MAROU_ACC7_Ecole web !@ 
	fclose($file);	
}


?>
