<?php
$VERSION = '2021';

$repCMDLABO = "../../CMDLABO/";
$repMINIATURES = "../../CMDLABO/MINIATURES/";
$repTIRAGES = "../../TIRAGES/";

////////////////////////////// APIConnexion //////////////////////////////////////////////
class CConnexionAPI {
	var $isDebug;
    var $codeMembre;
	var $Service;
    var $URL;
	var $Domaine;
	
    function __construct($codeMembre ,$isDebug){
        $this->isDebug = $isDebug;
        $this->codeMembre = $codeMembre;
		if ($isDebug){
            $this->Service = '/res/LOGPhotoLab.php';
            $this->URL = 'http://localhost/API_photolab';  //"http://localhost/online/res/drop.php" 
            $this->Domaine = 'localhost:80';  
		}
        else {
            $this->Service = '/res/LOGPhotoLab.php';
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
		$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod');
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
  <a href="CATSources.php' . ArgumentURL().'" id="sourcePhotos" title="Sources des photos ...">S</a>
  <a href="CATPhotolab.php' . ArgumentURL().'" id="commandesEnCours" title="Commandes en cours de traitement ...">C</a>
  <a href="CATHistorique.php' . ArgumentURL().'" id="commandesExpediees" title="Historique des commandes expediées ...">H</a>
  <a href="' . $maConnexionAPI->Adresse().'" id="administration" title="Administration ...">A</a>
</div>
</center>
'

;
}


?>