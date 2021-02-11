﻿/*var g_Rep_PHOTOLAB = Folder($.fileName).parent.parent + "/";



//var picked = AfficheListeSOURCE();
//alert ('picked : ' + g_FichierSource);

AfficheListeSOURCE();*/

var g_LesSources = [];

function LireFichierSource() {	
	g_LesSources = [];	
	var file = new File(g_FichierSource);
	if (file.exists){	
		file.open("r"); // open file with write access
			file.readln(); // On Passe les entetes du csv
			while(!file.eof){
				var uneSource = new objSourceCSV(file.readln()); 
				g_LesSources.push(uneSource);
				//g_LesSourcesListe.push(uneSource.Affiche());
			}
		file.close();
		/**/
	}
	else{
		alert ('Pas de SOURCE : ' + g_FichierSource);
	}
	return g_FichierSource.length;
}

function TRIERSource() {
	g_LesSources.sort(function compareNomProjet(a, b) {
	  if (a.NomProjet < b.NomProjet)
		 return -1;
	  if (a.NomProjet > b.NomProjet )
		 return 1;
	  return 0;
	});
	/*g_LesSources.sort(function compareAnnee(a, b) {
	  if (a.Annee < b.Annee)
		 return -1;
	  if (a.Annee > b.Annee )
		 return 1;
	  return 0;
	});		*/
	g_LesSources.reverse();		
}

function MAJFichierSource() {	
	//var test ='';
	var file = new File(g_FichierSource);
	file.encoding='UTF-8';
	file.open("w");	
        file.writeln('Code;NomProjet;Annee;Rep Scripts PS;Repertoire'); // On Ecrit les entetes du csv	
		for (var n = 0; n < g_LesSources.length; n++) {
			//test = test + g_LesSources[n].LigneCSV();
			file.writeln(g_LesSources[n].LigneCSV());
		}	
		//alert (test);
	file.close();			
}

function RecupSourceDepuisCode(leCode) {
    var laSource = null;
	for (var n = 0; n < g_LesSources.length; n++) {
		if (g_LesSources[n].CodeEcole == leCode){	
            laSource = g_LesSources[n];  
			break;            
		}
	}	
	return laSource;	
}

function SuprimerSourceDepuisCode(leCode) {
	for (var n = 0; n < g_LesSources.length; n++) {
		if (g_LesSources[n].CodeEcole == leCode){	
		//alert('g_LesSources[n].CodeEcole ' + g_LesSources[n].CodeEcole);
            g_LesSources.splice(n, 1);
			break;            
		}
	}	
}

function TrouverRepSOURCEdansBibliotheque(leCode) {
	var repSource = '';
	LireFichierSource();
	var laSource = RecupSourceDepuisCode(leCode)
	if (laSource){
		repSource = laSource.Repertoire;
	}
	return repSource;
	g_LesSources = [];
}

function TrouverRepScriptPSdansBibliotheque(leCode) {
	var repSource = '';
	LireFichierSource();
	var laSource = RecupSourceDepuisCode(leCode)
	if (laSource){
		repSource = laSource.RepScriptPS;
	}
	return repSource;
	g_LesSources = [];
}

function objSourceCSV(uneLigne) {
	this.TableauInfo = uneLigne?uneLigne.split(';'):[];
    
    this.CodeEcole = this.TableauInfo[0] || "";
	this.NomProjet = this.TableauInfo[1] || "";
	this.Annee = this.TableauInfo[2] || "";
	this.RepScriptPS = this.TableauInfo[3] || "PHOTOLAB-Studio²";	
	this.Repertoire = this.TableauInfo[4] || "";
	
	this.LigneCSV = function(){return this.CodeEcole + ';'	+ this.NomProjet + ';' + this.Annee  + ';' + this.RepScriptPS + ';' + this.Repertoire;};
	this.isValide = function(){
		return (this.CodeEcole != '')&&(this.NomProjet != '')&&(this.Annee != '')&&(Folder(this.Repertoire).exists);		
		//return (this.CodeEcole != '')&&(this.NomProjet != '')&&(this.Annee != '')&&(Folder(encodeURI(this.Repertoire)).exists);
		//alert("Folder(this.Repertoire).exists  : " + Folder(this.Repertoire).exists);	
	};
}

function AfficheListeSOURCE() {
	var valRetour = 0; // Rien
	if (LireFichierSource()){ // il y a au moins une source
		//var dlgListeSOURCE = new Window ('palette {text: "Bibliotheque des sources photos PhotoLab", alignChildren: "fill"}');
    var dlgListeSOURCE = new Window ('dialog',"Bibliotheque des sources photos PhotoLab");
        dlgListeSOURCE.alignChildren = ["left","top"]; 
                //dlgListeSOURCE.frameLocation = [ -4, -4 ];
        dlgListeSOURCE.graphics.backgroundColor = dlgListeSOURCE.graphics.newBrush (dlgListeSOURCE.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
        dlgListeSOURCE.graphics.foregroundColor = dlgListeSOURCE.graphics.newPen(dlgListeSOURCE.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);
        
		// GROUP1
		var group1 = dlgListeSOURCE.add("group", undefined, {name: "group1"}); 
			group1.orientation = "row"; 
			group1.alignChildren = ["left","center"]; 
			group1.spacing = 10; 
			group1.margins = 0; 
			
            var btnNewProjet = group1.add ('button', undefined, 'Ajouter un Projet', {name: 'btnNewProjet'});
			
            var statictext1 = group1.add("statictext", undefined, undefined, {name: "statictext1"}); 
                    statictext1.text = "Recherche par Nom de projet :"; 

            var rechTxtProjet = group1.add('edittext {properties: {name: "editNomProjet"}}'); 		
                rechTxtProjet.preferredSize.width = 300; 	
                
		// GROUP2
	var group2 = dlgListeSOURCE.add ("group");
		var listSOURCE = group2.add ('listbox', [0, 0, 800, 250]," ",{numberOfColumns: 5, showHeaders: true, columnTitles: ["Code", "Année", "Nom du projet", "Dossier Script PS", "Répertoire source"]});
		
		Init = function () {//INIT	
			TRIERSource();
			listSOURCE.removeAll ();
			for (var i = 0; i < g_LesSources.length; i++) {
				with (listSOURCE.add ('item', g_LesSources[i].CodeEcole)){
					subItems[0].text = g_LesSources[i].Annee;            
					subItems[1].text = g_LesSources[i].NomProjet;
					subItems[2].text = g_LesSources[i].RepScriptPS; 
					subItems[3].text = decodeURI(g_LesSources[i].Repertoire);
				}
			}    
		}
		
		btnNewProjet.onClick = function () {
			var uneNouvelleSource = new objSourceCSV(); 
			valRetour = AfficheEditionSOURCE(uneNouvelleSource);	
			if(valRetour > 0 ){
				//alert('uneNouvelleSource : ' + uneNouvelleSource.NomProjet);
				g_LesSources.push(uneNouvelleSource);
				MAJFichierSource();
				Init();
				if(valRetour == 3 ){
					//alert('valRetour : ' + valRetour);
					dlgListeSOURCE.close();
					//return valRetour;
				}		
			}
			listSOURCE.selection = null;
			//Pour compil Web :
			//dlgListeSOURCE.close () ;
		}			
		
		rechTxtProjet.onChanging = function () {
			var temp = this.text.toLowerCase();
			listSOURCE.removeAll ();
			for (var i = 0; i < g_LesSources.length; i++) {
				if (g_LesSources[i].NomProjet.toLowerCase().indexOf(temp) > -1) {
					with (listSOURCE.add ('item', g_LesSources[i].CodeEcole)){
					subItems[0].text = g_LesSources[i].Annee;            
					subItems[1].text = g_LesSources[i].NomProjet;
					subItems[2].text = g_LesSources[i].RepScriptPS; 
					subItems[3].text = decodeURI(g_LesSources[i].Repertoire);
					}
				}
			}
			if (listSOURCE.items.length > 0){
				listSOURCE.selection = null;
			}
		}
		//listSOURCE.numberOfColumns= 6;
		//rechTxtProjet.onChanging();
		listSOURCE.onChange = function(){
			if(listSOURCE.selection != null){
				valRetour = AfficheEditionSOURCE(RecupSourceDepuisCode(listSOURCE.selection.text));	
				if(valRetour > 0 ){				
					MAJFichierSource();
					Init(); 
					
					if(valRetour == 3 ){
						//alert('valRetour : ' + valRetour);
						dlgListeSOURCE.close();
						//return valRetour;
					}						
					
				}
				listSOURCE.selection = null;	
			}	
		}	
		Init();
		listSOURCE.selection = null;
		

		// We need the button to catch the Return/Enter key (CC and later)
		//dlgListeSOURCE.add ('button', undefined, 'Ok', {name: 'ok'});
        //dlgListeSOURCE.show () ;
		
		if (dlgListeSOURCE.show () != 2){			//return listSOURCE.selection.text;
			return valRetour;
		}
		//dlgListeSOURCE.close();
        g_LesSources = [];
	}
}

//function EditionSOURCE(leCode , lAnnee , leNomProjet , leRepertoire ) {
function AfficheEditionSOURCE(uneSource) {
	//var isNEW = (uneSource == null);	
	//alert('uneSourceisNEW : ' + isNEW);
	var isMAJ = 0; // Rien
	// DIALOG
	var dlgEditSOURCE = new Window("dialog"); 
        //dlgEditSOURCE.frameLocation = [ -4, -4 ];
        dlgEditSOURCE.graphics.backgroundColor = dlgEditSOURCE.graphics.newBrush (dlgEditSOURCE.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
        dlgEditSOURCE.graphics.foregroundColor = dlgEditSOURCE.graphics.newPen(dlgEditSOURCE.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);
        
		dlgEditSOURCE.text = "Edition d'une source PhotoLab"; 
		dlgEditSOURCE.orientation = "column"; 
		dlgEditSOURCE.alignChildren = ["left","top"]; 
		dlgEditSOURCE.spacing = 10; 
		dlgEditSOURCE.margins = 16; 


	// group5 '1- Dossier des SOURCES du projet :'	
	var group5 = dlgEditSOURCE.add("group", undefined, {name: "group5"}); 
		group5.orientation = "row"; 
		group5.alignChildren = ["left","center"]; 
		group5.spacing = 10; 
		group5.margins = 0; 
		

		var statictext3 = group5.add("statictext", undefined, '1- Dossier des SOURCES du projet :', {name: "statictext3"}); 			

	var btnRepertoire = group5.add("button", undefined, undefined, {name: "btnRepertoire"}); 
	btnRepertoire.text = "Sélectionner dossier"; 
	btnRepertoire.helpTip = "Sélection du dossier contenant les photos 'Source'";
	
	btnRepertoire.onClick = function () {	
		var leRepSOURCE = Folder.selectDialog("Sélectionnez un dossier de Photos pour: " + editNomProjet.text, g_Rep_PHOTOLAB + "/SOURCES/");	
		//var leRepSOURCE = Folder.selectDialog("Sélectionnez un dossier de Photos pour: " + editNomProjet.text);
		//alert('leRepSOURCE : ' + leRepSOURCE);
		if(leRepSOURCE){
			var leChemin = leRepSOURCE.path + '/' + leRepSOURCE.name;
			//var leChemin = leRepSOURCE.fsName.toString();
			//alert('leChemin : ' + leChemin);
			uneSource.Repertoire = leChemin;
			staticRepertoire.text = decodeURI(uneSource.Repertoire);
			editNomProjet.text = decodeURI(leRepSOURCE.name);
		}
	}
	var staticRepertoire = dlgEditSOURCE.add('edittext {properties: {name: "edittext1", readonly: true, borderless: true}}'); 
	//staticRepertoire.graphics.font = ScriptUI.newFont ('', '', 10);
    staticRepertoire.text = decodeURI(uneSource.Repertoire);
    staticRepertoire.preferredSize.width = 600;		
	
	staticRepertoire.graphics.foregroundColor = staticRepertoire.graphics.newPen (staticRepertoire.graphics.PenType.SOLID_COLOR, [0, 1,
	0], 1);
	staticRepertoire.graphics.backgroundColor = staticRepertoire.graphics.newBrush (staticRepertoire.graphics.BrushType.SOLID_COLOR,
	[0.35, 0.35, 0.35]);
	
	
	// GROUP1 '2- Nom Projet :'
	var group1 = dlgEditSOURCE.add("group", undefined, {name: "group1"}); 
		group1.orientation = "row"; 
		group1.alignChildren = ["left","center"]; 
		group1.spacing = 10; 
		group1.margins = 0; 

		var statictext1 = group1.add("statictext", undefined, '2- Nom projet :', {name: "statictext1"}); 

		var editNomProjet = group1.add('edittext {properties: {name: "editNomProjet"}}'); 
			editNomProjet.text = decodeURI(uneSource.NomProjet); 		
			editNomProjet.preferredSize.width = 500; 
			
			

	// GROUP2 3- Années scolaire :
	var group2 = dlgEditSOURCE.add("group", undefined, {name: "group2"}); 
		group2.orientation = "row"; 
		group2.alignChildren = ["left","center"]; 
		group2.spacing = 10; 
		group2.margins = 0; 
		
		var staticAnnee = group2.add("statictext", undefined, '3- Années scolaire :', {name: "staticAnnee"}); 

		var dropdownAnnee_array = ["2019-2020", "2020-2021","2021-2022","2022-2023", "2023-2024","2024-2025"]; 
		var dropdownAnnee = group2.add("dropdownlist", undefined, undefined, {name: "dropdownAnnee", items: dropdownAnnee_array}); 
		dropdownAnnee.selection = 1; // Annee par defaut 2020-2021
		for (var i = 0; i < dropdownAnnee.items.length; i++) {if (dropdownAnnee.items[i].text == uneSource.Annee ){dropdownAnnee.selection = i;}}
		
		
		
	// GROUP3 4- Code Ecole :
	var group3 = dlgEditSOURCE.add("group", undefined, {name: "group3"}); 
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
	var group4 = dlgEditSOURCE.add("group", undefined, {name: "group4"}); 
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
		
		var statictext31 = group4.add("statictext", undefined, ' (à utiliser pour ce projet photo.)', {name: "statictext3"});

	
	var btnArboWeb = dlgEditSOURCE.add("button", undefined, undefined, {name: "btnArboWeb"}); 
	btnArboWeb.text = "Création des fichiers WEB";
	btnArboWeb.helpTip = "Création du repertoire structuré pour transférer sur l'interface Lumys"; 
	
	btnArboWeb.onClick = function () {	
		if (uneSource.isValide()){
			if(AfficheClassesAvantArboWeb(uneSource)){
				isMAJ = 3; // MAJ Source + Arrbo WEB
				dlgEditSOURCE.close();
			}								
		}else{alert('Tout les champs doivent être remplis !');}	
	}	

	// GROUP4
	var group4 = dlgEditSOURCE.add("group", undefined, {name: "group4"}); 
		group4.orientation = "row"; 
		group4.alignChildren = ["right","center"]; 
		group4.spacing = 10; 
		group4.margins = [0,20,0,0]; 
		group4.alignment = ["right","top"]; 

		var btnAnnuler = group4.add("button", undefined, 'Annuler', {name: "btnAnnuler"}); 
			//btnAnnuler.text = "Annuler"; 
			
		btnAnnuler.onClick = function () {	
			dlgEditSOURCE.close();
		}	
		
		var btnSuprProjet = group4.add ('button', undefined, 'Suprimer ce Projet', {name: 'btnSuprProjet'});		
		btnSuprProjet.onClick = function () {	
			SuprimerSourceDepuisCode(uneSource.CodeEcole);
			isMAJ = 1; // MAJ Source
			dlgEditSOURCE.close();	
		}	
		
		var btnOK = group4.add("button", undefined, 'Enregistrer le Projet', {name: "btnOK"}); 
			//btnOK.text = "Enregistrer le Projet"; 
		
		btnOK.onClick = function () {	
			uneSource.CodeEcole = editCodeEcole.text;
			uneSource.NomProjet = editNomProjet.text;
			uneSource.Annee = dropdownAnnee.selection.text;
			//uneSource.RepScriptPS = editRepScriptPS.text;
			uneSource.RepScriptPS = dropdownRepScriptPS.selection.text;
			uneSource.Repertoire = staticRepertoire.text;			
			if (uneSource.isValide()){
				//MAJFichierSource();
				isMAJ = 1; // MAJ Source
				dlgEditSOURCE.close();								
			}else{alert('Tout les champs doivent être remplis !');}
		}		

	dlgEditSOURCE.show();
	return isMAJ;
}

//function AfficheClassesAvantArboWeb(NomProjet, leRertoireSource){
function AfficheClassesAvantArboWeb(uneSource){
	LoadConfig();	
	var valRetour = false;
	// DIALOG
	// ======
	var dlgArboWEB = new Window("dialog", 'WEB : ' + uneSource.NomProjet); 
		//dlgArboWEB.text = "Validation des noms de classes"; 
		dlgArboWEB.orientation = "column"; 
		dlgArboWEB.alignChildren = ["center","top"]; 
		dlgArboWEB.spacing = 10; 
		dlgArboWEB.margins = 16; 
		
		//dlgArboWEB.frameLocation = [ 0, 20 ];
		dlgArboWEB.graphics.backgroundColor = dlgArboWEB.graphics.newBrush (dlgArboWEB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

		dlgArboWEB.graphics.foregroundColor =dlgArboWEB.graphics.newPen (dlgArboWEB.graphics.PenType.SOLID_COLOR, [0.9, 0.9, 0.9], 1);			
	
	// GROUPEINFO2
	// ===========
	var GroupeInfo2 = dlgArboWEB.add("group", undefined, {name: "GroupeInfo2"}); 
		GroupeInfo2.orientation = "column"; 
		GroupeInfo2.alignChildren = ["center","top"]; 
		GroupeInfo2.spacing = 10; 
		GroupeInfo2.margins = 0; 	
	
	var statictext3 = GroupeInfo2.add("statictext", undefined, undefined, {name: "dsfsdf", justify: "center"}); 
	statictext3.text = "Noms des classes trouvés :"; 
	statictext3.preferredSize.width = 300; 
		
	var listtext3 = GroupeInfo2.add ("edittext", [0, 0, 300, 400], " ", {name: "Noms des classes :", multiline: true});
	listtext3.text = "Aucune ..."; 
		
////////A VOIR POSITION




////CONFIGURATION /////

	// PANEL1
	// ======
	var panel1 = dlgArboWEB.add("panel", undefined, undefined, {name: "panel1"}); 
		panel1.text = "Configuration de la compilation Web"; 
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
		radioStandard.value = (g_CONFIGtypeConfigWeb == 'Rien');		

	var radioNB = group1.add("radiobutton", undefined, undefined, {name: "radioNB"}); 
		radioNB.text = "Noir et Blanc personnalisé"; 
		radioNB.value = (g_CONFIGtypeConfigWeb == 'NOIR-ET-BLANC');

	var radioQuattro = group1.add("radiobutton", undefined, undefined, {name: "radioQuattro"}); 
		radioQuattro.text = "Quattro"; 
		radioQuattro.value = (g_CONFIGtypeConfigWeb == 'WEB-QUATTRO');

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
		checkPhotosGroupes.value = g_CONFIGisPhotosGroupes;

	var checkPhotosIndiv = group2.add("checkbox", undefined, undefined, {name: "checkPhotosIndiv"}); 
		checkPhotosIndiv.text = "Individuelles";
		checkPhotosIndiv.value = g_CONFIGisPhotosIndiv;		

	var checkPhotosFratrie = group2.add("checkbox", undefined, undefined, {name: "checkPhotosFratrie"}); 
		checkPhotosFratrie.text = "Fratries"; 	
		checkPhotosFratrie.value = g_CONFIGisPhotosFratrie;
	

	
	
	// GROUPEINFO4
	// ===========
	var GroupeInfo4 = dlgArboWEB.add("group", undefined, {name: "GroupeInfo4"}); 
		GroupeInfo4.orientation = "row"; 
		GroupeInfo4.alignChildren = ["left","center"]; 
		GroupeInfo4.spacing = 10; 
		GroupeInfo4.margins = 0; 
		GroupeInfo4.alignment = ["right","top"]; 	

	var buttonGenerererArboWEB = GroupeInfo4.add("button", undefined, undefined, {name: "buttonGenerererArboWEB"}); 
		buttonGenerererArboWEB.text = "Générer Arborescence WEB"; 
		
		
	buttonGenerererArboWEB.onClick = function () {	
			g_RepTIRAGES_DateEcole = g_Rep_PHOTOLAB + 'WEB-ARBO/LUMYS-' +  uneSource.NomProjet;
			valRetour = true;
			var typeConfig = '';
			if (radioQuattro.value){typeConfig='WEB-QUATTRO';}
			if (radioNB.value){typeConfig='NOIR-ET-BLANC';}
			if (radioStandard.value){typeConfig='Rien';}
			
			SaveConfig(g_OrdreInversePlanche, typeConfig, checkPhotosGroupes.value, checkPhotosIndiv.value, checkPhotosFratrie.value);
			g_RepSCRIPTSPhotoshop = uneSource.RepScriptPS;
			//return valRetour;	
			
			dlgArboWEB.close();	
	}		
	
	dlgArboWEB.onActivate = function(){	
		app.refresh(); // or, alternatively, waitForRedraw(); 
		dlgArboWEB.update(); // A voir sur MAC?
		
		g_RepSOURCE  = uneSource.Repertoire;

		g_TabListeNomsClasses = [];
		
		InitialisationSourcePourLewEB(Folder(g_RepSOURCE), []);
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
		listtext3.text = decodeURIComponent(nomClasse);
		statictext3.text =  nbclasses + " classes trouvés" + (isfratrie?" et des fratries":"") + " : "; 
	};
	
	

	//dlgArboWEB.show();
	
	if (dlgArboWEB.show () != 2){			
		return valRetour;	
	}
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
