<meta charset="utf-8"/>
<?php
include 'ConvertCSV.php';

$repCommandesLABO = '../../CMDLABO';


$TabCSV = array();

$Ecole_EnCOURS = '';
$Client_EnCOURS = '';
$ProduitPhoto_EnCOURS = '';
	
$TabResumeProduit = array();
$TabResumeFormat = array();

//$TTEST = '';
//echo ConvertirCMDcsvEnlab($TabCSV, 'EcoleWEB.csv');
$fichierCSV = '../../2020-11-24 groupée Maternelle Salentine 2020-2021.csv';
$fichierLAB = 'TEST.lab';

echo ConvertirCMDcsvEnlab($TabCSV,$fichierCSV , $fichierLAB);

?>

