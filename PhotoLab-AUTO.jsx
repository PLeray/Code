// Pierre S Mac Leray Jr
#target photoshop-60.064
#include SourceJSX/PSDFonctionsInterface.js
#include SourceJSX/PSDBibliotheque.js

var g_NumVersion = 2.9;

var g_NomVersion = 'PhotoLab PLUGIN v3.3';
var g_Rep_PHOTOLAB = Folder($.fileName).parent.parent + "/";
var g_FichierSource = g_Rep_PHOTOLAB + 'Code/Sources.csv';
var isDebug = ($.fileName.substr(-4) == '.jsx'); //et non .jsxbin !

var g_NomVersion = 'PhotoLab PLUGIN v' + g_NumVersion + (isDebug?' !!! BETA !!!':'');
//alert('$.fileName.substr(-4)  : ' + $.fileName.substr(-4) + ' is debug : ' + isDebug);

var g_CeCalculateur = '';
var g_CodeClient = '';



var g_LargeurUI = 650;
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

LoadConfig();

RecupNomOrdi();

//RECHERCHE REPERTOIRE SOURCE
/*
var g_UIWINRechercheSource = new Window ('palette');

g_UIWINRechercheSource.frameLocation = [ -4,g_HauteurRechercheUI + 30 ];
g_UIWINRechercheSource.graphics.backgroundColor = g_UIWINRechercheSource.graphics.newBrush (g_UIWINRechercheSource.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
//g_UIWINRechercheSource.add ("statictext", [0,0,800,20], "SCAN POUR TROUVER LES SOURCES DES IMAGES");
g_UIWINRechercheSource.add ("statictext", [0,0,g_LargeurUI,20], "SCAN POUR TROUVER LES SOURCES DES IMAGES");
//g_UIWINRechercheSource.graphics.font = "Arial-Bold:18";

var UIRepertoireSource = g_UIWINRechercheSource.add ("statictext", [0,0,g_LargeurUI,50], "Recherche de Sources", {multiline: true});
UIRepertoireSource.graphics.foregroundColor = UIRepertoireSource.graphics.newPen (UIRepertoireSource.graphics.PenType.SOLID_COLOR, [0.9, 0.9, 0.9], 1);
*/
//RECHERCHE REPERTOIRE SOURCE

//PHOTOLAB
var PHOTOLAB = new Window ('palette', g_NomVersion + '     [' + g_CeCalculateur + ']', undefined); 
//var PHOTOLAB = new Window ('dialog', 'PHOTOLAB PLUGIN '); 
//PHOTOLAB.size.height = 150;
PHOTOLAB.frameLocation = [ -4, -4 ];
PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
PHOTOLAB.graphics.foregroundColor = PHOTOLAB.graphics.newPen(PHOTOLAB.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);



//var couleurVert = PHOTOLAB.graphics.newBrush(PHOTOLAB.graphics.BrushType.SOLID_COLOR,[0,0,0.8], 1);

// Zone1Entete 
var Zone1Entete = PHOTOLAB.add ("group");


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
			ArborescenceWEB();
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
	
	

	// Zone12Logo 
	var Zone12Logo = Zone1Entete.add ("image", undefined, File(g_RepIMG+'ImgPhotoLabPS.png'));
	
	// Zone13Option 
	var Zone13Option= Zone1Entete.add ("group");
	Zone13Option.alignment = "right";
	Zone13Option.orientation = "column"; 

		// Zone131Action 
		var Zone131Action= Zone13Option.add ("group");
		Zone131Action.orientation = "row";

		var buttonScanCMD = Zone131Action.add ("button", [0,0,g_LargeurUI/4,25], "Scanner rep Commandes");
		buttonScanCMD.alignment = "left";
		buttonScanCMD.helpTip = "Scanner et traiter le repertoire des commandes ..."; 
		buttonScanCMD.onClick = function () {	
			Auto(); 
		}
		/*  §§§§§§§§§§§§§§§§§§§§ SUPRIMER SOURCE DERRIERE
		var buttonSourceWeb = Zone131Action.add ("button", [0,0,g_LargeurUI/4,25], "Créer arborecence web");
		buttonSourceWeb.alignment = "left";
		buttonSourceWeb.helpTip = "Générer une arborescence structurée par classes des fichiers photos à uploader sur le site web Lumys"; 
		buttonSourceWeb.onClick = function () {	
			if (AfficheValidationNomClasse() == true){
				ArborescenceWEB('I.WEB-QUATTRO');
			} 
			
		}
		/*
	/*
	var checkOrdre = Zone13Option.add ("checkbox", [0,0,g_LargeurUI/3,20], "Ordre des planches inversé"); 
	checkOrdre.value = g_OrdreInversePlanche;
	*/
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

	Select_Generer.enabled = false;
	Select_Generer.onClick = function () {
		//GenererFichiersLabo();
		 GestionBoutonGenerer();
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
