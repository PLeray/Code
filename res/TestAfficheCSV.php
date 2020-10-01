<meta charset="utf-8"/>
<?php
include 'ConvertCSV.php';




$TabCSV = array();

$Ecole_EnCOURS = '';
$Client_EnCOURS = '';
$ProduitPhoto_EnCOURS = '';
	
$TabResumeProduit = array();
$TabResumeFormat = array();

//$TTEST = '';
//echo ConvertirCMDcsvEnlab($TabCSV, 'EcoleWEB.csv');
$fichierCSV = 'Production Commande groupée.csv';
$fichierLAB = 'TEST.lab';

echo ConvertirCMDcsvEnlab($TabCSV,$fichierCSV , $fichierLAB);

?>

