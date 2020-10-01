/**/

var w = new Window('dialog');
w.maximumSize.height = 300;


var show_btn = w.add ("button", undefined, "show");
show_btn.onClick = function () {
var txt = "";
for (var n = 0; n < maingroup.children.length; n++) {
 txt += maingroup.children[n].edit.text + "\n";
}
alert ("Rows: \n" + txt);
}



var panel = w.add ('panel {alignChildren: "left"}');
 var maingroup = panel.add ('panel {orientation: "column"}');
 for (var i = 0; i <= 35; i++) {
 
 add_row (maingroup)
 }


var scrollBar = panel.add  ("scrollbar", [0,0,220,400], 0, 0, 60);
// Move the whole scroll group up or down
scrollBar.onChanging = function () {
 maingroup.location.y = -1 * this.value;
}
w.onShow = function() {
 // Set various sizes and locations when the window is drawn
 panel.size.height = w.size.height-120;
 scrollBar.size.height = w.size.height-140;
 scrollBar.size.width = 20;
 scrollBar.location = [panel.size.width-30, 8];
 scrollBar.maxvalue = maingroup.size.height - panel.size.height + 15;
};
w.show();

function add_row (maingroup) {
var group = maingroup.add ("group");

group.RefClasse = group.add('statictext', undefined, 'Lawsdqbel ' + i);

group.NomClasse = group.add ("edittext", ["", "", 200, 20], "tretre " + maingroup.children.length);
//i=33;
//group.add ('statictext', undefined, 'Lawsdqbel ' + i);

maingroup.children

group.index = maingroup.children.length - 1;
w.layout.layout (true);
}
