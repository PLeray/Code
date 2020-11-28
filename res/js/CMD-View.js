


/***********************************************************************************/
/********************************/
window.onload = function (){ 

InitCommandes();

};



// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
} 


function VisuCMD(elementId) {
	var ele = document.getElementById(elementId);
	if(ele.style.display == "none") {
		ele.style.display = "block";
		setCookie(elementId, 'affiche', 30);			
  	}
	else {
		ele.style.display = "none";
		setCookie(elementId, 'cache', 30);
	}
	//alert('Cookie cmd : ' + elementId + ' val : ' + getCookie(elementId) );
} 

function InitCommandes(referme = true) {
    var cmd, i, etat;	
	cmd = document.getElementsByClassName('Contenucommande');
    for (i = 0; i < cmd.length; i++) {
		if (referme){
			etat = 'block';
			if (getCookie(cmd[i].id)=='cache'){	etat = 'none';}
			cmd[i].style.display = etat;						
		}else{
			cmd[i].style.display = 'block';			
		}
    }	
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}
/*
function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("mySearch");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
        if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
        } else {
            a[i].style.display = "none";
        }
    }
	document.getElementById("myDropdown").classList.toggle("show");
}*/

function filterFunction() {
    var input, filter, cmd, i, e, listDrop  ;
	var tabCMD = [];
	var synthTabCMD = [];	
    input = document.getElementById("mySearch");
    filter = input.value.toUpperCase();
	var elemRech = input.value.toUpperCase().split(' ');
	for (e = 0; e < elemRech.length; e++) {
		////////////////////////////////////////////////:
		/* pour les num de commandes */
		cmd = document.getElementsByClassName("Contenucommande");
		for (i = 0; i < cmd.length; i++) {
			if (cmd[i].id.toUpperCase().indexOf(elemRech[e]) > -1) {	
				tabCMD.push(cmd[i].id.toUpperCase()); // ajoute num commande ds Tableau
			}
		}		
		////////////////////////////////////////////////:
		/* pour les Produits */
		cmd = document.getElementsByClassName("produit");
		//a = div.getElementsByTagName("a");
		for (i = 0; i < cmd.length; i++) {			
			if (cmd[i].id.toUpperCase().indexOf(elemRech[e]) > -1) {			
				tabCMD.push(cmd[i].parentNode.id.toUpperCase()); // ajoute num commande ds Tableau
			}		
		}	
		////////////////////////////////////////////////:
		/* pour les nom Planche derriere photo */
		cmd = document.getElementsByClassName("planche");
		for (i = 0; i < cmd.length; i++) {
			if (cmd[i].id.toUpperCase().indexOf(elemRech[e]) > -1) {	
				tabCMD.push(cmd[i].parentNode.parentNode.id.toUpperCase()); // ajoute num commande ds Tableau
			}
		}	

		
		////////////////////////////////////////////////:
		// On recupere les elements uniques des commandes trouvées
		//console.log("tabCMD '" + elemRech[e] + "' : " + tabCMD);
		if(e == 0){
			synthTabCMD = [...new Set(tabCMD)];
			//console.log("synthTabCMD0 '" + elemRech[e] + "' : " + synthTabCMD);
		}
		else{
			synthTabCMD = intersect(synthTabCMD, tabCMD)
			//console.log("synthTabCMDn '" + elemRech[e] + "' : " + synthTabCMD);
		}
		tabCMD = [];
		
	}
	
	// Affichage des commandes affichées dans la Dropdown à Jour 
	listDrop = document.getElementById("myDropdown").getElementsByTagName("a");//dropdown-content
    for (i = 0; i < listDrop.length; i++) {	
        if (synthTabCMD.indexOf(listDrop[i].innerHTML.trim().toUpperCase()) > -1) { 		
            listDrop[i].style.display = "";
        } else {
            listDrop[i].style.display = "none";
        }
    }	
	// Affichage des commandes affichées dans la page à Jour 
	listDrop = document.getElementById("myDropdown").getElementsByTagName("a");//dropdown-content
	cmd = document.getElementsByClassName("Contenucommande");
	
    for (i = 0; i < cmd.length; i++) {		
        if (synthTabCMD.indexOf(cmd[i].id.toUpperCase()) > -1) { 	
			//console.log("cmd[i].id.toUpperCase() : " + cmd[i].id.toUpperCase());		
            cmd[i].parentNode.style.display = ""; // .parentNode effacer toute la commande, même le titre
        } else {
            cmd[i].parentNode.style.display = "none";
        }
    }	
	//console.log("filter !=  : " + (filter != ''));	
	document.getElementById("myDropdown").classList.toggle("show");
	InitCommandes(filter == '');
}

function intersect(a, b) {
  var setB = new Set(b);
  return [...new Set(a)].filter(x => setB.has(x));
}