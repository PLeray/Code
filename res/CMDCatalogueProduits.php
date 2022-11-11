<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV-Lab.php';


$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}


$pagePrecedenteCatalogueProduits = 'CATListeCatalogues.php';
if (isset($_GET['pagePrecedenteCatalogueProduits'])) { $pagePrecedenteCatalogueProduits = $_GET['pagePrecedenteCatalogueProduits'];}


if (isset($_GET['unNomProduit'])) { $unNomProduit = $_GET['unNomProduit'];}


//$CodeEcole = '2PLANCHES';
//$AnneeScolaire = '2021-2022';
$CodeEcole = '';
$AnneeScolaire = '';

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
    //MAJFichierCatalogue($monProjetSource,$PDTNumeroLigne,$PDTDenomination,$PDTCodeScripts);
    //header('Location: CMDCatalogueProduits.php'. ArgumentURL() .'&CodeEcole='.$CodeEcole.'&AnneeScolaire='.$AnneeScolaire );
    MAJFichierCatalogue($monCatalogueProduit,$PDTNumeroLigne,$PDTDenomination,$PDTCodeScripts);
    header('Location: CMDCatalogueProduits.php'. ArgumentURL() .'&NomDossiserScript='. urlencode($NomDossiserScript));
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
            <a href="<?php echo RetourEcranPrecedent($monProjetSource); ?>" class="close" title="Annuler et retour à l'écran de la source des photos">&times;</a>	
            <?php 
                //echo LienEdition($PDTDenomination,0, $monProjetSource->CodeEcole,$monProjetSource->AnneeScolaire); 
                echo LienEdition($PDTDenomination,0, $monCatalogueProduit->ScriptsPS); 
            ?>			
        </div>
        <h1><img src="img/logo.png" width ="80px">Catalogue des produits avec "<?php echo $monCatalogueProduit->NomCatalogue() ?>"</h1>


    <h2>(Nom du dossier d'Actions dans Photoshop : <?php echo $monCatalogueProduit->ScriptsPS; ?>)</h2>

    <h1>Liste des Produits</h1>

<div class="CadreListeProduits">
    <table class="TableListeProduits" > 
	<tr >	
		<th >Nom du Produit</th>
		<th >Code pour actions Photoshop</th>	
        <th style="width:90px;"></th>	
		<th style="width:90px;"><font size="-1">Editer</font></th>		
		<th style="width:90px;"><font size="-1">Suprimer</font></th>
        
	</tr>          
<?php
    //$TabProduits = ListeProduitsSelonCatalogue($monProjetSource);
    $TabProduits = ListeProduitsSelonCatalogue($monCatalogueProduit);
    $retourMSG = '';
    for($i = 1; $i < count($TabProduits); $i++){
        if ($TabProduits[$i] != '') {
            $morceau = explode(';', $TabProduits[$i]);
            $retourMSG .= '<tr>
                            <td><H3>' . $morceau[0] . '</H3></td>
                            <td>' . $morceau[1] . '</td>
                            <td>' .RetourneImageProduit($morceau[1]). '</td>';                       
            //$retourMSG .= '<td>' .LienEdition($TabProduits[$i], $i,$monProjetSource->CodeEcole,$monProjetSource->AnneeScolaire). '</td>
            //                <td>' .LienSupression($morceau[0],  $i, $monProjetSource->CodeEcole,$monProjetSource->AnneeScolaire). '</td>
            $retourMSG .= '<td>' .LienEdition($TabProduits[$i], $i,$NomDossiserScript). '</td>
                            <td>' .LienSupression($morceau[0],  $i,$NomDossiserScript). '</td>                       
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

function RetourEcranPrecedent($monProjet){
    if (isset($_GET['NomDossiserScript'])) {
        $RetourEcran = 'CATListeCatalogues.php' . ArgumentURL() ;
   }else{
    
        $RetourEcran = $GLOBALS['pagePrecedenteCatalogueProduits'] . ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire) ;    
   }
    //$RetourEcran = 'CMDAfficheSource.php' . ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire) ;
	return $RetourEcran ;
    
}


//function LienEdition($Ligne, $PDTNumeroLigne, $CodeEcole, $AnneeScolaire){
    //$ParamCProjetSource = '&CodeEcole=' . $CodeEcole . '&AnneeScolaire=' . $AnneeScolaire;    
function LienEdition($Ligne, $PDTNumeroLigne){
    if (isset($_GET['NomDossiserScript'])) {
        $ParamCProjetSource = '&NomDossiserScript=' . $GLOBALS['NomDossiserScript']; ; 
   }else{
        $ParamCProjetSource = '&CodeEcole=' . $GLOBALS['CodeEcole'] . '&AnneeScolaire=' . $GLOBALS['AnneeScolaire'];      
   } 
    
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
    /*$lien = '<a href="CMDEditionProduits.php' . ArgumentURL($DefinitionProduit.$ParamCProjetSource) 
                . '" class="'.$NomClasseBouton.'" title="'.$TagTitleBouton.'">'.$TitreBouton.'</a>';*/
    $lien = '<a href="CMDEditionProduits.php' . ArgumentURL($DefinitionProduit.$ParamCProjetSource) 
                . '" class="'.$NomClasseBouton.'" title="'.$TagTitleBouton.'">'.$TitreBouton.'</a>';
    return $lien ;
}


//function LienSupression($NomProduit, $PDTNumeroLigne, $CodeEcole, $AnneeScolaire){
function LienSupression($NomProduit, $PDTNumeroLigne, $NomDossiserScript){
    if (isset($_GET['NomDossiserScript'])) {
        $ParamCProjetSource = '&NomDossiserScript=' . $GLOBALS['NomDossiserScript']; ; 
   }else{
        $ParamCProjetSource = '&CodeEcole=' . $GLOBALS['CodeEcole'] . '&AnneeScolaire=' . $GLOBALS['AnneeScolaire'];      
   } 


    /*$lien = '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) 
                . ArgumentURL('&PDTNumeroLigne=-'. $PDTNumeroLigne
                .'&CodeEcole=' . $CodeEcole 
                . '&AnneeScolaire=' . $AnneeScolaire) 
                . '" class="icone" title="Suprimer le produit : '. $NomProduit .'">🗑</a>';*/

    $lien = '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) 
        . ArgumentURL('&PDTNumeroLigne=-'. $PDTNumeroLigne . $ParamCProjetSource) 
        . '" class="icone" title="Suprimer le produit : '. $NomProduit .'">🗑</a>';

    return $lien ;

}



?>