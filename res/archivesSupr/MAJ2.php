<?php 

$url = 'https://photolab-site.fr/installation/PhotoLab/Code/installationFICHIERS.txt';



// Lit une page web dans un tableau.
$lines = file('https://photolab-site.fr/installation/PhotoLab/Code/installationFICHIERS.txt');

// Affiche toutes les lignes du tableau comme code HTML, avec les numéros de ligne
foreach ($lines as $line_num => $line) {
    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
}











$url = 'https://waytolearnx.com/wp-content/uploads/2018/09/cropped-logoWeb.png'; 
$url = 'http://localhost/API_photolab/installation/PhotoLab/Code/telechargement.zip';
$url = 'https://photolab-site.fr/installation/PhotoLab/Code/telechargement.zip';



$fichier_contenu = file_get_contents($url);


$fichier_nom = basename($url);
$dossier_enregistrement = "../telechargement/";



echo $fichier_contenu;

if(file_put_contents($dossier_enregistrement . $fichier_nom, $fichier_contenu)) 
{ 
    echo "Fichier téléchargé avec succès"; 
} 
else 
{ 
    echo "Fichier non téléchargé"; 
} 


?> 