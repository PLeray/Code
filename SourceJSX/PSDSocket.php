<?php
//$urlAPI = 'http://photoprod.000webhostapp.com/API_photolab.php';
$urlAPI = 'http://localhost:80/API_photolab/API_photolab.php';
if (isset($_GET['apiCMDLAB'])) { // Renvoie les planches  générer du fichier lab en parametre
    $apiCMDLAB = $_GET['apiCMDLAB'];
	$url = $urlAPI . '?apiCMDLAB=' . $apiCMDLAB ;
	echo file_get_contents($url);

}
/*
if (isset($_GET['apiTEST'])) { // Test connexion l'API
    $apiTEST = $_GET['apiTEST'];
	$url = $urlAPI . '?apiTEST=' . $apiTEST ;
	echo file_get_contents($url);	
} 
*/
?>