<?php
/**/
include_once 'CMDClassesDefinition.php';
	
ini_set('display_errors','on');
error_reporting(E_ALL);


function RetourEcranFichier($myfileName){
	$Etat = substr($myfileName, -1);
	$RetourEcran = 'CATPhotolab.php';	
    if ($Etat > 4){
		$RetourEcran = 'CATHistorique.php';
	}
	return $RetourEcran . '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') ;
}

function EnregistrerFichier($myfileName){
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
		$retourMSG = "Les planches de cette commandes ont été envoyé au labo " . utf8_encode(strftime("%A %d %B", strtotime(substr($myfileName,0,10)) )).    "."; 
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

function LienAfficheToutesLesCommandes($TouteslesCommandes,$Commande){
	//echo $Commande;
	$NbCMD=$GLOBALS['NbCMDAffiche'];
	$NbCMD=-1 * $NbCMD;
	if ($TouteslesCommandes){
		
	} 

	//
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') . '&nbCmd=' . $NbCMD;
	$Environnement = $Environnement . $Commande;
	
	$LienFichier = htmlspecialchars($_SERVER['PHP_SELF']) . $Environnement;
	return $LienFichier;
	
}

function LienRecherche($Commande){
	
	//echo $Commande;
	$NbCMD=$GLOBALS['NbCMDAffiche'];

	//
	$Environnement = '?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod') . '&nbCmd=' . $NbCMD;
	$Environnement = $Environnement . $Commande;
	
	//$LienFichier = htmlspecialchars($_SERVER['PHP_SELF']) . $Environnement;
	/* */ 
	$LienFichier= 'CMDRecherche.php'. $Environnement;;
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