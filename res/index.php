<?php
setlocale(LC_TIME, 'french');

include 'APIConnexion.php';

/*include 'res/CATFonctions.php';
include 'res/ConvertCSV.php';

$repCommandesLABO = "../CMDLABO/";
*/
$codeMembre = 0;
if (isset($_GET['codeMembre'])) { $codeMembre = $_GET['codeMembre'];}
$isDebug = file_exists ('debug.txt');
if (isset($_GET['isDebug'])) { $isDebug = ($_GET['isDebug'] == 'Debug') ? true : false;}

$maConnexionAPI = new CConnexionAPI($codeMembre,$isDebug);

if ($codeMembre == '' || $codeMembre == '0'){
	header('Location: ' . $maConnexionAPI->URL . '/index.php?PourConnexionLOCAL=true');

}

?>

<!DOCTYPE html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<html>
<head>
	<title id="GO-PHOTOLAB">PhotoLab : accueil</title>
    <link rel="stylesheet" type="text/css" href="css/Couleurs<?php echo ($isDebug?'':'AMP'); ?>.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
	<!-- <script type="text/javascript" src="res/js/CATFonctions.js"></script>
	<script type="text/javascript" src="res/APIConnexion.js"></script>	 -->
</head>
<!-- <div class="logo">	
		<img src="res/img/Logo.png" alt="Image de fichier">
	</div> -->
<body>

<div class="logo">
	<img src="img/Logo.png" alt="Image de fichier">
</div>


	<center>
		<div class="recherche">	
		<h1>Phot<img src="img/Logo-Ultra-mini.png" width="20">Lab <?php echo $GLOBALS['VERSION'] ?></h1>
		</div>

		<?php 		
echo '
<div id="mySidenav" class="sidenav">
  <a href="CATSources.php' . ArgumentURL().'" id="sourcePhotos" title="Sources des photos ..."></a>
  <a href="CATPhotolab.php' . ArgumentURL().'" id="commandesEnCours" title="Commandes en cours de préparation ..."></a>
  <a href="CATHistorique.php' . ArgumentURL().'" id="commandesExpediees" title="Historique des commandes expediées ..."></a>
  <a href="' . $maConnexionAPI->Adresse().'" id="administration" title="Administration ..."></a>
</div>
'	
?>
		
<br><br>
		<?php 
		if ($codeMembre != '' && $codeMembre != '0'){
			echo '<div id="dropArea"><br>Glisser déposer un fichier commandes dans cette zone.<br>
			Soit un fichier (.lab ou .web) créé par ProdEcole (Excel).<br>
			Soit un fichier (.csv) téléchargé depuis le site de vente en ligne Lumys.<br>
			<span id="count"></span>
			<div id="result"></div>				
			</div>';	
		}else{
			
			echo '<a href="' . $maConnexionAPI->URL . '/index.php?PourConnexionLOCAL=true' . '">Connectez vous pour deposer des commandes !</a>';
		}
		?>
		<br><br><br>
		<p>	<?php echo VersionPhotoLab();?> </p>
	</center>
	<!-- <script src="js/drop.js"></script> -->
	<script>
	
// variables
var dropArea = document.getElementById('dropArea'); // drop area zone JS object
var count = document.getElementById('count'); // text zone to display nb files done/remaining
var result = document.getElementById('result'); // text zone where informations about uploaded files are displayed
var list = []; // file list
var nbDone = 0; // initialisation of nb files already uploaded during the process.


// main initialization
(function(){

	// init handlers
	function initHandlers() {
		dropArea.addEventListener('drop', handleDrop, false);
		dropArea.addEventListener('dragover', handleDragOver, false);
		dropArea.addEventListener('dragleave', handleDragLeave, false);
	}

	// drag over
	function handleDragOver(event) {
		event.stopPropagation();
		event.preventDefault();

		dropArea.className = 'hover';
	}
	// drag Leave
	function handleDragLeave(event) {
		event.stopPropagation();
		event.preventDefault();

		dropArea.className = 'leave';
	}
	// drag drop
	function handleDrop(event) {
		event.stopPropagation();
		event.preventDefault();

		processFiles(event.dataTransfer.files);
	}

	// process bunch of files
	function processFiles(filelist) {
		if (!filelist || !filelist.length || list.length) return;

		result.textContent = '';

		for (var i = 0; i < filelist.length && i < 500; i++) { // limit is 500 files (only for not having an infinite loop)
			list.push(filelist[i]);
		}
		uploadNext();
	}

	// upload file
	function uploadFile(file, status) {

		// prepare XMLHttpRequest
		var xhr = new XMLHttpRequest();
		xhr.open('POST', <?php echo "'DROPUpload.php". ArgumentURL(). "'" ;?>);
		//xhr.open('POST', 'index.php');
		xhr.onload = function() {
			result.innerHTML += this.responseText;
			uploadNext();
		};
		xhr.onerror = function() {
			result.textContent = this.responseText;
			uploadNext();
		};

		// prepare and send FormData
		var formData = new FormData();  
		formData.append('myfile', file); 
		xhr.send(formData);
	}

	// upload next file
	function uploadNext() {
		if (list.length) {
			var nb = list.length - 1;
			nbDone +=1;
			//count.textContent = 'Files done: '+nbDone+' ; '+'Files left: '+nb;

			var nextFile = list.shift();
			var extension = nextFile.name.substr(nextFile.name.lastIndexOf("."));
			//count.textContent = 'Files : '+	extension;
			if (extension == '.lab' || extension == '.web' || extension == '.csv') {
				if (nextFile.size >= 20000000) { // 20Mb = generally the max file size on PHP hosts
					result.innerHTML += '<div class="f">File too big</div>';
					count.textContent = '<div class="f">File too big</div>';
					uploadNext();
				} else {
					count.textContent = '';
					uploadFile(nextFile, status);
				}
			} else {
				count.textContent = nextFile.name + ' : n\'est pas un fichier de type : ".lab, .web ou .csv"';
				uploadNext();
			}
		}
	}

	initHandlers();
})();
	</script>	
	</body>
</html>

<?php ?>
