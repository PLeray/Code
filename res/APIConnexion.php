<?php
$VERSIONLOCAL = 0.876;
$ANNEE = '2022';

$repPHOTOLAB = "../../";
$repCMDLABO = "../../CMDLABO/";
$repMINIATURES = "../../CMDLABO/MINIATURES/";
$repTIRAGES = "../../TIRAGES/";
$repWEBARBO = "../../WEB-ARBO/";
$repGABARITS = "../../GABARITS/";

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
		$this->Demo = '/res/DEMOPhotoLab.php';	
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
    function Demonstration($ParamGET = true){
		$cmd = '';
		if ($ParamGET){
			$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod'). '&pageRetour=' . $this->PageRetour . '&serveurRetour=' . urlencode(ServeurLocal());
		}
        return $this->URL . $this->Demo . $cmd;
    } 	
    function CallServeur($CMDLocal, $PageRetour = ''){
		$PageRetour = ($PageRetour == ''?$this->PageRetour:$PageRetour);
		$cmd = '?codeMembre=' . $this->codeMembre . '&isDebug=' .($this->isDebug ? 'Debug' : 'Prod') . '&pageRetour=' . $PageRetour . '&serveurRetour=' . urlencode(ServeurLocal()) ;
        return $this->URL .'/res/LOGTalkServeur.php' . $cmd . $CMDLocal;
    } 	
}

function VersionPhotoLab(){
	return '©PhotoLab 2018 - ' . $GLOBALS['ANNEE'] . ' (v'.$GLOBALS['VERSIONLOCAL'].') : Création et visualisation de commandes de photographies scolaires';
}

function ArgumentURL($ARGSupl = ''){
	return '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' .($GLOBALS['isDebug'] ? 'Debug' : 'Prod') . $ARGSupl;
}

function MAJPhotoLab($NouvelleVersion) { 
	if( $GLOBALS['VERSIONLOCAL'] < $NouvelleVersion){ 		 // pour Tester
		//header('Location: MAJPhotoLab.php'. ArgumentURL('&versionDistante='. $NouvelleVersion));
		header('Location: APIDialogue.php'. ArgumentURL('&versionDistante='. $NouvelleVersion));

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

function execInBackground($cmd, $isDedans = false) {
	if ($isDedans){ // On affiche l'intérieur en ajoutant le premier fichier au dossier cherché
			$cmd = str_replace("/","\\",$cmd); // Oui Ajouter ca permet de rentrer dans le dossier plutot que le pointer depuis le repertoire parent ????
	} 
    
	if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r")); 
		echo "Sur OS Windows " . $cmd;
    }
    else {
        exec($cmd . " > /dev/null &");  
		echo "Sur OS Autres " . $cmd;
    }
/*	
 $last_line = system($cmd, $retval);

*/
	
}

function IsLocalMachine() {
	//$isLocal = false;

  //echo 'L adresse IP de l utilisateur est : '.$_SERVER['REMOTE_ADDR'];
  //echo '<br>L adresse IP du serveur est : '.$_SERVER['SERVER_ADDR'];

	return ($_SERVER['REMOTE_ADDR'] === $_SERVER['SERVER_ADDR']) ;
}

function AfficheMenuPage($Page,$maConnexionAPI) {
	$menuPage = '<center>
	<div id="mySidenav">';		
		$menuPage .= '<a href="CATListeCatalogues.php' . ArgumentURL().'" ' . (($Page == "listeCatalogues")?' class="actif" ':'') . '  id="listeCatalogues" title="Catalogues disponibles ..."></a>';
		$menuPage .= '<a href="CATSources.php' . ArgumentURL().'" ' . (($Page == "sourcePhotos")?' class="actif" ':'') . '  id="sourcePhotos" title="Sources des photos ..."></a>';
		$menuPage .= '<a href="index.php' . ArgumentURL().'" ' . (($Page == "ajoutCommandeGroupee")?' class="actif" ':'') . '   id="ajoutCommandeGroupee" title="Ajouter une commande groupée ..."></a>';		
		$menuPage .= '<a href="CATPhotolab.php' . ArgumentURL().'" ' . (($Page == "commandesEnCours")?' class="actif" ':'') . '   id="commandesEnCours" title="Commandes en cours de préparation ..."></a>';
		$menuPage .= '<a href="CATHistorique.php' . ArgumentURL().'" ' . (($Page == "commandesExpediees")?' class="actif" ':'') . '   id="commandesExpediees" title="Historique des commandes expediées ..."></a>';
		$menuPage .= '<a href="' . $maConnexionAPI->Adresse().'" ' . (($Page == "administration")?' class="actif" ':'') . '   id="administration" title="Administration ..."></a>';
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
	if (file_exists($fichier)){ 
		//chmod($fichier,0777);
		unlink($fichier);
	}	
}	

function SuprDossier($Dossier) {
	if (file_exists($Dossier)){ 
		//chmod($Dossier,0777);
		
	}	
	return rmdir($Dossier);
}	

function RenommerFichierOuDossier($AncienNom, $NouveauNom){ // Nom De Fichier ou Dossier
	$isReussis = true;	
	if ($AncienNom != $NouveauNom) {
		if (file_exists($AncienNom)){ 
			$isReussis = renommer_win($AncienNom, $NouveauNom);
		}else{
			EnregistrerLigneLOG($AncienNom . " n existe pas !");
			$isReussis = FALSE;
		}
	}
	return $isReussis; 

}	

function renommer_win($oldfile,$newfile) {
	// renommer en gérant l'erreur de rename
	if (!rename($oldfile,$newfile)) {
		if (copy ($oldfile,$newfile)) {
			unlink($oldfile);
			EnregistrerLigneLOG("2-Copie-Supr + Supression de " . $oldfile . " vers " . $newfile);
			return TRUE;
		}else{
			EnregistrerLigneLOG("Impossible de copier " . $oldfile );
			return FALSE;
		}	   	   
	}else{
		EnregistrerLigneLOG("1-Renommage " . $oldfile . " en " . $newfile);
		return TRUE;
	}
 }


function EnregistrerLigneLOG($laLigne) {
	$laLigne = date('d-m-y h:i:s') . " >> " . $laLigne;
	return file_put_contents($GLOBALS['repPHOTOLAB'] . '/LOGInfo.txt', PHP_EOL.$laLigne, FILE_APPEND);
}



?>