
// DIALOG
// ======
var dialog = new Window("dialog"); 
    dialog.text = "Paramétrage de PhotoLab"; 
    dialog.orientation = "column"; 
    dialog.alignChildren = ["right","top"]; 
    dialog.spacing = 10; 
    dialog.margins = 16; 

// PANEL1
// ======
var panel1 = dialog.add("panel", undefined, undefined, {name: "panel1"}); 
    panel1.text = "Généralités"; 
    panel1.orientation = "column"; 
    panel1.alignChildren = ["left","top"]; 
    panel1.spacing = 10; 
    panel1.margins = 10; 

// GROUP1
// ======
var group1 = panel1.add("group", undefined, {name: "group1"}); 
    group1.orientation = "row"; 
    group1.alignChildren = ["left","center"]; 
    group1.spacing = 10; 
    group1.margins = 0; 
    group1.alignment = ["right","top"]; 

var statictext1 = group1.add("statictext", undefined, undefined, {name: "statictext1"}); 
    statictext1.helpTip = "Saisit ici son code client"; 
    statictext1.text = "Code Client"; 

var edittext1 = group1.add('edittext {properties: {name: "edittext1"}}'); 
    edittext1.text = "AMP2018"; 
    edittext1.preferredSize.width = 400; 

// GROUP2
// ======
var group2 = panel1.add("group", undefined, {name: "group2"}); 
    group2.orientation = "row"; 
    group2.alignChildren = ["left","center"]; 
    group2.spacing = 10; 
    group2.margins = 0; 
    group2.alignment = ["right","top"]; 

var statictext2 = group2.add("statictext", undefined, undefined, {name: "statictext2"}); 
    statictext2.helpTip = "voir dans la config de votre serveur ou est installé PhotoLab ou XamPP, Wamp, ..."; 
    statictext2.text = "url du site PhotoLab local"; 

var edittext2 = group2.add('edittext {properties: {name: "edittext2"}}'); 
    edittext2.text = "http://localhost/PhotoLab/"; 
    edittext2.preferredSize.width = 400; 

// GROUP3
// ======
var group3 = panel1.add("group", undefined, {name: "group3"}); 
    group3.orientation = "row"; 
    group3.alignChildren = ["left","center"]; 
    group3.spacing = 10; 
    group3.margins = 0; 
    group3.alignment = ["right","top"]; 

var statictext3 = group3.add("statictext", undefined, undefined, {name: "statictext3"}); 
    statictext3.helpTip = "A prioris rien à changer ici ..."; 
    statictext3.text = "url du site PhotoLab en ligne"; 

var edittext3 = group3.add('edittext {properties: {name: "edittext3"}}'); 
    edittext3.text = "https://www.photolab-site.fr"; 
    edittext3.preferredSize.width = 400; 

// PANEL2
// ======
var panel2 = dialog.add("panel", undefined, undefined, {name: "panel2"}); 
    panel2.text = "Localisation des photos Source"; 
    panel2.orientation = "column"; 
    panel2.alignChildren = ["left","top"]; 
    panel2.spacing = 10; 
    panel2.margins = 10; 

// GROUP4
// ======
var group4 = panel2.add("group", undefined, {name: "group4"}); 
    group4.orientation = "row"; 
    group4.alignChildren = ["left","center"]; 
    group4.spacing = 10; 
    group4.margins = 0; 
    group4.alignment = ["right","top"]; 

var statictext4 = group4.add("statictext", undefined, undefined, {name: "statictext4"}); 
    statictext4.helpTip = "Tous les scripts (ou actions) de photoshop qui sont utilisés par photolab\ndoivent se trouver dans ce dossier de scripts de Photoshop"; 
    statictext4.text = "Nom du dossier de script à utiliser dans Photoshop"; 

var edittext4 = group4.add('edittext {properties: {name: "edittext4"}}'); 
    edittext4.text = "PhotoLab-Script"; 
    edittext4.preferredSize.width = 400; 

// GROUP5
// ======
var group5 = panel2.add("group", undefined, {name: "group5"}); 
    group5.orientation = "row"; 
    group5.alignChildren = ["left","center"]; 
    group5.spacing = 10; 
    group5.margins = 0; 
    group5.alignment = ["right","top"]; 

var statictext5 = group5.add("statictext", undefined, undefined, {name: "statictext5"}); 
    statictext5.helpTip = "C'est dans ce dossier que se trouveront les sous dossier comprenant le code du projet.\nPar exemple un dossier nommé 'St Joseph-Nantes (AFF3456)'"; 
    statictext5.text = "Dossier de base des  photos sources "; 

var edittext5 = group5.add('edittext {properties: {name: "edittext5"}}'); 
    edittext5.text = "'D:\Prises de vue\'"; 
    edittext5.preferredSize.width = 400; 

// GROUP6
// ======
var group6 = panel2.add("group", undefined, {name: "group6"}); 
    group6.orientation = "row"; 
    group6.alignChildren = ["left","center"]; 
    group6.spacing = 10; 
    group6.margins = 0; 
    group6.alignment = ["right","top"]; 

var statictext6 = group6.add("statictext", undefined, undefined, {name: "statictext6"}); 
    statictext6.helpTip = "Si vos projet sont directement sous le répertoire de Base,\nexemple : D:\Prises de vue\St Joseph-Nantes (AFF3456) saisir 1\nexemple : D:\Prises de vue\Novembre 2019\St Joseph-Nantes (AFF3456) saisir 2\nexemple : D:\Prises de vue\2019-2020\Novembre 2019\St Joseph-Nantes (AFF3456) saisir 3"; 
    statictext6.text = "Profondeur de sous niveau à explorer "; 

var edittext6 = group6.add('edittext {properties: {name: "edittext6"}}'); 
    edittext6.text = "2"; 
    edittext6.preferredSize.width = 400; 

// GROUP7
// ======
var group7 = panel2.add("group", undefined, {name: "group7"}); 
    group7.orientation = "row"; 
    group7.alignChildren = ["left","center"]; 
    group7.spacing = 10; 
    group7.margins = 0; 
    group7.alignment = ["right","top"]; 

var statictext7 = group7.add("statictext", undefined, undefined, {name: "statictext7"}); 
    statictext7.helpTip = "Selon son organisation de dossier 'projet', on peut souhaiter ranger les photos à exploiter dans un sous dossier particulier. A saisir ici"; 
    statictext7.text = "Sous dossier source d'un dossier 'projet'"; 

var edittext7 = group7.add('edittext {properties: {name: "edittext7"}}'); 
    edittext7.text = "\PHOTOS\SOURCE"; 
    edittext7.preferredSize.width = 400; 

// PANEL3
// ======
var panel3 = dialog.add("panel", undefined, undefined, {name: "panel3"}); 
    panel3.text = "(optionnel) Paramètres FTP imprimeur"; 
    panel3.orientation = "column"; 
    panel3.alignChildren = ["left","top"]; 
    panel3.spacing = 10; 
    panel3.margins = 10; 

// GROUP8
// ======
var group8 = panel3.add("group", undefined, {name: "group8"}); 
    group8.orientation = "row"; 
    group8.alignChildren = ["left","center"]; 
    group8.spacing = 10; 
    group8.margins = 0; 
    group8.alignment = ["right","top"]; 

var statictext8 = group8.add("statictext", undefined, undefined, {name: "statictext8"}); 
    statictext8.helpTip = "Saisir le nom du serveur FTP d'échange avec l'imprimeur (optionnel)"; 
    statictext8.text = "Hôte"; 

var edittext8 = group8.add('edittext {properties: {name: "edittext8"}}'); 
    edittext8.text = "crepes.o2ftp.net"; 
    edittext8.preferredSize.width = 400; 

// GROUP9
// ======
var group9 = panel3.add("group", undefined, {name: "group9"}); 
    group9.orientation = "row"; 
    group9.alignChildren = ["left","center"]; 
    group9.spacing = 10; 
    group9.margins = 0; 
    group9.alignment = ["right","top"]; 

var statictext9 = group9.add("statictext", undefined, undefined, {name: "statictext9"}); 
    statictext9.helpTip = "Login FTP (optionnel)"; 
    statictext9.text = "Identifiant"; 

var edittext9 = group9.add('edittext {properties: {name: "edittext9"}}'); 
    edittext9.text = "bibi"; 
    edittext9.preferredSize.width = 400; 

// GROUP10
// =======
var group10 = panel3.add("group", undefined, {name: "group10"}); 
    group10.orientation = "row"; 
    group10.alignChildren = ["left","center"]; 
    group10.spacing = 10; 
    group10.margins = 0; 
    group10.alignment = ["right","top"]; 

var statictext10 = group10.add("statictext", undefined, undefined, {name: "statictext10"}); 
    statictext10.helpTip = "Mot de passe FTP (optionnel)"; 
    statictext10.text = "Mot de passe"; 

var edittext10 = group10.add('edittext {properties: {name: "edittext10"}}'); 
    edittext10.text = "*****"; 
    edittext10.preferredSize.width = 400; 

// GROUP11
// =======
var group11 = panel3.add("group", undefined, {name: "group11"}); 
    group11.orientation = "row"; 
    group11.alignChildren = ["left","center"]; 
    group11.spacing = 10; 
    group11.margins = 0; 
    group11.alignment = ["right","top"]; 

var statictext11 = group11.add("statictext", undefined, undefined, {name: "statictext11"}); 
    statictext11.helpTip = "Login FTP (optionnel)"; 
    statictext11.text = "Dossier distant ou déposer les tirages"; 

var edittext11 = group11.add('edittext {properties: {name: "edittext11"}}'); 
    edittext11.text = "/LABO-TIRAGES"; 
    edittext11.preferredSize.width = 400; 

// GROUP12
// =======
var group12 = dialog.add("group", undefined, {name: "group12"}); 
    group12.orientation = "row"; 
    group12.alignChildren = ["left","center"]; 
    group12.spacing = 10; 
    group12.margins = 0; 

var button1 = group12.add("button", undefined, undefined, {name: "button1"}); 
    button1.text = "Annuler"; 

var button2 = group12.add("button", undefined, undefined, {name: "button2"}); 
    button2.text = "Valider"; 

dialog.show();

//photoshop.quit()



// CONFIG AVANT 18-10

function DLGConfiguration(){
	// DIALOG
	// ======
	var dialog = new Window("dialog"); 
		dialog.text = "Paramétrage de PhotoLab"; 
		dialog.orientation = "column"; 
		dialog.alignChildren = ["right","top"]; 
		dialog.spacing = 10; 
		dialog.margins = 16; 
		
		dialog.frameLocation = [ -1,0 ];
		dialog.graphics.backgroundColor = dialog.graphics.newBrush (dialog.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

		dialog.graphics.foregroundColor =UIRepertoireSource.graphics.newPen (UIRepertoireSource.graphics.PenType.SOLID_COLOR, [0.9, 0.9, 0.9], 1);	

	// PANEL1
	// ======
	var panel1 = dialog.add("panel", undefined, undefined, {name: "panel1"}); 
		panel1.text = "Généralités"; 
		panel1.orientation = "column"; 
		panel1.alignChildren = ["left","top"]; 
		panel1.spacing = 10; 
		panel1.margins = 10; 
	// GROUP1
	// ======
	var group1 = panel1.add("group", undefined, {name: "group1"}); 
		group1.orientation = "row"; 
		group1.alignChildren = ["left","center"]; 
		group1.spacing = 10; 
		group1.margins = 0; 
		group1.alignment = ["right","top"]; 
	var staticCodeClient = group1.add("statictext", undefined, undefined, {name: "staticCodeClient"}); 
		staticCodeClient.text = "Code Client"; 
	var editCodeClient = group1.add('edittext {properties: {name: "editCodeClient"}}'); 
		editCodeClient.text = "AMP2018"; 
		editCodeClient.preferredSize.width = 400; 
		editCodeClient.helpTip = "Saisir ici son code client";
	// GROUP2
	// ======
	var group2 = panel1.add("group", undefined, {name: "group2"}); 
		group2.orientation = "row"; 
		group2.alignChildren = ["left","center"]; 
		group2.spacing = 10; 
		group2.margins = 0; 
		group2.alignment = ["right","top"]; 
	var staticURLLocal = group2.add("statictext", undefined, undefined, {name: "staticURLLocal"}); 
		staticURLLocal.text = "url du site PhotoLab local"; 
	var editURLLocal = group2.add('edittext {properties: {name: "editURLLocal"}}'); 
		editURLLocal.text = "http://localhost/PhotoLab/"; 
		editURLLocal.preferredSize.width = 400; 
		editURLLocal.helpTip = "voir dans la config de votre serveur ou est installé PhotoLab ou XamPP, Wamp, ..."; 
	// GROUP3
	// ======
	var group3 = panel1.add("group", undefined, {name: "group3"}); 
		group3.orientation = "row"; 
		group3.alignChildren = ["left","center"]; 
		group3.spacing = 10; 
		group3.margins = 0; 
		group3.alignment = ["right","top"]; 
	var staticURLOnLigne = group3.add("statictext", undefined, undefined, {name: "staticURLOnLigne"}); 
		staticURLOnLigne.text = "url du site PhotoLab en ligne"; 
	var editURLOnLigne = group3.add('edittext {properties: {name: "editURLOnLigne"}}'); 
		editURLOnLigne.text = "https://www.photolab-site.fr"; 
		editURLOnLigne.preferredSize.width = 400; 
		editURLOnLigne.helpTip = "A prioris rien à changer ici ..."; 
	// PANEL2
	// ======
	var panel2 = dialog.add("panel", undefined, undefined, {name: "panel2"}); 
		panel2.text = "Localisation des photos Source"; 
		panel2.orientation = "column"; 
		panel2.alignChildren = ["left","top"]; 
		panel2.spacing = 10; 
		panel2.margins = 10; 
	// GROUP4
	// ======
	var group4 = panel2.add("group", undefined, {name: "group4"}); 
		group4.orientation = "row"; 
		group4.alignChildren = ["left","center"]; 
		group4.spacing = 10; 
		group4.margins = 0; 
		group4.alignment = ["right","top"]; 
	var staticDossierScript = group4.add("statictext", undefined, undefined, {name: "staticDossierScript"}); 
		staticDossierScript.text = "Nom du dossier de script à utiliser dans Photoshop"; 
	var editDossierScript = group4.add('edittext {properties: {name: "editDossierScript"}}'); 
		editDossierScript.text = "PhotoLab-Script"; 
		editDossierScript.preferredSize.width = 400; 
		editDossierScript.helpTip = "Tous les scripts (ou actions) de photoshop qui sont utilisés par photolab\ndoivent se trouver dans ce dossier de scripts de Photoshop"; 
	// GROUP5
	// ======
	var group5 = panel2.add("group", undefined, {name: "group5"}); 
		group5.orientation = "row"; 
		group5.alignChildren = ["left","center"]; 
		group5.spacing = 10; 
		group5.margins = 0; 
		group5.alignment = ["right","top"]; 
	var staticDossierBASESource = group5.add("statictext", undefined, undefined, {name: "staticDossierBASESource"}); 
		staticDossierBASESource.text = "Dossier de base des  photos sources "; 
	var editDossierBASESource = group5.add('edittext {properties: {name: "editDossierBASESource"}}'); 
		editDossierBASESource.text = "'D:\Prises de vue\'"; 
		editDossierBASESource.preferredSize.width = 400; 
		editDossierBASESource.helpTip = "C'est dans ce dossier que se trouveront les sous dossier comprenant le code du projet.\nPar exemple un dossier nommé 'St Joseph-Nantes (AFF3456)'"; 
	// GROUP6
	// ======
	var group6 = panel2.add("group", undefined, {name: "group6"}); 
		group6.orientation = "row"; 
		group6.alignChildren = ["left","center"]; 
		group6.spacing = 10; 
		group6.margins = 0; 
		group6.alignment = ["right","top"]; 
	var staticProfondeurSOURCE = group6.add("statictext", undefined, undefined, {name: "staticProfondeurSOURCE"}); 
		staticProfondeurSOURCE.text = "Profondeur de sous niveau à explorer "; 
	var editProfondeurSOURCE = group6.add('edittext {properties: {name: "editProfondeurSOURCE"}}'); 
		editProfondeurSOURCE.text = "2"; 
		editProfondeurSOURCE.preferredSize.width = 400; 
		editProfondeurSOURCE.helpTip = "Si vos projet sont directement sous le répertoire de Base,\nexemple : D:\Prises de vue\St Joseph-Nantes (AFF3456) saisir 1\nexemple : D:\Prises de vue\Novembre 2019\St Joseph-Nantes (AFF3456) saisir 2\nexemple : D:\Prises de vue\2019-2020\Novembre 2019\St Joseph-Nantes (AFF3456) saisir 3"; 
	// GROUP7
	// ======
	var group7 = panel2.add("group", undefined, {name: "group7"}); 
		group7.orientation = "row"; 
		group7.alignChildren = ["left","center"]; 
		group7.spacing = 10; 
		group7.margins = 0; 
		group7.alignment = ["right","top"]; 
	var staticURLSousDossierSOURCE = group7.add("statictext", undefined, undefined, {name: "staticURLSousDossierSOURCE"}); 
		staticURLSousDossierSOURCE.text = "Sous dossier source d'un dossier 'projet'"; 
	var editURLSousDossierSOURCE = group7.add('edittext {properties: {name: "editURLSousDossierSOURCE"}}'); 
		editURLSousDossierSOURCE.text = "\PHOTOS\SOURCE"; 
		editURLSousDossierSOURCE.preferredSize.width = 400; 
		editURLSousDossierSOURCE.helpTip = "Selon son organisation de dossier 'projet', on peut souhaiter ranger les photos à exploiter dans un sous dossier particulier. A saisir ici"; 
	// PANEL3
	// ======
	var panel3 = dialog.add("panel", undefined, undefined, {name: "panel3"}); 
		panel3.text = "(optionnel) Paramètres FTP imprimeur"; 
		panel3.orientation = "column"; 
		panel3.alignChildren = ["left","top"]; 
		panel3.spacing = 10; 
		panel3.margins = 10; 
	// GROUP8
	// ======
	var group8 = panel3.add("group", undefined, {name: "group8"}); 
		group8.orientation = "row"; 
		group8.alignChildren = ["left","center"]; 
		group8.spacing = 10; 
		group8.margins = 0; 
		group8.alignment = ["right","top"]; 
	var staticFTPHote = group8.add("statictext", undefined, undefined, {name: "staticFTPHote"}); 
		staticFTPHote.text = "Hôte"; 
	var editFTPHote = group8.add('edittext {properties: {name: "editFTPHote"}}'); 
		editFTPHote.text = "crepes.o2ftp.net"; 
		editFTPHote.preferredSize.width = 400; 
		editFTPHote.helpTip = "Saisir le nom du serveur FTP d'échange avec l'imprimeur (optionnel)"; 
	// GROUP9
	// ======
	var group9 = panel3.add("group", undefined, {name: "group9"}); 
		group9.orientation = "row"; 
		group9.alignChildren = ["left","center"]; 
		group9.spacing = 10; 
		group9.margins = 0; 
		group9.alignment = ["right","top"]; 
	var staticFTPId = group9.add("statictext", undefined, undefined, {name: "staticFTPId"}); 
		staticFTPId.text = "Identifiant"; 
	var editFTPId = group9.add('edittext {properties: {name: "editFTPId"}}'); 
		editFTPId.text = "bibi"; 
		editFTPId.preferredSize.width = 400; 
		editFTPId.helpTip = "Login FTP (optionnel)"; 
	// GROUP10
	// =======
	var group10 = panel3.add("group", undefined, {name: "group10"}); 
		group10.orientation = "row"; 
		group10.alignChildren = ["left","center"]; 
		group10.spacing = 10; 
		group10.margins = 0; 
		group10.alignment = ["right","top"]; 
	var staticFTPMp = group10.add("statictext", undefined, undefined, {name: "staticFTPMp"}); 
		staticFTPMp.text = "Mot de passe"; 
	var editFTPMp = group10.add('edittext {properties: {name: "editFTPMp"}}'); 
		editFTPMp.text = "*****"; 
		editFTPMp.preferredSize.width = 400; 
		editFTPMp.helpTip = "Mot de passe FTP (optionnel)"; 
	// GROUP11
	// =======
	var group11 = panel3.add("group", undefined, {name: "group11"}); 
		group11.orientation = "row"; 
		group11.alignChildren = ["left","center"]; 
		group11.spacing = 10; 
		group11.margins = 0; 
		group11.alignment = ["right","top"]; 
	var staticFTPDossier = group11.add("statictext", undefined, undefined, {name: "staticFTPDossier"}); 
		staticFTPDossier.text = "Dossier distant ou déposer les tirages"; 
	var editFTPDossier = group11.add('edittext {properties: {name: "editFTPDossier"}}'); 
		editFTPDossier.text = "/LABO-TIRAGES"; 
		editFTPDossier.preferredSize.width = 400; 
		editFTPDossier.helpTip = "Login FTP (optionnel)"; 
	// GROUP12
	// =======
	var group12 = dialog.add("group", undefined, {name: "group12"}); 
		group12.orientation = "row"; 
		group12.alignChildren = ["left","center"]; 
		group12.spacing = 10; 
		group12.margins = 0; 

	var buttonAnnuler = group12.add("button", undefined, undefined, {name: "button1"}); 
		buttonAnnuler.text = "Annuler"; 

	var buttonValider = group12.add("button", undefined, undefined, {name: "button2"}); 
		buttonValider.text = "Valider"; 
		buttonValider.helpTip = "Ensuite il faudra fermer le plug-in PhotoLab et redémarrez le pour prendre en compte vos modifications."; 

	var fileName = g_Rep_PHOTOLAB + 'Code/PhotoLab-config.ini';	
	var file = new File(fileName);
	if ( file.open("r")){
		file.readln();	
		editCodeClient.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editURLLocal.text = decodeURIComponent(file.readln());		
		
		file.readln();		
		editURLOnLigne.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editDossierScript.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editDossierBASESource.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editProfondeurSOURCE.text = decodeURIComponent(file.readln());
		
		file.readln();	
		editURLSousDossierSOURCE.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editFTPHote.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editFTPId.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editFTPMp.text = decodeURIComponent(file.readln());
		
		file.readln();		
		editFTPDossier.text = decodeURIComponent(file.readln());		

		file.close();
	}

	buttonAnnuler.onClick = function () {		
		dialog.close();
	}

	buttonValider.onClick = function () {		
		var fileName = g_Rep_PHOTOLAB + 'Code/PhotoLab-config.ini';	
		var file = new File(fileName);
		file.encoding='UTF-8';
		file.open("w"); // open file with write access
			file.writeln("// editCodeClient");		
			file.writeln(encodeURIComponent(editCodeClient.text));
			
			file.writeln("// editURLLocal");		
			file.writeln(encodeURIComponent(editURLLocal.text));		
			
			file.writeln("// editURLOnLigne");		
			file.writeln(encodeURIComponent(editURLOnLigne.text));
			
			file.writeln("// editDossierScript");		
			file.writeln(encodeURIComponent(editDossierScript.text));
			
			file.writeln("// editDossierBASESource");		
			file.writeln(encodeURIComponent(editDossierBASESource.text));
			
			file.writeln("// editProfondeurSOURCE");		
			file.writeln(encodeURIComponent(editProfondeurSOURCE.text));
			
			file.writeln("// editURLSousDossierSOURCE");		
			file.writeln(encodeURIComponent(editURLSousDossierSOURCE.text));
			
			file.writeln("// editFTPHote");		
			file.writeln(encodeURIComponent(editFTPHote.text));
			
			file.writeln("// editFTPId");		
			file.writeln(encodeURIComponent(editFTPId.text));
			
			file.writeln("// editFTPMp");		
			file.writeln(encodeURIComponent(editFTPMp.text));
			
			file.writeln("// editFTPDossier");		
			file.writeln(encodeURIComponent(editFTPDossier.text));
		file.close();
		alert("Fermer le plug-in PhotoLab et redémarrez le pour prendre en compte vos modifications.");
				
		dialog.close();
	}	

dialog.show();
}


function InitConfig() {
	var fileName = g_Rep_PHOTOLAB + 'Code/PhotoLab-config.ini';	
	var file = new File(fileName);
	if ( file.open("r")){
		file.readln();	
		g_CodeClient = file.readln();
		
		file.readln();		//editURLLocal.text = 
		file.readln();		
		
		file.readln();		//editURLOnLigne.text = 
		file.readln();
		
		file.readln();		// editDossierScript"		
		g_RepSCRIPTSPhotoshop = decodeURIComponent(file.readln());
		
		file.readln();		//editDossierBASESource
		g_RepBASESOURCE = decodeURIComponent(file.readln());		
		//alert(g_RepBASESOURCE);
		
		file.readln();		//editProfondeurSOURCE.text = 
		file.readln();
		
		file.readln();		//editURLSousDossierSOURCE.text = 
		g_SousRepSOURCE = decodeURIComponent(file.readln());
		
		file.readln();		//editFTPHote.text = 
		file.readln();
		
		file.readln();		//editFTPId.text = 
		file.readln();
		
		file.readln();		//editFTPMp.text = 
		file.readln();
		
		file.readln();		//editFTPDossier.text = 
		file.readln();		
	/**/
		file.close();
	}
}