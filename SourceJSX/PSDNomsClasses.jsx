
// PALETTE
// =======
var palette = new Window("dialog", undefined, undefined, {su1PanelCoordinates: true}); 
    palette.text = "Nom des Classes ..."; 
    palette.orientation = "column"; 
    palette.alignChildren = ["center","top"]; 
    palette.spacing = 10; 
    palette.margins = 16; 

// GROUP1
// ======
var group1 = palette.add("group", undefined, {name: "group1"}); 
    group1.orientation = "row"; 
    group1.alignChildren = ["left","center"]; 
    group1.spacing = 10; 
    group1.margins = 0; 

var statictext1 = group1.add("statictext", undefined, undefined, {name: "statictext1"}); 
    statictext1.text = "Nom de classe 1"; 

var edittext1 = group1.add('edittext {properties: {name: "edittext1"}}'); 
    edittext1.text = "EditText"; 
    edittext1.preferredSize.width = 400; 

// GROUP2
// ======
var group2 = palette.add("group", undefined, {name: "group2"}); 
    group2.orientation = "row"; 
    group2.alignChildren = ["left","center"]; 
    group2.spacing = 10; 
    group2.margins = 0; 

var statictext2 = group2.add("statictext", undefined, undefined, {name: "statictext2"}); 
    statictext2.text = "Nom de classe 2"; 

var edittext2 = group2.add('edittext {properties: {name: "edittext2"}}'); 
    edittext2.text = "EditText"; 
    edittext2.preferredSize.width = 400; 

// GROUP3
// ======
var group3 = palette.add("group", undefined, {name: "group3"}); 
    group3.orientation = "row"; 
    group3.alignChildren = ["right","center"]; 
    group3.spacing = 10; 
    group3.margins = 0; 
    group3.alignment = ["right","top"]; 

var button1 = group3.add("button", undefined, undefined, {name: "button1"}); 
    button1.text = "Annuler"; 

var button2 = group3.add("button", undefined, undefined, {name: "button2"}); 
    button2.text = "Valider"; 

palette.show();

