var g_Rep_PHOTOLAB = Folder($.fileName).parent.parent + "/";

var g_FichierSource = g_Rep_PHOTOLAB + 'Code/Sources.csv';
var g_LesSources = [];
//var picked = AfficheListeSOURCE();
//alert ('picked : ' + g_FichierSource);

AfficheListeSOURCE();

function LireFichierSource() {	
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
		g_LesSources.sort(function compareNomProjet(a, b) {
		  if (a.NomProjet < b.NomProjet)
			 return -1;
		  if (a.NomProjet > b.NomProjet )
			 return 1;
		  return 0;
		});
		g_LesSources.sort(function compareAnnee(a, b) {
		  if (a.Annee < b.Annee)
			 return -1;
		  if (a.Annee > b.Annee )
			 return 1;
		  return 0;
		});		
		g_LesSources.reverse();	
	}
	else{
		alert ('Pas de SOURCE !');
	}
	return g_FichierSource.length;
}

function MAJFichierSource() {	
	//var test ='';
	file = new File(g_FichierSource);
	file.open("w");	
        file.writeln('Code;NomProjet;Annee;Repertoire'); // On Ecrit les entetes du csv	
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

function objSourceCSV(uneLigne) {
	this.TableauInfo = uneLigne?uneLigne.split(';'):[];
    
    this.CodeEcole = this.TableauInfo[0] || "";
	this.NomProjet = this.TableauInfo[1] || "";
	this.Annee = this.TableauInfo[2] || "";
	this.Repertoire = this.TableauInfo[3] || "";
	
	this.LigneCSV = function(){return this.CodeEcole + ';'	+ this.NomProjet + ';' + this.Annee + ';' + this.Repertoire;};
	this.isValide = function(){return (this.CodeEcole != '')&&(this.NomProjet != '')&&(this.Annee != '')&&(Folder(this.Repertoire).exists)};	
	
}

function AfficheListeSOURCE() {
	if (LireFichierSource()){ // il y a au moins une source
		var dlgListeSOURCE = new Window ('dialog {text: "Bibliotheque des sources photos PhotoLab", alignChildren: "fill"}');
		// GROUP1
		var group1 = dlgListeSOURCE.add("group", undefined, {name: "group1"}); 
			group1.orientation = "row"; 
			group1.alignChildren = ["left","center"]; 
			group1.spacing = 10; 
			group1.margins = 0; 
			
            var btnNewProjet = group1.add ('button', undefined, 'Ajouter un Projet', {name: 'btnNewProjet'});
			btnNewProjet.onClick = function () {
				var uneNouvelleSource = new objSourceCSV(); 				
				if(AfficheEditionSOURCE(uneNouvelleSource)){
					//alert('uneNouvelleSource : ' + uneNouvelleSource.NomProjet);
					g_LesSources.push(uneNouvelleSource);
					MAJFichierSource();
					Init(); 
				}
				listSOURCE.selection = null;
			}	
			
            var statictext1 = group1.add("statictext", undefined, undefined, {name: "statictext1"}); 
                    statictext1.text = "Recherche par Nom de projet :"; 

            var rechTxtProjet = group1.add('edittext {properties: {name: "editNomProjet"}}'); 		
                rechTxtProjet.preferredSize.width = 300; 	
                
		// GROUP2
		var group2 = dlgListeSOURCE.add ("group");
		var listSOURCE = group2.add ('listbox', [0, 0, 800, 250]," ",{numberOfColumns: 4, showHeaders: true, columnTitles: ["Code", "Année", "Nom du projet", "Répertoire source"]});
		
		Init = function () {//INIT	
			listSOURCE.removeAll ();
			for (var i = 0; i < g_LesSources.length; i++) {
				with (listSOURCE.add ('item', g_LesSources[i].CodeEcole)){
					subItems[0].text = g_LesSources[i].Annee;            
					subItems[1].text = g_LesSources[i].NomProjet;
					subItems[2].text = g_LesSources[i].Repertoire;
				}
			}    
		}
		Init();
		listSOURCE.selection = null;
		
		rechTxtProjet.onChanging = function () {
			var temp = this.text.toLowerCase();
			listSOURCE.removeAll ();
			for (var i = 0; i < g_LesSources.length; i++) {
				if (g_LesSources[i].NomProjet.toLowerCase().indexOf(temp) > -1) {
					with (listSOURCE.add ('item', g_LesSources[i].CodeEcole)){
						subItems[0].text = g_LesSources[i].Annee;            
						subItems[1].text = g_LesSources[i].NomProjet;
						subItems[2].text = g_LesSources[i].Repertoire;
					}
				}
			}
			if (listSOURCE.items.length > 0){
				listSOURCE.selection = null;
			}
		}
		
		listSOURCE.onChange = function(){
			if(listSOURCE.selection != null){
				if(AfficheEditionSOURCE(RecupSourceDepuisCode(listSOURCE.selection.text))){
					MAJFichierSource();
					Init(); 
				}
				listSOURCE.selection = null;	
			}	
		}	

		// We need the button to catch the Return/Enter key (CC and later)
		//dlgListeSOURCE.add ('button', undefined, 'Ok', {name: 'ok'});
		if (dlgListeSOURCE.show () != 2){
			//return listSOURCE.selection.text;
		}
		dlgListeSOURCE.close();
	}
}

//function EditionSOURCE(leCode , lAnnee , leNomProjet , leRepertoire ) {
function AfficheEditionSOURCE(uneSource) {
	//var isNEW = (uneSource == null);	
	//alert('uneSourceisNEW : ' + isNEW);
	var isMAJ = false;
	// DIALOG
	var dlgEditPOURCE = new Window("dialog"); 
		dlgEditPOURCE.text = "Edition d'une source PhotoLab"; 
		dlgEditPOURCE.orientation = "column"; 
		dlgEditPOURCE.alignChildren = ["left","top"]; 
		dlgEditPOURCE.spacing = 10; 
		dlgEditPOURCE.margins = 16; 

	// GROUP1
	var group1 = dlgEditPOURCE.add("group", undefined, {name: "group1"}); 
		group1.orientation = "row"; 
		group1.alignChildren = ["left","center"]; 
		group1.spacing = 10; 
		group1.margins = 0; 

		var statictext1 = group1.add("statictext", undefined, undefined, {name: "statictext1"}); 
			statictext1.text = "Nom Projet :"; 

		var editNomProjet = group1.add('edittext {properties: {name: "editNomProjet"}}'); 
			//editNomProjet.text = leNomProjet; 
			editNomProjet.text = uneSource.NomProjet; 		
			editNomProjet.preferredSize.width = 300; 

	// GROUP2
	var group2 = dlgEditPOURCE.add("group", undefined, {name: "group2"}); 
		group2.orientation = "row"; 
		group2.alignChildren = ["left","center"]; 
		group2.spacing = 10; 
		group2.margins = 0; 

		var dropdownAnnee_array = ["2019-2020", "2020-2021","2021-2022","2022-2023", "2023-2024","2024-2025"]; 
		var dropdownAnnee = group2.add("dropdownlist", undefined, undefined, {name: "dropdownAnnee", items: dropdownAnnee_array}); 
		dropdownAnnee.selection = 2; 
		for (var i = 0; i < dropdownAnnee.items.length; i++) {if (dropdownAnnee.items[i].text == uneSource.Annee ){dropdownAnnee.selection = i;}}

		var statictext2 = group2.add("statictext", undefined, undefined, {name: "statictext2"}); 
			statictext2.text = "Code Projet :"; 

		var editCodeEcole = group2.add('edittext {properties: {name: "editCodeEcole"}}'); 
			//editCodeEcole.text = leCode; 
			editCodeEcole.text = uneSource.CodeEcole; 
			editCodeEcole.preferredSize.width = 150; 
	
	var staticRepertoire = dlgEditPOURCE.add('edittext {properties: {name: "edittext1", readonly: true, borderless: true}}'); 
	//staticRepertoire.graphics.font = ScriptUI.newFont ('', '', 10);
    staticRepertoire.text = uneSource.Repertoire;
    staticRepertoire.preferredSize.width = 500;
		
	var btnRepertoire = dlgEditPOURCE.add("button", undefined, undefined, {name: "btnRepertoire"}); 
	btnRepertoire.text = "Selection du repertoire Source"; 
	
	btnRepertoire.onClick = function () {	
		var leRepSOURCE = Folder.selectDialog("Sélectionnez un repertoire de Photos :");
		//alert('leRepSOURCE : ' + leRepSOURCE);
		if(leRepSOURCE){
			//var leChemin = leRepSOURCE.path + '/' + leRepSOURCE.name;
			var leChemin = leRepSOURCE.fsName.toString();
			//alert('leChemin : ' + leChemin);
			staticRepertoire.text = leChemin ;
		}
	}

	// GROUP4
	var group4 = dlgEditPOURCE.add("group", undefined, {name: "group4"}); 
		group4.orientation = "row"; 
		group4.alignChildren = ["right","center"]; 
		group4.spacing = 10; 
		group4.margins = [0,20,0,0]; 
		group4.alignment = ["right","top"]; 

		var btnAnnuler = group4.add("button", undefined, undefined, {name: "btnAnnuler"}); 
			btnAnnuler.text = "Annuler"; 
			
		btnAnnuler.onClick = function () {	
			dlgEditPOURCE.close();
		}	

		var btnOK = group4.add("button", undefined, undefined, {name: "btnOK"}); 
			btnOK.text = "OK"; 
		
		btnOK.onClick = function () {	
			uneSource.CodeEcole = editCodeEcole.text;
			uneSource.NomProjet = editNomProjet.text;
			uneSource.Annee = dropdownAnnee.selection.text;
			uneSource.Repertoire = staticRepertoire.text;
			if (uneSource.isValide()){
				//MAJFichierSource();
				isMAJ = true;
				dlgEditPOURCE.close();								
			}else{alert('Tout les champs doivent être remplis !');}
		}		

	dlgEditPOURCE.show();
	return isMAJ;
}

