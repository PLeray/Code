<?php
include_once 'CMDClassesDefinition.php';
include_once 'APIConnexion.php';


$CataloguePdtENCOURS = array();

$ERREUR_EnCOURS = '';

$Ecole_EnCOURS = '';
$Client_EnCOURS = '';
$ProduitPhoto_EnCOURS = '';
	
$TabResumeProduit = array();
$TabResumeFormat = array();

//echo ConvertirLUMYSCMDcsvEnlab($TabCSV, 'EcoleWEB.csv');

function ConvertirLUMYSCMDcsvEnlab($fichierCSV, &$target_file){	
	$valRetour = false;

	$TabCSV = array();
	echo '<br><br>';
	$TabCSV = csv_to_array($fichierCSV, ';');
	$isCMDUnique = is_numeric(substr($target_file ,-11,-5));
	$PrefixeTirage = '';
	
	if ($isCMDUnique){
		$NumCMD = substr($target_file ,-11,-5) ;
		$target_file = date("Y-m-d") . '-SOLO-Commande(' . $NumCMD . ').lab0';
		$PrefixeTirage = '(CMD-' . $NumCMD . ') ';
	}
	else{
		if ($TabCSV[0]["Type"] == 'groupee'){
			$target_file = date("Y-m-d") . '-L2-' . SUPRAccents($TabCSV[0]["Nom de l'ecole"]) . '-' . $TabCSV[0]["Ville ecole"] . '.lab0';	
			$PrefixeTirage = 'L2-';			
		}else { 
			if ($TabCSV[0]["Type"] == 'isolee'){
				$target_file = date("Y-m-d") . '-Commandes Isolees'. '.lab0';
				$PrefixeTirage = '(ISOLEES) ';		
			}
			else{
				$target_file = 'Erreur';	
			}
		}	
	}
	$affiche_Tableau = '';
	if ($target_file != 'Erreur'){
		foreach ($TabCSV as $key => $row) {
			$NumCommande[$key] = $row['Num de commande'];
			$NomProjet[$key] = $row['Nom du projet'];
			$NomClasse[$key] = $row['Classe'];
		}
		//$NumCommande = array_column($TabCSV, "Num de commande");
		//array_multisort($NumCommande, SORT_ASC, $TabCSV);
		// Ancien Tri : array_multisort($NomProjet, SORT_ASC, $NumCommande, SORT_ASC, $TabCSV);
		//array_multisort($NomProjet, SORT_ASC, $NumCommande, SORT_ASC, $NomClasse, SORT_ASC, $TabCSV);
		
		// On garde le tri de LUMYS / par classe ou commandes
		//array_multisort($NumCommande, SORT_ASC, $NomClasse, SORT_ASC, $TabCSV); // Tri par num commande puis classe
		$NbLignes=count($TabCSV);
		
		//Prefixe rep Tirage
		//REpérer les colonne de commandes
		$colNomDeLaPhoto = 0; // numero col Nom de la photo, la commande commence apres
		$nbColonneCommnandes = 0; // numero col Nom de la photo
		$i=0;
		$isAvantColonneNom = true;
		foreach($TabCSV[0] as $key => $value)
		{
			$i++;
			if($key == 'Nom de la photo'){$colNomDeLaPhoto = $i;}// numero col Nom de la photo, la commande commence apres
			if($key == 'Nom'){$isAvantColonneNom = false;}		
			if($colNomDeLaPhoto && $isAvantColonneNom){$nbColonneCommnandes++;}					  
			//echo $i. ' : ' . $key.'<br />';
		}		
		$nbColonneCommnandes--; //On ne prend pas le colonne Nom
		//echo '<br /> $colNomDeLaPhoto ' . $colNomDeLaPhoto.'  $nbColonneCommnandes ' . $nbColonneCommnandes.'<br /><br /> ';	

		for($i = 0; $i < $NbLignes; $i++)
		{ 
			$affiche_Tableau .= EcrireEcole($TabCSV[$i]["Nom de l'ecole"].'-'
										. strtoupper($TabCSV[$i]["Ville ecole"]) .'_'										
										. ReferenceECOLE($TabCSV[$i]["Reference école"], $TabCSV[$i]["Nom du projet"]) . '_Ecole web Lumys!'
										, $PrefixeTirage);   
										
			$affiche_Tableau .= EcrireClient($TabCSV[$i]["Num de commande"] . '_' 
										. $TabCSV[$i]["Num de facture"] . '_' 
										. $TabCSV[$i]["Prenom"] . '_' 
										. $TabCSV[$i]["Nom"] . '_' 
										. $TabCSV[$i]["Adresse1"] . '_' 								
										. $TabCSV[$i]["Adresse2"] . '_' 								
										. $TabCSV[$i]["Code postal"] . '_' 								
										. $TabCSV[$i]["Ville"]);  
										
			if ($TabCSV[$i]['Nom de la photo'] != '[Pochettes]') {	
				$affiche_Tableau .= EcrireCommande(array_slice($TabCSV[$i], $colNomDeLaPhoto, $nbColonneCommnandes,true), $TabCSV[$i]['Nom de la photo'], $TabCSV[$i]['Classe']);				
			}	
		}
		//$NbPlanches = $NbLignes;
		$affiche_Tableau = '[Version :1.0]' . '<br>'
							. '{Etat : -1 : Non enregistre %%'
							. EcrireBilanCMD(count(array_unique(array_column($TabCSV, 'Num de commande')))) . '<br>' 
							. $affiche_Tableau;
							
		
		if ($GLOBALS['ERREUR_EnCOURS'] != ''){
			//$affiche_Tableau = '';
			$valRetour = false;
			echo '<br style="background-color:#EAEFF5" />  PROBLEME : ' . $GLOBALS['ERREUR_EnCOURS'] .'</br>';
		}
		else{ // Tout va bien !
			# Chemin vers fichier texte
			$target_file =  $GLOBALS['repCMDLABO'] . $target_file ;

			$txtFichier = str_replace("<br>", "\n", $affiche_Tableau);
			$txtFichier = str_replace("&#60;", "<", $txtFichier);
			$txtFichier = str_replace("&#62;", ">", $txtFichier);

			# Ouverture en mode écriture
			$fileopen=(fopen("$target_file",'w'));
			# Ecriture de "Début du fichier" dansle fichier texte 
			fputs($fileopen,$txtFichier);
			# On ferme le fichier proprement
			fclose($fileopen);	
			$valRetour = true;
		}		

	}
	//'&#60;' . $Classe . ' ' . $key . '&#62;<br>';

	return $valRetour;  
}


function SupressionCommandesVide(){	
	//§§§§§§§§§§§§§§§§§§§§§§§§§§return $CodeEcole . '_' . substr($NOMPROJETanneeScolaire, -9);
}


function ReferenceECOLE($CodeEcole , $NOMPROJETanneeScolaire){	
	return $CodeEcole . '_' . substr($NOMPROJETanneeScolaire, -9);
}

function EcrireBilanCMD( $NbCommandes){
	try {
		$unBilan = 'Le groupe de commandes comprend ' . $NbCommandes . ' commandes.%';
		$unTab = array_count_values($GLOBALS['TabResumeProduit']);
		
		foreach ($unTab as $key => $row) {
			$unBilan .= '- ' .$key . ': ' . $unTab[$key] . '%';
		}
		//echo "erreur avant " . error_get_last();
		if ($GLOBALS['isDebug']){
			var_dump($GLOBALS['TabResumeFormat']);

		}
		$unTab = array_count_values($GLOBALS['TabResumeFormat']);
		//echo "erreur apres " . error_get_last();
		//var_dump($GLOBALS['TabResumeFormat']);
		//$unBilan .= '%%%Pour un total de ' . $NbCommandes . ' commandes individuelles%';
		$unBilan .= '%%%Il y a ' . count($GLOBALS['TabResumeProduit']) . ' fichiers a creer au laboratoire.%';
		foreach ($unTab as $key => $row) {
			$unBilan .= '- Format ' .$key . ': ' . $unTab[$key] . '%';
		}	
		$unBilan .= '}';
		//var_dump($unBilan);
		
		return $unBilan;
		//return $TabCSV;
	} catch (ErrorException $e) {
		$unBilan ='';
		return $unBilan;
	}
}

function EcrireEcole($Ecole, $PrefixeTirage){
	$valRetour = '';
	if ($Ecole != $GLOBALS['Ecole_EnCOURS']) {   
		$GLOBALS['Ecole_EnCOURS'] = $Ecole;
		//PAs suprimer accent 1-10
		//$valRetour = '@' . date("Y-m-d") . '_' . $PrefixeTirage . $Ecole . '@ <br>';
		$valRetour = '@' . date("Y-m-d") . '_' . $PrefixeTirage . SUPRAccents($Ecole) . '@';
		
		//var_dump( $valRetour) ;
		$EcoleEnCours = new CEcole($valRetour,'@');
		$monProjetSource = new CProjetSource($EcoleEnCours->CodeEcole, $EcoleEnCours->AnneeScolaire); 
		if ($monProjetSource->ScriptsPS != ''){
			$GLOBALS['CataloguePdtENCOURS'] = csv_to_array($GLOBALS['repGABARITS'] . $monProjetSource->NomCatalogue(), ';'); 
		}else{
			$GLOBALS['ERREUR_EnCOURS'] = 'Erreur : Pas de catalogue trouvé pour l\'ecole  : ' . $Ecole ;
			$GLOBALS['CataloguePdtENCOURS'] = array();
		}

		$valRetour .= ' <br>';
	}
	//return utf8_decode($valRetour);
	return $valRetour;
}

function EcrireClient($Client){
	$valRetour = '';
	if ($Client != $GLOBALS['Client_EnCOURS']){
		$GLOBALS['Client_EnCOURS'] = $Client;
		$valRetour = '#' . $Client . '# <br>';
	}
	//return utf8_decode($valRetour);
	return $valRetour;
}

function EcrireCommande($CMD, $NomPhoto, $Classe){
	$valRetour = '';
	foreach ($CMD as $key => $value) {
		$value = intval($value);
		if ($value > 0) {
			$valRetour .= '&#60;' . $Classe . '%' . $key . '&#62;<br>'; // on peut pas mettre <> !!
			for($i = 0; $i < $value; $i++)
			{ 			
				$valRetour .= EcrireProduitPhoto($NomPhoto, $key);
			}			
		}
	}
	//return utf8_decode($valRetour);
	return $valRetour;
}

function EcrireProduitPhoto($NomPhoto, $ProduitPhoto){
    $valRetour = '';
	//echo utf8_decode(strtolower($ProduitPhoto)) . '<br>';
	//echo $ProduitPhoto . '<br>';
	//var_dump( $GLOBALS['CataloguePdtENCOURS'][1]) ;
	//$leCodeProduit = $GLOBALS['CataloguePdtENCOURS'][strtolower($ProduitPhoto)];
	//$leCodeProduit = $GLOBALS['CataloguePdtENCOURS'][$ProduitPhoto];
	//echo ' sqfqsfdqsf  ' . $GLOBALS['CataloguePdtENCOURS'][0]['Description']. ' sqfqsfdqsf <br>';
	$leCodeProduit ='';
	$leCodeFormat ='';

	for($i = 0; $i < count($GLOBALS['CataloguePdtENCOURS']) ; $i++){
		//echo $GLOBALS['CataloguePdtENCOURS'][$i]['Description'] . '<br>';
		if ($GLOBALS['CataloguePdtENCOURS'][$i]['Description'] == $ProduitPhoto){
			$leCodeProduit = $GLOBALS['CataloguePdtENCOURS'][$i]['Code'];
			break; 
		}
	} 	
	//var_dump($GLOBALS['CataloguePdtENCOURS']) ;
	//echo ' KJhlkjhlkjh:kj ' . count($GLOBALS['CataloguePdtENCOURS']);	

	if (count($GLOBALS['CataloguePdtENCOURS']) > 0 ){			
		$tabCodesProduit = explode($GLOBALS['SeparateurInfoPlanche'], $leCodeProduit); // En Cas de produit multi-planches
		
		for($i = 0; $i < count($tabCodesProduit) ; $i++){
			if ($tabCodesProduit[$i]){
				///Pour classement Lumys
				if (substr($NomPhoto, -4 )== '-WEB'){$NomPhoto = substr($NomPhoto, 0, -4);}
				if (substr($NomPhoto, -7 )== '-WEB_nb'){$NomPhoto = substr($NomPhoto, 0, -7) . '_nb';}
				
				if (substr($NomPhoto ,-3 )== '_nb'){
					$valRetour .= substr($NomPhoto ,0,-3 ) . '.jpg_' . $tabCodesProduit[$i] . '_NOIR-ET-BLANC<br>';
				}
				else{
					$valRetour .= str_pad($NomPhoto, 4, "0", STR_PAD_LEFT) . '.jpg_' . $tabCodesProduit[$i] . '<br>';			
				}
				array_push($GLOBALS['TabResumeProduit'],$ProduitPhoto);
				if (! in_array($tabCodesProduit[$i], $GLOBALS['ProduitsNONLABO'])){
					$leCodeFormat = stristr($tabCodesProduit[$i] , '_', true);
					//array_push($GLOBALS['TabResumeFormat'], $leCodeFormat);
					array_push($GLOBALS['TabResumeFormat'], $leCodeFormat);	
					/*
					if ($leCodeFormat){
						
					}
					else{
						$GLOBALS['ERREUR_EnCOURS'] = 'Erreur Ecriture Produit Photo ! ajoutez : "' . $ProduitPhoto . '" dans votre catalogue produits';
						//echo ' PROBLEME  ' . $valRetour . ' PROBLEME  ' . $valRetour . '  PROBLEME  ' . $valRetour . '  PROBLEME ';
					}
					*/
						
				}
			}
		} 
	}


	return $valRetour;
}

function ConvertPDT($ProduitPhoto)
{
    $valRetour = '_A REVOIR_'. $ProduitPhoto;
	return $valRetour;
}

function SUPRAccents($str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );    
    return $str;
}

function ConvertirEXCELCMDcsvEnlab($fichierCSV, &$target_file){	
	$valRetour = false;
	//$GLOBALS['CataloguePdtENCOURS'] = csv_to_array($GLOBALS['repGABARITS'] .  $CatalogueProduits, ';'); // New 22-10
	//var_dump( $GLOBALS['CataloguePdtENCOURS']) ;
	$TabCSV = array();
	echo '<br><br>';
	$TabCSV = csv_to_array($fichierCSV, ';');
	//$isCMDUnique = is_numeric(substr($target_file ,-11,-5));
	$PrefixeTirage = '';
	// A Sup
	//var_dump( $TabCSV[0]) ;
	// A Sup
	
    $target_file = date("Y-m-d") . '-L2-' . SUPRAccents($TabCSV[0]["Nom Ecole"])  . '.lab0';	
    $PrefixeTirage = 'L2-';	


	$affiche_Tableau = '';
	if ($target_file != 'Erreur'){
		/*
        foreach ($TabCSV as $key => $row) {
			$NumCommande[$key] = $row['Num de commande'];
			$NomProjet[$key] = $row['Nom du projet'];
			$NomClasse[$key] = $row['Classe'];
		}
        */
		//$NumCommande = array_column($TabCSV, "Num de commande");
		//array_multisort($NumCommande, SORT_ASC, $TabCSV);
		// Ancien Tri : array_multisort($NomProjet, SORT_ASC, $NumCommande, SORT_ASC, $TabCSV);
		//array_multisort($NomProjet, SORT_ASC, $NumCommande, SORT_ASC, $NomClasse, SORT_ASC, $TabCSV);
		
		// On garde le tri de LUMYS / par classe ou commandes
		//array_multisort($NumCommande, SORT_ASC, $NomClasse, SORT_ASC, $TabCSV); // Tri par num commande puis classe
		$NbLignes=count($TabCSV);
		
		//Prefixe rep Tirage
		//REpérer les colonne de commandes
		$colNomDeLaPhoto = 0; // numero col Numero de la Photo, la commande commence apres
		$nbColonneCommnandes = 0; // numero col Numero de la Photo
		$i=0;
		$isAvantColonneNom = true;
		foreach($TabCSV[0] as $key => $value)
		{
			$i++;
			if($key == 'Nom de la classe'){ // numero col Nom de la photo est avant , + 1 (Classe) pour le livret des ventes
				$colNomDeLaPhoto = $i - 1;// numero col Nom de la photo est avant !!!
			}// numero col Nom de la photo, + 1 (Classe) pour le livret des ventes
			if($key == 'Nom'){$isAvantColonneNom = false;} // Ca n'arrive jamais ca pour le moment dans un livret de vente (Une colonne apres les produits)
			if($colNomDeLaPhoto && $isAvantColonneNom){$nbColonneCommnandes++;}					  
			//echo $i. ' : ' . $key.'<br />';
		}		
		//$nbColonneCommnandes--; //On ne prend pas le colonne Nom
        //$nbColonneCommnandes--; //On ne prend pas le colonne Nom  x2 ???
		echo '<br /> numéro col de NomDeLaPhoto ' . $colNomDeLaPhoto.'   nb de ColonneCommnandes ' . $nbColonneCommnandes.'<br /><br /> ';

		for($i = 0; $i < $NbLignes; $i++)
		{ 
			if ($TabCSV[$i]["Nom Ecole"] && $TabCSV[$i]['Numero de la Photo']){
				$affiche_Tableau .= EcrireEcole($TabCSV[$i]["Nom Ecole"] .'_'
											//. ($TabCSV[$i]["Code interne"] == ''?$TabCSV[$i]["Reference"]:$TabCSV[$i]["Code interne"]) . '_Ecole web !'
											. ReferenceECOLE($TabCSV[$i]["Code Ecole"], $TabCSV[$i]["Nom Ecole"]) . '_Ecole web Lumys!'
											, $PrefixeTirage);   
											
				$affiche_Tableau .= EcrireClient($TabCSV[$i]['Numero de la Photo'] . '_' 
											.  '_' 
											.  '_' 
											.  '_' 
											.  '_' 								
											.  '_' 								
											.  '_' );  
											
				if ($TabCSV[$i]['Numero de la Photo'] != '[Pochettes]') {	
					$affiche_Tableau .= EcrireCommande(array_slice($TabCSV[$i], $colNomDeLaPhoto, $nbColonneCommnandes,true), $TabCSV[$i]['Numero de la Photo'], $TabCSV[$i]['Nom de la classe']);				
				}
			}
 /* */   
		}
       
		//$NbPlanches = $NbLignes;
		$affiche_Tableau = '[Version :1.0]' . '<br>'
							. '{Etat : -1 : Non enregistre %%'
							. EcrireBilanCMD(count(array_unique(array_column($TabCSV, 'Num de commande')))) . '<br>' 
							. $affiche_Tableau;
							
		//echo $affiche_Tableau;
		
		
		if ($GLOBALS['ERREUR_EnCOURS'] != ''){
			//$affiche_Tableau = '';
			$valRetour = false;
			echo '<br style="background-color:#EAEFF5" />  PROBLEME : ' . $GLOBALS['ERREUR_EnCOURS'] .'</br>';
		}
		else{ // Tout va bien !
			# Chemin vers fichier texte
			$target_file =  $GLOBALS['repCMDLABO'] . $target_file ;
			
			
			//$txtFichier = str_replace("<br>", "\n", $affiche_Tableau);
			
			
			$txtFichier = str_replace("&#60;", "<", $affiche_Tableau);
			$txtFichier = str_replace("&#62;", ">", $txtFichier);

			$tabLigneFichier = explode("<br>", $txtFichier);
			$corLigneFichier = array();
			
			for($i = 0; $i < count($tabLigneFichier); $i++)
			{ 
				if (!((substr($tabLigneFichier[$i] ,0,1)=='#')
					&& 
					((substr($tabLigneFichier[$i + 1] ,0,1)=='#') ||(substr($tabLigneFichier[$i + 1] ,0,1)==''))
					
					)){// Si ligne d'apres est auss un client # alors la comamnde est vide
					array_push($corLigneFichier,$tabLigneFichier[$i]);
				}
			}

			# Ouverture en mode écriture
			$fileopen=(fopen("$target_file",'w'));
			# Ecriture de "Début du fichier" dansle fichier texte 
			//fputs($fileopen,$txtFichier);

			for($i = 0; $i < count($corLigneFichier); $i++)
			{ 
				fputs($fileopen, $corLigneFichier[$i].PHP_EOL );
			}
/* 
			for($i = 0; $i < count($tabLigneFichier); $i++)
			{ 
				if (!((substr($tabLigneFichier[$i] ,0,1)=='#')
						&& (  (substr($tabLigneFichier[$i + 1] ,0,1)=='#')
								|| (substr($tabLigneFichier[$i + 1] ,0,1)==''))){// Si ligne d'apres est auss un client # alors la comamnde est vide
					fputs($fileopen, $tabLigneFichier[$i].PHP_EOL );
				}
			}
*/
			# On ferme le fichier proprement
			fclose($fileopen);	
			$valRetour = true;
		}		
	}
	//'&#60;' . $Classe . ' ' . $key . '&#62;<br>';
	return $valRetour;  
}




?>

