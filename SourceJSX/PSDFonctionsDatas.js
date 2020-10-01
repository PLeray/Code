////////////////////////////// LES FONCTIONS OUTILS //////////////////////////////////////////////
//#include PSDCode.js
#include PSDConnexionAPI.js

//var g_IsDebug = true;
var sepNumLigne = '§';
var sepRetourLigne = 'RCSL';
var g_IsFratrie = false;

var g_TypeGROUPE = [
'PANO', 
'TRAD', 
'CUBE', 
'RUCH', 
'CADR',
];

var g_PdtGROUPE = [
'PANO', 
'CAL-PANO', 
'COMPO-PANO',
'TRAD', 
'CAL-TRAD',
'COMPO-TRAD',
'DUO-TRAD',
'CUBE', 
'CAL-CUBE',
'RUCH', 
'CAL-RUCH',
];

var g_PdtSANS_NB = [
'COMPOSITE', 
'MP-SIX-NEUF', 
'DUO-DIX-QUINZE',
];

var g_PdtNotLABO = [
'CADRE-PANO',
'TAPIS-SOURIS',
'XXX'
];

var g_MinimuNomClasse = 11;
var g_ProfondeurMAX = 3;
//var g_ProfondeurRech = 0;


var hexcase=0;function hex_md5(a){return rstr2hex(rstr_md5(str2rstr_utf8(a)))}function hex_hmac_md5(a,b){return rstr2hex(rstr_hmac_md5(str2rstr_utf8(a),str2rstr_utf8(b)))}function md5_vm_test(){return hex_md5("abc").toLowerCase()=="900150983cd24fb0d6963f7d28e17f72"}function rstr_md5(a){return binl2rstr(binl_md5(rstr2binl(a),a.length*8))}function rstr_hmac_md5(c,f){var e=rstr2binl(c);if(e.length>16){e=binl_md5(e,c.length*8)}var a=Array(16),d=Array(16);for(var b=0;b<16;b++){a[b]=e[b]^909522486;d[b]=e[b]^1549556828}var g=binl_md5(a.concat(rstr2binl(f)),512+f.length*8);return binl2rstr(binl_md5(d.concat(g),512+128))}function rstr2hex(c){try{hexcase}catch(g){hexcase=0}var f=hexcase?"0123456789ABCDEF":"0123456789abcdef";var b="";var a;for(var d=0;d<c.length;d++){a=c.charCodeAt(d);b+=f.charAt((a>>>4)&15)+f.charAt(a&15)}return b}function str2rstr_utf8(c){var b="";var d=-1;var a,e;while(++d<c.length){a=c.charCodeAt(d);e=d+1<c.length?c.charCodeAt(d+1):0;if(55296<=a&&a<=56319&&56320<=e&&e<=57343){a=65536+((a&1023)<<10)+(e&1023);d++}if(a<=127){b+=String.fromCharCode(a)}else{if(a<=2047){b+=String.fromCharCode(192|((a>>>6)&31),128|(a&63))}else{if(a<=65535){b+=String.fromCharCode(224|((a>>>12)&15),128|((a>>>6)&63),128|(a&63))}else{if(a<=2097151){b+=String.fromCharCode(240|((a>>>18)&7),128|((a>>>12)&63),128|((a>>>6)&63),128|(a&63))}}}}}return b}function rstr2binl(b){var a=Array(b.length>>2);for(var c=0;c<a.length;c++){a[c]=0}for(var c=0;c<b.length*8;c+=8){a[c>>5]|=(b.charCodeAt(c/8)&255)<<(c%32)}return a}function binl2rstr(b){var a="";for(var c=0;c<b.length*32;c+=8){a+=String.fromCharCode((b[c>>5]>>>(c%32))&255)}return a}function binl_md5(p,k){p[k>>5]|=128<<((k)%32);p[(((k+64)>>>9)<<4)+14]=k;var o=1732584193;var n=-271733879;var m=-1732584194;var l=271733878;for(var g=0;g<p.length;g+=16){var j=o;var h=n;var f=m;var e=l;o=md5_ff(o,n,m,l,p[g+0],7,-680876936);l=md5_ff(l,o,n,m,p[g+1],12,-389564586);m=md5_ff(m,l,o,n,p[g+2],17,606105819);n=md5_ff(n,m,l,o,p[g+3],22,-1044525330);o=md5_ff(o,n,m,l,p[g+4],7,-176418897);l=md5_ff(l,o,n,m,p[g+5],12,1200080426);m=md5_ff(m,l,o,n,p[g+6],17,-1473231341);n=md5_ff(n,m,l,o,p[g+7],22,-45705983);o=md5_ff(o,n,m,l,p[g+8],7,1770035416);l=md5_ff(l,o,n,m,p[g+9],12,-1958414417);m=md5_ff(m,l,o,n,p[g+10],17,-42063);n=md5_ff(n,m,l,o,p[g+11],22,-1990404162);o=md5_ff(o,n,m,l,p[g+12],7,1804603682);l=md5_ff(l,o,n,m,p[g+13],12,-40341101);m=md5_ff(m,l,o,n,p[g+14],17,-1502002290);n=md5_ff(n,m,l,o,p[g+15],22,1236535329);o=md5_gg(o,n,m,l,p[g+1],5,-165796510);l=md5_gg(l,o,n,m,p[g+6],9,-1069501632);m=md5_gg(m,l,o,n,p[g+11],14,643717713);n=md5_gg(n,m,l,o,p[g+0],20,-373897302);o=md5_gg(o,n,m,l,p[g+5],5,-701558691);l=md5_gg(l,o,n,m,p[g+10],9,38016083);m=md5_gg(m,l,o,n,p[g+15],14,-660478335);n=md5_gg(n,m,l,o,p[g+4],20,-405537848);o=md5_gg(o,n,m,l,p[g+9],5,568446438);l=md5_gg(l,o,n,m,p[g+14],9,-1019803690);m=md5_gg(m,l,o,n,p[g+3],14,-187363961);n=md5_gg(n,m,l,o,p[g+8],20,1163531501);o=md5_gg(o,n,m,l,p[g+13],5,-1444681467);l=md5_gg(l,o,n,m,p[g+2],9,-51403784);m=md5_gg(m,l,o,n,p[g+7],14,1735328473);n=md5_gg(n,m,l,o,p[g+12],20,-1926607734);o=md5_hh(o,n,m,l,p[g+5],4,-378558);l=md5_hh(l,o,n,m,p[g+8],11,-2022574463);m=md5_hh(m,l,o,n,p[g+11],16,1839030562);n=md5_hh(n,m,l,o,p[g+14],23,-35309556);o=md5_hh(o,n,m,l,p[g+1],4,-1530992060);l=md5_hh(l,o,n,m,p[g+4],11,1272893353);m=md5_hh(m,l,o,n,p[g+7],16,-155497632);n=md5_hh(n,m,l,o,p[g+10],23,-1094730640);o=md5_hh(o,n,m,l,p[g+13],4,681279174);l=md5_hh(l,o,n,m,p[g+0],11,-358537222);m=md5_hh(m,l,o,n,p[g+3],16,-722521979);n=md5_hh(n,m,l,o,p[g+6],23,76029189);o=md5_hh(o,n,m,l,p[g+9],4,-640364487);l=md5_hh(l,o,n,m,p[g+12],11,-421815835);m=md5_hh(m,l,o,n,p[g+15],16,530742520);n=md5_hh(n,m,l,o,p[g+2],23,-995338651);o=md5_ii(o,n,m,l,p[g+0],6,-198630844);l=md5_ii(l,o,n,m,p[g+7],10,1126891415);m=md5_ii(m,l,o,n,p[g+14],15,-1416354905);n=md5_ii(n,m,l,o,p[g+5],21,-57434055);o=md5_ii(o,n,m,l,p[g+12],6,1700485571);l=md5_ii(l,o,n,m,p[g+3],10,-1894986606);m=md5_ii(m,l,o,n,p[g+10],15,-1051523);n=md5_ii(n,m,l,o,p[g+1],21,-2054922799);o=md5_ii(o,n,m,l,p[g+8],6,1873313359);l=md5_ii(l,o,n,m,p[g+15],10,-30611744);m=md5_ii(m,l,o,n,p[g+6],15,-1560198380);n=md5_ii(n,m,l,o,p[g+13],21,1309151649);o=md5_ii(o,n,m,l,p[g+4],6,-145523070);l=md5_ii(l,o,n,m,p[g+11],10,-1120210379);m=md5_ii(m,l,o,n,p[g+2],15,718787259);n=md5_ii(n,m,l,o,p[g+9],21,-343485551);o=safe_add(o,j);n=safe_add(n,h);m=safe_add(m,f);l=safe_add(l,e)}return Array(o,n,m,l)}function md5_cmn(h,e,d,c,g,f){return safe_add(bit_rol(safe_add(safe_add(e,h),safe_add(c,f)),g),d)}function md5_ff(g,f,k,j,e,i,h){return md5_cmn((f&k)|((~f)&j),g,f,e,i,h)}function md5_gg(g,f,k,j,e,i,h){return md5_cmn((f&j)|(k&(~j)),g,f,e,i,h)}function md5_hh(g,f,k,j,e,i,h){return md5_cmn(f^k^j,g,f,e,i,h)}function md5_ii(g,f,k,j,e,i,h){return md5_cmn(k^(f|(~j)),g,f,e,i,h)}function safe_add(a,d){var c=(a&65535)+(d&65535);var b=(a>>16)+(d>>16)+(c>>16);return(b<<16)|(c&65535)}function bit_rol(a,b){return(a<<b)|(a>>>(32-b))};

function PhotoClient(CodePhotos) {
// <Taille>_<(Nom) Numéro de la photo_< Produit>_<Teinte>_<Nombre>ex//.jpg
	this.Code = CodePhotos;
	this.ListePhotos = this.Code.split('_');
	//this.FichierPhoto = this.ListePhotos.length;
	this.NbPhotos = this.ListePhotos.length;

}

function PhotosDeClasse(CodeLigne) {
//this.Code = CodeLigne;
	this.TabGroupe = this.CodeLigne.split('_');
	if ( thefilename.indexOf('TRAD') > -1) { // c'est un groupe TRAD
		leGroupe = thefilename.substr(0,thefilename.indexOf('TRAD')-1);
	}
	if  (thefilename.indexOf('PANO') > -1) { // c'est un groupe PANO
		leGroupe = thefilename.substr(0,thefilename.indexOf('PANO')-1);
	}
	if  (thefilename.indexOf('CUBE') > -1) { // c'est un groupe CUBE
		leGroupe = thefilename.substr(0,thefilename.indexOf('CUBE')-1);
	}	
	if  (thefilename.indexOf('RUCH') > -1) { // c'est un groupe RUCHE
		leGroupe = thefilename.substr(0,thefilename.indexOf('RUCH')-1);
	}	
	if  (thefilename.indexOf('CADR') > -1) { // c'est un groupe CADR
		leGroupe = thefilename.substr(0,thefilename.indexOf('CADR')-1);
	}		
}

function Ecole(CodeLigne) {
	//this.Code = CodeLigne;
	this.Code = CodeLigne.substr(1,CodeLigne.indexOf(sepNumLigne)-2); 
	//alert('this.Code' + CodeLigne);
	this.TableauInfo = this.Code.split('_');
    
    this.DateTirage = this.TableauInfo[0];
	this.NomEcole = this.TableauInfo[1];
	this.CodeRefEcole = this.TableauInfo[2];
	this.Commentaire = this.TableauInfo[3];
	//this.Commentaire2 = function(){return this.TableauInfo.length + 'apple :: ' + this.Commentaire;};
	//alert('this.CodeRefEcole'  + this.CodeRefEcole);
}

function isEcole(ligne) {
//alert(ligne.substr(0, 1));
  return ligne.substr(0, 1) == '@';
}

function isEntete(ligne) {
//alert(ligne.substr(0, 1));
  return ligne.substr(0, 1) == '[';
}

function isLigneEtat(ligne) {
//alert(ligne.substr(0, 1));
  return ligne.substr(0, 1) == '{';
}

function Produit(CodeLigne) {
// <(Nom) Numéro de la photo_<Taille>_< Produit>_<Teinte>_<Nombre>ex//.jpg
	this.indexOriginal =  CodeLigne.substr(1 + CodeLigne.indexOf(sepNumLigne));
	
	this.Code =  CodeLigne.substr(0,CodeLigne.indexOf(sepNumLigne));

	this.TableauInfo = CodeLigne.substr(0,CodeLigne.indexOf(sepNumLigne)).split('_');

	this.FichierPhoto = this.TableauInfo[0];	
	this.Taille = this.TableauInfo[1] || "";	
	this.Type = this.TableauInfo[2] || "";
	this.Teinte = this.TableauInfo[3] || "";
	this.Nombre = this.TableauInfo[4] || 1;
	this.isFichierIndiv = function(){return (this.FichierPhoto.length < g_MinimuNomClasse);	};
	
	//Si fichier fini par NB ou SEP Cas des fichier Site web
	if (this.FichierPhoto.lastIndexOf("NB") > 0){
		//alert('AVANT ("NB") ' + this.FichierPhoto );
		this.Teinte = "NOIR-ET-BLANC";
		this.FichierPhoto = this.FichierPhoto.replace("NB", "");
		//alert('APRES("NB") ' + this.FichierPhoto );
	}
	if (this.FichierPhoto.lastIndexOf("SEP") > 0){
		//alert('APRES("SEP") ' + this.FichierPhoto + ' last index  :  ' + this.FichierPhoto.lastIndexOf("SEP"));	
		this.Teinte = "SEPIA";
		this.FichierPhoto = this.FichierPhoto.replace("SEP", "");
	}
	
	this.isFichierGroupe = function(){return (this.FichierPhoto.length >= g_MinimuNomClasse); };
	this.Nom = function(){return this.FichierPhoto.substr(0,this.FichierPhoto.length-4); };
	/*this.isProduitGroupe = g_PdtGROUPE.includes(this.Type);*/		
	this.isProduitGroupe = function(){		
		var retour = false;
		//alert('isProduitGroupe ' + g_PdtGROUPE[1] );
		for (var i = 0; i < g_PdtGROUPE.length; i++) {
			if (g_PdtGROUPE[i]==this.Type) {
				retour = true;
				break;
			}
		} 
		return retour;
	};
	this.isTypeGroupe = function(){		
		var retour = false;
		//alert('isTypeGroupe ' + g_TypeGROUPE[1] );
		for (var i = 0; i < g_TypeGROUPE.length; i++) {
			if (g_TypeGROUPE[i]==this.Type) {
				retour = true;
				break;
			}
		} 
		return retour;
	};	
	this.isSansNB = function(){
		var retour = false;
		//alert('g_PdtSANS_NB ' + g_PdtSANS_NB[1] );
		for (var i = 0; i < g_PdtSANS_NB.length; i++) {
			if (g_PdtSANS_NB[i]==this.Type) {
				retour = true;
				break;
			}
		} 
		return retour;
	};	
	this.isNeedGroupeClasse = function(){return (this.isFichierIndiv() && this.isProduitGroupe());};	
	this.isProduitLABO = function(){
		var retour = true;
		//alert('isProduitLABO ' + g_PdtNotLABO[1] );		
		for (var i = 0; i < g_PdtNotLABO.length; i++) {
			if (g_PdtNotLABO[i]==this.Type) {
				retour = false;
				break;
			}
		} 
		//alert('isProduitLABO ' + retour );
		return retour;
	};	/**/
}

function CommandesLabo(tableaudeLabo, FichierLab) {
	this.TableauLignes = tableaudeLabo;
	this.Ecole = this.TableauLignes[2];
	this.FichierLab = FichierLab;
	this.ListePlanches = [];
    
	this.NbLignes = function(){return this.TableauLignes.length;};
	this.isValide = function(){return (this.NbLignes() > 0) ? true : false ;};
	
	this.NbPlanchesACreer = function(){return this.ListePlanches.length;};
	//alert( 'Init Liste Planches');
	this.InitListePlanches = function(){
		this.ListePlanches.length = 0;
		var identifiant = '';	
		//Pour sauter les 2 lignes d'entete
		//alert( 'Init Liste Planches');			
		for (var i = 2; i < this.TableauLignes.length; i++) {
			//if ((!isEcole(this.TableauLignes[i])) && (TableauLignes[i] != '')) {
			identifiant = this.TableauLignes[i].substr(0, 1);
			if ((identifiant != '[') &&
				(identifiant != '{') && 
				(identifiant != '#') && 
				(identifiant != '<') && 
				(identifiant != '')) {

				if (!isEcole(this.TableauLignes[i]) && (this.TableauLignes[i])) {
					this.ListePlanches.push(this.TableauLignes[i]);
				}
			}				
		} 		
	};
	this.isRecord = function(){
		var CodeCherche = this.FichierLab.substr(0, this.FichierLab.length-5); //Recalcul
		var CodeTrouve = this.TableauLignes[0].substr(14); //Lecture de la commande
		CodeTrouve = CodeTrouve.substr(0,CodeTrouve.indexOf(sepNumLigne)); 
		//alert( 'isRecord = (b64_md5('+ CodeCherche + ') :' + hex_md5(CodeCherche) + ' =' + CodeTrouve);		//this.FichierLab = 'xx';
		return (hex_md5(CodeCherche) == CodeTrouve); // false
		
	};
}

//////// API Fx //////////////////////
function LogResultAPI(resultAPI) {
	var fileName = g_SelectFichierLab.path + '/logAPI.txt'; // + '1' : Etat les planches de la commande sont créees
	var file = new File(fileName);
	file.open("w"); // open file with write access
		file.writeln(resultAPI);
	file.close();
	return true;
}

function TestAPI(){
	//var retourAPI = APIphotolab('?apiTEST=TEST');
	var retourAPI = APIphotolab('?apiCMDLAB=TEST');
	//alert ('retourAPI : ' + retourAPI);
	var isconnecte = (retourAPI == 'OK');
	//alert ('(retourAPI == OK) : ' + isconnecte);
	if (!isconnecte){
		var LaConnexion = new APIConnexionJS;
		MsgERREUR("ERREUR TestAPI()", 'Impossible de se connecter : \n\n ' + + LaConnexion.Adresse + '\n\n Vérifiez l\'accès au service...\n\n TestAPI() = ' + TestAPI());
	}
	return isconnecte;
}

function APIphotolab(Data){
	var LaConnexion = new APIConnexionJS;
	if (LaConnexion.isSocket){
		try {
			alert( 'Adresse + Data  : ' + LaConnexion.Adresse + Data );		
			var reponse;  
			var socket = new Socket();  
			// socket.encoding = "binary";  
			if (socket.open(LaConnexion.Domaine , 'binary')) { // 188.121.45.1  
				//alert( 'connect to : ' + LaConnexion.Adresse + Data);
				socket.timeout = 20000;
				socket.write ('GET ' + LaConnexion.Adresse + Data + ' HTTP/1.0\r\nHost:' + LaConnexion.Domaine + '\r\nConnection: close\r\n\r\n');  
				reponse = socket.read(999999);  
				var result = removeHeaders(reponse); 	
				//var result = reponse; 			
				socket.close(); 
				LogResultAPI(result);
				return result;
			}
			else {
				alert('socket.open false API');
				return '';		
			}
		}
		catch(err) {
			alert( 'Probleme de connexion avec APIphotolab : \n\n' + LaConnexion.Service + '\n\n Ne fonctionne pas !' + err.message);
			return '';
		}	
	}
	else{
		//alert( 'connect to local');
		if ( Data == '?apiCMDLAB=TEST'){ return 'OK'; }
		else{
			if ( Data == '?apiCMDLAB=' + g_SelectFichierLab.name){
				g_SelectFichierLab.open("r");
				var tabPlanchesLabo = [];
				var ligne = "";
				while(!g_SelectFichierLab.eof){
					ligne = g_SelectFichierLab.readln();
					//alert('ligne ' + ligne);
					if (ligne != ""){
						if (ligne.substr(0,2) != "//"){
							tabPlanchesLabo.push(ligne);
							//alert( ' ligne : ' + ligne );	
						}						
					}
				}
				g_SelectFichierLab.close();			
				var result ='';
				for(var i = 0; i < tabPlanchesLabo.length; i++){
					var identifiant = tabPlanchesLabo[i].substr(0, 1);
					if ((identifiant != '#') 
						&& (identifiant != '<') 
						//&& (identifiant != '[') 
						//&& (identifiant != '{') 
						&& (identifiant != '')) {
						//alert('tabPlanchesLabo[i] ' + tabPlanchesLabo[i]);
						result = result + tabPlanchesLabo[i] + sepNumLigne + i + sepRetourLigne;
						
					}		
				 }
				 result = 'OK' + result;
				 LogResultAPI(result);
				 //alert('reukt ' + result);
				return result;			
			}		
		}
	}
}

function removeHeaders(binary){
    var bContinue = true, // flag for finding end of header
        line = '',
        nFirst = 0,
        count = 0;

    while (bContinue) {
        line = getLine(binary) ; // each header line
        bContinue = line.length >= 2 ; // blank header == end of header
        nFirst = line.length + 1 ;
        binary = binary.substr(nFirst) ;
    }
    return binary;
}

function getLine(html){
    var line = '', i = 0;
    for (; html.charCodeAt(i) != 10; i++){ // finding line end
        line += html[i] ;
    }
    return line;
}
