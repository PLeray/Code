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



if (isset($_GET['CodeEcole'])) { $unCodeEcole = $_GET['CodeEcole'];}
if (isset($_GET['AnneeScolaire'])) { $uneAnneeScolaire = $_GET['AnneeScolaire'];}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');

$monProjetSource = new CProjetSource($unCodeEcole, $uneAnneeScolaire); 



?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/CMDCatalogueProduits.css")?>">

</head>
<body>

<?php

if($isDebug){
	echo 'un DossierScript : ' . $monProjetSource->ScriptsPS;
}


            $target_file_seul = '2022-04-10-POUR VALIDATION.lab';
            $target_file = $GLOBALS['repCMDLABO'] . "temp/".$target_file_seul . "0";
                        
            $retourMSG = '';	
            $retourMSG .= '<div id="apiReponse" class="modal">
            <div class="modal-content animate" >
                <div class="imgcontainer">
                    <a href="'.RetourEcranAfficheSources($monProjetSource).'" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
                </div>
                <h1><img src="img/logo.png" width ="80px" alt="Aide sur l\'étape" >Catalogue des produits pour ' . $monProjetSource->ScriptsPS . '</h1>';	

                echo $retourMSG;
                if (isset($_POST['PDTTaille'])) { 
                    echo '<br> PDTRecadrage > ' . $_POST['PDTRecadrage'];
                    echo '<br> PDTTaille > ' . $_POST['PDTTaille'];
                    echo '<br> PDTTransformation > ' . $_POST['PDTTransformation'];
                    echo '<br> PDTTeinte > ' . $_POST['PDTTeinte'];
                }
                ?>

<h3>Dossier de script : <?php echo $monProjetSource->ScriptsPS; ?></h3>
<div class="DefinitionProduit">
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">



<h4>Nom du produit : 
<input type="text" id="zoneTexteNomCommande" placeholder="Nom de votre commande..." value="le nom du porod" name="apiNomCommande" required>
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
                <select name="PDTRecadrage">
                    <?php          
                        echo $monProjetSource->DropListeScriptsRecadrages(); 
                    ?>
                    </select>
                    </div> 
                </td>
                <td ><div class="custom-select" style="width:180px;">
                    <select name="PDTTaille">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTailles('20x20cm'); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:250px;">
                <select name="PDTTransformation">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTransformation(); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:250px;">
                <select name="PDTTeinte">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTeinte(); 
                    ?>
                    </select>
                    </div>  
                </td>                

  
            </tr>
</table>
<a href="CATPhotolab.php' . ArgumentURL() .'" class="KO" title="Annuler">Annuler</a>

<button type="submit" class="OK">OK</button>
  </form>
</div>
      
<?php
    $retourMSG .= '	<div class="Planchecontainer">';
    $retourMSG = '<table class="TableListeProduit" >';            
    //
    $retourMSG .= '<h1>Liste des Produits</h1>';
    //$retourMSG .= BilanScriptPhotoshop($target_file);
    $TabProduits = ListeProduitsSelonCatalogue($monProjetSource->ScriptsPS);

    $retourMSG .= '<tr><td><h2>Nom du Produit</h2></td><td><h2>Code pour actions Photoshop</h2></td></tr>';	

    for($i = 1; $i < count($TabProduits); $i++){
        if ($TabProduits[$i] != '') {
            $morceau = explode(';', $TabProduits[$i]);
            $retourMSG .= '<tr><td><h3>' . $morceau[0] . '</h3></td><td><h3>' . $morceau[1] . '</h3></td></tr>';	
        }		
    }
    $retourMSG .= '</table>	';	
    $retourMSG .= '</div>';
    $retourMSG .= '</div>
                   </div>';
    echo $retourMSG;
?>

<script type="text/javascript" src="<?php Mini('js/APIDialogue.js');?>"></script>
</body>
</html>

<?php

function RetourEcranAfficheSources($monProjet){
    $RetourEcran = 'CMDAfficheSource.php' . ArgumentURL('&CodeEcole=' . $monProjet->CodeEcole . '&AnneeScolaire=' . $monProjet->AnneeScolaire) ;
	return $RetourEcran ;
}

?>




