// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};



function EffacerChargement(){
   document.getElementById('MSGChargement').style.display='none';
   document.getElementById('site').style.visibility='visible';
}

/********************************/
window.onload = function (){ 
//alert('Onload :!!');
	InitCommandes();
	EffacerChargement();
};

function VoirPannier() {
	var ele = document.getElementById('monDetailPannier');
	if(ele.style.display == "none") {
		ele.style.display = "block";		
	}
	else {
		ele.style.display = "none";

	}
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

function CouleurBoutonPage_OLD_SUPR(numPage) {
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

function filterPlanches() {
	var input, filter;  
	input = document.getElementById("zoneRecherchePlanche");
	filter = input.value.toUpperCase();	
	//var elementsRech = input.value.toUpperCase().split(' ');
	//RechercheMulti(filter);
	//alert('dsd');
	RecherchePlanche(filter);
}

function RecherchePlanche(strElementsRech) {
	var tabElementsRech = strElementsRech.split(' ');
    var lesPlanches, i, e;
	var isVisible = "none";
	//var listDrop  ;
	//var tabCMD = [];
	//var synthTabCMD = [];	
	//document.getElementById("zoneRechercheCMD").removeAttribute("mark"); 
	
	lesPlanches = document.getElementsByClassName("planche");

		for (i = 0; i < lesPlanches.length; i++) {
			lesPlanches[i].style.display = "none";
			lesPlanches[i].parentNode.style.display = "none";		
			lesPlanches[i].parentNode.parentNode.style.display = "none";
			lesPlanches[i].parentNode.parentNode.parentNode.style.display = "none";
			lesPlanches[i].parentNode.parentNode.parentNode.parentNode.style.display = "none"; // Les ecoles 

		}

	for (e = 0; e < tabElementsRech.length; e++) {
		////////////////////////////////////////////////:
		// pour les num de commandes 
		/*	*/


		
		for (i = 0; i < lesPlanches.length; i++) {
			//alert('id : ' + lesPlanches[i].id + ' is dans champ : ' + (lesPlanches[i].id.toUpperCase().indexOf(tabElementsRech[e]) > -1));
			if (lesPlanches[i].id.toUpperCase().indexOf(tabElementsRech[e]) > -1) {	
				isVisible = "inline-block";
				//lesPlanches[i].style.display = "";

			lesPlanches[i].style.display = isVisible;
			lesPlanches[i].parentNode.style.display = isVisible;
			lesPlanches[i].parentNode.parentNode.style.display = isVisible;
			lesPlanches[i].parentNode.parentNode.parentNode.style.display = isVisible;
			lesPlanches[i].parentNode.parentNode.parentNode.parentNode.style.display = isVisible; // Les ecoles	
				
			}
		}		
	}
}

function filterCommandes() {
	var input, filter;  
	input = document.getElementById("zoneRecherche");
	filter = input.value.toUpperCase();	
	//var elementsRech = input.value.toUpperCase().split(' ');
	//RechercheMulti(filter);
	RechercheCommandes(filter);
}

function RechercheCommandes(strElementsRech) {
	//alert(strElementsRech);
	var tabElementsRech = strElementsRech.split(' ');
    var cmd, i, e;
	//var listDrop  ;
	//var tabCMD = [];
	//var synthTabCMD = [];	
	//document.getElementById("zoneRechercheCMD").removeAttribute("mark"); 
	cmd = document.getElementsByClassName("commande"); 
	for (e = 0; e < tabElementsRech.length; e++) {
		////////////////////////////////////////////////:
		/* pour les num de commandes */
		
		for (i = 0; i < cmd.length; i++) {
			cmd[i].style.display = "none";
			cmd[i].parentNode.style.display = "none";	
		}

		for (i = 0; i < cmd.length; i++) {
			if (cmd[i].id.toUpperCase().indexOf(tabElementsRech[e]) > -1) {	
				cmd[i].style.display = "inline-block";
				cmd[i].parentNode.style.display = "inline-block";

			}/*
			else{
				cmd[i].style.display = "none";
			}*/
		}	
	}
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
			console.log("synthTabCMD0 '" + tabElementsRech[e] + "' : " + synthTabCMD);
		}
		else{
			synthTabCMD = intersect(synthTabCMD, tabCMD)
			console.log("synthTabCMDn '" + tabElementsRech[e] + "' : " + synthTabCMD);
		}
		tabCMD = [];
		
	}
	// Affichage des commandes affichées dans la Dropdown à Jour 

	listDrop = document.getElementById("listeRechercheCMD").getElementsByTagName("li");
    for (i = 0; i < listDrop.length; i++) {	
		menu = listDrop[i].getElementsByTagName("a")[0];
		console.log("menu : " + menu.innerHTML.trim());	
		console.log("synthTabCMD : " + synthTabCMD);	
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
			console.log("cmd[i].id.toUpperCase() : " + cmd[i].id.toUpperCase());		
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



//AfficheRechercheCMD(true);	

}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {  


//AfficheRechercheCMD(false);
InitCommandes();

}

function SelectionPhoto(x) {
	//	if(x.classList.contains("planche") && isRECOmmandes){
	if(x.classList.contains("planche") ){
		x.classList.replace("planche", "plancheSELECTIONNER");
		x.setAttribute('title',  'Planche en cours de recommande  ' + x.getAttribute('id'));					
	}else {
		x.classList.replace("plancheSELECTIONNER", "planche");
		x.setAttribute('title',  x.getAttribute('id'));
		document.getElementById("CaseSelectionnerCommandesAffiche").className = 'caseCheckVide';
	}  
	
	mesRecommandes();
}

function SelectionnerCommandesAffiche() {
	var ToutSelectionner = (document.getElementById("CaseSelectionnerCommandesAffiche").className == 'caseCheckVide');
	if (ToutSelectionner){
		var mesPlanches = document.getElementsByClassName("planche");
		while(mesPlanches.length >0){
			if ( mesPlanches[i].offsetHeight > 0 ) {
				mesPlanches[0].classList.replace("planche", "plancheSELECTIONNER");
			}
			else{
				mesPlanches[0].classList.replace("planche", "plancheNONVisible");
			}			
		}	
			/*
		for (i = 0; i < mesPlanches.length; i++) {			
			if ( mesPlanches[i].offsetHeight > 0 ) {
				mesPlanches[i].className = "plancheSELECTIONNER";
			}
				
			if (window.getComputedStyle(mesPlanches[i]).display != "none") {
				mesPlanches[i].className = "plancheSELECTIONNER";}
		}	*/
		/*
		*/
		var mesPlanches = document.getElementsByClassName("plancheNONVisible");
		while(mesPlanches.length >0){
			mesPlanches[0].classList.replace("plancheNONVisible", "planche");
		}

	}else{
		var mesPlanches = document.getElementsByClassName("plancheSELECTIONNER");
		while(mesPlanches.length >0){
			mesPlanches[0].classList.replace("plancheSELECTIONNER", "planche");	
		}
	}
	document.getElementById("CaseSelectionnerCommandesAffiche").className = (ToutSelectionner?'caseCheckCoche':'caseCheckVide');
	mesRecommandes();
}		
			
function VoirPhotoSelection() {
	var ToutEstAfficher = (document.getElementById("CaseVoirCommandes").className == 'caseCheckVide');
	var StatutAffiche = (ToutEstAfficher ? 'none' : 'inline-block' );
	var mesPlanches = document.getElementsByClassName("planche");
	for (i = 0; i < mesPlanches.length; i++) {
		mesPlanches[i].style.display = StatutAffiche;
		mesPlanches[i].parentNode.style.display = StatutAffiche;		
		mesPlanches[i].parentNode.parentNode.style.display = StatutAffiche;
		mesPlanches[i].parentNode.parentNode.parentNode.style.display = StatutAffiche;
		mesPlanches[i].parentNode.parentNode.parentNode.parentNode.style.display = StatutAffiche; // Les ecoles
	}
	
	if (ToutEstAfficher){
		//alert('Seilement les ');
		var mesPlanches = document.getElementsByClassName("plancheSELECTIONNER");

		for (i = 0; i < mesPlanches.length; i++) {
			mesPlanches[i].style.display = "inline-block";
			mesPlanches[i].parentNode.style.display = "inline-block";
			mesPlanches[i].parentNode.parentNode.style.display = "inline-block";
			mesPlanches[i].parentNode.parentNode.parentNode.style.display = "inline-block";	
			mesPlanches[i].parentNode.parentNode.parentNode.parentNode.style.display = "inline-block"; // Les ecoles
		}	
	}

	/* */
	

	//Changement etat case a cocher
	document.getElementById("CaseVoirCommandes").className = (ToutEstAfficher?'caseCheckCoche':'caseCheckVide');

	

	
	
}		

function mesRecommandes() {
	var mesReco ='';
	var mesPlanches = document.getElementsByClassName("plancheSELECTIONNER");


	document.getElementById("btnEnregistrerCMD").style.display = ((mesPlanches.length > 0)?'inline-block':'none');
	document.getElementById("txtAfficherSelection").innerHTML = '            Afficher sélection des recommandes : <br>' + mesPlanches.length + ' Planches';
	//document.getElementById("txtAfficherSelection").innerHTML = "Afficher dsfsdfsdn des recommandes : ";


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
