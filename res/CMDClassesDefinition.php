<?php
$SeparateurInfoCatalogue = '£';
$SeparateurInfoPlanche = '§';
$SeparateurInfoPlancheLab0 = '_';

$DateISOLEE = '2020-09-31';
$FichierDossierRECOMMANDE = "9999-99-99-(RECOMMANDES)-EN-COURS";
$FichierDossierCMDESLIBRE = "8888-88-88-(COMMANDES LIBRES)-EN-COURS";

$CSVCatalogueSources = '../../SOURCES/Sources.csv';
$CSVBibliothequeScriptPS = '../../GABARITS/ActionsScriptsPSP.csv';

$InviteNomProduit = 'Saisissez le nom de votre nouveau produit';

$ProduitsNONLABO = array(
	'NON-IMPRIMABLE',
	'TAPIS-SOURIS',
	'CADRE-PANO'
);

$ProduitsPourGROUPE = array(
	'CADRE',  
	'SITU', 
	'TRAD', 
	'PANO', 
	'CUBE', 
	'RUCH'	
);

class CGroupeCmdes {
    var $ListePromoteurs;
    var $ListeCommandes;
    var $nomFichierCmdes;	
	var $tabFICHIERLabo;
	var $tabCMDLabo;  
	var $DateISOLEE;
	var $colEColes;
	var $DossierTirage;	
	var $NbCommande;	
	
    function __construct($myfileName){
		$this->nomFichierCmdes = $myfileName;
		if (file_exists($this->nomFichierCmdes)){
			$myfile = fopen($this->nomFichierCmdes, "r") or die('Unable to open file : ' .$this->nomFichierCmdes);
			$this->tabFICHIERLabo = array();
			// Output one line until end-of-file
			
			//$GLOBALS['DateISOLEE'] = substr($myfileName, strripos($myfileName, '/') + 1,10);
					//echo '$myfileName : '. substr($myfileName, -1);
			while(!feof($myfile)) {
				array_push($this->tabFICHIERLabo,trim(fgets($myfile)));
			}
			fclose($myfile);
		}	

		$this->DateISOLEE = substr($this->nomFichierCmdes, strripos($this->nomFichierCmdes, '/') + 1,10);		
		$this->colEColes = array();
		$this->tabCMDLabo = array();
		if ($this->tabFICHIERLabo){
			//On demarre à 2 pour sauter les 2 premieres lignes du fichier
			for($i = 2; $i < count($this->tabFICHIERLabo); $i++){
				$identifiant = substr($this->tabFICHIERLabo[$i],0,1);
				//Si Commande pas vide , on ajoute la commande au tableau!
				//echo $this->tabFICHIERLabo[$i];
				if ($identifiant == '@') {
					if ($this->DossierTirage == ''){
						/*if ($GLOBALS['FichierDossierRECOMMANDE'] == substr($myfileName, strripos($myfileName, '/') + 1,-5)) { // C'est des RECO TEMPORAIRE
							$this->DossierTirage =  $GLOBALS['FichierDossierRECOMMANDE'] ;															
						}	
*/
						if ((stripos($this->tabFICHIERLabo[$i], '(ISOLEES)') !== false)&&(stripos($this->tabFICHIERLabo[$i], '(RECOMMANDES)') !== true)) { // C'est des ISOLEES
							$this->DossierTirage =  substr($this->nomFichierCmdes, strripos($this->nomFichierCmdes, '/') + 1,-5);	
							//echo '$this->DossierTirage : ' .$this->DossierTirage;
							if (!file_exists($GLOBALS['repMINIATURES'].$this->DossierTirage)) {
								if (file_exists($GLOBALS['repMINIATURES'].$this->DateISOLEE . '-CMD-ISOLEES')) {
									$this->DossierTirage = $this->DateISOLEE . '-CMD-ISOLEES' ;
								} 								
							} 							
						}	
						elseif (stripos($this->tabFICHIERLabo[$i], '(RECOMMANDES)') !== false) { // C'est des RECOs
							$this->DossierTirage =  substr($this->nomFichierCmdes, strripos($this->nomFichierCmdes, '/') + 1,-5);	

						}				
						else{ // C'est des PAS des ISOLEES
							$curEcole = new CEcole($this->tabFICHIERLabo[$i], '');							
							$this->DossierTirage =  substr($this->nomFichierCmdes, strripos($this->nomFichierCmdes, '/') + 1,-5);	
							//$this->DossierTirage = $curEcole->DateTirage . '-' .$curEcole->Nom ;
						}	
					}
					//$curEcole = new CEcole($this->tabFICHIERLabo[$i], $this->DateISOLEE);
					$curEcole = new CEcole($this->tabFICHIERLabo[$i], $this->DossierTirage);
					array_push($this->colEColes,$curEcole);			
				}
				elseif ($identifiant == '#') {
					$curCommande = new CCommande($this->tabFICHIERLabo[$i]);
					$curEcole->AjoutCMD($curCommande);
					array_push($this->tabCMDLabo,$curCommande);
				}				
				elseif ($identifiant == '<') {
					$curProduit = new CProduit($this->tabFICHIERLabo[$i]);
					$curCommande->AjoutPDT($curProduit);
				}	
				elseif ($identifiant == 'P') {
					$curPlanche = new CPlanche($this->tabFICHIERLabo[$i]);
					$curProduit->AjoutPlanche($curPlanche);
				}	
				/**/
				elseif (substr($this->nomFichierCmdes, -1) == '0') {
					if(strlen($this->tabFICHIERLabo[$i]) > 7) {
						$curPlanche = new CPlanche($this->tabFICHIERLabo[$i]);
						$curProduit->AjoutPlanche($curPlanche);
					}				
				}		
			}
		}  
    } 
	function ListeFichiersSourcesManquants(){
		$resultat = '';
		for($i = 0; $i < count($this->colEColes); $i++){
			$resultat .= $this->colEColes[$i]->ListeFichiersSourcesManquants() . $GLOBALS['SeparateurInfoPlanche'];			
		}
		return $resultat;
	}
	function ListeProduitsManquants(){
		$TableauDeProduitsManquants = array();

		//$resultat = '';
		for($i = 0; $i < count($this->colEColes); $i++){
			$this->colEColes[$i]->ListeProduitsManquants($TableauDeProduitsManquants);
		}
		return $TableauDeProduitsManquants;
	}		
	function AfficheMenuCMD(){
		$resultat = '';
		//echo 'Affiche ecole Affiche : ' . count($this->colEColes);
		for($i = 0; $i < count($this->tabCMDLabo); $i++){
			$resultat .= '<li><a href="#C-'.$this->tabCMDLabo[$i]->Numero.'">'.$this->tabCMDLabo[$i]->Numero.'</a></li>';
		}
		return $resultat;
	}	
	function Affiche($numP){
		$gestionPage = new CPage($numP);

		$resultat = '';
		//echo 'Affiche ecole Affiche : ' . count($this->colEColes);
		for($i = 0; $i < count($this->colEColes); $i++){
			global $EcoleEnCours;
			$EcoleEnCours = $this->colEColes[$i];
			$resultat .= $this->colEColes[$i]->Affiche($gestionPage);			
		}
		$gestionPage->AfficheFinPage(true);
		return $resultat;
	}	
	function AffichePlancheAProduire(){
		$resultat = '';
		//echo 'Affiche ecole Affiche : ' . count($this->colEColes);
		for($i = 0; $i < count($this->colEColes); $i++){
			global $EcoleEnCours;
			$EcoleEnCours = $this->colEColes[$i];
			$resultat .= $this->colEColes[$i]->AffichePlancheAProduire();			
		}

		return $resultat;
	}	
	function AfficheCommandesAProduire(){
		$resultat = '<table class="TableCommandes"><tr>
		<td  width="15%" class ="StyleNumCommande">Identifiant</td><td  width="85%" class ="StyleInfoClient"><center>Informations client</center></td>
		</tr></table>';


		//echo 'Affiche ecole Affiche : ' . count($this->colEColes);
		for($i = 0; $i < count($this->colEColes); $i++){
			global $EcoleEnCours;
			$EcoleEnCours = $this->colEColes[$i];
			$resultat .= $this->colEColes[$i]->AfficheCommandesAProduire();			
		}

		return $resultat;
	}	

	function SauvegarderEtatCommandeFermer($strCommandesFermees){
		if (file_exists($this->nomFichierCmdes)){
			$myfile = fopen($this->nomFichierCmdes, "r") or die('Unable to open file : ' .$this->nomFichierCmdes);
			$this->tabFICHIERLabo = array();
			while(!feof($myfile)) {
				array_push($this->tabFICHIERLabo,trim(fgets($myfile)));
			}
			fclose($myfile);
			$TableauCommandesFermees = explode($GLOBALS['SeparateurInfoPlanche'], $strCommandesFermees);
			$myfile = fopen($this->nomFichierCmdes, 'w');
				for($i = 0; $i < count($this->tabFICHIERLabo); $i++){
					$maLigne = $this->tabFICHIERLabo[$i];
					
					if(substr($maLigne,0,1) == '#') {
						$curCommande = new CCommande($maLigne);
						$FlagFERMER = (in_array($curCommande->Numero,$TableauCommandesFermees))?'FERMER':'';
						$maLigne ='#'. $curCommande->Numero . '_'	. $curCommande->NumFacture . '_' 
									. $curCommande->Prenom . '_' . $curCommande->Nom . '_' 
									. $curCommande->Adresse . '__' . $curCommande->CodePostal .'_' . $curCommande->Ville .'_' 
									. $FlagFERMER .'#'; 																		
					}
					fputs($myfile, $maLigne.PHP_EOL );
				}		
			fclose($myfile);
		}	
	}	

	function Ecrire($tabPlanche, &$isRecommande){
		$resultat =''; 
		for($i = 0; $i < count($this->colEColes); $i++){
			global $EcoleEnCours;
			$EcoleEnCours = $this->colEColes[$i];
			$isEcris = false;
			$resultat .= $this->colEColes[$i]->Ecrire($tabPlanche, $isEcris);	
			$isRecommande = $isRecommande || $isEcris;				
		}
		if ($isRecommande) {
			return $resultat;
		}	
		else{
			return '';			
		}				
	}		
}

class CPage {
    var $compteurCMD;
    var $NbCMDAffiche;
    var $numeroPage;
    var $isPageOuverte;
	var $isPage;
	
    function __construct($NbCMDAffiche){
		$this->compteurCMD = 0;
		$this->NbCMDAffiche = $NbCMDAffiche;		
		$this->isPageOuverte = false;	
		$this->isPage = ($NbCMDAffiche > 0);
		$this->numeroPage = 0; //($this->isPage?1:0);	// On met le numero de page a 1 si on initialise l'objet page avec un nb de page	
    } 
    //function AfficheDebutPage($unNomEcole){
	function AfficheDebutPage(){
		$resultat = '';
		if ($this->isPage && !$this->isPageOuverte){
			if (($this->compteurCMD % $this->NbCMDAffiche) == 0){	
				$this->numeroPage++;
				// On ouvre la page			
				$resultat .= '<div id="P-'. $this->numeroPage .'" class="pageCMD">'; // On l'ouvre ! 	
				$this->isPageOuverte = true;				
			}		
		}
		return $resultat;
	}
    function AfficheFinPage($isForce = false){
		$resultat = '';
		if($this->isPage && $this->isPageOuverte){
			if ($isForce || ($this->compteurCMD % $this->NbCMDAffiche) == 0){			
				$resultat .= '</div>';	// On la referme ! 
				$this->isPageOuverte = false;		
			}			
		}
		return $resultat;
	}

}
class CEcole {
    var $DateTirage;
    var $Nom;
	var $CodeEcole;
	var $AnneeScolaire;
    var $Details;
	var $tabCommandes;
	//var $DateISOLEE;
	var $DossierTirage;

    function __construct($str, $DossierTirage){
        //NEW UTF-8 $morceau = explode("_", utf8_encode(str_replace("@", "", $str)));
        $morceau = explode("_", str_replace("@", "", $str));

		//echo '<br> $morceau ' . print_r ($morceau);
		$this->DateTirage = $morceau[0];
        $this->Nom = $morceau[1];
        $this->CodeEcole = $morceau[2];
		$this->AnneeScolaire = $morceau[3];
		if (count($morceau) > 4){$this->Details = $morceau[4];} 		
		$this->tabCommandes = array();
		//$this->DateISOLEE = $dateIsole;
		$this->DossierTirage = $DossierTirage;
    }
    function ListeFichiersSourcesManquants(){
		$monProjetSource = new CProjetSource($this->CodeEcole, $this->AnneeScolaire);
			
		if ($GLOBALS['isDebug']){
			//echo 'monProjetSource->Dossier '. $monProjetSource->Dossier;

		}	
		$resultat = 'Il n\'y a pas de dossier source correspondant au code : ' . $this->CodeEcole . '" et l\'année ' . $this->AnneeScolaire;
		if ($monProjetSource->Dossier != ''){
			$TableauDeFichierduDossierSource  = glob($monProjetSource->Dossier . '/*.*{jpg,jpeg}',GLOB_BRACE);		
			$resultat = '';
			//var_dump($TableauDeFichierduDossierSource);	
			for($i = 0; $i < count($this->tabCommandes); $i++){
				$resultat .= $this->tabCommandes[$i]->FichiersSourceNecessaires();	
			}	
			$TableauDeFichierNecessaire = explode($GLOBALS['SeparateurInfoPlanche'], $resultat);
			$resultat = '';
			for($i = 0; $i < count($TableauDeFichierNecessaire); $i++){
				if ($TableauDeFichierNecessaire[$i] != '') {
					$TableauDeFichierNecessaire[$i] = $monProjetSource->Dossier .'/'. $TableauDeFichierNecessaire[$i];
					if (!(in_array($TableauDeFichierNecessaire[$i], $TableauDeFichierduDossierSource))) {
						$resultat .=  $TableauDeFichierNecessaire[$i] . $GLOBALS['SeparateurInfoPlanche'];
					}
				}			

			}	
		}	
	
		return $resultat;
    }   

    function ListeProduitsManquants(&$TableauDeProduitsManquants){
		$monProjetSource = new CProjetSource($this->CodeEcole, $this->AnneeScolaire);	
		$DossierScriptsPS = $monProjetSource->ScriptsPS;
		//$TableauDeProduitsDansDossierScript  = //REcupt tableau des scripts de repScript
		$monCatalogueScriptPS = $GLOBALS['repGABARITS'] . $monProjetSource->NomCatalogue();	

		$TableauDeProduitsDansDossierScript = array();

		if (file_exists($monCatalogueScriptPS)){ 
			$file = fopen($monCatalogueScriptPS, "r");
			if ($file) {
				while(!feof($file)) {
					$line = trim(fgets($file));
					if (strpos($line, ';') > 1){
						array_push($TableauDeProduitsDansDossierScript, $line);	
						//array_push($TableauDeProduitsDansDossierScript, $line . '@' . $monProjetSource->NomCatalogue());						
					}
				}
				fclose($file);	
			}			
		}
		//var_dump($TableauDeProduitsDansDossierScript);
		$resultat = '';
		for($i = 0; $i < count($this->tabCommandes); $i++){
			$resultat .= $this->tabCommandes[$i]->ProduitsNecessaires();	
		}	
		//echo $resultat . '<br>';
		$TableauDeProduitsNecessaire = explode($GLOBALS['SeparateurInfoPlanche'], $resultat);
		//var_dump($TableauDeProduitsNecessaire);
		//var_dump($TableauDeProduitsNecessaire)  ;
		$ligne = '';
		for($i = 0; $i < count($TableauDeProduitsNecessaire); $i++){
			if ($TableauDeProduitsNecessaire[$i] != '') {
				$indiceTrouve = 0;
				for($j = 0; $j < count($TableauDeProduitsDansDossierScript); $j++){
					$NomProduitsCatalogue = explode(";", $TableauDeProduitsDansDossierScript[$j]);
					if( strtolower($NomProduitsCatalogue[0]) == strtolower($TableauDeProduitsNecessaire[$i])){
						$indiceTrouve = $j;
						$ligne =  $TableauDeProduitsNecessaire[$i] .';'. $NomProduitsCatalogue[1];//. $GLOBALS['SeparateurInfoCatalogue'];
						array_push($TableauDeProduitsManquants, $ligne.';'.$this->CodeEcole.';'.$this->AnneeScolaire);
						break;
					}
				}
				if ($indiceTrouve == 0){
					$ligne =  $TableauDeProduitsNecessaire[$i] .';';//. $GLOBALS['SeparateurInfoCatalogue'];
					array_push($TableauDeProduitsManquants, $ligne.';'.$this->CodeEcole.';'.$this->AnneeScolaire);
				}

				// ?? array_push($TableauDeProduitsManquants, $ligne);
			}			
		}	
		//var_dump($TableauDeProduitsManquants)  ;
    }   
    function RepTirage(){
		return $this->DossierTirage;		
    }   
	function AjoutCMD($uneCMD){
		array_push($this->tabCommandes,$uneCMD);
    } 	
    function Affiche(&$gestionPage){
		//$isParPage = ($numeroPage>0);
		$resultat = '';
		//$numPage = 0;
		
		//$resultat .= $gestionPage->AfficheDebutPage($this->Nom);
		$resultat .= $gestionPage->AfficheDebutPage();
		/**/$resultat .= '<div class="ecole">';	
		//$resultat .= '<span class ="Titreecole">'.$this->Nom .'</span>';  
		$resultat .= '<h1>'.$this->Nom .'</h1>';  	
		$resultat .= '</div>';
		for($i = 0; $i < count($this->tabCommandes); $i++){
			//$resultat .= $gestionPage->AfficheDebutPage($this->Nom);
			$resultat .= $gestionPage->AfficheDebutPage();
			$resultat .= $this->tabCommandes[$i]->Affiche($gestionPage);	
			$resultat .= $gestionPage->AfficheFinPage();
		}
		//$resultat .= '</div>';	/////////////////////////////		
		return $resultat;
	}	
	function AffichePlancheAProduire(){
		//$isParPage = ($numeroPage>0);
		$resultat = '';

		/**/$resultat .= '<div class="StyleEcole">';	
		$resultat .= $this->Nom ;  
		$resultat .= '</div>';				
		$resultat .= '<table class="TablePlanche">';	
		for($i = 0; $i < count($this->tabCommandes); $i++){
			$resultat .= $this->tabCommandes[$i]->AffichePlancheAProduire();	
		}	
		$resultat .= '</table>';
	
		return $resultat;
	}
    function AfficheCommandesAProduire(){
		//$isParPage = ($numeroPage>0);
		$resultat = '';

		/**/$resultat .= '<div class="StyleEcole">';	
		$resultat .= $this->Nom ;  
		$resultat .= '</div>';				
		$resultat .= '<table class="TableCommandes">';	
		for($i = 0; $i < count($this->tabCommandes); $i++){
			$resultat .= $this->tabCommandes[$i]->AfficheCommandesAProduire();	
		}	
		$resultat .= '</table>';
	
		return $resultat;
	}	
//utf8_encode(strftime('%A %d %B, %H:%M', strtotime($this->DateTirage)));

	function Ecrire($tabPlanche, &$isRecommande){	
		$resultat ='@9999-99-99'.  '_RECOMMANDES du ' . MarqueurDateCommande() .' sur : ' . $this->Nom . '_' . $this->CodeEcole . '_' . $this->AnneeScolaire . '_' . $this->Details.'@'.PHP_EOL; 
		//@2020-12-03_(ISOLEES) Elementaire La Chateigneraie-HAUTE GOULAINE_ECOLE-1017_Ecole web !@ 
		for($i = 0; $i < count($this->tabCommandes); $i++){
			$isEcris = false;
			$resultat .= $this->tabCommandes[$i]->Ecrire($tabPlanche, $isEcris);		
			$isRecommande = $isRecommande || $isEcris;		
		}		
		if ($isRecommande) {
			return $resultat;
		}	
		else{
			return '';			
		}				
	}		
}
class CCommande {
    var $Numero;
    var $CmdClient;
    var $NumFacture;
    var $Prenom;
    var $Nom;
    var $Adresse;
    var $CodePostal;
    var $Ville;
	var $Ouverte;
	var $tabProduits;
    
    function __construct($str){
        //NEW UTF-8 $this->CmdClient = utf8_encode($str);
        $this->CmdClient = $str;
		//$morceau = explode(".", $this->CmdClient);
        //NEW UTF-8 $morceau = explode("_", utf8_encode(str_replace("#", "", $str)));
        $morceau = explode("_", str_replace("#", "", $str));
					//echo $str . "   ...  ";
		$this->Numero = $morceau[0];
        $TailleInfo = count($morceau);

        if ($TailleInfo > 1){$this->NumFacture = $morceau[1];}         
        if ($TailleInfo > 2){$this->Prenom = $morceau[2];}  
        if ($TailleInfo > 3){$this->Nom = $morceau[3];}  
        if ($TailleInfo > 4){$this->Adresse = $morceau[4];}  
		if ($TailleInfo > 5){$this->Adresse = trim($this->Adresse . ' ' . $morceau[5]);}  
        if ($TailleInfo > 6){$this->CodePostal = $morceau[6];}  
        if ($TailleInfo > 7){$this->Ville = $morceau[7];}   
		/* new 12-11 */
		$this->Ouverte = true;
		if ($TailleInfo > 8){$this->Ouverte = ($morceau[8] != 'FERMER');}  

		$this->tabProduits = array();		
    } 
    function FormatNumCmd(){
        $numCMD = trim(str_replace("#", "", $this->Numero));
        $numCMD = sprintf ("%04s\n",  $numCMD);
        return $numCMD;
    }    
	function AjoutPDT($unPDT){
		array_push($this->tabProduits,$unPDT);
    }     
	//function Affiche(&$isParPage){	
    function Affiche(&$gestionPage){
		$resultat = '';
		$nbPlanche = 0;
		if ($gestionPage->isPage){ // Pour le cartonnage
			$resultat .= '<div class="commande"  >';				
				$resultat .= '<button  class="Titrecommande" onclick="VisuCMD(\''.$this->Numero . '\');" > Commande <span class="grosNumCMD">' . $this->FormatNumCmd() . '</span> ' . $this->NumFacture . ' (' . $this->Prenom . ' ' . $this->Nom . ', ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .')</button>';
				//Le contenu ...
				//$resultat .= '<div id="'. $this->Numero .'" class="Contenucommande" >';
				$resultat .= '<div id="'. $this->Numero .'" class="Contenucommande" '.  ($this->Ouverte ? '': 'style="display: none;"')    .'>';


				
				
					for($i = 0; $i < count($this->tabProduits); $i++){
						$resultat .= $this->tabProduits[$i]->Affiche();
						$nbPlanche = $nbPlanche + count($this->tabProduits[$i]->colPlanche);
					}
					// Afffichage Facture nb de planche
					$resultat .= '<div class="ResumeCMD">'; //Debut du produit
						$resultat .= '<h5>'.'La commande comprend : '.'</h5><br>'  ;
						$resultat .= '<span class="nbPanches">'. $nbPlanche .'<br>'  ;
						$resultat .= '<h6>'. 'Planches' .'</h6></span>';
						$resultat .= '<span class="nbPanches">+</span>'  ;
						$resultat .= '<span class="nbPanches"><img class="maFacture" src="img/Bonco.png"  title="Bon de commande / Facture"></span>';	
					//$resultat .= '<p>'. 'Facture' .'</p>';
					$resultat .= '</div>';				
				
				$resultat .= '</div>';
			$resultat .= '</div>';
			$gestionPage->compteurCMD++;
		}   
		else{ // Pour la recherche
			$resultat .= '<div id="C-'. $this->Numero .'" class="commande"  >';			
				$resultat .= '<button  class="TitrecommandeRecherche"> Commande <span class="grosNumCMD"> ' . $this->FormatNumCmd() . '</span> ' . $this->NumFacture . ' (' . $this->Prenom . ' ' . $this->Nom . ', ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .')</button>';
				//Le contenu ...
				$resultat .= '<div class="Contenucommande">';
				for($i = 0; $i < count($this->tabProduits); $i++){
					$resultat .= $this->tabProduits[$i]->Affiche();			
				}
				$resultat .= '</div>';
			$resultat .= '</div>';
		
			
		}
		return $resultat;				
	}
	function AffichePlancheAProduire(){
		$resultat = '';

		for($i = 0; $i < count($this->tabProduits); $i++){
			$resultat .= $this->tabProduits[$i]->AffichePlancheAProduire();			
		}

		return $resultat;				
	}
	function FichiersSourceNecessaires(){
		$resultat = '';

		for($i = 0; $i < count($this->tabProduits); $i++){
			$resultat .= $this->tabProduits[$i]->FichiersSourceNecessaires();			
		}

		return $resultat;				
	}
	function ProduitsNecessaires(){
		$resultat = '';

		for($i = 0; $i < count($this->tabProduits); $i++){
			$resultat .= $this->tabProduits[$i]->ProduitsNecessaires();			
		}
		return $resultat;
	}		
	function AfficheCommandesAProduire(){
		$resultat = '';
		//for($i = 0; $i < count($this->tabProduits); $i++){
			$resultat .= '<tr>
			<td width="15%" class ="StyleNumCommande">'. $this->FormatNumCmd() . '</td>
			<td width="85%" class ="StyleInfoClient"><b>' . $this->Prenom . ' ' . $this->Nom . '</b>, ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .'</td>
			</tr>';
;			
		//}
//' . $this->FormatNumCmd() . '</span> ' . $this->NumFacture . ' (' . $this->Prenom . ' ' . $this->Nom . ', ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .')
		return $resultat;		

	}	


	function Ecrire($tabPlanche, &$isRecommande){
		$resultat ='#'. $this->Numero . '_' . $this->NumFacture . '_' . $this->Prenom . '_' . $this->Nom . '_' . $this->Adresse . '_' . $this->CodePostal .'_' . $this->Ville .'_' . $this->Ouverte .'#'. PHP_EOL; 
	
		for($i = 0; $i < count($this->tabProduits); $i++){
			$isEcris = false;
			$resultat .= $this->tabProduits[$i]->Ecrire($tabPlanche, $isEcris);	
			$isRecommande = $isRecommande || $isEcris;			
		}		
		if ($isRecommande) {
			return $resultat;
			
		}	
		else{
			return '';			
		}				
	}	
}

class CProduit { // <CP-CE1 1%Produits Carrés Cadre-ID>
	var $Classe;
	var $Nom;
	var $colPlanche;
    function __construct($str){
		$str = str_replace("<", "", str_replace(">", "", $str));
		//echo "jhgjhg :  " . $str;
		$morceau = explode("%", $str);		

        $this->Classe = $morceau[0]; 
		if (count($morceau) > 1){
			$this->Nom = $morceau[1];
			$this->Classe = $morceau[0]; 
		} 
		else{
			//$this->Classe = '';
			$this->Nom = $morceau[0];			
		}
		$this->colPlanche = array();
    }   
	function AjoutPlanche($unePlanche){
		array_push($this->colPlanche,$unePlanche);
		
    } 	
    
	function Affiche(){
		$resultat = '';
		$resultat .= '<span id="'. $this->Classe. ' ' . $this->Nom .'" class="produit">'; //Debut du produit
		$resultat .= '<h5>'.$this->Classe.'</h5>'  ;
		$resultat .= '<h4>'. $this->Nom.'</h4><br>'  ;
		for($i = 0; $i < count($this->colPlanche); $i++){
			$resultat .= $this->colPlanche[$i]->Affiche();			
		}
		$resultat .= '</span>';
		return $resultat;
	}	
    function AffichePlancheAProduire(){
		$resultat = '';
		$resultat .= '<span >'; //Debut du produit

		//$resultat .= '<h3>'. $this->Nom.count($this->colPlanche).'</h3>'  ;
		for($i = 0; $i < count($this->colPlanche); $i++){
			$resultat .= '<tr>' .$this->colPlanche[$i]->AffichePlancheAProduire(). '</tr>' ;			
		}
		$resultat .= '</span>';
		return $resultat;
	}	    

	function AfficheCommandesAProduire(){
		$resultat = '';
		$resultat .= '<span >'; //Debut du produit

		//$resultat .= '<h3>'. $this->Nom.count($this->colPlanche).'</h3>'  ;
		for($i = 0; $i < count($this->colPlanche); $i++){
			$resultat .= '<tr>' .$this->colPlanche[$i]->AffichePlancheAProduire(). '</tr>' ;			
		}
		$resultat .= '</span>';
		return $resultat;
	}		
	function Ecrire($tabPlanche, &$isRecommande){
		$resultat ='<'. $this->Classe. '%' . $this->Nom .'>'. PHP_EOL;
		for($i = 0; $i < count($this->colPlanche); $i++){
			$isEcris = false;
			$resultat .= $this->colPlanche[$i]->Ecrire($tabPlanche, $isEcris);		
			$isRecommande = $isRecommande || $isEcris;
		}		 
		if ($isRecommande) {
			return $resultat;
		}	
		else{
			return '';			
		}				
	}	
	function FichiersSourceNecessaires(){
		$resultat = '';
		for($i = 0; $i < count($this->colPlanche); $i++){
			$resultat .= $this->colPlanche[$i]->FichiersSourceNecessaires() ;			
		}
		return $resultat;
	}		
	function ProduitsNecessaires(){
		//$resultat = 'qsdqsdqsddq';
		$resultat = $this->Nom . $GLOBALS['SeparateurInfoPlanche'] ;

		return $resultat;
	}			
}

class CPlanche {
	var $FichierPlanche;
	var $IndexOrdre;
	var $FichierSource;
    var $Taille;
	var $Type;
	var $Teinte;
	//var $Extension;
    function __construct($str){		
		$this->FichierPlanche = $str;	
		//echo '<br><br><br> fsd : ' . $this->FichierPlanche;		

		if (substr($str,0,1) == 'P'){	
			//NEW UTF-8 $morceau = explode(".", utf8_encode($this->FichierPlanche));
			$NomfichierSansExtension = substr($this->FichierPlanche,0,strrpos($this->FichierPlanche, "."));
			$morceau = explode($GLOBALS['SeparateurInfoPlanche'], $NomfichierSansExtension);
			//echo 'sdgsdgfd ' . count($morceau)	;
			if (count($morceau)< 2) {
				$morceau = explode(".", $NomfichierSansExtension); // Comme avant
			}
			$this->IndexOrdre = $morceau[0];
			$this->FichierSource = $morceau[1]. '.jpg';
			$this->Type = $morceau[2];
			$this->Taille = $morceau[3]; 
			$this->Teinte = (count($morceau) > 4)?$morceau[4]:'';   	
		}else{
			//echo "<br>XX SeparateurInfoPlancheLab0 : " . $GLOBALS['SeparateurInfoPlancheLab0']. "  <br>// FichierPlanche " .  $this->FichierPlanche	;
			$morceau = explode($GLOBALS['SeparateurInfoPlancheLab0'], $this->FichierPlanche);
			
			$this->FichierSource = $morceau[0];
			$this->Taille = (count($morceau) > 1)?$morceau[1]:'';
			$this->Type = (count($morceau) > 2)?$morceau[2]:'';
			//$this->Teinte = (count($morceau) > 3)?$morceau[3]:'';     
			//var_dump($morceau) ;
		}
    }   	
	function Affiche(){
		$resultat = '';
		$resultat .= '<span  id="'. urldecode($this->FichierPlanche) . '" class="planche" title="dqsdsq '. urldecode($this->FichierPlanche) . '">';

			global $EcoleEnCours;
			//echo $GLOBALS['repTIRAGES'] . '<br>';
			//echo $GLOBALS['repMINIATURES']. '<br>';

			
			$valideNomPlanche = str_replace("#", "%23", $this->FichierPlanche);
			$Lien = $GLOBALS['repMINIATURES'] . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;
			$LienBig = $GLOBALS['repTIRAGES'] . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;				
			if (!file_exists($LienBig)){$LienBig = $Lien;}
			
			//$resultat .= '<a href="CMDAffichePlanche.php?urlImage=' . $LienBig . '"><img id="myImgPlanche" src="' . $Lien . '"  title="'. urldecode($this->FichierPlanche) . '"></a>';	
			$resultat .= '<img class="NomPhotoZoom" onclick="ZoomPhoto(\''. $LienBig  .'\')" id="ImgPlanche" src="' . $Lien . '" title="Cliquez pour zoomer">';	


			
			$resultat .='<button class="NomPlancheSelection" onclick="SelectionnerCliquePhoto(this.parentElement)" title="Cliquez pour préparer un tirage">

			<span style="color:Fuchsia">'.$this->IndexOrdre .'</span>.
			<span style="color:LimeGreen">'. substr($this->FichierSource,0,-4).'</span>.
			<span style="color:DarkTurquoise">'.$this->Type .'</span>.
			<span style="color:DarkOrchid">'.$this->Taille .'</span>.
			<span style="color:Turquoise">'.$this->Teinte .'</span>.jpg


			</button>';


			//$resultat .= '<p class="NomPlancheSelection" onclick="SelectionnerCliquePhoto(this.parentElement)">'. $this->FichierPlanche .'</p>';
			
		$resultat .= '</span> ';
		return $resultat;
	}
	function AffichePlancheAProduire(){
		//$resultat = 'qsdqsdqsddq';
		$resultat = 	'<td width="40%" class ="StyleFichier">'.urldecode($this->FichierSource) . 
							'</td><td width="20%" class ="StyleTaille">' . $this->Taille . 
								'</td><td width="40%" class ="StyleProduit">' . $this->Type .'</td>';

		return $resultat;
	}


	function RECOPIEREcrire($tabPlanche, &$isRecommande){
		 $resultat ='';
		if (in_array($this->FichierPlanche, $tabPlanche)) {
			$isRecommande = in_array($this->FichierPlanche, $tabPlanche);
			$resultat = $this->FichierPlanche . PHP_EOL;
			//fputs($Fichier, $lines[$i]);
			//(RECOMMANDES) EN COURS

			global $EcoleEnCours;
			 
			$valideNomPlanche = str_replace("#", "%23", $this->FichierPlanche);
			$Lien = $GLOBALS['repMINIATURES'] . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;			
			$LienBig = $GLOBALS['repTIRAGES'] . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;	
			
			$DossierRECOenCours = CreationDossier($GLOBALS['repTIRAGES'] . $GLOBALS['FichierDossierRECOMMANDE']);
			$DossierTailleRECOenCours = CreationDossier($DossierRECOenCours . '/' . $this->Taille . ' (1ex de chaque)');
			$DossierMiniatureRECOenCours = CreationDossier($GLOBALS['repMINIATURES'] . $GLOBALS['FichierDossierRECOMMANDE']);
			$DossierMiniatureTailleRECOenCours = CreationDossier($DossierMiniatureRECOenCours . '/' . $this->Taille . ' (1ex de chaque)');				
			
			RecopierPlanche(utf8_decode($Lien),utf8_decode($DossierMiniatureTailleRECOenCours. '/'  . $valideNomPlanche));
			RecopierPlanche(utf8_decode($LienBig),utf8_decode($DossierTailleRECOenCours. '/'  . $valideNomPlanche));
			//RecopierPlanche($LienBig,$LienBig.".jpg");

		}
		return $resultat;				
	}
	function Ecrire($tabPlanche, &$isRecommande){
		$resultat ='';
	   if (in_array($this->FichierPlanche, $tabPlanche)) {
		   $isRecommande = true; // = in_array($this->FichierPlanche, $tabPlanche);
		   //$resultat = $this->FichierPlanche . PHP_EOL;
		   //fputs($Fichier, $lines[$i]);
		   //(RECOMMANDES) EN COURS
		   $resultat = $this->FichierSource;
		   $resultat .= '_'. $this->Taille; 
		   $resultat .= '_'. $this->Type;
		   $resultat .= '_'. $this->Teinte;
		   $resultat .= PHP_EOL;
		   ///////////////////////////////////////////////////
				
		}
		return $resultat;					
	}
	function EcrireLab0(){
		$resultat = $this->FichierSource;
		$resultat .= '_'. $this->Taille; 
		$resultat .= '_'. $this->Type;
		$resultat .= '_'. $this->Teinte;
		$resultat .= PHP_EOL;
		return $resultat;					
	}	
	function FichiersSourceNecessaires(){
		$resultat = $this->FichierSource;
		/**/
		if (strpos($resultat,'-QCoin')>-1){
			$MorceauAsurpprimer = substr($resultat,strpos($resultat,'-QCoin'),7);
			$resultat = str_replace($MorceauAsurpprimer,'',$resultat);
		}
		return $resultat . $GLOBALS['SeparateurInfoPlanche'] ;
	}	
}
function RecopierPlanche($LienOrigine,$LienDestination){
	$valReturn = copy($LienOrigine,$LienDestination);

	if($GLOBALS['isDebug']){
		if ($valReturn){
			Echo '<br>Le fichier '.$LienOrigine.' a été copié<br> dans le répertoire '.$LienDestination;
		}
		else{
			echo "<br>Erreur La copy n'est pas faite !";				
		} 	
		return $valReturn;
	}
}

class CCatalogueProduit {
    var $Existe;
    var $ScriptsPS;
	
	function __construct($NomDossiserScript){
        $this->ScriptsPS = $NomDossiserScript;
		$this->Existe = (file_exists($GLOBALS['repGABARITS'] . $this->NomCatalogue() ));
	}	
	function DossierCatalogue(){
		return 'Catalogue' . $this->ScriptsPS;
	}
	function NomCatalogue(){
		return $this->DossierCatalogue() . '.csv';
	}	
	    
	function DropListeScriptsRecadrages($valDefaut = ''){ 
		//$laDropliste = '<option value="(facultatif)">(facultatif)</option>';
		//$laDropliste .= '<option value="">(rien)</option>';
		$lesScripts = $this->TabScriptsPhotoshop();	
		$laDropliste = '';	
        for($i = 1; $i < count($lesScripts); $i++){
            if (substr($lesScripts[$i],0,9) == 'Portrait-') {
				$aSelectionner = ($lesScripts[$i] == $valDefaut)?'selected':'';
				$laDropliste .= '<option value="'. $lesScripts[$i] .'" '.$aSelectionner.'>'. $lesScripts[$i] .'</option>';
            }		
        }
		if($laDropliste == ''){
			$laDropliste = 'VIDE';

		} else{
			$laDropliste = '<option value="(facultatif)">(facultatif)</option>
						<option value="">(rien)</option>'
						. $laDropliste;
		}
		return $laDropliste;
	}	
	function DropListeScriptsTailles($valDefaut = ''){ 
		$laDropliste = '<option value="(obligatoire !)">(obligatoire !)</option>';
		$lesScripts = $this->TabScriptsPhotoshop();		
        for($i = 1; $i < count($lesScripts); $i++){
            if (($lesScripts[$i] != '')&&(is_numeric(substr($lesScripts[$i],0,1)))) {
				$aSelectionner = ($lesScripts[$i] == $valDefaut)?'selected':'';
				$laDropliste .= '<option value="'. $lesScripts[$i] .'" '.$aSelectionner.'>'. $lesScripts[$i] .'</option>';
            }		
        }
		
        for($i = 0; $i < count($GLOBALS['ProduitsNONLABO']); $i++){
			$aSelectionner = ($GLOBALS['ProduitsNONLABO'][$i] == $valDefaut)?'selected':'';
			$laDropliste .= '<option value="'. $GLOBALS['ProduitsNONLABO'][$i] .'" '.$aSelectionner.'>'. $GLOBALS['ProduitsNONLABO'][$i] .'</option>';
        }
		return $laDropliste;
	}	

	function DropListeAutresScripts($valDefaut = ''){ 
		$laDropliste = '<option value="(facultatif)">(facultatif)</option>';
		$laDropliste .= '<option value="">(rien)</option>';
		$lesScripts = $this->TabScriptsPhotoshop();	
        for($i = 1; $i < count($lesScripts); $i++){
            if (($lesScripts[$i] != '')&&(!is_numeric(substr($lesScripts[$i],0,1)))&&(substr($lesScripts[$i],0,9) != 'Portrait-')) {
				$aSelectionner = ($lesScripts[$i] == $valDefaut)?'selected':'';
				$laDropliste .= '<option value="'. $lesScripts[$i] .'" '.$aSelectionner.'>'. $lesScripts[$i] .'</option>';
            }		
        }
		return $laDropliste;
	}	
	function DropListeScriptsTransformation($valDefaut = ''){ 
		$laDropliste = $this->DropListeAutresScripts($valDefaut);	
        for($i = 0; $i < count($GLOBALS['ProduitsPourGROUPE']); $i++){
			$aSelectionner = ($GLOBALS['ProduitsPourGROUPE'][$i] == $valDefaut)?'selected':'';
			$laDropliste .= '<option value="'. $GLOBALS['ProduitsPourGROUPE'][$i] .'" '.$aSelectionner.'>'. $GLOBALS['ProduitsPourGROUPE'][$i] .'</option>';
        }
		return $laDropliste;
	}	
	function DropListeScriptsTeinte($valDefaut = ''){ 
		return $this->DropListeAutresScripts($valDefaut);	
	}	
	function TabScriptsPhotoshop(){ 
		$leTableauDeScriptsPhotoshop = array();
		$maBibliothequeScriptPS = $GLOBALS['CSVBibliothequeScriptPS'] ;	
		//echo $maBibliothequeScriptPS;		
		if (file_exists($maBibliothequeScriptPS)){ 
			$file = fopen($maBibliothequeScriptPS, "r");
			if ($file) {
				while(!feof($file)) {
					$line = trim(fgets($file));	
					//echo '<br>' . $this->AnneeScolaire . ' = ' . substr($line,0,strpos($line, ';'));				
					if($this->ScriptsPS == substr($line,0,strpos($line, ';'))){
						$leTableauDeScriptsPhotoshop = explode(';', $line);
					}
				}
				//var_dump($leTableauDeScriptsPhotoshop);
				fclose($file);	
			}	
		}
		return $leTableauDeScriptsPhotoshop;
	}	
	function DropListeProduits(){ 
		$laDropliste ='';
		$lesProduits = $this->TabProduits();		
        for($i = 0; $i < count($lesProduits); $i++){
            if ($lesProduits[$i] != '') {
				$morceau = explode(";",  $lesProduits[$i]);
				$laDropliste .= '<a href=javascript:void(0); Code="'.$morceau[0].'" onclick="CliqueDropDown(this)">'.$morceau[1].'</a>';
            }		
        }
		return $laDropliste;
	}	
	function TabProduits(){ 
		$monCatalogueScriptPS = $GLOBALS['repGABARITS'] . $this->NomCatalogue()	;	
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
		}
		return $CataloguePRODUITS;
	}
}

class CProjetSource {
	var $NomProjet;
	var $Dossier;
	var $CodeEcole;
	var $AnneeScolaire;
    var $ScriptsPS;
	
	function __construct($CodeEcole,$AnneeScolaire){
		if (file_exists($GLOBALS['CSVCatalogueSources'])){
			$TabCSV = csv_to_array($GLOBALS['CSVCatalogueSources'], ';');
			$NbLignes=count($TabCSV);
			if ($NbLignes){
				for($i = 0; $i < $NbLignes; $i++){ 
					if ($CodeEcole == $TabCSV[$i]["Code"]  && $AnneeScolaire == $TabCSV[$i]["AnneeScolaire"]){
						$Dossier = $TabCSV[$i]["DossierSources"];	
						$Dossier = "../.." . urldecode(substr($Dossier, strpos($Dossier, '/SOURCES')));
						//echo '<br>'.$Dossier;
						$this->NomProjet = $TabCSV[$i]["NomProjet"];
						$this->Dossier = $Dossier;
						$this->CodeEcole = $CodeEcole;
						$this->AnneeScolaire = $AnneeScolaire;
						$this->ScriptsPS = $TabCSV[$i]["Rep Scripts PS"];
						break;
					}
				}
			}
		}
	}

	function DossierCatalogue(){
		return 'Catalogue' . $this->ScriptsPS;
	}
	function NomCatalogue(){
		return $this->DossierCatalogue() . '.csv';
	}	
	function DropListeScriptsRecadrages($valDefaut = ''){ 
		//$laDropliste = '<option value="(facultatif)">(facultatif)</option>';
		//$laDropliste .= '<option value="">(rien)</option>';
		$lesScripts = $this->TabScriptsPhotoshop();	
		$laDropliste = '';	
        for($i = 1; $i < count($lesScripts); $i++){
            if (substr($lesScripts[$i],0,9) == 'Portrait-') {
				$aSelectionner = ($lesScripts[$i] == $valDefaut)?'selected':'';
				$laDropliste .= '<option value="'. $lesScripts[$i] .'" '.$aSelectionner.'>'. $lesScripts[$i] .'</option>';
            }		
        }
		if($laDropliste == ''){
			$laDropliste = 'VIDE';

		} else{
			$laDropliste = '<option value="(facultatif)">(facultatif)</option>
						<option value="">(rien)</option>'
						. $laDropliste;
		}
		return $laDropliste;
	}	
	function DropListeScriptsTailles($valDefaut = ''){ 
		$laDropliste = '<option value="(obligatoire !)">(obligatoire !)</option>';
		$lesScripts = $this->TabScriptsPhotoshop();		
        for($i = 1; $i < count($lesScripts); $i++){
            if (($lesScripts[$i] != '')&&(is_numeric(substr($lesScripts[$i],0,1)))) {
				$aSelectionner = ($lesScripts[$i] == $valDefaut)?'selected':'';
				$laDropliste .= '<option value="'. $lesScripts[$i] .'" '.$aSelectionner.'>'. $lesScripts[$i] .'</option>';
            }		
        }
		
        for($i = 0; $i < count($GLOBALS['ProduitsNONLABO']); $i++){
			$aSelectionner = ($GLOBALS['ProduitsNONLABO'][$i] == $valDefaut)?'selected':'';
			$laDropliste .= '<option value="'. $GLOBALS['ProduitsNONLABO'][$i] .'" '.$aSelectionner.'>'. $GLOBALS['ProduitsNONLABO'][$i] .'</option>';
        }
		return $laDropliste;
	}	

	function DropListeAutresScripts($valDefaut = ''){ 
		$laDropliste = '<option value="(facultatif)">(facultatif)</option>';
		$laDropliste .= '<option value="">(rien)</option>';
		$lesScripts = $this->TabScriptsPhotoshop();	
        for($i = 1; $i < count($lesScripts); $i++){
            if (($lesScripts[$i] != '')&&(!is_numeric(substr($lesScripts[$i],0,1)))&&(substr($lesScripts[$i],0,9) != 'Portrait-')) {
				$aSelectionner = ($lesScripts[$i] == $valDefaut)?'selected':'';
				$laDropliste .= '<option value="'. $lesScripts[$i] .'" '.$aSelectionner.'>'. $lesScripts[$i] .'</option>';
            }		
        }
		return $laDropliste;
	}	
	function DropListeScriptsTransformation($valDefaut = ''){ 
		$laDropliste = $this->DropListeAutresScripts($valDefaut);	
        for($i = 0; $i < count($GLOBALS['ProduitsPourGROUPE']); $i++){
			$aSelectionner = ($GLOBALS['ProduitsPourGROUPE'][$i] == $valDefaut)?'selected':'';
			$laDropliste .= '<option value="'. $GLOBALS['ProduitsPourGROUPE'][$i] .'" '.$aSelectionner.'>'. $GLOBALS['ProduitsPourGROUPE'][$i] .'</option>';
        }
		return $laDropliste;
	}	
	function DropListeScriptsTeinte($valDefaut = ''){ 
		return $this->DropListeAutresScripts($valDefaut);	
	}	
	function TabScriptsPhotoshop(){ 
		$leTableauDeScriptsPhotoshop = array();
		$maBibliothequeScriptPS = $GLOBALS['CSVBibliothequeScriptPS'] ;	
		//echo $maBibliothequeScriptPS;		
		if (file_exists($maBibliothequeScriptPS)){ 
			$file = fopen($maBibliothequeScriptPS, "r");
			if ($file) {
				while(!feof($file)) {
					$line = trim(fgets($file));	
					//echo '<br>' . $this->AnneeScolaire . ' = ' . substr($line,0,strpos($line, ';'));				
					if($this->ScriptsPS == substr($line,0,strpos($line, ';'))){
						$leTableauDeScriptsPhotoshop = explode(';', $line);
					}
				}
				//var_dump($leTableauDeScriptsPhotoshop);
				fclose($file);	
			}	
		}
		return $leTableauDeScriptsPhotoshop;
	}	
	function DropListeProduits(){ 
		$laDropliste ='';
		$lesProduits = $this->TabProduits();		
        for($i = 0; $i < count($lesProduits); $i++){
            if ($lesProduits[$i] != '') {
				$morceau = explode(";",  $lesProduits[$i]);
				$laDropliste .= '<a href=javascript:void(0); Code="'.$morceau[0].'" onclick="CliqueDropDown(this)">'.$morceau[1].'</a>';
            }		
        }
		return $laDropliste;
	}	
	function TabProduits(){ 
		$monCatalogueScriptPS = $GLOBALS['repGABARITS'] . $this->NomCatalogue()	;	
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
		}
		return $CataloguePRODUITS;
	}
	
}

class CImgSource {
	var $Fichier;
	var $Dossier;
	var $CodeEcole;
    var $ScriptsPS;
	var $AnneeScolaire;

	//var $Extension;
    function __construct($Fichier,$Dossier,$CodeEcole,$AnneeScolaire,$ScriptsPS){	
			$this->Fichier = $Fichier;
			$this->Dossier = $Dossier;
			$this->CodeEcole = $CodeEcole;
			$this->AnneeScolaire = $AnneeScolaire;
			$this->ScriptsPS = $ScriptsPS;
	}   

	function isGroupe(){
		return (strlen($this->Fichier) > 10); // à 10 caharctere ;
	}
	
	function Affiche(){
		$resultat = '';
			//SUPRESSION DU CACHE $Lien = $this->Dossier . 'Cache/' .  $this->Fichier;
			$Lien = $this->Dossier .  $this->Fichier;
			$LienBig = $this->Dossier . $this->Fichier;		
			$Lien = 'SRCImage.php?fichierImage='.$Lien;	
			//$Lien = 'couchersoleil.jpg';	
			
			if (!file_exists($LienBig)){$LienBig = $Lien;}
			if (strpos(strtolower($this->Fichier),'fratrie')){
				$Lien = '../../Code/res/img/Fratries.png';
			}		
				  
		$resultat .= '   
			<span  onclick="CopierCommandes(this)" 
			id="'. urldecode($this->Fichier) . '" 
			Nb="0" 
			class="'.($this->isGroupe()?'PlancheGroupe':'PlancheIndiv') .'">';	

			$resultat .= '<button class="ZoomerPhoto" onclick="ZoomPhoto(\''. $LienBig  .'\',this.parentElement)"  >
			<img id="'. ($this->isGroupe()?'ImgPlancheGroupe':'ImgPlancheIndiv') .'" 
				src="' . $Lien . '"  title="Cliquez pour agrandir la photo : '. urldecode($this->Fichier) . '">
			</button>';

			$resultat .= '<input type="hidden" name="lesPhotoSelection" id="ZlesPhotoSelection" value="0" /> 
			<input type="hidden" name="lesCmdesLibres" id="ZlesCmdesLibres" value="0" /> 
			<input type="hidden" name="lesFichiersBoutique" id="ZlesFichiersBoutique" value="0" /> ';
			
			$resultat .='<button class="NomPhotoSelection" onclick="SelectionnerCliquePhoto(this.parentElement)" title="Cliquez pour préparer un tirage">
			<p>'. $this->Fichier  .'</p>
			</button>';
			//$resultat .= '</form>';

			$resultat .= '<span class="ZoneMoinsPlus">';					
			$resultat .= '<span onclick="NbPlancheMOINS(this.parentElement)"  class="moinsplus">-</span>
							<span class="NombrePhoto"> 0 </span>
							<span onclick="NbPlanchePLUS(this.parentElement)"  class="moinsplus">+</span>';			
			$resultat .= '</span>';	

			$resultat .= ($this->isGroupe()?'<span>':'');			
			$resultat .= ($this->isGroupe()?'</span>':'');			
			
			//$resultat .= '<p>'. substr($this->Fichier, 0, -4)  .'</p>';

			$Argument = '&urlImage=' . $LienBig;
			$Argument .= '&CodeEcole=' . urlencode($this->CodeEcole). '&AnneeScolaire=' . urlencode($this->AnneeScolaire);

			$resultat .= '<div class="ImageFichierWeb"></div>';		

		$resultat .= '</span> ';
		
		return $resultat;
	}
	
}
class CNomFichierGroupe {
    var $Numero;
	var $TypeGroupe;	
	var $NomClasse;
	var $Version;

    function __construct($NomFichierGroupe){   //0100-CADR-5A-CM2.jpg
		$PosMarqueur = strpos($NomFichierGroupe,'-');
		if ( $PosMarqueur > 1){
			$this->Numero = substr($NomFichierGroupe,0, $PosMarqueur); 			
			if (strpos(strtolower($NomFichierGroupe),'fratrie')){
				$this->NomClasse = 'Fratries';
				$this->TypeGroupe = 'Fratries';
			}
			else{
				$ResteCodeFichierGroupe = substr($NomFichierGroupe,$PosMarqueur + 1, strripos($NomFichierGroupe,'.') - $PosMarqueur - 1); // CADR-5A-CM2 :: enlever le code et l'extension
				$PosMarqueur = strpos($ResteCodeFichierGroupe,'-');	
				$this->TypeGroupe = substr($ResteCodeFichierGroupe,0, $PosMarqueur); 
				$this->NomClasse = substr($ResteCodeFichierGroupe,$PosMarqueur +1 ); // 5A-CM2 :: enlever le type de groupe
				if (strpos($this->NomClasse,'@') > 1){ // Nom du groupe avec plusieurs version
					$morceauNomClasse = explode('@', $this->NomClasse);
					$this->NomClasse = $morceauNomClasse[0];
					$this->Version = $morceauNomClasse[1];				
				}	
			}	
		}	
	} 
	
}

function csv_to_array($filename='', $delimiter=';')
{
	try {
		//echo ('$filename ' . $filename);
		if (!file_exists($filename) || !is_readable($filename))
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
	catch (ErrorException $e) {
		echo ('message ' . $e->getMessage() . 'row : ' .$row);
		return false;
	}
	
}

function MarqueurDateCommande() {

	setlocale(LC_TIME, 'fr_FR');

	$date = new DateTime();


	return $date->format('d/m/Y à H:i');
}

?>