//var PHOTOLAB = new Window("palette", "palette PHOTOLAB");
//var PHOTOLAB = new Window("window", "window PHOTOLAB");
// Pierre S Mac Leray Jr
#target photoshop-60.064
#include SourceJSX/PSDFonctionsInterface.js
var g_CeCalculateur = '';
var g_CodeClient = '';


var g_Version = 3.0;
var g_LargeurUI = 655;
var g_HauteurDetailsUI = 1080;
var g_HauteurUI = 240;

var g_HauteurTabUI = 732;  //100; te
var g_OriginalRulerUnits = app.preferences.rulerUnits;  
var g_OriginalTypeUnits = app.preferences.typeUnits;  
var g_OriginalDisplayDialogs = app.displayDialogs;  
app.preferences.rulerUnits = Units.PIXELS; // Set the ruler units to PIXELS  
app.preferences.typeUnits = TypeUnits.POINTS;   // Set Type units to POINTS
app.displayDialogs = DialogModes.NO; // Set Dialogs off 

var g_BilanGeneration = [];

var g_Erreur = '';

var g_SelectFichierLab;
var g_VersionLab;

var g_TabFichierAvecErreur = [];
var g_TabListeCompilationFichier = [];

var g_TabLigneOriginale = [];
var g_CommandeLabo; 
var g_GroupeIndiv = {};

var g_TabListeNomsClasses = {};

var g_ToutFichier = false;

var g_RepSCRIPTSPhotoshop = 'PHOTOLAB-STUDIO2';

var g_RepSOURCE;
var g_RepBASESOURCE;
var g_SousRepSOURCE = '\PHOTOS\SOURCE';


var g_RepTIRAGES_DateEcole;
var g_RepMINIATURES_DateEcole;

var g_Rep_PHOTOLAB = Folder($.fileName).parent.parent + "/";

//alert("g_Rep_PHOTOLAB : " + g_Rep_PHOTOLAB);

//var dir = g_Rep_PHOTOLAB + 'Code/res/img/';
var g_RepIMG = g_Rep_PHOTOLAB + 'Code/res/img/';

var g_IsPhotoLabON = false;
var g_IsTravail = false;

var g_IsPlancheSiteWEB = false;

var g_IsGenerationEnCours = false;
var g_IsGenerationEnPause = true;
UIindex = 4; //Pour un, deux, trois, GO!

InitConfig();

//RECHERCHE REPERTOIRE SOURCE
var g_UIWINRechercheSource = new Window ('palette');
g_UIWINRechercheSource.frameLocation = [ -4,g_HauteurUI + 30 ];
g_UIWINRechercheSource.graphics.backgroundColor = g_UIWINRechercheSource.graphics.newBrush (g_UIWINRechercheSource.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
//g_UIWINRechercheSource.add ("statictext", [0,0,800,20], "SCAN POUR TROUVER LES SOURCES DES IMAGES");
g_UIWINRechercheSource.add ("statictext", [0,0,g_LargeurUI,20], "SCAN POUR TROUVER LES SOURCES DES IMAGES");
//g_UIWINRechercheSource.graphics.font = "Arial-Bold:18";

//var UIRepertoireSource = g_UIWINRechercheSource.add ("statictext", [0,0,800,50], "Recherche de Sources", {multiline: true});
var UIRepertoireSource = g_UIWINRechercheSource.add ("statictext", [0,0,g_LargeurUI,50], "Recherche de Sources", {multiline: true});
UIRepertoireSource.graphics.foregroundColor =UIRepertoireSource.graphics.newPen (UIRepertoireSource.graphics.PenType.SOLID_COLOR, [0.9, 0.9, 0.9], 1);

//RECHERCHE REPERTOIRE SOURCE

//PHOTOLAB
var PHOTOLAB = new Window ('palette', 'PHOTOLAB PLUGIN ' + g_Version, undefined, {resizeable: true}); 
//var PHOTOLAB = new Window ('dialog', 'PHOTOLAB PLUGIN '); 
PHOTOLAB.frameLocation = [ -4, -4 ];
PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);

PHOTOLAB.graphics.foregroundColor = UIRepertoireSource.graphics.newPen(UIRepertoireSource.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);

//var couleurVert = PHOTOLAB.graphics.newBrush(PHOTOLAB.graphics.BrushType.SOLID_COLOR,[0,0,0.8], 1);

var Entete = PHOTOLAB.add ("group");
//Entete.alignment = "left";
var imgEntete = Entete.add ("image", undefined, File(g_RepIMG+'ImgPhotoLabPS.png'));

var ZoneOption= Entete.add ("group");

ZoneOption.alignment = "right";
ZoneOption.orientation = "column"; 



var checkAuto = ZoneOption.add ("checkbox", [0,0,g_LargeurUI/3,20], "Mode Auto"); 
checkAuto.shortcutKey = "a";
//var checkFile =ZoneOption.add ("checkbox", [0,0,g_LargeurUI/3,20], "Tous les fichiers"); checkFile.shortcutKey = "c";

var checkOrdre = ZoneOption.add ("checkbox", [0,0,g_LargeurUI/3,20], "Ordre des planches inversé"); checkOrdre.shortcutKey = "c";
checkOrdre.value=true;

var txtTraitement = ZoneOption.add ('statictext', [0,0,g_LargeurUI/3,35], '0/0', {multiline: true});
txtTraitement.graphics.font = ScriptUI.newFont ("Arial", 'BOLD', 16);



var buttonSourceWeb = ZoneOption.add ("button", [0,0,g_LargeurUI/3,25], "Créer arborecence web");
buttonSourceWeb.alignment = "left";
buttonSourceWeb.helpTip = "Générer une arborescence structurée par classes des fichiers photos à uploader sur le site web Lumys"; 
buttonSourceWeb.onClick = function () {	
	DLGValidationNomClasse(); 
}

g_ToutFichier=false;
/*checkFile.value=false;
checkFile.onClick = function () { 
    g_ToutFichier = checkFile.value;
    Init();
}*/


var ZoneBoutton= Entete.add ("group");

ZoneBoutton.alignment = "right";
ZoneBoutton.orientation = "column";




var imgConfig = {a: File(g_RepIMG+"Config.png"), b: File(g_RepIMG+"Config-Disable.png"), c: File(g_RepIMG+"Config-Click.png"), d: File(g_RepIMG+"Config-Over.png")};

var buttonConfig = ZoneBoutton.add ("iconbutton", undefined, ScriptUI.newImage (imgConfig.a, imgConfig.b, imgConfig.c, imgConfig.d), {style: "toolbutton"});  // Ne fonctionne pas

buttonConfig.alignment = "right";

buttonConfig.enabled = true;
buttonConfig.helpTip = "Configuration des options de PhotoLab"; 
buttonConfig.onClick = function () {

     DLGConfiguration();
}


var imgGenerer = {a: File(g_RepIMG+"Play.png"), b: File(g_RepIMG+"Play-Disable.png"), c: File(g_RepIMG+"Play-Click.png"), d: File(g_RepIMG+"Play-Over.png")};
var imgPause = {a: File(g_RepIMG+"Pause.png"), b: File(g_RepIMG+"Pause-Disable.png"), c: File(g_RepIMG+"Pause-Click.png"), d: File(g_RepIMG+"Pause-Over.png")};

var Select_Generer = ZoneBoutton.add ("iconbutton", undefined, ScriptUI.newImage (imgGenerer.a, imgGenerer.b, imgGenerer.c, imgGenerer.d), {style: "toolbutton"});  // Ne fonctionne pas

Select_Generer.enabled = false;
Select_Generer.onClick = function () {
	//GenererFichiersLabo();
     GestionBoutonGenerer();
}


// ZONE TRAITEMENT
var ZoneTraitement = PHOTOLAB.add ("group");
ZoneTraitement.orientation = "row";

/*var ComboFichierLab = ZoneOption.add ("dropdownlist", undefined, []);
ComboFichierLab.preferredSize.width = 560;
ComboFichierLab.onChange = function () { 
    OuvrirSelectFichierLab0(ComboFichierLab.selection.text);
}

*/


var laTaille = false; 

/*
    var MiniTaille = ZoneTraitement.add ("button", [0,0,70,20], "Détails");
MiniTaille.onClick = function () {
	PHOTOLAB.size.height = (laTaille) ? g_HauteurDetailsUI : g_HauteurUI;

	laTaille = (laTaille) ? false : true ;
}

*/


var txtFichier = ZoneTraitement.add ('statictext',  [0,0,560,12], 'Initialisation liste de commande...', {multiline: true});
//txtFichier.graphics.font = ScriptUI.newFont ("Arial", 'BOLD', 12);

// PROGRESS BAR
var Progression = PHOTOLAB.add ("group");
Progression.alignChildren = ["fill", "fill"]; 
Progression.orientation = "column";

var progressBar = Progression.add ('progressbar', [0,0,g_LargeurUI,10], 0, 5);

//PHOTOLAB.add ('statictext', [0,0,g_LargeurUI,30], 'Aucun Dossier...', {multiline: true});


//var UIListeMessage = PHOTOLAB.add ("listbox", [0,0,g_LargeurUI,g_HauteurTabUI], ["Un","Deux","Trois","Go !"]); 
    //UIListeMessage.graphics.font = ScriptUI.newFont ("Courier New", 'BOLD', 9);
    //UIListeMessage.graphics.foregroundColor =UIRepertoireSource.graphics.newPen (UIRepertoireSource.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);

/*
var ComboFichierLab = PHOTOLAB.add ("dropdownlist", undefined, []);
ComboFichierLab.preferredSize.width = 560;
ComboFichierLab.onChange = function () { 
    OuvrirSelectFichierLab0(ComboFichierLab.selection.text);
}
*/
PHOTOLAB.onDeactivate = function(){
    // just to prevent window from fading out
    PHOTOLAB.update();
};

checkAuto.onClick = function () { 
	g_IsPhotoLabON = checkAuto.value;
    InitUI(checkAuto.value);
	Auto();
}

// keep palette opened until user click button or close window
var FermerPhotoLab = false;

PHOTOLAB.onClose = function(){
	g_IsPhotoLabON = false;
    FermerPhotoLab = true;
    app.displayDialogs = g_OriginalDisplayDialogs; // Reset display dialogs   
    app.preferences.typeUnits  = g_OriginalTypeUnits; // Reset ruler units to original settings   
    app.preferences.rulerUnits = g_OriginalRulerUnits; // Reset units to original settings     
    photoshop.quit()
};

PHOTOLAB.show();	

checkAuto.value=true; //False pour normal ! True auto
InitUI(checkAuto.value);

Auto();

while(FermerPhotoLab == false){
   app.refresh();
};


////////////////////////////// LES FONCTIONS //////////////////////////////////////////////
function Raffraichir() { 
    app.refresh(); // or, alternatively, waitForRedraw(); 
    PHOTOLAB.update(); // A voir sur MAC?
}
