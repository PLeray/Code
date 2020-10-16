<?php
////////////////////////////// APIConnexion LOCAL //////////////////////////////////////////////
class CConnexionLOCAL {
	var $codeMembre;
	var $Service;
    var $URL;
	var $Domaine;
	var $Bdd;
	var $isDebug;
	
    function __construct($codeMembre, $isDebug){
		$this->codeMembre = $codeMembre;
        if ($codeMembre){
            $this->Service = 'Code/res/PhotolabCMD.php';
            $this->URL = 'https://amp-serveur.local:998';
			//$Bdd = 'mysql:host=localhost;dbname=id4963524_photolab;charset=utf8', 'id4963524_admin', '0314delphine314';
        }
        else {
            $this->Service = 'Code/res/PhotolabCMD.php';
            $this->URL = 'http://localhost:80/PhotoLab';
			//$Bdd = 'mysql:host=localhost;dbname=test;charset=utf8', 'admin', '';			
        }
        if ($isDebug){
            //$Bdd = 'mysql:host=localhost;dbname=test;charset=utf8', 'admin', '';	
        }
        else {
			//$Bdd = 'mysql:host=localhost;dbname=id4963524_photolab;charset=utf8', 'id4963524_admin', '0314delphine314';
        }		
    }
    function AdresseLOCAL($CMDLocal){
        return $this->URL . $this->Service . $CMDLocal;
    } 	
    function ConnectBDD(){
        return $Bdd;
    } 	
    function TalkServeur($CMDLocal){
        return 'res/talkServeur.php' . $CMDLocal;
		
		$cmd = '?codeMembre=' . ($this->codeMembre ? 'OK' : 'KO') . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod');
        return $this->URL .'/res/talkServeur.php' . $cmd . $CMDLocal;		
    } 	
}

//$maConnexionLOCAL = new CConnexionLOCAL(false);
?>