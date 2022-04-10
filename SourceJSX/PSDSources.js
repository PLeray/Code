

function AfficheEditionSOURCE(uneSource) {
	LoadConfig();	
	var isMAJ = 0; // Rien
	// DIALOG
	var dlgEditSOURCE = new Window ('dialog'); 
        //dlgEditSOURCE.frameLocation = [ -4, -4 ];
        //PSJ AOUT 21 
		dlgEditSOURCE.graphics.backgroundColor = dlgEditSOURCE.graphics.newBrush (dlgEditSOURCE.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
        //PSJ AOUT 21 
		dlgEditSOURCE.graphics.foregroundColor = dlgEditSOURCE.graphics.newPen(dlgEditSOURCE.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);
        
		dlgEditSOURCE.text = "Edition d'une source de photos pour PhotoLab"; 
		dlgEditSOURCE.orientation =  "row"; 
		dlgEditSOURCE.alignChildren = ["left","top"]; 
		dlgEditSOURCE.spacing = 10; 
		dlgEditSOURCE.margins = 16; 
		
		
// ColonneGauche
// ======
var ColonneGauche = dlgEditSOURCE.add("group", undefined, {name: "ColonneGauche"}); 
    ColonneGauche.orientation = "column"; 
    ColonneGauche.alignChildren = ["left","center"]; 
    ColonneGauche.spacing = 10; 
    ColonneGauche.margins = 0; 

// ColonneDroite
// ======
var ColonneDroite = dlgEditSOURCE.add("group", undefined, {name: "ColonneDroite"}); 
    ColonneDroite.orientation = "column"; 
    ColonneDroite.alignChildren = ["left","center"]; 
    ColonneDroite.spacing = 10; 
    ColonneDroite.margins = 0; 

	var btnScanDossier = ColonneDroite.add("button", undefined, undefined, {name: "btnScanDossier"}); 
	btnScanDossier.text = "Scanner dossier source";
	btnScanDossier.helpTip = "Scanner le dossier source et verifier les nom de classes"; 


	var titrelistClasses = ColonneDroite.add("statictext", undefined, undefined, {name: "dsfsdf", justify: "center"}); 
	titrelistClasses.text = "Noms des classes trouvés :"; 
	titrelistClasses.preferredSize.width = 300; 
		
	var listClasses = ColonneDroite.add ("edittext", [0, 0, 300, 300], " ", {name: "Noms des classes :", multiline: true});
	listClasses.text = "Aucune ..."; 
	

	btnScanDossier.onClick = function () {	
		
		var typeConfig = 'Rien';
		if (radioQuattro.value){typeConfig='WEB-QUATTRO';}
		if (radioNB.value){typeConfig='NOIR-ET-BLANC';}
		//if (radioStandard.value){typeConfig='Rien';}
		
		// A supprimer ulterieureme,nt 
		SaveConfig(g_OrdreInversePlanche, typeConfig, checkPhotosGroupes.value, checkPhotosIndiv.value, checkPhotosFratrie.value);
		
		uneSource.OrdrePlancheInverse = g_OrdreInversePlanche;
		uneSource.typeConfigWeb = typeConfig;		
		uneSource.isPhotosGroupes = checkPhotosGroupes.value;
		uneSource.isPhotosIndiv = checkPhotosIndiv.value;
		uneSource.isPhotosFratrie = checkPhotosFratrie.value;
		

		g_RepSOURCE  = uneSource.DossierSources;

		g_TabListeNomsClasses = [];
		
		titrelistClasses.text =  "Scan en cours patienter..."; 
		
		InitialisationSourcePourLeWEB(Folder(g_RepSOURCE), []);
		//alert('Avant initialisation');
		var nbclasses = 0;	
		var isfratrie = false;
		var refClasse = '';
		var nomClasse = '';
		

		for(var valeur in g_TabListeNomsClasses){
			 refClasse = (refClasse == '')? valeur : (refClasse + "\n" + valeur);
			 nomClasse = (nomClasse == '')? g_TabListeNomsClasses[valeur] : (nomClasse + "\n" + g_TabListeNomsClasses[valeur]);
			 if ( nomClasse.toLowerCase().indexOf('fratrie') > -1){
				 isfratrie = true;
			}else{
				nbclasses = nbclasses + 1;	
			}

		}	
		listClasses.text = decodeURIComponent(nomClasse);
		titrelistClasses.text =  nbclasses + " classes trouvés" + (isfratrie?" et des fratries":"") + " : "; 
	};

	// group5 '1- Dossier des SOURCES du projet :'	
	var group5 = ColonneGauche.add("group", undefined, {name: "group5"}); 
		group5.orientation = "row"; 
		group5.alignChildren = ["left","center"]; 
		group5.spacing = 10; 
		group5.margins = 0; 
		

		var statictext3 = group5.add("statictext", undefined, '1- Dossier des SOURCES du projet :', {name: "statictext3"}); 			

	var btnDossier = group5.add("button", undefined, undefined, {name: "btnDossier"}); 
	btnDossier.text = "Importer dossier"; 
	btnDossier.helpTip = "Sélection du dossier à importer qui contient les photos 'Source'";
	
	btnDossier.onClick = function () {	
		var leRepSOURCE = Folder.selectDialog("Sélectionnez un dossier de Photos à importer pour: " + editNomProjet.text, g_Rep_PHOTOLAB + "/SOURCES/");	
		//var leRepSOURCE = Folder.selectDialog("Sélectionnez un dossier de Photos pour: " + editNomProjet.text);
		//alert('leRepSOURCE : ' + leRepSOURCE);
		if(leRepSOURCE){
			var leChemin = leRepSOURCE.path + '/' + leRepSOURCE.name;
			//var leChemin = leRepSOURCE.fsName.toString();
			//alert('leChemin : ' + leChemin);
			uneSource.DossierSources = leChemin;
			staticDossier.text = decodeURI(uneSource.DossierSources);
			editNomProjet.text = decodeURI(NomDossierSOURCE(leRepSOURCE.name));
		}
	}
	var staticDossier = ColonneGauche.add('edittext {properties: {name: "edittext1", readonly: true, borderless: true}}'); 
	//staticDossier.graphics.font = ScriptUI.newFont ('', '', 10);
    staticDossier.text = decodeURI(uneSource.DossierSources);
    staticDossier.preferredSize.width = 600;		
	
	//PSJ AOUT 21 
	staticDossier.graphics.foregroundColor = staticDossier.graphics.newPen (staticDossier.graphics.PenType.SOLID_COLOR, [0, 1,	0], 1);
	//PSJ AOUT 21 
	staticDossier.graphics.backgroundColor = staticDossier.graphics.newBrush (staticDossier.graphics.BrushType.SOLID_COLOR,	[0.35, 0.35, 0.35]);
	
	
	// GROUP1 '2- Nom Projet :'
	var group1 = ColonneGauche.add("group", undefined, {name: "group1"}); 
		group1.orientation = "row"; 
		group1.alignChildren = ["left","center"]; 
		group1.spacing = 10; 
		group1.margins = 0; 

		var statictext1 = group1.add("statictext", undefined, '2- Nom projet :', {name: "statictext1"}); 

		var editNomProjet = group1.add('edittext {properties: {name: "editNomProjet"}}'); 
			editNomProjet.text = decodeURI(NomDossierSOURCE(uneSource.NomProjet)); 		
			editNomProjet.preferredSize.width = 500; 
			
			

	// GROUP2 3- Années scolaire :
	var group2 = ColonneGauche.add("group", undefined, {name: "group2"}); 
		group2.orientation = "row"; 
		group2.alignChildren = ["left","center"]; 
		group2.spacing = 10; 
		group2.margins = 0; 
		
		var staticAnnee = group2.add("statictext", undefined, '3- Année scolaire :', {name: "staticAnnee"}); 

		var dropdownAnnee_array = ["2019-2020", "2020-2021","2021-2022","2022-2023", "2023-2024","2024-2025"]; 
		var dropdownAnnee = group2.add("dropdownlist", undefined, undefined, {name: "dropdownAnnee", items: dropdownAnnee_array}); 
		dropdownAnnee.selection = 2; // Annee par defaut 2020-2021
		for (var i = 0; i < dropdownAnnee.items.length; i++) {if (dropdownAnnee.items[i].text == uneSource.AnneeScolaire ){dropdownAnnee.selection = i;}}
		
		
		
	// GROUP3 4- Code Ecole :
	var group3 = ColonneGauche.add("group", undefined, {name: "group3"}); 
		group3.orientation = "row"; 
		group3.alignChildren = ["left","center"]; 
		group3.spacing = 10; 
		group3.margins = 0; 		

		var staticCodeEcole = group3.add("statictext", undefined, '4- Code école :', {name: "staticCodeEcole"}); 
			//staticCodeEcole.text = "Code Projet :"; 

		var editCodeEcole = group3.add('edittext {properties: {name: "editCodeEcole"}}'); 
			//editCodeEcole.text = leCode; 
			editCodeEcole.text = decodeURI(uneSource.CodeEcole); 
			editCodeEcole.preferredSize.width = 120; 
			
	// group4 5- Dossier de Sripts / Actions :'
	var group4 = ColonneGauche.add("group", undefined, {name: "group4"}); 
		group4.orientation = "row"; 
		group4.alignChildren = ["left","center"]; 
		group4.spacing = 10; 
		group4.margins = 0; 
		
		var statictext3 = group4.add("statictext", undefined, '5- Dossier de Sripts / Actions :', {name: "statictext3"}); 
			//statictext3.text = "Nom du dossier de Sripts (Actions) dans Photoshop à utiliser :"; 

		/*
		var editRepScriptPS = group4.add('edittext {properties: {name: "editRepScriptPS"}}'); 
			//editCodeEcole.text = leCode; 
			editRepScriptPS.text = decodeURI(uneSource.RepScriptPS); 
			editRepScriptPS.preferredSize.width = 150; 		
		*/
		GlobalVariables();
		var dropdownRepScript_array = [];
		
		
		var dropdownRepScriptPS = group4.add("dropdownlist", undefined, undefined, {name: "dropdownRepScriptPS", items: dropdownRepScript_array}); 
		//dropdownRepScriptPS.selection = 1; 

		dropdownRepScript_array = GetActionSetInfo();

		if ( dropdownRepScript_array.length > 0 ) {
			for (var i = 0; i < dropdownRepScript_array.length; i++) {
				dropdownRepScriptPS.add( "item", dropdownRepScript_array[i].name );
				//dropdownRepScriptPS.items[i].selected = true;
				if (dropdownRepScriptPS.items[i].text == uneSource.RepScriptPS ){dropdownRepScriptPS.selection = i;}
			}        
		} else {
			dropdownRepScriptPS.enabled = false;
		}
		
		var statictext31 = group4.add("statictext", undefined, ' (à utiliser pour ce projet.)', {name: "statictext3"});

	
	var divider1 = ColonneGauche.add("panel", undefined, undefined, {name: "divider1"}); 
    divider1.alignment = "fill"; 
	

	var group5 = ColonneGauche.add("group", undefined, {name: "group5"}); 
		group5.orientation = "row"; 
		group5.alignChildren = ["left","center"]; 	
	
////CONFIGURATION /////

	// PANEL1
	// ======
	var panel1 = group5.add("panel", undefined, undefined, {name: "panel1"}); 
		panel1.text = "Configuration des photos pour la boutique en ligne"; 
		panel1.orientation = "column"; 
		panel1.alignChildren = ["left","top"]; 
		panel1.spacing = 10; 
		panel1.margins = 10; 

	var statictext1 = panel1.add("statictext", undefined, undefined, {name: "statictext1"}); 
		statictext1.text = "Type :"; 

	// GROUP1
	// ======
	var group1 = panel1.add("group", undefined, {name: "group1"}); 
		group1.orientation = "row"; 
		group1.alignChildren = ["left","center"]; 
		group1.spacing = 10; 
		group1.margins = 0; 
		
	var radioStandard = group1.add("radiobutton", undefined, undefined, {name: "radioStandard"}); 
		radioStandard.text = "Standard"; 
		radioStandard.value = (uneSource.typeConfigWeb == 'Rien');		

	var radioNB = group1.add("radiobutton", undefined, undefined, {name: "radioNB"}); 
		radioNB.text = "Noir et Blanc personnalisé"; 
		radioNB.value = (uneSource.typeConfigWeb == 'NOIR-ET-BLANC');

	var radioQuattro = group1.add("radiobutton", undefined, undefined, {name: "radioQuattro"}); 
		radioQuattro.text = "Quattro"; 
		radioQuattro.value = (uneSource.typeConfigWeb == 'WEB-QUATTRO');

	// PANEL1
	// ======
	var divider1 = panel1.add("panel", undefined, undefined, {name: "divider1"}); 
		divider1.alignment = "fill"; 
		
	var statictext2 = panel1.add("statictext", undefined, undefined, {name: "statictext2"}); 
		statictext2.text = "Pour quelles photos :"; 

	// GROUP2
	// ======
	var group2 = panel1.add("group", undefined, {name: "group2"}); 
		group2.orientation = "row"; 
		group2.alignChildren = ["left","center"]; 
		group2.spacing = 10; 
		group2.margins = 0; 

	var checkPhotosGroupes = group2.add("checkbox", undefined, undefined, {name: "checkPhotosGroupes"}); 
		checkPhotosGroupes.text = "Groupes"; 
		checkPhotosGroupes.value = uneSource.isPhotosGroupes;

	var checkPhotosIndiv = group2.add("checkbox", undefined, undefined, {name: "checkPhotosIndiv"}); 
		checkPhotosIndiv.text = "Individuelles";
		checkPhotosIndiv.value = uneSource.isPhotosIndiv;		

	var checkPhotosFratrie = group2.add("checkbox", undefined, undefined, {name: "checkPhotosFratrie"}); 
		checkPhotosFratrie.text = "Fratries"; 	
		checkPhotosFratrie.value = uneSource.isPhotosFratrie;	
	

	// GROUP4
	var group4 = ColonneDroite.add("group", undefined, {name: "group4"}); 
		group4.orientation = "row"; 
		group4.alignChildren = ["right","center"]; 
		group4.spacing = 10; 
		group4.margins = [0,20,0,0]; 
		group4.alignment = ["right","top"]; 

		var btnQuitter = group4.add("button", undefined, 'Quitter', {name: "btnQuitter"}); 

			
		btnQuitter.onClick = function () {	
			dlgEditSOURCE.close();
		}	
		
		var btnSuprProjet = group4.add ('button', undefined, 'Suprimer Projet', {name: 'btnSuprProjet'});		
		btnSuprProjet.onClick = function () {	
			SuprimerSourceDepuiobjEcole(uneSource);
			isMAJ = 1; // MAJ Source
			dlgEditSOURCE.close();	
		}	
		
		var btnOK = group4.add("button", undefined, 'Enregistrer Projet', {name: "btnOK"}); 
			//btnOK.text = "Enregistrer le Projet"; 
		
		btnOK.onClick = function () {	
			uneSource.CodeEcole = editCodeEcole.text;
			uneSource.NomProjet = editNomProjet.text;
			uneSource.AnneeScolaire = dropdownAnnee.selection.text;
			//uneSource.RepScriptPS = editRepScriptPS.text;
			uneSource.RepScriptPS = dropdownRepScriptPS.selection.text;
			uneSource.DossierSources = staticDossier.text;			

		var typeConfig = 'Rien';
		if (radioQuattro.value){typeConfig='WEB-QUATTRO';}
		if (radioNB.value){typeConfig='NOIR-ET-BLANC';}
		//if (radioStandard.value){typeConfig='Rien';}		
			
			uneSource.OrdrePlancheInverse = g_OrdreInversePlanche;
			uneSource.typeConfigWeb = typeConfig;		
			uneSource.isPhotosGroupes = checkPhotosGroupes.value;
			uneSource.isPhotosIndiv = checkPhotosIndiv.value;
			uneSource.isPhotosFratrie = checkPhotosFratrie.value;			
			
			
			
			if (uneSource.isValide()){
				//MAJFichierSource();
				isMAJ = 1; // MAJ Source
				dlgEditSOURCE.close();
				ImporterSource(uneSource);
												
			}else{alert('Tout les champs doivent être remplis !');}
		}		

	dlgEditSOURCE.show();
	return isMAJ;
}



/////////////////////////////////////////////////////////////////////
// Function: GlobalVariables
// Usage: global action items that are reused
// Input: <none>
// Return: <none>
/////////////////////////////////////////////////////////////////////
function GlobalVariables() {
    gClassActionSet = charIDToTypeID( 'ASet' );
    gClassAction = charIDToTypeID( 'Actn' );
    gKeyName = charIDToTypeID( 'Nm  ' );
    gKeyNumberOfChildren = charIDToTypeID( 'NmbC' );
}

/////////////////////////////////////////////////////////////////////
// Function: GetActionSetInfo
// Usage: walk all the items in the action palette and record the action set
//        names and all the action children
// Input: <none>
// Return: the array of all the ActionData
// Note: This will throw an error during a normal execution. There is a bug
// in Photoshop that makes it impossible to get an acurate count of the number
// of action sets.
/////////////////////////////////////////////////////////////////////
function GetActionSetInfo() {
    var actionSetInfo = new Array();
    var setCounter = 1;
      while ( true ) {
        var ref = new ActionReference();
        ref.putIndex( gClassActionSet, setCounter );
        var desc = undefined;
        try { desc = executeActionGet( ref ); }
        catch( e ) { break; }
        var actionData = new ActionData();
        if ( desc.hasKey( gKeyName ) ) {
            actionData.name = desc.getString( gKeyName );
        }
        var numberChildren = 0;
        if ( desc.hasKey( gKeyNumberOfChildren ) ) {
            numberChildren = desc.getInteger( gKeyNumberOfChildren );
        }
        if ( numberChildren ) {
            actionData.children = GetActionInfo( setCounter, numberChildren );
            actionSetInfo.push( actionData );
        }
        setCounter++;
    }
    return actionSetInfo;
	//alert (actionSetInfo);
}

/////////////////////////////////////////////////////////////////////
// Function: GetActionInfo
// Usage: used when walking through all the actions in the action set
// Input: action set index, number of actions in this action set
// Return: true or false, true if file or folder is to be displayed
/////////////////////////////////////////////////////////////////////
function GetActionInfo( setIndex, numChildren ) {
    var actionInfo = new Array();
    for ( var i = 1; i <= numChildren; i++ ) {
        var ref = new ActionReference();
        ref.putIndex( gClassAction, i );
        ref.putIndex( gClassActionSet, setIndex );
        var desc = undefined;
        desc = executeActionGet( ref );
        var actionData = new ActionData();
        if ( desc.hasKey( gKeyName ) ) {
            actionData.name = desc.getString( gKeyName );
        }
        var numberChildren = 0;
        if ( desc.hasKey( gKeyNumberOfChildren ) ) {
            numberChildren = desc.getInteger( gKeyNumberOfChildren );
        }
        actionInfo.push( actionData );
    }
    return actionInfo;
}

/////////////////////////////////////////////////////////////////////
// Function: ActionData
// Usage: this could be an action set or an action
// Input: <none>
// Return: a new Object of ActionData
/////////////////////////////////////////////////////////////////////
function ActionData() {
    this.name = "";
    this.children = undefined;
    this.toString = function () {
        var strTemp = this.name;
        if ( undefined != this.children ) {
            for ( var i = 0; i < this.children.length; i++ ) {
                strTemp += " " + this.children[i].toString();
            }
        }
        return strTemp;
    }
}
//////////
function AfficheImportationSOURCE2(dossierAImporter, DossierDestination) {
	if (dossierAImporter != DossierDestination) {
		var leRep = Folder(dossierAImporter)
		if (leRep) {
			//AfficheImportationSOURCE(theFolder.fullName, 'Copy');
			var dlgImportationSOURCE = new Window("dialog"); 	
			//var dlgImportationSOURCE = new Window('palette'); 
				dlgImportationSOURCE.text = "Importation SOURCE"; 
				dlgImportationSOURCE.preferredSize.width = 400; 
				dlgImportationSOURCE.orientation = "column"; 
				dlgImportationSOURCE.alignChildren = ["center","top"]; 
				dlgImportationSOURCE.spacing = 10; 
				dlgImportationSOURCE.margins = 16; 
						
				//PSJ AOUT 21 
				dlgImportationSOURCE.graphics.backgroundColor = dlgImportationSOURCE.graphics.newBrush (dlgImportationSOURCE.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

				//PSJ AOUT 21 
				dlgImportationSOURCE.graphics.foregroundColor = dlgImportationSOURCE.graphics.newPen (dlgImportationSOURCE.graphics.PenType.SOLID_COLOR, [0.9, 0.9, 0.9], 1);				

			var statictext1 = dlgImportationSOURCE.add("statictext", undefined, undefined, {name: "statictext1"}); 
				statictext1.text = "Importation SOURCE ..."; 


			var leRep = Folder(dossierAImporter)
			var theContent = leRep.getFiles();
			var nbfichier = theContent.length;
			
			var isImpor = false;
			dlgImportationSOURCE.onActivate = function () {			
				if (!isImpor){
					isImpor = true;
					//alert("le onActivate");
					//statictext1.text = 	"Importation SOURCE ...     0 / " +  String (nbfichier);

					for (var n = 0; n < nbfichier; n++) {
					  var theObject = theContent[n]; 
						if (theObject.constructor.name != "Folder") {
							if (theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg") {
								var fichierACopier = new File(theObject); 	
								var nomFichierSOURCE = fichierACopier.name.substr(0,fichierACopier.name.length-4);
								nomFichierSOURCE = nomFichierSOURCE.replace('.', ' '); // ATTENTION NE SUPPRIME QU4UN SEUL POINT VOIR REGEX PAs de point dans le nome de la classe
								//alert("le Fichier : " + fichierACopier + "    vers : " + DossierDestination + '/' + nomFichierSOURCE + '.jpg' );
								if (!fichierACopier.copy(DossierDestination + '/' + nomFichierSOURCE + '.jpg')) alert('ERREUR IMPORTATION SOURCE : ' + fichierACopier.error); 	
								//statictext1.text = 	"Importation SOURCE ...     " + String ( n + 1 ) + " / " +  String (nbfichier);
								//if ((  n %  50 ) == 0) {Raffraichir(); } // tout les 50
								
							}
						}
					}
					//statictext1.text = "Importation SOURCE : Terminé !"; 
					//Raffraichir();
					dlgImportationSOURCE.close();				
				}

		}
		dlgImportationSOURCE.show();	
		}
	}
}


function AfficheImportationSOURCE(dossierAImporter, DossierDestination) {
	if (dossierAImporter != DossierDestination) {	
		var leRep = Folder(dossierAImporter)
		if (leRep) {
			//AfficheImportationSOURCE(theFolder.fullName, 'Copy');
			//var dlgImportationSOURCE = new Window("dialog"); 	
			var dlgImportationSOURCE = new Window('palette'); 
				dlgImportationSOURCE.text = "Importation SOURCE"; 
				dlgImportationSOURCE.preferredSize.width = 400; 
				dlgImportationSOURCE.orientation = "column"; 
				dlgImportationSOURCE.alignChildren = ["center","top"]; 
				dlgImportationSOURCE.spacing = 10; 
				dlgImportationSOURCE.margins = 16; 
						
				//PSJ AOUT 21 
				dlgImportationSOURCE.graphics.backgroundColor = dlgImportationSOURCE.graphics.newBrush (dlgImportationSOURCE.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

				//PSJ AOUT 21 
				dlgImportationSOURCE.graphics.foregroundColor = dlgImportationSOURCE.graphics.newPen (dlgImportationSOURCE.graphics.PenType.SOLID_COLOR, [0.9, 0.9, 0.9], 1);				

			var statictext1 = dlgImportationSOURCE.add("statictext", undefined, undefined, {name: "statictext1"}); 
				statictext1.text = "Importation SOURCE ..."; 


			var leRep = Folder(dossierAImporter)
			var theContent = leRep.getFiles();
			var nbfichier = theContent.length;		

			dlgImportationSOURCE.show();
			statictext1.text = 	"Importation SOURCE ...     0 / " +  String (nbfichier);
			
			for (var n = 0; n < nbfichier; n++) {
			  var theObject = theContent[n]; 
				if (theObject.constructor.name != "Folder") {
					if (theObject.name.slice(-4) == ".JPG" || theObject.name.slice(-4) == ".jpg") {
						var fichierACopier = new File(theObject); 
						//alert("le Fichier : " + fichierACopier + "    vers : " + DossierDestination + fichierACopier.name);	
						var nomFichierSOURCE = fichierACopier.name.substr(0,fichierACopier.name.length-4);

						nomFichierSOURCE = SuprimerToutCharactere(nomFichierSOURCE, '.'); 
						if (!fichierACopier.copy(DossierDestination + '/' + nomFichierSOURCE + '.jpg'  )) alert('ERREUR IMPORTATION SOURCE : ' + fichierACopier.error); 	
						statictext1.text = 	"Importation SOURCE ...     " + String ( n + 1 ) + " / " +  String (nbfichier);
						if ((  n %  50 ) == 0) {Raffraichir(); } // tout les 50
						
					}
				}
			}
			statictext1.text = "Importation SOURCE : Terminé !"; 
			Raffraichir();
			dlgImportationSOURCE.close();		
		}
	}
}

function SuprimerToutCharactere(chaine, charactere){
	while(chaine.indexOf(charactere) > -1){
		chaine = chaine.replace(charactere, ' ');	
	}
    return chaine;
}

function ImporterSource(uneSource) {
	var destSource = g_Rep_PHOTOLAB  + 'SOURCES/' + uneSource.AnneeScolaire;
	if (destSource != uneSource.DossierSources) {
		var theFolder = Folder(uneSource.DossierSources);
		if (theFolder) {
			//var destSource = g_Rep_PHOTOLAB  + 'SOURCES/' + uneSource.AnneeScolaire;
			if (CreerUnDossier(destSource)){
				destSource = destSource  + '/' + uneSource.NomProjet;
				//alert("le Dos " + destSource);
				if (CreerUnDossier(destSource)){
					AfficheImportationSOURCE(theFolder.fullName, destSource);
					uneSource.DossierSources = destSource;
				}			
			}
		}
		
		
	}
}


function NomDossierSOURCE(chaine){
   /*alert( '"'+chaine+'"');
 chaine = removeDiacritics(chaine);
  chaine = chaine.replace(/[~`!@#$%^&*()+={}\[\];:\'\"<>.,\/\\\?-_]/g, '');
  chaine = chaine.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
   chaine = chaine.replace(/ /g, '-');
*/
    return chaine;
}


var changes;
function removeDiacritics (str) {
    if(!changes) {
        changes = defaultDiacriticsRemovalMap;
    }
    for(var i=0; i<changes.length; i++) {
        str = str.replace(changes[i].letters, changes[i].base);
    }
    return str;
}

function MAJFichierScriptPSP() {	
	GlobalVariables();
	var Ligne = '';
	var g_Rep_Script = g_Rep_PHOTOLAB  + 'GABARITS/';
	var fichierScriptPSP = g_Rep_Script+"ActionsScriptsPSP.csv";
	var file = new File(fichierScriptPSP);
	var actionInfo = GetActionSetInfo();
	//alert(fichierScriptPSP);
	file.encoding='UTF-8';
	file.open("w");	
    if ( actionInfo.length > 0 ) {
        for ( var i = 0; i < actionInfo.length; i++ ) {	
			Ligne = actionInfo[i].name + ';'			
			if ( Ligne.indexOf('PHOTOLAB') > -1) {
				/**/
				for ( var j = 0; j < actionInfo[i].children.length; j++ ) {
					Ligne += actionInfo[i].children[j].name + ';';				
				}
				file.writeln(Ligne); // On Ecrit les entetes du csv					
				Ligne = '';	
			}		
        }
    } 
	file.close();			
}