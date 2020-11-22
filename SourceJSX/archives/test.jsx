/**/

var w = new Window('dialog');
w.maximumSize.height = 300;


var show_btn = w.add ("button", undefined, "show");
show_btn.onClick = function () {
add_row (maingroup);
}

var panel = w.add ('panel {alignChildren: "left"}');
 var scrollGroup = panel.add ('group {orientation: "column"}');
 for (var i = 0; i <= 35; i++) {
 scrollGroup.add ('statictext', undefined, 'Lawsdqbel ' + i);
 }
var maingroup = panel.add ("panel {orientation: 'column'}");
add_row (maingroup);

var scrollBar = panel.add  ("scrollbar", [0,0,200,200], 0, 0, 60);
// Move the whole scroll group up or down
scrollBar.onChanging = function () {
 scrollGroup.location.y = -1 * this.value;
}
w.onShow = function() {
 // Set various sizes and locations when the window is drawn
 panel.size.height = w.size.height-120;
 scrollBar.size.height = w.size.height-140;
 scrollBar.size.width = 20;
 scrollBar.location = [panel.size.width-30, 8];
 scrollBar.maxvalue = scrollGroup.size.height - panel.size.height + 15;
};
w.show();

function add_row (maingroup) {
var group = maingroup.add ("group");
group.edit = group.add ("edittext", ["", "", 200, 20], maingroup.children.length);


group.index = maingroup.children.length - 1;
w.layout.layout (true);
}

