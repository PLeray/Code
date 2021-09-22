// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

/*var rech = document.getElementById("zoneRechercheCMD");
rech.style.display = 'none';
AfficheRechercheCMD(false);*/

//initPagination();

function EffacerChargement(){
   document.getElementById('MSGChargement').style.display='none';
   document.getElementById('site').style.display='block';
}



function AfficheRechercheCMD(isAffiche) {
	isRECOmmandes = isAffiche;
	if (isAffiche){
		
		document.getElementById("mySidenav").style.width = "350px";
		//document.getElementById("mySidenav").style.z-index = 2;  
		document.getElementById("main").style.marginRight = "350px";
		document.getElementById("Entete").style.marginRight = "350px";			
		
		var rech = document.getElementById("zoneRechercheCMD");
		rech.style.display = '';
		var list = document.getElementById("zoneListePageCMD");
		list.style.display = 'none';			
		
	}
	else{
		document.getElementById("zoneRecherche").value ='';
		RechercheMulti(' ');
		document.getElementById("mySidenav").style.width = "0";
		  //document.getElementById("mySidenav").style.z-index = -2;  
		document.getElementById("main").style.marginRight = "0";
		document.getElementById("Entete").style.marginRight = "0";		
		
		
		var rech = document.getElementById("zoneRechercheCMD");
		rech.style.display = 'none';
		var list = document.getElementById("zoneListePageCMD");
		list.style.display = 'block';			
	}
}
	
/***********************************************************************************/
/********************************/
window.onload = function (){ 
//alert('Onload :!!');
	InitCommandes();
	EffacerChargement();
};

function AfficheGroupe() {
	//alert('AfficheGroupe :!!');
	listPhoto = document.getElementsByClassName('PlancheGroupe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline";
    }	
	listPhoto = document.getElementsByClassName('PlancheIndiv');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "none";
    }	
	listPhoto = document.getElementsByClassName('ligne_classe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "none";
    }		
	document.getElementById("idAfficheGroupe").style.display = "none";
	document.getElementById("idAfficheIndivs").style.display = "inline";
	document.getElementById("idAfficheTout").style.display = "inline";
}

function AfficheIndivs() {
	listPhoto = document.getElementsByClassName('PlancheGroupe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "none";
    }	
	listPhoto = document.getElementsByClassName('PlancheIndiv');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline-block";
    }	
	listPhoto = document.getElementsByClassName('ligne_classe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "flex";
    }		
	document.getElementById("idAfficheGroupe").style.display = "inline";
	document.getElementById("idAfficheIndivs").style.display = "none";
	document.getElementById("idAfficheTout").style.display = "inline";	
}

function AfficheTout() {
	listPhoto = document.getElementsByClassName('PlancheGroupe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline";
    }	
	listPhoto = document.getElementsByClassName('PlancheIndiv');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline-block";
    }	
	listPhoto = document.getElementsByClassName('ligne_classe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "flex";
    }		
	document.getElementById("idAfficheGroupe").style.display = "inline";
	document.getElementById("idAfficheIndivs").style.display = "inline";
	document.getElementById("idAfficheTout").style.display = "none";	
}


function scrollFunction() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		document.getElementById("btnRemonter").style.display = "block";
	} else {
		document.getElementById("btnRemonter").style.display = "none";
	}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
	document.body.scrollTop = 0; // For Safari
	document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
} 



//function InitCommandes(referme = true) {
function InitCommandes(referme) {
	var referme = (typeof referme !== 'undefined') ? referme : true;	
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
	MAJPage();	
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

function filterFunction() {
	var input, filter;  
	input = document.getElementById("zoneRecherche");
	filter = input.value.toUpperCase();	
	//var elementsRech = input.value.toUpperCase().split(' ');
	RechercheMulti(filter);
}

function RechercheMulti(strElementsRech) {
	var tabElementsRech = strElementsRech.split(' ');
    var cmd, i, e, listDrop  ;
	var tabCMD = [];
	var synthTabCMD = [];	
	document.getElementById("zoneRechercheCMD").removeAttribute("mark"); 
	for (e = 0; e < tabElementsRech.length; e++) {
		////////////////////////////////////////////////:
		/* pour les num de commandes */
		cmd = document.getElementsByClassName("commande");
		for (i = 0; i < cmd.length; i++) {
			if (cmd[i].id.toUpperCase().indexOf(tabElementsRech[e]) > -1) {	
				texteColoration = cmd[i].getElementsByTagName("button")[0];
				texteColoration.innerHTML = ColorRecherche(texteColoration.textContent, tabElementsRech[e]);			
			
				tabCMD.push(cmd[i].id.toUpperCase()); // ajoute num commande ds Tableau
			}
			else {cmd[i].getElementsByTagName("button")[0].removeAttribute("mark"); }
		}	
		/*		
		cmd = document.getElementsByClassName("TitrecommandeRecherche");
		for (i = 0; i < cmd.length; i++) {
			if (cmd[i].textContent.toUpperCase().indexOf(tabElementsRech[e]) > -1) {	
				texteColoration = cmd[i][0];
				texteColoration.innerHTML = ColorRecherche(texteColoration.textContent, tabElementsRech[e]);			
			
				tabCMD.push(cmd[i].id.toUpperCase()); // ajoute num commande ds Tableau
			}
		}	
*/

		
		////////////////////////////////////////////////:
		/* pour les Produits */
		cmd = document.getElementsByClassName("produit");
		//a = div.getElementsByTagName("a");
		for (i = 0; i < cmd.length; i++) {			
			if (cmd[i].id.toUpperCase().indexOf(tabElementsRech[e]) > -1) {		
				texteColoration = cmd[i].getElementsByTagName("h4")[0];
				texteColoration.innerHTML = ColorRecherche(texteColoration.textContent, tabElementsRech[e]);
				texteColoration = cmd[i].getElementsByTagName("h5")[0];
				texteColoration.innerHTML = ColorRecherche(texteColoration.textContent, tabElementsRech[e]);
			
				tabCMD.push('C-' + cmd[i].parentNode.id.toUpperCase()); // ajoute num commande ds Tableau
			}		
		}	
		////////////////////////////////////////////////:
		/* pour les nom Planche derriere photo */
		cmd = document.getElementsByClassName("planche");
		for (i = 0; i < cmd.length; i++) {
			if (cmd[i].id.toUpperCase().indexOf(tabElementsRech[e]) > -1) {	
				texteColoration = cmd[i].getElementsByTagName("p")[0];
				texteColoration.innerHTML = ColorRecherche(texteColoration.textContent, tabElementsRech[e]);
				tabCMD.push('C-' + cmd[i].parentNode.parentNode.id.toUpperCase()); // ajoute num commande ds Tableau
			}
		}	

		////////////////////////////////////////////////:
		// On recupere les elements uniques des commandes trouvées
		if(e == 0){
			//synthTabCMD = [...new Set(tabCMD)]; // Pour Minify ...
			synthTabCMD = tabCMD; // // Averifier .?? Pour Minify ...
			//console.log("synthTabCMD0 '" + tabElementsRech[e] + "' : " + synthTabCMD);
		}
		else{
			synthTabCMD = intersect(synthTabCMD, tabCMD)
			//console.log("synthTabCMDn '" + tabElementsRech[e] + "' : " + synthTabCMD);
		}
		tabCMD = [];
		
	}
	// Affichage des commandes affichées dans la Dropdown à Jour 

	listDrop = document.getElementById("listeRechercheCMD").getElementsByTagName("li");
    for (i = 0; i < listDrop.length; i++) {	
		menu = listDrop[i].getElementsByTagName("a")[0];
		//console.log("menu : " + menu.innerHTML.trim());	
		//console.log("synthTabCMD : " + synthTabCMD);	
        if (synthTabCMD.indexOf('C-' + menu.innerHTML.trim().toUpperCase()) > -1) { 		
            listDrop[i].style.display = "";
        } else {
            listDrop[i].style.display = "none";
        }
    }	
	// Affichage des commandes affichées dans la page à Jour 
	//listDrop = document.getElementById("myDropdown").getElementsByTagName("a");//dropdown-content
	cmd = document.getElementsByClassName("commande");
	
    for (i = 0; i < cmd.length; i++) {		
        if (synthTabCMD.indexOf(cmd[i].id.toUpperCase()) > -1) { 	
			//console.log("cmd[i].id.toUpperCase() : " + cmd[i].id.toUpperCase());		
            cmd[i].style.display = ""; // .parentNode effacer toute la commande, même le titre
        } else {
            cmd[i].style.display = "none";
        }
    }	

	
	InitCommandes(strElementsRech == '');
}
/* ne minify pas ...
function intersect(a, b) {
  var setB = new Set(b);
  var setA = new Set(a);
  //return [...new Set(a)].filter(x => setB.has(x));
  return setA.filter(x => setB.has(x));
}
*/
function ColorRecherche(unTexte, UneRech) {
	if(UneRech!=''){
		var regex = new RegExp(UneRech,'i');
		textedeRemplacement = unTexte.substr(unTexte.toUpperCase().indexOf(UneRech), UneRech.length);
		unTexte = unTexte.replace(regex,'<mark>'+ textedeRemplacement +'</mark>' );	
		
		//unTexte = unTexte.replace(regex,'<mark>'+ UneRech +'</mark>' );	
	}
	return unTexte;
}

function openNav() {



AfficheRechercheCMD(true);	

}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {  


AfficheRechercheCMD(false);
InitCommandes();

}

function SelectionPhoto(x) {
	if(x.classList.contains("planche") && isRECOmmandes){
		x.classList.replace("planche", "plancheSELECTIONNER");
		x.setAttribute('title',  'Planche en cours de recommande  ' + x.getAttribute('id'));					
	}else {
		x.classList.replace("plancheSELECTIONNER", "planche");
		x.setAttribute('title',  x.getAttribute('id'));
	}  
	mesRecommandes();
}
			
function VoirPhotoSelection(x) {
	var mesPlanches = document.getElementsByClassName("planche");

	for (i = 0; i < mesPlanches.length; i++) {
	  if (mesPlanches[i].style.display === "none") {
		mesPlanches[i].style.display = "inline-block";
	  } else {
		mesPlanches[i].style.display = "none";
	  }
	}	
}		

function mesRecommandes() {
	var mesReco ='';
	var mesPlanches = document.getElementsByClassName("plancheSELECTIONNER");
	//console.log(' Nombre Page : ' + BoutonPage.length );	
	for (i = 0; i < mesPlanches.length; i++) {
	  mesReco = mesReco + '%' + mesPlanches[i].getAttribute('id');
	  //mesPlanches[i].setAttribute('title',  'Planche en cours de recommande  ' + mesPlanches[i].getAttribute('id'));
	}		

	document.getElementById('lesRecommandes').value =  mesReco;
}	
			
			
/*
function coloriserString(text, debut, fin, color) {
	return text.substring(0, debut) 
		+ "<strong>" + text.substring(debut, fin+1) + "</strong>"
		+ text.substring(fin+1);
}
function coloriserString2(text, debut, fin, color) {
	return text.substring(0, debut) 
		+ "<span style='color:" + color + "'>" + text.substring(debut, fin+1) + "</span>"
		+ text.substring(fin+1);
}*/
