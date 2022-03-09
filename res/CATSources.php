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
	if($GLOBALS['VERSION'] < $_GET['version']){ MAJPhotoLab($_GET['version']);}
	
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
	<a href="<?php echo $maConnexionAPI->Demonstration(); ?>" title="Voir informations sur PhotoLab"><img src="img/Logo-mini.png" alt="Informations sur PhotoLab"></a>
</div>



<?php
if (isset($_GET['OpenRep'])) { // OUVRIR REP !
	$leRep = str_replace("/","\\",$_GET['OpenRep']);
	if ($GLOBALS['isDebug']){
		echo 'le rep  à ouvrir : explorer /select,"'.$leRep.'"' ;
	}
	execInBackground('explorer /select,"'.$leRep.'"');
} 

$AnneeScolaire = '2021-2022';
if (isset($_GET['AnneeScolaire'])) { $AnneeScolaire = $_GET['AnneeScolaire'];}


/**/
if (isset($_GET['BDDRECFileLab']) && !isset($_GET['BDDRECCode']) && !isset($_GET['CodeEcole']) ) { // Transformation de l'état d'un fichier lab 
	if ($GLOBALS['isDebug']){
		echo 'le fichier a transformer : ' . $_GET['BDDRECFileLab'] . ' en : ' . $_GET['BDDRECFileLab'] . '0';
		}
	BDDRECFileLab($_GET['BDDRECFileLab'], $_GET['BDDRECCode']);
}else 

if (isset($_GET['BDDARBOwebfile'])) { // Renvoie les planches à générer du fichier lab en parametre
	$CMDwebArbo = '';
	if (isset($_GET['CMDwebArbo']) ){
		$CMDwebArbo = $_GET['CMDwebArbo'];
		if ($isDebug){
			echo '<br><br>VOILA LES $_GET[CMDwebArbo  : ' . $CMDwebArbo;
		}	
	}
	BDDARBOwebfile($_GET['BDDARBOwebfile'], $_GET['BDDRECCode'], $_GET['CodeEcole'], $_GET['AnneeScolaire'], $CMDwebArbo);
}

if (isset($_GET['BDDRECFileLab']) && isset($_GET['BDDRECCode']) && isset($_GET['CodeEcole']) ) { // Transformation de l'état d'un fichier lab 
	if ($GLOBALS['isDebug']){
		echo 'le fichier avec ces commandes : ' . $_GET['BDDRECFileLab'] . ' en : ' . $_GET['BDDRECFileLab'] . '0';
		}
	BDDLibreRECFileLab($_GET['BDDRECFileLab'], $_GET['BDDRECCode'], $_GET['CodeEcole'], isset($_GET['AnneeScolaire']), $CMDwebArbo);
}


?>
<div class="majCache">
	<span class="mini"><br><a href="<?php echo 'CMDGenererCacheSource.php' . ArgumentURL(); ?>" title="Mettre à jour le cache des Photos"  target="_blank">Mettre à jour le cache</a></span>
</div>

<BR>
<div class="zoneTable" >
	<?php
	$nb_fichier = 0;

	$SourcesCSV =  "../../SOURCES/Sources.csv";
	
	$affiche_Saison = RecuptTableauAnneeSOURCES($SourcesCSV,$AnneeScolaire);
	
	$affiche_Tableau = AfficheTableauSOURCES($nb_fichier,$SourcesCSV,$AnneeScolaire);	
	
	?>
	<div class="titreFichier">Saisons : <?php echo $affiche_Saison; ?></div>  
	<h1>Sources écoles référencées : <?php echo $nb_fichier; ?></H1>    

	<BR><BR>
		<div class="recherche">	
	<!-- RECHERCHE -->
	<input type="text" id="myInput" onkeyup="MonFiltreSource()" placeholder="Tapez les premières lettres, pour rechercher une source de photos..." title="Tapez les premières lettres...">
</div>



	<table id="commandes">
	<tr class="header" >
		<th style="width:90px;" onclick="sortTable(1)"><H3>Ref Ecole</H3></th>	
		<th style="width:165px;" onclick="sortTable(2)"><H3>Dossier 'actions' Photoshop</H3></th>		
		<th style="width:105px;" onclick="sortTable(0)"><H3>Année scolaire</H3></th>
		<th  onclick="sortTable(1)"><H3>Nom projet</H3></th>	
		<th style="width:200px;" onclick="sortTable(0)"><H3>Contenus Projet</H3></th>		
		<th  style="width:180px;" ><H3>Fichiers de présentation Web</H3></th>		
	</tr>  
	<?php echo $affiche_Tableau; ?>	  
	</table>
	<p class="mention">	<?php echo VersionPhotoLab();?> </p>
</div>
<script type="text/javascript" src="<?php Mini('js/CATSources.js');?>"></script>
</body>
</html>

<?php
function RecuptTableauAnneeSOURCES($fichierCSV, $SelectSaison){ 
	//$AnneeScolaire ='';
	$affiche_Tableau = '';
	$Dossier = '';
	if (file_exists($fichierCSV)){
		$TabCSV = csv_to_array($fichierCSV, ';');

		$NbLignes=count($TabCSV);
		if ($NbLignes){
			/* A quoi ca sert dsans tri .?*/
			foreach ($TabCSV as $key => $row) {
				$AnneeProjet[$key] = $row['AnneeScolaire'];
			}
			array_multisort($AnneeProjet, SORT_DESC, $TabCSV); // Tri par Annee de projet		
			$lesSaisons = array_unique($AnneeProjet);


			foreach ($lesSaisons as $laSaison) {
				if($laSaison == $SelectSaison){
					$affiche_Tableau .=	'<strong>| ' . $laSaison . ' |</strong>';
				}else{
					
					$lienSaison = 'CATSources.php' . ArgumentURL('&AnneeScolaire='.$laSaison);
					$lienSaison = '| <a href="'.$lienSaison. '" title="Voir la saison '.$laSaison .' ">'.$laSaison.'</a> |';
					$affiche_Tableau .=	$lienSaison;	
				}					
			}							
		}
	}
	return $affiche_Tableau;
}

function AfficheTableauSOURCES(&$nb_fichier, $fichierCSV, $AnneeScolaire){ 
	//$AnneeScolaire ='2021-2022';
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
			$nbFichier = 0;
			$nb_fichier = $NbLignes;
			for($i = 0; $i < $NbLignes; $i++)
			{ 
				if ($AnneeScolaire == $TabCSV[$i]["AnneeScolaire"]){
						$Dossier =$TabCSV[$i]["DossierSources"];
						$Dossier = "../.." . urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
						$affiche_Tableau .=	'<tr>			
					<td class="mini" ><div class="tooltip">' . $TabCSV[$i]["Code"] .'
								<span class="tooltiptext">'. $Dossier . '</span></div></td>				
					<td class="mini" ><div class="tooltip">' . $TabCSV[$i]["Rep Scripts PS"] .'
								<span class="tooltiptext">'. $Dossier . '</span></div></td>	
					<td class="titreCommande" ><div class="tooltip">' . $TabCSV[$i]["AnneeScolaire"] .'
								<span class="tooltiptext">'. $Dossier . '</span></div></td>		
					<td align="left" class="titreCommande" ><div class="tooltip"><a href="' . LienImgSource($TabCSV[$i]["Code"], $TabCSV[$i]["AnneeScolaire"]) . '" >' . $TabCSV[$i]["NomProjet"] .'</a>
								<span class="tooltiptext">'. $Dossier . '</span></div></td>';						
					$nbFichier = NBfichiersDOSSIER($Dossier);
					$affiche_Tableau .= '<td>'. CodeLienImageDossier($Dossier,$nbFichier) . '</td>';				
							
					$affiche_Tableau .= '<td>' . LienImageArbo($TabCSV[$i]["NomProjet"], $TabCSV[$i]["Code"],$nbFichier).'</td>';	

					$affiche_Tableau .=	'</tr>';	
				}
			}
		}	
	}
	return $affiche_Tableau;
}

function CodeLienImageDossier($Dossier, $nbFichier){	
/*	*/
	$Lien = $GLOBALS['g_IsLocalMachine'] ;
	$DossierOK =($Lien )? 'OK':'KO' ;
	$codeHTML = '<div class="containerCMDPLanche">
		<div class="txtCMDPLanche">' . $nbFichier . ' <span class="mini">photos</span><br>' . NBClassesDOSSIER($Dossier) .  ' <span class="mini">classes</span></div>'. '<img src="img/Dossier' . $DossierOK . '.png"></div>';	
	
	if ($Lien) {
		$codeHTML = '<div class="tooltip">
		<a href="' . LienOuvrirDossierOS($Dossier,'CATSources') . '" >' . $codeHTML . '</a>
		<span class="tooltiptext"><br>Cliquez pour aller vers le dossier des fichiers<br><br></span></div>';
		
	}
	$codeHTML = $codeHTML ;
	
	return $codeHTML;
}


function LienImgSource($codeProjet, $anneeProjet) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');

	$LienFichier = "#";

	$LienFichier = "CMDImgSource.php". $Environnement . "&codeSource=" . urlencode($codeProjet). "&anneeSource=" . urlencode($anneeProjet);
	//$isDebug = true;
	return $LienFichier;
}



function NomfichierARBO($NomProjet) {
  //return 'ARBO-' . date("Y-m-d") . '-' . $NomProjet. '.web';
  return 'ARBO-' . $NomProjet. '.web';
}

function NBfichiersARBOWEB($Dossier) {
	$NbFicher=0;
	$files = glob($Dossier . '/*',GLOB_BRACE);	
	foreach($files as $SousDossier) {
		$NbFicher = NBfichiersDOSSIER($SousDossier);
		$NbFicher = count(glob($Dossier . '/' . $SousDossier . '/*.*{jpg,jpeg}',GLOB_BRACE));
	}
	return $NbFicher;
}

function NBfichiersDOSSIER($Dossier) {
	$files = glob($Dossier . '/*.*{jpg,jpeg}',GLOB_BRACE);	
	$NbFicher = count($files);/* Variable $compteur pour compter (count) les fichiers lister ($files) dans le dossier */
	return $NbFicher;
}

function NBClassesDOSSIER($Dossier) {
	$files = glob($Dossier . '/*-*-*.*{jpg,jpeg}',GLOB_BRACE);	
	$NbFicher = count($files);/* Variable $compteur pour compter (count) les fichiers lister ($files) dans le dossier */
	return $NbFicher;
}


function LienEtatArbo($fichier , $CodeEcole, $nbFichier) {
	$isDone	= file_exists($GLOBALS['repCMDLABO'] .  NomfichierARBO($fichier) . '2');
	$fichierARBO = NomfichierARBO($fichier);
	$retourMSG = '#';
	if(! $isDone){
		$CMDhttpLocal ='';			
		$CMDhttpLocal .= '&CMDdate=' . date("Y-m-d"); 
		$CMDhttpLocal .= '&CMDnbPlanches=' . $nbFichier;
		
		$CMDhttpLocal .= '&CMDwebArbo='. urlencode('ARBO');
		$CMDhttpLocal .= '&CodeEcole=' . $CodeEcole;
		$CMDhttpLocal .= '&AnneeScolaire=' . $GLOBALS['AnneeScolaire'] ;  
		//$CMDhttpLocal .= '&CMDwebArbo=' . urlencode(utf8_encode(basename(SUPRAccents($fichierARBO))));		
		$CMDhttpLocal .= '&BDDARBOwebfile=' . urlencode(utf8_encode(basename(SUPRAccents($fichierARBO))));	
		
		$retourMSG = $GLOBALS['maConnexionAPI']->CallServeur($CMDhttpLocal);				
	}	
	return $retourMSG;	
}

function LienImageArbo($fichier, $CodeEcole, $nbFichier) {
	$ImageLien = '';
	$title = '';
	$LienPage ='';	
	$isOn	= file_exists($GLOBALS['repCMDLABO'] .  NomfichierARBO($fichier) . '0');
	$isDone	= isArboPrete($fichier);
	//echo  '<br> isDone : ' . $isDone;
	if ($isDone){
		$ImageLien = 'src="img/ArboOK.png"';
		if ($GLOBALS['g_IsLocalMachine']) {
			$LienPage = LienOuvrirDossierOS($GLOBALS['repWEBARBO'] .'ARBO-' . $fichier,'CATSources');
			$title = 'Voir : Ensemble de fichier pour presentation web';
		}else{ 
			$title = 'les fichier sont pret surl la machine "serveur"';
		}
	} elseif($isOn){
		$ImageLien = 'src="img/ArboON.png"';
		$title = 'Commande en cours : Ensemble de fichier pour presentation web';
		$LienPage = 'CATPhotolab.php' . ArgumentURL();	
	}else{
		$ImageLien = 'src="img/ArboKO.png"';
		$title = 'Faire : Ensemble de fichier pour presentation web';				

		$LienPage = '&CMDdate=' . date("Y-m-d"); 
		$LienPage .= '&CMDnbPlanches=' . $nbFichier;

		$LienPage .= '&CMDwebArbo='. urlencode('ARBO');
		$LienPage .= '&CodeEcole=' . $CodeEcole;
		$LienPage .= '&AnneeScolaire=' . $GLOBALS['AnneeScolaire'] ;  	
		$LienPage .= '&BDDARBOwebfile=' . urlencode(utf8_encode(basename(SUPRAccents(NomfichierARBO($fichier)))));	
		
		$LienPage = $GLOBALS['maConnexionAPI']->CallServeur($LienPage);	
	}
	return  '<a href="' . $LienPage .'" title="'.$title .'"><img ' . $ImageLien. 'class="imgArbo"></a>';


	

} 

function isArboPrete($NomProjet) {
	$DossierCMDLABO = $GLOBALS['repCMDLABO'];
	$files = glob( $DossierCMDLABO . SUPRAccents(NomfichierARBO($NomProjet)) .'{2,3,4,5}',GLOB_BRACE);	

	return (count($files) > 0)?true:false;
}

function SUPRAccents($str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );    
    return $str;
}

function BDDARBOwebfile($NewFichier, $BDDRECCode, $CodeEcole, $Annnescolaire, $strListeFichiers = ''){
	$listeFichiers = explode("_", $strListeFichiers);
	
	if ($GLOBALS['isDebug']){
		echo "<br> STOP !<br> ";
		echo '<br><br>' . $NewFichier;
		echo '<br><br> strListeFichiers ' . $strListeFichiers;
	}
	
	$Tabl = [];
	$strURL_NewFichier = $GLOBALS['repCMDLABO'] . utf8_decode($NewFichier) . "0";	

	if (file_exists($strURL_NewFichier)) { 	$Tabl = file($strURL_NewFichier); }//Les commande existantes s'il y en a 
	
	$file = fopen($strURL_NewFichier, 'w');
		$ligne = '[Version : 2.0' . $BDDRECCode . "\n";
		fputs($file, $ligne);
		$ligne = '{Etat 1 :0% %%'. (($strListeFichiers != '')? str_replace("§","<br>",$strListeFichiers):'TOUTES LES PHOTOS') . '....}' . "\n";
		fputs($file, $ligne);    //{Etat 1 :1%%Le groupe de commandes comp....}
		$nomFichier = utf8_decode($NewFichier);
		
		$ligne =   (($strListeFichiers != '')?'@CORR_':'@ARBO_') . substr($nomFichier,5 , -4).'_'. $CodeEcole .'_'. $Annnescolaire .'_Fichiers de présentation pour boutique en ligne!@' . "\n";
		//$ligne =  '@2021-02-26_L2-Ecole TEST-MAROU_'.$CodeEcole.'_Ecole web !@' . "\n";
		fputs($file, $ligne);	 //@2021-02-26_L2-Ecole TEST-MAROU_ACC7_Ecole web !@ 
		
		
		
		if ($strListeFichiers != ''){
			//Les commande existantes
			for($i = 3; $i < count($Tabl) ; $i++){		
				if ($Tabl[$i] != '' ) {fputs($file, $Tabl[$i]);}				
			}	
			for($i = 0; $i < count($listeFichiers); $i++){		
				fputs($file, $listeFichiers[$i] . "\n");
			}
			//fputs($file, 'nb S ' . count($listeFichiers) . '  0 : ' .  $listeFichiers[0]);
		}
		else {
			fputs($file, 'TOUTES LES PHOTOS');
		}
	fclose($file);	
}

function BDDLibreRECFileLab($NewFichier, $BDDRECCode, $CodeEcole, $Annnescolaire, $strListeFichiers = ''){
	$listeFichiers = explode("_", $strListeFichiers);
	
	if ($GLOBALS['isDebug']){
		echo "<br> STOP !<br> ";
		echo '<br><br>' . $NewFichier;
		echo '<br><br>' . $strListeFichiers;
	}
	$monProjet = ChercherSOURCESEcole("../../SOURCES/Sources.csv", $CodeEcole, $Annnescolaire);
	
	$Tabl = [];
	$strURL_NewFichier = $GLOBALS['repCMDLABO'] . utf8_decode($NewFichier) . "0";	

	if (file_exists($strURL_NewFichier)) { 	$Tabl = file($strURL_NewFichier); }//Les commande existantes s'il y en a 
	
	$file = fopen($strURL_NewFichier, 'w');
		$ligne = '[Version : 2.0' . $BDDRECCode . "\n";
		fputs($file, $ligne);
		$ligne = '{Etat 1 :0%%En Cours....}' . "\n";
		fputs($file, $ligne);    //{Etat 1 :1%%Le groupe de commandes comp....}
		$nomFichier = utf8_decode($NewFichier);
		
		$ligne =   (($strListeFichiers != '')?'@CORR_':'@ARBO_') . substr($nomFichier,5 , -4).'_'.$CodeEcole.'_Fichiers de présentation pour boutique en ligne!@' . "\n";
		//$ligne =  '@2021-02-26_L2-Ecole TEST-MAROU_'.$CodeEcole.'_Ecole web !@' . "\n";
		fputs($file, $ligne);	 //@2021-02-26_L2-Ecole TEST-MAROU_ACC7_Ecole web !@ 
		
		if ($strListeFichiers != ''){
			$ligne =    '#Libre__Date du jour# ' . "\n";
		

			//Les commande existantes
			for($i = 3; $i < count($Tabl) ; $i++){		
				if ($Tabl[$i] != '' ) {fputs($file, $Tabl[$i]);}				
			}	
			for($i = 0; $i < count($listeFichiers); $i++){		
				fputs($file, $listeFichiers[$i] . "\n");
			}
			//fputs($file, 'nb S ' . count($listeFichiers) . '  0 : ' .  $listeFichiers[0]);
		}
		else {
			fputs($file, 'TOUTES LES PHOTOS');
		}
	fclose($file);	
}
?>
