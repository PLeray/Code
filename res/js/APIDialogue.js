
//
var ValeurInterdite = "(obligatoire !)";
var sepPlanches = '§';
//var ValeurInterdite = "15x23cm";

InitDropListe(NumPlanche);
MAJTabPlanche();

//alert(' NumPlanche ' + NumPlanche);

/*if the user clicks anywhere outside the select box,
then close all select boxes:*/
document.addEventListener("click", closeAllSelect);

function InitDropListe() {
  var x, i, j, l, ll, selElmnt, a, b, c;
  /*look for any elements with the class "custom-select":*/
  x = document.getElementsByClassName("custom-select");
  l = x.length;
  for (i = 0; i < l; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    ll = selElmnt.length;
    /*for each element, create a new DIV that will act as the selected item:*/
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /*for each element, create a new DIV that will contain the option list:*/
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < ll; j++) {
      /*for each option in the original select element,
      create a new DIV that will act as an option item:*/
      c = document.createElement("DIV");
      c.innerHTML = selElmnt.options[j].innerHTML;
      c.addEventListener("click", function(e) {
          /*when an item is clicked, update the original select box,
          and the selected item:*/
          var y, i, k, s, h, sl, yl;
          s = this.parentNode.parentNode.getElementsByTagName("select")[0];
          sl = s.length;
          h = this.parentNode.previousSibling;
          for (i = 0; i < sl; i++) {
            if (s.options[i].innerHTML == this.innerHTML) {
              s.selectedIndex = i;
              h.innerHTML = this.innerHTML;
              y = this.parentNode.getElementsByClassName("same-as-selected");
              yl = y.length;
              for (k = 0; k < yl; k++) {
                y[k].removeAttribute("class");
              }
              this.setAttribute("class", "same-as-selected");
              break;
            }
          }
          h.click();
      });
      b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function(e) {
        /*when the select box is clicked, close any other select boxes,
        and open/close the current select box:*/
        e.stopPropagation();
        closeAllSelect(this);
        this.nextSibling.classList.toggle("select-hide");
        this.classList.toggle("select-arrow-active");
        MiseAJourPDTCodeScript(0);
        
      });
  }
  document.getElementById("btnOK").disabled = document.getElementById("PDTCodeScripts").value.includes(ValeurInterdite);
  MAJTabPlanche();
}

function MiseAJourPDTCodeScript(NumPlanche) {
  var lePDTCodeScripts = document.getElementById("PDTCodeScripts").value;


  var leTabPlanches = document.getElementById("PDTCodeScripts").value.split(sepPlanches);
  lePDTCodeScripts = '';
  for (i = 0; i < leTabPlanches.length; i++) {
    if(NumPlanche == i){
      lePDTCodeScripts +=  sepPlanches + document.getElementById("PDTTaille").value;
      lePDTCodeScripts += '_' + document.getElementById("PDTTransformation").value;
      lePDTCodeScripts += '_' + document.getElementById("PDTTeinte").value;
      if (document.getElementById("PDTRecadrage") != null){
        lePDTCodeScripts += '_' + document.getElementById("PDTRecadrage").value;
      }
      //lePDTCodeScripts += '.' + lePDTCodeScripts ;
    }else{
      lePDTCodeScripts += sepPlanches + leTabPlanches[i];
    }
  }

/*


  var lePDTCodeScripts;


  //alert('closeAsdqqsdllSelect');
  //document.getElementByid("PDTCodeScripts").value = document.getElementByid("PDTTaille").value;

  var lePDTCodeScripts = document.getElementById("PDTTaille").value;
  lePDTCodeScripts += '_' + document.getElementById("PDTTransformation").value;
  lePDTCodeScripts += '_' + document.getElementById("PDTTeinte").value;
  if (document.getElementById("PDTRecadrage") != null){
    lePDTCodeScripts += '_' + document.getElementById("PDTRecadrage").value;
  }
  */
  document.getElementById("PDTCodeScripts").value = lePDTCodeScripts.replaceAll('(facultatif)', '').substring(1); // Pour enlever le premier "."
}

function closeAllSelect(elmnt) {
  /*a function that will close all select boxes in the document,
  except the current select box:*/
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
      
    }
  }
  //document.getElementById("btnOK").disabled = (document.getElementById("PDTTeinte").value === "(facultatif)");
  //document.getElementById("btnOK").disabled = (document.getElementById("PDTTaille").value === "(obligatoire !)");
  document.getElementById("btnOK").disabled = document.getElementById("PDTCodeScripts").value.includes(ValeurInterdite);
  
}


function MAJTabPlanche() {

}
