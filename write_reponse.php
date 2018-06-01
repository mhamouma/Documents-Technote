<!DOCTYPE html>

<?php
session_start();
?>

<html>
  <head>
	<meta charset = "utf-8" />
	<link rel="stylesheet" href="style.css">
	<title='Technote'>
  </head>
  <body>
	<?php

	//chargement base de donnée
	try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
		}
		catch(PDOException $e)
		{
			die('Erreur :'.$e->getMessage());	
		}	
	
	if(isset($_POST['reponse']))
	{
		$ajout = $bdd->prepare('INSERT INTO reponse(id_question,message,mail_membre,date) VALUES(:id,:message,:mail,NOW())');
			$ajout->execute(array(
			'id' => $_GET['ID'],
			'message' => $_POST['message'],
			'mail' => $_SESSION['mail']));
	}
	
	?>
	<form method="post" action="write_reponse.php">
	<textaera name="reponse"></textarea>
	<br/>
	<input type="submit" value="valider">
	</form>
  </body>
