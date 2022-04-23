<?php
include_once 'APIConnexion.php';
include_once 'CATFonctions.php';
include_once 'ConvertCSV.php';

//$repCommandesLABO = "../../CMDLABO/";

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
            $retourMSG .= '<table>
            <tr>
                <td width="50%">';            
            $retourMSG .= '	<div class="Planchecontainer">';

			$retourMSG .= '<h1>1) Vérification des scripts Photoshop</h1>';
            $retourMSG .= BilanScriptPhotoshop($target_file);

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






</body>
</html>

<?php


function BilanScriptPhotoshop($target_file){
    $mesInfosFichier = new CINFOfichierLab($target_file); 
    $resultat = '<table width="100%">';  

    $ListeDeProduits = array_keys($mesInfosFichier->TabResumeProduit);
    for($i = 0; $i < count($ListeDeProduits); $i++){
        $resultat .=  '<tr class="StyleKO"><td width="80%">' . $ListeDeProduits[$i] . '</td ><td width="20%">' . LienEditionProduit($ListeDeProduits[$i]). '</td ></tr>';

    }
    $resultat .= '</table>';
    return $resultat;
}

function LienEditionProduit($leProduit){

    $resultat = ' KO' ;  

    return $resultat;
}




function PhotosManquantes($target_file){
    $NombrePhotosManquante = 0;
    $resultat = ''; 
    $monGroupeCmdes = new CGroupeCmdes($target_file);
    $maListeDeFichier = $monGroupeCmdes->ListeFichiersSourcesManquants();

    if ($maListeDeFichier != ''){
        $TableauFichiersManquants = explode($GLOBALS['SeparateurInfoPlanche'], $maListeDeFichier);    
        for($i = 0; $i < count($TableauFichiersManquants); $i++){
            if ($TableauFichiersManquants[$i] != '') {
                $NombrePhotosManquante += 1;
                $resultat .= $TableauFichiersManquants[$i] . '<br>';	
            }		
        }
    }
    $resultat = '<span class="Style'.(($NombrePhotosManquante)?'KO':'OK').'"> Photos manquantes : ' . $NombrePhotosManquante .'<br>' . $resultat .'</span>';
    return $resultat;
}

?>




