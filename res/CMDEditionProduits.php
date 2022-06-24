<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV-Lab.php';

//$repCommandesLABO = "../../CMDLABO/";

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}


//if (isset($_GET['unNomProduit'])) { $unNomProduit = $_GET['unNomProduit'];}
$isImport = false;
if (isset($_GET['isImport'])) { $isImport = ($_GET['isImport'] == 'true');}

$pageRetour = 'CMDCatalogueProduits.php';
if (isset($_GET['pageRetour'])) { $pageRetour = $_GET['pageRetour'];}

if($isDebug){
	echo 'un pageRetour : ' . $pageRetour . '<br>';
}
$CodeEcole = '';
$AnneeScolaire = '';

/*
if (isset($_GET['CodeEcole'])) { $CodeEcole = $_GET['CodeEcole'];}
if (isset($_GET['AnneeScolaire'])) { $AnneeScolaire = $_GET['AnneeScolaire'];}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');

$monProjetSource = new CProjetSource($CodeEcole, $AnneeScolaire); 
*/
$NomDossiserScript = '';

if ((isset($_GET['CodeEcole'])) && (isset($_GET['AnneeScolaire']))) { 
    $CodeEcole = $_GET['CodeEcole'];
    $AnneeScolaire = $_GET['AnneeScolaire'];
}

$monProjetSource = new CProjetSource($CodeEcole, $AnneeScolaire); 

if (isset($_GET['NomDossiserScript'])) {
     $NomDossiserScript = $_GET['NomDossiserScript'];
}else{
    $NomDossiserScript = $monProjetSource->ScriptsPS;
}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');
$monCatalogueProduit = new CCatalogueProduit($NomDossiserScript); 




$NumPlanche = 0;
if (isset($_GET['NumPlanche'])) { $NumPlanche = intval($_GET['NumPlanche']);}

$PDTNumeroLigne = 0;
$PDTDenomination = 'Mon nom de produit';

if (isset($_GET['PDTDenomination'])) { $PDTDenomination = $_GET['PDTDenomination'];}
if (isset($_POST['PDTDenomination'])) { $PDTDenomination = $_POST['PDTDenomination'];}
if (isset($_GET['PDTNumeroLigne'])) { $PDTNumeroLigne = $_GET['PDTNumeroLigne'];}
if (isset($_POST['PDTNumeroLigne'])) { $PDTNumeroLigne = $_POST['PDTNumeroLigne'];}

$PDTCodeScripts = '(facultatif)_(facultatif)_facultatif)_(facultatif)';
if (isset($_GET['PDTCodeScripts'])) { $PDTCodeScripts = $_GET['PDTCodeScripts'];}
if (isset($_POST['PDTCodeScripts'])) { $PDTCodeScripts = $_POST['PDTCodeScripts'];}

if (isset($_GET['SuprPlanche'])) { 
    if($isDebug){
        echo '<br>SuprPlanche  ' . $_GET['SuprPlanche'];
        echo '<br><br>DEPART $PDTCodeScripts ' . $PDTCodeScripts;
    }    
    $PDTCodeScripts = SupressionPlancheNum($PDTCodeScripts, $_GET['SuprPlanche']);
    if($isDebug){
        echo '<br>SuprPlanche  ' . $_GET['SuprPlanche'];
        echo '<br><br>ARRIVE $PDTCodeScripts ' . $PDTCodeScripts;
    }        
    $NumPlanche = 0;
}

$tabPlanches = explode($GLOBALS['SeparateurInfoPlanche'], $PDTCodeScripts);

$nbPlanches = count($tabPlanches);

//if ($nbPlanches <= $NumPlanche > )
if ($nbPlanches > $NumPlanche ){// La planche existe pas encore c'est une nouvelle
    $Script = explode('_', $tabPlanches[$NumPlanche]); 
}else{
    $Script = explode('_', $tabPlanches[0]); // Nouvelle Planche
}
  
$PDTTaille = $Script[0];
$PDTTransformation = (count($Script)>1? $Script[1]:'');
$PDTTeinte = (count($Script)>2? $Script[2]:'');
$PDTRecadrage = (count($Script)>3? $Script[3]:'');

//MAJFichierCatalogue
if ((isset($_GET['PDTNumeroLigne'])) || (isset($_POST['PDTNumeroLigne']))) { 
    if($PDTNumeroLigne > 0){
        //MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTCodeScripts);
        MAJFichierCatalogue($monCatalogueProduit,$PDTNumeroLigne,$PDTDenomination,$PDTCodeScripts);
    }
    //header('Location: CMDCatalogueProduits.php'. ArgumentURL() .'&CodeEcole='.$CodeEcole.'&AnneeScolaire='.$AnneeScolaire );
}


?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/CMDEditionProduits.css")?>">

</head>
<body>

<?php
    if($isDebug){
        echo '<br>un DossierScript : ' . $monCatalogueProduit->ScriptsPS;
    }             
    $retourMSG = '<div id="apiReponse" class="modal">
    <div class="modal-content animate" >
        <div class="imgcontainer">
            <a href="'.RetourEcranPrecedent($monCatalogueProduit).'" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
        </div>
        <h1><img src="img/logo.png" width ="80px" alt="Aide sur l\'étape" >Catalogue produits : Edition d\'un produit '
        . '</h1>';	
        
        echo $retourMSG;
        /*
        if ($GLOBALS['isDebug']) { 
                echo '<//> CodeEcole : ' . $CodeEcole .'FIN';
                echo '<//> AnneeScolaire : ' . $AnneeScolaire .'FIN';
                echo '<//> PDTNumeroLigne : ' . $PDTNumeroLigne .'FIN';
                echo '<//> PDTDenomination : ' . $PDTDenomination .'FIN';
                echo '<//> PDTCodeScripts : ' . $PDTCodeScripts .'FIN';
             
                echo '<br><//> PDTRecadrage > ' . $PDTRecadrage;
                echo '<//> PDTTaille > ' . urldecode($PDTTaille);
                echo '<//> PDTTransformation > ' . $PDTTransformation;
                echo '<//> PDTTeinte > ' . $PDTTeinte;
        }*/
if ($isImport){
    $ValeurNomDefaut = $PDTDenomination;
}else{
    $ValeurNomDefaut = ($PDTNumeroLigne?$PDTDenomination:'');
}
 ?>




<form action="<?php echo RetourEcranPrecedent($monCatalogueProduit); ?>" method="post">


<table width="100%">
        <tr>
        <td style="text-align: center; vertical-align: middle; background-color: var(--texteGris);" width="400px">
                <div >
                <?php  echo RetourneImagePlanche($tabPlanches[$NumPlanche]);  ?>
                </div>
            </td>            
            <td style="padding-left: 10px; vertical-align: middle; ">
        <?php            
            $retourMSG = "<h2>(Nom du dossier d'Actions dans Photoshop : ". $monCatalogueProduit->ScriptsPS .')</h2>';
            $retourMSG .= "<h3>Dossier de script : ". $monCatalogueProduit->ScriptsPS .'</h3>';
            $retourMSG .= "<h4>Nom du produit : </h4>";        
            echo $retourMSG;
        ?>
<input type="text" id="zoneTexteNomCommande" 
                            placeholder="<?php echo $PDTDenomination; ?>"
                            value="<?php echo $ValeurNomDefaut; ?>" 
                            name="PDTDenomination" 
                            <?php echo ($isImport)?'readonly':''; ?>
                            required>
<input type="text" id="PDTCodeScripts" 
                            value="<?php echo $PDTCodeScripts; ?>" 
                            name="PDTCodeScripts" 
                            readonly
                            required>   

<input type="text" id="zonePDTNumeroLigne" value="<?php echo $PDTNumeroLigne; ?>" name="PDTNumeroLigne" required>      
                            
                                  
            


<h4>Transformation(s) avec actions Photoshop : </h4>
      

<?php  echo ListeFichier($PDTCodeScripts, $NumPlanche);?>
    <div class="DefinitionProduit">
    <br><br>
    <table class="TableDefinitionCodeProduit">
            <tr>
                <?php 
                    $DropListeRecadrages = $monCatalogueProduit->DropListeScriptsRecadrages($PDTRecadrage);
                    echo ($DropListeRecadrages != 'VIDE')?'<td ><h2>Recadrages :</h2></td>':''; 
                    //echo $DropListeRecadrages;
                ?>    
                <td ><h2>Taille :</h2></td>
                
                <td ><h2>Transformation :</h2></td>
                <td ><h2>Teinte :</h2></td>
            </tr>
            
            <tr>
                <?php 
                    if ($DropListeRecadrages != 'VIDE') {
                        echo '<td ><div class="custom-select" style="width:180px;">
                            <select id="PDTRecadrage" name="PDTRecadrage">'.
                            $DropListeRecadrages
                            .'</select>
                            </div> 
                        </td>';
                    }
                ?>                   
                <td ><div class="custom-select" style="width:180px;">
                    <select id="PDTTaille" name="PDTTaille">
                    <?php          
                        echo $monCatalogueProduit->DropListeScriptsTailles($PDTTaille); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:250px;">
                <select id="PDTTransformation" name="PDTTransformation">
                    <?php          
                        echo $monCatalogueProduit->DropListeScriptsTransformation($PDTTransformation); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:250px;">
                <select id="PDTTeinte" name="PDTTeinte">
                    <?php          
                        echo $monCatalogueProduit->DropListeScriptsTeinte($PDTTeinte); 
                    ?>
                    </select>
                    </div>  
                </td> 
                <?php 
                if($nbPlanches>1){
                    
                    //$lien = htmlspecialchars($_SERVER['PHP_SELF']) .'?'.$_SERVER['QUERY_STRING'].'&SuprPlanche='  . $NumPlanche ;
                    $lien = getURI(true, array('SuprPlanche','NumPlanche')).'SuprPlanche='  . $NumPlanche ;
                    echo '<td ><div >
                    <a  class= "SuprPlanche" href="'. $lien .'" title="Suprimer cette planche du produit">'. (($nbPlanches>1)?'🗑':'').'</a> 
                    </div> </td> '; 

                }

                ?>                                       
            </tr>
</table>
<br>

</td>

</tr>
</table>


<div align="center">
    <a href="<?php echo RetourEcranPrecedent($monCatalogueProduit); ?>" class="KO" title="Annuler">Annuler</a>

    <button type="submit" id="btnOK" class="OK" >OK</button>
    </form>
    </div>
</div>


<script type="text/javascript" src="<?php Mini('js/APIDialogue.js');?>"></script>
<script>
    InitDropListe(<?php echo $NumPlanche ?>);
</script>	
</body>
</html>

<?php
//function RetourneImageProduit($monCodeScript){

function ParamtreEditionProduit(){
    $Param ='&PDTNumeroLigne='. $GLOBALS['PDTNumeroLigne'] .
            '&PDTDenomination='. $GLOBALS['PDTDenomination'] .
            '&CodeEcole='. $GLOBALS['CodeEcole'] .
            '&AnneeScolaire='. $GLOBALS['AnneeScolaire'] ;
        
        //&NumPlanche='.$GLOBALS['NumPlanche'] ;
        return $Param;
}

/*
function RetourEcranPrecedent($monProjet){
    $DebutParam = (substr($GLOBALS['pageRetour'], -4) == '.php') ? '?' :  '&';
   
    $RetourEcran = $GLOBALS['pageRetour']. $DebutParam. 'CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire ;
	return $RetourEcran ;
}*/
function RetourEcranPrecedent($monCatalogueProduit){
    $DebutParam = (substr($GLOBALS['pageRetour'], -4) == '.php') ? '?' :  '&';
   
    $RetourEcran = $GLOBALS['pageRetour']. $DebutParam. 'NomDossiserScript=' . urlencode($monCatalogueProduit->ScriptsPS) ;
	return $RetourEcran ;
}
function ListeFichier($PDTCodeScripts, $NumPlanche = 0){
    $tabPlanches = explode($GLOBALS['SeparateurInfoPlanche'], $PDTCodeScripts);
    $maListe = '';

    $maListe .=' <div class="topnav">';

    $nbPlanches = count($tabPlanches);
    for($i = 0; $i < $nbPlanches; $i++){ 
        if($i==$NumPlanche){
            $maListe .=  '<a id="Planche'.$i.'" class="active" >'.$tabPlanches[$i] .'</a>';
        }else{
            //$lien = htmlspecialchars($_SERVER['PHP_SELF']) .'?'.$_SERVER['QUERY_STRING'].'&NumPlanche='  . $i ;
            $lien = getURI(true, 'SuprPlanche','NumPlanche') .'NumPlanche='  . $i ;
            $urlBase = getURI(true, array('SuprPlanche','NumPlanche','PDTCodeScripts')).'NumPlanche='  . $i ;
            $maListe .=  '<a id="Planche'.$i.'"
            href="'. $lien .'" 
            urlBase= "'. $urlBase .'">'.$tabPlanches[$i].'</a>';
        }
        
    }
    //$NumPlanche=$nbPlanches;
    
    $maListe .= '</div>';
    //Pour l'ajout de planche
    $PDTCodeScripts=str_replace('(facultatif)', '', $PDTCodeScripts);
   //  $lien = htmlspecialchars($_SERVER['PHP_SELF']) .'?'.$_SERVER['QUERY_STRING'].'&NumPlanche='  . $NumPlanche ;
    //$urlBase = getURI(true, 'PDTCodeScripts'). 'xxxxxPDTCodeScripts='.$PDTCodeScripts.'§___&NumPlanche='  . $nbPlanches;
    //$lien = $urlBase;
    $lien = htmlspecialchars($_SERVER['PHP_SELF']) .ArgumentURL(ParamtreEditionProduit()). '&PDTCodeScripts='.$PDTCodeScripts.'§___&NumPlanche='  . $nbPlanches;
    
    $maListe .=  '<div><a class="Plus" id="Planche'.$NumPlanche.'"
    title="Ajouter une planche au produit" href="'. $lien .'">+</a></div>';  

    return $maListe;
}

function SupressionPlancheNum($PDTCodeScripts, $NumPlanche){
    $tabPlanches = explode($GLOBALS['SeparateurInfoPlanche'], urldecode($PDTCodeScripts));
    $monNouveauProduit = '';
    var_dump($tabPlanches);
    $nbPlanches = count($tabPlanches);
    for($i = 0; $i < $nbPlanches; $i++){ 
        if($i!=$NumPlanche){
            if($GLOBALS['isDebug']){
                echo '<br>planche dans ' . $tabPlanches[$i];
            }     
            $monNouveauProduit .= $tabPlanches[$i] . $GLOBALS['SeparateurInfoPlanche'];
        }
    }

    return trim(substr($monNouveauProduit, 0, -2)); // -2 car y a un charractere bizarre qui traine !!... 
}

function getURI($parametre = false, $param_to_delete = array()){ // Fonction pour suprimmer un argument ici 
    //On convertit le second paramètre en array si ce n'en est pas une
    $param_to_delete = (!is_array($param_to_delete) ? array($param_to_delete) : $param_to_delete);
     
    $adresse = null;
    foreach($_GET as $cle => $valeur){ //On parcourt toutes les variables en GET
        if(!in_array($cle, $param_to_delete)){ //On regarde si la variable fait partie de celles à supprimer
            //Sinon, on l'ajoute à l'adresse
            $adresse .= ($adresse ? '&' : '?').$cle.($valeur ? '='.$valeur : '');
        }
    }
    if($parametre){
        //Si le premier paramètre de la fonction est true, on ajoute le nécessaire pour pouvoir ajouter une variable après le retour de la fonction
        $adresse .= ($adresse ? '&' : '?');
    }
    //On ajoute le chemin du fichier sans les paramètres GET
    $adresse = $_SERVER['PHP_SELF'].$adresse;
    return $adresse;
}


/*
function ValidationOK($monProjet){
    $RetourEcran = htmlspecialchars($_SERVER['PHP_SELF']) . ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire) ;
	return $RetourEcran ;
}



function AccesCatalogue($monProjet){
	$RetourEcran = 'CMDCatalogueProduits.php?codeMembre=' . $GLOBALS['codeMembre'] . '&isDebug=' . ($GLOBALS['isDebug']?'Debug':'Prod');
	return $RetourEcran . '&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire ;	
}	
*/


?>




