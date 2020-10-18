<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body, html {
  height: 100%;
  margin: 0;
}

.bg {
  /* The image used */
	background-color: #3b3b3b;
	background-image: url("<?php echo $_GET['urlImage']; ?>");


  /* Full height */
  height: 100%; 

  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;

}
/* The Close Button (x) */
.close {
    position: absolute;
    right: 20px;
    top: -15px;
    color: #FFF;
    font-size: 100px;
    font-weight: bold;
	text-decoration: none;
}

.close:hover,
.close:focus {
    color: #F00;
    cursor: pointer;
} 
</style>
</head>
<body>
<div class="bg"></div>
<a href="javascript:history.go(-1)" title="Retour aux commandes" class="close">&times;</a>
</body>
</html>