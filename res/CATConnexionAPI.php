<?php
$VERSION = '2020';


$repCMDLABO = "../../CMDLABO/";
$repMINIATURES = "../../CMDLABO/MINIATURES/";
$repTIRAGES = "../../TIRAGES/";

////////////////////////////// APIConnexion //////////////////////////////////////////////
class CConnexionAPI {
	var $isDebug;
    var $isAMP;
	var $Service;
    var $URL;
	var $Domaine;
	
    function __construct($isAMP ,$isDebug){
        $this->isDebug = $isDebug;
        $this->isAMP = $isAMP;
		if ($isDebug){
            $this->Service = '/PhotoLab.php';
            $this->URL = 'http://localhost/API_photolab';  //"http://localhost/online/res/drop.php" 
            $this->Domaine = 'localhost:80';  
		}
        else {
            $this->Service = '/PhotoLab.php';
            $this->URL = 'https://photolab-site.fr'; //https://www.studio-carre.fr/PeterTest/API_photolab/   
            $this->Domaine = 'www.photolab-site.fr:80';  
			
        }
    }
    function Adresse($ParamGET = true){
		if ($ParamGET){
			$cmd = '?isAMP=' . ($this->isAMP ? 'OK' : 'KO') . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod');
		}
        return $this->URL . $this->Service . $cmd;
    } 
    function TalkServeur($CMDLocal){
		$cmd = '?isAMP=' . ($this->isAMP ? 'OK' : 'KO') . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod');
        return $this->URL .'/res/talkServeur.php' . $cmd . $CMDLocal;
    } 	
}

function VersionPhotoLab(){
	return '©PhotoLab ' . $GLOBALS['VERSION'] . ' : Création et visualisation de commandes de photographies';
}

function ArgumentURL(){
	return '?isAMP=' . ($GLOBALS['isAMP'] ? 'OK' : 'KO') . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod');
}


////////////////////////////// APIConnexion LOCAL //////////////////////////////////////////////
/*class CConnexionLOCAL {
	var $isAMP;
	var $Service;
    var $URL;
	var $Domaine;
	
    function __construct($isAMP){
		$this->isAMP = $isAMP;
        if ($isAMP){
            $this->Service = '/index.php';
            $this->URL = 'http://amp-serveur.local:999';  //https://amp-serveur.local:998/index.php
        }
        else {
            $this->Service = '/index.php';
            $this->URL = 'http://localhost/PhotoLab';
            //$this->URL = 'http://Photoprod';
        }
    }
    function Adresse($CMDLocal){
        return $this->URL . $this->Service . $CMDLocal;
    } 
}*/

?>