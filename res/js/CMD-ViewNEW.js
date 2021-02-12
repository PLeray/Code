// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

/*var rech = document.getElementById("zoneRechercheCMD");
rech.style.display = 'none';
AfficheRechercheCMD(false);*/

//initPagination();

function EffacerChargement(){
   document.getElementById('MSGChargement').style.display='none';
   document.getElementById('site').style.visibility='visible';
}

function initPagination() {
	//console.log("document.onreadystatechange  : Avant" );	
	document.onreadystatechange = function() {
		//console.log("document.onreadystatechange  : OK" );	
		if (document.readyState === "complete") {
			var laZonePages = new purePajinate({ 
				containerSelector: '.zonePagesCMD .items', 
				itemSelector: '.zonePagesCMD .items .pageCMD', 
				navigationSelector: '.zonePagesCMD .page_navigation',
				wrapAround: false,
				navLabelPrev: '<',
				navLabelNext: '>',				
				pageLinksToDisplay: 20,
				itemsPerPage: 1,
				startPage: 0
			});
		}
	};
}	

function AfficheRechercheCMD(isAffiche) {
	if (isAffiche){
		
		
  document.getElementById("mySidenav").style.width = "250px";
  //document.getElementById("mySidenav").style.z-index = 2;  
  document.getElementById("main").style.marginRight = "250px";
  document.getElementById("Entete").style.marginRight = "250px";		
		
		
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

/*
// New defil page
function openPage(pageName,elmnt,color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }
  document.getElementById(pageName).style.display = "block";
  elmnt.style.backgroundColor = color;
}

// Get the element with id="defaultOpen" and click on it

*/

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
	//setCookie('name', 'inform...', 30);
	MAJPage();	
} 

function MAJPage() {	
	var BoutonPage = document.getElementsByClassName("page_link");
	//console.log(' Nombre Page : ' + BoutonPage.length );	
	for (i = 0; i < BoutonPage.length; i++) {
		console.log(' Page : ' + (i+1) );	
		BoutonPage[i].style.background = CouleurBoutonPage(i);
	}
}

function CouleurBoutonPage(numPage) {
	var numeroPage = numPage + 1;
	var laCouleur = 'var(--btnPageDebut)';
	
	var BoutonBackground = '';	
	var i, nbCMD, nbCMDFerme;

	var laPage = document.getElementById('P-' + (numeroPage));
	var lesCmdPage = laPage.getElementsByClassName('Contenucommande');

	nbCMD = lesCmdPage.length;
	nbCMDFerme = 0;

	//console.log(' Page : ' + numeroPage + ' / Nombre de commandes : ' + nbCMD );	

	for (i = 0; i < nbCMD; i++) {
			if(lesCmdPage[i].style.display == "none") {
			nbCMDFerme++;			
		}	/**/	
    }

	if (nbCMDFerme > 0) {laCouleur = 'var(--btnPageEntame)';}	
	if (nbCMD == nbCMDFerme) {laCouleur = 'var(--btnPageFini)';}

	//console.log(' laCouleur : ' + laCouleur );	
	BoutonBackground = 'radial-gradient(circle at 0px -5px, white, ' + laCouleur + ')';
	//console.log(' BoutonBackground : ' + BoutonBackground );	
	return BoutonBackground;
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

/*function myFunction2() {
    document.getElementById("myDropdown").classList.toggle("show");
}


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
	var input, filter;  
	input = document.getElementById("zoneRecherche");
	filter = input.value.toUpperCase();	
	var elementsRech = input.value.toUpperCase().split(' ');
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
			synthTabCMD = [...new Set(tabCMD)];
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

function intersect(a, b) {
  var setB = new Set(b);
  return [...new Set(a)].filter(x => setB.has(x));
}

function ColorRecherche(unTexte, UneRech) {
	if(UneRech!=''){
		var regex = new RegExp(UneRech,'g');
		unTexte = unTexte.replace(regex,'<mark>'+UneRech+'</mark>' );		
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
  //x.classList.toggle("PlancheSELECTIONNER");
  // document.getElementById('myButton').className = "Planche";
  
  
	if(x.classList.contains("Planche") ){
		x.classList.replace("Planche", "PlancheSELECTIONNER");
		x.setAttribute('title',  'Planche en cours de recommande  ' + x.getAttribute('id'));

	}else {
		x.classList.replace("PlancheSELECTIONNER", "Planche");
		x.setAttribute('title',  x.getAttribute('id'));
	}  

  
  mesRecommandes();
}
			
function VoirPhotoSelection(x) {
	var mesPlanches = document.getElementsByClassName("Planche");

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
	var mesPlanches = document.getElementsByClassName("PlancheSELECTIONNER");
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
