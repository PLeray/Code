<?php
$VERSION = '2021';

$repCMDLABO = "../../CMDLABO/";
$repMINIATURES = "../../CMDLABO/MINIATURES/";
$repTIRAGES = "../../TIRAGES/";
$repWEBARBO = "../../WEB-ARBO/";

//ini_set("auto_detect_line_endings", true); // Lecture MAC fin de ligne

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
			$this->URL = 'https://photolab-site.fr'; //https://www.studio-carre.fr/PeterTest/API_photolab/   
            $this->Domaine = 'www.photolab-site.fr:80';  
			
        }
    }
    function Adresse($ParamGET = true){
		if ($ParamGET){
			$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod');
		}
        return $this->URL . $this->Service . $cmd;
    } 
    function CallServeur($CMDLocal){
		$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod') . '&pageRetour=' . $this->PageRetour ;
        return $this->URL .'/res/talkServeur.php' . $cmd . $CMDLocal;
    } 	
}

function VersionPhotoLab(){
	return '©PhotoLab ' . $GLOBALS['VERSION'] . ' : Création et visualisation de commandes de photographies';
}

function ArgumentURL(){
	return '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod');
}

function LienOuvrirDossierOS($repertoire) {
	$LienFichier = '#';
	if ($repertoire != ''){
		$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
		$LienFichier = "CATPhotolab.php". $Environnement . "&OpenRep=" . urlencode($repertoire);		
	}
	return $LienFichier;
}

function AfficheMenuPage($Page,$maConnexionAPI) {
echo '
<center>
<div id="mySidenav" class="sidenav">
  <a href="CATSources.php' . ArgumentURL().'" id="sourcePhotos" title="Sources des photos ..."></a>
  <a href="CATPhotolab.php' . ArgumentURL().'" id="commandesEnCours" title="Commandes en cours de préparation ..."></a>
  <a href="CATHistorique2.php' . ArgumentURL().'" id="commandesExpediees" title="Historique des commandes expediées ..."></a>
  <a href="' . $maConnexionAPI->Adresse().'" id="administration" title="Administration ..."></a>
</div>
</center>
'

;
}


?>