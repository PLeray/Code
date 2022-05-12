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


$CodeEcole = '2PLANCHES';
$AnneeScolaire = '2021-2022';

if (isset($_GET['CodeEcole'])) { $CodeEcole = $_GET['CodeEcole'];}
if (isset($_GET['AnneeScolaire'])) { $AnneeScolaire = $_GET['AnneeScolaire'];}

$PDTNumeroLigne = 0;
$PDTDenomination = $InviteNomProduit;


if (isset($_GET['PDTDenomination'])) { $PDTDenomination = $_GET['PDTDenomination'];}
if (isset($_POST['PDTDenomination'])) { $PDTDenomination = $_POST['PDTDenomination'];}
if (isset($_GET['PDTNumeroLigne'])) { $PDTNumeroLigne = $_GET['PDTNumeroLigne'];}
if (isset($_POST['PDTNumeroLigne'])) { $PDTNumeroLigne = $_POST['PDTNumeroLigne'];}

$PDTCodeScripts = '(facultatif)_(facultatif)_facultatif)_(facultatif)';
if (isset($_GET['PDTCodeScripts'])) { $PDTCodeScripts = $_GET['PDTCodeScripts'];}
if (isset($_POST['PDTCodeScripts'])) { $PDTCodeScripts = $_POST['PDTCodeScripts'];}

$PDTCodeScripts = str_replace('(facultatif)','', $PDTCodeScripts) ;

/*
$Script = explode('_', $PDTCodeScripts);   
$PDTTaille = urlencode($Script[0]);
$PDTTransformation = (count($Script)>1? $Script[1]:'');
$PDTTeinte = (count($Script)>2? $Script[2]:'');
$PDTRecadrage = (count($Script)>3? $Script[3]:'');


$PDTRecadrage = ($PDTRecadrage=='(facultatif)'?'':$PDTRecadrage);
$PDTTaille = ($PDTTaille=='(facultatif)'?'':$PDTTaille);
$PDTTransformation = ($PDTTransformation=='(facultatif)'?'':$PDTTransformation);
$PDTTeinte = ($PDTTeinte=='(facultatif)'?'':$PDTTeinte);

*/

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');

$monProjetSource = new CProjetSource($CodeEcole, $AnneeScolaire); 

//MAJFichierCatalogue
if ((isset($_GET['PDTNumeroLigne'])) || (isset($_POST['PDTNumeroLigne']))) { 
    /*
    if($PDTNumeroLigne > 0){
        MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte);
    }elseif($PDTNumeroLigne == 0){ // On Ajoute cette ligne du fichier
        MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte);

    }elseif($PDTNumeroLigne < 0){ // On suprime cette ligne du fichier
        
        MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTRecadrage,$PDTTaille,$PDTTransformation,$PDTTeinte);
    }
    */
    MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTCodeScripts);
    header('Location: CMDCatalogueProduits.php'. ArgumentURL() .'&CodeEcole='.$CodeEcole.'&AnneeScolaire='.$AnneeScolaire );
}



?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/CMDCatalogueProduits.css")?>">

</head>
<body>

<div id="apiReponse" class="modal">
    <div class="modal-content animate" >
        <div class="imgcontainer">
            <a href="<?php echo RetourEcranAfficheSources($monProjetSource); ?>" class="close" title="Annuler et retour à l'écran de la source des photos">&times;</a>	
            <?php echo LienEdition($PDTDenomination,0, $monProjetSource->CodeEcole,$monProjetSource->AnneeScolaire); ?>			
        </div>
        <h1><img src="img/logo.png" width ="80px">Catalogue des produits avec <?php echo $monProjetSource->ScriptsPS ?></h1>


    <h2>(Nom du dossier d'Actions dans Photoshop : <?php echo $monProjetSource->ScriptsPS; ?>)</h2>

    <h1>Liste des Produits</h1>

<div class="CadreListeProduits">
       




    <table class="TableListeProduits" > 
	<tr >	
		<th >Nom du Produit</th>
		<th >Code pour actions Photoshop</th>	
		<th style="width:90px;"><font size="-1">Editer</font></th>		
		<th style="width:90px;"><font size="-1">Suprimer</font></th>
        
	</tr>          


<?php
    
    $TabProduits = ListeProduitsSelonCatalogue($monProjetSource);
    $retourMSG = '';
    for($i = 1; $i < count($TabProduits); $i++){
        if ($TabProduits[$i] != '') {
            $morceau = explode(';', $TabProduits[$i]);
            $retourMSG .= '<tr>
                            <td><H3>' . $morceau[0] . '</H3></td>
                            <td>' . $morceau[1] . '</td>
                        
                            <td>' .LienEdition($TabProduits[$i], $i,$monProjetSource->CodeEcole,$monProjetSource->AnneeScolaire). '</td>
                            <td>' .LienSupression($morceau[0],  $i, $monProjetSource->CodeEcole,$monProjetSource->AnneeScolaire). '</td>
                       
                            </tr>';	
        }		
    }
    echo $retourMSG;
    ?>

</table>

</div>

</div>
</div>

    


<script type="text/javascript" src="<?php Mini('js/APIDialogue.js');?>"></script>
</body>
</html>

<?php

function RetourEcranAfficheSources($monProjet){
    $RetourEcran = 'CMDAfficheSource.php' . ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire) ;
	return $RetourEcran ;
}


function LienEdition($Ligne, $PDTNumeroLigne, $CodeEcole, $AnneeScolaire){
    $ParamCProjetSource = '&CodeEcole=' . $CodeEcole . '&AnneeScolaire=' . $AnneeScolaire;
    
    if($PDTNumeroLigne == 0){// // Ajout d'un nouveau Produit
        $NomProduit = $GLOBALS['PDTDenomination'];

        $NomClasseBouton = 'AjoutProduit';
        $TitreBouton = '+';
        $TagTitleBouton = 'Ajouter un nouveau produit : ';
        $DefinitionProduit = '&PDTNumeroLigne=' . $PDTNumeroLigne . 
        '&PDTDenomination=' . urlencode($NomProduit) ;
        $DefinitionProduit .= '&PDTCodeScripts=';

    }else{
        $morceau = explode(';', $Ligne);
        $Script = explode('_', $morceau[1]);        
        $NomProduit = $morceau[0];
        $NomClasseBouton = 'icone';
        $TitreBouton = '🖉';
        $TagTitleBouton = 'Editer le produit : '.$NomProduit;
        $DefinitionProduit = '&PDTNumeroLigne=' . $PDTNumeroLigne . 
        '&PDTDenomination=' . urlencode($NomProduit) ;
        $DefinitionProduit .= '&PDTCodeScripts=' . $morceau[1];

        
    }

    $lien = '<a href="CMDEditionProduits.php' . ArgumentURL($DefinitionProduit.$ParamCProjetSource) 
                . '" class="'.$NomClasseBouton.'" title="'.$TagTitleBouton.'">'.$TitreBouton.'</a>';
    return $lien ;
}


function LienSupression($NomProduit, $PDTNumeroLigne, $CodeEcole, $AnneeScolaire){
    

    $lien = '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) 
                . ArgumentURL('&PDTNumeroLigne=-'. $PDTNumeroLigne
                .'&CodeEcole=' . $CodeEcole 
                . '&AnneeScolaire=' . $AnneeScolaire) 
                . '" class="icone" title="Suprimer le produit : '. $NomProduit .'">🗑</a>';
    return $lien ;

}



?>