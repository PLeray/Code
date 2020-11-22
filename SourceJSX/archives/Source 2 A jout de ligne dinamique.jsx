var win = new Window ("dialog");

 win.maximumSize.height = 500;
var scrollBar = win.add  ("scrollbar", [0,0,20,200], 0, 0, 60);

var panel =win.add ('panel {alignChildren: "left"}');
 var maingroup = panel.add ("panel {orientation: 'column'}");
 
 


add_row (maingroup);
var show_btn = win.add ("button", undefined, "show");
show_btn.onClick = function () {
var txt = "";
for (var n = 0; n < maingroup.children.length; n++) {
 txt += maingroup.children[n].edit.text + "\n";
}
alert ("Rows: \n" + txt);
}


// Move the whole scroll group up or down
scrollBar.onChanging = function () {
 maingroup.location.y = -1 * this.value;
}



win.show ();
function add_row (maingroup) {
var group = maingroup.add ("group");
group.edit = group.add ("edittext", ["", "", 200, 20], 'ljhkvg ' + maingroup.children.length);
group.plus = group.add ("button", undefined, "+");
group.plus.onClick = add_btn;

group.minus = group.add ("button", undefined, "-");
group.minus.onClick = minus_btn;
group.index = maingroup.children.length - 1;
win.layout.layout (true);
}
function add_btn () {
add_row (maingroup);
}
function minus_btn () {
maingroup.remove (this.parent);
win.layout.layout (true);
}

win.onShow = function() {
 // Set various sizes and locations when the window is drawn
 panel.size.height =win.size.height-120;
 scrollBar.size.height =win.size.height-140;
 scrollBar.size.width = 20;
 scrollBar.location = [panel.size.width-30, 8];
 scrollBar.maxvalue = maingroup.size.height - panel.size.height + 15;
};