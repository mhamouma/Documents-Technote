<!DOCTYPE html>

<?php 

session_start();


// Suppression des variables de session et de la session

$_SESSION = array();

session_destroy();

?>

<html>
  <head>
	<meta charset = "utf-8" />
	<link rel="stylesheet" href="style.css">
	<title='Rechercher une technote'>
  </head>
  <body>
	<?php
			
	include("header.php");
	echo "Vous avez été déconnécté!" 
	?>
  </body>
</html>
