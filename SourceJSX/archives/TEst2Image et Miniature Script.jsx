// ============Ouverture Premiere image===========================================
var idOpn = charIDToTypeID( "Opn " );
    var desc1 = new ActionDescriptor();
    var idnull = charIDToTypeID( "null" );
    desc1.putPath( idnull, new File( "C:\\Users\\Delphine\\Dropbox\\Echange Pierre-Del\\Location\\Location actuel Nantes 4 rue Scribe\\Photos\\Image Ouverture 1.jpg" ) );
executeAction( idOpn, desc1, DialogModes.NO );

// ==============Importation Deuxieme image =========================================
var idPlc = charIDToTypeID( "Plc " );
    var desc2 = new ActionDescriptor();
    var idnull = charIDToTypeID( "null" );
    desc2.putPath( idnull, new File( "C:\\Users\\Delphine\\Dropbox\\Echange Pierre-Del\\Location\\Location actuel Nantes 4 rue Scribe\\Photos\\Image Ouverture Deuxieme.jpg" ) );
    var idFTcs = charIDToTypeID( "FTcs" );
    var idQCSt = charIDToTypeID( "QCSt" );
    var idQcsa = charIDToTypeID( "Qcsa" );
    desc2.putEnumerated( idFTcs, idQCSt, idQcsa );
    var idOfst = charIDToTypeID( "Ofst" );
        var desc3 = new ActionDescriptor();
        var idHrzn = charIDToTypeID( "Hrzn" );
        var idRlt = charIDToTypeID( "#Rlt" );
        desc3.putUnitDouble( idHrzn, idRlt, 0.000000 );
        var idVrtc = charIDToTypeID( "Vrtc" );
        var idRlt = charIDToTypeID( "#Rlt" );
        desc3.putUnitDouble( idVrtc, idRlt, 0.000000 );
    var idOfst = charIDToTypeID( "Ofst" );
    desc2.putObject( idOfst, idOfst, desc3 );
executeAction( idPlc, desc2, DialogModes.NO );

// ===========Script Plomb en fusion deCS5 de base===========================================
var idPly = charIDToTypeID( "Ply " );
    var desc4 = new ActionDescriptor();
    var idnull = charIDToTypeID( "null" );
        var ref1 = new ActionReference();
        var idActn = charIDToTypeID( "Actn" );
        ref1.putName( idActn, "Plomb en fusion" );
        var idASet = charIDToTypeID( "ASet" );
        ref1.putName( idASet, "Pierre Script" );
    desc4.putReference( idnull, ref1 );
executeAction( idPly, desc4, DialogModes.NO );


// =================Reduction à 10 % ======================================
var idImgS = charIDToTypeID( "ImgS" );
    var desc10 = new ActionDescriptor();
    var idWdth = charIDToTypeID( "Wdth" );
    var idPrc = charIDToTypeID( "#Prc" );
    desc10.putUnitDouble( idWdth, idPrc, 10.000000 );
    var idscaleStyles = stringIDToTypeID( "scaleStyles" );
    desc10.putBoolean( idscaleStyles, true );
    var idCnsP = charIDToTypeID( "CnsP" );
    desc10.putBoolean( idCnsP, true );
    var idIntr = charIDToTypeID( "Intr" );
    var idIntp = charIDToTypeID( "Intp" );
    var idBcbc = charIDToTypeID( "Bcbc" );
    desc10.putEnumerated( idIntr, idIntp, idBcbc );
executeAction( idImgS, desc10, DialogModes.NO );

// ==============Applatir =========================================
var idFltI = charIDToTypeID( "FltI" );
executeAction( idFltI, undefined, DialogModes.NO );

// ===================Enregistrement de la miniature ====================================
var idsave = charIDToTypeID( "save" );
    var desc11 = new ActionDescriptor();
    var idAs = charIDToTypeID( "As  " );
        var desc12 = new ActionDescriptor();
        var idEQlt = charIDToTypeID( "EQlt" );
        desc12.putInteger( idEQlt, 12 );
        var idMttC = charIDToTypeID( "MttC" );
        var idMttC = charIDToTypeID( "MttC" );
        var idNone = charIDToTypeID( "None" );
        desc12.putEnumerated( idMttC, idMttC, idNone );
    var idJPEG = charIDToTypeID( "JPEG" );
    desc11.putObject( idAs, idJPEG, desc12 );
    var idIn = charIDToTypeID( "In  " );
    desc11.putPath( idIn, new File( "C:\\Users\\Delphine\\Dropbox\\Echange Pierre-Del\\Pierre TMP\\Miniature10prct.jpg" ) );
executeAction( idsave, desc11, DialogModes.NO );


