<meta charset="utf-8"/>
<?php
include 'ConvertCSV-Lab.php';

$repCommandesLABO = '../../CMDLABO';


$TabCSV = array();

$Ecole_EnCOURS = '';
$Client_EnCOURS = '';
$ProduitPhoto_EnCOURS = '';
	
$TabResumeProduit = array();
$TabResumeFormat = array();

//$TTEST = '';
//echo ConvertirLUMYSCMDcsvEnlab($TabCSV, 'EcoleWEB.csv');
$fichierCSV = '../../2020-11-24 groupée Maternelle Salentine 2020-2021.csv';
$fichierCSV = '../../2021-04-10 TEST MAX PLANCK-NANTES-2020-2021.csv';
$fichierCSV = '../../2021-6-1 isolées.csv';
$fichierCSV = '../../Modele Livret des Ventes-NomEcole-Localité-2022-2023.csv';
$fichierCSV = '../../PIERRE Les Plantes Livret des Ventes-NomEcole-Localité-2021-2022.csv';
$fichierLAB = 'TEST.lab';



if (isFichierLumysCSV($fichierCSV)) {
    echo ' Fichier Lumys <br>' ;
    $RetourConversion = ConvertirLUMYSCMDcsvEnlab('CatalogueProduits.csv', $fichierCSV , $fichierLAB);
}else{
    echo ' Fichier Excel <br>' ;		
    $RetourConversion = ConvertirEXCELCMDcsvEnlab('CatalogueProduits.csv', $fichierCSV , $fichierLAB);
}
//echo  $fichierLAB ;


//echo ConvertirLUMYSCMDcsvEnlab($TabCSV,$fichierCSV , $fichierLAB);
//echo ConvertirEXCELCMDcsvEnlab('CatalogueProduits.csv',$fichierCSV , $fichierLAB);



function isFichierLumysCSV($fichierCSV) {
	$lines = file($fichierCSV);
	return (strpos($lines[0], 'Num de facture', 1) > 1);
	
}
?>

