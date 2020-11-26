////////////////////////////// LES FONCTIONS INTERFACE //////////////////////////////////////////////
#include PSDFonctionsOutils.js
var g_CommandePDTEncours = '';
var g_CommandeECOLEEncours = ''; 
var g_CommandeAVANCEMENT = ''; 

var g_TabFichierATraiter = [];

function InitCommande() { 
	g_CommandePDTEncours = '';
	g_CommandeECOLEEncours = ''; 
	g_CommandeAVANCEMENT = ''; 
	//txtFichier.text = leFichierCMD;
	
	//PHOTOLAB.text = g_NomVersion + 
	fichierEnCours.text = '         traitement de : ' + g_NomFichierEnCours;
	
	g_SelectFichierLab = 0;
	g_BilanGeneration.length = 0; // = [];
	
	g_TabFichierAvecErreur.length = 0; // = [];
	g_TabListeCompilationFichier.length = 0; // = [];	
}

function Auto() { 
	Raffraichir(); 
	
	var nbFichierATraiter = ChercherFichierLab();
	g_IsPhotoLabON = true;
	g_IsTravail = true;
	while (g_IsPhotoLabON && g_IsTravail){ 
		// TANT QU'IL Y A DES FICHIERS '0' > initialiser tableau de fichier a traiter
		for (var i = 0; i < g_TabListeCompilationFichier.length; i++) {
			g_NomFichierEnCours = g_TabListeCompilationFichier[i];
			InitCommande();
			GenererLeFichierNOM(); //du tableau
		} 		
		nbFichierATraiter = ChercherFichierLab('SansLesErreurs');
		g_IsTravail = (nbFichierATraiter > 0);						
	} 	
}

function GenererLeFichierNOM() { 
	PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
	g_IsGenerationEnCours =true;
	g_IsGenerationEnPause = false;	
	//buttonConfig.enabled = false;
	Raffraichir();

	if (OuvrirSelectFichierLab0(g_NomFichierEnCours)){
		InitTableauFichier();
		if (g_IsPlancheSiteWEB){
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

function OuvrirSelectFichierLab0(fileName) {
	var valRetour = false;
	if (g_IsPhotoLabON){
		g_IsPlancheSiteWEB = (fileName.slice(-5) == ".web0") ? true : false ; 
		//alert('OuvrirSeleFichierLab0 : ' + fileName);
		var fileNamePath = g_Rep_PHOTOLAB + 'CMDLABO/' + fileName;
		if (isDroitCompiler(fileNamePath.substr(0,fileNamePath.length-1) + '1')){
			//alert('isDroitCompiler ok pour : ' + fileNamePath);
			g_SelectFichierLab = new File(fileNamePath);
			valRetour = OuvrirFichierLabo();
		}		
	}
	return valRetour;
}

function ChercherFichierLab(avecErreur) {
	var avecErreur = avecErreur || 'AvecLesErreurs';
	//alert('var avecErreur ? : ' + avecErreur);
	var theFolder = new Folder(g_Rep_PHOTOLAB + 'CMDLABO/');	
	var theContent = theFolder.getFiles();
	//alert('ChercherFichierLab ON');
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
			strBaseName = leFichier.substring(0, leFichier.lastIndexOf(strExtension));  

			if (!isFichierErreur(leFichier)) {	
				YAutre = false;

				for (var i = 0; i < g_TabFichierATraiter.length; i++) {
					if (strBaseName == g_TabFichierATraiter[i].substring(0, g_TabFichierATraiter[i].lastIndexOf(strExtension))){								
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
	g_SelectFichierLab = File.openDialog("selection de le fichier de la ou y a XXXXXXXX les photos qui n'en faut imprimer");
	return OuvrirFichierLabo();
}

function OuvrirFichierLabo() { 
	var valRetour = false;
	try { 
		//alert('OuvrirFichierLabo test : ' + g_SelectFichierLab);	
		if(g_SelectFichierLab && TestAPI()){
			//Select_InfoAPI.enabled = true;
			MsgINFO('Fichier Labo a compiler : ' + g_SelectFichierLab);
			g_CommandeLabo = new CommandesLabo(OuvrirFichierToTableauDeLigne(g_SelectFichierLab), g_NomFichierEnCours);			
			if (g_CommandeLabo.isRecord()){
				if (g_CommandeLabo.isValide()){
					g_CommandeLabo.InitListePlanches();
					//UI
					var premiereEcole = new Ecole (g_CommandeLabo.Ecole);				 
					//UI
					//nbFichierAGenerer.text = 'Repertoire TIRAGE :     (Nombre de planches à générer : ' + g_CommandeLabo.NbPlanchesACreer() + ')';
					Select_Generer.enabled =  (g_SelectFichierLab) ? true : false ;
					//buttonConfig.enabled = true;
					progressBar.maxvalue = g_CommandeLabo.NbPlanchesACreer();		
					valRetour = true;					
				}
			}else{
				//Gestion erreur pas de code a faire !!
				msg = "PROBLEME : Le fichier de commande n'est pas enregistré";
				AjoutBilanGeneration(msg);
				msg = "     SOLUTION : Déposer de nouveau le fichier de commande (.csv, . lab ou .web) par drag and Drop pour essayer de le ré-enregistrer !";
				AjoutBilanGeneration(msg);			
				AjoutBilanGeneration('');	
				EcrireBilan(g_SelectFichierLab.name);
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

	g_RepSOURCE = TrouverRepSOURCEdansBibliotheque(uneEcole.CodeRefEcole);
	g_RepSCRIPTSPhotoshop = TrouverRepScriptPSdansBibliotheque(uneEcole.CodeRefEcole);
    if (g_RepSOURCE ==''){
		msg = "Ecole en cours : " + g_CommandeECOLEEncours
		AjoutBilanGeneration(msg);

		msg = "     PROBLEME : PhotoLab n'a pas trouvé de dossier SOURCE dans la bibliotheque avec ce code (" + uneEcole.CodeRefEcole + ")";
		AjoutBilanGeneration(msg);
		msg = "     SOLUTION PROBABLE : Créer ou ajouter le repertoire de photo avec le code : " + uneEcole.CodeRefEcole + " dans la bibliotheque de PhotoLAb !";
		AjoutBilanGeneration(msg);			
		AjoutBilanGeneration('');
		MsgERREUR('Erreur de dossier /SOURCE !', msg);
		g_Erreur = msg;
	}
	else {

		if (isRepertoireExiste(g_RepSOURCE)){
			if (g_IsPlancheSiteWEB){
				g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'SOURCESWEB/' + uneEcole.DateTirage + "-" + uneEcole.NomEcole;
			} else {					
				//alert('Commentaire : ' + uneEcole.Commentaire.substr(0, 11) + ' Est une ecole Web !!!!! : ' + uneEcole.isEcoleWEB());
				if  (uneEcole.NomEcole.indexOf('(ISOLEES)') > -1) {
					var ladate=new Date();
					repTirage = ladate.getFullYear()+"-"+twoDigit((ladate.getMonth()+1))+"-"+twoDigit(ladate.getDate())+'-CMD-ISOLEES';
				}
				else{
					repTirage = uneEcole.DateTirage + "-" + uneEcole.NomEcole;				
				}				
				g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'TIRAGES/' + repTirage;
				g_RepMINIATURES_DateEcole = g_Rep_PHOTOLAB + 'CMDLABO/MINIATURES/' + repTirage;
			}
			nbFichiers = 0;
			nbFichiers = NbJPGArborescence(Folder(g_RepSOURCE),nbFichiers) ;

			MsgINFO('Rep SOURCE : (Nb de fichiers exploitables : ' + nbFichiers + ')', decodeURI(g_RepSOURCE));
			MsgINFO('Rep TIRAGE : (Nb de planches à générer : ' + g_CommandeLabo.NbPlanchesACreer() + ')', decodeURI(g_RepTIRAGES_DateEcole));
			//alert('InitGroupesClasseIndive : ' + uneEcole.Commentaire);
			g_GroupeIndiv.length = 0;
			if (!uneEcole.isEcoleWEB()){InitGroupesClasseIndiv(Folder(g_RepSOURCE), []); }
			valRetour = true;		
		}
		else{
			msg = "     PROBLEME : PhotoLab n'a pas trouvé de dossier SOURCE pour les photos avec ce code (" + uneEcole.CodeRefEcole + ")";
			AjoutBilanGeneration(msg);
			msg = "     SOLUTION PROBABLE : Modifier ou ajouter le code (" + uneEcole.CodeRefEcole + ") dans le nom du repertoire de l'école " + g_CommandeECOLEEncours;
			AjoutBilanGeneration(msg);			
			AjoutBilanGeneration('');
			MsgERREUR('Erreur de dossier /SOURCE !', msg);
			g_Erreur = msg;			
			
			valRetour = false;		
		}
	}
	return valRetour;
}
/*
function MAJinfoEcoleOLD(uneEcole) {   
	var valRetour = false;
	var msg = '';
	var repTirage = '';
	//UI alert('UI MAJinfoEcole()');
	g_RepSOURCE = TrouverSOURCE('('+ uneEcole.CodeRefEcole +')');
	alert('Ancien g_RepSOURCE : ' + g_RepSOURCE);
	g_RepSOURCE = TrouverRepSOURCEdansBibliotheque(uneEcole.CodeRefEcole);
	alert('Nouveau g_RepSOURCE : ' + g_RepSOURCE);
    if (g_RepSOURCE ==''){
		msg = "Ecole en cours : " + g_CommandeECOLEEncours
		AjoutBilanGeneration(msg);

		msg = "     PROBLEME : PhotoLab n'a pas trouvé de dossier SOURCE pour les photos avec ce code (" + uneEcole.CodeRefEcole + ")";
		AjoutBilanGeneration(msg);
		msg = "     Le dossier de base où doivent se trouver les Photos sources est défini comme étant : " + g_RepBASESOURCE;
		AjoutBilanGeneration(msg);
		msg = "     SOLUTION PROBABLE : Modifier ou ajouter ' (" + uneEcole.CodeRefEcole + ") ' dans le nom du repertoire de l'école !";
		AjoutBilanGeneration(msg);			
		AjoutBilanGeneration('');
		MsgERREUR('Erreur de dossier /SOURCE !', msg);
		g_Erreur = msg;
	}
	else {
		//g_RepSOURCE = g_RepSOURCE + g_SousRepSOURCE; //'/PHOTOS/SOURCE' ;
		//alert('MAJinfoEcole g_RepSOURCE !!!!!!!!!!!!!!!!!!! : ' + g_RepSOURCE);
		if (isRepertoireExiste(g_RepSOURCE)){
			if (g_IsPlancheSiteWEB){
				g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'SOURCESWEB/' + uneEcole.DateTirage + "-" + uneEcole.NomEcole;
			} else {					
				//alert('Commentaire : ' + uneEcole.Commentaire.substr(0, 11) + ' Est une ecole Web !!!!! : ' + uneEcole.isEcoleWEB());
				if  (uneEcole.NomEcole.indexOf('(ISOLEES)') > -1) {
					var ladate=new Date();
					repTirage = ladate.getFullYear()+"-"+twoDigit((ladate.getMonth()+1))+"-"+twoDigit(ladate.getDate())+'-CMD-ISOLEES';
				}
				else{
					repTirage = uneEcole.DateTirage + "-" + uneEcole.NomEcole;				
				}				
				g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'TIRAGES/' + repTirage;
				g_RepMINIATURES_DateEcole = g_Rep_PHOTOLAB + 'CMDLABO/MINIATURES/' + repTirage;
			}
			nbFichiers = 0;
			nbFichiers = NbJPGArborescence(Folder(g_RepSOURCE),nbFichiers) ;

			MsgINFO('Rep SOURCE : (Nb de fichiers exploitables : ' + nbFichiers + ')', decodeURI(g_RepSOURCE));
			MsgINFO('Rep TIRAGE : (Nb de planches à générer : ' + g_CommandeLabo.NbPlanchesACreer() + ')', decodeURI(g_RepTIRAGES_DateEcole));
			//alert('InitGroupesClasseIndive : ' + uneEcole.Commentaire);
			g_GroupeIndiv.length = 0;
			if (!uneEcole.isEcoleWEB()){InitGroupesClasseIndiv(Folder(g_RepSOURCE), []); }
			valRetour = true;		
		}
		else{
			msg = "     PROBLEME : PhotoLab n'a pas trouvé de dossier SOURCE pour les photos avec ce code (" + uneEcole.CodeRefEcole + ")";
			AjoutBilanGeneration(msg);
			msg = "     SOLUTION PROBABLE : Modifier ou ajouter le code (" + uneEcole.CodeRefEcole + ") dans le nom du repertoire de l'école " + g_CommandeECOLEEncours;
			AjoutBilanGeneration(msg);			
			AjoutBilanGeneration('');
			MsgERREUR('Erreur de dossier /SOURCE !', msg);
			g_Erreur = msg;			
			
			valRetour = false;		
		}
	}
	return valRetour;
}
*/
function GestionBoutonGenerer() { 
	g_IsGenerationEnPause = !g_IsGenerationEnPause;	
	SetBoutonGenerer();
	if (!g_IsGenerationEnCours){ //  On n'est pas en cours de generation !!!
		GenererLeFichierNOM();
		g_IsGenerationEnPause = false;	
	}		
}
/*
function GenererFichiers() { 
	//alert("GenererFichiers");
	g_IsGenerationEnCours =true;
	g_IsGenerationEnPause = false;	
	//alert("g_SelectFichierLab " + g_SelectFichierLab);
	if (! g_SelectFichierLab) {
		OuvrirSelectFichierLab0(ComboFichierLab.selection.text);
		ComboFichierLab.remove(ComboFichierLab.selection);
	}
	InitTableauFichier();
	if (g_IsPlancheSiteWEB){
		GenererFichiersWEB();
	}
	else {
		GenererFichiersLABO();
	}	
	g_IsGenerationEnCours = false;
	g_IsGenerationEnPause = true;
	SetBoutonGenerer();
	Raffraichir();
	Select_Generer.enabled = true;	
}
*/
function GenererFichiersLABO() { 
	try {
		var isEcoleOK = false;
		// On met en gris au depart ...
		PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

		var chronoDebut = new Date().getTime();
        var nbLigneFichier = g_CommandeLabo.NbLignes();
		//var cmdReste = nbLigneFichier;		
		for (var m = 0; m < nbLigneFichier; m++) {
			
			// ATTEN tion ici on n'utilise plus le tableau d eligne avec seulement ecole et planche
			// Mais tou ...
            var ligne = g_CommandeLabo.TableauLignes[m];
			if (!isEntete(ligne) && !isLigneEtat(ligne))
			{			
				if (isEcole(ligne)){// Mise à jour des SOURCES !!
					//alert("isEcole : " + ligne);
					g_CommandeECOLEEncours = ligne;	
					var uneEcole = new Ecole(g_CommandeECOLEEncours);  
					isEcoleOK = MAJinfoEcole(uneEcole);		
				}  
				else {
					//UI
					progressBar.value = m + 1;
					g_CommandeAVANCEMENT = Number( m  / g_CommandeLabo.NbPlanchesACreer());
					g_CommandeAVANCEMENT = (g_CommandeAVANCEMENT>1)?1:g_CommandeAVANCEMENT; 
					txtTraitement.text = String ( m + 1 ) + " / " +  String (nbLigneFichier);										
					if (isEcoleOK && ligne && CreerRepertoire(g_RepTIRAGES_DateEcole)){                
					
						g_CommandePDTEncours = ligne.substr(0,ligne.length-2);
						var unProduit = new Produit(ligne);  					
						
						if (unProduit.isProduitLABO()){
							var plancheCree = CreerUnProduitPourLeLaboratoire(unProduit);
						}else{
							var plancheCree = unProduit.Type;
							g_TabLigneOriginale[unProduit.indexOriginal] = plancheCree;
							MsgERREUR(plancheCree,'Produit Labo non defini : ' + g_CommandePDTEncours);
						}
						//UI
						MAJTableauGeneration(plancheCree,unProduit);
						Raffraichir(); 			
					}                   
				}
				if (!g_IsPhotoLabON ||isInterruptionTraitement()){break;};	
				// Attention*

				SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),1);
				EcrireBilan(decodeURI(g_SelectFichierLab.name));
				//alert('SauverFichierFromTableauDeLigne ' +  plancheCree);			
			}
		}
		/////// Retour sur le temps Passé   //////////////
        if (!g_IsGenerationEnPause){ 
			var nbErreur = EcrireBilan(decodeURI(g_SelectFichierLab.name));
			//alert('nbErreur ' +  nbErreur + ' g_SelectFichierLab.name : ' +  g_SelectFichierLab.name);	
			BilanFinTraitement(chronoDebut, nbErreur);		
			SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),(nbErreur?1:2));
		}

	}
	catch(err) {
		MsgERREUR("ERREUR GenererFichiersLABO()", "Fichier en cours : " + g_CommandePDTEncours + "\n" + ErreurInfoMSG(err));
		Select_Generer.enabled = true;
		//buttonConfig.enabled = true;
	}
}

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

function isInterruptionTraitement() { 

	if (g_IsGenerationEnPause){		//alert('g_IsGenerationEnPause ' + g_IsGenerationEnPause);	
		SetBoutonGenerer();
		var leMessage = 'Voulez vous stopper la creation des planches ? ';
		// affichage des erreurs :
		/*if (g_BilanGeneration.length > 0){
			for (var n = 0; n < g_BilanGeneration.length; n++) {
				leMessage = g_BilanGeneration[n] + '\n\n' + leMessage;
			}		
		}*/
		var retour = confirm(leMessage);
		if (retour == true) {
			
			EcrireBilan(decodeURI(g_SelectFichierLab.name));
			
			MsgINFO("Création annulée !");
			g_IsPhotoLabON = false;		
			return true;
		} 
		else {
			MsgINFO("La création va repartir !");
			return false;
		}
		g_IsGenerationEnPause = false;
		//Select_Generer.text = (g_IsGenerationEnPause?'>':'||');
		SetBoutonGenerer();
	}	
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
	MsgERREUR(bilan, VitesseMoy + " secondes par planche");
	txtTraitement.text = "Bilan : " + String (Minute) + " min et " + String (nbSecondes % 60) + " sec pour créeer " + String (nbPlanches - nbErreur) + " Planches."; 
	/////////////////////////////////////////////////
	if (nbErreur > 0){
		MsgERREUR("!!! ATTENTION !!!", "Il y a " + nbErreur + " planche(s) qui n'ont pas été crée(s) !!! Voir le détail dans le fichier.erreur...");
		g_BilanGeneration.push("!!! ATTENTION !!! Il y a " + nbErreur + " planche(s) qui n'ont pas été crée(s) !!! ");
	}
	
	
}

function GenererFichiersWEB() { 
	try {
		var isEcoleOK = false;
		// On met en gris au depart ...
		PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
		
		chronoDebut = new Date().getTime();
        var nbLigneFichier = g_CommandeLabo.NbLignes();
		//var cmdReste = nbLigneFichier;
		for (var m = 0; m < nbLigneFichier; m++) {
            var ligne = g_CommandeLabo.TableauLignes[m];
			if (!isEntete(ligne) && !isLigneEtat(ligne))
			{			
				if (isEcole( ligne)){// Mise à jour des SOURCES !!
					g_CommandeECOLEEncours = ligne;	
					var uneEcole = new Ecole(g_CommandeECOLEEncours);  
					//MAJinfoEcole(uneEcole);	
					isEcoleOK = MAJinfoEcole(uneEcole);							
				}  
				else {
					if (isEcoleOK && ligne && CreerRepertoire(g_RepTIRAGES_DateEcole)){                
						//UI
						progressBar.value = m + 1;
						g_CommandeAVANCEMENT = Number( m  / g_CommandeLabo.NbPlanchesACreer()); 
						g_CommandeAVANCEMENT = (g_CommandeAVANCEMENT>1)?1:g_CommandeAVANCEMENT; 					
						txtTraitement.text = String ( m + 1 ) + " / " +  String (nbLigneFichier);
						
							var unProduit = new Produit (ligne);  
							if (unProduit.isProduitLABO()){
								var plancheCree = CreerUnProduitPourLeSiteWEB(unProduit);
							}else{
								var plancheCree = unProduit.Type;
								g_TabLigneOriginale[unProduit.indexOriginal] = plancheCree;
							}

						//UI
						MAJTableauGeneration(plancheCree,unProduit);

						Raffraichir(); // A voir ?					
					}                   
				}
			}				
				//if (isInterruptionTraitement()){break;};	
				if (!g_IsPhotoLabON ||isInterruptionTraitement()){break;};	
				// Attention*

				SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),1);
				EcrireBilan(decodeURI(g_SelectFichierLab.name));
				//alert('SauverFichierFromTableauDeLigne ' +  plancheCree);				
        }
        if (!g_IsGenerationEnPause){ 
			//alert(' FIN');
			var nbErreur = EcrireBilan(decodeURI(g_SelectFichierLab.name));
			BilanFinTraitement(chronoDebut, nbErreur);		
			SauverFichierFromTableauDeLigne(decodeURI(g_SelectFichierLab.name),(nbErreur?1:2));
		}
	}
	catch(err) {
		MsgERREUR("ERREUR GenererFichiersWEB()", "Fichier en cours : " + unProduit.FichierPhoto + "\n" + ErreurInfoMSG(err));
		Select_Generer.enabled = true;
	}
}

function SetBoutonGenerer(){
	Select_Generer.image = (g_IsGenerationEnPause?ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d): ScriptUI.newImage (imgPause.a, imgPause.b, imgPause.c, imgPause.d));				
}

function InitTableauFichier() { 
	try {
		txtTraitement.text = "0 / " + String (g_CommandeLabo.NbPlanchesACreer());
		/*while (UIListeMessage.items.length > 0){
			UIListeMessage.remove(0);
		}*/
		//MsgINFO('InitTableauFichier()');
		//UIListeMessage.add ("item", "-");
		//UIListeMessage.add ("item", "-");	
		
		/*Supression UIListeMessage 
		for (var m = 0; m < g_CommandeLabo.NbPlanchesACreer() ; m++) {
			var unProduit = new Produit (g_CommandeLabo.ListePlanches[m]);
			//UIListeMessage.add ("item", FormatSTR("Commande : " + unProduit.Code, 55,' ',false)+ "  " + FormatSTR("a faire... ", 60,' ',false));
			UIListeMessage.add ("item", "Commande : " + unProduit.Code + " a faire... ");			
			//MsgINFO(FormatSTR(unProduit.Code, 55,' ',false))
		}
		*/
	}
	catch(err) {
		var msg = "Init de TableauFichier :  \n \n";
		//Commentaires.push (msg);
		//alert( msg + "\n\n" + err.message);
		MsgERREUR('Init de TableauFichier',msg + "\n\n" + err.message);
	}	
}



function MsgERREUR(Titre,Message){
	MsgINFO(Titre, Message, 'ERREUR', false, 'NL');
}

function MsgINFO(Titre, Message, PetiteColonne, isCoche, isNew){
	var isNew = isNew || 'NL';
	var Message = Message || '';
	var PetiteColonne = PetiteColonne || '...!';
	var isCoche = isCoche || true;
//QUE FAIT ON DE L'ERREUR
	//alert(Message, Titre, true);
	MAJTableauLOG(Titre, Message, PetiteColonne, isCoche, isNew);
}


function MAJTableauLOG(Col1, Col2, Col3, isCoche, isNew){
/*	
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
*/	
}

function ArborescenceWEB(){
	//laDate = Date.now();
	//laDate.getDate();
	
	/**/
	var size = 0;
	var i = 0;
	for(var fichier in g_GroupeIndiv){
		size++;
	}	
	progressBar.maxvalue = size;
	//alert('progressBar.maxvalue : ' + progressBar.maxvalue);
	//progressBar.value = 15;
	//alert('progressBar.value : ' + progressBar.value);
	Raffraichir();
	for(var fichier in g_GroupeIndiv){
		i = i + 1
		 progressBar.value = i ;
		 txtTraitement.text = String (i) + " / " + String (size);
		 fichierEnCours.text = 'traitement WEB : ' + decodeURIComponent(fichier);
		 Raffraichir();

		 //alert("fichier : " + fichier + " g_TabListeNomsClasses[fichier] : " + g_GroupeIndiv[fichier]);
		 CreerFichiersPresentationWEB(fichier, '_nb', g_GroupeIndiv[fichier] );

	}	
	fichierEnCours.text = 'traitement fichier de présentation WEB terminé !';
}

function LoadConfig() {
	var fileName = g_Rep_PHOTOLAB + 'Code/PhotoLab-config.ini';	
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
	var fileName = g_Rep_PHOTOLAB + 'Code/PhotoLab-config.ini';	
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


