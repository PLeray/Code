////////////////////////////// LES FONCTIONS INTERFACE //////////////////////////////////////////////
#include PSDFonctionsOutils.js;

var g_CommandePDTEncours = '';
var g_CommandeECOLEEncours = ''; 
var g_CommandeAVANCEMENT = ''; 

var g_TabFichierATraiter = [];
var g_TabPlancheDEJAFaites = [];

function InitCommande() { 
	g_CommandePDTEncours = '';
	g_CommandeECOLEEncours = ''; 
	g_CommandeAVANCEMENT = ''; 
	//txtFichier.text = leFichierCMD;
	
	//PHOTOLAB.text = g_NomVersion + 
	fichierEnCours.text = 'Traitement de : ' + g_NomFichierEnCours;
	
	g_SelectFichierLab = 0;
	g_BilanGeneration.length = 0; // = [];
	
	g_TabFichierAvecErreur.length = 0; // = [];
	g_TabListeCompilationFichier.length = 0; // = [];	
}

function Auto() { 
	Raffraichir(); 
	//alert('AUTO');
	var nbFichierATraiter = ChercherFichierLab();
	//g_IsPhotoLabON = true;
	g_IsTravail = true;
	InitCommande();
	
	while (g_IsPhotoLabON && g_IsTravail){ 
		// TANT QU'IL Y A DES FICHIERS '0' > initialiser tableau de fichier a traiter
		// new 2 Jan 2022
		

		for (var i = 0; i < g_TabListeCompilationFichier.length; i++) {
			g_BilanGeneration.length = 0; 
			g_NomFichierEnCours = g_TabListeCompilationFichier[i];
			listboxCommandes.selection.text = g_NomFichierEnCours;
			//alert('AUTO : ' + g_TabListeCompilationFichier[i]  + ' n° ' + i);
			

			GenererLeFichierNOM(); //g_NomFichierEnCours cad le fichier selectionné dans la liste box

		} 		
		nbFichierATraiter = ChercherFichierLab('SansLesErreurs');
		g_IsTravail = (nbFichierATraiter > 0);						
	} 	
}
/*
function AutoOLD() { 
	Raffraichir(); 
	//alert('AUTO');
	var nbFichierATraiter = ChercherFichierLab();
	g_IsPhotoLabON = true;
	g_IsTravail = true;
	
	while (g_IsPhotoLabON && g_IsTravail){ 
		// TANT QU'IL Y A DES FICHIERS '0' > initialiser tableau de fichier a traiter
		for (var i = 0; i < g_TabListeCompilationFichier.length; i++) {
			g_NomFichierEnCours = g_TabListeCompilationFichier[i];
			InitCommande();
			fichierEnCours.text = 'Traitement de : ' + g_NomFichierEnCours;
			GenererLeFichierNOM(); //g_NomFichierEnCours

		} 		
		nbFichierATraiter = ChercherFichierLab('SansLesErreurs');
		g_IsTravail = (nbFichierATraiter > 0);						
	} 	
}
*/

function GenererLeFichierNOM() { 
	//alert('TESTZ10  GenererLeFichierNOM !!!' + g_SelectFichierLab);
	//PSJ AOUT 21 
	PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
	g_IsGenerationEnCours = true;
	g_IsGenerationEnPause = false;	
	//buttonConfig.enabled = false;
	//alert('TESTZ11  GenererLeFichierNOM !!!' + g_SelectFichierLab);
	//NEW 02-01-2022 Raffraichir();
	//alert('TESTZ11bb  GenererLeFichierNOM !!!' + g_NomFichierEnCours);
	if (OuvrirSelectFichierLab0(g_NomFichierEnCours)){
		//alert('TESTZ20  OuvrirSelectFichierLab0 !!!' + g_NomFichierEnCours);
		//InitInfoFichier();
		
		if (g_IsPlancheSiteWEB){
			//alert('TESTZ21  GenererFichiersWEB !!!' + g_SelectFichierLab);
			GenererFichiersWEB();
		}
		else {
		
			GenererFichiersLABO();
		}	
	}
	g_IsGenerationEnCours = false;
	g_IsGenerationEnPause = true;
	SetBoutonGenerer();
	Raffraichir();
	Select_Generer.enabled = true;	
	//buttonConfig.enabled = true;
}

function GestionBoutonGenerer() { 
	g_IsGenerationEnPause = !g_IsGenerationEnPause;	
	
	SetBoutonGenerer();
	if (!g_IsGenerationEnCours){ //  On n'est pas en cours de generation !!!
		//alert('TESTZ29  g_IsGenerationEnPause : ' + g_IsGenerationEnPause);	
	
		GenererLeFichierNOM();
		g_IsGenerationEnPause = false;	
		Auto();

	}	
//alert('TESTZ30  g_IsGenerationEnPause !!! : ' + g_IsGenerationEnPause);	
}

function SetBoutonGenerer(){
	/*if (!g_IsTravail){
		Select_Generer.image = (g_IsGenerationEnPause?ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d): ScriptUI.newImage (imgPause.a, imgPause.b, imgPause.c, imgPause.d));		
	}else{
		Select_Generer.image = (g_IsGenerationEnPause?ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d): ScriptUI.newImage (imgPause.a, imgPause.b, imgPause.c, imgPause.d));					
	}*/
	Select_Generer.image = (g_IsGenerationEnPause?ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d): ScriptUI.newImage (imgPause.a, imgPause.b, imgPause.c, imgPause.d));		
}


function OuvrirSelectFichierLab0(fileName) {
	var valRetour = false;
	//alert('TESTZ12  g_IsPhotoLabON : ' + g_IsPhotoLabON);
	if (g_IsPhotoLabON){
		//alert('TESTZ12aaaa  fileName : ' + fileName);
		g_IsPlancheSiteWEB = (fileName.slice(-5) == ".web0") ? true : false ; 
		//alert('TESTZ12bbbb  OuvrirSeleFichierLab0 : ' + fileName);
		var fileNamePath = g_Rep_PHOTOLAB + 'CMDLABO/' + fileName;
		if (isDroitCompiler(fileNamePath.substr(0,fileNamePath.length-1) + '1')){
			//alert('TESTZ12 : isDroitCompiler ok pour : ' + fileNamePath);
			g_SelectFichierLab = new File(fileNamePath);
			valRetour = OuvrirFichierLabo();
		}		
	}
	return valRetour;
	alert('TESTZ13  OuvrirSelectFichierLab0 !!!' + valRetour);
}

function ChercherFichierLab(avecErreur) {
	var avecErreur = avecErreur || 'AvecLesErreurs';
	//alert('var avecErreur ? : ' + avecErreur);
	var theFolder = new Folder(g_Rep_PHOTOLAB + 'CMDLABO/');	
	var theContent = theFolder.getFiles();

	g_TabFichierATraiter.length = 0; // = [];
	g_TabListeCompilationFichier.length = 0; // = [];
	var leFichier = '';
	var strBaseName = '';   
	var YAutre = false;

	var strExtension = '';
   
   //alert('g_TabFichierATraiter.length : ' + g_TabFichierATraiter.length);
	for (var n = 0; n < theContent.length; n++) {
	  var theObject = theContent[n];
		 g_TabFichierATraiter.push(decodeURI(theObject.name));
	}
	for (var n = 0; n < g_TabFichierATraiter.length; n++) {
		if (g_TabFichierATraiter[n].slice(-5) == ".lab0" || g_TabFichierATraiter[n].slice(-5) == ".web0") {
			leFichier = g_TabFichierATraiter[n];
			
			strExtension = (leFichier.slice(-5) == ".lab0")? '.lab' : '.web';
			strBaseName = leFichier.substr(0, leFichier.lastIndexOf(strExtension));  

			if (!isFichierErreur(leFichier)) {	
				YAutre = false;

				for (var i = 0; i < g_TabFichierATraiter.length; i++) {
					if (strBaseName == g_TabFichierATraiter[i].substr(0, g_TabFichierATraiter[i].lastIndexOf(strExtension))){								
						if(leFichier != g_TabFichierATraiter[i]){ //Fichier en cours de traitement								
							if (g_TabFichierATraiter[i].substr(-1, 1) != '1'){
								$YAutre = true;
								break;									
							} else {$YAutre = false;}	
						} else {$YAutre = false;}					
					}
				}
				if(!$YAutre){
					//Ajout dans la liste des fichiers en errreur						
					if (isFichierExiste(g_Rep_PHOTOLAB + 'CMDLABO/' + strBaseName + '.Erreur')) {							
						g_TabFichierAvecErreur.push(leFichier);
					}
					//ComboFichierLab.add ("item", leFichier); 
					if (avecErreur == 'AvecLesErreurs'){
						g_TabListeCompilationFichier.push(leFichier);										
					}
					else
					{   //alert('g_TabFichierAvecErreur  ' + TableauTOStr(g_TabFichierAvecErreur) + ' 
						if (!isFichierErreur(leFichier)) {								
							g_TabListeCompilationFichier.push(leFichier);					
						}							
					}								
				}									
			}			
		}		
	}   
	return g_TabListeCompilationFichier.length;		
}

function isFichierErreur(unFichier) { 
		var retour = false;
		//alert('isProduitLABO ' + g_PdtNotLABO[1] );		
		for (var i = 0; i < g_TabFichierAvecErreur.length; i++) {
			if (g_TabFichierAvecErreur[i] == unFichier) {
				//alert(' FichierAvecErreur : ' + g_TabFichierAvecErreur[i]);
				retour = true;
				break;
			}
		} 
		return retour;
}

function SelectionnerFichierLabo() { 
	g_SelectFichierLab = File.openDialog("selection de le fichier de la ou y a x les photos qui n'en faut imprimer");
	return OuvrirFichierLabo();
}

function OuvrirFichierLabo() { 
	var valRetour = false;
	try { 
		//alert('TestZ1 OuvrirFichierLabo test : ' + g_SelectFichierLab);	
		if(g_SelectFichierLab && TestAPI()){
			//Select_InfoAPI.enabled = true;
			MsgLOGInfo('Fichier Labo a compiler : ' + g_SelectFichierLab);
			g_CommandeLabo = new CommandesLabo(OuvrirFichierToTableauDeLigne(g_SelectFichierLab), g_NomFichierEnCours);
			//alert('TESTZ2 g_CommandeLabo test : ' + g_CommandeLabo.FichierLab);		
			if (g_CommandeLabo.isRecord()){
				//alert('TESTZ3  est  enregistré');
				if (g_CommandeLabo.isValide()){
					//alert('TESTZ5  est  isValide');
					g_CommandeLabo.InitListePlanches();
					//alert('TESTZ6  g_CommandeLabo.Ecole : "' + (g_CommandeLabo.Ecole?'Ecole':'KO') + '"  nb planche : ' + g_CommandeLabo.NbPlanchesACreer());
					//UI

					
					// PLANTE POUR FICHIER WEB >> supression car inutile ???? 22/03
					//var premiereEcole = new Ecole (g_CommandeLabo.Ecole);	
					
					
					//alert('TESTZ7  InitListePlanches');					
					//UI
					//nbFichierAGenerer.text = 'Dossier TIRAGE :     (Nombre de planches à générer : ' + g_CommandeLabo.NbPlanchesACreer() + ')';
					Select_Generer.enabled =  (g_SelectFichierLab) ? true : false ;
					//buttonConfig.enabled = true;
					progressBar.maxvalue = g_CommandeLabo.NbPlanchesACreer();		
					valRetour = true;		
					//alert('TESTZ8bb  valRetour ' + valRetour);					
				}
			}else{
				//alert('TESTZ4 commande est pas enregistré');
				//Gestion erreur pas de code a faire !!
				msg = "PROBLEME : Le fichier de commande n'est pas enregistré";
				AjoutBilanGeneration(msg);
				msg = "     SOLUTION : Déposer de nouveau le fichier de commande (.csv, . lab ou .web) par drag and Drop pour essayer de le ré-enregistrer !";
				AjoutBilanGeneration(msg);			
				AjoutBilanGeneration('');	
				EcrireErreursBilan(g_SelectFichierLab.name);
				valRetour = false;
			}
        }
	}
	catch(err) {
        console.log( "SelectionnerFichierLabo : " + g_SelectFichierLab.name + "\n\n" + err.message);
		valRetour = false;
	}  
	return valRetour;
}

function MAJinfoEcole(uneEcole) {   
	var valRetour = false;
	var msg = '';
	var repTirage = '';
		
	g_RepSOURCE = TrouverRepSOURCEdansBibliotheque(uneEcole);
	g_RepSCRIPTSPhotoshop = TrouverRepScriptPSdansBibliotheque(uneEcole);
	//alert("TEST Z61 g_RepSOURCE " + g_RepSOURCE);	
    if (g_RepSOURCE == ''){
		msg = "Ecole en cours : " + g_CommandeECOLEEncours
		AjoutBilanGeneration(msg);

		msg = "     PROBLEME : PhotoLab n'a pas trouvé de dossier SOURCE dans la bibliotheque avec ce code (" + uneEcole.CodeEcole + ")";
		AjoutBilanGeneration(msg);
		msg = "     SOLUTION PROBABLE : Créer ou ajouter le dossier de photo avec le code : " + uneEcole.CodeEcole + " dans la bibliotheque de PhotoLAb !";
		AjoutBilanGeneration(msg);			
		AjoutBilanGeneration('');
		MsgLOGInfo('Erreur de dossier /SOURCE !', msg);
		g_Erreur = msg;
	}else{
		if (isDossierExiste(g_RepSOURCE)){
			if (g_IsPlancheSiteWEB){
				g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'WEB-ARBO/' + uneEcole.DateTirage + "-" + uneEcole.NomEcole;
			} 

			if (!uneEcole.isEcoleWEB()){InitGroupesClasseIndiv(Folder(g_RepSOURCE), []); }
			valRetour = true;		
		}
		else{
			msg = "     PROBLEME : PhotoLab n'a pas trouvé de dossier SOURCE pour les photos avec ce code (" + uneEcole.CodeEcole + ")";
			AjoutBilanGeneration(msg);
			msg = "     SOLUTION PROBABLE : Modifier ou ajouter le code (" + uneEcole.CodeEcole + ") dans le nom du dossier de l'école " + g_CommandeECOLEEncours;
			AjoutBilanGeneration(msg);			
			AjoutBilanGeneration('');
			MsgLOGInfo('Erreur de dossier /SOURCE !', msg);
			g_Erreur = msg;			
			
			valRetour = false;		
		}
		//alert("TEST Z63 ");
	}
	return valRetour;
}

function GenererFichiersLABO() { 
	try {
		var isEcoleOK = false;
		PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

		var chronoDebut = new Date().getTime();
        var nbLigneFichier = g_CommandeLabo.NbLignes();
		
		// Definition du repertoire de tirage : unique par fichier de commande !!!
		for (var m = 0; m < nbLigneFichier; m++) {
            var ligne = g_CommandeLabo.TableauLignes[m];
			if (!isEntete(ligne) && !isLigneEtat(ligne))
			{		
				if (isEcole(ligne)){// Mise à jour des SOURCES !!
					var uneEcole = new Ecole(ligne);  
					//alert('Commentaire : ' + uneEcole.Commentaire.substr(0, 11) + ' Est une ecole Web !!!!! : ' + uneEcole.isEcoleWEB());
					if  (uneEcole.NomEcole.indexOf('(ISOLEES)') > -1) {
						var ladate=new Date();
						//repTirage = ladate.getFullYear()+"-"+twoDigit((ladate.getMonth()+1))+"-"+twoDigit(ladate.getDate())+'-CMD-ISOLEES';
						repTirage = ladate.getFullYear()+"-"+twoDigit((ladate.getMonth()+1))+"-"+twoDigit(ladate.getDate())+'-Commandes Isolees';
					}
					else {
						if  (uneEcole.NomEcole.indexOf('(RECOMMANDES)') > -1) {
							//var ladate=new Date();
							//repTirage = ladate.getFullYear()+"-"+twoDigit((ladate.getMonth()+1))+"-"+twoDigit(ladate.getDate())+'-RECOMMANDES de GROUPEES';
							repTirage = '9999-99-99-(RECOMMANDES)-EN-COURS';
							//alert('repTirage :' + repTirage);
						}
						else{
							if  (uneEcole.NomEcole.indexOf('(COMMANDES LIBRES)') > -1) {
								//var ladate=new Date();
								//repTirage = ladate.getFullYear()+"-"+twoDigit((ladate.getMonth()+1))+"-"+twoDigit(ladate.getDate())+'-RECOMMANDES de GROUPEES';
								repTirage = '8888-88-88-(COMMANDES LIBRES)-EN-COURS';
								//alert('repTirage :' + repTirage);
							}	
							else{
								repTirage = uneEcole.DateTirage + "-" + uneEcole.NomEcole;				
							}											
						}				
					}
					
					g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'TIRAGES/' + repTirage;
					g_RepMINIATURES_DateEcole = g_Rep_PHOTOLAB + 'CMDLABO/MINIATURES/' + repTirage;
					break;						
				}
			}  			
		}
		//alert("TEST Z44440 g_RepTIRAGES_DateEcole : " + g_RepTIRAGES_DateEcole);
		//Initialisation du tableau de planche déja faites!
		/*	*/

		g_TabPlancheDEJAFaites = [];
		//alert('TEST Z42 ' + TableauTOStr(g_TabPlancheDEJAFaites));
		var strListeFichier = '';
		strListeFichier = strListeFichiersJPGDossierTirage(Folder(g_RepTIRAGES_DateEcole),strListeFichier);
		if (strListeFichier != ''){			
			g_TabPlancheDEJAFaites = strListeFichier.split(sepNumLigne);
		}
		
		//alert('TEST Z43 g_TabPlancheDEJAFaites ' + TableauTOStr(g_TabPlancheDEJAFaites));
		/*		*/
		// Creation des planches du fichier de commande !!!
		for (var m = 0; m < nbLigneFichier; m++) {
			// ATTENtion ici on n utilise plus le tableau de ligne avec seulement ecole et planche
			// Mais tout ...
            var ligne = g_CommandeLabo.TableauLignes[m];
			//alert("TEST Z39 : " + ligne);
			if (!isEntete(ligne) && !isLigneEtat(ligne))
			{		
				if (isEcole(ligne)){// Mise à jour des SOURCES !!
					
					g_CommandeECOLEEncours = ligne;	
					var uneEcole = new Ecole(g_CommandeECOLEEncours);  
					//	
					isEcoleOK = MAJinfoEcole(uneEcole);		
					//alert("TEST Z3955 uneEcole.NomEcole : " + uneEcole.NomEcole);
				}  
				else {
					//UI			
					if (isEcoleOK && ligne && CreerUnDossier(g_RepTIRAGES_DateEcole)){                
					
						g_CommandePDTEncours = ligne.substr(0,ligne.length-2);
						var unProduit = new Produit(ligne);  						
						if (unProduit.isProduitLABO()){
							//SUPRIMMER CA POUR VOIR SI PREND DU TEMPS!
							//alert("TEST Z39566 unProduit.Nombre : " + unProduit.Nombre);
							var plancheCree = CreerUnProduitPourLeLaboratoire(unProduit);							
						}else{
							var plancheCree = unProduit.Type;
							g_TabLigneOriginale[unProduit.indexOriginal] = plancheCree;
							MsgLOGInfo(plancheCree,'Produit Labo non defini : ' + g_CommandePDTEncours);
						}
						//UI
						//NEW SUPR
						//MAJTableauGeneration(plancheCree,unProduit);
						//NEW 02-01-2022 Raffraichir(); 			
					}                   
				}
				if (!g_IsPhotoLabON ||isInterruptionTraitement()){break;};	
				// Attention
				//alert("TEST Z499 ");

				// TOUT LES 10 SEULEMENT
				/*
				if (( m % 10) == 0){ 					
					SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),1);
					//alert("TEST Z45 ");
					if (g_BilanGeneration.length > 0) {EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));}				
				}	*/
				SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),1);
				//alert("TEST Z45 ");
				if (g_BilanGeneration.length > 0) {EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));}								
				//alert("TEST Z46 ");
				//alert('SauverFichierFromTableauDeLigne ' +  plancheCree);			
			}
			//NEW 02-01-2022 
			Raffraichir(); 
			progressBar.value = m + 1;
			g_CommandeAVANCEMENT = Number( ( m + 1)  / (nbLigneFichier - 1) ); //g_CommandeLabo.NbPlanchesACreer()
			g_CommandeAVANCEMENT = (g_CommandeAVANCEMENT>1)?1:g_CommandeAVANCEMENT; 
			txtTraitement.text = String ( m + 1 ) + " / " +  String (nbLigneFichier) 
			+ "\n              pour stopper le traitement : \n       Appui long sur les touche MAJ + 'x'";	
	
		}
		/////// Retour sur le temps Passé   //////////////
        if (!g_IsGenerationEnPause && g_IsPhotoLabON){ 
			var nbErreur = EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));
			//alert('nbErreur ' +  nbErreur + ' g_SelectFichierLab.name : ' +  g_SelectFichierLab.name);	
			BilanFinTraitement(chronoDebut, nbErreur);		
			SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),(nbErreur?1:2));
		}
	}
	catch(err) {
		MsgLOGInfo("ERREUR GenererFichiersLABO()", "Fichier en cours : " + g_CommandePDTEncours + "\n" + ErreurInfoMSG(err));
		Select_Generer.enabled = true;
		//buttonConfig.enabled = true;
	}
}

/*
function MAJTableauGeneration(plancheCree,unProduit)
{
	var reussite = (plancheCree.substr(0,1) == 'P')?"Fait ! ":"Echec !";	
	//var reussite = (plancheCree != 'KO')?"Fait ! ":"Echec !";
	if (plancheCree == 'KO'){plancheCree = g_Erreur;}
	//MAJTableauLOG(UIListeMessage.items[UIindex].text, 			   FormatSTR(plancheCree, 60,' ',false), reussite,(plancheCree != 'KO'),'update');		
	//MAJTableauLOG(FormatSTR("Commande : " + unProduit.Code, 55,' ',false), FormatSTR(plancheCree, 60,' ',false), reussite,(plancheCree != 'KO'),'update');	
	MAJTableauLOG("Commande : " + unProduit.Code, reussite, "( " + plancheCree + " )",(reussite == "Fait ! "),'update');	
	
	UIindex++;
}
*/

function QuestionInterruptionTraitement() { 
	var leMessage = 'Voulez vous stopper la creation des planches ? ';
	return confirm(leMessage);
}

function isInterruptionTraitement() { 
	var isStop = false;
	if (g_IsGenerationEnPause){		//alert('g_IsGenerationEnPause ' + g_IsGenerationEnPause);	
		SetBoutonGenerer();
		//var leMessage = 'Voulez vous stopper la creation des planches ? ';
		// affichage des erreurs :
		/*if (g_BilanGeneration.length > 0){
			for (var n = 0; n < g_BilanGeneration.length; n++) {
				leMessage = g_BilanGeneration[n] + '\n\n' + leMessage;
			}		
		}*/
		var retour = QuestionInterruptionTraitement();
		if (retour == true) {
			
			EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));
			
			MsgLOGInfo("Création annulée !");
			g_IsPhotoLabON = false;		
			isStop =  true;
		} 
		else {
			MsgLOGInfo("La création va repartir !");
			
			isStop =  false;
		}
		g_IsGenerationEnPause = false;
		//Select_Generer.text = (g_IsGenerationEnPause?'>':'||');
		SetBoutonGenerer();
	}	
	return isStop;
}

function BilanFinTraitement(chronoDebut, nbErreur) { 
	var chronoFin = new Date().getTime();
	var nbSecondes = (chronoFin - chronoDebut);
	nbSecondes = (nbSecondes - (nbSecondes % 1000)) / 1000
	var Minute = (nbSecondes - (nbSecondes % 60))/60;
	
	var nbPlanches = g_CommandeLabo.NbPlanchesACreer();
	
	//NOUVEAU
	var VitesseMoy = nbSecondes / g_CommandeLabo.NbPlanchesACreer();
	var bilan = "Bilan : " + String (Minute) + " min et " + String (nbSecondes % 60) + " sec pour créeer " + String (nbPlanches - nbErreur) + " Planches."
	MsgLOGInfo(bilan, VitesseMoy + " secondes par planche");
	fichierEnCours.text = "Bilan : " + String (Minute) + " min et " + String (nbSecondes % 60) + " sec pour créeer " + String (nbPlanches - nbErreur) + " Planches."; 
	/////////////////////////////////////////////////
	if (nbErreur > 0){
		MsgLOGInfo("!!! ATTENTION !!!", "Il y a " + nbErreur + " planche(s) qui n'ont pas été crée(s) !!! Voir le détail dans le fichier.erreur...");
		g_BilanGeneration.push("!!! ATTENTION !!! Il y a " + nbErreur + " planche(s) qui n'ont pas été crée(s) !!! ");
	}
	
	
}



/*
function InitInfoFichier() { 
	try {
		txtTraitement.text = "0 / " + String (g_CommandeLabo.NbPlanchesACreer());
		fichierEnCours.text = 'Traitement de : ' + g_NomFichierEnCours;

	}
	catch(err) {
		var msg = "Init de TableauFichier :  \n \n";
		//Commentaires.push (msg);
		//alert( msg + "\n\n" + err.message);
		MsgLOGInfo('Init de TableauFichier',msg + "\n\n" + err.message);
	}	
}



function MsgERREUR(Titre,Message){
	MsgLOGInfo(Titre, Message, 'ERREUR', false, 'NL');
}


function MsgLOGInfo(Titre, Message, PetiteColonne, isCoche, isNew){
	var isNew = isNew || 'NL';
	var Message = Message || '';
	var PetiteColonne = PetiteColonne || '...!';
	var isCoche = isCoche || true;
//QUE FAIT ON DE L'ERREUR
	//alert(Message, Titre, true);
	MAJTableauLOG(Titre, Message, PetiteColonne, isCoche, isNew);
}
*/
function MsgLOGInfo(Message, Titre){
var Titre = Titre || 'Log';

//QUE FAIT ON DE L'ERREUR enregistrer dans fichier de log
	//alert(Message, Titre, true);
	
	EcrireLOGInfo(Titre + ' : ' + Message);
	
	
	

}

function EcrireLOGInfo(resultAPI) {
	var fileName = g_Rep_PHOTOLAB + '/LOGInfo.txt'; // 
	var file = new File(fileName);
	file.encoding='UTF-8';
	file.open("w"); // open file with write access
		file.writeln(resultAPI);
	file.close();
	//return true;
}


/*
function MAJTableauLOG(Col1, Col2, Col3, isCoche, isNew){
	
	var isNew = isNew || 'NL';
	//alert('isNew : ' + isNew + 'UIindex : ' + UIindex);
	if (isNew =='NL') {UIListeMessage.add ("item", Col1);}
	UIListeMessage.items[UIindex].checked = isCoche;	
	UIListeMessage.items[UIindex].text = Col1 + "  " + Col2 + "  " + Col3;
	if (isNew =='NL') {UIindex++;}
	if((UIindex-20) > 0){
		UIListeMessage.revealItem(UIListeMessage.items.length-1);
		UIListeMessage.revealItem(UIindex-20);
	}	
	
}
*/

/*function ArborescenceWEB(){
	//laDate = Date.now();
	//laDate.getDate();
	

	var size = 0;
	var i = 0;
	for(var fichier in g_GroupeIndiv){
		size++;
	}	
	progressBar.maxvalue = size;
	//alert('progressBar.maxvalue : ' + progressBar.maxvalue);
	//progressBar.value = 15;
	alert('ZX00333  progressBar.value : ' + progressBar.value);
	Raffraichir();
	for(var fichier in g_GroupeIndiv){
		i = i + 1;
		 progressBar.value = i ;
		 txtTraitement.text = String (i) + " / " + String (size);
		 fichierEnCours.text = 'Création arborescence web pour : ' + decodeURIComponent(fichier);
		 Raffraichir();

		 //alert("fichier : " + fichier + " g_TabListeNomsClasses[fichier] : " + g_GroupeIndiv[fichier]);
		 CreerUnFichiersPresentationWEB(fichier, '_nb', g_GroupeIndiv[fichier] );

	}	
	fichierEnCours.text = 'Création arborescence web terminée !';

}

*/
//////////////////////////////////////////////
//////////////////////////////////////////////
function GenererFichiersWEB() { 
	try {
		// On met en gris au depart ...
		//PSJ AOUT 21 
		PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
		
		chronoDebut = new Date().getTime();				
	
		var SRCEcole = new Ecole (g_CommandeLabo.Ecole);	

		var isEcoleOK = MAJinfoEcole(SRCEcole);			
		//alert("MAJinfoEcole(SRCEcole)"+ isEcoleOK);	
		LireFichierSource();
		var uneSource = new objSourceCSV(); 
		//uneSource = RecupSourceDepuisobjEcole(cdecole);
		uneSource = RecupSourceDepuisobjEcole(SRCEcole);
		
		var isCORR = (g_CommandeLabo.ListePlanches[0] != 'TOUTES LES PHOTOS');

		g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'WEB-ARBO/' + (isCORR? 'CORR-' : 'ARBO-') +  uneSource.NomProjet;
		g_RepSCRIPTSPhotoshop = uneSource.RepScriptPS;				
		g_RepSOURCE  = uneSource.DossierSources;
		//g_RepSOURCE  = encodeURI(uneSource.DossierSources);
		//alert('g_RepSOURCE : ' + g_RepSOURCE);
		g_TabListeNomsClasses = [];

		var theFolder = new Folder(g_RepSOURCE);
			

		InitialisationSourcePourLeWEB(theFolder, []);


		var leSousGroupeDeFichier = [];
		if (isCORR){
			leSousGroupeDeFichier = g_CommandeLabo.ListePlanches;	
		}
		else{
			for(var fichier in g_GroupeIndiv){
				leSousGroupeDeFichier.push(fichier);
			}			
		}
		leSousGroupeDeFichier.sort();
		//alert('x4545' + TableauTOStr(leSousGroupeDeFichier));
		//alert(TableauAssociatifTOStr(g_GroupeIndiv));
		
		progressBar.maxvalue = leSousGroupeDeFichier.length;			
		//for(var fichier in g_GroupeIndiv){//Pour tout les fichier du Source
		for (var i = 0; i < leSousGroupeDeFichier.length; i++) {	
				var fichier = leSousGroupeDeFichier[i];
			//alert('x4545 leSousGroupeDeFichier[i] ' + leSousGroupeDeFichier[i] + ' fichier : ' + fichier );
			//if (leSousGroupeDeFichier.includes(fichier)){
				//i = i + 1;
				progressBar.value = i ;
				fichierEnCours.text = 'Création arborescence web pour : ' + decodeURIComponent(fichier);

				//UI
				Raffraichir(); // A voir ?		
				CreerUnFichiersPresentationWEB(fichier, '_nb', g_GroupeIndiv[fichier] );
				//alert('x4545' + TableauTOStr(leSousGroupeDeFichier));
				if (!g_IsPhotoLabON ||isInterruptionTraitement()){break;};	
				//ICI Probleme avec les groupes 
				//alert(TableauTOStr(leSousGroupeDeFichier));
				SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),1);
				EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));
				
				g_CommandeAVANCEMENT = Number( ( i + 1)  / (leSousGroupeDeFichier.length - 1) ); //g_CommandeLabo.NbPlanchesACreer()
				g_CommandeAVANCEMENT = (g_CommandeAVANCEMENT>1)?1:g_CommandeAVANCEMENT; 
				txtTraitement.text = String (i + 1) + " / " +  String (leSousGroupeDeFichier.length)
				+ "\n              pour stopper le traitement : \n       Appui long sur les touche MAJ + 'x'";		
				//alert("ZXDXDXD 01 : g_CommandeAVANCEMENT : " + g_CommandeAVANCEMENT );
			//}    
			
		}			
        if (!g_IsGenerationEnPause){ 
			
			var etatCompil = (g_CommandeAVANCEMENT<1)?1:2;			
			var nbErreur = EcrireErreursBilan(decodeURI(g_SelectFichierLab.name));
			etatCompil = nbErreur?1:etatCompil; 
			
			BilanFinTraitement(chronoDebut, nbErreur);		
			//alert(' FIN etatCompil : ' + etatCompil);
			SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),etatCompil);
		}
	}
	catch(err) {
		MsgLOGInfo("ERREUR GenererFichiersWEB()", "Fichier en cours : " + unProduit.FichierPhoto + "\n" + ErreurInfoMSG(err));
		Select_Generer.enabled = true;
	}
}

function LoadConfig() {
	var fileName = g_Rep_PHOTOLAB + 'PhotoLab-config.ini'; // 'Code/PhotoLab-config.ini';	
	var file = new File(fileName);
	if ( file.open("r")){
		file.readln();		//Planche inversé ?			
		g_OrdreInversePlanche = (file.readln() == 'true');
		
		file.readln();		// typeConfigWeb
		g_CONFIGtypeConfigWeb = file.readln();		
		//alert(g_RepBASESOURCE);
		
		file.readln();		// isPhotosGroupes		
		g_CONFIGisPhotosGroupes = (file.readln() == 'true');
		
		file.readln();		// isPhotosIndiv		
		g_CONFIGisPhotosIndiv = (file.readln() == 'true');

		file.readln();		// isPhotosFratrie			
		g_CONFIGisPhotosFratrie = (file.readln() == 'true');

		file.close();
	}
}

function SaveConfig(checkOrdre, typeConfigWeb, isPhotosGroupes, isPhotosIndiv, isPhotosFratrie) {
	var fileName = g_Rep_PHOTOLAB + 'PhotoLab-config.ini'; // 'Code/PhotoLab-config.ini';	
	var file = new File(fileName);
	file.encoding='UTF-8';
	file.open("w"); // open file with write access
		file.writeln("// Planche inversé ?");		
		file.writeln(checkOrdre);	
		g_OrdreInversePlanche = checkOrdre;	
		
		file.writeln("// typeConfigWeb");		
		file.writeln(typeConfigWeb);
		g_CONFIGtypeConfigWeb = typeConfigWeb;	
		
		file.writeln("// isPhotosGroupes");		
		file.writeln(isPhotosGroupes);	
		g_CONFIGisPhotosGroupes = isPhotosGroupes;	
		
		file.writeln("// isPhotosIndiv");		
		file.writeln(isPhotosIndiv);	
		g_CONFIGisPhotosIndiv = isPhotosIndiv;	
		
		file.writeln("// isPhotosFratrie");		
		file.writeln(isPhotosFratrie);	
		g_CONFIGisPhotosFratrie = isPhotosFratrie;		

	file.close();
}


