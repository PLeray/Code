<?php
$VERSION = 0.838;
$ANNEE = '2021';

$repCMDLABO = "../../CMDLABO/";
$repMINIATURES = "../../CMDLABO/MINIATURES/";
$repTIRAGES = "../../TIRAGES/";
$repWEBARBO = "../../WEB-ARBO/";

// remis le 21/06/2021 ??
ini_set("auto_detect_line_endings", true); // Lecture MAC fin de ligne

////////////////////////////// APIConnexion //////////////////////////////////////////////
class CConnexionAPI {
	var $isDebug;
    var $codeMembre;
	var $Service;
    var $URL;
	var $Domaine;
	var $PageRetour;	
	
    function __construct($codeMembre ,$isDebug, $PageRetour){
        $this->isDebug = $isDebug;
        $this->codeMembre = $codeMembre;
        $this->PageRetour = $PageRetour;	
        $this->Service = '/res/LOGPhotoLab.php';		
		if ($isDebug){
            //$this->Service = '/res/LOGPhotoLab.php';
            $this->URL = 'http://localhost/API_photolab';  //"http://localhost/online/res/drop.php" 
            $this->Domaine = 'localhost:80';  
		}
        else {
            //$this->Service = '/res/' . $this->PageRetour . '.php';
            //$this->Service = '/res/LOGPhotoLab.php';
			$this->URL = 'https://photolab-site.fr'; 
            $this->Domaine = 'www.photolab-site.fr:80';  
			
        }
    }
    function Adresse($ParamGET = true){
		$cmd = '';
		if ($ParamGET){
			$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod'). '&pageRetour=' . $this->PageRetour . '&serveurRetour=' . urlencode(ServeurLocal());
		}
        return $this->URL . $this->Service . $cmd;
    } 
    function CallServeur($CMDLocal){
		$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod') . '&pageRetour=' . $this->PageRetour . '&serveurRetour=' . urlencode(ServeurLocal()) ;
        return $this->URL .'/res/talkServeur.php' . $cmd . $CMDLocal;
    } 	
}

function VersionPhotoLab(){
	return '©PhotoLab ' . $GLOBALS['ANNEE'] . ' (v'.$GLOBALS['VERSION'].') : Création et visualisation de commandes de photographies';
}

function ArgumentURL($ARGSupl = ''){
	return '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod') . $ARGSupl;
}

function MAJPhotoLab($NouvelleVersion) {
	if( $GLOBALS['VERSION'] < $NouvelleVersion){ 
		header('Location: MAJPhotoLab.php'. ArgumentURL('&version='. $NouvelleVersion));
	}
}


function ServeurLocal(){
	//$urlLocal = $_SERVER['HTTP_REFERER']; 	
	
	$urlLocal = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
	
	//echo '<br> urlencode : ' . $urlLocal; 	
	$PosCode = strripos($urlLocal,'/Code/');
	$urlLocal = substr($urlLocal, 0, $PosCode) ;
	//echo '<br> ServeurLocal : ' . $urlLocal;	
	return $urlLocal; 
}


function LienOuvrirDossierOS($Dossier,$depuisPage) {
	$LienFichier = '#';
	if ($Dossier != ''){
		$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
		$LienFichier = $depuisPage . '.php'. $Environnement . '&OpenRep=' . urlencode($Dossier);		
	}
	return $LienFichier;
}

function execInBackground($cmd) {
    
	if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
    }
    else {
        exec($cmd . " > /dev/null &");  
    }
/*	
 $last_line = system($cmd, $retval);

*/
	
}
function IsLocalMachine() {
	$isLocal = false;

  //echo 'L adresse IP de l utilisateur est : '.$_SERVER['REMOTE_ADDR'];
  //echo '<br>L adresse IP du serveur est : '.$_SERVER['SERVER_ADDR'];

	return ($_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR']) ;
}

function AfficheMenuPage($Page,$maConnexionAPI) {
$menuPage = '<center>
<div id="mySidenav" class="sidenav">';
	if ($Page != "ajoutCommandeGroupee") {$menuPage .= '<a href="index.php' . ArgumentURL().'" id="ajoutCommandeGroupee" title="Ajouter une Commande Groupée ..."></a>';}
	if ($Page != "sourcePhotos") {$menuPage .= '<a href="CATSources.php' . ArgumentURL().'" id="sourcePhotos" title="Sources des photos ..."></a>';}
	if ($Page != "commandesEnCours") {$menuPage .= '<a href="CATPhotolab.php' . ArgumentURL().'" id="commandesEnCours" title="Commandes en cours de préparation ..."></a>';}
	if ($Page != "commandesExpediees") {$menuPage .= '<a href="CATHistorique.php' . ArgumentURL().'" id="commandesExpediees" title="Historique des commandes expediées ..."></a>';}
	if ($Page != "administration") {$menuPage .= '<a href="' . $maConnexionAPI->Adresse().'" id="administration" title="Administration ..."></a>';}
   
	$menuPage .= '</div>
	</center>';

	echo $menuPage;
}

function Mini($Nom) {
	echo strMini($Nom);
}

function strMini($Nom) {
	$PosExtention = strripos($Nom,'.');
	$NewNom = substr($Nom, 0, $PosExtention) . ($GLOBALS['isDebug']?'':'.min') . substr($Nom, $PosExtention);
	if (file_exists($NewNom)){$Nom=$NewNom;}

	return $Nom;
}

function SuprArborescenceDossier($nomDossier) {
	if($GLOBALS['isDebug']){
			Echo '<br>TENTATIVE DE SUPPRIMER le DOSSIER '. $nomDossier;
	}
			
	if (is_dir($nomDossier)) {

		$files = array_diff(scandir($nomDossier), array('.','..'));
		/**/
		foreach ($files as $file) {
		  (is_dir("$nomDossier/$file")) ? SuprArborescenceDossier("$nomDossier/$file") : SuprFichier("$nomDossier/$file");
		}
		return SuprDossier($nomDossier);
	}	
}

function SuprFichier($fichier) {
	//chmod($fichier,0777);
	unlink($fichier);
}	

function SuprDossier($Dossier) {
	//chmod($Dossier,0777);
	return rmdir($Dossier);
}	


?>