<?php
setlocale(LC_TIME, 'fr_FR');
include_once 'CMDClassesDefinition.php';
class CINFOfichierLab {
	var $Fichier;	
	var $FichierERREUR;	
	var $EtatFichier;  // 0, 1 , 2 , 3
	var $PourcentageAvancement = 0;
	//var $SyntheseCMD;
	var $Compilateur;
	var $NbPlanches = 0;
	var $NbCommandes = 0;
	var $NomEcole;
	var $DateTirage;

	//var $leCProjetSource;  // CProjetSource

	var $TabResumeProduit = array();
	var $TabResumeFormat = array();

	function __construct($myfileName){ // Le chemin complet !
		$tabLignesFichierLabo = LireFichierLab($myfileName);
		$this->Fichier = basename($myfileName);
		$this->EtatFichier = substr(strrchr($this->Fichier, '.'),4);
		$this->FichierERREUR = substr($this->Fichier, 0, -5) . '.Erreur';
		$this->AffichageNomCMD = substr($this->Fichier, 11, -5);

		$this->DateTirage = substr($this->Fichier,0,10);
		$this->NomEcole = substr($this->Fichier,11,-5);



		//for($i = 0; $i < count($tabLignesFichierLabo); $i++){
		for($i = 0; $i < 2; $i++){ //Juste les 2 premieres lignes
			$identifiant = substr($tabLignesFichierLabo[$i],0,1);
			if ($identifiant == '[')  {
				$this->Compilateur = strstr(strrchr($tabLignesFichierLabo[$i], '%'), 1, -1); // enlever le } de la fin de ligne
			}		
			if ($identifiant == '{')  {
				$this->LireLigneSyntheseCMD(substr(stristr($tabLignesFichierLabo[$i], '%%'),2,-1)); // la partie Résumé
				$this->PourcentageAvancement = 100 * floatval(str_replace(",", ".", substr(stristr($tabLignesFichierLabo[$i], '%%', true), 9)));	
				
			}
		}
		
	}	
	/*
	function SyntheseCMDDepuisLigne($Ligne){	
		$this->LireLigneSyntheseCMD(substr(stristr($Ligne, '%%'),1,-1)); // la partie Résumé
		//$this->SyntheseCMD = substr(stristr($Ligne, '%%'),1,-1);
		//$this->SyntheseCMD = str_replace("%", "<br>", $this->SyntheseCMD);
		//$this->SyntheseCMD = str_replace("{", "<br>", $this->SyntheseCMD) . "<br>";
		$this->PourcentageAvancement = 100 * floatval(str_replace(",", ".", substr(stristr($Ligne, '%%', true), 9)));		
	}	
	*/

	function LireLigneSyntheseCMD($Ligne){	// que la partie concernant la Synthese
		if (strpos($Ligne,'NBPLA') > 1){
			$this->TabListeFormats = array();		
			$this->TabListeProduits = array();	
			
			$morceau = explode("%", substr($Ligne,0,strpos($Ligne,'NBPLA'))); // avant 'NBPLA'
			
			$this->NbCommandes = substr($morceau[0],strpos($Ligne,'NBCMD')+5);	
			for($i = 1; $i < count($morceau); $i++){
				if (strpos($morceau[$i],' : ') > 1){
					$nbProduits = explode(' : ', $morceau[$i]);
					$this->TabResumeProduit[$nbProduits[0]] = $nbProduits[1];
				}

			}		
			$morceau = explode("%", substr($Ligne,strpos($Ligne,'NBPLA') + 5)); // apres 'NBPLA'
			$this->NbPlanches = $morceau[0];
			for($i = 1; $i < count($morceau); $i++){
				if (strpos($morceau[$i],' : ') > 1){					
					$nbFormats = explode(' : ', $morceau[$i]);
					//echo ' nbFormats0='. $nbFormats[0] . ' nbFormats1='. $nbFormats[1];	
					$this->TabResumeFormat[$nbFormats[0]] = $nbFormats[1];
				}				
			}	
		}
		//var_dump($this->TabResumeProduit);
	}	
	function EcrireLigneSyntheseCMD(){	
		$str = 'NBCMD' . $this->NbCommandes . '%';
		foreach ($this->TabResumeProduit as $key => $row) {
			$str .= $key . ' : ' . $this->TabResumeProduit[$key] . '%';
		}
		$str .= 'NBPLA' . $this->NbPlanches . '%';
		foreach ($this->TabResumeFormat as $key => $row) {
			$str .= $key . ' : ' . $this->TabResumeFormat[$key] . '%';
		}	
		return $str;		
	}				

    function RepTirage(){
		$leRepTirage = '';
		if ($this->EtatFichier){
			if (stripos($this->NomEcole, '(ISOLEES)') !== false) { // C'est des ISOLEES
				//$leRepTirage = substr($this->Fichier, strripos($this->Fichier, '/'),10) . '-CMD-ISOLEES' ;
				$leRepTirage = substr($this->Fichier, 0, -5);
			}	
			elseif (stripos($this->NomEcole,  '(RECOMMANDES)') !== false) { // C'est des RECOs
				$leRepTirage = substr($this->Fichier, 0, -5);
			}	
			else{
					$leRepTirage = substr($this->Fichier, 0, -5);
			}	
		}
		return $leRepTirage;
    } 
	/**/
	function TexteSyntheseCommande(){	
		$unBilan = 'Il y a ' . $this->NbCommandes . ' commandes dans ce groupe de commandes.<br>';
		foreach ($this->TabResumeProduit as $key => $row) {
			$unBilan .= '- ' .$key . ' : ' . $this->TabResumeProduit[$key] . '<br>';
		}
		$unBilan .= 'Il y a ' . $this->NbPlanches . ' fichiers a imprimer.<br>';
		foreach ($this->TabResumeFormat as $key => $row) {
			$unBilan .= '- Format ' .$key . ' : ' . $this->TabResumeFormat[$key] . '<br>';
		}	
		return $unBilan;	
	}	

    function MAJSyntheseCommande(){
		try {
			$tabLignesFichierLabo = LireFichierLab($GLOBALS['repCMDLABO'] .$this->Fichier);
			$ProduitenCours = '';
			$TabListeProduits = array();
			$TabListeFormats = array();
			$this->NbCommandes = 0;
			$this->NbPlanches = 0;
			for($i = 0; $i < count($tabLignesFichierLabo); $i++){			
				$identifiant = substr($tabLignesFichierLabo[$i],0,1);
				if (($identifiant != '[') && ($identifiant != '{') && ($identifiant != '#') && ($identifiant != '@') && ($identifiant != '<') && ($identifiant != '')) {
					$this->NbPlanches += 1 ;
					$curPlanche = new CPlanche($tabLignesFichierLabo[$i]);
					if ($curPlanche->Taille != ''){
						array_push($TabListeFormats,$curPlanche->Taille);	
						array_push($TabListeProduits,$ProduitenCours);
					}			
				}else {	
					if ($identifiant == '#')  {
						$this->NbCommandes += 1 ;		
					}						
					if ($identifiant == '<')  {
						$str = str_replace("<", "", str_replace(">", "", $tabLignesFichierLabo[$i]));
						$morceau = explode("%", $str);		
						if (count($morceau) > 1){$ProduitenCours = $morceau[1];} // Y Le nom de la classe
						else{$ProduitenCours = $morceau[0];} // ya que le produit
					}					
				}		
			}
			$this->TabResumeFormat = array_count_values($TabListeFormats);
			$this->TabResumeProduit = array_count_values($TabListeProduits);

		} catch (ErrorException $e) {
			echo 'Probleme MAJSyntheseCommande';
		}
		//$this->SyntheseCMD =  $unBilan;
    }

	function MAJResumeFichierCommandes(){	
		$monNomFichier = $GLOBALS['repCMDLABO'] .$this->Fichier;
		$tabLignesFichierLabo = array();
		$myfile = fopen($monNomFichier, "r") or die('IMPOSSIBLE de open file : ' .$monNomFichier);
		// Output one line until end-of-file
		while(!feof($myfile)) {
			array_push($tabLignesFichierLabo,trim(fgets($myfile)));
		}
		fclose($myfile);

		$this->MAJSyntheseCommande();
		$ligneResume = '{Etat '. substr($monNomFichier,-1) .' : %%' . $this->EcrireLigneSyntheseCMD() . '}';	
	
		$myfile = fopen($monNomFichier, 'w');
			fputs($myfile,  $tabLignesFichierLabo[0].PHP_EOL );
			fputs($myfile, $ligneResume.PHP_EOL );		
			for($j = 2; $j < count($tabLignesFichierLabo); $j++){								
				fputs($myfile, $tabLignesFichierLabo[$j].PHP_EOL );
			}
		fclose($myfile);
	}

    function TabFormats(){
		$tabFormatsNombre = array();
		return $tabFormatsNombre;
    } 			
    function TabProduits(){

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
	
		$tabLignesFichierLabo = LireFichierLab($myfileName);
		$this->Fichier = basename($myfileName);
		$this->EtatFichier = substr($this->Fichier, -1,1);
		$this->FichierERREUR = substr($this->Fichier, 0, -5) . '.Erreur';
		$this->AffichageNomSOURCE = substr($this->Fichier, 5, -5);
		//echo $this->Fichier . '<br>';
		//$this->DateTirage = substr($this->Fichier, 5, 10);
		$this->TypeArbo = substr($this->Fichier, 0, strpos($this->Fichier, '-'));

		for($i = 0; $i < count($tabLignesFichierLabo); $i++){
			$identifiant = substr($tabLignesFichierLabo[$i],0,1);
			if (($identifiant != '[') && ($identifiant != '{') && ($identifiant != '#') && ($identifiant != '@') && ($identifiant != '<') && ($identifiant != '')) {
				$this->NbPlanches = $this->NbPlanches + 1 ;
			}
			else {
				if ($identifiant == '{')  {
					$this->SyntheseCMD = substr(stristr($tabLignesFichierLabo[$i], '%%'),1,-1);
					$this->SyntheseCMD = str_replace("%", "<br>", $this->SyntheseCMD);
					$this->SyntheseCMD = str_replace("{", "<br>", $this->SyntheseCMD) . "<br>";

					$txtAvancement = str_replace(",", ".", substr(stristr($tabLignesFichierLabo[$i], '%%', true), 9));
					$this->PourcentageAvancement = 100 * floatval($txtAvancement );
					//echo '$txtAvancement: ' . $txtAvancement . '  this->PourcentageAvancement : ' . $this->PourcentageAvancement ;
		
				}
				if ($identifiant == '@')  {
					//NEW UtF8//$morceau = explode("_", utf8_encode(str_replace("@", "", $tabLignesFichierLabo[$i])));
							$morceau = explode("_", str_replace("@", "", $tabLignesFichierLabo[$i]));
							$this->DateTirage = $morceau[0];
							$this->NomEcole = $morceau[1];		
							
							//$this->DateTirage = substr($this->Fichier,0,10);
							$this->NomEcole = substr($this->Fichier,16,-5);		
						}				
			}
		}
	}
	function NBfichiersARBOWEB(&$NbClasse) {
		$Dossier = $GLOBALS['repWEBARBO'] . $this->RepTirage();
		$NbFicher = 0;
		$NbClasse = 0;
		$files = glob($Dossier . '/*',GLOB_BRACE);	
		foreach($files as $SousDossier) {
			$NbFicher = $NbFicher + count(glob($Dossier . '/'.  $SousDossier . '/*.*{jpg,jpeg}',GLOB_BRACE));
			$NbClasse += 1;
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
function SuprimeFichierCMDetDossier($strFILELAB){
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
						Echo '<br>Le dossier de TIRAGES a supprimer est  ' . $mesInfosFichier->RepTirage();
					}
					SuprArborescenceDossier($GLOBALS['repTIRAGES'].$mesInfosFichier->RepTirage());
					SuprArborescenceDossier($GLOBALS['repMINIATURES'].$mesInfosFichier->RepTirage());
				}
			}
			else{
				$mesInfosFichier = new CINFOfichierArbo($fichier); 
				if ($mesInfosFichier->RepTirage() != '') {
					if($GLOBALS['isDebug']){
						Echo '<br>Le dossier WEB-ARBO a supprimer est  ' . $mesInfosFichier->RepTirage();
					}					
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
		if ($GLOBALS['isDebug']) echo ' Y chgt etat   BaseName : ' .$strBaseName;	

		$AncienNomDeFichier = $GLOBALS['repCMDLABO'] . $strFILELAB;
		$NouveauNomDeFichier = $GLOBALS['repCMDLABO'] . $strBaseName . $Extension . $Etat;	


		//$AncienNomDeFichier = utf8_encode($AncienNomDeFichier);
		//$NouveauNomDeFichier = utf8_encode($NouveauNomDeFichier);


		if ($GLOBALS['isDebug']){
			echo '<br><br><br>$Ancien NomDeFichier  40    : ' . $AncienNomDeFichier;
			echo '<br>$Nouveau NomDeFichier   40  : ' . $NouveauNomDeFichier;			
		}
		RenommerFichierOuDossier($AncienNomDeFichier, $NouveauNomDeFichier);

		//$fichierdeBase = $GLOBALS['repCMDLABO'] . utf8_decode($strBaseName) ;
		$fichierdeBase = $GLOBALS['repCMDLABO'] . $strBaseName ;
		if ($Etat > 2){
			SuprFichier($fichierdeBase . $Extension . '0');
			SuprFichier($fichierdeBase . $Extension . '1');
			SuprFichier($fichierdeBase . '.Erreur');
		}
		return 'OK';

		$CMDhttpLocal = '?apiFichierChgEtat='. urlencode($strFILELAB) .'&apiEtat=' . $Etat ;
		echo $CMDhttpLocal;		
	}
}

function RemplacementNomCommande($AncienNomDeFichier, $NouveauNomDeFichier){ // Nouveau Nom SANS extention
	//$NomTemporaire =utf8_decode($GLOBALS['FichierDossierRECOMMANDE']);
	//$AncienNomDeDossier =  substr($AncienNomDeFichier,0,-5);
	//$NouveauNomDeDossier =  substr($NouveauNomDeFichier,0,-5);
	//$AncienNomDeDossier =  substr(($AncienNomDeFichier),0,-5);
	//$NouveauNomDeDossier =  substr(($NouveauNomDeFichier),0,-5);
	
	//$NouveauNomDeFichier = utf8_encode($NouveauNomDeFichier);
	$AncienNomDeDossier =  substr(($AncienNomDeFichier),0,-5);
	$NouveauNomDeDossier =  substr(($NouveauNomDeFichier),0,-5);


	if ($GLOBALS['isDebug']){
		echo '<br><br><br>$Ancien NomDeFichier     : ' . $AncienNomDeFichier;
		echo '<br>$Nouveau NomDeFichier     : ' . $NouveauNomDeFichier;		

		echo '<br><br><br>$Ancien NomDeDossier     : ' . $AncienNomDeDossier;
		echo '<br>$Nouveau NomDeDossier     : ' . $NouveauNomDeDossier;				
	}
	//substr(    ,0,-5)

	RenommerFichierOuDossier($GLOBALS['repCMDLABO'] . $AncienNomDeFichier , $GLOBALS['repCMDLABO'] . $NouveauNomDeFichier);
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

	$mesInfosFichier = new CINFOfichierLab($NewFichier); 
	$mesInfosFichier->MAJResumeFichierCommandes();	

}

function SetCatalog($strTRANSFileLab){
	return 'le transformer ' . $strTRANSFileLab;
}

function AfficheTableauCommandeEnCours(&$nb_fichier, $isEnCours){
	//echo '$cmd = ' . ($GLOBALS['isDebug']?'':($ParamGET ?'?apiAMP=OK':''));
	//setlocale(LC_TIME, 'french');
	$affiche_Tableau = '';
	$tabLignesFichierLabo = TableauRepFichier('.lab', $isEnCours);
	rsort($tabLignesFichierLabo);
	for($i = 0; $i < count($tabLignesFichierLabo); $i++){
		// Un objet pour récupérer les infos Fichier !!! 
		$mesInfosFichier = new CINFOfichierLab($GLOBALS['repCMDLABO'] . $tabLignesFichierLabo[$i]); 
		$nb_fichier++;

		$laCouleur = ($mesInfosFichier->EtatFichier == 2)?'GreenYellow':'white';
		$affiche_Tableau .=
		'<tr style="background-color:'.$laCouleur.'">
		
			<td class="titreCommande" >' . $mesInfosFichier->DateTirage .'</td>			
			<td align="left" class="titreCommande" ><div class="tooltip">' . $mesInfosFichier->AffichageNomCMD
								 . '<span class="tooltiptext">'. $mesInfosFichier->TexteSyntheseCommande() . '</span></div></td>		
			<td>'. CodeLienImageDossier($mesInfosFichier) . '</td>	';
	
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

///////////////////////////////////
//Pour l'historique a changer !!!!!
function AfficheTableauHistoriqueCMDLAB(&$nb_fichier, $isEnCours){
	//echo '$cmd = ' . ($GLOBALS['isDebug']?'':($ParamGET ?'?apiAMP=OK':''));
	//setlocale(LC_TIME, 'french');
	$affiche_Tableau = '';
	$tabLignesFichierLabo = TableauRepFichier('.lab', $isEnCours);
	rsort($tabLignesFichierLabo);
	for($i = 0; $i < count($tabLignesFichierLabo); $i++){
		// Un objet pour récupérer les infos Fichier !!! 
		$mesInfosFichier = new CINFOfichierLab($GLOBALS['repCMDLABO'] . $tabLignesFichierLabo[$i]); 
		$nb_fichier++;

		$laCouleur = ($mesInfosFichier->EtatFichier == 2)?'GreenYellow':'white';
		$affiche_Tableau .=
		'<tr style="background-color:'.$laCouleur.'">
			<td>
					<img src="img/' . $mesInfosFichier->EtatFichier . '-Etat.png"></a></td>			
			<td class="titreCommande" >' . $mesInfosFichier->DateTirage .'</td>			
			<td align="left" class="titreCommande" ><div class="tooltip"><a href="' . LienFichierLab($mesInfosFichier->Fichier) . '">'.LienImageVoir($mesInfosFichier->EtatFichier).' ' . $mesInfosFichier->AffichageNomCMD . '</a>
				<span class="tooltiptext">'. $mesInfosFichier->TexteSyntheseCommande() . '</span></div></td>		
			<td>'. CodeLienImageDossier($mesInfosFichier) . '</td>	
			
			<td>'. LienVoirMiseEnPochette($mesInfosFichier) . '</td>	
			<td>'. LienRecherchePlanche($mesInfosFichier) . '</td>';
			
			$affiche_Tableau .=	'  
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,1) . LienImageOKKO($mesInfosFichier->EtatFichier >= "2") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,3) . LienImageOKKO($mesInfosFichier->EtatFichier >= "3") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,4) . LienImageOKKO($mesInfosFichier->EtatFichier >= "4") . '</a></td>
				<td><a href="' . LienEtatLab($mesInfosFichier->Fichier,5) . LienImageOKKO($mesInfosFichier->EtatFichier >= "5") . '</a></td>'	
			;	
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
	$tabLignesFichierLabo = TableauRepFichier('.web', $isEnCours);
	
	rsort($tabLignesFichierLabo);
	
	for($i = 0; $i < count($tabLignesFichierLabo); $i++){
			$nb_fichier++;	
			//$NomFichier = $tabLignesFichierLabo[$i];	

			// Un objet pour récupérer les infos Fichier !!! 
			$mesInfosFichier = new CINFOfichierArbo($GLOBALS['repCMDLABO'] . $tabLignesFichierLabo[$i]); 	
			
			$laCouleur = ($mesInfosFichier->EtatFichier == 2)?'GreenYellow':'white';
			$affiche_Tableau .=
			'<tr style="background-color:'.$laCouleur.'">			
				<td>'.LienImageEtatWEB($mesInfosFichier->EtatFichier).'</a></td>				
				<td class="titreCommande" >' . $mesInfosFichier->TypeArbo .'</td>	
				<td  class="titreCommande" align="left"><div class="tooltip">' . $mesInfosFichier->AffichageNomSOURCE . '
					<span class="tooltiptext">'. $mesInfosFichier->SyntheseCMD . '</span></div></td>		
					<td>'. CodeLienImageWebArboDossier($mesInfosFichier) . '</td>';
				
			if($mesInfosFichier->EtatFichier < 2){ //<td>' . $mesInfosFichier->NBfichiersARBOWEB() . '</td>';
				$affiche_Tableau .=	'
				<td colspan=3>';
				
				if (file_exists($mesInfosFichier->LienFichierERREUR())){
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
				}//

				$affiche_Tableau .=	'
				<div class="boiteProgressBar">
				<div class="progressBar" id="AV'. $mesInfosFichier->Fichier .'" style="width:'.$mesInfosFichier->Avancement().'%;" title="'. TitleEtatTirage($mesInfosFichier->Fichier,1) . '">';				
				$affiche_Tableau .=	'<font size="2" >'. $mesInfosFichier->Compilateur . '→ Création : ' . number_format($mesInfosFichier->Avancement(), 1).'%</font>';			
				$affiche_Tableau .=	'</div>
					</div>';	
				

			/* pour Ajax defilement Barres 
			if ($mesInfosFichier->EtatFichier == 1) {array_push($GLOBALS['tabFichiersEnCoursDeCompilation'], $mesInfosFichier->Fichier);}
			*/


			}else {
				$affiche_Tableau .=	'
				<td><a href="' . LienEtatCMDWEB($mesInfosFichier->Fichier,2) . LienImageOKKO($mesInfosFichier->EtatFichier >= "2") . '</a></td>					
				<td><a href="' . LienEtatCMDWEB($mesInfosFichier->Fichier,3) . LienImageOKKO($mesInfosFichier->EtatFichier >= "3") . '</a></td>	
				<td><a href="' . LienEtatCMDWEB($mesInfosFichier->Fichier,4) . LienImageOKKO($mesInfosFichier->EtatFichier >= "4") . '</a></td>'						
				;				
			}		
			$affiche_Tableau .=	'<td>' . LienIMGSuprFichierLab($mesInfosFichier->Fichier, $mesInfosFichier->EtatFichier) . '</td>';
		//}
			
			
		}
				
	$affiche_Tableau .=	'</tr>';
	return $affiche_Tableau;
}

function TitleEtatTirage($fichier, $Etat){
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

function TitleEtatCMDWEB($fichier, $Etat){
	if (strrchr($fichier, '.') != ".lab0"){
		switch ($Etat) {
		case "1":
			$retourMSG = "Les fichiers sont en cours de création.";
			break;			
		case "2":
			$retourMSG = "Les fichiers et dossiers sont touts crées.";
			break;		
		case "3":
			$retourMSG = "Déclarer les fichiers comme transférer sur la boutique en ligne";
			break;
		case "4":
			$retourMSG = "Les ventes sont en cours, suprimer les fichiers...";
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
	$tabLignesFichierLabo = array();
	$EtatFinal = 5;
	if($dossier = opendir($GLOBALS['repCMDLABO'])){
		while(false !== ($fichier = readdir($dossier))){
			$Extension = strrchr($fichier, '.');
			$EtatFinal = ($ExtFichier == '.lab'?5:4);
			
			if($fichier != '.' && $fichier != '..'  && substr($Extension,0,4) == $ExtFichier && strlen($Extension) > 4){
				if ($isEnCours && substr(strrchr($fichier, '.'),4) < $EtatFinal){
					array_push($tabLignesFichierLabo,$fichier);
					//echo $fichier .' ext ETAT : ' . substr(strrchr($fichier, '.'),4) . '<br>';					
				}
				else{
					if (!$isEnCours && substr(strrchr($fichier, '.'),4) > $EtatFinal-1){
						array_push($tabLignesFichierLabo,$fichier);
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
	$tabLignesFichierLaboSelect = array();
	for($i = 0; $i < count($tabLignesFichierLabo); $i++){
		$Extension = strrchr($tabLignesFichierLabo[$i], '.');
		if ($Extension == $ExtFichier . '0'){// Seulement si y a pas un .lab1 du même nom!
			$fichier = $tabLignesFichierLabo[$i];			
			$strBaseName = substr($fichier, 0, strpos($fichier, $ExtFichier));
			$YAutre = false;
			for($j = 0; $j < count($tabLignesFichierLabo); $j++){				
				if ($strBaseName == substr($tabLignesFichierLabo[$j], 0, strpos($tabLignesFichierLabo[$j], $ExtFichier))) {
					if($fichier != $tabLignesFichierLabo[$j]){
						$YAutre = true;
						break;
					} else {
						$YAutre = false;				
					}
				}
			}
			if(!$YAutre){array_push($tabLignesFichierLaboSelect,$tabLignesFichierLabo[$i]);}
		} 
		/* pour enlever lab1 qd Lab 2 :*/
		elseif ($Extension == $ExtFichier . '1'){// Ne pas afficher 2 lignes ! Seulement si y a pas un .lab2 du même nom!
			$fichier = $tabLignesFichierLabo[$i];		
			//remettre  
			$fichierEtat2 = substr($fichier, 0, strpos($fichier, $ExtFichier)). $ExtFichier . '2';
			//echo $fichierEtat2;
			$YAutre = false;
			for($j = 0; $j < count($tabLignesFichierLabo); $j++){				
				if($fichierEtat2 == $tabLignesFichierLabo[$j]){
					$YAutre = true;
					break;
				} else {
					$YAutre = false;				
				}
			}
			if(!$YAutre){array_push($tabLignesFichierLaboSelect,$tabLignesFichierLabo[$i]);}
		} 
		else{
			array_push($tabLignesFichierLaboSelect,$tabLignesFichierLabo[$i]);		
		}
	}	
	return $tabLignesFichierLaboSelect;
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
			if ($EtatVise == 1 ){// Hack pour Historique pour pouvoir revenir sur commande en cours
				$lien =  $GLOBALS['maConnexionAPI']->CallServeur('&apiFichierChgEtat='. urlencode($fichier) .'&apiEtat=2','CATPhotolab');	
			}				
			elseif ($EtatVise == 2 ){//
				//echo '<br>' .  'APIDialogue.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
				//$lien =  'APIDialogue.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;

				$lien =   "CMDRecherche.php" . ArgumentURL() . "&fichierLAB=" . urlencode($fichier) ;
			}	
			//if (($EtatVise == 3 )&& (substr($fichier, 0, -5) == $GLOBALS['FichierDossierRECOMMANDE'])){//
			elseif  ($EtatVise == 3 ){//
				//echo '<br>' .  'APIDialogue.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
				$lien =  'APIDialogue.php' . ArgumentURL() . '&apiDemandeNOMImpression=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
			}
			elseif ($EtatVise == 4 ){//
				//echo '<br>' .  'APIDialogue.php' . ArgumentURL() . '&apiInfoMiseEnPochette=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
				if (substr($fichier, -1) == $EtatVise){
					$lien =   "CMDCartonnage.php" . ArgumentURL() . "&fichierLAB=" . urlencode($fichier) ;
				}else{
					$lien =  'APIDialogue.php' . ArgumentURL() . '&apiInfoMiseEnPochette=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
				}			
			}
			elseif ($EtatVise == 5 ){//
				//echo '<br>' .  'APIDialogue.php' . ArgumentURL() . '&apiInfoExpeditionArchivage=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
				$lien =  'APIDialogue.php' . ArgumentURL() . '&apiInfoExpeditionArchivage=OUI'.'&apiFichierChgEtat='. urlencode($fichier) ;
			}
			else{
			//NEW2 UTF-8 return $GLOBALS['maConnexionAPI']->CallServeur('&apiFichierChgEtat='. urlencode(utf8_encode($fichier)) .'&apiEtat=' . $EtatVise);
			$lien =  $GLOBALS['maConnexionAPI']->CallServeur('&apiFichierChgEtat='. urlencode($fichier) .'&apiEtat=' . $EtatVise);		
			
			}
		}

	}else {
		$lien = 'APIDialogue.php' . ArgumentURL() . '&apiCMDEnCours=' . urlencode($fichier) ;
	}
	return $lien . '"  title="'. TitleEtatTirage($fichier, $EtatVise) . '">';
}

function LienEtatCMDWEB($fichier, $Etat) {
	$EtatActuel = substr($fichier,-1);
	$lien = '#';
	if ($EtatActuel > 0){
		//NEW2 UTF-8 return $GLOBALS['maConnexionAPI']->CallServeur('&apiFichierChgEtat='. urlencode(utf8_encode($fichier)) .'&apiEtat=' . $Etat);
		$lien =  $GLOBALS['maConnexionAPI']->CallServeur('&apiFichierChgEtat='. urlencode($fichier) .'&apiEtat=' . $Etat);			
	} else {
		$lien =  'APIDialogue.php' . ArgumentURL() . '&apiCMDEnCours=' . urlencode($fichier) ;
	}
	return $lien . '"  title="'. TitleEtatCMDWEB($fichier, $Etat) . '">';
}


/* */
function LienFichierLab($fichier) {
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	$Extension = strrchr($fichier, '.');
	$LienFichier = "#";
	switch ($Extension) {
		case ".lab0":
			$LienFichier = 'APIDialogue.php' . ArgumentURL() . '&apiCMDEnCours=' . urlencode($fichier) ;
			break;
		default:
			$LienFichier = "CMDRecherche.php". $Environnement . "&fichierLAB=" . urlencode($fichier);
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
			$Lien = 'APIDialogue.php' . ArgumentURL() . '&apiCMDEnCours=' . urlencode($infosFichier->Fichier) ;
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
			$Lien = 'APIDialogue.php' . ArgumentURL() . '&apiCMDEnCours=' . urlencode($infosFichier->Fichier) ;
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
 	$tabLignesFichierLabo = array();
	$myfile = fopen($myfileName, "r") or die('Unable to open file : ' .$myfileName);
	// Output one line until end-of-file
	while(!feof($myfile)) {
		array_push($tabLignesFichierLabo,trim(fgets($myfile)));
	}
	fclose($myfile);
	//afficheTab($tabLignesFichierLabo);
	return $tabLignesFichierLabo;
}

function TypeFichier($myfileName){
	return substr($myfileName, -4, 3);
}



// POUR LES RECOMMANDES !
function MAJRecommandes($FichierOriginal, $strTabCMDReco) {
	SuprFichier($GLOBALS['repCMDLABO'] . $GLOBALS['FichierDossierRECOMMANDE'].".lab2");	
	$NewFichierSeul = $GLOBALS['FichierDossierRECOMMANDE'] . '.lab0' ;
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

   $mesInfosFichier = new CINFOfichierLab($NewFichier); 
   $mesInfosFichier->MAJResumeFichierCommandes();

  return $NewFichierSeul;
}

// POUR LES COMMANDES LIBRES  ! A REVOIR
function MAJCommandesLibres($SourceDesCMD, $strTabCMDLibre) {
	SuprFichier($GLOBALS['repCMDLABO'] . $GLOBALS['FichierDossierCMDESLIBRE'].".lab2");
	$NewFichierSeul = $GLOBALS['FichierDossierCMDESLIBRE'].".lab0" ;
	$NewFichier = $GLOBALS['repCMDLABO'] . $NewFichierSeul ;
	if (!file_exists($NewFichier)){
		$file = fopen($NewFichier, 'w');
			fputs($file, '[Version : 2.0]'.PHP_EOL );
			fputs($file, '{Etat : 0 : Non enregistre %%Commandes de tirages Libres }'.PHP_EOL );
		fclose($file); 
	}

	$resultat = $SourceDesCMD ."\n";

	$resultat .= '# # __Tirages d\'exemples du ' . date("d / F") ."#\n";

	$resultat .= trim(str_replace($GLOBALS['SeparateurInfoPlanche'] , "\n", $strTabCMDLibre));

   //Ajouter commande au fichier de commande Libres 
   file_put_contents($NewFichier, $resultat."\n", FILE_APPEND | LOCK_EX);

   $mesInfosFichier = new CINFOfichierLab($NewFichier); 
   $mesInfosFichier->MAJResumeFichierCommandes();

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
		<a href="' . LienOuvrirDossierOS($GLOBALS['repTIRAGES'].$mesInfosFichier->RepTirage(),'CATPhotolab') . '" >' . $codeHTML . '</a>
		<span class="tooltiptext"><br>Cliquez ici pour retrouver le dossier des planches crées pour les tirages photos<br><br></span></div>';
		
	}
	$codeHTML = $codeHTML ;
	
	return $codeHTML;
	
}

function CodeLienImageWebArboDossier($mesInfosFichier){	

	$Lien = $GLOBALS['g_IsLocalMachine'] && ($mesInfosFichier->EtatFichier > 0);
	$DossierOK =($Lien )? 'OK':'KO' ;
	$nbClasse = 0;
	$nbFichier = $mesInfosFichier->NBfichiersARBOWEB($nbClasse);
	$codeHTML = '<div class="containerCMDPLanche">
		<div class="txtCMDPLanche">' . $nbFichier . ' <span class="mini">fichiers</span></div>'. '<img src="img/Dossier' . $DossierOK . '.png"></div>';	
	
	if ($Lien) {
		/*$codeHTML = '<div class="tooltip">
		<a href="' . LienOuvrirDossierOS($GLOBALS['repWEBARBO'].$mesInfosFichier->RepTirage(),'CATPhotolab') . '" >' . $codeHTML . '</a>
		<span class="tooltiptext"><br>Cliquez ici pour retrouver le dossier "Arborescence" des fichiers et dossiers crées<br><br></span></div>';*/
		
		$codeHTML = '<a href="' . LienOuvrirDossierOS($GLOBALS['repWEBARBO'].$mesInfosFichier->RepTirage(),'CATPhotolab') . '" 
		title="Cliquez ici pour retrouver le dossier \'Arborescence\' des fichiers et dossiers crées">' . $codeHTML . '</a>';		
	}
	$codeHTML = $codeHTML ;
	
	return $codeHTML;
	
}


function BilanScriptPhotoshop($target_file, &$nbProduitsManquant){

	$resultat = ''; 
    $monGroupeCmdes = new CGroupeCmdes($target_file);
    $monTableauDeProduits = array_unique($monGroupeCmdes->ListeProduitsManquants());
    if ($monTableauDeProduits != ''){
        $resultat = '<table width="100%" class = "TableProduit">';   
		$nbProduitsManquant = 0;
        for($i = 0; $i < count($monTableauDeProduits); $i++){
            if ($monTableauDeProduits[$i] != '') {
                //$resultat .= $monTableauDeProduits[$i] . 'qsdqsd<br>';	
                //$tableauProduitsManquants = explode($GLOBALS['SeparateurInfoCatalogue'], $monTableauDeProduits[$i]); 
				$refProduitsManquants = explode(';', $monTableauDeProduits[$i]); 
				$CommandeRetour=urlencode(substr($target_file,1+strripos($target_file, '/')));
				//echo $CommandeRetour;
				if ($refProduitsManquants[1] == ''){ // NODEFINITION pas défini
					$resultat .=  '<tr class="StyleKO"><td >' . $refProduitsManquants[0] . '</td >
								<td >' . LienEditionProduit($refProduitsManquants,$CommandeRetour). '</td ></tr>';
					$nbProduitsManquant = $nbProduitsManquant + 1;
				}
				else{
					$resultat .=  '<tr class="StyleOK"><td >' . $refProduitsManquants[0] . '</td >
								<td >' . LienEditionProduit($refProduitsManquants,$CommandeRetour). '</td ></tr>';
				}                
            }		
        }
    }
    $resultat .= '</table>';
    return $resultat;
}

function LienEditionProduit($leProduit, $CommandeRetour) {
	$ParamCProjetSource = '&CodeEcole=' . $leProduit[2] . '&AnneeScolaire=' . $leProduit[3].  '&isImport=true';
	$NomProduit = $leProduit[0];
	if ($leProduit[1]==''){ // Pas de produit défini
		$Script = explode('_', $leProduit[1]);        
		$DefinitionProduit = '&PDTDenomination=' . urlencode($NomProduit) ;
		$LienImage = '<img class="OKKOIMG" src="img/KO.png" alt="Pas de produit défini">';		
	}else{
		$Script = explode('_', $leProduit[1]);        
		$DefinitionProduit = '&PDTDenomination=' . urlencode($NomProduit) .
		'&PDTRecadrage=' . ''.
		'&PDTTaille=' . urlencode($Script[0]).
		'&PDTTransformation=' . (count($Script)>1? urlencode($Script[1]):'').
		'&PDTTeinte=' . (count($Script)>2? urlencode($Script[2]):'');
		$LienImage = '<img class="OKKOIMG" src="img/OK.png" alt="Produit défini">';		
	}

	if($CommandeRetour == ''){
		$Lien = 'CMDEditionProduits.php' . ArgumentURL($ParamCProjetSource. $DefinitionProduit.
				'&pageRetour=' . urlencode(basename($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']))) ;
	}else{
		//APIDialogue.php?codeMembre=PSL&isDebug=Debug&apiCMDEnCours=2022-05-10-L2-2022-02-28-Elementaire+Les+Plantes-NANTES+2021-2022.lab0
		$CommandeRetour = 'APIDialogue.php' .ArgumentURL('&isImport=true'. '&apiCMDEnCours='. $CommandeRetour);
		//'&apiCMDEnCours=2022-05-10-L2-2022-02-28-Elementaire+Les+Plantes-NANTES+2021-2022.lab0'
		
		$Lien = 'CMDEditionProduits.php' . ArgumentURL($ParamCProjetSource. $DefinitionProduit.
				'&pageRetour=' . urlencode($CommandeRetour)) ;
	}
	//echo '&pageRetour=' . urlencode(basename($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));



	return $LienImage.'<a href="'. $Lien . '" class ="icone" title="Editer le produit : '. $leProduit[0].'"> 🖉 </a>';

}

function PhotosManquantes($target_file){
    $NombrePhotosManquante = 0;
    $resultat = ''; 
    $monGroupeCmdes = new CGroupeCmdes($target_file);
    $maListeDeFichier = $monGroupeCmdes->ListeFichiersSourcesManquants();

    if ($maListeDeFichier != ''){
        $TableauFichiersManquants = explode($GLOBALS['SeparateurInfoPlanche'], $maListeDeFichier);    
        for($i = 0; $i < count($TableauFichiersManquants); $i++){
            if ($TableauFichiersManquants[$i] != '') {
                //$NombrePhotosManquante += 1;
            }		
        }		
		//$TableauFichiersManquants = array_unique($TableauFichiersManquants,SORT_LOCALE_STRING);
		//var_dump(array_unique($TableauFichiersManquants));
		foreach (array_unique($TableauFichiersManquants,SORT_LOCALE_STRING) as &$value) {
            if ($value != '') {
                $NombrePhotosManquante += 1;
            }	
			$resultat .= $value . '<br>';	
		}



    }
    $resultat = '<span class="Style'.(($NombrePhotosManquante)?'KO':'OK').'"> Photos manquantes : ' . $NombrePhotosManquante .
				'<img class="OKKOIMG" src="img/'.(($NombrePhotosManquante)?'KO':'OK').'.png"><br><H3>' . $resultat .'</H3></span>';
    return $resultat;
}

function ListeProduitsSelonCatalogue($monCatalogue){ 
	$fichierCatalogueScriptPS = $GLOBALS['repGABARITS'] . 'Catalogue'. $monCatalogue . '.csv';
	
	$CataloguePRODUITS = array();
	if (file_exists($fichierCatalogueScriptPS)){ 
		$file = fopen($fichierCatalogueScriptPS, "r");
		if ($file) {
			while(!feof($file)) {
				$line = trim(fgets($file));
				if (strpos($line, ';') > 1){
					array_push($CataloguePRODUITS, $line);
				}
			}
			fclose($file);	
		}			
	}
	return $CataloguePRODUITS;
}

function MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte){
    $Supression = ($PDTNumeroLigne<0);
    $PDTNumeroLigne = abs($PDTNumeroLigne);
    $monCatalogueScriptPS = $GLOBALS['repGABARITS'] . 'Catalogue'.$monProjetSource->ScriptsPS . '.csv';
	$CataloguePRODUITS = array();
	if (file_exists($monCatalogueScriptPS)){ 
		$file = fopen($monCatalogueScriptPS, "r");
		if ($file) {
			while(!feof($file)) {
				$line = trim(fgets($file));
				if (strpos($line, ';') > 1){
					array_push($CataloguePRODUITS, $line);
				}
			}
			fclose($file);	
		}
        $file = fopen($monCatalogueScriptPS, 'w');

        $lefichier ='';

        for($i = 1; $i < count($CataloguePRODUITS) ; $i++){
            $morceau = explode(';', $CataloguePRODUITS[$i]);   
            if(($morceau[0]==$PDTDenomination) &&  ($i != $PDTNumeroLigne)){
                $PDTDenomination .='> (Doublon) ! Modifiez le nom du produit...';
            }          
        }        
        for($i = 1; $i < count($CataloguePRODUITS) ; $i++){ 
            //echo '<br>PDTDenomination ' . $PDTDenomination ;
            if ($i == $PDTNumeroLigne){
                if(!$Supression){
                    $lefichier .= $PDTDenomination .';'. CodeProduit($PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte) . "\n";
                    //fputs($file, $PDTDenomination .';'. CodeProduit($PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte) . "\n");
                }                
            }else{
                $lefichier .= $CataloguePRODUITS[$i]. "\n";
                //fputs($file, $CataloguePRODUITS[$i]. "\n");
            }            
        }
        if ($PDTNumeroLigne == 0) { 
            $lefichier = $PDTDenomination .';'. CodeProduit($PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte) . "\n" . $lefichier;  
        }        
        $lefichier = "Description;Code\n" . $lefichier;        
        
        fputs($file, $lefichier);
        fclose($file);
        //header('Location: '. htmlspecialchars($_SERVER['PHP_SELF']). ArgumentURL('&CodeEcole=' . $monProjetSource->CodeEcole . '&AnneeScolaire=' . $monProjetSource->AnneeScolaire));

    }
}

function CodeProduit($PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte){
    $leCodeProduit = ($PDTTaille ==''?'':$PDTTaille);
    $leCodeProduit .= ($PDTTransformation ==''?'': '_'. $PDTTransformation);
    $leCodeProduit .= ($PDTTeinte ==''?'': '_'. $PDTTeinte);
    return $leCodeProduit;
}

?>