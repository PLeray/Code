////////////////////////////// LES FONCTIONS OUTILS //////////////////////////////////////////////
#include PSDFonctionsDatas.js;

var scriptPoincon = 'POINCON-S²';

function trimPSL (str) { // car leContenu.trim(); ne fonctionne sou pshp
    return str.replace(/^\s+/,'').replace(/\s+$/,'');
}


function OuvrirFichierToTableauDeLigne(file) {
	var tabPlanchesLabo = [];
	if (file.open("r")){
		var leContenu = "";
		g_TabLigneOriginale.length = 0; // = [];
		while(!file.eof){
			leContenu = file.readln();
			//alert('API CONTENU : \n' + leContenu.substr(0,2));
			if (leContenu != ""){
				if (leContenu.substr(0,2) != "//"){
					g_TabLigneOriginale.push(trimPSL(leContenu));
					//alert('API CONTENU IN : \n' + leContenu);	
				}
			}			
		}
		file.close();
		//alert(TableauTOStr(g_TabLigneOriginale));
		var retourAPI = APIphotolab('?apiCMDLAB=' + file.name);
		//retourAPI.substr(0,2)
		//alert('retourAPI.substr(0,2) ' + retourAPI.substr(0,2));	
		if 	(retourAPI.substr(0,2) == "OK") {// tout va bien
			tabPlanchesLabo = retourAPI.substr(2,retourAPI.length-2).split(sepRetourLigne);
			//alert('API CONTENU : \n' + tabPlanchesLabo);
		}
		else {
			//alert (retourAPI, "APIphotolab Erreur !", true)
			MsgLOGInfo("APIphotolab Erreur !", retourAPI);
			
			tabPlanchesLabo.length = 0; // = [];
		}
	}

	return tabPlanchesLabo;
}

function isDroitCompiler(fileName) {
	var monOrdi = '';
	var leCompilateur = '';
	var droitCompiler = false;
	if (isFichierExiste(fileName)){
		var file = new File(fileName);
		file.open("r"); // open file with write access
			leCompilateur = file.readln();
			//alert ('file.readln() : ' + leCompilateur + ' fileName : ' + fileName);
			leCompilateur = leCompilateur.substr(15,-1);
		file.close();
	
		if (leCompilateur == ''){
			file = new File(fileName);
			file.encoding='UTF-8';
			file.open("w");					
				leCompilateur = g_CeCalculateur;
				file.writeln('[Version : 2.0]%' + g_CeCalculateur + '%');
			file.close();				
		}
		droitCompiler = (leCompilateur == g_CeCalculateur) ? true : false;		
	}
	else{
		droitCompiler = true;		
	}
	//alert ('isDroitCompiler : ' + droitCompiler);	
	return droitCompiler;
}

function isCMDEnregistree(fileName) {
	// Code MD5 enregistrement serveur
	var monOrdi = '';
	var leCompilateur = '';
	var droitCompiler = false;
	/*if (isFichierExiste(fileName)){
		var file = new File(fileName);
		file.open("r"); // open file with write access
			leCompilateur = file.readln();
			//alert ('file.readln() : ' + leCompilateur + ' fileName : ' + fileName);
			leCompilateur = leCompilateur.substr(15,-1);
		file.close();
	
		if (leCompilateur == ''){			
			file = new File(fileName);
			file.encoding='UTF-8';
			file.open("w");					
				leCompilateur = g_CeCalculateur;
				file.writeln('[Version : 2.0]%' + g_CeCalculateur + '%');
			file.close();				
		}
		droitCompiler = (leCompilateur == g_CeCalculateur) ? true : false;		
	}
	else{
		droitCompiler = true;		
	}*/
	//alert ('isDroitCompiler : ' + droitCompiler);	
	droitCompiler = true;
	return droitCompiler;
}

function RecupNomOrdi() {	
	if (g_CeCalculateur == ''){
				
		var fileName = 'C:\\PhotoLab-Plugin.ini';
//alert ('g_CeCalculateur : ' + g_CeCalculateur);	
		if (isFichierExiste(fileName)){			
			var file = new File(fileName);
			file.open("r"); // open file with write access
				g_CeCalculateur = file.readln();
//alert ('PhotoLab Plugin utilise ' + g_CeCalculateur);
				//PHOTOLAB.text =  'PHOTOLAB PLUGIN ' + g_Version + ' [' + g_CeCalculateur + ']';
			file.close();
		}
		else{
			//alert ('Pas de Calculateur !');
			g_CeCalculateur = 'Defaut';
		}
	}
//alert ('g_CeCalculateur : ' + g_CeCalculateur);		
}	

function SauverFichierFromTableauDeLigne(fileName,numEtatCompil) {
	//Fchier etat/ lab1 ou lab2 ou web1 web2
	var fileName = fileName.substr(0,fileName.length-1); // lab0 >> lab1
	fileName = g_SelectFichierLab.path + '/' + fileName + numEtatCompil; // + '1' : Etat les planches de la commande sont EN COURS (16-11)
	//alert('TESTY01  SauverFichierFromTableauDeLigne ' +  TableauTOStr(g_TabLigneOriginale));
	var file = new File(fileName);
	file.encoding='UTF-8';
	file.open("w"); // open file with write access
		for (var n = 0; n < g_TabLigneOriginale.length; n++) {	
	
			switch(n) {
			case 0:

				file.writeln('[Version : 2.0]%' + g_CeCalculateur + '%');
				break;
			case 1:
				var recuRESUME = g_TabLigneOriginale[n];					
				if (numEtatCompil == 1) {
					file.writeln('{Etat 1 :' + g_CommandeAVANCEMENT + recuRESUME.substr(recuRESUME.indexOf('%%')));						
				}
				else{
					file.writeln('{Etat 2 : Création des planches terminées' + recuRESUME.substr(recuRESUME.indexOf('%%')));
					
				}
				break;
			default:
				
				file.writeln(g_TabLigneOriginale[n]);
			} 
		}		
	file.close();

	
	MsgLOGInfo('PLANCHES PRETES !   Les commandes sont visionables dans le gestionnaire  Web PHOTOLAB');

	return true;
}
/*
function SauverEtatFichier(fileName, encoursFichier, totalFichier) {
	var fileName = fileName.substr(0,fileName.length-1); // lab0 >> lab1
	fileName = g_SelectFichierLab.path + '/' + fileName + '1'; // + '1' : Etat les planches de la commande sont créees
	var file = new File(fileName);
	file.encoding='UTF-8';
	file.open("w"); // open file with write access
		file.readln();
		file.writeln(encoursFichier + ' / ' + totalFichier);
		//file.writeln('{Etat : ' + encoursFichier + ' / ' + totalFichier);	
	file.close();
	return true;
}
*/
function EcrireErreursBilan(fileName) {
	var nbErreur = g_BilanGeneration.length;
	//alert('EcrireErreursBilan(fileName)' + fileName);
	if (nbErreur > 0){
		var fileName = fileName.substr(0, fileName.length-4); 
		fileName = g_SelectFichierLab.path + '/' + fileName + 'Erreur'; 
		var file = new File(fileName);
		file.encoding='UTF-8';
		file.open("w"); // open file with write access
			for (var n = 0; n < g_BilanGeneration.length; n++) {
				file.writeln(g_BilanGeneration[n]);
				MsgLOGInfo("ERREUR " + (n + 1), g_BilanGeneration[n]);
			}		
		file.close();
	}
	return nbErreur;	
}

/////////////// Pour Action sur BOUTON TEST ///////////////////////////////////////
function TEST() {
	//var myName = myInput ();
	//alert('myName : ' + myName);
	Init();

}
////////////////////////////////////////////////////////////////////////////////////

/////////////// Pour Action sur BOUTON Classes ///////////////////////////////////////
function Classes() {
	alert('TEST Indiv / Groupe : ' + TestIndivPhotoDeGroupe());
}
////////////////////////////////////////////////////////////////////////////////////

/////////////// Pour Action sur BOUTON Info ///////////////////////////////////////
function InfoAPI() {
	var retourAPI = APIphotolab('?apiCMDLAB=' + encodeURI(g_SelectFichierLab.name));
	alert('retourAPI.substr(2) : ' + retourAPI.substr(2));
	var tabPlanchesLabo = [];
	var isAPIOK = TestAPI();
	if 	(isAPIOK){
		if 	(retourAPI.substr(0,2) == "OK") {// tout va bien
			//alert('retourAPI.substr(2,retourAPI.length-2) : ' + retourAPI.substr(2,retourAPI.length-2));
			tabPlanchesLabo = retourAPI.substr(2,retourAPI.length-2).split(sepRetourLigne);
			//alert('API Connexion : ' + isAPIOK + '\n Retour API : \n' + TableauTOStr(tabPlanchesLabo));
		}
	}
}
////////////////////////////////////////////////////////////////////////////////////

function OuvrirPhotoSource(unFichierPhoto){
	//var leFichierPhotoOK = new File(g_RepSOURCE + "/" + unFichierPhoto); 
	var leFichierPhotoOK = g_RepSOURCE + "/" + unFichierPhoto; 	
	try {
		//if(leFichierPhotoOK.exists){
		if(isFichierExiste(leFichierPhotoOK)){ 
			var laPhoto = app.open(File(leFichierPhotoOK));
			return laPhoto;		
		} else {
			//alert ( "Le fichier exiterait pas : \n\n" + leFichierPhotoOK);			
			var theFolder = new Folder(g_RepSOURCE);
			//alert ( "TrouverFichierSource : \n\n" + leFichierPhotoOK);			
			leFichierPhotoOK = ChercherSourcePhoto(theFolder, '', unFichierPhoto);
			//alert ( "leFichierPhotoOK : \n\n" + leFichierPhotoOK);	
			var laPhoto = app.open(File(leFichierPhotoOK));
			return laPhoto;	
		}						
	}
	catch(err) {
		var msg = 'Ecole en cours : ' + g_CommandeECOLEEncours;
		AjoutBilanGeneration(msg);
		msg = '  Commande en cours : ' + g_CommandePDTEncours;
		AjoutBilanGeneration(msg);
		msg = '     PROBLEME : Ouverture de la photo : ' + unFichierPhoto;		
		AjoutBilanGeneration(msg);
		msg = "     SOLUTION PROBABLE : vérifier que le fichier : " + unFichierPhoto + " existe bien dans le dossier SOURCE de l'ecole !";	
		msg = "                       dossier SOURCE de l'ecole : " + g_RepSOURCE;	
				AjoutBilanGeneration(msg);		
		AjoutBilanGeneration('');
		g_Erreur = msg;
		return null;
	}
}

//function ChercherSourcePhoto(theFolder, unObjFichierPhoto) {   
function ChercherSourcePhoto(theFolder, FichierTrouve, nomFichierATrouver) {    
   //var FichierRech = '';
   var theContent = theFolder.getFiles();
   for (var n = 0; n < theContent.length; n++) {
      var theObject = theContent[n];
      if (theObject.constructor.name == "Folder") {
	  //alert ( "nomFichierATrouver : " + nomFichierATrouver + "\n\n dansrep rep : " + theObject);
         FichierTrouve = ChercherSourcePhoto(theObject, FichierTrouve, nomFichierATrouver);
      }		
      if (theObject.name == encodeURI(nomFichierATrouver)) {
	  //alert ( "theObject.name : \n\n" + theObject.path + '/' + theObject.name + " \n\n    nomFichierATrouver : " + nomFichierATrouver );
         //alert ( "FichierTrouve !!!!!!!!!!!!!!!!! " + FichierTrouve);
		 FichierTrouve = theObject.path + '/' + theObject.name ;
		break;
      }
   }
   return FichierTrouve;
}

/*
function TrouverFichierSource(unFichierPhoto) {
	alert ( "TrouverFichierSource : \n\n" + leFichierPhotoOK);
	var FichierRech = '';
	var theFolder = new Folder(g_RepSOURCE);

	FichierRech = ChercherSourcePhoto(theFolder, unFichierPhoto);
	alert ( "Le fichier trouvé : \n\n" + FichierRech)
	return FichierRech ;
}
*/


function Action_Script_PhotoshopPSP(N_action){
	try {
		/**/
        if( N_action != ""){
			var idPly = charIDToTypeID( "Ply " );
			var desc63 = new ActionDescriptor();
			var idnull = charIDToTypeID( "null" );
			var ref8 = new ActionReference();

			var idActn = charIDToTypeID( "Actn" );
			ref8.putName( idActn, N_action );

			var idASet = charIDToTypeID( "ASet" );
			ref8.putName( idASet, g_RepSCRIPTSPhotoshop);

			desc63.putReference( idnull, ref8 );
			executeAction( idPly, desc63, DialogModes.NO );
            //alert("N_action.sortie  : " + N_action);
		}
		return true;
	}
	catch(err) {
		var msg = 'Ecole en cours : ' + g_CommandeECOLEEncours;
		AjoutBilanGeneration(msg);
		msg = '  Commande en cours : ' + g_CommandePDTEncours;
		AjoutBilanGeneration(msg);
		msg = "     PROBLEME : Pas de script Photoshop (Action) nommé : " + N_action + " dans : '" + g_RepSCRIPTSPhotoshop + "'";
		AjoutBilanGeneration(msg);
		msg = "     SOLUTION PROBABLE : Ajouter un script Photoshop (Action) nommé : " + N_action + " dans : '" + g_RepSCRIPTSPhotoshop + "'";	
		AjoutBilanGeneration(msg);
		AjoutBilanGeneration('');		
		g_Erreur = msg;
		return false;			
	}
}

function Miniature_Reduction(pourcent){
	// =================Reduction à 10 % ======================================
	var idImgS = charIDToTypeID( "ImgS" );
	var desc10 = new ActionDescriptor();
	var idWdth = charIDToTypeID( "Wdth" );
	var idPrc = charIDToTypeID( "#Prc" );
	desc10.putUnitDouble( idWdth, idPrc, pourcent );
	var idscaleStyles = stringIDToTypeID( "scaleStyles" );
	desc10.putBoolean( idscaleStyles, true );
	var idCnsP = charIDToTypeID( "CnsP" );
	desc10.putBoolean( idCnsP, true );
	var idIntr = charIDToTypeID( "Intr" );
	var idIntp = charIDToTypeID( "Intp" );
	var idBcbc = charIDToTypeID( "Bcbc" );
	desc10.putEnumerated( idIntr, idIntp, idBcbc );
	executeAction( idImgS, desc10, DialogModes.NO );
}

function ImporterAutrePhoto(PathNomAutrePhoto){
	try {
		/**/
        //if( isFichierExiste(PathNomAutrePhoto)){
			// ==============Importation Deuxieme image ======================================	
			var idPlc = charIDToTypeID( "Plc " );
			var desc2 = new ActionDescriptor();
			var idnull = charIDToTypeID( "null" );
			desc2.putPath( idnull, new File(PathNomAutrePhoto) );
			var idFTcs = charIDToTypeID( "FTcs" );
			var idQCSt = charIDToTypeID( "QCSt" );
			var idQcsa = charIDToTypeID( "Qcsa" );
			desc2.putEnumerated( idFTcs, idQCSt, idQcsa );
			var idOfst = charIDToTypeID( "Ofst" );
			var desc3 = new ActionDescriptor();
			var idHrzn = charIDToTypeID( "Hrzn" );
			var idRlt = charIDToTypeID( "#Rlt" );
			desc3.putUnitDouble( idHrzn, idRlt, 0.000000 );
			var idVrtc = charIDToTypeID( "Vrtc" );
			var idRlt = charIDToTypeID( "#Rlt" );
			desc3.putUnitDouble( idVrtc, idRlt, 0.000000 );
			var idOfst = charIDToTypeID( "Ofst" );
			desc2.putObject( idOfst, idOfst, desc3 );
			executeAction( idPlc, desc2, DialogModes.NO );
		//}
		return true;
	}
	catch(err) {
		var msg = 'Ecole en cours : ' + g_CommandeECOLEEncours;
		AjoutBilanGeneration(msg);
		msg = '  Commande en cours : ' + g_CommandePDTEncours;
		AjoutBilanGeneration(msg);
		msg = "     PROBLEME : Pas de fichier Gabarit nommé : " + PathNomAutrePhoto.split('\\').pop().split('/').pop() + " dans : '" + g_Rep_GABARITS + "'";
		AjoutBilanGeneration(msg);
		msg = "     SOLUTION PROBABLE : Ajouter fichier Gabarit nommé : " + PathNomAutrePhoto.split('\\').pop().split('/').pop() + " dans : '" + g_Rep_GABARITS + "'";	
		AjoutBilanGeneration(msg);
		AjoutBilanGeneration('');		
		g_Erreur = msg;
		return false;			
	}

}

function AjoutBilanGeneration(msg){
	if (g_BilanGeneration.length == 0){
		//alert ('msg_Erreur ' + msg);
		PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush(PHOTOLAB.graphics.BrushType.SOLID_COLOR, [1, 0.2, 0.2]);	
	}
	g_BilanGeneration.push(msg);
	//EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));
	//SuiteERREURGenerer();	
}

/**/
function SuiteERREURGenerer() { 
	g_IsGenerationEnPause = !g_IsGenerationEnPause;	
	SetBoutonGenerer();
	if (!g_IsGenerationEnCours){ //  On n'est pas en cours de generation !!!
		GenererFichiers();
		g_IsGenerationEnPause = false;	
	}		
}

function CreerUnProduitPourLeLaboratoire(unProduit){
	var nomFichierPhoto = unProduit.FichierPhoto;
	var valRetour = nomFichierPhoto;	
	//alert('QQQ003 nouveau  nomFichierPhoto ' + nomFichierPhoto);
	if (nomFichierPhoto.substr(4, 6) == '-QCoin'){   //2012-QCoinD-WEB.jpg	
		valRetour = CreerUnProduitQUATTROPourLeLaboratoire(unProduit);
	}
	else{
		var unNomdePlanche = NomPlancheLabo(unProduit, nomFichierPhoto);
		//alert('QQQ004 nouveau unNomdePlanche ' + unNomdePlanche);
		var unPathPlanche = g_RepTIRAGES_DateEcole + "/" + unProduit.Taille + " (1ex de chaque)/" + unNomdePlanche;
		var unPathMiniature = g_RepMINIATURES_DateEcole + "/" + unProduit.Taille + " (1ex de chaque)/" + unNomdePlanche;
		if(!isDEJAPlancheJPGDossierTirage(unNomdePlanche)){
		//if(!isFichierExiste(unPathPlanche)){
			try {
				//alert('CreerUnProduitPourLeLaboratoire \n Code de unProduit ' + unProduit.Code);

				//alert('TESTZ50 DEBUT CreerUnProduitPour : ' + nomFichierPhoto + ' // Code de unProduit ' + unProduit.Code); //////////////////////////////////////////////
				if (unProduit.Code){
					if (unProduit.FichierPhoto.length && unProduit.isNeedGroupeClasse()){//Ouvrir la bonne photo ? Groupe
						//alert('sdsdsdsdur : ' + nomFichierPhoto ); //////////////////////////////////////////////
						nomFichierPhoto = GroupeClassePourIndiv(unProduit);
						//alert('nomFichierPhoto : ' + nomFichierPhoto);
					}
					if (unProduit.Type.indexOf('QUATTRO') > -1){ //Produit QUATTRO Besoin du fichier Quatro !!
						//alert('Pour CADRE-QUATTRO : ' + nomFichierPhoto + ' Sera ' + NextQuattro(nomFichierPhoto) ); 
						nomFichierPhoto = NextQuattro(nomFichierPhoto);										
					}	
					
					if (unProduit.Type.indexOf('IDENTITE') > -1){ //Produit IDENTITE Besoin du fichier Identite !!
						nomFichierPhoto = FichierIdentite(nomFichierPhoto);										
					}					
					var laPhoto = OuvrirPhotoSource(nomFichierPhoto); 	
					var reussiteTraitement = (laPhoto != null);	
					if (reussiteTraitement) {
						//var docName = laPhoto.name;
						//var basename = docName.match(/(.*)\.[^\.]+$/)[1];
						//var docPath = laPhoto.path;		SUPRESSION 17/11/2020 ??!!						
						////////  Cas des fratrie ou Indiv en paysage =>> Portrait /////////
						//var isFratrie = false;
						var myDocument = app.activeDocument; 
						var aRetourner = false;
						//if (unProduit.isFichierIndiv() && !unProduit.isProduitGroupe()) {
							if (myDocument.width > myDocument.height) { 
								//alert('rotateCanvas' ); 						
								aRetourner = true;
								myDocument.rotateCanvas(90);  
							}  
						//}	
						//alert('TRANSFORMATIONS teinte ; ") ' + unProduit.Teinte );
						//////////////// VERIF-DPI //////////////////////
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('300DPI');				
						//////////////// TRANSFORMATIONS //////////////////////
						// 1 : LA TEINTE  DE L'IMAGE /////////////////////////
						if (unProduit.Teinte != "Couleur" && !unProduit.isSansNB()){
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Teinte);
							//Raffraichir();  AVOIR new 27-08
						}
						
						// CADRE-CARRE-ID !!!!!!!!!
						if (unProduit.Type.indexOf('CADRE-CARRE-ID') > -1){ //Produit CARRE-ID Besoin du fichier ID !!							
							//reussiteTraitement = reussiteTraitement && 
							ImporterAutrePhoto(g_RepSOURCE + "/" + FichierIdentite(nomFichierPhoto));					
						}	
						// INSITU-CARRE-ID !!!!!!!!!
						if (unProduit.Type.indexOf('INSITU-CARRE-ID') > -1){ //Produit CARRE-ID Besoin du fichier ID !!							
							//reussiteTraitement = reussiteTraitement && 
							ImporterAutrePhoto(g_RepSOURCE + "/" + FichierIdentite(nomFichierPhoto));					
						}								
						// IMPORT FOND D'ici
						if (unProduit.Type.substr(0, 3).indexOf('png') > -1){ //Produit CARRE-ID Besoin du fichier ID !!							
							reussiteTraitement = reussiteTraitement && 
							ImporterAutrePhoto(g_Rep_GABARITS + g_RepSCRIPTSPhotoshop + '/' + unProduit.Type + '.png');			
							
							
							g_RepSCRIPTSPhotoshop = TrouverRepScriptPSdansBibliotheque(uneEcole);



						}							
						// 3 : LE TYPE DE PRODUIT / IMAGE ////////////////////
						if (unProduit.Type != "PORTRAIT" 
							&& unProduit.Type != "PANO" 
							&& unProduit.Type != "TRAD" 
							&& unProduit.Type != "CUBE" 
							&& unProduit.Type != "RUCH"						
							&& unProduit.Type != "CADRE-GP" // utilité ???
							&& unProduit.Type != "INSITU-GP"
							&& unProduit.Type != "" ){// NEW PSL OCTOBRE 2021	
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Type);
							//Raffraichir(); AVOIR new 27-08
						}
						/*if (unProduit.Type != "PORTRAIT" 
							&& unProduit.isTypeGroupe() == false){
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Type);
							Raffraichir(); 
						}*/					
						// 2 : Si Portait LA TAILLE DE L'IMAGE FINALE ///////////////////
						if (g_RepSCRIPTSPhotoshop == 'PHOTOLAB-2022-Cadre-Studio2'){ // QQue pour Studio² !!!						
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(scriptPoincon);							
						}


						if (myDocument.width > myDocument.height) { 
							//alert('re re tourne 90');
							myDocument.rotateCanvas(90);
							//alert('re re tourne Taille');
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Taille);
							//alert('re re tourne -90');
							myDocument.rotateCanvas(-90);
						} else{
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Taille);
						}

						//reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Taille);
						
						/*else{ // Sinon !!!		
							// A REVOIR !!!!!!!		
							if ((unProduit.Type.lastIndexOf("PORTRAIT") > -1)||(unProduit.Type.lastIndexOf("TRAD") > -1)){ 
								//alert('lastIndexOf("Agrandissements") ');
								reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Taille);		
							}
						}	
						*/
						reussiteTraitement = reussiteTraitement && CreerUnDossier(g_RepTIRAGES_DateEcole + "/"+ unProduit.Taille + " (1ex de chaque)");
						reussiteTraitement = reussiteTraitement && CreerUnDossier(g_RepMINIATURES_DateEcole + "/"+ unProduit.Taille + " (1ex de chaque)");
						
						if (reussiteTraitement){
							// Pour avoir des planches homogenes dans le viewer de commandes					
							myDocument = app.activeDocument; 
							//if (unProduit.isFichierIndiv() && !unProduit.isProduitGroupe()) { 
							//
							if (aRetourner) {
								//alert('aRetourner'); 
								myDocument.rotateCanvas(-90);
							} 
								
								//if (myDocument.width > myDocument.height) { myDocument.rotateCanvas(-90);}  
							//} 
							//La sauvegarde ...						
							SauvegardeJPEG(laPhoto, unPathPlanche);
							
							// Ici Faire 10%
							Miniature_Reduction(10.000000); // Soit 10 %
							SauvegardeJPEG(laPhoto, unPathMiniature);

							laPhoto.close(SaveOptions.DONOTSAVECHANGES);						

							valRetour = unNomdePlanche;
						}
						else {
							laPhoto.close(SaveOptions.DONOTSAVECHANGES);
							valRetour = "KO";
						}
					}
					else { valRetour = "KO";}
				}
				return valRetour;
			}
			catch(err) {
				g_Erreur = "Commande  : " + g_CommandePDTEncours + " ERREUR ds CreerUnProduitPourLeLaboratoire pour : " + nomFichierPhoto;
				laPhoto.close(SaveOptions.DONOTSAVECHANGES);
				return "KO";
			}
		}		
	}
	return valRetour;
}

function CreerUnProduitQUATTROPourLeLaboratoire(unProduit){
	var nomFichierPhoto = unProduit.FichierPhoto;
	
	//Recadrage à faire
	var leRECADRAGE = 'Portrait-' + nomFichierPhoto.substr(10, 1);
	//alert('QQQ000  : ' + leRECADRAGE +  ' pour le nom photo : ' + nomFichierPhoto);

	var nomFichierPhoto = nomFichierPhoto.substr(0,4) + '.jpg';
	
	var unNomdePlanche = NomPlancheLabo(unProduit, unProduit.FichierPhoto);
	//alert('QQQ004 nouveau unNomdePlanche ' + unNomdePlanche);
	var valRetour = unNomdePlanche;
	var unPathPlanche = g_RepTIRAGES_DateEcole + "/" + unProduit.Taille + " (1ex de chaque)/" + unNomdePlanche;
	var unPathMiniature = g_RepMINIATURES_DateEcole + "/" + unProduit.Taille + " (1ex de chaque)/" + unNomdePlanche;
	
	//alert('TESsdfsd isDEJAPlancheJPGDossierTirage(unNomdePlanche) : ' + isDEJAPlancheJPGDossierTirage(unNomdePlanche) + ' ' + unNomdePlanche);
	if(!isDEJAPlancheJPGDossierTirage(unNomdePlanche)){
	//if(!isFichierExiste(unPathPlanche)){
		try {
			//alert('CreerUnProduitPourLeLaboratoire \n Code de unProduit ' + unProduit.Code);

			//alert('TESTZ50 DEBUT CreerUnProduitPour : ' + nomFichierPhoto ); //////////////////////////////////////////////
			if (unProduit.Code){
				if (unProduit.FichierPhoto.length && unProduit.isNeedGroupeClasse()){//Ouvrir la bonne photo ? Groupe
					//alert('sdsdsdsdur : ' + nomFichierPhoto ); //////////////////////////////////////////////
					nomFichierPhoto = GroupeClassePourIndiv(unProduit);
					//alert('nomFichierPhoto : ' + nomFichierPhoto);
				}
				if (unProduit.Type.indexOf('QUATTRO') > -1){ //Produit QUATTRO Besoin du fichier Quatro !!
					//alert('Pour CADRE-QUATTRO : ' + nomFichierPhoto + ' Sera ' + NextQuattro(nomFichierPhoto) ); 
					leRECADRAGE = 'KO';									
				}	
				if (unProduit.Type.indexOf('Portrait-') > -1){ //Produit QUATTRO Besoin du fichier Quatro !!
					//alert('Pour CADRE-QUATTRO : ' + nomFichierPhoto + ' Sera ' + NextQuattro(nomFichierPhoto) ); 
					leRECADRAGE = 'KO';									
				}					
				
				/**/
				if (unProduit.Type.indexOf('IDENTITE') > -1){ //Produit IDENTITE Besoin du fichier Identite !!
					leRECADRAGE = 'Portrait-A';									
				}					
				
				//alert('Avant OUverture CreerUnProduitPour : ' + nomFichierPhoto ); //////////////////////////////////////////////
				var laPhoto = OuvrirPhotoSource(nomFichierPhoto); 	
				var reussiteTraitement = (laPhoto != null);	
				if (reussiteTraitement) {
					var docName = laPhoto.name;
					//var basename = docName.match(/(.*)\.[^\.]+$/)[1];
					//var docPath = laPhoto.path;		SUPRESSION 17/11/2020 ??!!						
					////////  Cas des fratrie ou Indiv en paysage =>> Portrait /////////
					var isFratrie = false;
					var myDocument = app.activeDocument; 
					if (unProduit.isFichierIndiv() && !unProduit.isProduitGroupe()) {
						if (myDocument.width > myDocument.height) { 
							//alert('rotateCanvas' ); 						
							isFratrie = true;
							myDocument.rotateCanvas(90)  
						}  
					}	

					//alert('TRANSFORMATIONS teinte ; ") ' + unProduit.Teinte );
					//////////////// VERIF-DPI //////////////////////
					reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('300DPI');	
					//HACK pour passer au quattro reel dans LR donc si taille >7204 (69cm) on rogne	à 61cm
					//alert('myDocument.width : ' + myDocument.width );
					if (myDocument.width > 7300) {reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('ROGNAGE-61CM');} 
														
					
					// CADRE-CARRE-ID !!!!!!!!!
					if (unProduit.Type.indexOf('CADRE-CARRE-ID') > -1){ //Produit CARRE-ID Besoin du fichier ID !!							
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('Extract-ID');					
					}						
					// CADRE-PHOTOMATON !!!!!!!!!
					if (unProduit.Type.indexOf('CADRE-PHOTOMATON') > -1){ //Produit CARRE-PHOTOMATON Besoin du fichier QUATTRO !!							
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('Extract-Quattro');					
					}	

					if (leRECADRAGE != 'KO'){
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(leRECADRAGE);	
					}	
					
					//////////////// TRANSFORMATIONS //////////////////////
					// 1 : LA TEINTE  DE L'IMAGE /////////////////////////
					if (unProduit.Teinte != "Couleur" && !unProduit.isSansNB()){
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Teinte);
					}
					
					// IMPORT FOND D'ici
					if (unProduit.Type.substr(0, 3).indexOf('png') > -1){ //Produit CARRE-ID Besoin du fichier ID !!							
						//reussiteTraitement = reussiteTraitement && 
						reussiteTraitement = reussiteTraitement && ImporterAutrePhoto(g_Rep_GABARITS + g_RepSCRIPTSPhotoshop + unProduit.Type + '.png');					
					}

					// 3 : LE TYPE DE PRODUIT / IMAGE ////////////////////
					if (unProduit.Type != "PORTRAIT" 
						&& unProduit.Type != "PANO" 
						&& unProduit.Type != "TRAD" 
						&& unProduit.Type != "CUBE" 
						&& unProduit.Type != "RUCH"						
						&& unProduit.Type != "CADRE-GP" // utilité ???
						&& unProduit.Type != "INSITU-GP"){
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Type);
						//Raffraichir(); AVOIR new 27-08
					}
					/*if (unProduit.Type != "PORTRAIT" 
						&& unProduit.isTypeGroupe() == false){
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Type);
						Raffraichir(); 
					}*/					
					// 2 : Si Portait LA TAILLE DE L'IMAGE FINALE ///////////////////
					if (g_RepSCRIPTSPhotoshop == 'PHOTOLAB-2022-Cadre-Studio2'){ // QQue pour Studio² !!!						
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(scriptPoincon);						
					}
					reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Taille);
					
					/*else{ // Sinon !!!		
						// A REVOIR !!!!!!!		
						if ((unProduit.Type.lastIndexOf("PORTRAIT") > -1)||(unProduit.Type.lastIndexOf("TRAD") > -1)){ 
							//alert('lastIndexOf("Agrandissements") ');
							reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Taille);		
						}
					}	
					*/
					reussiteTraitement = reussiteTraitement && CreerUnDossier(g_RepTIRAGES_DateEcole + "/"+ unProduit.Taille + " (1ex de chaque)");
					reussiteTraitement = reussiteTraitement && CreerUnDossier(g_RepMINIATURES_DateEcole + "/"+ unProduit.Taille + " (1ex de chaque)");
					
					if (reussiteTraitement){
						// Pour avoir des planches homogenes dans le viewer de commandes					
						myDocument = app.activeDocument; 
						if (unProduit.isFichierIndiv() && !unProduit.isProduitGroupe()) {  
							if (myDocument.width > myDocument.height) { myDocument.rotateCanvas(-90);}  
						} 
						//La sauvegarde ...						
						SauvegardeJPEG(laPhoto, unPathPlanche);
						
						// Ici Faire 10%
						Miniature_Reduction(10.000000); // Soit 10 %
						SauvegardeJPEG(laPhoto, unPathMiniature);

						laPhoto.close(SaveOptions.DONOTSAVECHANGES);						

						valRetour = unNomdePlanche;
					}
					else {
						laPhoto.close(SaveOptions.DONOTSAVECHANGES);
						valRetour = "KO";
					}
				}
				else { valRetour = "KO";}
			}
			return valRetour;
		}
		catch(err) {
			g_Erreur = "Commande  : " + g_CommandePDTEncours + " ERREUR ds CreerUnProduitPourLeLaboratoire pour : " + nomFichierPhoto;
			laPhoto.close(SaveOptions.DONOTSAVECHANGES);
			return "KO";
		}
	}
	return valRetour;
}





/*
function CreerUnProduitPourLeSiteWEB(unProduit){
	var nomFichierPhoto = unProduit.FichierPhoto;
	var unNomdePlanche = NomPlancheLabo(unProduit, nomFichierPhoto);
	var valRetour = unNomdePlanche;
	var unPathPlanche = g_RepTIRAGES_DateEcole + "/" + unProduit.Taille + " (1ex de chaque)/" + unNomdePlanche;
	if(!isFichierExiste(unPathPlanche)){	
		try {
			//alert('CreerUnProduitPourLe Site WEB \n Code de unProduit ' + unProduit.Code);
			var extTeinte = '';
			var nomFichierPhoto = unProduit.FichierPhoto;
			if (unProduit.Code){
				
				if (unProduit.FichierPhoto.length && unProduit.isNeedGroupeClasse()){//Ouvrir la bonne photo ? Groupe
					nomFichierPhoto = GroupeClassePourIndiv(unProduit);
					
				}
				var laPhoto = OuvrirPhotoSource(nomFichierPhoto); 	
				var reussiteTraitement = (laPhoto != null);	
				if (reussiteTraitement) {
					var docName = laPhoto.name;
					//var basename = docName.match(/(.*)\.[^\.]+$/)[1];
					var docPath = laPhoto.path;								
					////////  Cas des fratrie ou Indiv en paysage =>> Portrait /////////
					var isFratrie = false;
					var myDocument = app.activeDocument; 	
					//////////////// TRANSFORMATIONS //////////////////////
					// 1 : LA TEINTE  DE L'IMAGE /////////////////////////
					if (unProduit.Teinte != "Couleur"){
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(unProduit.Teinte);
						Raffraichir(); 
						//extTeinte = ExtensionTeinte(unProduit.Teinte); //SEP / SEPIA NB / Noir et blanc
						}
					reussiteTraitement = reussiteTraitement && CreerUnDossier(g_RepTIRAGES_DateEcole + "/"+ unProduit.Taille );
					if (reussiteTraitement){
						// Pour avoir des planches homogenes dans le viewer de commandes					
						myDocument = app.activeDocument; 
						//La sauvegarde ...
						var unNomdePlanche = NomPlancheSiteWEB(unProduit, nomFichierPhoto);
						
						SauvegardeJPEG(laPhoto, g_RepTIRAGES_DateEcole + "/" + unProduit.Taille + "/" + unNomdePlanche);

						laPhoto.close(SaveOptions.DONOTSAVECHANGES);
						valRetour = unNomdePlanche;
					}
					else {
						laPhoto.close(SaveOptions.DONOTSAVECHANGES);
						valRetour =  "KO";
					}
				}
				else {valRetour =  "KO";}
			}
		}
		catch(err) {
			g_Erreur = "Commande  : " + g_CommandePDTEncours + " ERREUR CreerUnProduitPourLeSiteWEB pour : " + nomFichierPhoto;
			laPhoto.close(SaveOptions.DONOTSAVECHANGES);
			return "KO";
		}
	}
	return valRetour;	
}
*/

function IdentifiantOrdreTirage(unProduit){
////////// A CHANGER POUR ORDRE DE TIRAGE INVERSE ///////////
	var Index = 0;
	var IndexMAX = 9999;
	if (g_OrdreInversePlanche){		
		//Index = (g_TabLigneOriginale.length - unProduit.indexOriginal);	
		Index = (IndexMAX - unProduit.indexOriginal);	
	}
	else{
		Index = unProduit.indexOriginal;
	}
	//pad(Index, 4)
	return "P" + FormatSTR(Index, 4,'0',true);
}

function FormatSTR(chaine, length, motif, gauche) {   
    var str = '' + chaine;
    while (str.length < length) {
        if (gauche){ str = motif + str;}
		else{ str = str + motif;}
    }
    return str;
}

function NomPlancheLabo(unProduit, fichierName){

	var leNomFichier = fichierName.substr(0,fichierName.length-4);

	
	var leNomPlanche = (unProduit.Teinte)? '.' + unProduit.Teinte : '';
	leNomPlanche = IdentifiantOrdreTirage(unProduit) + "." + leNomFichier + "." + unProduit.Type + "." + unProduit.Taille + leNomPlanche + ".jpg";  

	g_TabLigneOriginale[unProduit.indexOriginal] = leNomPlanche;

	return leNomPlanche;
}

function NomPlancheSiteWEB(unProduit, fichierName){

	var leNomFichier = fichierName.substr(0,fichierName.length-4);

	var leNomPlanche = leNomFichier + ExtensionTeinte(unProduit.Teinte) + ".jpg";  

	g_TabLigneOriginale[unProduit.indexOriginal] = leNomPlanche;

	return leNomPlanche;
}

function UrlPlancheLabo(unProduit, fichierName){
	//Chemin Complet
	urlPlanche = g_RepTIRAGES_DateEcole + "/" + unProduit.Taille + "/" + NomPlancheLabo(unProduit, fichierName);
	return urlPlanche;
}

function SauvegardeJPEG(unDocument, unNomdeFichier){
// jpg options;
	  //alert("SauvegardeJPEG : " + unNomdeFichier);
      var jpegOptions = new JPEGSaveOptions();
      jpegOptions.quality = 12;
      jpegOptions.embedColorProfile = true;
//save jpg;
      unDocument.saveAs((new File(unNomdeFichier)),jpegOptions,true);
      // Fit apres l'appel : unDocument.close(SaveOptions.DONOTSAVECHANGES);
	  //return unNomdeFichier;
}	

function VerifNomRep(unNomdeDossier){
  	// A REVOIR !!!!!!!!!
    //alert("verif " +unNomdeDossier);
	var regex = new RegExp('^[a-zA-Z0-9]+([\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z0-9]{2,6}$', 'i'); 
	if (regex.test (unNomdeDossier)) {
        //alert ('Nom de dossier incorrect');
		MsgLOGInfo('Erreur','Nom de dossier incorrect');
        return false;
	 }
	 else {
         return true;
     }
    //return true;
}

function CreerUnDossier(unNomdeDossier){
	try {
		var repOK = true;
		var folder = new Folder(unNomdeDossier); 	 
		if (!folder.exists) {  
			repOK = folder.create();
			return repOK;
		}	
		return repOK;
	}
	catch(err) {
		//alert ("Impossible de creer le repertore : \n\n" + unNomdeDossier + "\n\n" + err.message, "ERREUR : CreerUnDossier()", true);
		MsgLOGInfo("ERREUR : CreerUnDossier()", "Impossible de creer le repertore : \n\n" + unNomdeDossier + "\n\n" + err.message);
		return false;
	}
}

function isDossierExiste(unNomdeDossier){
	var folder = new Folder(unNomdeDossier); 	 
	return folder.exists;
}

function isFichierExiste(unNomdeFichier){
	//alert ("isFichierExiste : " + unNomdeFichier)
	var fichier = new File(unNomdeFichier); 	 
	return fichier.exists;
}

function NbFichiersDossier(theFolderPath) {
	var leRep = Folder(theFolderPath)
	var theContent = leRep.getFiles();
	var nb=0;
	for (var n = 0; n < theContent.length; n++) {
	  var theObject = theContent[n];
	  if (theObject.constructor.name != "Folder") {
		 nb = nb + 1;
		}
	}
	return nb;
}


function NbJPGArborescence(theFolder, theNombre) {
   //if (!theFiles) {var theFiles = []};
   var theContent = theFolder.getFiles();
   
   for (var n = 0; n < theContent.length; n++) {
      var theObject = theContent[n];
      if (theObject.constructor.name == "Folder") {
         theNombre = NbJPGArborescence(theObject, theNombre)
         }
	  else {
		  if ((theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg" ) && theObject.name.substr(0, 2) != "._" ) {
			 theNombre = theNombre + 1;
		  }
	  }
   }
   return theNombre
}

function strListeFichiersJPGDossierTirage(theFolder, strFichier) {	     
	//alert('theFolder.exists :: ' + theFolder.exists );
	if (theFolder.exists) {
		var theContent = theFolder.getFiles();
	
		for (var n = 0; n < theContent.length; n++) {
			var theObject = theContent[n];
			if (theObject.constructor.name == "Folder") {
				strFichier = strFichier + strListeFichiersJPGDossierTirage(theObject, strFichier)
			}
			else {
				if (theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg" ){
					strFichier = strFichier + decodeURI(theObject.name) + sepNumLigne;
				}
			}
		}
	};
	//alert('strFichier :: ' + strFichier );
   return strFichier
}

function isDEJAPlancheJPGDossierTirage(strFichier) {
	var isDeja = false;
	for (var n = 0; n < g_TabPlancheDEJAFaites.length; n++) {
		if ( g_TabPlancheDEJAFaites[n] == strFichier) {
			isDeja = true;
			//return isDeja;
			break;
		}
	}
	//alert('isDEJAPlancheJPGDossierTirage :: ' + strFichier + '  :: ' + isDeja);
	return isDeja;
}

function TestIndivPhotoDeGroupe(){
	var msgTest = '';
	var nomFichierGroupe = '';
	for (var m = 0; m < g_CommandeLabo.NbPlanchesACreer() ; m++) {
		var unProduit = new Produit(g_CommandeLabo.ListePlanches[m]);
		if (unProduit.FichierPhoto.length && unProduit.isNeedGroupeClasse()){//Ouvrir la bonne photo ? Groupe
			nomFichierGroupe = GroupeClassePourIndiv(unProduit);
			//msgTest = msgTest + "\n" + unProduit.FichierPhoto + " => " + g_GroupeIndiv[unProduit.FichierPhoto];
			msgTest = msgTest + "\n" + unProduit.FichierPhoto + " => " + nomFichierGroupe;			
		}
	}	
	return msgTest;
}

function GroupeClassePourIndiv(unProduit){
	var nomGroupe = 'aucun';
   // On recupere le(s) groupe(s) de l'indiv
   //alert('g_GroupeIndiv[unProduit.FichierPhoto]' + unProduit.FichierPhoto + " => " + g_GroupeIndiv[unProduit.FichierPhoto]);
   //alert('TableauAssociatifTOStr  : ' + TableauAssociatifTOStr(g_GroupeIndiv));

    if (g_GroupeIndiv[unProduit.FichierPhoto]){
        var TableauListeGroupe = g_GroupeIndiv[unProduit.FichierPhoto].split('_'); 
		//alert('TableauListeGroupe: ' + TableauListeGroupe);		
		var unFichierGroupe ='';
		for (var n = 0; n < TableauListeGroupe.length; n++) {
			unFichierGroupe = TableauListeGroupe[n];
			//alert('unFichierGroupe: ' + unFichierGroupe + ' on y cherche : (unProduit.Type.slice(-4)) : ' + unProduit.Type.slice(-4));	
			
			var uneInfoNomFichierGroupe  = new CNomFichierGroupe(unFichierGroupe);
			if (unFichierGroupe.indexOf(uneInfoNomFichierGroupe.TypeGroupe) > -1){					
			//if (unFichierGroupe.indexOf(unProduit.Type.slice(-4)) > -1){
				//alert(unProduit.FichierPhoto + ' ZZZZ=> fichier groupe de remplacement :  ' + unFichierGroupe);
				//On renvoie celui qui correspond au produit de groupe demandé
				unProduit.FichierPhoto = decodeURI(unFichierGroupe);
				//alert('unProduit.FichierPhoto => ' + unProduit.FichierPhoto );
				nomGroupe = decodeURI(unFichierGroupe);
				break;
			}
		}		
	}
	//alert('nomGroupe  : ' + nomGroupe);
	return nomGroupe;
}

function InitGroupesClasseIndiv(leRepSOURCE, theFiles) {
	try {
		//alert("InitClasseIndiv START sur " + leRepSOURCE);
		if (!theFiles) {var theFiles = []};
		var leContenuRep = leRepSOURCE.getFiles();
		
		var strNUMEROClasse = '';
		var StrLesGroupesClasse = '';
		var TabLesGroupesClasse = [];
		//alert("boucle sur : " + leContenuRep.length + " fichiers : ");
		leContenuRep.sort();
		for (var n = 0; n < leContenuRep.length; n++){
			var theObject = leContenuRep[n];
			if (theObject.constructor.name == "Folder") {
				theFiles = InitGroupesClasseIndiv(theObject, theFiles);
			}
			if (theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg") {
				//alert("boucle : " + theObject.name + " taille : " + theObject.name.length);
				if (theObject.name.length >= g_MinimuNomClasse) { // C'est un groupe !
					//On ajoute le groupe à TabLesGroupesClasse
					//alert("theObject.name : " + theObject.name + "    strNUMEROClasse :  " + strNUMEROClasse);
					if ((strNUMEROClasse != "") && (strNUMEROClasse != NumeroClasseDepuisNomGroupe(theObject.name))){
							TabLesGroupesClasse.length = 0; // = [];
							strNUMEROClasse = "";
					}					
					TabLesGroupesClasse = TabLesGroupesClasse.concat(theObject.name);	
					strNUMEROClasse = NumeroClasseDepuisNomGroupe(theObject.name);
						
				}
				else {
					StrLesGroupesClasse = RecupPhotoDeGroupe(TabLesGroupesClasse, StrLesGroupesClasse);
					//alert("g_GroupeIndiv[theObject.name] : " + theObject.name + " [  " + StrLesGroupesClasse + " ]");
					TabLesGroupesClasse.length = 0; // = [];
					g_GroupeIndiv[theObject.name] = StrLesGroupesClasse;
					strNUMEROClasse = "";
					
					
				}
			}
		}
		//alert("FIN InitClasseIndiv START sur " + leRepSOURCE);
		//alert('TableauAssociatifTOStr  : ' + TableauAssociatifTOStr(g_GroupeIndiv));
		return theFiles;
	}
	catch(err) {
		//alert ("Impossible de creer le repertore : \n\n" + unNomdeDossier + "\n\n" + err.message, "ERREUR : CreerUnDossier()", true);
		MsgLOGInfo("Commande  : " + g_CommandePDTEncours + " ERREUR : InitGroupesClasseIndiv()", ErreurInfoMSG(err));
		return '';
	}		
}

function NumeroClasseDepuisNomGroupe(strNOMdeClasse){
	var uneInfoNomFichierGroupe  = new CNomFichierGroupe(strNOMdeClasse);
	return uneInfoNomFichierGroupe.Numero;

/*
	var retour = '';
	if ( strNOMdeClasse.toLowerCase().indexOf('fratrie') > -1) { // c'est une classe fratrie 
		retour = strNOMdeClasse.substr(0, 4);
	}
	else{
		//alert('isProduitGroupe ' + g_PdtGROUPE[1] );
		for (var i = 0; i < g_TypeGROUPE.length; i++) {
			if ( strNOMdeClasse.indexOf(g_TypeGROUPE[i]) > -1) { // c'est un groupe repertorié
				retour = strNOMdeClasse.substr(0, 4);
			}
		} 
	}
	return retour;
*/
}

function NomClasseDepuisNomGroupe(strNOMdeClasse){
	var uneInfoNomFichierGroupe  = new CNomFichierGroupe(strNOMdeClasse);
	return uneInfoNomFichierGroupe.NomClasse;
	/* 
	var retour = '';
	if ( strNOMdeClasse.toLowerCase().indexOf('fratrie') > -1) { // c'est une classe fratrie 
			retour = 'Fratries';
	}
	else{
		retour = strNOMdeClasse.slice(PosDeuxiemeTiret(strNOMdeClasse),-4);		
	}
	return retour;*/
}

// Avant// Avant// Avant// Avant// Avant// Avant
function PosDeuxiemeTiret(strNOMdeClasse){
	var pos = nthIndex(strNOMdeClasse,'-',2) + 1;
	//alert('Position  ' + pos );
	return pos; // Avant
}

function nthIndex(str, pat, n){
    var L= str.length, i= -1;
    while(n-- && i++<L){
        i= str.indexOf(pat, i);
        if (i < 0) break;
    }
    return i;
}

function RecupPhotoDeGroupe(TabLesGroupesClasse, StrLesGroupesClasse){
	if (TabLesGroupesClasse.length) { // il y a au moins 1 groupe
		var StrLesGroupesClasse = '';
		for (var n = 0; n < TabLesGroupesClasse.length; n++) {
			StrLesGroupesClasse = StrLesGroupesClasse + TabLesGroupesClasse[n] + "_";
		}		
		//StrLesGroupesClasse=leGroupe;
	}	
	return StrLesGroupesClasse;
}

//////  jpg-files from folder and subfolders //////
function ChercherFichierJPGdansDossier(theFolder, theFiles) {
   if (!theFiles) {var theFiles = []};
   var theContent = theFolder.getFiles();
   
   for (var n = 0; n < theContent.length; n++) {
      var theObject = theContent[n];
      if (theObject.constructor.name == "Folder") {
         theFiles = ChercherFichierJPGdansDossier(theObject, theFiles)
         }
      if (theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg") {
         theFiles = theFiles.concat(theObject);
      }
   }
   return theFiles
}
//////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
function ChercherRefDossier(theFolder, RepChercheRep, reference, Profondeur) {
    var theContent = theFolder.getFiles();
	//alert('ChercherRefDossier ' + theContent);
	var laProfondeur = Profondeur;
	theContent.sort();
    for (var n = 0; n < theContent.length; n++) {
        var theObject = theContent[n];
        if (theObject.constructor.name == "Folder") {
			// UI Affichage 
			UIDossierSource.text  = theObject.path + '/' + theObject.name;
			UIText = theObject.path + '/' + theObject.name; //UIDossierSource.text + '.';

			// UI Affichage
			//g_UIWINRechercheSource.update();
			app.refresh();
			$.sleep(1);
			
            if (theObject.name.indexOf(reference) != -1){ 
                RepChercheRep = theObject.path + '/' + theObject.name ;
//alert(' g_ProfondeurMAX : ' + g_ProfondeurMAX + ' laProfondeur : ' + laProfondeur);				
				g_ProfondeurMAX = laProfondeur;
                break;
            }
            //alert(' REP : ' + theObject.path + '/' + theObject.name);
			if (laProfondeur < g_ProfondeurMAX){
				RepChercheRep = ChercherRefDossier(theObject, RepChercheRep, reference, laProfondeur + 1);			
			}
         }
    }
   return RepChercheRep; /**/
}

/*	
function TrouverSOURCE(refEcole) {
	var repRech = '';
	g_UIWINRechercheSource.show();
	
	var repDepart = g_RepBASESOURCE;
	UIDossierSource.text = 'Recherche de Sources... ';

	var theFolder = new Folder(repDepart);	
	repRech = ChercherRefDossier(theFolder, '', refEcole, 0);
	UIDossierSource.text = repRech;

	g_UIWINRechercheSource.onClose = function() {return repRech ;}
	g_UIWINRechercheSource.close();
	//alert('repRech ' + repRech);
	return repRech ;
}
*/

function ErreurInfoMSG(err){
	var msg = "     Fichier : " + err.fileName +  "   Ligne n° " + err.lineNumber + "    msg : " + err.message;
	return msg;
}

function TableauTOStr(unTableau){
	var strTableau = 'Le TableauTOStr : ';
	for (var n = 0; n < unTableau.length; n++) {
		strTableau = strTableau + "\n" + unTableau[n] ;
	}	
	//alert('unTableau[5] ' + unTableau[5] );
	return strTableau;
}

function TableauAssociatifTOStr(unTableau){
	var strTableau = 'Le TableauAssociatifTOStr : ' ;
	
	for(var valeur in unTableau){
		 //document.write('<strong>'+valeur + ' : </strong>' + monTab[valeur] + '</br>');
		 strTableau = strTableau + "\n" + ' valeur : '+ valeur + ' unTableau[valeur] : ' + unTableau[valeur] + "\n";
	   }	
	return strTableau;	
}

function ExtensionTeinte(uneTeinte){
	var extTeinte;
	//alert('uneTeinte : ' + uneTeinte);
	switch(uneTeinte) {
		case 'NOIR-ET-BLANC':
			extTeinte = '_nb';
			break;
		case 'SEPIA':
			extTeinte = 'SEP';
			break;
		default:
			extTeinte = '';
	} 
	return extTeinte;
}
/////////////// NEW JUILLET 2020 ///////////////////////////////////////
function InitialisationSourcePourLeWEB(leRepSOURCE, theFiles) {
	
	try {
		/*alert("ZRY00");*/
		if (!theFiles) {var theFiles = []};
		var leContenuRep = leRepSOURCE.getFiles();

		var strNOMClasse = '';
		var strNUMEROClasse = '';
		var StrLesGroupesClasse = '';
		leContenuRep.sort();
		
		//alert("ZRY00AAA leContenuRep.length = " + leContenuRep.length + " theObject.name : "+leContenuRep.name);/**/
		
		//alert(TableauAssociatifTOStr(g_GroupeIndiv));
		for (var n = 0; n < leContenuRep.length; n++){
			var theObject = leContenuRep[n];
			if (theObject.constructor.name == "Folder") {
				theFiles = InitialisationSourcePourLewEB(theObject, theFiles);
			}
			if (theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg") {
				if (theObject.name.length >= g_MinimuNomClasse) { 
					// C'est un Groupe  :: 000PANOgs(.jpg)// C'est un petit nom de groupe !
					//On ajoute le groupe à TabLesGroupesClasse
					if (strNUMEROClasse != NumeroClasseDepuisNomGroupe(theObject.name)){
							//TabLesGroupesClasse.length = 0; // = [];


							// A Chnger Model php objet infofichierGroupe

							strNUMEROClasse = NumeroClasseDepuisNomGroupe(theObject.name);							
							strNOMClasse = NomClasseDepuisNomGroupe(theObject.name);

							//alert("ZX00FFF : strNOMClasse = "+ strNOMClasse);	

							/////////////////////////////////////////////////////

							//alert("X234 strNOMClasse.name : " + strNOMClasse);
							g_TabListeNomsClasses[strNUMEROClasse] = strNOMClasse;	
					}					
					// Même pour les groupe classes
					g_GroupeIndiv[theObject.name] = strNOMClasse;
				}
				else {
					g_GroupeIndiv[theObject.name] = strNOMClasse;
				}
			}
		}
		return theFiles;
		alert(TableauAssociatifTOStr(g_GroupeIndiv));
	}
	
	catch(err) {
		MsgLOGInfo("Commande  : " + g_CommandePDTEncours + " ERREUR : CreationSOURCEWEB()", ErreurInfoMSG(err));
		return '';
	}	
}

function CreerQUATTROPresentationWEB(unfichier, extension, unDossier){
	
		var nomFichierPhoto = unfichier;
		var unNomdePlancheWEBFiche = unfichier.slice(0,-4) + '-Fiche_nb.jpg';
		
		var unNomdePlancheAWEB = unfichier.slice(0,-4) + '-QCoinA-WEB.jpg';
		var unNomdePlancheAWEBQuattro = unfichier.slice(0,-4) + '-QCoinA-WEB' + extension + '.jpg'; // nb ou Quattro

		var unNomdePlancheBWEB = unfichier.slice(0,-4) + '-QCoinB-WEB.jpg';
		var unNomdePlancheBWEBQuattro = unfichier.slice(0,-4) + '-QCoinB-WEB' + extension + '.jpg'; // nb ou Quattro
		
		var unNomdePlancheCWEB = unfichier.slice(0,-4) + '-QCoinC-WEB.jpg';
		var unNomdePlancheCWEBQuattro = unfichier.slice(0,-4) + '-QCoinC-WEB' + extension + '.jpg'; // nb ou Quattro	
		
		var unNomdePlancheDWEB = unfichier.slice(0,-4) + '-QCoinD-WEB.jpg';
		var unNomdePlancheDWEBQuattro = unfichier.slice(0,-4) + '-QCoinD-WEB' + extension + '.jpg'; // nb ou Quattro		

		var unPathPlanche = g_RepTIRAGES_DateEcole + "/" + unDossier + "/";

		if(	
			(!isFichierExiste(unPathPlanche + unNomdePlancheWEBFiche)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheAWEB)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheAWEBQuattro)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheBWEB)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheBWEBQuattro)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheCWEB)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheCWEBQuattro)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheDWEB)) || 
			(!isFichierExiste(unPathPlanche + unNomdePlancheDWEBQuattro))
			){
				var laPhoto = OuvrirPhotoSource(nomFichierPhoto); 			
				var reussiteTraitement = (laPhoto != null);
				reussiteTraitement = reussiteTraitement && CreerUnDossier(unPathPlanche.slice(0,-1));
				if (reussiteTraitement){

					// Pour avoir des planches homogenes dans le viewer de commandes					
					var myDocument = app.activeDocument; 	
					if (myDocument.width < 7000)  { // Pas un Quattro !!!!
						reussiteTraitement = false;

						MsgLOGInfo("Commande  : " + g_CommandePDTEncours + " ERREUR : L'image n'est pas assez grande pour un Quattro !!	CreerQUATTROPresentationWEB()", "TAILLE QUATTRO");
					}else{
						if (myDocument.width > 7300) {reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('ROGNAGE-61CM');} 
											//HACK pour passer au quattro reel dans LR donc si taille >7204 (69cm) on rogne	à 61cm
					//alert('myDocument.width : ' + myDocument.width );		


						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('OUVERTURE-Instantane');
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('WEB-PRESENTATION-FICHE');
						//1
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheWEBFiche);	
						
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('OUVERTURE-Retour');		
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('WEB-QUATTRO');
						//2
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheAWEBQuattro);
						//3
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheBWEBQuattro);
						//4
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheCWEBQuattro);
						//5
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheDWEBQuattro);	
						
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('OUVERTURE-Retour');
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('Portrait-A');
						//6
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheAWEB);		

						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('OUVERTURE-Retour');
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('Portrait-B');
						//7
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheBWEB);	

						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('OUVERTURE-Retour');
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('Portrait-C');
						//8
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheCWEB);	

						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('OUVERTURE-Retour');
						reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('Portrait-D');
						//9
						SauvegardeJPEG(laPhoto, unPathPlanche + unNomdePlancheDWEB);	
						laPhoto.close(SaveOptions.DONOTSAVECHANGES);	
					}
		
				}

			}				
		

		return unfichier;	
}

function CreerUnFichiersPresentationWEB(unfichier, extension, unDossier){
	var isTransformQUATTRO = false;
	//alert(' unfichier :  ' + unfichier + ' extension :  ' +  extension + ' unDossier :  ' +  unDossier);
	if (g_CONFIGtypeConfigWeb == 'WEB-QUATTRO'){
		isTransformQUATTRO = isTransformQUATTRO || ((unDossier.indexOf('Fratrie') < 0) && (unfichier.length < g_MinimuNomClasse) && g_CONFIGisPhotosIndiv);	
		//alert('isTransformQUATTRO ' + isTransformQUATTRO );		
		isTransformQUATTRO = isTransformQUATTRO || ((unDossier.indexOf('Fratrie') > -1) && g_CONFIGisPhotosFratrie);
		//alert('isTransformQUATTRO ' + isTransformQUATTRO );
		isTransformQUATTRO = isTransformQUATTRO || ((unfichier.length >= g_MinimuNomClasse) && g_CONFIGisPhotosGroupes);
	}
	
	/*alert('isTransformQUATTRO ' + isTransformQUATTRO 
	+ ' isLesGroupe ' 	+ g_CONFIGisPhotosGroupes 
	+ ' isLesfratrie ' 	+ g_CONFIGisPhotosFratrie 
	+ ' unFichier ' + unfichier 
	+ ' g_CONFIGtypeConfigWeb ' + g_CONFIGtypeConfigWeb);*/
	
	if (isTransformQUATTRO){	
		CreerQUATTROPresentationWEB(unfichier, extension, unDossier);
	}
	else{
		var nomFichierPhoto = unfichier;
		//var unNomdePlanche = unfichier.slice(0,-4) + extension + '.jpg';
		var unNomdePlancheWEB = unfichier.slice(0,-4) + '-WEB.jpg';
		var unNomdePlancheWEBVariante = unfichier.slice(0,-4) + '-WEB' + extension + '.jpg'; // nb ou Quattro
		var unNomdePlancheWEBFiche = unfichier.slice(0,-4) + '-Fiche_nb.jpg';
		//var valRetour = unNomdePlanche;
		var valRetour = unNomdePlancheWEB;		
		var unPathPlanche = g_RepTIRAGES_DateEcole + "/" + unDossier;
		//alert('g_RepTIRAGES_DateEcole + "/" + unDossier : ' + g_RepTIRAGES_DateEcole + "/" + unDossier);
		try {
			var copiefichierOriginal = TypeTraitement(unfichier, unDossier, false);
			//ON FAIT LA COPIE NORMALE !! (Ou pas si 0 - 5 sur Quattro !)	
			if (copiefichierOriginal != 'KO') {
				if(	(!isFichierExiste(unPathPlanche + "/" + unNomdePlancheWEB)) || (!isFichierExiste(unPathPlanche + "/" + unNomdePlancheWEBVariante)) ||  (isFichierIdentite(nomFichierPhoto) && (!isFichierExiste(unPathPlanche + "/" + unNomdePlancheWEBFiche)))  ){		
					var laPhoto = OuvrirPhotoSource(nomFichierPhoto); 	
					var reussiteTraitement = (laPhoto != null);
					reussiteTraitement = reussiteTraitement && CreerUnDossier(unPathPlanche);
					if (reussiteTraitement){
						// Pour avoir des planches homogenes dans le viewer de commandes					
						var myDocument = app.activeDocument; 
						//La sauvegarde ...	
						//SauvegardeJPEG(laPhoto, unPathPlanche + "/" + nomFichierPhoto);
						SauvegardeJPEG(laPhoto, unPathPlanche + "/" + unNomdePlancheWEB);
						
						// traitement de la variante =>N&B, Quattro, autres ?
						//var copiefichierVariante = TypeTraitement(unfichier, unDossier, true);
						var copiefichierVariante = TypeTraitement(unfichier, unDossier, true);					
						//alert ('copiefichierVariante ' + copiefichierVariante);
						if (copiefichierVariante != 'KO') {
							if (copiefichierVariante == 'WEB-QUATTRO'){
								//alert ('NextQuattro(nomFichierPhoto) ' + NextQuattro(nomFichierPhoto));
								laPhoto.close(SaveOptions.DONOTSAVECHANGES);
								
								laPhoto = OuvrirPhotoSource(NextQuattro(nomFichierPhoto)); 	
								reussiteTraitement = (laPhoto != null);								
							}
							if (reussiteTraitement){	
								myDocument = app.activeDocument; 	
								//////////////// TRANSFORMATIONS //////////////////////
								//alert ('g_RepSCRIPTSPhotoshop ' + g_RepSCRIPTSPhotoshop);
								reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP(copiefichierVariante);
								//alert ('copiefichierVariante ' + copiefichierVariante);
								//SauvegardeJPEG(laPhoto, unPathPlanche + "/" + unNomdePlanche);
								SauvegardeJPEG(laPhoto, unPathPlanche + "/" + unNomdePlancheWEBVariante);
								
								//WEB-PRESENTATION-FICHE	
								// if 1 ou 6
								if (isFichierIdentite(nomFichierPhoto) && (copiefichierVariante == 'WEB-QUATTRO'))
								{
									laPhoto.close(SaveOptions.DONOTSAVECHANGES);							
									laPhoto = OuvrirPhotoSource(NextQuattro(nomFichierPhoto)); 	
									reussiteTraitement = (laPhoto != null);
									if (reussiteTraitement){	
										myDocument = app.activeDocument; 	
										//////////////// TRANSFORMATIONS //////////////////////
										reussiteTraitement = reussiteTraitement && Action_Script_PhotoshopPSP('WEB-PRESENTATION-FICHE');
										SauvegardeJPEG(laPhoto, unPathPlanche + "/" + unNomdePlancheWEBFiche);
									}															
								}
							}	
						}
						laPhoto.close(SaveOptions.DONOTSAVECHANGES);
					}
					else {
						laPhoto.close(SaveOptions.DONOTSAVECHANGES);
					}			
				}					

			}
			
		}
		catch(err) {
			g_Erreur = "Commande  : " + g_CommandePDTEncours + " ERREUR CreerUnFichiersPresentationWEB pour : " + nomFichierPhoto;
			laPhoto.close(SaveOptions.DONOTSAVECHANGES);
			return "KO";
		}

		return valRetour;			
	}
	
	
	
	
	
}


function TypeTraitement(unFichier, unDossier, isVariante){
	var retourval = g_CONFIGtypeConfigWeb;
	var numFichierIndiv = 0;
	
	if (g_CONFIGtypeConfigWeb == 'Rien') {retourval = 'KO';}
	else{
		if ((unFichier.length >= g_MinimuNomClasse) && !g_CONFIGisPhotosGroupes && isVariante) { 
		// Pas sur Groupe
				retourval = 'KO';
		}
		if ((unDossier.indexOf('Fratrie') > -1) && !g_CONFIGisPhotosFratrie && isVariante) { // Pas sur les Fratries ?
				retourval = 'KO';
		}
		else{
			//if ((unFichier.length <= g_MinimuNomClasse) && (g_CONFIGtypeConfigWeb != 'Rien')) { // Pas sur Groupe
			if ((unFichier.length <= g_MinimuNomClasse) && (g_CONFIGtypeConfigWeb == 'WEB-QUATTRO')) { // Pas sur Groupe
				numFichierIndiv = parseFloat(unFichier.slice(0,-4));	
				if (numFichierIndiv != NaN){				
					if (((numFichierIndiv % 5) == 0) && !((unDossier.indexOf('Fratrie') > -1) && !g_CONFIGisPhotosFratrie)){
						retourval = 'KO'; // on ne fait pas les 0 et 5 car ce sont des Quattrod !
					}					
				}				
			}
		}
	}	

	/*alert('numFichierIndiv ' + numFichierIndiv 
	+ ' isLesGroupe ' 	+ g_CONFIGisPhotosGroupes 
	+ ' isLesfratrie ' 	+ g_CONFIGisPhotosFratrie 
	+ ' unFichier ' + unFichier 
	+ ' isQuattro ' + isQuattro);*/
	return retourval;
}

function TypeTraitementOLD(unFichier, unDossier, isPlancheWEBVariante){
	var retourval = g_CONFIGtypeConfigWeb;
	//var isQuattro = (g_CONFIGtypeConfigWeb == 'WEB-QUATTRO');
	var numFichierIndiv = 0;
	
	
	if ((unFichier.length >= g_MinimuNomClasse) && !g_CONFIGisPhotosGroupes && isPlancheWEBVariante) { 
	// Pas sur Groupe
			retourval = 'KO';
	}
	if ((unDossier.indexOf('Fratrie') > -1) && !g_CONFIGisPhotosFratrie && isPlancheWEBVariante) { // Pas sur les Fratries ?
			retourval = 'KO';
	}
	else{
		if ((unFichier.length <= g_MinimuNomClasse) && (g_CONFIGtypeConfigWeb != 'Rien')) { // Pas sur Groupe
			numFichierIndiv = parseFloat(unFichier.slice(0,-4));	
			if (numFichierIndiv != NaN){				
				if (((numFichierIndiv % 5) == 0) && !((unDossier.indexOf('Fratrie') > -1) && !g_CONFIGisPhotosFratrie)){
					retourval = 'KO';
				}					
			}				
		}
	}

	/*alert('numFichierIndiv ' + numFichierIndiv 
	+ ' isLesGroupe ' 	+ g_CONFIGisPhotosGroupes 
	+ ' isLesfratrie ' 	+ g_CONFIGisPhotosFratrie 
	+ ' unFichier ' + unFichier 
	+ ' isQuattro ' + isQuattro);*/
	return retourval;
}

function NextQuattro(unFichier){
	var retourval = '';
	var numFichierIndiv = 0;
	//alert ('unFichier  ' + unFichier);
	if ((unFichier.length <= g_MinimuNomClasse)) { // Pas sur Groupe
		numFichierIndiv = parseFloat(unFichier.slice(0,-4));	
		while ((numFichierIndiv % 5) != 0) {
			numFichierIndiv++;
		}			
	}
	retourval = ("0000" + numFichierIndiv).slice(-4) + '.jpg';
	//alert ( 'unFichier  ' + unFichier + ' Quattro ' + retourval);
	return retourval;
}

function isFichierIdentite(unfichier){
	var retourval = false;
	var numFichierIndiv = 0;
	//alert ('unfichier  ' + unfichier);
	if ((unfichier.length <= g_MinimuNomClasse)) { // Pas sur Groupe
		numFichierIndiv = parseFloat(unfichier.slice(0,-4));
		if ((numFichierIndiv % 5) == 1) {retourval = true;}	
	}
	return retourval;
}


function FichierIdentite(unfichier){
	var retourval = '';
	var numFichierIndiv = 0;
	//alert ('unfichier  ' + unfichier);
	if ((unfichier.length <= g_MinimuNomClasse)) { // Pas sur Groupe
		numFichierIndiv = parseFloat(unfichier.slice(0,-4));	
		while ((numFichierIndiv % 5) != 0) {
			numFichierIndiv++;
		}			
	}
	numFichierIndiv = numFichierIndiv - 4;
	retourval = ("0000" + numFichierIndiv).slice(-4) + '.jpg';
	//alert ( 'unfichier  ' + unfichier + ' Quattro ' + retourval);
	return retourval;
}

function twoDigit(n) {
  return (n < 10 ? '0' : '') + n
}

