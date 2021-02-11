<?php
/**/

ini_set('display_errors','on');
error_reporting(E_ALL);

$DateISOLEE = '2020-09-31';

class CGroupeCmdes {
    var $ListePromoteurs;
    var $ListeCommandes;
    var $nomFichierCmdes;	
	var $tabFICHIERLabo;
	var $tabCMDLabo;  
	var $DateISOLEE;
	var $colEColes;

	
    function __construct($myfileName){
		$this->tabFICHIERLabo = $myfileName;
		$myfile = fopen($myfileName, "r") or die('Unable to open file : ' .$myfileName);
		$this->tabFICHIERLabo = array();
		// Output one line until end-of-file
		
		//$GLOBALS['DateISOLEE'] = substr($myfileName, strripos($myfileName, '/') + 1,10);
		
		
		while(!feof($myfile)) {
			array_push($this->tabFICHIERLabo,trim(fgets($myfile)));
		}
		fclose($myfile);	
		$this->DateISOLEE = substr($myfileName, strripos($myfileName, '/') + 1,10);		
		$this->colEColes = array();
		$this->tabCMDLabo = array();
		if ($this->tabFICHIERLabo){
			for($i = 0; $i < count($this->tabFICHIERLabo); $i++){
				$identifiant = substr($this->tabFICHIERLabo[$i],0,1);
				//Si Commande pas vide , on ajoute la commande au tableau!
				if ($identifiant == '@') {
					$curEcole = new CEcole($this->tabFICHIERLabo[$i], $this->DateISOLEE);
					array_push($this->colEColes,$curEcole);			
				}
				if ($identifiant == '#') {
					$curCommande = new CCommande($this->tabFICHIERLabo[$i]);
					$curEcole->AjoutCMD($curCommande);
					array_push($this->tabCMDLabo,$curCommande);
				}				
				if ($identifiant == '<') {
					$curProduit = new CProduit($this->tabFICHIERLabo[$i]);
					$curCommande->AjoutPDT($curProduit);
				}	
				if ($identifiant == 'P') {
					$curPlanche = new CPlanche($this->tabFICHIERLabo[$i]);
					$curProduit->AjoutPlanche($curPlanche);
				}				
			}
		}  
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
    function AfficheDebutPage($unNomEcole){
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
    var $Details;
	var $colCMD;
	var $DateISOLEE;

    function __construct($str, $dateIsole){
        //NEW UTF-8 $morceau = explode("_", utf8_encode(str_replace("@", "", $str)));
        $morceau = explode("_", str_replace("@", "", $str));
		$this->DateTirage = $morceau[0];
        $this->Nom = $morceau[1];
        $this->Details = $morceau[2];
		$this->colCMD = array();
		$this->DateISOLEE = $dateIsole;
    }
    function RepTirage(){
		if (stripos($this->Nom, '(ISOLEES)') !== false) { // C'est des ISOLEES
			//return $GLOBALS['DateISOLEE'] . '-CMD-ISOLEES' ;
			return $this->DateISOLEE . '-CMD-ISOLEES' ;
		}	
		else{
			return $this->DateTirage . '-' .$this->Nom ;	
		}	
    }   
	function AjoutCMD($uneCMD){
		array_push($this->colCMD,$uneCMD);
    } 	
    function Affiche(&$gestionPage){
		//$isParPage = ($numeroPage>0);
		$resultat = '';
		$numPage = 0;
		
		$resultat .= $gestionPage->AfficheDebutPage($this->Nom);
		/**/$resultat .= '<div class="ecole">';	
		$resultat .= $this->Nom ;  
		$resultat .= '</div>';	
		
		for($i = 0; $i < count($this->colCMD); $i++){
			$resultat .= $gestionPage->AfficheDebutPage($this->Nom);

			$resultat .= $this->colCMD[$i]->Affiche($gestionPage);	
			$resultat .= $gestionPage->AfficheFinPage();
		}			
		return $resultat;
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
	var $colPDT;
    
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
		if ($TailleInfo > 5){$this->Adresse = $this->Adresse . ' ' . $morceau[5];}  
        if ($TailleInfo > 6){$this->CodePostal = $morceau[6];}  
        if ($TailleInfo > 7){$this->Ville = $morceau[7];}   
		$this->colPDT = array();		
    } 
    function FormatNumCmd(){
        $numCMD = trim(str_replace("#", "", $this->Numero));
        $numCMD = sprintf ("%04s\n",  $numCMD);
        return $numCMD;
    }    
	function AjoutPDT($unPDT){
		array_push($this->colPDT,$unPDT);
    }     
	//function Affiche(&$isParPage){	
    function Affiche(&$gestionPage){
		$resultat = '';
		$nbPlanche = 0;
		if ($gestionPage->isPage){ // Pour le cartonnage
			$resultat .= '<div class="commande"  >';				
				$resultat .= '<button  class="Titrecommande" onclick="VisuCMD(\''.$this->Numero . '\');" > Commande <span class="grosNumCMD">' . $this->FormatNumCmd() . '</span> ' . $this->NumFacture . ' (' . $this->Prenom . ' ' . $this->Nom . ', ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .')</button>';
				//Le contenu ...
				$resultat .= '<div id="'. $this->Numero .'" class="Contenucommande">';
				
					for($i = 0; $i < count($this->colPDT); $i++){
						$resultat .= $this->colPDT[$i]->Affiche();
						$nbPlanche = $nbPlanche + count($this->colPDT[$i]->colPlanche);
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
		else{		 // Pour la recherche
			$resultat .= '<div id="C-'. $this->Numero .'" class="commande"  >';			
				$resultat .= '<button  class="TitrecommandeRecherche"> Commande <span class="grosNumCMD"> ' . $this->FormatNumCmd() . '</span> ' . $this->NumFacture . ' (' . $this->Prenom . ' ' . $this->Nom . ', ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .')</button>';
				//Le contenu ...
				$resultat .= '<div class="Contenucommande">';
				for($i = 0; $i < count($this->colPDT); $i++){
					$resultat .= $this->colPDT[$i]->Affiche();			
				}
				$resultat .= '</div>';
			$resultat .= '</div>';
		
			
		}
		return $resultat;				
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
		$TailleInfo = count($morceau);

        $this->Classe = $morceau[0]; 
		if ($TailleInfo > 1){$this->Nom = $morceau[1];} 
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
		//echo '<br>' . $this->FichierPlanche ;
		if (substr($str,0,1) == 'P'){
			$this->FichierPlanche = $str;		
			//NEW UTF-8 $morceau = explode(".", utf8_encode($this->FichierPlanche));
			$morceau = explode(".", $this->FichierPlanche);		
			$this->IndexOrdre = $morceau[0];
			$this->FichierSource = $morceau[1]. '.jpg';
			$this->Type = $morceau[2];
			$this->Taille = $morceau[3]; 
		}
    }   	
	function Affiche(){
		$resultat = '';
		$resultat .= '<span onclick="SelectionPhoto(this)" id="'. urldecode($this->FichierPlanche) . '" class="Planche">';
			global $repertoireTirages;
			global $repertoireMiniatures;
			global $EcoleEnCours;
			
			$valideNomPlanche = str_replace("#", "%23", $this->FichierPlanche);
			$Lien = $repertoireMiniatures . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;
			$LienBig = $repertoireTirages . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;				
			if (!file_exists($LienBig)){$LienBig = $Lien;}
			
			//$resultat .= '<a href="CMDAffichePlanche.php?urlImage=' . $LienBig . '"><img id="myImgPlanche" src="' . $Lien . '"  title="'. urldecode($this->FichierPlanche) . '"></a>';	
			$resultat .= '<img id="ImgPlanche" src="' . $Lien . '"  title="'. urldecode($this->FichierPlanche) . '">';	
			//$resultat .= '<div class="overlay">My Name is John</div>';
			
			$resultat .= '<p>'. $this->FichierPlanche .'</p>';
		$resultat .= '</span> ';
		return $resultat;
	}	
}


function RetourEcranFichier($myfileName){
	$Etat = substr($myfileName, -1);
	$RetourEcran = 'CATPhotolab.php';	
    if ($Etat > 4){
		$RetourEcran = 'CATHistorique.php';
	}
	return $RetourEcran . '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') ;
}	


function FormatNumCmd($strCMD){
	//Ajoute toute la ligne commande / client A changer pour juste numéro!
	$numCMD = trim(str_replace("#", "", $strCMD));
	$numCMD = sprintf ("%04s\n",  $numCMD);
	return $numCMD;
}		

function VersionFichierLab($tabFICHIERLabo){
	$Info = $tabFICHIERLabo[0];
	//NEW UTF-8 return utf8_encode($Info);
	return $Info;
}

function EtatFichierLab($tabFICHIERLabo){
    $Info = $tabFICHIERLabo[1];
	//NEW UTF-8 return utf8_encode($Info);
	return $Info;
}

function AfficheEtatFichierLab($myfileName){
	$Etat = substr(strrchr($myfileName, '.'),4);
	$retourMSG ='';
    switch ($Etat) {
	case "1":
		$retourMSG = "Les planches de cette commande sont en création.";
		break;		
	case "2":
		$retourMSG = "Les planches de cette commande sont prêtes pour envoi au labo";
		break;
	case "3":  //. substr($myfileName,0,10)    strftime(" in French %A and",); $date = strftime("%d %B %Y", strtotime($date1));
		//$retourMSG = "Les planches ont été envoyé au labo le " .date('l d B',strtotime(substr($myfileName,0,10)) ).    "."; 
		$retourMSG = "Les planches de cette commandes ont été envoyé au labo " .strftime("%A %d %B", strtotime(substr($myfileName,0,10)) ).    "."; 
		break;		
	case "4":
		$retourMSG = "Les planches de cette commande sont en cours d'empaquetage.";
		break;
	default :	
		$retourMSG = "Les commandes sont expédiées.";
		break;
	}

	return $retourMSG;
}

function LienLocal($Commande){
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') . '&nbCmd=' . ($GLOBALS['NbCMDAffiche']?$GLOBALS['NbCMDAffiche']:1);
	$Environnement = $Environnement . $Commande;
	$LienFichier = htmlspecialchars($_SERVER['PHP_SELF']) . $Environnement;
	//echo $LienFichier;
	return $LienFichier;
}

function LienMEGA($tabCMDLabo,$numero_CMD_courante){
	$myfileName = urlencode($GLOBALS['myfileName']);
	$resultat = '';
	$isCommandeAffiche = 0;
	for($i = 1; $i <= count($tabCMDLabo); $i++){
		if ($i == $numero_CMD_courante){ $isCommandeAffiche = 1;}
		else {
			if ($isCommandeAffiche && $isCommandeAffiche < $GLOBALS['NbCMDAffiche']){ $isCommandeAffiche++;}
			else { $isCommandeAffiche = 0;}
		}
		//if ($isCommandeAffiche > 0) {
		if ($isCommandeAffiche) {
				$resultat .= '&nbsp;<a href="'. LienLocal('&fichierLAB='.$myfileName.'& numeroCMD='. $i ) .'
			" title="Consulter la commande '. $tabCMDLabo[$i -1]->FormatNumCmd() . '" class="active">'     
			. $tabCMDLabo[$i -1]->FormatNumCmd() . '</a>&nbsp;';
			//$isCommandeAffiche = true;		
		}
		else {        
			$resultat .= '&nbsp;<a href="'. LienLocal('&fichierLAB='.$myfileName.'& numeroCMD='. $i ) .'
			" title="Consulter la commande '. $tabCMDLabo[$i -1]->FormatNumCmd() . '">' 
			. $tabCMDLabo[$i -1]->FormatNumCmd() . '</a>&nbsp;';
		}
	}	
	return $resultat;
}


function LienAffichePlusMoins($signe,$Commande){
	$NbCMD=$GLOBALS['NbCMDAffiche'];
	if ($signe == '-') {
		if ($GLOBALS['NbCMDAffiche']>1) {
			$NbCMD=$GLOBALS['NbCMDAffiche'] - 1;
			if ($GLOBALS['NbCMDAffiche']>=10000) { $NbCMD=$GLOBALS['DefautNbCMDAffiche'];};
		}
	} 
	if ($signe == '+') {
		$NbCMD=$GLOBALS['NbCMDAffiche'] + 1;
		if ($GLOBALS['NbCMDAffiche']>=10000) { $NbCMD=$GLOBALS['DefautNbCMDAffiche'];};
	} 
	if ($signe == 'Toutes') {
		$NbCMD=10000;
	} 	
	//
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') . '&nbCmd=' . $NbCMD;
	$Environnement = $Environnement . $Commande;
	$LienFichier = htmlspecialchars($_SERVER['PHP_SELF']) . $Environnement;
	//echo $LienFichier;
	return $LienFichier;
}

/////////////
/**/
function afficheTab($tabFICHIERLabo){
	$ligne = '<br><br><br><br><br><br><br>';
	if ($tabFICHIERLabo){
		$NB = count($tabFICHIERLabo);
		for($i = 0; $i < $NB; $i++){
			$ligne=$tabFICHIERLabo[$i];
			$ligne=str_replace("<", "", str_replace(">", "", $ligne));
			echo $ligne. "<br>";
		}
	}
}
?>