// Pierre S Mac Leray Jr
//#target photoshop-60.064 // Pour cs6 direct
//#target photoshop-130.064 // Pour CC2019 direct
#target photoshop

#include SourceJSX/PSDFonctionsInterface.js
#include SourceJSX/PSDBibliotheque.js

app.bringToFront();

var g_NumVersion = 0.865;

var is_PC = (File.fs == "Windows") ? true : false ; 

var g_Rep_PHOTOLAB = Folder($.fileName).parent.parent + "/";
var g_FichierSource = g_Rep_PHOTOLAB + 'SOURCES/Sources.csv';

var g_Rep_GABARITS = g_Rep_PHOTOLAB + 'GABARITS/';

var isDebug = ($.fileName.substr(-4) == '.jsx'); //et non .jsxbin !


var g_NomVersion = 'PhotoLab PLUGIN BETA v' + g_NumVersion + (isDebug?' !!! DEV-BETA !!!':'');
//alert('$.fileName.substr(-4)  : ' + $.fileName.substr(-4) + ' is debug : ' + isDebug);

var g_CeCalculateur = '';
var g_CodeClient = '';



var g_LargeurUI = 650;
//var g_HauteurDetailsUI = 1080;
//var g_HauteurRechercheUI = 240;

var g_HauteurTabUI = 732;  //100; te
var g_OriginalRulerUnits = app.preferences.rulerUnits;  
var g_OriginalTypeUnits = app.preferences.typeUnits;  
var g_OriginalDisplayDialogs = app.displayDialogs;  
app.preferences.rulerUnits = Units.PIXELS; // Set the ruler units to PIXELS  
app.preferences.typeUnits = TypeUnits.POINTS;   // Set Type units to POINTS
app.displayDialogs = DialogModes.NO; // Set Dialogs off 

var g_BilanGeneration = [];

var g_Erreur = '';

var g_IsEcoleWEB = true;

var g_SelectFichierLab;
var g_NomFichierEnCours = '';

//var g_VersionLab;

var g_TabFichierAvecErreur = [];
var g_TabListeCompilationFichier = [];

var g_TabLigneOriginale = [];
var g_CommandeLabo; 
var g_GroupeIndiv = {};

var g_TabListeNomsClasses = {};

var g_ToutFichier = false;

var g_RepSCRIPTSPhotoshop = 'PHOTOLAB';

var g_RepSOURCE;


var g_ProfondeurMAX = 1;

var g_OrdreInversePlanche = true;
var g_CONFIGtypeConfigWeb = 'WEB-QUATTRO';
var g_CONFIGisPhotosGroupes = true;
var g_CONFIGisPhotosIndiv = true;	
var g_CONFIGisPhotosFratrie = true;


var g_RepTIRAGES_DateEcole;
var g_RepMINIATURES_DateEcole;

var g_RepIMG = g_Rep_PHOTOLAB + 'Code/res/img/';

var g_IsPhotoLabON = false;
var g_IsTravail = false;

var g_IsPlancheSiteWEB = false;

var g_IsGenerationEnCours = false;
var g_IsGenerationEnPause = true;
UIindex = 4; //Pour un, deux, trois, GO!

app.preferences.height = 100;

LoadConfig();

RecupNomOrdi();

var g_TypeUI =  'dialog'  ; // '' 'palette'  'window'    (MAC


//////////TEST ////////////////////////////
/*var g_REN = g_Rep_PHOTOLAB + 'Code/TEST';
var doc  =  new Folder(g_REN);
doc.rename('TTTTES4');
*/
//ImporterSource();

///////////////////////////

/*if ( g_Rep_PHOTOLAB.indexOf('xamppfiles') > -1){
	g_TypeUI =  'dialog' // MAC
}else{
	g_TypeUI =  'palette' // PC
}*/

if (is_PC){	g_TypeUI =  'palette'} // PC	
else{ g_TypeUI =  'dialog'} // MAC

//TEST pour probleme de quitter process pendans compilation INTERRUPTION  COMMENT ?
//g_TypeUI =  'dialog'  ;

//alert('is_PC : ' + is_PC);
//PHOTOLAB
var PHOTOLAB = new Window (g_TypeUI, g_NomVersion + '     [' + g_CeCalculateur + ']', undefined); 
PHOTOLAB.margins = 0;

PHOTOLAB.frameLocation = [ -4, -4 ];
PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
PHOTOLAB.graphics.foregroundColor = PHOTOLAB.graphics.newPen(PHOTOLAB.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);


//var couleurVert = PHOTOLAB.graphics.newBrush(PHOTOLAB.graphics.BrushType.SOLID_COLOR,[0,0,0.8], 1);

// Zone1Entete 
var Zone1Entete = PHOTOLAB.add ("group");


	// Zone11Config 
	var Zone11Config = Zone1Entete.add ("group");
	Zone11Config.alignment = ['left', 'top'];
	Zone11Config.orientation = "row";

	var statictextBB = Zone11Config.add("statictext", undefined, undefined, {name: "statictextBB"}); 
    statictextBB.text = "Bibliothèque de photos : "; 

	
	/*
	var group = Zone1111Action.add ("group {alignChildren: 'left', orientation: ’stack'}");	
	var statictextBB = panel1.add("statictext", undefined, undefined, {name: "statictextBB"}); 
    statictextBB.text = "Bibliothèque de photos : "; 	
	*/
	
	var imgBibliotheque = {a: File(g_RepIMG+"Bibliotheque.png"), b: File(g_RepIMG+"Bibliotheque-Disable.png"), c: File(g_RepIMG+"Bibliotheque-Click.png"), d: File(g_RepIMG+"Bibliotheque-Over.png")};
	var buttonBibliotheque = Zone11Config.add ("iconbutton", undefined, ScriptUI.newImage (imgBibliotheque.a, imgBibliotheque.b, imgBibliotheque.c, imgBibliotheque.d), {style: "toolbutton"});  // Ne fonctionne pas
	//buttonConfig.alignment = "right";
	buttonBibliotheque.enabled = true;
	buttonBibliotheque.helpTip = "Bibliothèque des sources photo pour PhotoLab"; 
	buttonBibliotheque.onClick = function () {
		if (AfficheListeSOURCE() == 3){
			//ArborescenceWEB();
		} 		 
	}	
	
	// Zone12Logo 
	var Zone12Logo = Zone1Entete.add ("image", undefined, File(g_RepIMG+'ImgPhotoLabPS.png'));	
	
	/*
	var imgConfig = {a: File(g_RepIMG+"Config.png"), b: File(g_RepIMG+"Config-Disable.png"), c: File(g_RepIMG+"Config-Click.png"), d: File(g_RepIMG+"Config-Over.png")};
	var buttonConfig = Zone11Config.add ("iconbutton", undefined, ScriptUI.newImage (imgConfig.a, imgConfig.b, imgConfig.c, imgConfig.d), {style: "toolbutton"});  // Ne fonctionne pas
	//buttonConfig.alignment = "right";
	buttonConfig.enabled = true;
	buttonConfig.helpTip = "Configuration des options de PhotoLab"; 
	buttonConfig.onClick = function () {
		 DLGConfiguration();
	}
	*/
	
	


	

	/*
	///////// MAC PC ICI TEST ??????? VERIF A FAIRE	
	if (File.fs !== "Windows") {
		var dpDown = group.add ("dropdownlist", undefined, g_TabListeCompilationFichier);
		//var e = group.add ("edittext");
	} else {
		//var e = group.add ("edittext");
		var dpDown = group.add ("dropdownlist", undefined, g_TabListeCompilationFichier);
	}
		dpDown.graphics.foregroundColor = dpDown.graphics.newPen(dpDown.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);





	dpDown.selection = 0;
	g_NomFichierEnCours = g_TabListeCompilationFichier[0]; 
	//e.text = g_NomFichierEnCours;
	//e.active = true;
	dpDown.preferredSize.width = 340;
	//e.preferredSize.width = 220; e.preferredSize.height = 20;


	dpDown.onChange = function () {
	g_NomFichierEnCours = dpDown.selection.text;

	}	
*/

	
	


	
	g_ToutFichier=false;
	
	// Zone14Boutton 
	var Zone14Boutton= Zone1Entete.add ("group");

	Zone14Boutton.alignment = "right";
	Zone14Boutton.orientation = "row";

	var imgGenerer = {a: File(g_RepIMG+"Play.png"), b: File(g_RepIMG+"Play-Disable.png"), c: File(g_RepIMG+"Play-Click.png"), d: File(g_RepIMG+"Play-Over.png")};
	var imgPause = {a: File(g_RepIMG+"Pause.png"), b: File(g_RepIMG+"Pause-Disable.png"), c: File(g_RepIMG+"Pause-Click.png"), d: File(g_RepIMG+"Pause-Over.png")};
	
	
	var statictextLance = Zone14Boutton.add("statictext", undefined, undefined, {name: "statictextBB"}); 
    statictextLance.text = "Lance : "; 	

	var Select_Generer = Zone14Boutton.add ("iconbutton", undefined, ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d), {style: "toolbutton"});  // Ne fonctionne pas
	
	Select_Generer.helpTip = "Lancer ou arrêter la creation de fichiers ..."; 

	Select_Generer.enabled = true;
	Select_Generer.onClick = function () {
		
		g_IsPhotoLabON = true;
		 GestionBoutonGenerer();

		 //GenererLeFichierNOM(); //g_NomFichierEnCours
		Auto();
	}
	// Zone13Option 
	var Zone13Option= Zone1Entete.add ("group");
	Zone13Option.alignment = "right";
	Zone13Option.orientation = "column"; 

		// Zone131Action 

		var Zone131Action= Zone13Option.add ("group");
		Zone131Action.orientation = "row";
		
//var names = ["Annabel", "Bertie", "Caroline", "Debbie", "Erica"];

	ChercherFichierLab();

	var group = Zone131Action.add ("group {alignChildren: 'left', orientation: ’stack'}");	
	
	var txtTraitement = Zone13Option.add ('statictext', [0,0,g_LargeurUI/2,55], '0/0', {multiline: true});
	txtTraitement.graphics.font = ScriptUI.newFont ("Arial", 'BOLD', 14);	
	

// PROGRESS BAR
var Zone2Progression = PHOTOLAB.add ("group");
Zone2Progression.alignChildren = ["fill", "fill"]; 
Zone2Progression.orientation = "column";



if (is_PC) {
	var listboxCommandes = Zone2Progression.add ("listbox", undefined, g_TabListeCompilationFichier);
} else {
	var listboxCommandes = Zone2Progression.add ("listbox", undefined, g_TabListeCompilationFichier);
}
//listboxCommandes.size.height = 100;
listboxCommandes.preferredSize.height = 100;
	//listboxCommandes.graphics.foregroundColor = listboxCommandes.graphics.newPen(listboxCommandes.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);





listboxCommandes.selection = 0;
g_NomFichierEnCours = g_TabListeCompilationFichier[0]; 
//e.text = g_NomFichierEnCours;
//e.active = true;
listboxCommandes.preferredSize.width = 340;
//e.preferredSize.width = 220; e.preferredSize.height = 20;


listboxCommandes.onChange = function () {
g_NomFichierEnCours = listboxCommandes.selection.text;

}	

var fichierEnCours = Zone2Progression.add ('statictext {justify: "center"}'); //,  [0,0,g_LargeurUI,10]);

var progressBar = Zone2Progression.add ('progressbar', [0,0,g_LargeurUI,10], 0, 15);

var Groupefermer = PHOTOLAB.add("group", undefined, {name: "Groupefermer"}); 
Groupefermer.alignment = ["center","top"]; 
var btnQuitter = Groupefermer.add("button", undefined, 'Quitter', {name: "btnQuitter"}); 	
btnQuitter.onClick = function () {	
    //alert('PHOTOLAB.close()');
    PHOTOLAB.close();
}	


PHOTOLAB.onDeactivate = function(){
    // just to prevent window from fading out
    PHOTOLAB.update();
    Raffraichir();
};

PHOTOLAB.onClose = function(){
	g_IsPhotoLabON = false;
    FermerPhotoLab = true;
    app.displayDialogs = g_OriginalDisplayDialogs; // Reset display dialogs   
    app.preferences.typeUnits  = g_OriginalTypeUnits; // Reset ruler units to original settings   
    app.preferences.rulerUnits = g_OriginalRulerUnits; // Reset units to original settings     
    if(!isDebug){photoshop.quit();}
};

//Mettre à jour fichier des script de Photoshop Pour PhotoLab mot clé : 'photolab'
MAJFichierScriptPSP();

// keep palette opened until user click button or close window
var FermerPhotoLab = false;
PHOTOLAB.show();	

while(FermerPhotoLab == false){
   //app.refresh();
   Raffraichir();


};

////////////////////////////// LES FONCTIONS //////////////////////////////////////////////
function Raffraichir() { 
	//if(ScriptUI.environment.keyboardState.shiftKey == true){ 
	if((ScriptUI.environment.keyboardState.shiftKey == true) && (ScriptUI.environment.keyboardState.keyName == 'X')){ 
		//alert('g_IsPhotoLabON ' + g_IsPhotoLabON);
		
		//alert('Touche : ' + ScriptUI.environment.keyboardState.keyName + 'g_IsPhotoLabON ' + g_IsPhotoLabON); // "A"

		//g_IsPhotoLabON = !QuestionInterruptionTraitement();
		//alert('g_IsPhotoLabON ' + g_IsPhotoLabON);
		GestionBoutonGenerer()
	} 


    //NEW 02-01-2022 
	RaffraichirOLD();
	//PHOTOLAB.show();	
	//PHOTOLAB.hide();





	
	
} 

function WaitForRedraw(){
    var eventWait = charIDToTypeID("Wait")
    var enumRedrawComplete = charIDToTypeID("RdCm")
    var typeState = charIDToTypeID("Stte")
    var keyState = charIDToTypeID("Stte")
    var desc = new ActionDescriptor()
    desc.putEnumerated(keyState, typeState, enumRedrawComplete)
    executeAction(eventWait, desc, DialogModes.NO)
}  

function RaffraichirOLD() { 
    // NEW 08-12-2021  
	WaitForRedraw();
	
	app.refresh(); // or, alternatively, 
	PHOTOLAB.update(); // NEW 08-12-2021

} 
