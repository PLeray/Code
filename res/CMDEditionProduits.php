<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV-Lab.php';

//$repCommandesLABO = "../../CMDLABO/";

$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}


if (isset($_GET['unNomProduit'])) { $unNomProduit = $_GET['unNomProduit'];}


$unCodeEcole = '2PLANCHES';
$uneAnneeScolaire = '2021-2022';

if (isset($_GET['CodeEcole'])) { $unCodeEcole = $_GET['CodeEcole'];}
if (isset($_GET['AnneeScolaire'])) { $uneAnneeScolaire = $_GET['AnneeScolaire'];}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');

$monProjetSource = new CProjetSource($unCodeEcole, $uneAnneeScolaire); 

$PDTNumeroLigne = 0;
$PDTDenomination = 'Mon nom de produit';
$PDTRecadrage = '(facultatif)';
$PDTTaille = '(facultatif)';
$PDTTransformation = '(facultatif)';
$PDTTeinte = '(facultatif)';
if (isset($_GET['PDTNumeroLigne'])) { $PDTNumeroLigne = $_GET['PDTNumeroLigne'];}
if (isset($_GET['PDTDenomination'])) { $PDTDenomination = $_GET['PDTDenomination'];}
if (isset($_GET['PDTRecadrage'])) { $PDTRecadrage = $_GET['PDTRecadrage'];}
if (isset($_GET['PDTTaille'])) { $PDTTaille = $_GET['PDTTaille'];}
if (isset($_GET['PDTTransformation'])) { $PDTTransformation = $_GET['PDTTransformation'];}
if (isset($_GET['PDTTeinte'])) { $PDTTeinte = $_GET['PDTTeinte'];}

if (isset($_POST['PDTNumeroLigne'])) { $PDTNumeroLigne = $_POST['PDTNumeroLigne'];}
if (isset($_POST['PDTDenomination'])) { $PDTDenomination = $_POST['PDTDenomination'];}
if (isset($_POST['PDTRecadrage'])) { $PDTRecadrage = $_POST['PDTRecadrage'];}
if (isset($_POST['PDTTaille'])) { $PDTTaille = $_POST['PDTTaille'];}
if (isset($_POST['PDTTransformation'])) { $PDTTransformation = $_POST['PDTTransformation'];}
if (isset($_POST['PDTTeinte'])) { $PDTTeinte = $_POST['PDTTeinte'];}


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
	echo 'un DossierScript : ' . $monProjetSource->ScriptsPS;
}
                        
            $retourMSG = '';	
            $retourMSG .= '<div id="apiReponse" class="modal">
            <div class="modal-content animate" >
                <div class="imgcontainer">
                    <a href="'.RetourEcranCatalogue($monProjetSource).'" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
                </div>
                <h1><img src="img/logo.png" width ="80px" alt="Aide sur l\'étape" >Catalogue produits : Edition d\'un produit '
                . '</h1>';	
                $retourMSG .= "<h2>(Nom du dossier d'Actions dans Photoshop : ". $monProjetSource->ScriptsPS .')</h2>';
                
                echo $retourMSG;
                
                if ((isset($_GET['PDTTaille']))||(isset($_POST['PDTTaille']))) { 
                    echo '<br> CodeEcole : ' . $unCodeEcole;
                    echo '<br> AnneeScolaire : ' . $uneAnneeScolaire;
                    echo '<br> PDTNumeroLigne : ' . $PDTNumeroLigne;
                    echo '<br> PDTDenomination : ' . $PDTDenomination;
                    /**/
                    echo '<br> PDTRecadrage > ' . $PDTRecadrage;
                    echo '<br> PDTTaille > ' . $PDTTaille;
                    echo '<br> PDTTransformation > ' . $PDTTransformation;
                    echo '<br> PDTTeinte > ' . $PDTTeinte;
                    
                    
                }
                ?>

<h3>Dossier de script : <?php echo $monProjetSource->ScriptsPS; ?></h3>
<div class="DefinitionProduit">
<form action="<?php echo RetourEcranCatalogue($monProjetSource); ?>" method="post">



<h4>Nom du produit : 
<input type="text" id="zoneTexteNomCommande" placeholder="<?php echo $PDTDenomination; ?>"
                            value="<?php echo ($PDTNumeroLigne?$PDTDenomination:''); ?>" name="PDTDenomination" required>
<input type="text" id="zonePDTNumeroLigne" placeholder="Nom de votre commande..."
                            value="<?php echo $PDTNumeroLigne; ?>" name="PDTNumeroLigne" required>                            
</h4>
    <table class="TableDefinitionCodeProduit">
            <tr>
                <td ><h2>Recadrages :</h2></td>
                <td ><h2>Taille :</h2></td>
                <td ><h2>Transformation :</h2></td>
                <td ><h2>Teinte :</h2></td>
            </tr>

            <tr>
                <td ><div class="custom-select" style="width:180px;">
                <select id="PDTRecadrage" name="PDTRecadrage">
                    <?php       
                        echo $monProjetSource->DropListeScriptsRecadrages($PDTRecadrage); 
                    ?>
                    </select>
                    </div> 
                </td>
                <td ><div class="custom-select" style="width:180px;">
                    <select id="PDTTaille" name="PDTTaille">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTailles($PDTTaille); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:250px;">
                <select id="PDTTransformation" name="PDTTransformation">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTransformation($PDTTransformation); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:250px;">
                <select id="PDTTeinte" name="PDTTeinte">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTeinte($PDTTeinte); 
                    ?>
                    </select>
                    </div>  
                </td>                
  
            </tr>
</table>
<a href="<?php echo RetourEcranCatalogue($monProjetSource); ?>" class="KO" title="Annuler">Annuler</a>

<button type="submit" id="btnOK" class="OK" >OK</button>
  </form>
</div>
 

<script type="text/javascript" src="<?php Mini('js/APIDialogue.js');?>"></script>
</body>
</html>

<?php

function RetourEcranCatalogue($monProjet){
    $RetourEcran = 'CMDCatalogueProduits.php' . ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire) ;
	return $RetourEcran ;
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




