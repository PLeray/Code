var sepFinLigne = '§';

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function EffacerChargement(){
   document.getElementById('MSGChargement').style.display='none';
   document.getElementById('site').style.display='block';
}

function BasculeAfficheSideBar() {
	var isAffiche = (document.getElementById("mySidenav").style.width == "350px");
	AfficheRechercheCMD(!isAffiche);
}

function AfficheRechercheCMD(isAffiche) {
	//isRECOmmandes = isAffiche;
	
	if (isAffiche){
		
		document.getElementById("mySidenav").style.width = "350px";
		//document.getElementById("mySidenav").style.z-index = 2;  
		document.getElementById("main").style.marginRight = "350px";
		document.getElementById("Entete").style.marginRight = "350px";	
		document.getElementById("btnRemonter").style.right = "380px";	
		document.getElementById("closeSidenav").textContent = ">>";
	}
	else{
		document.getElementById("mySidenav").style.width = "50px";
		document.getElementById("mySidenav").style.height = "50px";
		  //document.getElementById("mySidenav").style.z-index = -2;  
		document.getElementById("main").style.marginRight = "50px";
		document.getElementById("Entete").style.marginRight = "50px";
		document.getElementById("btnRemonter").style.right = "80px";	
		document.getElementById("closeSidenav").textContent = "<<"; //= >>				
	}
}
	
/***********************************************************************************/
/********************************/
window.onload = function (){ 
//alert('Onload :!!');
	//InitCommandes();
	EffacerChargement();
};

function AfficheGroupe() {
	listPhoto = document.getElementsByClassName('PlancheGroupe');
	
	//alert('AfficheGroupe : ' + listPhoto[0].getAttribute('id'));
	
    for (i = 0; i < listPhoto.length; i++) {	
		//listPhoto[i].style.display = "inline";
		listPhoto[i].style.display = (listPhoto[i].getAttribute('id').indexOf('FRATRIES')>-1)?"none":"inline";
    }	
	listPhoto = document.getElementsByClassName('PlancheIndiv');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "none";
    }	
	listPhoto = document.getElementsByClassName('IndivSELECTION');
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
	listPhoto = document.getElementsByClassName('GroupeSELECTION');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "none";
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
	listPhoto = document.getElementsByClassName('PlancheIndiv');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline-block";
    }	
	listPhoto = document.getElementsByClassName('IndivSELECTION');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline-block";
    }	
	
	listPhoto = document.getElementsByClassName('PlancheGroupe');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline";
    }	
	listPhoto = document.getElementsByClassName('GroupeSELECTION');
    for (i = 0; i < listPhoto.length; i++) {	
		listPhoto[i].style.display = "inline";
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


function openNav() {
	AfficheRechercheCMD(true);	
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {  


	AfficheRechercheCMD(false);
	//InitCommandes();

}

function CopierCommandes(x) {
	//alert('lesCmdesLibres generales : ' + document.getElementById("lesCmdesLibres").value + ' lesFichiersBoutique generales : ' + document.getElementById("lesFichiersBoutique").value); 
	//alert('etzrte : '); 	
	x.querySelector("#ZlesPhotoSelection").value = document.getElementById("lesPhotoSelection").value;
	x.querySelector("#ZlesCmdesLibres").value = document.getElementById("lesCmdesLibres").value;
	x.querySelector("#ZlesFichiersBoutique").value = document.getElementById("lesFichiersBoutique").value;
	//x.setAttribute('name',  document.getElementById("lesFichiersBoutique").value);
	//alert('ZlesCmdesLibres : ' + x.querySelector("#ZlesCmdesLibres").value + ' ZlesFichiersBoutique : ' +  x.querySelector("#ZlesFichiersBoutique").value); 

}

function SelectionnerCliquePhoto(x) {
/* */
	var nbPhotos = parseInt(x.getAttribute('Nb'));

	if (nbPhotos < 1){
		CopierCommandes(x);
		if (!(x.getAttribute('id').indexOf('FRATRIES')>-1)) {
			SelectionSurPhoto(x);
		}
		openNav();
	}		

}

function SelectionSurPhoto(x) {
	var nbPhotos = parseInt(x.getAttribute('Nb'));
	if (nbPhotos == 0){
		x.setAttribute('Nb',  ' 1 ');
	}else{
		x.setAttribute('Nb',  ' 0 ');
	}		
	RemplacementClassSelection(x);
	MAJEnregistrementSelectionPhotos();
}

function RemplacementClassSelection(x) {
	if(x.classList.contains("PlancheIndiv")){x.classList.replace("PlancheIndiv", "IndivSELECTION");}
	else if(x.classList.contains("IndivSELECTION")){x.classList.replace("IndivSELECTION", "PlancheIndiv");}
	else if(x.classList.contains("PlancheGroupe")){x.classList.replace("PlancheGroupe", "GroupeSELECTION");
	}else if(x.classList.contains("GroupeSELECTION")){x.classList.replace("GroupeSELECTION", "PlancheGroupe");}  	
}


function SelectionnerCommandesAffiche() {
	var ToutSelectionner = (document.getElementById("CaseSelectionnerCommandesAffiche").className == 'caseCheckVide');
	if (ToutSelectionner){
		// INDIV  
		var mesPlanches = document.getElementsByClassName("PlancheIndiv");
		while(mesPlanches.length >0){
			if ( mesPlanches[0].offsetHeight > 0 ) {
				SelectionSurPhoto(mesPlanches[0]);
			}
			else{
				mesPlanches[0].classList.replace("PlancheIndiv", "plancheNONVisibleIndiv");
			}			
		}	
		var mesPlanches = document.getElementsByClassName("plancheNONVisibleIndiv");
		while(mesPlanches.length > 0){
			mesPlanches[0].classList.replace("plancheNONVisibleIndiv", "PlancheIndiv");
		}
		// GROUPE
		var mesPlanches = document.getElementsByClassName("PlancheGroupe");
		while(mesPlanches.length > 0){
			if ( mesPlanches[0].offsetHeight > 0 ) {
				if (!(mesPlanches[0].getAttribute('id').indexOf('FRATRIES')>-1)) {
					SelectionSurPhoto(mesPlanches[0]);
				}else{
					mesPlanches[0].classList.replace("PlancheGroupe", "plancheNONVisibleGroupe");
				}

			}	
			else{
				mesPlanches[0].classList.replace("PlancheGroupe", "plancheNONVisibleGroupe");
			}			
		}
		var mesPlanches = document.getElementsByClassName("plancheNONVisibleGroupe");
		while(mesPlanches.length > 0){
			mesPlanches[0].classList.replace("plancheNONVisibleGroupe", "PlancheGroupe");
		}		
		openNav();
	}else{
		// INDIV
		var mesPlanches = document.getElementsByClassName("IndivSELECTION");
		while(mesPlanches.length >0){
			mesPlanches[0].classList.replace("IndivSELECTION", "PlancheIndiv");	
		}
		// GROUPE
		var mesPlanches = document.getElementsByClassName("GroupeSELECTION");
		while(mesPlanches.length >0){
			//mesPlanches[0].classList.replace("GroupeSELECTION", "PlancheGroupe");	
			SelectionSurPhoto(mesPlanches[0]);
		}		
	}
	document.getElementById("CaseSelectionnerCommandesAffiche").className = (ToutSelectionner?'caseCheckCoche':'caseCheckVide');
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

function MAJEnregistrementSelectionPhotos() {
	//alert('qsd');
	var mesRecoInfo ='';
	var mesPlanches = document.getElementsByClassName("GroupeSELECTION");
	//console.log(' Nombre Page : ' + BoutonPage.length );	
	for (i = 0; i < mesPlanches.length; i++) {
		mesRecoInfo = mesRecoInfo  + mesPlanches[i].getAttribute('id') + '____'
								+ mesPlanches[i].getAttribute('Nb').trim() + sepFinLigne;
	}	
	var mesPlanches = document.getElementsByClassName("IndivSELECTION");
	//console.log(' Nombre Page : ' + BoutonPage.length );	
	for (i = 0; i < mesPlanches.length; i++) {
	  mesRecoInfo = mesRecoInfo  + mesPlanches[i].getAttribute('id') + '____'
	 							 + mesPlanches[i].getAttribute('Nb').trim() + sepFinLigne;
	}	

	document.getElementById('lesPhotoSelection').value =  mesRecoInfo;

	var maListePhotos = document.getElementById("myListePhoto");
	maListePhotos.innerHTML =  AfficherCMDparLigne(mesRecoInfo);

	document.getElementById("btnAjouterTirages").disabled = (maListePhotos.innerHTML === "");

	MAJAffichageSelectionPhotos();
}	

function MAJAffichageSelectionPhotos(chargement = false) {
	var TableauSelectionPhotos = document.getElementById('lesPhotoSelection').value.split(sepFinLigne);
	//alert('TableauSelectionPhotos ' + TableauSelectionPhotos);
	for (i = 0; i < TableauSelectionPhotos.length  ; i++) {
		//alert('TableauSelectionPhotos : "'  + TableauSelectionPhotos[i] + '"');	
		if(TableauSelectionPhotos[i].trim()!=''){
			var CMDSelectionPhoto = TableauSelectionPhotos[i].split('____');
			var maPhoto = document.getElementById(CMDSelectionPhoto[0]);
			if (chargement) {
				RemplacementClassSelection(maPhoto);
				maPhoto.setAttribute('Nb', CMDSelectionPhoto[1].trim());
			}
			maPhoto.getElementsByClassName("NombrePhoto")[0].textContent = CMDSelectionPhoto[1];
		}
	}
	var TableauFichierBoutique = document.getElementById('lesFichiersBoutique').value.split(sepFinLigne);
	//alert('TableauFichierBoutique ' + TableauFichierBoutique);
	for (i = 0; i < TableauFichierBoutique.length  ; i++) {
		if(TableauFichierBoutique[i].trim()!=''){
			var maPhoto = document.getElementById(TableauFichierBoutique[i]);
			//alert('ImageFichierWeb : "'  + maPhoto.getElementsByClassName("ImageFichierWeb")[0].textContent + '"');	
			maPhoto.getElementsByClassName("ImageFichierWeb")[0].style.display = "inline-block";			
		}
	}	
}

function AjoutFichierBoutique(element) {
	//alert('xcvxcvxcv.id : ' + element.getAttribute('id')); 
	var leFichier = element.getAttribute('id');
	var cmdfichierBoutique = document.getElementById("lesFichiersBoutique").value;
	//var AfffichierBoutique = document.getElementById("myListeFichiersBoutique").innerHTML;
	/*	*/		
	if(cmdfichierBoutique.indexOf(leFichier)>-1){//On l'enleve		
		document.getElementById("lesFichiersBoutique").value = cmdfichierBoutique.replaceAll(leFichier + sepFinLigne, '');
		element.getElementsByClassName("ImageFichierWeb")[0].style.display = "none";
		//document.getElementById("myListeFichiersBoutique").innerHTML = AfffichierBoutique.replaceAll(leFichier + '<br>', '');
	}else{//On l'ajoute
		document.getElementById("lesFichiersBoutique").value = cmdfichierBoutique + leFichier + sepFinLigne;
		//document.getElementById("myListeFichiersBoutique").innerHTML = AfffichierBoutique +  leFichier + '<br>';	
		element.getElementsByClassName("ImageFichierWeb")[0].style.display = "inline-block";
	
	}
	var maListeFichiersBoutique = document.getElementById("myListeFichiersBoutique");
	maListeFichiersBoutique.innerHTML = AfficherCMDparLigne( document.getElementById("lesFichiersBoutique").value); 
	
	document.getElementById("btnFichiersBoutique").disabled = (maListeFichiersBoutique.innerHTML === "");


}

function AfficherCMDparLigne(str) {
	str = str.trim();
	return str.replaceAll(sepFinLigne, '<br>');
}
/* 
function MAJCommandes() {
	document.getElementById("myListeCommandes").innerHTML = 'jghkjhhj'; //AfficherCMDUnderscore(document.getElementById("lesCmdesLibres").value); 
	document.getElementById("myListeFichiersBoutique").innerHTML = 'jghkjhhj'; //AfficherCMDUnderscore(document.getElementById("lesFichiersBoutique").value); 
}
*/
function NbPlancheMOINS(element) {
	//alert('NbPlancheMOINS');
	var nombre = parseInt( element.parentElement.parentElement.getAttribute('Nb'));
	nombre = nombre -1;
	
	//if (nombre < 1) {SelectionSurPhoto(element.parentElement.parentElement);}
	if (nombre < 1) {RemplacementClassSelection(element.parentElement.parentElement);}

	

	element.parentElement.parentElement.setAttribute('Nb',  ' ' + nombre +  ' ');
	MAJEnregistrementSelectionPhotos();
	//alert('Moins : ' + element.parentElement.parentElement.getAttribute('id') +  '  x' + element.parentElement.parentElement.getAttribute('Nb')); 

}
function NbPlanchePLUS(element) {
	//alert('NbPlanchePLUS');
	var nombre = parseInt( element.parentElement.parentElement.getAttribute('Nb'));
	nombre = nombre + 1;
	element.parentElement.parentElement.setAttribute('Nb', ' ' + nombre +  ' ');	
	MAJEnregistrementSelectionPhotos();
	//alert('PLUS : ' + element.parentElement.parentElement.getAttribute('id') +  '  x' + element.parentElement.parentElement.getAttribute('Nb')); 
}

function TransfererCMD() {
	var TableauSelectionPhotos = document.getElementById('lesPhotoSelection').value.split(sepFinLigne);
	if (TableauSelectionPhotos.length > 1 ) {
		//alert('TableauSelectionPhotos ' + TableauSelectionPhotos);
		for (i = 0; i < TableauSelectionPhotos.length  ; i++) {
			
			if(TableauSelectionPhotos[i].trim()!=''){
				var CMDSelectionPhoto = TableauSelectionPhotos[i].split('____');
				var maPhoto = document.getElementById(CMDSelectionPhoto[0]);
				RemplacementClassSelection(maPhoto);
				maPhoto.setAttribute('Nb', '0');
			}
		}
		document.getElementById('lesPhotoSelection').value =  '';
		var maListePhotos = document.getElementById("myListePhoto").innerHTML;
		var LeProduitSelection = document.getElementById('SelectProduit').innerHTML;
				
		// '&#60;' et '&#62;' pour remplacer les '<' et '>'
		document.getElementById("myListeCommandes").innerHTML += '&#60;' + LeProduitSelection + '&#62;' + '<br>' 
														+ RecupPhotosProduits(maListePhotos, LeProduitSelection);																		
		document.getElementById('lesCmdesLibres').value += '<' + LeProduitSelection + '>' + sepFinLigne
														+ CMDPhotosProduits(maListePhotos, LeProduitSelection);
														
		document.getElementById("btnCmdesLibres").disabled = (document.getElementById("myListeCommandes").innerHTML === "");	
		
		/*		

var strProduit = this.TableauLignes[i].substring(0,this.TableauLignes[i].indexOf(sepNumLigne));
					var pos = strProduit.lastIndexOf('_');
					var nbProduitAFaire = parseInt(strProduit.substring(pos+1));

					alert( '444 Init Liste Planches : ' + strProduit);
					
					strProduit = strProduit.substring(0, pos+1) + '1';





		document.getElementById('lesCmdesLibres').value = document.getElementById('lesCmdesLibres').value							
												+ '<Voir a quoi correspond ceci ... %20 cm>'+ sepFinLigne;									
		document.getElementById('lesCmdesLibres').value = document.getElementById('lesCmdesLibres').value
												+ CMDPhotosProduits(document.getElementById('lesPhotoSelection').value, 
																	document.getElementById('SelectProduit').innerHTML);

		document.getElementById("myListeCommandes").innerHTML =  document.getElementById("myListeCommandes").innerHTML				
		+ '&#60;' + document.getElementById("SelectProduit").innerHTML + '&#62;' + '<br>';	// '&#60;' et '&#62;' pour remplacer les '<' et '>'
		document.getElementById("myListeCommandes").innerHTML =  document.getElementById("myListeCommandes").innerHTML
																+ RecupPhotosProduits(document.getElementById("myListePhoto").innerHTML,
																		document.getElementById('SelectProduit').innerHTML);
				
		document.getElementById('lesCmdesLibres').value = document.getElementById('lesCmdesLibres').value
										+ '<' + document.getElementById("SelectProduit").innerHTML + '>' + sepFinLigne;
		document.getElementById('lesCmdesLibres').value = document.getElementById('lesCmdesLibres').value 
										+ RecupPhotosProduits(document.getElementById("myListePhoto").innerHTML,
										document.getElementById('SelectProduit').innerHTML);
*/
	}
}

function CMDPhotosProduits(ListedePhoto, LeProduitSelection) {
	
	ListedePhoto = RecupPhotosProduits(ListedePhoto, LeProduitSelection);
	return ListedePhoto.replaceAll('<br>', sepFinLigne );

}

function RecupPhotosProduits(ListedePhoto, LeProduitSelection) {

	return ListedePhoto.replaceAll('____', '_' + CodeProduit(LeProduitSelection) + '_');
}

function CodeProduit(Produits) {
	Produits =  '20x20cm__';


	return Produits;
}

function SelectionProduit() {
	document.getElementById("myDropdown").classList.toggle("show");
}
  
function filterProduits() {
	var input, filter, ul, li, a, i;
	input = document.getElementById("ZoneSaisie");
	filter = input.value.toUpperCase();
	div = document.getElementById("myDropdown");
	a = div.getElementsByTagName("a");
	for (i = 0; i < a.length; i++) {
		txtValue = a[i].textContent || a[i].innerText;
		if (txtValue.toUpperCase().indexOf(filter) > -1) {
		a[i].style.display = "";
		} else {
		a[i].style.display = "none";
		}
	}
}