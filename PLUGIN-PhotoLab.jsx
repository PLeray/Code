// Pierre S Mac Leray Jr
//#target photoshop-60.064 // Pour cs6 direct
#target photoshop
#include SourceJSX/PSDFonctionsInterface.js
#include SourceJSX/PSDBibliotheque.js

var g_NumVersion = 3.1;

var g_Rep_PHOTOLAB = Folder($.fileName).parent.parent + "/";
var g_FichierSource = g_Rep_PHOTOLAB + 'Code/Sources.csv';
var isDebug = ($.fileName.substr(-4) == '.jsx'); //et non .jsxbin !

var g_NomVersion = 'PLUGIN-PhotoLab v' + g_NumVersion + (isDebug?' !!! BETA !!!':'');
//alert('$.fileName.substr(-4)  : ' + $.fileName.substr(-4) + ' is debug : ' + isDebug);

var g_CeCalculateur = '';
var g_CodeClient = '';



var g_LargeurUI = 550;
var g_HauteurDetailsUI = 1080;
var g_HauteurRechercheUI = 240;

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

var g_RepSCRIPTSPhotoshop = 'PHOTOLAB-STUDIO2';

var g_RepSOURCE;
//var g_RepBASESOURCE;
//var g_SousRepSOURCE = '\PHOTOS\SOURCE';

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


//PHOTOLAB
var PHOTOLAB = new Window ('palette', g_NomVersion + '     [' + g_CeCalculateur + ']', undefined); 

//var PHOTOLAB = new Window ('dialog', 'PHOTOLAB PLUGIN '); 
//PHOTOLAB.size.height = 150;
PHOTOLAB.frameLocation = [ -4, -4 ];
PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
PHOTOLAB.graphics.foregroundColor = PHOTOLAB.graphics.newPen(PHOTOLAB.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);


	var Groupefermer = PHOTOLAB.add("group", undefined, {name: "Groupefermer"}); 
	Groupefermer.alignment = ["right","top"]; 
	var btnFermer = Groupefermer.add("button", undefined, 'Fermer', {name: "btnFermer"}); 	
	btnFermer.onClick = function () {	
		PHOTOLAB.close();
	}	



//var couleurVert = PHOTOLAB.graphics.newBrush(PHOTOLAB.graphics.BrushType.SOLID_COLOR,[0,0,0.8], 1);

// Zone1Entete 
var Zone1Entete = PHOTOLAB.add ("group");
	// Zone12Logo 
	var Zone12Logo = Zone1Entete.add ("image", undefined, File(g_RepIMG+'ImgPhotoLabPS.png'));

	// Zone11Config 
	var Zone11Config = Zone1Entete.add ("group");
	Zone11Config.alignment = ['left', 'top'];
	Zone11Config.orientation = "column";
	
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


	var txtTraitement = Zone13Option.add ('statictext', [0,0,g_LargeurUI/2,25], '0/0', {multiline: true});
	txtTraitement.graphics.font = ScriptUI.newFont ("Arial", 'BOLD', 14);
	
	g_ToutFichier=false;
	
	// Zone14Boutton 
	var Zone14Boutton= Zone1Entete.add ("group");

	Zone14Boutton.alignment = "right";
	Zone14Boutton.orientation = "column";

	var imgGenerer = {a: File(g_RepIMG+"Play.png"), b: File(g_RepIMG+"Play-Disable.png"), c: File(g_RepIMG+"Play-Click.png"), d: File(g_RepIMG+"Play-Over.png")};
	var imgPause = {a: File(g_RepIMG+"Pause.png"), b: File(g_RepIMG+"Pause-Disable.png"), c: File(g_RepIMG+"Pause-Click.png"), d: File(g_RepIMG+"Pause-Over.png")};

	var Select_Generer = Zone14Boutton.add ("iconbutton", undefined, ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d), {style: "toolbutton"});  // Ne fonctionne pas

	Select_Generer.enabled = true;
	Select_Generer.onClick = function () {
		g_IsPhotoLabON = true;
		 GestionBoutonGenerer();
		 //GenererLeFichierNOM(); //g_NomFichierEnCours
		Auto();
	}

// PROGRESS BAR
var Zone2Progression = PHOTOLAB.add ("group");
Zone2Progression.alignChildren = ["fill", "fill"]; 
Zone2Progression.orientation = "column";
var fichierEnCours = Zone2Progression.add ('statictext {justify: "center"}'); //,  [0,0,g_LargeurUI,10]);


var progressBar = Zone2Progression.add ('progressbar', [0,0,g_LargeurUI,10], 0, 15);


PHOTOLAB.onDeactivate = function(){
    // just to prevent window from fading out
    PHOTOLAB.update();
};

// keep palette opened until user click button or close window
var FermerPhotoLab = false;

PHOTOLAB.onClose = function(){
	g_IsPhotoLabON = false;
    FermerPhotoLab = true;
    app.displayDialogs = g_OriginalDisplayDialogs; // Reset display dialogs   
    app.preferences.typeUnits  = g_OriginalTypeUnits; // Reset ruler units to original settings   
    app.preferences.rulerUnits = g_OriginalRulerUnits; // Reset units to original settings     
    if(!isDebug){photoshop.quit();}
};

PHOTOLAB.show();	

while(FermerPhotoLab == false){
   app.refresh();
};

////////////////////////////// LES FONCTIONS //////////////////////////////////////////////
function Raffraichir() { 
    app.refresh(); // or, alternatively, waitForRedraw(); 
    PHOTOLAB.update(); // A voir sur MAC?
}
