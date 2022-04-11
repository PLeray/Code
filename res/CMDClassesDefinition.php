<?php
$SeparateurInfoPlanche = '§';
$SeparateurInfoPlancheLab0 = '_';
$DateISOLEE = '2020-09-31';
$FichierDossierRECOMMANDE = "9999-99-99-(RECOMMANDES)-EN-COURS";
$FichierDossierCMDESLIBRE = "8888-88-88-(COMMANDES LIBRES)-EN-COURS";

class CGroupeCmdes {
    var $ListePromoteurs;
    var $ListeCommandes;
    var $nomFichierCmdes;	
	var $tabFICHIERLabo;
	var $tabCMDLabo;  
	var $DateISOLEE;
	var $colEColes;
	var $DossierTirage;	
	
    function __construct($myfileName){
		$this->tabFICHIERLabo = $myfileName;
		$myfile = fopen($myfileName, "r") or die('Unable to open file : ' .$myfileName);
		$this->tabFICHIERLabo = array();
		// Output one line until end-of-file
		
		//$GLOBALS['DateISOLEE'] = substr($myfileName, strripos($myfileName, '/') + 1,10);
				//echo '$myfileName : '. substr($myfileName, -1);
		while(!feof($myfile)) {
			array_push($this->tabFICHIERLabo,trim(fgets($myfile)));
		}
		fclose($myfile);	

		$this->DateISOLEE = substr($myfileName, strripos($myfileName, '/') + 1,10);		
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
						if (stripos($this->tabFICHIERLabo[$i], '(ISOLEES)') !== false) { // C'est des ISOLEES
							$this->DossierTirage =  substr($myfileName, strripos($myfileName, '/') + 1,-5);	
							//echo '$this->DossierTirage : ' .$this->DossierTirage;
							if (!file_exists($GLOBALS['repMINIATURES'].$this->DossierTirage)) {
								if (file_exists($GLOBALS['repMINIATURES'].$this->DateISOLEE . '-CMD-ISOLEES')) {
									$this->DossierTirage = $this->DateISOLEE . '-CMD-ISOLEES' ;
								} 								
							} 
							
						}	

						elseif (stripos($this->tabFICHIERLabo[$i], '(RECOMMANDES)') !== false) { // C'est des RECOs
							$this->DossierTirage =  substr($myfileName, strripos($myfileName, '/') + 1,-5);	

						}	
			
						else{ // C'est des PAS des ISOLEES
							$curEcole = new CEcole($this->tabFICHIERLabo[$i], '');							
							$this->DossierTirage =  substr($myfileName, strripos($myfileName, '/') + 1,-5);	
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
				elseif (substr($myfileName, -1) == '0') {
					if(strlen($this->tabFICHIERLabo[$i]) > 7) {
						$curPlanche = new CPlanche($this->tabFICHIERLabo[$i]);
						$curProduit->AjoutPlanche($curPlanche);
					}				
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
	function AffichePlancheAProduire(){
		$resultat = '<table class="TablePlanche"><tr>
			<td  width="40%" class ="StyleFichier">FichierSource</td>
			<td  width="20%" class ="StyleTaille">Taille</td>
			<td  width="40%" class ="StyleProduit">Produit</td>
		</tr></table>';
		//echo 'Affiche ecole Affiche : ' . count($this->colEColes);
		for($i = 0; $i < count($this->colEColes); $i++){
			global $EcoleEnCours;
			$EcoleEnCours = $this->colEColes[$i];
			$resultat .= $this->colEColes[$i]->AffichePlancheAProduire();			
		}

		return $resultat;
	}	
	function AfficheCommandesAProduire(){
		$resultat = '';
		//echo 'Affiche ecole Affiche : ' . count($this->colEColes);
		for($i = 0; $i < count($this->colEColes); $i++){
			global $EcoleEnCours;
			$EcoleEnCours = $this->colEColes[$i];
			$resultat .= $this->colEColes[$i]->AfficheCommandesAProduire();			
		}

		return $resultat;
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
	var $AnneeScolaire;
    var $Details;
	var $colCMD;
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
		$this->colCMD = array();
		//$this->DateISOLEE = $dateIsole;
		$this->DossierTirage = $DossierTirage;
    }

    function RepTirage(){
		return $this->DossierTirage;		
    }   
	function AjoutCMD($uneCMD){
		array_push($this->colCMD,$uneCMD);
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
		for($i = 0; $i < count($this->colCMD); $i++){
			//$resultat .= $gestionPage->AfficheDebutPage($this->Nom);
			$resultat .= $gestionPage->AfficheDebutPage();
			$resultat .= $this->colCMD[$i]->Affiche($gestionPage);	
			$resultat .= $gestionPage->AfficheFinPage();
		}
		//$resultat .= '</div>';	/////////////////////////////		
		return $resultat;
	}	
    function AfficheCommandesAProduire(){
		//$isParPage = ($numeroPage>0);
		$resultat = '';

		/**/$resultat .= '<div class="StyleEcole">';	
		$resultat .= $this->Nom ;  
		$resultat .= '</div>';				
		$resultat .= '<table class="TableCommandes">';	
		for($i = 0; $i < count($this->colCMD); $i++){
			$resultat .= $this->colCMD[$i]->AfficheCommandesAProduire();	
		}	
		$resultat .= '</table>';
	
		return $resultat;
	}	
	function AffichePlancheAProduire(){
		//$isParPage = ($numeroPage>0);
		$resultat = '';

		/**/$resultat .= '<div class="StyleEcole">';	
		$resultat .= $this->Nom ;  
		$resultat .= '</div>';				
		$resultat .= '<table class="TablePlanche">';	
		for($i = 0; $i < count($this->colCMD); $i++){
			$resultat .= $this->colCMD[$i]->AffichePlancheAProduire();	
		}	
		$resultat .= '</table>';
	
		return $resultat;
	}

//utf8_encode(strftime('%A %d %B, %H:%M', strtotime($this->DateTirage)));

	function Ecrire($tabPlanche, &$isRecommande){	

		$strDate = MarqueurDateCommande('2022-03-04');

		//$date1 = date($this->DateTirage); // Date du jour
		//setlocale(LC_TIME, "fr_FR");
		//$strDate = strftime("%A %d %B %G", strtotime($date1)); //Mercredi 26 octobre 2016

		//$date1 = date($strDtae); // Date du jour

		//$strDate = strftime("%A %d %B %G", strtotime($date1)); //Mercredi 26 octobre 2016	
		

		//$strDate = MarqueurDateCommande($this->DateTirage);
		$resultat ='@9999-99-99'.  '_' . $strDate .' (RECOMMANDES) ' . $this->Nom . '_' . $this->CodeEcole . '_' . $this->AnneeScolaire . '_' . $this->Details.'@'.PHP_EOL; 
		//@2020-12-03_(ISOLEES) Elementaire La Chateigneraie-HAUTE GOULAINE_ECOLE-1017_Ecole web !@ 
		for($i = 0; $i < count($this->colCMD); $i++){
			$isEcris = false;
			$resultat .= $this->colCMD[$i]->Ecrire($tabPlanche, $isEcris);		
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
		else{ // Pour la recherche
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
	function AfficheCommandesAProduire(){
		$resultat = '';
		//for($i = 0; $i < count($this->colPDT); $i++){
			$resultat .= '<tr>
			<td width="15%" class ="StyleNumCommande">'. $this->FormatNumCmd() . '</td>
			<td width="85%" class ="StyleInfoClient"><b>' . $this->Prenom . ' ' . $this->Nom . '</b>, ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .'</td>
			</tr>';
;			
		//}
//' . $this->FormatNumCmd() . '</span> ' . $this->NumFacture . ' (' . $this->Prenom . ' ' . $this->Nom . ', ' . $this->Adresse . ', ' . $this->CodePostal .' ' . $this->Ville .')
		return $resultat;		

	}	
	function AffichePlancheAProduire(){
		$resultat = '';

		for($i = 0; $i < count($this->colPDT); $i++){
			$resultat .= $this->colPDT[$i]->AffichePlancheAProduire();			
		}

		return $resultat;				
	}

	function Ecrire($tabPlanche, &$isRecommande){
		$resultat ='#'. $this->Numero . '_' . $this->NumFacture . '_' . $this->Prenom . '_' . $this->Nom . '_' . $this->Adresse . '_' . $this->CodePostal .'_' . $this->Ville .'#'. PHP_EOL; 
	
		for($i = 0; $i < count($this->colPDT); $i++){
			$isEcris = false;
			$resultat .= $this->colPDT[$i]->Ecrire($tabPlanche, $isEcris);	
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
			
			$morceau = explode($GLOBALS['SeparateurInfoPlanche'], $this->FichierPlanche);
			//echo 'sdgsdgfd ' . count($morceau)	;
			if (count($morceau)< 2) {
				$morceau = explode(".", $this->FichierPlanche); // Comme avant
			}
			$this->IndexOrdre = $morceau[0];
			$this->FichierSource = $morceau[1]. '.jpg';
			$this->Type = $morceau[2];
			$this->Taille = $morceau[3]; 
		}else{
			//echo "<br>XX SeparateurInfoPlancheLab0 : " . $GLOBALS['SeparateurInfoPlancheLab0']. "  <br>// FichierPlanche " .  $this->FichierPlanche	;
			$morceau = explode($GLOBALS['SeparateurInfoPlancheLab0'], $this->FichierPlanche);
			
			$this->FichierSource = $morceau[0];
			$this->Taille = $morceau[1];
			$this->Type = $morceau[2];
			//var_dump($morceau) ;
		}
    }   	
	function Affiche(){
		$resultat = '';
		$resultat .= '<span onclick="SelectionnerCliquePhoto(this)" id="'. urldecode($this->FichierPlanche) . '" class="planche" title="'. urldecode($this->FichierPlanche) . '">';

			global $EcoleEnCours;
			//echo $GLOBALS['repTIRAGES'] . '<br>';
			//echo $GLOBALS['repMINIATURES']. '<br>';

			
			$valideNomPlanche = str_replace("#", "%23", $this->FichierPlanche);
			$Lien = $GLOBALS['repMINIATURES'] . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;
			$LienBig = $GLOBALS['repTIRAGES'] . $EcoleEnCours->RepTirage(). '/' . $this->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;				
			if (!file_exists($LienBig)){$LienBig = $Lien;}
			
			//$resultat .= '<a href="CMDAffichePlanche.php?urlImage=' . $LienBig . '"><img id="myImgPlanche" src="' . $Lien . '"  title="'. urldecode($this->FichierPlanche) . '"></a>';	
			$resultat .= '<img id="ImgPlanche" src="' . $Lien . '">';	

			
			$resultat .= '<p>'. $this->FichierPlanche .'</p>';
			
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
		/*
		$isRecommande = in_array($this->FichierPlanche, $tabPlanche);
		if ($isRecommande) {
			return $this->FichierPlanche;			
		}	
		else{
			return '';			
		}	*/
		return $resultat;				
	}
	function Ecrire($tabPlanche, &$isRecommande){
		$resultat ='';
	   if (in_array($this->FichierPlanche, $tabPlanche)) {
		   $isRecommande = in_array($this->FichierPlanche, $tabPlanche);
		   //$resultat = $this->FichierPlanche . PHP_EOL;
		   //fputs($Fichier, $lines[$i]);
		   //(RECOMMANDES) EN COURS
		   $resultat = $this->FichierSource;
		   $resultat .= '_'. $this->Taille; 
		   $resultat .= '_'. $this->Type;
		   $resultat .= PHP_EOL;
		   ///////////////////////////////////////////////////
				
		}
		return $resultat;					
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

class CProjetSource {
	//var $FichierPlanche;
	//var $Fichier;
	var $NomProjet;
	var $Dossier;
	var $CodeEcole;
	var $AnneeScolaire;
    var $ScriptsPS;
	
    function __construct($NomProjet,$Dossier,$CodeEcole,$AnneeScolaire,$ScriptsPS){
			$this->NomProjet = $NomProjet;
			$this->Dossier = $Dossier;
			$this->CodeEcole = $CodeEcole;
			$this->AnneeScolaire = $AnneeScolaire;
			$this->ScriptsPS = $ScriptsPS;
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
			/* 	
			$resultat .= '   
			<span oncontextmenu="return monMenuContextuel(this)"
			  onclick="CopierCommandes(this)" 
			  id="'. urldecode($this->Fichier) . '" 
			  Nb="0" 
			  class="'.($this->isGroupe()?'PlancheGroupe':'PlancheIndiv') .'">';	
		  */					  
		$resultat .= '   
			<span  onclick="CopierCommandes(this)" 
			id="'. urldecode($this->Fichier) . '" 
			Nb="0" 
			class="'.($this->isGroupe()?'PlancheGroupe':'PlancheIndiv') .'">';	

			$resultat .= '<button class="EnregistrerPhoto" onclick="SelectionnerCliquePhoto(this.parentElement)" >
			<img id="'. ($this->isGroupe()?'ImgPlancheGroupe':'ImgPlancheIndiv') .'" 
				src="' . $Lien . '"  title="'. urldecode($this->Fichier) . '">
			</button>';

	/*
			$Argument = '&codeSource=' . urlencode($this->CodeEcole). '&anneeSource=' . urlencode($this->AnneeScolaire). '&urlImage=' . $LienBig;
			
			$resultat .= '<form name="VoirEnGrand" method="post" action="CMDAffichePlanche.php'.ArgumentURL($Argument).'" enctype="multipart/form-data">	
			<input type="hidden" name="lesPhotoSelection" id="ZlesPhotoSelection" value="0" /> 
			<input type="hidden" name="lesCmdesLibres" id="ZlesCmdesLibres" value="0" /> 
			<input type="hidden" name="lesFichiersBoutique" id="ZlesFichiersBoutique" value="0" /> ';

			$resultat .='<button type="submit" class="NomPhotoZoom">
			<p>'. substr($this->Fichier, 0, -4)  .'</p>
			</button>';

			$resultat .= '</form>';
			*/
/*
	
			$resultat .= '<br>';
			$resultat .= '<form>';
			$resultat .='<span class="NomPhotoZoom" onclick="ZoomPhoto(\''. $LienBig  .'\')">
			'. substr($this->Fichier, 0, -4)  .'
			</span>';
			$resultat .= '</form>';
*/
			//$resultat .= '<form>';
			$resultat .= '<input type="hidden" name="lesPhotoSelection" id="ZlesPhotoSelection" value="0" /> 
			<input type="hidden" name="lesCmdesLibres" id="ZlesCmdesLibres" value="0" /> 
			<input type="hidden" name="lesFichiersBoutique" id="ZlesFichiersBoutique" value="0" /> ';
			
			$resultat .='<button class="NomPhotoZoom" onclick="ZoomPhoto(\''. $LienBig  .'\')">
			<p>'. $this->Fichier  .'</p>
			</button>';
			//$resultat .= '</form>';

			$resultat .= '<span class="ZoneMoinsPlus">';					
			$resultat .= '<span onclick="NbPlancheMOINS(this)"  class="moinsplus">-</span>
							<span class="NombrePhoto"> 0 </span>
							<span onclick="NbPlanchePLUS(this)"  class="moinsplus">+</span>';			
			$resultat .= '</span>';	

			$resultat .= ($this->isGroupe()?'<span>':'');			
			$resultat .= ($this->isGroupe()?'</span>':'');			
			
			//$resultat .= '<p>'. substr($this->Fichier, 0, -4)  .'</p>';

			$Argument = '&urlImage=' . $LienBig;
			$Argument .= '&codeSource=' . urlencode($this->CodeEcole). '&anneeSource=' . urlencode($this->AnneeScolaire);
			
			//$resultat .= '<p><a href="CMDAffichePlanche.php'.ArgumentURL($Argument) . '" class = "NomPhotoZoom">'. substr($this->Fichier, 0, -4)  .'</a></p>';	
			



			$resultat .= '<div class="ImageFichierWeb"></div>';		
			

		$resultat .= '</span> ';
		
		return $resultat;
	}
	/*
	function Ecrire($tabPlanche, &$isRecommande){
		 $resultat ='';
		if (in_array($this->FichierPlanche, $tabPlanche)) {
			$isRecommande = in_array($this->FichierPlanche, $tabPlanche);
			$resultat = $this->FichierPlanche . PHP_EOL;

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
		}

		return $resultat;				
	}
	*/		
}
class CNomFichierGroupe {
    var $Numero;
	var $TypeGroupe;	
	var $NomClasse;
	var $Version;

    function __construct($NomFichierGroupe){
		if (strpos($NomFichierGroupe,'-') > 1){
			$CodeFichierGroupe = substr($NomFichierGroupe,0, strripos($NomFichierGroupe,'.')); // enlever l'extension
			$morceau = explode('-', $CodeFichierGroupe);
			$this->Numero = $morceau[0];
			$this->TypeGroupe = $morceau[1];
			if (count($morceau) > 2){ $this->NomClasse = $morceau[2]; } 
			if (strpos($this->NomClasse,'@') > 1){ // Nom du groupe avec plusieurs version
				$morceauNomClasse = explode('@', $this->NomClasse);
				$this->NomClasse = $morceauNomClasse[0];
				$this->Version = $morceauNomClasse[1];				
			}
			if (strpos(strtolower($NomFichierGroupe),'fratrie')){
				$this->NomClasse = 'Fratries';
			}			
		}
	} 
	function IdentifiantClasse(){
		return $this->Numero.$this->NomClasse; 
	}
}

function csv_to_array($filename='', $delimiter=';')
{
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

function MarqueurDateCommande($strDate) {
	//date_default_timezone_set('Europe/Paris');
	setlocale(LC_TIME, 'fr_FR');
	
	$date = new DateTime($strDate);
	//echo $date->format('Y-m-d H:i:s');


	//$date1 = date($strDate); // Date du jour

	//return strftime("%A %d %B %G", strtotime($date1)); //Mercredi 26 octobre 2016	

	return $date->format('d/m/Y');
}

?>