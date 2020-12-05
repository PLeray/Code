<?php

class CINFOfichierLab {
	var $Fichier;	
	var $FichierERREUR;	
	var $EtatFichier;  // 0, 1 , 2 , 3
	var $PourcentageAvancement = 0;
	var $SyntheseCMD;
	var $Compilateur;
	//var $isOuvrable;
    var $NbPlanches = 0;
    var $NomEcole;
    var $DateTirage;

    function __construct($myfileName){ // Le chemin complet !
		$tabFICHIERLabo = LireFichierLab($myfileName);
		$this->Fichier = basename($myfileName);
		$this->EtatFichier = substr(strrchr($this->Fichier, '.'),4);
		$this->FichierERREUR = substr($this->Fichier, 0, -5) . '.Erreur';
		$this->AffichageCMD = substr($this->Fichier, 11, -5);

		for($i = 0; $i < count($tabFICHIERLabo); $i++){
			$identifiant = substr($tabFICHIERLabo[$i],0,1);
			if (($identifiant != '[') && ($identifiant != '{') && ($identifiant != '#') && ($identifiant != '@') && ($identifiant != '<') && ($identifiant != '')) {
				$this->NbPlanches = $this->NbPlanches + 1 ;
				//echo $this->NbPlanches;
			}else {
				if ($identifiant == '{')  {
			//NEW UtF8//$this->SyntheseCMD = utf8_encode(substr(stristr($tabFICHIERLabo[$i], '%%'),1,-1));
					$this->SyntheseCMD = substr(stristr($tabFICHIERLabo[$i], '%%'),1,-1);
					$this->SyntheseCMD = str_replace("%", "<br>", $this->SyntheseCMD);
					$this->SyntheseCMD = str_replace("{", "<br>", $this->SyntheseCMD) . "<br>";

					$this->PourcentageAvancement = 100 * floatval(str_replace(",", ".", substr(stristr($tabFICHIERLabo[$i], '%%', true), 9)));
				}
				if ($identifiant == '[')  {
					$this->Compilateur = strstr(strrchr($tabFICHIERLabo[$i], '%'), 1, -1);
					//$this->isOuvrable = $this->Compilateur;
				}		
				if ($identifiant == '@')  {
			//NEW UtF8//$morceau = explode("_", utf8_encode(str_replace("@", "", $tabFICHIERLabo[$i])));
					$morceau = explode("_", str_replace("@", "", $tabFICHIERLabo[$i]));
					$this->DateTirage = $morceau[0];
					$this->NomEcole = $morceau[1];			
				}	
			}		
		}
	}
    function RepTirage(){
		$leRepTirage = '';
		if ($this->EtatFichier){
			if (stripos($this->NomEcole, '(ISOLEES)') !== false) { // C'est des ISOLEES
				$leRepTirage = substr($this->Fichier, strripos($this->Fichier, '/'),10) . '-CMD-ISOLEES' ;
			}	
			else{
				$leRepTirage = $this->DateTirage . '-' .$this->NomEcole ;	
			}	
		}
		return $leRepTirage;
    } 
	
    function LienFichierERREUR(){
		return $GLOBALS['repCMDLABO'] . $this->FichierERREUR;
    }	
	function Avancement(){
		switch ($this->EtatFichier) {
		case "0":
			$RetourEtat = 0;
			break;		
		case "1":
			$RetourEtat = floatval($this->PourcentageAvancement);
			break;			
		default:
			$RetourEtat = 100;
			break;		
		}
		//echo 'Avancement($Extension, $this->PourcentageAvancement)$this->EtatFichier: ' . $this->EtatFichier . '  this->PourcentageAvancement : ' . $this->PourcentageAvancement . '  $RetourEtat: ' . $RetourEtat;
		return $RetourEtat;  	
	}	
}

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
	$Lien = 'CATPhotolab.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($fichier);// . '&apiEtat=' . $Etat;
	$retour = '';
	if ($Etat == 'Erreur'){
		$retour = '<a href="'.$Lien.'"  title="' . "Pour recompiler, supprimer l'alerte d'erreur " .  '"><img src="img/poubelle.png"></a>'; 		
	}
	else{
		if (is_numeric($Etat)){
			if ($Etat < 3){
				//NEW2 UTF-8 $retour = '<a href="'.$Lien.'"  title="' . 'Supprimer ' . utf8_encode($fichier) .  '"><img src="img/poubelle.png"></a>'; 
				$retour = '<a href="'.$Lien.'"  title="' . 'Supprimer ' . $fichier .  '"><img src="img/poubelle.png"></a>'; 								
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
		// Un objet pour récupérer les infos Fichier !!! 
		$mesInfosFichier = new CINFOfichierLab($GLOBALS['repCMDLABO'] . $tabFichierLabo[$i]); 
		$nb_fichier++;

		$affiche_Tableau .=
		'<tr>
			<td>' . $mesInfosFichier->DateTirage .'</td>
			<td align="left" class="titreCommande" ><div class="tooltip"><a href="' . LienFichierLab($mesInfosFichier->Fichier) . '">'.LienImageVoir($mesInfosFichier->EtatFichier).' ' . $mesInfosFichier->AffichageCMD . '</a>
				<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>
			<td><div class="tooltip"><a href="' . LienFichierLab($mesInfosFichier->Fichier) . '"><img src="img/' . $mesInfosFichier->EtatFichier . '-Etat.png"></a></div></td>	
			<td><div class="tooltip"><a href="' . LienOuvrirRepTIRAGE($mesInfosFichier->RepTirage()) . '" >' . $mesInfosFichier->NbPlanches . '</a>
				<span class="tooltiptext"><br>Cliquez pour aller vers le repertoire des planches crées<br><br></span></div></td>';
		
		if($mesInfosFichier->EtatFichier < 2){
			$affiche_Tableau .=	'
			<td colspan=4>';
				
			//if (file_exists($GLOBALS['repCMDLABO'] . utf8_decode(substr($tabFichierLabo[$i], 0, -5)).'.Erreur')){
			//if (file_exists($GLOBALS['repCMDLABO'] . substr($tabFichierLabo[$i], 0, -5) .'.Erreur')){				
			if (file_exists($mesInfosFichier->LienFichierERREUR())){	
			$affiche_Tableau .=	'			
				<div class="tooltip"><a href="'. $mesInfosFichier->LienFichierERREUR() . '." title="Afficher les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : Erreurs !</font></a>
					' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
					</div>			
				</div>';				
			}
			$affiche_Tableau .=	'
			<div class="boiteProgressBar">
			<div class="progressBar" style="width:'.$mesInfosFichier->Avancement().'%;" >';
			$affiche_Tableau .=	'<font size="2" >'. $mesInfosFichier->Compilateur . '> '. number_format($mesInfosFichier->Avancement(), 1).'%</font>';			
			$affiche_Tableau .=	'</div>
				</div>';			
		}else {
			$affiche_Tableau .=	'
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,2) . '"  title="'. TitleEtat(2) . '">' . LienImageOKKO($mesInfosFichier->EtatFichier >= "2") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,3) . '"  title="'. TitleEtat(3) . '">' . LienImageOKKO($mesInfosFichier->EtatFichier >= "3") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,4) . '"  title="'. TitleEtat(4) . '">' . LienImageOKKO($mesInfosFichier->EtatFichier >= "4") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,5) . '"  title="'. TitleEtat(5) . '">' . LienImageOKKO($mesInfosFichier->EtatFichier >= "5") . '</a></td>'	
			;				
		}
		$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($mesInfosFichier->Fichier, $mesInfosFichier->EtatFichier) . '</td>';
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
		// Un objet pour récupérer les infos Fichier !!! 
		$mesInfosFichier = new CINFOfichierLab($tabFichierLabo[$i]); 		
		$nb_fichier++;		
		
		$affiche_Tableau .=
		'<tr>
			<td>' . $mesInfosFichier->DateTirage .'</td>				
			<td align="left">' . $mesInfosFichier->NomEcole . '</a></td>	
			<td>'.LienImageEtatWEB($mesInfosFichier->EtatFichier).'</a></td>		
			<td>' . $mesInfosFichier->NbPlanches . '</a></td>';

		if($mesInfosFichier->EtatFichier < 2){
			$affiche_Tableau .=	'
			<td colspan=2>';
			if (file_exists($mesInfosFichier->LienFichierERREUR())){
				$affiche_Tableau .=	'			
				<div class="tooltip"><a href="'. $mesInfosFichier->LienFichierERREUR(). '." title="Afficher les erreurs">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : Erreurs !</font></a>
					' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
					</div>			';					
			}
			$affiche_Tableau .=	'
			<div class="boiteProgressBar">
			<div class="progressBar" style="width:'.$mesInfosFichier->Avancement().'%;" >';
			$affiche_Tableau .=	'<font size="2" >'. $mesInfosFichier->Compilateur . '> '. number_format($mesInfosFichier->Avancement(), 1).'%</font>';			
			$affiche_Tableau .=	'</div>
				</div>';	
		}else {
			$affiche_Tableau .=	'
			<td><div class="tooltip"><a href="' . LienEtatLab($mesInfosFichier->Fichier,2) . '" title="'. TitleEtat(2) . '">' . LienImageOKKO($mesInfosFichier->EtatFichier >= "2") . '</a>
				<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>					
			<td><div class="tooltip"><a href="' . LienEtatLab($mesInfosFichier->Fichier,3) . '" title="'. TitleEtat(3) . '">' . LienImageOKKO($mesInfosFichier->EtatFichier >= "3") . '</a>
				<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>'	
			;				
		}		
		$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($mesInfosFichier->Fichier, $mesInfosFichier->EtatFichier) . '</td>';
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
		//NEW2 UTF-8 return $GLOBALS['maConnexionAPI']->CallServeur('&apiChgEtat='. urlencode(utf8_encode($fichier)) .'&apiEtat=' . $Etat);
		return $GLOBALS['maConnexionAPI']->CallServeur('&apiChgEtat='. urlencode($fichier) .'&apiEtat=' . $Etat);			
	} else {
		return 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($fichier) ;
	}
}

function LienFichierLab($fichier) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	$Extension = strrchr($fichier, '.');
	$LienFichier = "#";
	switch ($Extension) {
		case ".lab0":
			$LienFichier = 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($fichier) ;
			break;
		default:
			$LienFichier = "CMDViewNEW.php". $Environnement . "&fichierLAB=" . urlencode($fichier);
			break;		
	}
  
//$isDebug = true;
	return $LienFichier;
}

function LienOuvrirRepTIRAGE($repertoire) {
	$LienFichier = '#';
	if ($repertoire != ''){
		$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
		$LienFichier = "CATPhotolab.php". $Environnement . "&OpenRep=" . urlencode($repertoire);		
	}
	return $LienFichier;
}

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

function execInBackground($cmd) {
    if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        exec($cmd . " > /dev/null &");  
    }
}
?>