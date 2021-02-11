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
	<title id="GO-PHOTOLAB">PhotoLab : Sources enregistrées</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
	<link rel="stylesheet" type="text/css" href="css/CATPhotolab.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png">
</head>

<body>

<div class="logo">
	<a href="<?php echo 'index.php' . ArgumentURL(); ?>" title="Retour à l'acceuil"><img src="img/Logo.png" alt="Image de fichier"></a>
</div>

<?php
if (isset($_GET['OpenRep'])) { // OUVRIR REP !
	$leRep = str_replace("/","\\",$repTIRAGES. $_GET['OpenRep']);
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

$affiche_Tableau = AfficheTableauSOURCES($nb_fichier, "../Sources.csv");

?>


<BR><BR><BR><BR><BR><BR>


<h1>Sources écoles référencées : <?php echo $nb_fichier; ?></H1>    
	<!--<table class="Tableau" id="myTableWEB">-->
	<table id="commandes">
	  <tr class="header" >
		<th style="width:150px;" onclick="sortTable(1)"><H3>Code Ecole</H3></th>	
		<th style="width:150px;" onclick="sortTable(2)"><H3>Repertoire Actions PSP</H3></th>		
		<th style="width:127px;" onclick="sortTable(0)"><H3>Année scolaire</H3></th>
		<th  onclick="sortTable(1)"><H3>Nom projet</H3></th>	
		<th style="width:127px;" onclick="sortTable(0)"><H3>Nb Fichiers</H3></th>		
		<th  style="width:240px;" ><H3>Arborescence de présentation Web</H3></th>		
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



function csv_to_array($filename='', $delimiter=';')
{
    //echo ('$filename ' . $filename);
	if(!file_exists($filename) || !is_readable($filename))
        return FALSE;

    $header = NULL;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== FALSE)
    {
        while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE)
        {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }
    return $data;
}




function AfficheTableauSOURCES(&$nb_fichier, $fichierCSV){ 
	$TabCSV = csv_to_array($fichierCSV, ';');
	$affiche_Tableau = '';
	foreach ($TabCSV as $key => $row) {
				$NomProjet[$key] = $row['NomProjet'];
	}
	array_multisort($NomProjet, SORT_ASC, $TabCSV); // Tri par nom de projet
	$NbLignes=count($TabCSV);
	$nb_fichier = $NbLignes;
	for($i = 0; $i < $NbLignes; $i++)
	{ 
		$affiche_Tableau .=	'<tr>			
	<td><div class="tooltip">' . $TabCSV[$i]["Code"] .'
				<span class="tooltiptext">'. $TabCSV[$i]["Repertoire"] . '</span></div></td>				
	<td><div class="tooltip">' . $TabCSV[$i]["Rep Scripts PS"] .'
				<span class="tooltiptext">'. $TabCSV[$i]["Repertoire"] . '</span></div></td>	
	<td><div class="tooltip">' . $TabCSV[$i]["Annee"] .'
				<span class="tooltiptext">'. $TabCSV[$i]["Repertoire"] . '</span></div></td>		
	<td align="left" class="titreCommande" ><div class="tooltip">' . $TabCSV[$i]["NomProjet"] .'
				<span class="tooltiptext">'. $TabCSV[$i]["Repertoire"] . '</span></div></td>			
	<td><div class="tooltip"><a href="' . LienOuvrirRepTIRAGE($TabCSV[$i]["Repertoire"] ) . '" >' . NBfichiersDOSSIER($TabCSV[$i]["NomProjet"]) . '</a>';		
			
		$isArbo	= file_exists($GLOBALS['repCMDLABO'] .  NomfichierARBO($TabCSV[$i]["NomProjet"]) . '2');
		
		$affiche_Tableau .= '<td><a href="' . LienEtatArbo($TabCSV[$i]["NomProjet"],$isArbo) . '"  title="Faire un ensemble de fichier pour presentation web">' . LienImageOKKO($isArbo) . '</a></td>';	


			
		$affiche_Tableau .=	'</tr>';	
	}


	return $affiche_Tableau;
}

function NomfichierARBO($NomProjet) {
  return 'ARBO-' . $NomProjet. '.web';
}

function NBfichiersDOSSIER($Dossier) {
	$NbFicher = 55;
	return $NbFicher;
}
function LienEtatArbo($fichier , $isDone ) {
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

?>
