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

if (isset($_GET['CodeEcole'])) { $CodeEcole = $_GET['CodeEcole'];}
if (isset($_GET['AnneeScolaire'])) { $AnneeScolaire = $_GET['AnneeScolaire'];}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');

$monProjetSource = new CProjetSource($CodeEcole, $AnneeScolaire); 

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

$tabPlanches = explode($GLOBALS['SeparateurInfoPlanche'], urldecode($PDTCodeScripts));

$Script = explode('_', $tabPlanches[$NumPlanche]);   
$PDTTaille = $Script[0];
$PDTTransformation = (count($Script)>1? $Script[1]:'');
$PDTTeinte = (count($Script)>2? $Script[2]:'');
$PDTRecadrage = (count($Script)>3? $Script[3]:'');


//MAJFichierCatalogue
if ((isset($_GET['PDTNumeroLigne'])) || (isset($_POST['PDTNumeroLigne']))) { 
    if($isDebug){
        echo 'PDTCodeScripts REcupére ' . $PDTCodeScripts;
        echo '<br><br><br>PDTNumeroLigne ' . $PDTNumeroLigne;
    }
    if($PDTNumeroLigne > 0){
        //MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte);
        MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTCodeScripts);
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
        echo '<br>un DossierScript : ' . $monProjetSource->ScriptsPS;
    }             
    $retourMSG = '';	
    $retourMSG .= '<div id="apiReponse" class="modal">
    <div class="modal-content animate" >
        <div class="imgcontainer">
            <a href="'.RetourEcranPrecedent($monProjetSource).'" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
        </div>
        <h1><img src="img/logo.png" width ="80px" alt="Aide sur l\'étape" >Catalogue produits : Edition d\'un produit '
        . '</h1>';	
        $retourMSG .= "<h2>(Nom du dossier d'Actions dans Photoshop : ". $monProjetSource->ScriptsPS .')</h2>';
        
        echo $retourMSG;
        if ($GLOBALS['isDebug']) { 
                echo '<//> CodeEcole : ' . $CodeEcole;
                echo '<//> AnneeScolaire : ' . $AnneeScolaire;
                echo '<//> PDTNumeroLigne : ' . $PDTNumeroLigne;
                echo '<//> PDTDenomination : ' . $PDTDenomination;
                /**/
                echo '<br><//> PDTRecadrage > ' . $PDTRecadrage;
                echo '<//> PDTTaille > ' . urldecode($PDTTaille);
                echo '<//> PDTTransformation > ' . $PDTTransformation;
                echo '<//> PDTTeinte > ' . $PDTTeinte;
        }
if ($isImport){
    $ValeurNomDefaut = $PDTDenomination;
}else{
    $ValeurNomDefaut = ($PDTNumeroLigne?$PDTDenomination:'');
}

 ?>

<h3>Dossier de script : <?php echo $monProjetSource->ScriptsPS; ?></h3>
<div class="DefinitionProduit">
<form action="<?php echo RetourEcranPrecedent($monProjetSource); ?>" method="post">



<h4>Nom du produit : 
<input type="text" id="zoneTexteNomCommande" 
                            placeholder="<?php echo $PDTDenomination; ?>"
                            value="<?php echo $ValeurNomDefaut; ?>" 
                            name="PDTDenomination" 
                            <?php echo ($isImport)?'readonly':''; ?>
                            required>

<input type="text" id="zonePDTNumeroLigne" placeholder="Nom de votre commande..."
                            value="<?php echo $PDTNumeroLigne; ?>" name="PDTNumeroLigne" required>      
                            
<input type="text" id="PDTCodeScripts" 
                            value="<?php echo $PDTCodeScripts; ?>" 
                            name="PDTCodeScripts" 
                            readonly
                            required>                            
<h2><?php  echo ListeFichier($PDTCodeScripts, $NumPlanche);?></h2>


</h4>
    <table class="TableDefinitionCodeProduit">
            <tr>
                <?php 
                    $DropListeRecadrages = $monProjetSource->DropListeScriptsRecadrages($PDTRecadrage);
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
<a href="<?php echo RetourEcranPrecedent($monProjetSource); ?>" class="KO" title="Annuler">Annuler</a>

<button type="submit" id="btnOK" class="OK" >OK</button>
  </form>
</div>
 
<script>
		var NumPlanche = <?php echo $NumPlanche?>;
		//alert('TEST ' ); 
</script>
<script type="text/javascript" src="<?php Mini('js/APIDialogue.js');?>"></script>
	
</body>
</html>

<?php

function RetourEcranPrecedent($monProjet){
    $DebutParam = (substr($GLOBALS['pageRetour'], -4) == '.php') ? '?' :  '&';
   
    $RetourEcran = $GLOBALS['pageRetour']. $DebutParam. 'CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire ;
	return $RetourEcran ;
}

function ListeFichier($PDTCodeScripts, $NumPlanche = 0){
    $tabPlanches = explode($GLOBALS['SeparateurInfoPlanche'], $PDTCodeScripts);
    $TitreBouton = '🖉';
    $maListe = '';
    for($i = 0; $i < count($tabPlanches); $i++){ 
        if($i==$NumPlanche){
            $maListe .=  '<div class="Planche">'.$tabPlanches[$i].'</div>';

        }else{
            $lien = htmlspecialchars($_SERVER['PHP_SELF']) .'?'.$_SERVER['QUERY_STRING'].'&NumPlanche='  . $i ;
            //$maListe .=  '<div class="Planche"><a href="'. $lien .'" class="icone" title="'.$tabPlanches[$i].'">'.$tabPlanches[$i]. $TitreBouton.'</a></div>';
       
            /*
            $lien = 'index.php' ;
            $maListe .=  '<div class="Planche">'.$tabPlanches[$i].'</div>';
            $maListe .=  '<form action="'. $lien .'" method="post">

            <button type="submit"  >'.$tabPlanches[$i]. $TitreBouton.'sdfsdfsdfsddf</button>
            </form>
            ';
           
            //$lien = 'index.php' ;
            $maListe .= $lien;
			$maListe .= '<form name="VoirEnGrand" method="post" action="'. $lien .'" enctype="multipart/form-data"> ';

			$maListe .= '</form>';
            
 */
			$maListe .='<button type="submit" class="NomPhotoZoom">
			<p>'.$tabPlanches[$i]. $TitreBouton.'</p>
			</button>';

        }
    }
    return $maListe;
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




