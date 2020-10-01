<?php



/////////////////// Les Fonctions ... ///////////////////    

function SuprimeFichier($strFILELAB){
	if (file_exists($GLOBALS['repCMDLABO'] .  $strFILELAB)){
		$fichier = $GLOBALS['repCMDLABO'] . $strFILELAB ;
		if (file_exists($fichier)){ 
			//Supression du .lab0
			unlink($fichier);
			$Extension = '.' . TypeFichier($strFILELAB);
			$strBaseName = substr($fichier, 0, strpos($fichier, $Extension));
			if (file_exists($strBaseName . '.Erreur')){ 
				//Supression du fichier erreur
				unlink($strBaseName . '.Erreur');
			}				
		}
	} 	
}
	
function LienIMGSuprFichierLab($fichier, $Etat) {
	$Lien = 'PhotolabCMD.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($fichier);// . '&apiEtat=' . $Etat;
	$retour = '';
	if ($Etat == 'Erreur'){
		$retour = '<a href="'.$Lien.'"  title="' . "Pour recompiler, supprimer l'alerte d'erreur " .  '"><img src="img/poubelle.png"></a>'; 		
	}
	else{
		if (is_numeric($Etat)){
			if ($Etat < 3){
				$retour = '<a href="'.$Lien.'"  title="' . 'Supprimer ' . utf8_encode($fichier) .  '"><img src="img/poubelle.png"></a>'; 								
			}
		}
	}
	return $retour;
}

function ChangeEtat($strFILELAB, $Etat){
	$Extension = '.' . TypeFichier($strFILELAB);
	
	$strBaseName = substr($strFILELAB, 0, strpos($strFILELAB, $Extension));
	if (file_exists($GLOBALS['repCMDLABO'] . utf8_decode( $strFILELAB))){
		rename($GLOBALS['repCMDLABO'] . utf8_decode( $strFILELAB), $GLOBALS['repCMDLABO'] . utf8_decode($strBaseName) . $Extension . $Etat);
		$fichierdeBase = $GLOBALS['repCMDLABO'] . utf8_decode($strBaseName) ;
		if ($Etat > 2){
			if (file_exists($fichierdeBase . $Extension . '0')){ 
				//Supression du .lab0
				unlink($fichierdeBase . $Extension . '0');
			}
			if (file_exists($fichierdeBase . $Extension . '1')){ 
				//Supression du .lab0
				unlink($fichierdeBase . $Extension . '1');
			}			
			if (file_exists($fichierdeBase . '.Erreur')){ 
				//Supression du .lab0
				unlink($fichierdeBase . '.Erreur');
			}
		}
		return 'OK';
	} else {
		return "APIPhotoLab : erreur 44";
	}
	$CMDhttpLocal = '?apiChgEtat='. $strFILELAB .'&apiEtat=' . $Etat ;
	echo $CMDhttpLocal;
}

function BDDRECFileLab($strRECFileLab, $BDDRECCode){

	if ($GLOBALS['isDebug']){
		echo "<br> STOP !<br> ";
	}
	$line ='';
	$strURL_RECFileLab = $GLOBALS['repCMDLABO'] . utf8_decode($strRECFileLab) . "0";	
	
	// New
	/*Ouvre le fichier et retourne un tableau contenant une ligne par élément*/
	$lines = file($strURL_RECFileLab);
	if ($GLOBALS['isDebug']){		
		foreach ($lines as $lineNumber => $lineContent){/*On parcourt le tableau $lines et on affiche le contenu de chaque ligne précédée de son numéro*/
			echo $lineNumber .' : ' . $lineContent .'<br>';
		}	
	}
	$NewFichier = $strURL_RECFileLab;// . "_rec" ;
	//echo '<br><br>' . $NewFichier;
	$file = fopen($NewFichier, 'w');
		for($i = 0; $i < count($lines); $i++){
			if(!$i){
				$premiereligne = '[Version : 2.0' . $BDDRECCode . "\n";
				fputs($file, $premiereligne);
			}
			else{
				fputs($file, $lines[$i]);	
			}
		}
	fclose($file);	

}

function RECFileLab($strRECFileLab){
	if ($GLOBALS['isDebug']){
		echo "<br> STOP<br> ";

		echo 'strURL_RECFileLab = ' . $GLOBALS['maConnexionAPI']->URL ."/CMDLABO/" . urlencode($strRECFileLab) . "0";
	}
	$line ='';
	$strURL_RECFileLab = $GLOBALS['maConnexionAPI']->URL ."/CMDLABO/" . urlencode($strRECFileLab) . "0";
	echo '<br><br><br><br> strURL_RECFileLab : ' . $strURL_RECFileLab;


	$file = fopen($strURL_RECFileLab, "r");
	if ($file) {
		while (($buffer = fgets($file, 4096)) !== false) {
			echo $buffer;
			$line = $line . $buffer;
		}
		if (!feof($file)) {
			echo "Erreur: fgets() a échoué\n";
		}
		fclose($file);
	}

		$NewFichier = "CMDLABO/" . utf8_decode($strRECFileLab). "0" ;
		//echo '<br><br>' . $NewFichier;
		$file = fopen($NewFichier, 'w');
			fputs($file, $line);
		fclose($file);
		//rename($NewFichier, "CMDLABO/" . utf8_decode($strRECFileLab) . "0" );
}

function SetCatalog($strTRANSFileLab){
	return 'le transformer ' . $strTRANSFileLab;
}

function AfficheTableauCMDLAB(&$nb_fichier, $isEnCours){
	//echo '$cmd = ' . ($GLOBALS['isDebug']?'':($ParamGET ?'?apiAMP=OK':''));
	//setlocale(LC_TIME, 'french');
	$affiche_Tableau = '';
	$tabFichierLabo = TableauRepFichier('.lab', $isEnCours);
	rsort($tabFichierLabo);
	for($i = 0; $i < count($tabFichierLabo); $i++){
		$fichier = $tabFichierLabo[$i];
		$Extension = substr(strrchr($fichier, '.'),4);
		//echo "Extension : " . $Extension ."<br>";
		$nb_fichier++;
		$DateFichierLAb = strftime('%A %d %B %Y', strtotime(substr($fichier,0,10)));
		$NonFichierEcole = utf8_encode(pathinfo(substr($fichier,11))['filename']);

		$ResumeCMD = "init ZZ";
		$EtatCMD = 0;
		$Compilateur = "init ZZ";
		$NBPlanche = INFOsurFichierLab($GLOBALS['repCMDLABO'] . $fichier, $EtatCMD, $ResumeCMD, $Compilateur);
		//$ResumeCMD = RESUMEFichierLab($ResumeCMD);
		$affiche_Tableau .=
		'<tr>

			<td>' . substr($fichier,0,10) .'</td>
			<td align="left" class="titreCommande" ><div class="tooltip"><a href="' . LienFichierLab($fichier) . '">'.LienImageVoir($Extension).' ' . $NonFichierEcole . '</a>
				<span class="tooltiptext">'. $ResumeCMD . '</span></div></td>
			<td><div class="tooltip"><a href="' . LienFichierLab($fichier) . '"><img src="img/' . $Extension . '-Etat.png"></a>
				<span class="tooltiptext">'. $ResumeCMD . '</span></div></td>	
			<td><div class="tooltip"><a href="#" >' . $NBPlanche . '</a>
				<span class="tooltiptext">'. $ResumeCMD . '</span></div></td>';
		
		if($Extension < 2){
			$affiche_Tableau .=	'
			<td colspan=4>';
				
			//if (file_exists($GLOBALS['repCMDLABO'] . utf8_decode(substr($tabFichierLabo[$i], 0, -5)).'.Erreur')){
			if (file_exists($GLOBALS['repCMDLABO'] . substr($tabFichierLabo[$i], 0, -5) .'.Erreur')){				
				$affiche_Tableau .=	'			
				<div class="tooltip"><a href="../CMDLABO/'. utf8_encode(substr($tabFichierLabo[$i], 0, -5)) . '.Erreur" title="Afficher les erreurs sur '.$NonFichierEcole . '">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : Erreurs !</font></a>
					' . LienIMGSuprFichierLab(substr($tabFichierLabo[$i], 0, -5) . '.Erreur', 'Erreur') . '
					</div>			
				</div>';					
			}
			$affiche_Tableau .=	'
			<div class="boiteProgressBar">
			<div class="progressBar" style="width:'.Avancement($Extension, $EtatCMD).'%;" >';
			$affiche_Tableau .=	'<font size="2" >'. $Compilateur . '> '. number_format(Avancement($Extension, $EtatCMD), 1).'%</font>';			
			$affiche_Tableau .=	'</div>
				</div>';			
		}else {
			$affiche_Tableau .=	'
				<td><a href="' . LienEtatLab($fichier,2) . '"  title="'. TitleEtat(2) . '">' . LienImageOKKO($Extension >= "2") . '</a></td>
				<td><a href="' . LienEtatLab($fichier,3) . '"  title="'. TitleEtat(3) . '">' . LienImageOKKO($Extension >= "3") . '</a></td>
				<td><a href="' . LienEtatLab($fichier,4) . '"  title="'. TitleEtat(4) . '">' . LienImageOKKO($Extension >= "4") . '</a></td>
				<td><a href="' . LienEtatLab($fichier,5) . '"  title="'. TitleEtat(5) . '">' . LienImageOKKO($Extension >= "5") . '</a></td>'	
			;				
		}
		$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($fichier, $Extension) . '</td>';
	}
	$affiche_Tableau .=	'</tr>';
	return $affiche_Tableau;
}

function AfficheTableauCMDWEB(&$nb_fichier, $isEnCours){
	//echo '$cmd = ' . ($GLOBALS['isDebug']?'':($ParamGET ?'?apiAMP=OK':''));
	//setlocale(LC_TIME, 'french');
	$affiche_Tableau = '';
	$tabFichierLabo = TableauRepFichier('.web', $isEnCours);
	
	rsort($tabFichierLabo);
	
	for($i = 0; $i < count($tabFichierLabo); $i++){
		$fichier = $tabFichierLabo[$i];
		$Extension = substr(strrchr($fichier, '.'),4);
		//echo "Extension : " . $Extension ."<br>";
		$nb_fichier++;
		$DateFichierLAb = strftime('%A %d %B %Y', strtotime(substr($fichier,0,10)));
		$NonFichierEcole = pathinfo(utf8_encode(substr($fichier,11)))['filename'];
		//$NonFichierEcole = utf8_encode($fichier);
		
		$ResumeCMD = "init ZZ";
		$EtatCMD = 0;
		$Compilateur = "init ZZ";
		$NBPlanche = INFOsurFichierLab($GLOBALS['repCMDLABO'] . $fichier, $EtatCMD, $ResumeCMD, $Compilateur);
		
		//$ResumeCMD = RESUMEFichierLab($ResumeCMD);		
		$affiche_Tableau .=
		'<tr>
			<td>' . substr($fichier,0,10) .'</td>				
			<td align="left">' . $NonFichierEcole . '</a></td>	
			<td>'.LienImageEtatWEB($Extension).'</a></td>		
			<td>' . $NBPlanche . '</a></td>';

		if($Extension < 2){
			$affiche_Tableau .=	'
			<td colspan=2>';
			if (file_exists($GLOBALS['repCMDLABO'] . utf8_decode(substr($tabFichierLabo[$i], 0, -5)).'.Erreur')){
				$affiche_Tableau .=	'			
				<div class="tooltip"><a href="../CMDLABO/'. utf8_encode(substr($tabFichierLabo[$i], 0, -5)) . '.Erreur" title="Afficher les erreurs">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : Erreurs !</font></a>
					' . LienIMGSuprFichierLab(utf8_encode(substr($tabFichierLabo[$i], 0, -5)) . '.Erreur', 'Erreur') . '
					</div>			';					
			}
			$affiche_Tableau .=	'
			<div class="boiteProgressBar">
			<div class="progressBar" style="width:'.Avancement($Extension, $EtatCMD).'%;" >';
			$affiche_Tableau .=	'<font size="2" >'. $Compilateur . '> '. number_format(Avancement($Extension, $EtatCMD), 1).'%</font>';			
			$affiche_Tableau .=	'</div>
				</div>';	
		}else {
			$affiche_Tableau .=	'
			<td><div class="tooltip"><a href="' . LienEtatLab($fichier,2) . '" title="'. TitleEtat(2) . '">' . LienImageOKKO($Extension >= "2") . '</a>
				<span class="tooltiptext">'. $ResumeCMD . '</span></div></td>					
			<td><div class="tooltip"><a href="' . LienEtatLab($fichier,3) . '" title="'. TitleEtat(3) . '">' . LienImageOKKO($Extension >= "3") . '</a>
				<span class="tooltiptext">'. $ResumeCMD . '</span></div></td>'	
			;				
		}		
		$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($fichier, $Extension) . '</td>';
	}
	$affiche_Tableau .=	'</tr>';
	return $affiche_Tableau;
}

function TitleEtat($Etat){
	switch ($Etat) {
	case "1":
		$retourMSG = "Les planches sont en cours de création.";
		break;			
	case "2":
		$retourMSG = "Les planches sont toutes crées.";
		break;		
	case "3":
		$retourMSG = "Déclarer que les planches ont été envoyés au laboratoire ?";
		break;
	case "4":
		$retourMSG = "Déclarer que les photos sont tirées au laboratoire ?";
		break;		
	case "5":
		$retourMSG = "Déclarer que les photos sont mise en carton. Fin";
		break;	
	}
	return $retourMSG;
}

function TableauRepFichier($ExtFichier, $isEnCours){
	// NETOYAGE REPERTOIRE FICHIERS DE COMMANDES!!
	if($dossier = opendir($GLOBALS['repCMDLABO'])){
		while(false !== ($fichier = readdir($dossier))){
			$Extension = strrchr($fichier, '.');
			$fichierdeBase = substr($fichier, 0, -5);
			//echo $fichierdeBase;
			if (substr($Extension,-1) > 2){  // dernier charactere de la chaine
				$fichierdeBase = $GLOBALS['repCMDLABO'] . substr($fichier, 0, -5) . $ExtFichier;
				//echo $GLOBALS['repCMDLABO'] . substr($fichier, 0, -5) . $ExtFichier;
				if (file_exists($fichierdeBase . '0')){  //voir fichier de base
					//Supression du .lab0
					unlink($fichierdeBase . '0');
				}
				if (file_exists($fichierdeBase . '1')){ 
					//Supression du .lab0
					unlink($fichierdeBase . '1');
				}			
				if (file_exists($fichierdeBase . '.Erreur')){ 
					//Supression du .lab0
					unlink($fichierdeBase . '.Erreur');
				}
			}			
		} 
		closedir($dossier);
	} else {
		 echo 'Le dossier n\' a pas pu être ouvert';
	}
	// SCAN DU REPERTOIRE 
	$tabFichierLabo = array();
	$EtatFinal = 5;
	if($dossier = opendir($GLOBALS['repCMDLABO'])){
		while(false !== ($fichier = readdir($dossier))){
			$Extension = strrchr($fichier, '.');
			$EtatFinal = ($ExtFichier == '.lab'?5:3);
			
			if($fichier != '.' && $fichier != '..'  && substr($Extension,0,4) == $ExtFichier && strlen($Extension) > 4){
				if ($isEnCours && substr(strrchr($fichier, '.'),4) < $EtatFinal){
					array_push($tabFichierLabo,$fichier);
					//echo $fichier .' ext ETAT : ' . substr(strrchr($fichier, '.'),4) . '<br>';					
				}
				else{
					if (!$isEnCours && substr(strrchr($fichier, '.'),4) > $EtatFinal-1){
						array_push($tabFichierLabo,$fichier);
						//echo $fichier .' ext ETAT : ' . substr(strrchr($fichier, '.'),4) . '<br>';					
					}					
				}
			} 
		} 
		closedir($dossier);
	} else {
		 echo 'Le dossier n\' a pas pu être ouvert';
	}
	// enleve l'affichage de lab0 si lab1 ou Superieur existe	
	$tabFichierLaboSelect = array();
	for($i = 0; $i < count($tabFichierLabo); $i++){
		$Extension = strrchr($tabFichierLabo[$i], '.');
		if ($Extension == $ExtFichier . '0'){// Seulement si y a pas un .lab1 du même nom!
			$fichier = $tabFichierLabo[$i];			
			$strBaseName = substr($fichier, 0, strpos($fichier, $ExtFichier));
			$YAutre = false;
			for($j = 0; $j < count($tabFichierLabo); $j++){				
				if ($strBaseName == substr($tabFichierLabo[$j], 0, strpos($tabFichierLabo[$j], $ExtFichier))) {
					if($fichier != $tabFichierLabo[$j]){
						$YAutre = true;
						break;
					} else {
						$YAutre = false;				
					}
				}
			}
			if(!$YAutre){array_push($tabFichierLaboSelect,$tabFichierLabo[$i]);}
		} else{
			array_push($tabFichierLaboSelect,$tabFichierLabo[$i]);		
		}
	}	
	return $tabFichierLaboSelect;
}

function InfoETAT($isOK){
	$Lien = ($isOK?'src="img/OK.png" alt="Oui"':'src="img/KO.png" alt="Non"'). ' class="OKKOIMG"';
	//return $Lien;
	return '<img ' . $Lien . '>';
} 

function LienImageOKKO($isOK){
	$Lien = ($isOK?'src="img/OK.png" alt="Oui"':'src="img/KO.png" alt="Non"'). ' class="OKKOIMG"';
	//return $Lien;
	return '<img ' . $Lien . '>';
} 

function LienImageVoir($Etat){
	$Lien = '<img src="img/VisualisationKO.png" alt="Voir les planches">';
	if($Etat) {
		$Lien = '<img src="img/VisualisationOK.png" alt="Voir les planches">';
	}
	return $Lien;  
}

function LienImageEtat($Etat){
	return '<img src="img/' . $Etat . '-Etat.png">'; 
}

function LienImageEtatWEB($Etat){
	return '<img src="img/' . $Etat . '-Etat.png">';  
}

function LienEtatLab($fichier, $Etat) {
	if (strrchr($fichier, '.') != ".lab0"){
		return $GLOBALS['maConnexionAPI']->TalkServeur('&apiChgEtat='. urlencode(utf8_encode($fichier)) .'&apiEtat=' . $Etat);			
	} else {
		return 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($fichier) ;
	}
}

function LienFichierLab($fichier) {
	$Environnement = '?isAMP=' . ($GLOBALS['isAMP']?'OK':'KO') . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	$Extension = strrchr($fichier, '.');
	$LienFichier = "#";
	switch ($Extension) {
		case ".lab0":
			$LienFichier = 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($fichier) ;
			break;
		/*case ".lab1":
			$LienFichier = "CMD-View.php". $Environnement . "&fichierLAB=" . urlencode($fichier);
			break;*/
		default:
			$LienFichier = "CMD-View.php". $Environnement . "&fichierLAB=" . urlencode($fichier);
			break;		
	}
  
$isDebug = true;
	return $LienFichier;
}
/* //////////////  New du 12 Juin 2019 /////////////////////  */
function RESUMEFichierLab($ResumeCMD){

	$ResumeCMD = utf8_encode(substr(stristr($ResumeCMD, '%%'),1,-1)); // New 26-08
	
	$ResumeCMD = str_replace("%", "<br>", $ResumeCMD);
	$ResumeCMD = str_replace("{", "<br>", $ResumeCMD);
	return $ResumeCMD . "<br>";
}

function INFOsurFichierLab($myfileName, &$Pourcentage, &$ResumeCMD, &$Compilateur){
	$tabFICHIERLabo = LireFichierLab($myfileName);
	//echo count($tabFICHIERLabo);
	$nbPlanches=0;
	for($i = 0; $i < count($tabFICHIERLabo); $i++){
		$identifiant = substr($tabFICHIERLabo[$i],0,1);
		if (($identifiant != '[') && ($identifiant != '{') && ($identifiant != '#') && ($identifiant != '@') && ($identifiant != '<') && ($identifiant != '')) {
			$nbPlanches = $nbPlanches + 1 ;
		}else {
			if ($identifiant == '{')  {
				$ResumeCMD = RESUMEFichierLab($tabFICHIERLabo[$i]) ;

				$Pourcentage = 100 * floatval(str_replace(",", ".", substr(stristr($tabFICHIERLabo[$i], '%%', true), 9)));
			}
			if ($identifiant == '[')  {
				$Compilateur = strstr(strrchr($tabFICHIERLabo[$i], '%'), 1, -1);
			}			
		}
		
	 }
	return $nbPlanches;
}
function Avancement($Etat, $EtatCMD){
	switch ($Etat) {
	case "0":
		$RetourEtat = 0;
		break;		
	case "1":
		$RetourEtat = floatval($EtatCMD);
		break;			
	default:
		$RetourEtat = 100;
		break;		
	}
	//echo 'Avancement($Extension, $EtatCMD)$Etat: ' . $Etat . '  EtatCMD : ' . $EtatCMD . '  $RetourEtat: ' . $RetourEtat;
	return $RetourEtat;  	
}


/* //////////////  FIN New du 12 Juin 2019 /////////////////////  */

function LireFichierLab($myfileName){
 	$tabFICHIERLabo = array();
	$myfile = fopen($myfileName, "r") or die('Unable to open file : ' .$myfileName);
	// Output one line until end-of-file
	while(!feof($myfile)) {
		array_push($tabFICHIERLabo,trim(fgets($myfile)));
	}
	fclose($myfile);
	//afficheTab($tabFICHIERLabo);
	return $tabFICHIERLabo;
}

function TypeFichier($myfileName){
	return substr($myfileName, -4, 3);
}
?>