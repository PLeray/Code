<?php
/**/

ini_set('display_errors','on');
error_reporting(E_ALL);

$DateISOLEE = '2020-09-31';
/*class CCommande {
    var $Numero;
}
*/
class CCommande {
    var $Numero;
    var $CmdClient;
    var $NumFacture;
    var $Prenom;
    var $Nom;
    var $Adresse;
    var $CodePostal;
    var $Ville;
    
    function __construct($str){
        $this->CmdClient = utf8_encode($str);
		//$morceau = explode(".", $this->CmdClient);
        $morceau = explode("_", utf8_encode(str_replace("#", "", $str)));
					//echo $str . "   ...  ";
		$this->Numero = $morceau[0];
        $TailleInfo = count($morceau);

        if ($TailleInfo > 1){$this->NumFacture = $morceau[1];}         
        if ($TailleInfo > 2){$this->Prenom = $morceau[2];}  
        if ($TailleInfo > 3){$this->Nom = $morceau[3];}  
        if ($TailleInfo > 4){$this->Adresse = $morceau[4];}  
        if ($TailleInfo > 5){$this->CodePostal = $morceau[5];}  
        if ($TailleInfo > 6){$this->Ville = $morceau[6];}         
    } 
    function FormatNumCmd(){
        $numCMD = trim(str_replace("#", "", $this->Numero));
        $numCMD = sprintf ("%04s\n",  $numCMD);
        return $numCMD;
    }    
}

class CEcole {
    var $DateTirage;
    var $Nom;
    var $Details;

    function __construct($str){
        $morceau = explode("_", utf8_encode(str_replace("@", "", $str)));
		$this->DateTirage = $morceau[0];
        $this->Nom = $morceau[1];
        $this->Details = $morceau[2];
    }
    function RepTirage(){
		if (stripos($this->Nom, '(ISOLEES)') !== false) { // C'est des ISOLEES
			return $GLOBALS['DateISOLEE'] . '-CMD-ISOLEES' ;
		}	
		else{
			return $this->DateTirage . '-' .$this->Nom ;	
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
		//echo '<br>' . $this->FichierPlanche ;
		if (substr($str,0,1) == 'P'){
			$this->FichierPlanche = $str;		
			$morceau = explode(".", utf8_encode($this->FichierPlanche));		
			$this->IndexOrdre = $morceau[0];
			$this->FichierSource = $morceau[1]. '.jpg';
			$this->Type = $morceau[2];
			$this->Taille = $morceau[3]; 
		}
    }   
}

function LireFichierLab($myfileName){
 	$tabFICHIERLabo = array();
	$myfile = fopen($myfileName, "r") or die('Unable to open file : ' .$myfileName);
	// Output one line until end-of-file
	while(!feof($myfile)) {
		array_push($tabFICHIERLabo,trim(fgets($myfile)));
        //array_push($tabFICHIERLabo,fgets($myfile));
	}
	fclose($myfile);	
	$GLOBALS['DateISOLEE'] = substr($myfileName, strripos($myfileName, '/') + 1,10);
	//afficheTab($tabFICHIERLabo);
	return $tabFICHIERLabo;
}

function RetourEcranFichier($myfileName){
	$Etat = substr($myfileName, -1);
	$RetourEcran = 'CATPhotolab.php';	
    if ($Etat > 4){
		$RetourEcran = 'CATHistorique.php';
	}
	return $RetourEcran . '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') ;
}	


function InitTabCMDLabo($tabFICHIERLabo){
	$tabCMDLabo = array();
	if ($tabFICHIERLabo){
		for($i = 0; $i < count($tabFICHIERLabo); $i++){
			$identifiant = substr($tabFICHIERLabo[$i],0,1);
			//Si Commande pas vide , on ajoute la commande au tableau!
			if (($identifiant == '#') 
                && (substr($tabFICHIERLabo[$i+1],0,1)!='#') 
                && (substr($tabFICHIERLabo[$i+1],0,1)!='@') 
                && (substr($tabFICHIERLabo[$i+1],0,1)!='')
               ){
				//array_push($tabCMDLabo,FormatNumCmd($tabFICHIERLabo[$i]));
                $curCMD = new CCommande($tabFICHIERLabo[$i]);
                array_push($tabCMDLabo,$curCMD);
			}
		}
	}
	//afficheTab($tabCMDLabo);
	return $tabCMDLabo;
}

function FormatNumCmd($strCMD){
	//Ajoute toute la ligne commande / client A changer pour juste numéro!
	$numCMD = trim(str_replace("#", "", $strCMD));
	$numCMD = sprintf ("%04s\n",  $numCMD);
	return $numCMD;
}		

function VersionFichierLab($tabFICHIERLabo){
	$Info = $tabFICHIERLabo[0];
	return utf8_encode($Info);
}

function EtatFichierLab($tabFICHIERLabo){
    $Info = $tabFICHIERLabo[1];
	return utf8_encode($Info);
}

function AfficheEtatFichierLab($Etat){
	$retourMSG ='';
    switch ($Etat) {
	case "1":
		$retourMSG = "Les planches sont crées.";
		break;		
	case "2":
		$retourMSG = "Les planches sont envoyées labo";
		break;
	case "3":
		$retourMSG = "Les planches sont tirées au labo";
		break;		
	case "4":
		$retourMSG = "Les planches sont Cartonnées";
		break;	
	}
	return $retourMSG;
}

function AffichageCMD($tabFICHIERLabo, $numCMD, &$curEcole, $NbCMDAffiche){
	//echo  $numCMD . " nb : " . $NbCMDAffiche;
	$curCMD = $GLOBALS['tabCMDLabo'][$numCMD];
	$curseur = 2;
	$resultat = '';
	$CodeAfficheEcole = $curEcole->Details;
	$isAfficheEcole = true;
	for($nbCmd = 0; $nbCmd < $NbCMDAffiche; $nbCmd++){				
		if (count($GLOBALS['tabCMDLabo']) > $numCMD + $nbCmd){
			$curCMD = $GLOBALS['tabCMDLabo'][$numCMD + $nbCmd];	
			if ($tabFICHIERLabo){
				for($i = 2; $i < count($tabFICHIERLabo); $i++){
					$identifiant = substr($tabFICHIERLabo[$i],0,1);
					//Si Commande pas vide , on ajoute la commande au tableau!
					if ($identifiant == '@'){
						$curEcole = new CEcole($tabFICHIERLabo[$i]);
						//echo 'CodeAfficheEcole : ' . $CodeAfficheEcole . '    curEcole->Details  ' . $curEcole->Details . '<br>';
					}
					//if ($identifiant == '#') { 					
					if ($identifiant == '#'){ // Pierre Probleme Doublon	
						$laCMD = new CCommande($tabFICHIERLabo[$i]);
						if ($laCMD->Numero == $curCMD->Numero){
							//Ici verifier que la ligne suivante est bien un produit
							$identifiantSuivant = substr($tabFICHIERLabo[$i + 1],0,1);
							//if ($identifiantSuivant == "<"){
				            $curseur = $i;

						if ($CodeAfficheEcole != $curEcole->Details){
							$isAfficheEcole = true;
							//$CodeAfficheEcole = $curEcole->Details;
							}	
                            break;
                            return $i;						
							//}						
						}
					}
				}
				$curseur++;
				if ($curseur < count($tabFICHIERLabo)){
					$identifiant = substr($tabFICHIERLabo[$curseur],0,1);						
									
					if ($isAfficheEcole) {
						$resultat .= '<div class="ecole">';	
						//$resultat .= ' <h1>' .$curEcole->Nom . '</h1>';
						$resultat .= $curEcole->Nom;
						$CodeAfficheEcole = $curEcole->Details;
						$isAfficheEcole = false;
						$resultat .= '</div>';
					}
					$resultat .= '<div class="commande"  >';				
					$resultat .= '<button  class="Titrecommande" onclick="VisuCMD(\''.$curCMD->Numero . '\');" > Commande ' . $curCMD->FormatNumCmd() . ' ' . $curCMD->NumFacture . ' (' . $curCMD->Prenom . ' ' . $curCMD->Nom .')</button>';				
						$resultat .= '<div id="'. $curCMD->Numero .'" class="Contenucommande"  >';
						while(($identifiant != '@') && ($identifiant != '#') && ($identifiant != '')) { // tant qu'on est sur la meme commande
							$resultat .= AffichageProduit($tabFICHIERLabo, $curseur);
							$identifiant = substr($tabFICHIERLabo[$curseur],0,1); 						
						}
						$resultat .= '</div>';
					$resultat .= '</div>';
				}
			}	
		} 
	}
	return $resultat;
}

function AffichageProduit($tabFICHIERLabo, &$curseur){	
	$resultat = '';
	//echo '(curseur <' .$curseur . '< count($tabFICHIERLabo))' . count($tabFICHIERLabo);
	if ($curseur < count($tabFICHIERLabo)){
		$identifiant = substr($tabFICHIERLabo[$curseur],0,1);
		if ($identifiant == "<") {
			//$resultat = '<div class="produit">'; //Debut du produit
			$resultat = '<span class="produit">'; //Debut du produit
			$NomProduit = utf8_encode(str_replace("<", "", str_replace(">", "", $tabFICHIERLabo[$curseur])));
			$NomProduit = str_replace("%", "<br>", $NomProduit);
			$curseur++;
			$identifiant = substr($tabFICHIERLabo[$curseur],0,1);
			while(($identifiant != '@') && ($identifiant != '#') && ($identifiant != '<') && ($identifiant != '')) { 
				//$resultat = '<span class="produit">'; //Debut du produit
				$resultat .= '<h4>'.$NomProduit.'</h4><br>'  ;
				// tant qu'on est sur le meme Produit
					//echo 'tabFICHIERLabo[curseur]  : ' . $tabFICHIERLabo[$curseur];
					$resultat .= AffichagePlanche($tabFICHIERLabo, $curseur);
					
								
					
					
					
					$identifiant = substr($tabFICHIERLabo[$curseur],0,1);
					//$resultat .= '</span>';
			}
			//$resultat .= '</div>';		
			$resultat .= '</span>';
			//$resultat .= '<h3>'. substr($NomProduit,0,18) .'</h3>  </div>';

		}
	}
	return $resultat;
}

function AffichagePlanche($tabFICHIERLabo, &$curseur){
	$resultat = '';    
	if ($curseur < count($tabFICHIERLabo)){
        $resultat .= '<span class="planche">';
		$identifiant = substr($tabFICHIERLabo[$curseur],0,1);
		if (($identifiant != '@') && ($identifiant != '#') && ($identifiant != '<') && ($identifiant != '')) {
			//$resultat .= '<img src="' . LienJPG($tabFICHIERLabo[$curseur]) . '" title="'. urldecode($tabFICHIERLabo[$curseur]) . '">';// . '">&nbsp;';
			//$resultat = ;$resultat .= ;	
			
			//$resultat .= '<div id="plancheIMG">' . LienJPG($tabFICHIERLabo[$curseur]) . '</div>';
			$resultat .=  LienJPG($tabFICHIERLabo[$curseur]) ;
			//$resultat .= TitrePhotoJPG($tabFICHIERLabo[$curseur]);
			$resultat .= '<p>'.TitrePhotoJPG($tabFICHIERLabo[$curseur]).'</p>';
			//$resultat .= '<br>'.TitrePhotoJPG($tabFICHIERLabo[$curseur]).'<br>';
			$curseur++;
		}
        $resultat .= '</span> ';
	}
	return $resultat;
}

function LienJPG($filename){
	$Lien = '';

	switch (trim($filename)) {
		case "CADRE-PANO":
			$Lien = 'img/CadreBoisVide.jpg';
			$LienBig = 'img/CadreBoisVide.jpg';			
			break;
		case "TAPIS-SOURIS":
			$Lien = 'img/TapisSouris.jpg';
			$LienBig = 'img/TapisSouris.jpg';			
			break;
		default:
			global $repertoireTirages;
			global $repertoireMiniatures;
			global $curEcole;
			$maPlanche = new CPlanche($filename);			
			//$cheminIMG = $repertoireTirages . $curEcole->RepTirage(). '/' . $maPlanche->Taille . ' (1ex de chaque)'. '/' ; 		
			// Juste pour le # !!! ...
			$valideNomPlanche = str_replace("#", "%23", $maPlanche->FichierPlanche);
			$Lien = $repertoireMiniatures . $curEcole->RepTirage(). '/' . $maPlanche->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;
			
			$LienBig = $repertoireTirages . $curEcole->RepTirage(). '/' . $maPlanche->Taille . ' (1ex de chaque)'. '/'  . $valideNomPlanche;				
			break;
	}		
	if (!file_exists($LienBig)){
		$LienBig = $Lien;
	}
	//$ImageLien = '<a href="CMDAffichePlanche.php?urlImage=' . $LienBig . '"><img  id="myImgPlanche" src="' . $Lien . '"  title="'. urldecode($filename) . '"></a>';
	$ImageLien = '<a href="CMDAffichePlanche.php?urlImage=' . $LienBig . '"><img src="' . $Lien . '"  title="'. urldecode($filename) . '"></a>';	

	return $ImageLien;
}

function TitrePhotoJPG($filename){
	$Titre = '';
	/*if (trim($filename) == "CADRE-PANO"){ $Titre = 'Cadre Bois';}
	else {
		global $curEcole;
		$maPlanche = new CPlanche($filename); 
		$Titre = $maPlanche->FichierSource;
	}
    return $Titre;
	*/
	switch (trim($filename)) {
		case "CADRE-PANO":
			$Titre = 'Cadre Bois';
			break;
		case "TAPIS-SOURIS":
			$Titre = 'Tapis de Souris';
			break;
		default:
			global $curEcole;
			$maPlanche = new CPlanche($filename); 
			$Titre = $maPlanche->FichierSource;
			break;
		
	}	
    return $Titre;			
}

function PaginatorCMD($tabCMDLabo, $nb_results_p_page, $numero_CMD_courante, $nb_avant, $nb_apres)//, $premiere, $derniere)
{
	$resultat = '<div class="pagination">';
	$nb_results = count($tabCMDLabo);
	//echo "$nb_results ". $GLOBALS['NbCMDAffiche'];
    //global $myfileName;
	if 	($nb_results > $GLOBALS['NbCMDAffiche']){
		$myfileName = urlencode($GLOBALS['myfileName']);

		// Initialisation de la variable a retourner


		// nombre total de pages
		$nb_CMD = ceil($nb_results / $nb_results_p_page);
		// nombre de pages avant
		//$avant = $numero_CMD_courante > ($nb_avant + 1) ? $nb_avant : $numero_CMD_courante - 1;
		// nombre de pages apres
		$apres = $numero_CMD_courante <= $nb_CMD - $nb_apres ? $nb_apres : $nb_CMD - $numero_CMD_courante;

		// premiere page
		if ($numero_CMD_courante  > 1){	
			$resultat .= '<a href="'. LienLocal('&fichierLAB='.$myfileName.'& numeroCMD=1') .'" title="Premiere(s) commande(s) : '. $tabCMDLabo[0]->FormatNumCmd() . '" class="buton">|<</a>&nbsp;';
		}
		else { $resultat .= '<a class="disabled">|<</a>&nbsp;';}		

		// page precedente
		if ($numero_CMD_courante > $GLOBALS['NbCMDAffiche']){ 
			$resultat .= '<a href="'. LienLocal('&fichierLAB='.$myfileName.'& numeroCMD='. ($numero_CMD_courante - $GLOBALS['NbCMDAffiche'])) .'" title="Commande(s) precedente(s) : '. $tabCMDLabo[$numero_CMD_courante - $GLOBALS['NbCMDAffiche'] - 1]->FormatNumCmd() . '" class="buton"><</a>&nbsp;';
		}
		else { $resultat .= '<a class="disabled"><</a>&nbsp;';}		
		// affichage des numeros de page
		$resultat .= '	';
		for ($i = $numero_CMD_courante;  $i < $numero_CMD_courante + $GLOBALS['NbCMDAffiche']; $i++)    {
			// pages courantes
			if ($i <= $nb_results){
				$resultat .= '<a href="#" class="active">' . $tabCMDLabo[$i -1]->FormatNumCmd() . '</a>';		
			}
		}
		// page suivante
		$resultat .= '	';
		if ($numero_CMD_courante < $nb_CMD - $GLOBALS['NbCMDAffiche']){	
			$resultat .= '<a href="'. LienLocal('&fichierLAB='.$myfileName.'& numeroCMD='. ($numero_CMD_courante + $GLOBALS['NbCMDAffiche'])) .'" title="Consulter la(s) commande(s) suivante(s) : '. $tabCMDLabo[$numero_CMD_courante + $GLOBALS['NbCMDAffiche']-1]->FormatNumCmd() . '" class="buton">></a>&nbsp;';
		}
		else { $resultat .= '<a class="disabled">></a>&nbsp;';}	
		// derniere page
		if (($numero_CMD_courante ) < $nb_CMD- $GLOBALS['NbCMDAffiche']){
			$resultat .= '<a href="'. LienLocal('&fichierLAB='.$myfileName.'& numeroCMD='. (1+$nb_CMD-$GLOBALS['NbCMDAffiche'])) .'" title="Derniere(s) commande(s) : '. $tabCMDLabo[$nb_CMD-$GLOBALS['NbCMDAffiche']]->FormatNumCmd() . '" class="buton">>|</a>&nbsp;';
		}
		else { $resultat .= '<a class="disabled">>|</a>&nbsp;';}
		
		
	}
	

    $resultat .= '</div>'  ;  
    // On retourne le resultat
    return utf8_encode($resultat);
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
		}
	} 
	if ($signe == '+') {
		$NbCMD=$GLOBALS['NbCMDAffiche'] + 1;
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