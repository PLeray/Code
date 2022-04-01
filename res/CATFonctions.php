<?php
setlocale(LC_TIME, 'fr_FR');
include_once 'CMDClassesDefinition.php';

class CINFOfichierLab {
	var $Fichier;	
	var $FichierERREUR;	
	var $EtatFichier;  // 0, 1 , 2 , 3
	var $PourcentageAvancement = 0;
	var $SyntheseCMD;
	var $SyntheseCodeCMD;
	var $Compilateur;
	//var $isOuvrable;
    var $NbPlanches = 0;
    var $NbCommandes = 0;
    var $NomEcole;
    var $DateTirage;

    function __construct($myfileName){ // Le chemin complet !
		$tabFICHIERLabo = LireFichierLab($myfileName);
		$this->Fichier = basename($myfileName);
		$this->EtatFichier = substr(strrchr($this->Fichier, '.'),4);
		$this->FichierERREUR = substr($this->Fichier, 0, -5) . '.Erreur';
		$this->AffichageNomCMD = substr($this->Fichier, 11, -5);

		for($i = 0; $i < count($tabFICHIERLabo); $i++){
			$identifiant = substr($tabFICHIERLabo[$i],0,1);
			if (($identifiant != '[') && ($identifiant != '{') && ($identifiant != '#') && ($identifiant != '@') && ($identifiant != '<') && ($identifiant != '')) {
				$this->NbPlanches = $this->NbPlanches + 1 ;
				//echo $this->NbPlanches;
			}else {
				if ($identifiant == '{')  {
					$this->SyntheseCodeCMD = substr(stristr($tabFICHIERLabo[$i], '%%'),1,-1);
					$this->SyntheseCMD = $this->SyntheseCodeCMD;
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
					
					$this->DateTirage = substr($this->Fichier,0,10);
					$this->NomEcole = substr($this->Fichier,11,-5);


				}
				if ($identifiant == '#')  {
					$this->NbCommandes = $this->NbCommandes + 1 ;		
				}				
			}		
		}
	}
    function RepTirage(){
		$leRepTirage = '';
		if ($this->EtatFichier){
			if (stripos($this->NomEcole, '(ISOLEES)') !== false) { // C'est des ISOLEES
				//$leRepTirage = substr($this->Fichier, strripos($this->Fichier, '/'),10) . '-CMD-ISOLEES' ;
				$leRepTirage = substr($this->Fichier, 0, -5);
			}	
			elseif (stripos($this->NomEcole,  '(RECOMMANDES)') !== false) { // C'est des RECOs
				//$leRepTirage = $GLOBALS['FichierDossierRECOMMANDE'] ;
				//$leRepTirage = $this->DateTirage . '-' .$this->NomEcole ;
				$leRepTirage = substr($this->Fichier, 0, -5);
			}	
			else{
					//$leRepTirage = $this->DateTirage . '-' .$this->NomEcole ;	
					$leRepTirage = substr($this->Fichier, 0, -5);
			}	
		}
		return $leRepTirage;
    } 
	/*
    function GenSyntheseCommande(){
		try {
			$unBilan = 'Le groupe de commandes comprend ' . $NbCommandes . ' commandes.%';
			$unTab = array_count_values($GLOBALS['TabResumeProduit']);
			
			foreach ($unTab as $key => $row) {
				$unBilan .= '- ' .$key . ': ' . $unTab[$key] . '%';
			}
			//echo "erreur avant " . error_get_last();
			$unTab = array_count_values($GLOBALS['TabResumeFormat']);
			//echo "erreur apres " . error_get_last();
			//var_dump($GLOBALS['TabResumeFormat']);
			//$unBilan .= '%%%Pour un total de ' . $NbCommandes . ' commandes individuelles%';
			$unBilan .= '%%%Il y a ' . count($GLOBALS['TabResumeProduit']) . ' fichiers a creer au laboratoire.%';
			foreach ($unTab as $key => $row) {
				$unBilan .= '- Format ' .$key . ': ' . $unTab[$key] . '%';
			}	
			$unBilan .= '}';
			//var_dump($unBilan);
			
			return $unBilan;
			//return $TabCSV;
		} catch (ErrorException $e) {
			$unBilan ='';
			return $unBilan;
		}
		$this->SyntheseCodeCMD =  $unBilan;
    }
    function TabFormats(){
		$tabFormatsNombre = array();
		return $tabFormatsNombre;
    } 			
    function TabProduits(){

    } 	
	*/
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

class CINFOfichierArbo {
	var $Fichier;	
	var $FichierERREUR;	
	var $EtatFichier;  // 0, 1 , 2 , 3
	var $PourcentageAvancement = 0;
	var $SyntheseCMD;
	var $Compilateur;
	//var $isOuvrable;
    var $NbPlanches = 0;
    var $AffichageNomSOURCE;
    var $NomEcole;
    var $DateTirage;
	var $TypeArbo;

    function __construct($myfileName){ // Le chemin complet !
	
		$tabFICHIERLabo = LireFichierLab($myfileName);
		$this->Fichier = basename($myfileName);
		$this->EtatFichier = substr($this->Fichier, -1,1);
		$this->FichierERREUR = substr($this->Fichier, 0, -5) . '.Erreur';
		$this->AffichageNomSOURCE = substr($this->Fichier, 5, -5);
		//echo $this->Fichier . '<br>';
		//$this->DateTirage = substr($this->Fichier, 5, 10);
		$this->TypeArbo = substr($this->Fichier, 0, strpos($this->Fichier, '-'));

		for($i = 0; $i < count($tabFICHIERLabo); $i++){
			$identifiant = substr($tabFICHIERLabo[$i],0,1);
			if (($identifiant != '[') && ($identifiant != '{') && ($identifiant != '#') && ($identifiant != '@') && ($identifiant != '<') && ($identifiant != '')) {
				$this->NbPlanches = $this->NbPlanches + 1 ;
			}
			else {
				if ($identifiant == '{')  {
					$this->SyntheseCMD = substr(stristr($tabFICHIERLabo[$i], '%%'),1,-1);
					$this->SyntheseCMD = str_replace("%", "<br>", $this->SyntheseCMD);
					$this->SyntheseCMD = str_replace("{", "<br>", $this->SyntheseCMD) . "<br>";

					$txtAvancement = str_replace(",", ".", substr(stristr($tabFICHIERLabo[$i], '%%', true), 9));
					$this->PourcentageAvancement = 100 * floatval($txtAvancement );
					//echo '$txtAvancement: ' . $txtAvancement . '  this->PourcentageAvancement : ' . $this->PourcentageAvancement ;
		
				}
			}
		}
	}
	function NBfichiersARBOWEB() {
		$Dossier = $GLOBALS['repWEBARBO'] . $this->RepTirage();
		$NbFicher = 0;
		$files = glob($Dossier . '/*',GLOB_BRACE);	
		foreach($files as $SousDossier) {
			$NbFicher = $NbFicher + count(glob($Dossier . '/'.  $SousDossier . '/*.*{jpg,jpeg}',GLOB_BRACE));
		}
		return $NbFicher;
	}
	
    /*	*/
	function RepTirage(){
		$leRepTirage = '';
		if ($this->EtatFichier){
			$leRepTirage = substr($this->Fichier, 0, -5) ;	
			//js : g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'WEB-ARBO/LUMYS-' +  uneSource.NomProjet;
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
		return $RetourEtat;  	
	}	
}

/////////////////// Les Fonctions ... ///////////////////    
function SuprimeFichier($strFILELAB){
	$fichier = $GLOBALS['repCMDLABO'] . $strFILELAB ;
	if (file_exists($fichier)){ 
		// NEW SUP ARBORESCENCE FICHIER		
		//if (substr($strFILELAB, -1, 1) == '1'){
			if($GLOBALS['isDebug']){
				echo '<br>LAB1 : Là ON SUPPRIME ';
			}
			if (substr($strFILELAB, -5, 4) == '.lab'){
				$mesInfosFichier = new CINFOfichierLab($fichier); 				
				if ($mesInfosFichier->RepTirage() != '') {
					if($GLOBALS['isDebug']){
						Echo '<br>Le dossier Arbo a supprimer est  ' . $mesInfosFichier->RepTirage();
					}
					SuprArborescenceDossier($GLOBALS['repTIRAGES'].$mesInfosFichier->RepTirage());
					SuprArborescenceDossier($GLOBALS['repMINIATURES'].$mesInfosFichier->RepTirage());
				}
			}
			else{
				$mesInfosFichier = new CINFOfichierArbo($fichier); 
				if ($mesInfosFichier->RepTirage() != '') {
					SuprArborescenceDossier($GLOBALS['repWEBARBO'].$mesInfosFichier->RepTirage());
				}
			}
		//}

		//Supression du .lab0
		SuprFichier($fichier);
		$Extension = '.' . TypeFichier($strFILELAB);
		$strBaseName = substr($fichier, 0, strpos($fichier, $Extension));
		SuprFichier($strBaseName . '.Erreur');			
	}
}

function RenommerFichierEtDossiers($nomFichierAncien, $nomFichierNouveau){
	$fichier = $GLOBALS['repCMDLABO'] . $nomFichierAncien ;
	RenommerFichierOuDossier($fichier, $GLOBALS['repCMDLABO'] . $nomFichierNouveau);		
}

function LienIMGSuprFichierLab($fichier, $Etat) {
	$Lien = 'CATPhotolab.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($fichier);// . '&apiEtat=' . $Etat;
	$retour = '';
	if ($Etat == 'Erreur'){
		$retour = '<a href="'.$Lien.'"  title="' . "Avant de re-créer les planches, supprimez l'alerte d'erreur." .  '"><img src="img/poubelle.png"></a>'; 		
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

function ChangeEtat($strFILELAB, $Etat){ // QD On revient du serveur
	$Extension = '.' . TypeFichier($strFILELAB);
	
	$strBaseName = substr($strFILELAB, 0, strpos($strFILELAB, $Extension));

	if(($Etat == "3" )&& ($strBaseName == $GLOBALS['FichierDossierRECOMMANDE'])){
		if ($GLOBALS['isDebug']) echo ' X chgt etat   BaseName : ' .$strBaseName;		
		// ON Verifie si le nom de Dossier est OK pour le Laboratoire et suivit !

	
	}
	else{
		if ($GLOBALS['isDebug']) echo ' X chgt etat   BaseName : ' .$strBaseName;	

		RenommerFichierOuDossier($GLOBALS['repCMDLABO'] . utf8_decode( $strFILELAB), $GLOBALS['repCMDLABO'] . utf8_decode($strBaseName) . $Extension . $Etat);

			$fichierdeBase = $GLOBALS['repCMDLABO'] . utf8_decode($strBaseName) ;
			if ($Etat > 2){
				SuprFichier($fichierdeBase . $Extension . '0');
				SuprFichier($fichierdeBase . $Extension . '1');
				SuprFichier($fichierdeBase . '.Erreur');
			}
			return 'OK';

		$CMDhttpLocal = '?apiChgEtat='. $strFILELAB .'&apiEtat=' . $Etat ;
		echo $CMDhttpLocal;		

	}
}

function RemplacementNomCommande($AncienNomDeFichier, $NouveauNomDeFichier){ // Nouveau Nom SANS extention
	//$NomTemporaire =utf8_decode($GLOBALS['FichierDossierRECOMMANDE']);
	$AncienNomDeDossier =  substr(utf8_decode($AncienNomDeFichier),0,-5);
	$NouveauNomDeDossier =  substr(utf8_decode($NouveauNomDeFichier),0,-5);
	if ($GLOBALS['isDebug']){

		echo '<br><br><br>$Ancien NomDeFichier     : ' . $AncienNomDeFichier;
		echo '<br>$Nouveau NomDeFichier     : ' . $NouveauNomDeFichier;		

		echo '<br><br><br>$Ancien NomDeDossier     : ' . $AncienNomDeDossier;
		echo '<br>$Nouveau NomDeDossier     : ' . $NouveauNomDeDossier;				
	}
	//substr(    ,0,-5)

	RenommerFichierOuDossier($GLOBALS['repCMDLABO'] . utf8_decode($AncienNomDeFichier) , $GLOBALS['repCMDLABO'] . utf8_decode($NouveauNomDeFichier));
	RenommerFichierOuDossier($GLOBALS['repMINIATURES'] . $AncienNomDeDossier , $GLOBALS['repMINIATURES'] .  $NouveauNomDeDossier);	
	RenommerFichierOuDossier($GLOBALS['repTIRAGES'] . $AncienNomDeDossier,  $GLOBALS['repTIRAGES'] . $NouveauNomDeDossier);	

	SuprFichier($GLOBALS['repCMDLABO'] . $AncienNomDeDossier .'.lab0');			
	SuprFichier($GLOBALS['repCMDLABO'] . $AncienNomDeDossier .'.lab1');
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
	$NewFichier = $strURL_RECFileLab;  // . "_rec" ;
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

function AfficheTableauCommandeEnCours(&$nb_fichier, $isEnCours){
	//echo '$cmd = ' . ($GLOBALS['isDebug']?'':($ParamGET ?'?apiAMP=OK':''));
	//setlocale(LC_TIME, 'french');
	$affiche_Tableau = '';
	$tabFichierLabo = TableauRepFichier('.lab', $isEnCours);
	rsort($tabFichierLabo);
	for($i = 0; $i < count($tabFichierLabo); $i++){
		// Un objet pour récupérer les infos Fichier !!! 
		$mesInfosFichier = new CINFOfichierLab($GLOBALS['repCMDLABO'] . $tabFichierLabo[$i]); 
		$nb_fichier++;

		$laCouleur = ($mesInfosFichier->EtatFichier == 2)?'GreenYellow':'white';
		$affiche_Tableau .=
		'<tr style="background-color:'.$laCouleur.'">
		
			<td class="titreCommande" >' . $mesInfosFichier->DateTirage .'</td>			
			<td align="left" class="titreCommande" ><div class="tooltip">' . $mesInfosFichier->AffichageNomCMD . '<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>		
			<td>'. CodeLienImageDossier($mesInfosFichier) . '</td>	';

			/*
			$affiche_Tableau .='			
			<td><div class="tooltip"><a href="' . LienFichierLab($mesInfosFichier->Fichier) . '">
			<img src="img/' . $mesInfosFichier->EtatFichier . '-Etat.png"></a></div></td>';
			*/
			
		if(($mesInfosFichier->EtatFichier < 2)&&($mesInfosFichier->EtatFichier > 0)){
			$affiche_Tableau .=	'
			<td colspan=4>';
						
			if (file_exists($mesInfosFichier->LienFichierERREUR())){	
			/*$affiche_Tableau .=	'			
				<div class="tooltip"><a href="'. $mesInfosFichier->LienFichierERREUR() . '" title="Afficher les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : il y a des erreurs !</font></a>
					' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
					</div>			
				</div>';*/

				/* NEW */
				$affiche_Tableau .=	'			
				<div class="TitreErreur" onclick="VisuErreur(\''. $mesInfosFichier->FichierERREUR . '\');" title="Afficher les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : Erreurs !</font>' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '								
				</div>';	

				$affiche_Tableau .=	
				'<div id="'. $mesInfosFichier->FichierERREUR .'" class="ContenufichierErreur">';
				$affiche_Tableau .=	'<div class="TitreErreur" onclick="VisuErreur(\''. $mesInfosFichier->FichierERREUR . '\');" title="Fermer les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/KO.png" alt="FERMER">
					<font size="5">   '.$mesInfosFichier->NomEcole . '</font>
					<br>
					<font size="3" color="red"style="text-align: right;">CORRIGEZ les erreurs listée(s) ci-dessous, puis relancez le plugin PhotoLab </font>'
					. LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
				</div>
				<br>';				
				
				$affiche_Tableau .=	LireFichierErreur($mesInfosFichier->LienFichierERREUR());
				$affiche_Tableau .=	'</div>';				

			}
			$affiche_Tableau .=	'
			<div class="boiteProgressBar">			
				<div class="progressBar" id="AV'. $mesInfosFichier->Fichier .'" style="width:'.$mesInfosFichier->Avancement().'%;" >';
				$affiche_Tableau .=	'<font size="2" >'. $mesInfosFichier->Compilateur . '→ Création des planches : '. number_format($mesInfosFichier->Avancement(), 0).' %</font>';			
				$affiche_Tableau .=	'</div>
			</div>';

			/* pour Ajax defilement Barres 
			if ($mesInfosFichier->EtatFichier == 1) {array_push($GLOBALS['tabFichiersEnCoursDeCompilation'], $mesInfosFichier->Fichier);}
			*/
		}else {
			$affiche_Tableau .=	'  
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,2) . LienImageProgression($mesInfosFichier->EtatFichier,2) . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,3) . LienImageProgression($mesInfosFichier->EtatFichier,3) . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,4) . LienImageProgression($mesInfosFichier->EtatFichier,4) . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,5) . LienImageProgression($mesInfosFichier->EtatFichier,5) . '</a></td>'	
			;				
		}
		if ($isEnCours){
			$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($mesInfosFichier->Fichier, $mesInfosFichier->EtatFichier) . '</td>';
		}
	}
	$affiche_Tableau .=	'</tr>';
	return $affiche_Tableau;
}


//Pour l'historique a changer !!!!!
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

		$laCouleur = ($mesInfosFichier->EtatFichier == 2)?'GreenYellow':'white';
		$affiche_Tableau .=
		'<tr style="background-color:'.$laCouleur.'">
			<td><div class="tooltip"><a href="' . LienFichierLab($mesInfosFichier->Fichier) . '">
					<img src="img/' . $mesInfosFichier->EtatFichier . '-Etat.png"></a></div></td>			
			<td class="titreCommande" >' . $mesInfosFichier->DateTirage .'</td>			
			<td align="left" class="titreCommande" ><div class="tooltip"><a href="' . LienFichierLab($mesInfosFichier->Fichier) . '">'.LienImageVoir($mesInfosFichier->EtatFichier).' ' . $mesInfosFichier->AffichageNomCMD . '</a>
				<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>		
			<td>'. CodeLienImageDossier($mesInfosFichier) . '</td>	
			
			<td>'. LienVoirMiseEnPochette($mesInfosFichier) . '</td>	
			<td>'. LienRecherchePlanche($mesInfosFichier) . '</td>';
			
		if($mesInfosFichier->EtatFichier < 2){
			$affiche_Tableau .=	'
			<td colspan=4>';
						
			if (file_exists($mesInfosFichier->LienFichierERREUR())){	
			/*$affiche_Tableau .=	'			
				<div class="tooltip"><a href="'. $mesInfosFichier->LienFichierERREUR() . '" title="Afficher les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : il y a des erreurs !</font></a>
					' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
					</div>			
				</div>';*/

				/* NEW */
				$affiche_Tableau .=	'			
				<div class="TitreErreur" onclick="VisuErreur(\''. $mesInfosFichier->FichierERREUR . '\');" title="Afficher les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/ERREUR.png" alt="ERREUR">
					<font size="3" color="red">ATTENTION : Erreurs !</font>' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '								
				</div>';	

				$affiche_Tableau .=	
				'<div id="'. $mesInfosFichier->FichierERREUR .'" class="ContenufichierErreur">';
				$affiche_Tableau .=	'<div class="TitreErreur" onclick="VisuErreur(\''. $mesInfosFichier->FichierERREUR . '\');" title="Fermer les erreurs sur '.$mesInfosFichier->NomEcole . '">
					<img src="img/KO.png" alt="FERMER">
					<font size="5">   '.$mesInfosFichier->NomEcole . '</font>
					<br>
					<font size="3" color="red"style="text-align: right;">CORRIGEZ les erreurs listée(s) ci-dessous, puis relancez le plugin PhotoLab </font>'
					. LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
				</div>
				<br>';				
				
				$affiche_Tableau .=	LireFichierErreur($mesInfosFichier->LienFichierERREUR());
				$affiche_Tableau .=	'</div>';				

			}
			//echo $mesInfosFichier->Avancement();   
			$affiche_Tableau .=	'
			<div class="boiteProgressBar">			
				<div class="progressBar" id="AV'. $mesInfosFichier->Fichier .'" style="width:'.$mesInfosFichier->Avancement().'%;" >';
				$affiche_Tableau .=	'<font size="2" >'. $mesInfosFichier->Compilateur . '→ Création : '. number_format($mesInfosFichier->Avancement(), 1).'%</font>';			
				$affiche_Tableau .=	'</div>
			</div>';	
			
			/* pour Ajax defilement Barres 
			if ($mesInfosFichier->EtatFichier == 1) {array_push($GLOBALS['tabFichiersEnCoursDeCompilation'], $mesInfosFichier->Fichier);}
			*/
		}else {
			$affiche_Tableau .=	'  
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,2) . LienImageOKKO($mesInfosFichier->EtatFichier >= "2") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,3) . LienImageOKKO($mesInfosFichier->EtatFichier >= "3") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,4) . LienImageOKKO($mesInfosFichier->EtatFichier >= "4") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,5) . LienImageOKKO($mesInfosFichier->EtatFichier >= "5") . '</a></td>'	
			;				
		}
		if ($isEnCours){
			$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($mesInfosFichier->Fichier, $mesInfosFichier->EtatFichier) . '</td>';
		}
	}
	$affiche_Tableau .=	'</tr>';
	return $affiche_Tableau;
}

function LireFichierErreur($unFichierErreur){

	$affiche_Erreur = '<br><br> Identifiez l\'erreur en fonction des informations ci dessous...<br><br>';	
	$affiche_Erreur .=  nl2br(implode("", file($unFichierErreur)));

	return $affiche_Erreur;

	/*	
	$affiche_Erreur =	'';
	$affiche_Erreur .=  $unFichierErreur;
	$affiche_Erreur .= '<br><br> Identifiez l\'erreur en fonction des informations ci dessous...<br><br>';
	
	$affiche_Erreur .=  nl2br(implode("", file($unFichierErreur)));

	return $affiche_Erreur;	
	*/
}

function AfficheTableauCMDWEB(&$nb_fichier, $isEnCours){
	$affiche_Tableau = '';
	$tabFichierLabo = TableauRepFichier('.web', $isEnCours);
	
	rsort($tabFichierLabo);
	
	for($i = 0; $i < count($tabFichierLabo); $i++){
			$nb_fichier++;	
		$NomFichier = $tabFichierLabo[$i];	

			// Un objet pour récupérer les infos Fichier !!! 
			$mesInfosFichier = new CINFOfichierArbo($GLOBALS['repCMDLABO'] . $tabFichierLabo[$i]); 	
			
			$laCouleur = ($mesInfosFichier->EtatFichier == 2)?'GreenYellow':'white';
			$affiche_Tableau .=
			'<tr style="background-color:'.$laCouleur.'">			
				<td>'.LienImageEtatWEB($mesInfosFichier->EtatFichier).'</a></td>				
				<td class="titreCommande" >' . $mesInfosFichier->TypeArbo .'</td>	
				<td  class="titreCommande" align="left"><div class="tooltip">' . $mesInfosFichier->AffichageNomSOURCE . '</a>
					<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>		
				<td>' . $mesInfosFichier->NBfichiersARBOWEB() . '</a></td>';

			if($mesInfosFichier->EtatFichier < 2){
				$affiche_Tableau .=	'
				<td colspan=3>';
				
				if (file_exists($mesInfosFichier->LienFichierERREUR())){
					//echo $mesInfosFichier->LienFichierERREUR();
					$affiche_Tableau .=	'			
					<div class="tooltip" title="Afficher les erreurs">
						<img src="img/ERREUR.png" alt="ERREUR">
						
						<font size="3" color="red">ATTENTION : Erreurs !</font></a>
						' . LienIMGSuprFichierLab($mesInfosFichier->FichierERREUR, 'Erreur') . '
						</div>			';					
				}
				$affiche_Tableau .=	'
				<div class="boiteProgressBar">
				<div class="progressBar" id="AV'. $mesInfosFichier->Fichier .'" style="width:'.$mesInfosFichier->Avancement().'%;" >';				
				$affiche_Tableau .=	'<font size="2" >'. $mesInfosFichier->Compilateur . '→ Création : ' . number_format($mesInfosFichier->Avancement(), 1).'%</font>';			
				$affiche_Tableau .=	'</div>
					</div>';	
				

			/* pour Ajax defilement Barres 
			if ($mesInfosFichier->EtatFichier == 1) {array_push($GLOBALS['tabFichiersEnCoursDeCompilation'], $mesInfosFichier->Fichier);}
			*/


			}else {
				$affiche_Tableau .=	'
				<td><div class="tooltip"><a href="' . LienEtatLab($mesInfosFichier->Fichier,2) . LienImageOKKO($mesInfosFichier->EtatFichier >= "2") . '</a>
					<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>					
				<td><div class="tooltip"><a href="' . LienEtatLab($mesInfosFichier->Fichier,3) . LienImageOKKO($mesInfosFichier->EtatFichier >= "3") . '</a>
					<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>	
				<td><div class="tooltip"><a href="' . LienEtatLab($mesInfosFichier->Fichier,4) . LienImageOKKO($mesInfosFichier->EtatFichier >= "4") . '</a>
					<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>'						
				;				
			}		
			$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($mesInfosFichier->Fichier, $mesInfosFichier->EtatFichier) . '</td>';
		//}
			
			
		}
				
	$affiche_Tableau .=	'</tr>';
	return $affiche_Tableau;
}

function TitleEtat($fichier, $Etat){
	if (strrchr($fichier, '.') != ".lab0"){
		switch ($Etat) {
		case "1":
			$retourMSG = "Les planches sont en cours de création.";
			break;			
		case "2":
			$retourMSG = "Les planches sont toutes crées : cliquez pour voir les commandes, rechercher une planche ou une commande.";
			break;		
		case "3":
			$retourMSG = "Ajustez le nom de votre groupe de commandes avant de l'envoyer à imprimer.";
			break;
		case "4":
			$retourMSG = "Accédez à l'interface de mise en pochette rapide de vos commandes et préparer l'expédition à vos clients.";
			break;		
		case "5":
			$retourMSG = "Vous avez expédié vos commandes ? Cliquez ici pour archiver votre groupe de comamndes.";
			break;	
		}
	}else{
		$retourMSG = "Lancez le plugin PhotoLab pour Photoshop pour créer les planches commandées...";

	}	

	return $retourMSG;
}

function TableauRepFichier($ExtFichier, $isEnCours){
	// NETOYAGE Dossier FICHIERS DE COMMANDES!!
	if($dossier = opendir($GLOBALS['repCMDLABO'])){
		while(false !== ($fichier = readdir($dossier))){
			$Extension = strrchr($fichier, '.');
			//$fichierdeBase = substr($fichier, 0, -5);
			//echo $fichierdeBase;
			if (substr($Extension,-1) > 2){  // dernier charactere de la chaine
				$fichierdeBase = $GLOBALS['repCMDLABO'] . substr($fichier, 0, -5) . $ExtFichier;
				//echo $GLOBALS['repCMDLABO'] . substr($fichier, 0, -5) . $ExtFichier;
				SuprFichier($fichierdeBase . '0');
				SuprFichier($fichierdeBase . '1');
				SuprFichier($fichierdeBase . '.Erreur');
			}			
		} 
		closedir($dossier);
	} else {
		 echo 'Erreur TableauRepFichier : Le dossier n\' a pas pu être ouvert';
	}
	// SCAN DU Dossier 
	$tabFichierLabo = array();
	$EtatFinal = 5;
	if($dossier = opendir($GLOBALS['repCMDLABO'])){
		while(false !== ($fichier = readdir($dossier))){
			$Extension = strrchr($fichier, '.');
			$EtatFinal = ($ExtFichier == '.lab'?5:4);
			
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
		 echo 'Erreur TableauRepFichier : Le dossier n\' a pas pu être ouvert';
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
		} 
		/* pour enlever lab1 qd Lab 2 :*/
		elseif ($Extension == $ExtFichier . '1'){// Ne pas afficher 2 lignes ! Seulement si y a pas un .lab2 du même nom!
			$fichier = $tabFichierLabo[$i];		
			//remettre  
			$fichierEtat2 = substr($fichier, 0, strpos($fichier, $ExtFichier)). $ExtFichier . '2';
			//echo $fichierEtat2;
			$YAutre = false;
			for($j = 0; $j < count($tabFichierLabo); $j++){				
				if($fichierEtat2 == $tabFichierLabo[$j]){
					$YAutre = true;
					break;
				} else {
					$YAutre = false;				
				}
			}
			if(!$YAutre){array_push($tabFichierLaboSelect,$tabFichierLabo[$i]);}
		} 
		else{
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

function LienImageProgression($EtatActuel,$Etat){
	if (($EtatActuel == 0 ) && ($Etat < 3)){
		return '<img class= "OK" src="img/0-Etat.png">';
	}else{
		$Laclasse = (($EtatActuel<$Etat)?'KO':'OK');
		//return $Lien;
		//$Lien = '<img class= "'.$Laclasse.'" src="img/' . $Etat . '-Etat.png">'
		return '<img class= "'.$Laclasse.'" src="img/' . $Etat . '-Etat.png">';		


	}
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

function LienEtatLab($fichier, $EtatVise) {
	$EtatActuel = substr($fichier,-1);
	$lien = '#';
	if ($EtatActuel > 0){
		if ($EtatVise <= $EtatActuel + 1){	
			if ($EtatVise == 2 ){//
				//echo '<br>' .  'API_Photolab.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiChgEtat='. urlencode($fichier) ;
				//$lien =  'API_Photolab.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiChgEtat='. urlencode($fichier) ;

				$lien =   "CMDRecherche.php" . ArgumentURL() . "&fichierLAB=" . urlencode($fichier) ;
			}			

			//if (($EtatVise == 3 )&& (substr($fichier, 0, -5) == $GLOBALS['FichierDossierRECOMMANDE'])){//
			elseif  ($EtatVise == 3 ){//
				//echo '<br>' .  'API_Photolab.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiChgEtat='. urlencode($fichier) ;
				$lien =  'API_Photolab.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiChgEtat='. urlencode($fichier) ;
			}
			elseif ($EtatVise == 4 ){//
				//echo '<br>' .  'API_Photolab.php' . ArgumentURL() . '&apiInfoMiseEnPochette=OUI'.'&apiChgEtat='. urlencode($fichier) ;
				if (substr($fichier, -1) == $EtatVise){
					$lien =   "CMDCartonnage.php" . ArgumentURL() . "&fichierLAB=" . urlencode($fichier) ;
				}else{
					$lien =  'API_Photolab.php' . ArgumentURL() . '&apiInfoMiseEnPochette=OUI'.'&apiChgEtat='. urlencode($fichier) ;
				}			
			}
			elseif ($EtatVise == 5 ){//
				//echo '<br>' .  'API_Photolab.php' . ArgumentURL() . '&apiInfoExpeditionArchivage=OUI'.'&apiChgEtat='. urlencode($fichier) ;
				$lien =  'API_Photolab.php' . ArgumentURL() . '&apiInfoExpeditionArchivage=OUI'.'&apiChgEtat='. urlencode($fichier) ;
			}
			else{
			//NEW2 UTF-8 return $GLOBALS['maConnexionAPI']->CallServeur('&apiChgEtat='. urlencode(utf8_encode($fichier)) .'&apiEtat=' . $EtatVise);
			$lien =  $GLOBALS['maConnexionAPI']->CallServeur('&apiChgEtat='. urlencode($fichier) .'&apiEtat=' . $EtatVise);		
			
			}
		}

	}else {
		$lien = 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($fichier) ;
	}
	return $lien . '"  title="'. TitleEtat($fichier, $EtatVise) . '">';
}
/* */
function LienFichierLab($fichier) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	$Extension = strrchr($fichier, '.');
	$LienFichier = "#";
	switch ($Extension) {
		case ".lab0":
			$LienFichier = 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($fichier) ;
			break;
		default:
			$LienFichier = "CMDCartonnage.php". $Environnement . "&fichierLAB=" . urlencode($fichier);
			break;		
	}
  
//$isDebug = true;
	return $LienFichier;
}

function LienVoirMiseEnPochette($infosFichier) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	$Extension = strrchr($infosFichier->Fichier, '.');
	$Lien = "#";
	switch ($Extension) {
		case ".lab0":
			$Lien = 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($infosFichier->Fichier) ;
			break;
		default:
			$Lien = "CMDCartonnage.php". $Environnement . "&fichierLAB=" . urlencode($infosFichier->Fichier);
			break;		
	}

	$LienImage = '<img src="img/VisualisationKO.png" alt="Mise en pochette non disponible">';
	if($infosFichier->EtatFichier) {
		$LienImage = '<img src="img/MiseEnPochette.png" alt="Voir écran de mise en pochette">';
	}
	return '<a href="'. $Lien . '">'.$LienImage.'</a>';
}

function LienRecherchePlanche($infosFichier) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	$Extension = strrchr($infosFichier->Fichier, '.');
	$Lien = "#";
	switch ($Extension) {
		case ".lab0":
			$Lien = 'API_Photolab.php' . ArgumentURL() . '&apiPhotoshop=' . urlencode($infosFichier->Fichier) ;
			break;
		default:
			$Lien = "CMDRecherche.php". $Environnement . "&fichierLAB=" . urlencode($infosFichier->Fichier);
			break;		
	}

	$LienImage = '<img src="img/VisualisationKO.png" alt="Mise en pochette non disponible">';
	if($infosFichier->EtatFichier) {
		$LienImage = '<img src="img/searchicon.png" alt="Voir écran de mise en pochette">';
	}
	return '<a href="'. $Lien . '">'.$LienImage.'</a>';
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



// POUR LES RECOMMANDES !
function MAJRecommandes($FichierOriginal, $strTabCMDReco) {
	$NewFichierSeul = $GLOBALS['FichierDossierRECOMMANDE'].".lab0" ;
	$NewFichier = $GLOBALS['repCMDLABO'] . $NewFichierSeul ;
	if (!file_exists($NewFichier)){
		$file = fopen($NewFichier, 'w');
			fputs($file, '[Version : 2.0]'.PHP_EOL );
			fputs($file, '{Etat : 0 : Non enregistre %%Recommandes de tirages dejà effectués}'.PHP_EOL );
		fclose($file); 
	}
	$isRecommande = false; //true;
	
	$TabCMDReco = explode("%", $strTabCMDReco);
	$monGroupeCmdes = new CGroupeCmdes($GLOBALS['repCMDLABO'].$FichierOriginal);
	$resultat = $monGroupeCmdes->Ecrire($TabCMDReco, $isRecommande);

   //Ajouter commande au fichier de reco 
   file_put_contents($NewFichier, $resultat."\n", FILE_APPEND | LOCK_EX);

  return $NewFichierSeul;
}

// POUR LES COMMANDES LIBRES  ! A REVOIR
function MAJCommandesLibres($SourceDesCMD, $strTabCMDLibre) {
	$NewFichierSeul = $GLOBALS['FichierDossierCMDESLIBRE'].".lab0" ;
	$NewFichier = $GLOBALS['repCMDLABO'] . $NewFichierSeul ;
	if (!file_exists($NewFichier)){
		$file = fopen($NewFichier, 'w');
			fputs($file, '[Version : 2.0]'.PHP_EOL );
			fputs($file, '{Etat : 0 : Non enregistre %%Commandes de tirages Libres }'.PHP_EOL );
		fclose($file); 
	}
	/*
	$isRecommande = false; //true;
	$TabCMDLibre = explode("%", $strTabCMDLibre);

	if($GLOBALS['isDebug']){
		var_dump($TabCMDLibre);
	}	
	*/	
	$resultat = $SourceDesCMD ."\n";
	//date("j, n, Y"); 
	//date("Y-m-d")
	$resultat .= '#Tirages d\'exemples du ' . date("d / F") ."#\n";

	$resultat .= str_replace($GLOBALS['SeparateurInfoPlanche'] , "\n", $strTabCMDLibre);
	/*
	$monGroupeCmdes = new CGroupeCmdes($GLOBALS['repCMDLABO']. $NewFichierSeul);
	//// ????
	$resultat = $monGroupeCmdes->Ecrire($TabCMDLibre, $isRecommande);
	//// ????
	*/

   //Ajouter commande au fichier de commande Libres 
   file_put_contents($NewFichier, $resultat."\n", FILE_APPEND | LOCK_EX);

  return $NewFichierSeul;
}

function CreationDossier($nomDossier) {
	if (!is_dir($nomDossier)) {
		mkdir($nomDossier, 0777, true);
	}
	return $nomDossier;
}

function CodeLienImageDossier($mesInfosFichier){	

	$Lien = $GLOBALS['g_IsLocalMachine'] && ($mesInfosFichier->EtatFichier > 0);
	$DossierOK =($Lien )? 'OK':'KO' ;
	$codeHTML = '<div class="containerCMDPLanche">
		<div class="txtCMDPLanche">' . $mesInfosFichier->NbCommandes . ' <span class="mini">commandes</span><br>' . $mesInfosFichier->NbPlanches . ' <span class="mini">planches</span></div>'. '<img src="img/Dossier' . $DossierOK . '.png"></div>';	
	
	if ($Lien) {
		$codeHTML = '<div class="tooltip">
		<a href="' . LienOuvrirDossierOS($mesInfosFichier->RepTirage(),'CATPhotolab') . '" >' . $codeHTML . '</a>
		<span class="tooltiptext"><br>Cliquez pour aller vers le dossier des planches crées<br><br></span></div>';
		
	}
	$codeHTML = $codeHTML ;
	
	return $codeHTML;
	
}


?>