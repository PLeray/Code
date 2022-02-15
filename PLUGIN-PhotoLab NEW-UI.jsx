// Pierre S Mac Leray Jr
//#target photoshop-60.064 // Pour cs6 direct
#target photoshop

#include SourceJSX/PSDUI.js




#include SourceJSX/PSDFonctionsInterface.js
#include SourceJSX/PSDBibliotheque.js

var g_NumVersion = 0.839;

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

var g_RepSCRIPTSPhotoshop = 'PHOTOLAB-STUDIO2';

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
/**/
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
var g_TypeUI =  'dialog'  ; // '' 'palette'  'window'    (MAC
//alert('is_PC : ' + is_PC);


PHOTOLAB.text = g_NomVersion + '     [' + g_CeCalculateur + ']';
PHOTOLAB.margins = 0;

//PHOTOLAB.frameLocation = [ -4, -4 ];
PHOTOLAB.graphics.backgroundColor = PHOTOLAB.graphics.newBrush (PHOTOLAB.graphics.BrushType.SOLID_COLOR, [0.3, 0.3, 0.3]);
PHOTOLAB.graphics.foregroundColor = PHOTOLAB.graphics.newPen(PHOTOLAB.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);




	
	g_ToutFichier=false;


    ChercherFichierLab();


    //alert('g_TabListeCompilationFichier[] ' + TableauTOStr(g_TabListeCompilationFichier) );

        //var strTableau = 'Le TableauTOStr : ';
  /* 
          for (var n = 0; n < listboxCommandes.length; n++) {
            listboxCommandes.remove (listboxCommandes.selection[n]);
        }	

             */
       listboxCommandes.removeAll();  


        for (var n = 0; n < g_TabListeCompilationFichier.length; n++) {
            //strTableau = strTableau + "\n" + unTableau[n] ;
            listboxCommandes.add ("item", g_TabListeCompilationFichier[n]);
        }	
        //alert('unTableau[5] ' + unTableau[5] );

 /* 

 
    if (is_PC) {
        var listboxCommandes = Zone2Progression.add ("listbox", undefined, g_TabListeCompilationFichier);
    } else {
        var listboxCommandes = Zone2Progression.add ("listbox", undefined, g_TabListeCompilationFichier);
    }

    listboxCommandes.selection = 0;
    g_NomFichierEnCours = g_TabListeCompilationFichier[0]; 
    //e.text = g_NomFichierEnCours;
    //e.active = true;
    listboxCommandes.preferredSize.width = 340;
    //e.preferredSize.width = 220; e.preferredSize.height = 20;
    
    
    listboxCommandes.onChange = function () {
    g_NomFichierEnCours = listboxCommandes.selection.text;
    
    }

	*/





// PROGRESS BAR


//listboxCommandes.size.height = 100;

	//listboxCommandes.graphics.foregroundColor = listboxCommandes.graphics.newPen(listboxCommandes.graphics.PenType.SOLID_COLOR, [1, 1, 1], 1);





/**/
btnQuitter.onClick = function () {	
    //alert('PHOTOLAB.close()');
    PHOTOLAB.close();
};


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
    //if(!isDebug){photoshop.quit();}
};
// keep palette opened until user click button or close window
var FermerPhotoLab = false;
PHOTOLAB.show();	

while(FermerPhotoLab == false){
   //app.refresh();
   Raffraichir();
   
};

////////////////////////////// LES FONCTIONS //////////////////////////////////////////////
function Raffraichir() { 
    app.refresh(); // or, alternatively, 
    WaitForRedraw();

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

