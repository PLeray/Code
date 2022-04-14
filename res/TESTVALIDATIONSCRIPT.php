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
                <h1><img src="img/AIDE.png" alt="Aide sur l\'étape" > Etape 1 : Importer les commandes de fichiers "produits" à Créer.</h1>';	
            $retourMSG .= '<table>
            <tr>
                <td width="50%">';            
            $retourMSG .= '	<div class="Planchecontainer">
			<h1>Vérification des scripts Photoshop et des fichier source</h1>';
			//$retourMSG .= $monGroupeCmdes->tabCMDLabo;	

			// A REMETTRE !!! 
			/*$monGroupeCmdes = new CGroupeCmdes($target_file);
            $retourMSG .= $monGroupeCmdes->AffichePlancheAProduire(); 
			*/

            $mesInfosFichier = new CINFOfichierLab($target_file); 
            //$CMDAvancement ='';
            
            //$Compilateur = '';				
            $ListeDeProduits = array_keys($mesInfosFichier->TabResumeProduit);
            for($i = 0; $i < count($ListeDeProduits); $i++){
                $retourMSG .=  $ListeDeProduits[$i] . '<br>';

            }
            //var_dump($mesInfosFichier->TabResumeProduit) ;


			
            $retourMSG .= '<br><br><br><br>Photos manquantes : 0 PierrePierrePierrePierrePierrePierre ';
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