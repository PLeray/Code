<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV-Lab.php';


$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('../debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

$maConnexionAPI = new CConnexionAPI($codeMembre, $isDebug, 'CATPhotolab');
?>
<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/Couleurs" . ($GLOBALS['isDebug']?"DEBUG":"PROD") . ".css") ?>">
<link rel="stylesheet" type="text/css" href="<?php echo strMini("css/APIDialogue.css")?>">

</head>
<body>



<?php
            $target_file_seul = '2022-04-10-POUR VALIDATION.lab';
            $target_file = $GLOBALS['repCMDLABO'] . "temp/".$target_file_seul . "0";
                        
            $retourMSG = '';	
            $retourMSG .= '<div id="apiReponse" class="modal">
            <div class="modal-content animate" >
                <div class="imgcontainer">
                    <a href="CATPhotolab.php' . ArgumentURL() . '&apiSupprimer=' . urlencode($target_file_seul) .'0" class="close" title="Annuler et retour écran général des commandes">&times;</a>				
                </div>
                <h1><img src="img/AIDE.png" alt="Aide sur l\'étape" > Etape 1 : Vérification</h1>';	

                echo $retourMSG;
                if (isset($_POST['PDTTaille'])) { 
                    echo '<br> PDTRecadrage > ' . $_POST['PDTRecadrage'];
                    echo '<br> PDTTaille > ' . $_POST['PDTTaille'];
                    echo '<br> PDTTransformation > ' . $_POST['PDTTransformation'];
                    echo '<br> PDTTeinte > ' . $_POST['PDTTeinte'];
                }
                ?>

<div class="DefinitionProduit">
<form  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">


<?php $monProjetSource = new CProjetSource('2PLANCHES', '2021-2022'); ?>


<h3>Dossier de script : <?php echo $monProjetSource->ScriptsPS; ?></h3>
<h4>Nom du produit : 
<input type="text" id="zoneTexteNomCommande" placeholder="Nom de votre commande..." value="le nom du porod" name="apiNomCommande" required>
</h4>
    <table>
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
                <td ><div class="custom-select" style="width:350px;">
                <select name="PDTTransformation">
                    <?php          
                        echo $monProjetSource->DropListeScriptsTransformation(); 
                    ?>
                    </select>
                    </div>  
                </td>
                <td ><div class="custom-select" style="width:350px;">
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




<?php 



?>
</div>

                
                <?php                






            $retourMSG = '<table>
            <tr>
                <td width="50%">';            
            $retourMSG .= '	<div class="Planchecontainer">';

			$retourMSG .= '<h1>1) Vérification des scripts Photoshop</h1>';
            $ProduitsManquant = 0;
            $retourMSG .= BilanScriptPhotoshop($target_file,$ProduitsManquant);

            $retourMSG .= '<h1>2) Vérification des photos  "Sources"</h1>'; 
			$retourMSG .= PhotosManquantes($target_file);

			$retourMSG .= '</div>';
            $retourMSG .= ' </div>';	
            $retourMSG .= '</td>
            </tr>
         </table>	';	





            $retourMSG .= '	  
            </div>
        </div>';            

    echo $retourMSG;



?>

<script type="text/javascript" src="<?php Mini('js/APIDialogue.js');?>"></script>
</body>
</html>

<?php



?>




