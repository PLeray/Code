
###global Modernizr:true ###
'use strict'

(($) ->
  $.fn.extend
    mgPgnation: (options) ->
      $this = $(this)

      if $this.length
        $mainNav = @children()
        $pgnNav = $this.find('.pgn__item')
        $curNav = $this.find('.current')
        $magicNav = $this.find('a')
        $prevNav = $this.find('.prev')
        $nextNav = $this.find('.next')
        $prevNavText = $prevNav.find('.pgn__prev-txt')

        ### func :: update prev text ###
        updatePrevText = ->
          $prevNavText = $prevNav.find('.pgn__prev-txt')
          $prevNavText.html 'Prev'

        ### func :: calculate width of each page num ###
        calPgnWidth = ->
          # number of visible <a> plus <strong class="current">
          vsbNav = $this.find('.pgn__item a:visible').length + 1
          vsbNavs = vsbNav + 2
          prevWidth = 100 / vsbNavs
          pgnWidth = 100 - prevWidth * 2
          $prevNav.css 'width', prevWidth + '%'
          $nextNav.css 'width', prevWidth + '%'
          $pgnNav.css 'width', pgnWidth + '%'
          # <a> and <strong>
          $pgnNav.find('a, strong').css 'width', 100 / vsbNav + '%'

        ### func :: calculate and display prev/next ###
        # 85px - display full text
        showPrevNext = ->
          prevNavWidth = $prevNav.innerWidth()

          if prevNavWidth > 100
            $this.addClass 'fullprevnext'

            # display Previous
            $prevNavText.html 'Previous'
          else if prevNavWidth < 101 and prevNavWidth > 60
            $this.addClass 'fullprevnext'

            # display Prev
            $prevNavText.html 'Prev'
          else
            $this.removeClass 'fullprevnext'

        ### func :: draw magic line ###
        magicDraw = ->
          # draw init magic line
          $magicLine.width($curNav.width())
          if $curNav.position() != undefined
            $magicLine.css 'left', $curNav.position().left
          
          # assign orig values
          $magicLine.data 'origLeft', $magicLine.position().left
          $magicLine.data 'origWidth', $magicLine.width()
        # END funcs
        
        # create magic line
        $mainNav.append('<li class="pgn__magic-line">')
        
        # declare magic line
        $magicLine = $this.find('.pgn__magic-line')

        # add extra class & element if no prev or next
        prevNavWidth = $prevNav.innerWidth()

        if prevNavWidth > 100
          prevText = 'Previous'
        else
          prevText = 'Prev'

        if !$prevNav.children().length
          $prevNav.addClass 'disabled'
          $prevNav.append '<a rel="prev"><i class="pgn__prev-icon icon-angle-left"></i><span class="pgn__prev-txt">' + prevText + '</span></a>'

        if !$nextNav.children().length
          $nextNav.addClass 'disabled'
          $nextNav.append '<a rel="next"><i class="pgn__next-icon icon-angle-right"></i><span class="pgn__next-txt">Next</span></a>'

        # calculate pgn width
        calPgnWidth()

        # show prev/next
        showPrevNext()

        # draw magic line
        magicDraw()
        
        # when hover
        $magicNav.hover (->
          $el = $(this)
          leftPos = $el.position().left
          newWidth = $el.width()
          
          # animate magic line
          $magicLine.stop().animate
            left: leftPos
            width: newWidth
        ), ->
          $magicLine.stop().animate
            left: $magicLine.data('origLeft')
            width: $magicLine.data('origWidth')
      
        ### Window Resize Changes ###
        window.addEventListener 'resize', ->
          updatePrevText()
          calPgnWidth()
          showPrevNext()
          magicDraw()
  # END mgPgnation()
      
  # call function here 
  $('.pgn').mgPgnation()

) jQuery

/***********************************************************************************/
/********************************/
window.onload = function (){ 
alert('Onload :!!');


InitCommandes();



};
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

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


