alert('TEST2'); 


function VisuErreur(elementId) {
  /* 
  alert('ID ' + elementId);
  
  */
  cmd = document.getElementsByClassName('ContenufichierErreur');
  
  for (i = 0; i < cmd.length; i++) {
    if ((cmd[i].id == elementId)&&(cmd[i].style.display == 'none')){	
      //alert('elementId ' + elementId);
        cmd[i].style.display = 'block';
        setCookie(cmd[i].id, 'affiche', 30);
    }else{
      cmd[i].style.display = "none";
      setCookie(cmd[i].id, 'cache', 30);      
    }
  }	  
}

/*
	var ele = document.getElementById(elementId);
	if(ele.style.display == "inline-block") {
    ele.style.display = "block";	
    setCookie(elementId, 'affiche', 30);
	}*/

/*
function VisuErreur(elementId) {
	var ele = document.getElementById(elementId);
	

	if(ele.style.display == "inline-block") {
    alert('ID ' + elementId);
		ele.style.display = "none";
    setCookie(elementId, 'cache', 30);
	}
	else {
		ele.style.display = "block";
    setCookie(elementId, 'affiche', 30);	
	}
} 

 */

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


function InitAfficheErreur() {
  var cmd, i;	
  cmd = document.getElementsByClassName('ContenufichierErreur');
  for (i = 0; i < cmd.length; i++) {
    cmd[i].style.display = 'none';
    if (getCookie(cmd[i].id)=='affiche'){	cmd[i].style.display = 'block';	}
  }
}	



/**/ 
function myFunction() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("commandes");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
  /*table = document.getElementById("myTableWEB");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  } */ 
}
