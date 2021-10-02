var xMousePosition = 0;
var yMousePosition = 0;
document.onmousemove = function(e)
{
	xMousePosition = e.clientX + window.pageXOffset;
	yMousePosition = e.clientY + window.pageYOffset;
};
 
 
function Voir(element)
{
  window.location.href= 'CMDAffichePlanche.php' + element.getAttribute('paramLien');
}
 
function FichierWeb(element)
{
  AjoutFichierBoutique(element);
  //alert('xcvxcvxcv.id : ' + element.getAttribute('id'));   
}  

function Enregistrer(element)
{
  alert('xcvxcvxcv.id : ' + element.getAttribute('paramLien'));    
}  
 
function monMenuContextuel(element)
{
	var x = document.getElementById('ctxmenu1');
	if(x) x.parentNode.removeChild(x);

	var d = document.createElement('div');
	d.setAttribute('class', 'ctxmenu');
	d.setAttribute('id', 'ctxmenu1');
	element.parentNode.appendChild(d);
	d.style.left = xMousePosition + "px";
	d.style.top = yMousePosition + "px"; 
	d.onmouseover = function(e) { this.style.cursor = 'pointer'; } 
	d.onclick = function(e) { element.parentNode.removeChild(d);  }
	document.body.onclick = function(e) { element.parentNode.removeChild(d);  }

	var p = document.createElement('p');
	d.appendChild(p);
	p.onclick=function() { Voir(element) };
	p.setAttribute('class', 'ctxline');
	p.innerHTML = "Voir l'image en grand";

	var p2 = document.createElement('p');
	d.appendChild(p2);
	p2.onclick=function() { FichierWeb(element) };  
	p2.setAttribute('class', 'ctxline');
	var verbe = (document.getElementById("lesFichierBoutique").value.indexOf(element.getAttribute('id'))>-1)?"Annuler ":"Créer ";
	p2.innerHTML = verbe + "fichiers pour boutique web"; 
	
	
	
	
	
	var p3 = document.createElement('p');
	d.appendChild(p3);
	p3.onclick=function() { Enregistrer(element) };  
	p3.setAttribute('class', 'ctxline');
	p3.innerHTML = "Enregistrer photo en jpg"; 	

	return false;
}

function SauvegardeCanvas()
{
    canvas_sauvegarde = canvas.toDataURL(); // Récupération du canvas
    canvas_sauvegarde = canvas_sauvegarde.replace("image/png", "image/octet-stream");
    document.location.href = canvas_sauvegarde;
    //window.location = canvas_sauvegarde; // Redirection vers l'image au format PNG
}

