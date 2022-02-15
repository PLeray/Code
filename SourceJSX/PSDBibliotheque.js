#include PSDSources.js;

var g_LesSources = [];

function LireFichierSource() {	
	g_LesSources = [];	
	var file = new File(g_FichierSource);
	if (file.exists){	
		file.encoding='UTF-8';
		file.open("r"); // open file with write access
			file.readln(); // On Passe les entetes du csv
			while(!file.eof){
				var uneSource = new objSourceCSV(file.readln()); 
				//if (uneSource.isValide()) {g_LesSources.push(uneSource);}
				g_LesSources.push(uneSource);
				//g_LesSourcesListe.push(uneSource.Affiche());
			}
		file.close();
		
	}
	/*else{
		alert ('Pas de SOURCE : ' + g_FichierSource);
	}*/
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
	  if (aAnneeScolaire< b.AnneeScolaire)
		 return -1;
	  if (a.AnneeScolaire > b.AnneeScolaire )
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
        file.writeln('Code;NomProjet;AnneeScolaire;Rep Scripts PS;DossierSources;OrdrePlancheInverse;typeConfigWeb;isPhotosGroupes;isPhotosIndiv;isPhotosFratrie'); // On Ecrit les entetes du csv	
		for (var n = 0; n < g_LesSources.length; n++) {
			//test = test + g_LesSources[n].LigneCSV();
			file.writeln(g_LesSources[n].LigneCSV());
		}	
		//file.writeln(' '); // MAc
		//file.writeln(';;;;;;;;;'); // MAc
		//alert (test);
	file.close();			
}

function RecupSourceDepuisobjEcole(uneEcole) {
    var laSource = null;
	
	for (var n = 0; n < g_LesSources.length; n++) {
		
		if ((g_LesSources[n].CodeEcole == uneEcole.CodeEcole) && (g_LesSources[n].AnneeScolaire == uneEcole.AnneeScolaire)){	
			laSource = g_LesSources[n];  
			//alert("g_LesSources[n].CodeEcole : " + g_LesSources[n].CodeEcole +  "   "   + g_LesSources[n].AnneeScolaire);
			break;   
		}         
	}	
	//alert("laSource.CodeEcole : "+laSource.CodeEcole +  " laSource.AnneeScolaire " + laSource.AnneeScolaire);
	return laSource;	
}

function RecupSourceDepuisAnneeetCode(leCode, anneeScolaire) {
		var laSource = null;
		for (var n = 0; n < g_LesSources.length; n++) {
			if ((g_LesSources[n].CodeEcole == leCode) && (g_LesSources[n].AnneeScolaire == anneeScolaire)){	
				laSource = g_LesSources[n];  
				break;            
			}
		}	
		return laSource;	
}

function SuprimerSourceDepuiobjEcole(uneEcole) {
	for (var n = 0; n < g_LesSources.length; n++) {
		if ((g_LesSources[n].CodeEcole == uneEcole.CodeEcole) && (g_LesSources[n].AnneeScolaire == CodeEcole.AnneeScolaire)){			
            g_LesSources.splice(n, 1);
			break;            
		}
	}	
}

function TrouverRepSOURCEdansBibliotheque(uneEcole) {
	var repSource = '';
	//alert(" TrouverRepSOURCEda uneEcole.CodeEcole " + uneEcole.CodeEcole);
	LireFichierSource();
	var laSource = RecupSourceDepuisobjEcole(uneEcole);
	if (laSource){
		repSource = laSource.DossierSources;
	}
	//alert(" TrouverRepSOURCEda uneEcole.CodeEcole " + uneEcole.CodeEcole + " REP :  " + repSource);
	return repSource;
	g_LesSources = [];
}

function TrouverRepScriptPSdansBibliotheque(uneEcole) {
	var repSource = '';
	LireFichierSource();
	var laSource = RecupSourceDepuisobjEcole(uneEcole);
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
	this.AnneeScolaire = this.TableauInfo[2] || "";
	this.RepScriptPS = this.TableauInfo[3] || "PHOTOLAB-Studio²";	
	this.DossierSources = this.TableauInfo[4] || "";
	

	this.OrdrePlancheInverse = this.TableauInfo[5] || "true";
	this.typeConfigWeb = this.TableauInfo[6] || "WEB-QUATTRO";		
	
	this.isPhotosGroupes = ((this.TableauInfo[7] || "false") == 'true');	
	this.isPhotosIndiv = ((this.TableauInfo[8] || "true") == 'true');	
	this.isPhotosFratrie = ((this.TableauInfo[9] || "false") == 'true');	
	

	this.LigneCSV = function(){
		return this.CodeEcole + ';' + this.NomProjet + ';' + 
		this.AnneeScolaire + ';' + this.RepScriptPS + ';' + 
		this.DossierSources + ';'	+ this.OrdrePlancheInverse + ';' + 
		this.typeConfigWeb + ';' + this.isPhotosGroupes + ';' + 
		this.isPhotosIndiv  + ';' + this.isPhotosFratrie ;
	};
	/*	
	this.LigneCSV = function(){
		return 
		this.CodeEcole + ';' + 
		this.NomProjet + ';' + 
		this.AnneeScolaire  + ';' + 
		this.RepScriptPS + ';' + 
		this.DossierSources;
	};	
*/
	
	this.isValide = function(){
		return (this.CodeEcole != '')&&(this.NomProjet != '')&&(this.AnneeScolaire != '')&&(Folder(this.DossierSources).exists);		
	};
}

function AfficheListeSOURCE() {
	var valRetour = 0; // Rien
	if (LireFichierSource()){ // il y a au moins une source
		//var dlgListeSOURCE = new Window ('palette {text: "Bibliotheque des sources photos PhotoLab", alignChildren: "fill"}');
    var dlgListeSOURCE = new Window ('dialog',"Bibliotheque des sources de photos pour PhotoLab");
        dlgListeSOURCE.alignChildren = ["left","top"]; 
                //dlgListeSOURCE.frameLocation = [ -4, -4 ];
        //PSJ AOUT 21 
		dlgListeSOURCE.graphics.backgroundColor = dlgListeSOURCE.graphics.newBrush (dlgListeSOURCE.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
        //PSJ AOUT 21 
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
				
				

	var btnQuitter = group1.add("button", undefined, 'Quitter', {name: "btnQuitter"}); 
	btnQuitter.alignment = ["right","top"]; 	
	btnQuitter.onClick = function () {	
		dlgListeSOURCE.close();
	}				
                
	// GROUP2
	var group2 = dlgListeSOURCE.add ("group");
		var listSOURCE = group2.add ('listbox', [0, 0, 800, 250]," ",{numberOfColumns: 5, showHeaders: true, columnTitles: ["Code", "AnneeScolaire", "Nom du projet", "Dossier Script PS", "Dossier source"]});
		
		Init = function () {//INIT	
			TRIERSource();
			listSOURCE.removeAll ();
			for (var i = 0; i < g_LesSources.length; i++) {
				with (listSOURCE.add ('item', g_LesSources[i].CodeEcole)){
					subItems[0].text = g_LesSources[i].AnneeScolaire;            
					subItems[1].text = g_LesSources[i].NomProjet;
					subItems[2].text = g_LesSources[i].RepScriptPS; 
					subItems[3].text = decodeURI(g_LesSources[i].DossierSources);
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
				
		/*btnNewProjet.onClick = AfficheNouvelleSOURCE();	*/	
		
		rechTxtProjet.onChanging = function () {
			var temp = this.text.toLowerCase();
			listSOURCE.removeAll ();
			for (var i = 0; i < g_LesSources.length; i++) {
				if (g_LesSources[i].NomProjet.toLowerCase().indexOf(temp) > -1) {
					with (listSOURCE.add ('item', g_LesSources[i].CodeEcole)){
					subItems[0].text = g_LesSources[i].AnneeScolaire;            
					subItems[1].text = g_LesSources[i].NomProjet;
					subItems[2].text = g_LesSources[i].RepScriptPS; 
					subItems[3].text = decodeURI(g_LesSources[i].DossierSources);
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
				//alert('listSOURCE.selection.text ' + listSOURCE.selection.text );
				//alert('listSOURCE.selection.text ' + listSOURCE.selection.text + ' subItems[0] ' + listSOURCE.selection.subItems[0]);
				valRetour = AfficheEditionSOURCE(RecupSourceDepuisAnneeetCode(listSOURCE.selection.text,listSOURCE.selection.subItems[0]));	
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
		
		if (dlgListeSOURCE.show () != 2){			//return listSOURCE.selection.text;
			return valRetour;
		}
		//dlgListeSOURCE.close();
        g_LesSources = [];
	}
}



		




