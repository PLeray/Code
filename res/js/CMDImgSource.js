var sepFinLigne = '§';

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

/*var rech = document.getElementById("zoneAffichagePhotoEcole");
rech.style.display = 'none';
AfficheRechercheCMD(false);*/

//initPagination();

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
		
		//document.getElementById("myListeCommandes").innerHTML = 'kjhkjh';	
	}
	else{
		//alert('AfficheRechercheCMD()! : ' + isAffiche);	

		document.getElementById("mySidenav").style.width = "50px";
		  //document.getElementById("mySidenav").style.z-index = -2;  
		document.getElementById("main").style.marginRight = "50px";
		document.getElementById("Entete").style.marginRight = "50px";

		//alert('AfficheRechercheCMD()! : ' + isAffiche);			
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


function openNav() {
	AfficheRechercheCMD(true);	
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {  


	AfficheRechercheCMD(false);
	//InitCommandes();

}

function CopierCommandes(x) {
	//alert('lesCommandes generales : ' + document.getElementById("lesCommandes").value + ' lesFichiersBoutique generales : ' + document.getElementById("lesFichiersBoutique").value); 
	//alert('etzrte : '); 	
	x.querySelector("#ZlesPhotoSelection").value = document.getElementById("lesPhotoSelection").value;
	x.querySelector("#ZlesCommandes").value = document.getElementById("lesCommandes").value;
	x.querySelector("#ZlesFichiersBoutique").value = document.getElementById("lesFichiersBoutique").value;
	//x.setAttribute('name',  document.getElementById("lesFichiersBoutique").value);
	//alert('ZlesCommandes : ' + x.querySelector("#ZlesCommandes").value + ' ZlesFichiersBoutique : ' +  x.querySelector("#ZlesFichiersBoutique").value); 

}

function SelectionPhoto(x) {
/* */	
	if (parseInt(x.getAttribute('Nb')) == 0){
		x.setAttribute('Nb',  ' 1 ');
	}else{
		x.setAttribute('Nb',  ' 0 ');
	}
	RemplacementClassSelection(x);

/** 
	if(x.classList.contains("PlancheIndiv")){x.classList.replace("PlancheIndiv", "IndivSELECTION");}
	else if(x.classList.contains("IndivSELECTION")){x.classList.replace("IndivSELECTION", "PlancheIndiv");}
	else if(x.classList.contains("PlancheGroupe")){x.classList.replace("PlancheGroupe", "GroupeSELECTION");
	}else if(x.classList.contains("GroupeSELECTION")){x.classList.replace("GroupeSELECTION", "PlancheGroupe");}  	
	*/
	MAJEnregistrementSelectionPhotos();
}

function RemplacementClassSelection(x) {
	if(x.classList.contains("PlancheIndiv")){x.classList.replace("PlancheIndiv", "IndivSELECTION");}
	else if(x.classList.contains("IndivSELECTION")){x.classList.replace("IndivSELECTION", "PlancheIndiv");}
	else if(x.classList.contains("PlancheGroupe")){x.classList.replace("PlancheGroupe", "GroupeSELECTION");
	}else if(x.classList.contains("GroupeSELECTION")){x.classList.replace("GroupeSELECTION", "PlancheGroupe");}  	
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
								+ mesPlanches[i].getAttribute('Nb') + sepFinLigne;
	}	
	var mesPlanches = document.getElementsByClassName("IndivSELECTION");
	//console.log(' Nombre Page : ' + BoutonPage.length );	
	for (i = 0; i < mesPlanches.length; i++) {
	  mesRecoInfo = mesRecoInfo  + mesPlanches[i].getAttribute('id') + '____'
	 							 + mesPlanches[i].getAttribute('Nb') + sepFinLigne;
	}	

	document.getElementById('lesPhotoSelection').value =  mesRecoInfo;
	document.getElementById("myListePhoto").innerHTML =  AfficherCMDparLigne(mesRecoInfo);

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
	document.getElementById("myListeFichiersBoutique").innerHTML = AfficherCMDparLigne( document.getElementById("lesFichiersBoutique").value); 
	//MAJAffichageSelectionPhotos();
}

function AfficherCMDparLigne(str) {
	str = str.trim();
	return str.replaceAll(sepFinLigne, '<br>');
}
/* 
function MAJCommandes() {
	document.getElementById("myListeCommandes").innerHTML = 'jghkjhhj'; //AfficherCMDUnderscore(document.getElementById("lesCommandes").value); 
	document.getElementById("myListeFichiersBoutique").innerHTML = 'jghkjhhj'; //AfficherCMDUnderscore(document.getElementById("lesFichiersBoutique").value); 
}
*/
function NbPlancheMOINS(element) {
	var nombre = parseInt( element.parentElement.parentElement.getAttribute('Nb'));
	nombre = nombre -1;
	element.parentElement.parentElement.setAttribute('Nb',  ' ' + nombre +  ' ');
	if (nombre < 1) {SelectionPhoto(element.parentElement.parentElement);}
	MAJEnregistrementSelectionPhotos();
	//alert('Moins : ' + element.parentElement.parentElement.getAttribute('id') +  '  x' + element.parentElement.parentElement.getAttribute('Nb')); 

}
function NbPlanchePLUS(element) {
	var nombre = parseInt( element.parentElement.parentElement.getAttribute('Nb'));
	nombre = nombre + 1;
	element.parentElement.parentElement.setAttribute('Nb', ' ' + nombre +  ' ');	
	MAJEnregistrementSelectionPhotos();
	//alert('PLUS : ' + element.parentElement.parentElement.getAttribute('id') +  '  x' + element.parentElement.parentElement.getAttribute('Nb')); 
}

function TransfererCMD() {
	var TableauSelectionPhotos = document.getElementById('lesPhotoSelection').value.split(sepFinLigne);
	//alert('TableauSelectionPhotos ' + TableauSelectionPhotos);
	for (i = 0; i < TableauSelectionPhotos.length  ; i++) {
		
		if(TableauSelectionPhotos[i].trim()!=''){
			var CMDSelectionPhoto = TableauSelectionPhotos[i].split('____');
			var maPhoto = document.getElementById(CMDSelectionPhoto[0]);
			RemplacementClassSelection(maPhoto);
			maPhoto.setAttribute('Nb', '0');
		}
	}
	document.getElementById('lesCommandes').value = document.getElementById('lesCommandes').value							
											+ '<xx%20 cm>'+ sepFinLigne;									
	document.getElementById('lesCommandes').value = document.getElementById('lesCommandes').value
											 + CMDPhotosProduits(document.getElementById('lesPhotoSelection').value, 
											 					document.getElementById('SelectProduit').innerHTML);
	document.getElementById('lesPhotoSelection').value =  '';
	document.getElementById("myListeCommandes").innerHTML =  document.getElementById("myListeCommandes").innerHTML				
	+ '&#60;' + document.getElementById("SelectProduit").innerHTML + '&#62;' + '<br>';	// '&#60;' et '&#62;' pour remplacer les '<' et '>'
	document.getElementById("myListeCommandes").innerHTML =  document.getElementById("myListeCommandes").innerHTML
															+ AFFPhotosProduits(document.getElementById("myListePhoto").innerHTML,
																	 document.getElementById('SelectProduit').innerHTML);															
	document.getElementById("myListePhoto").innerHTML =  '';
}

function CMDPhotosProduits(PhotoNombre, Produits) {

	return PhotoNombre.replaceAll('____', '_' + Produits + '_');

}

function AFFPhotosProduits(PhotoNombre, Produits) {

	return PhotoNombre.replaceAll('____', '_' + CodeProduit(Produits) + '_');
}

function CodeProduit(Produits) {
	return '20x20cm__';
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