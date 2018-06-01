<?php
	//tableau de tous les mots clés pour l'autocomplétion

		try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());	
			}

		if(isset($_GET['action']) and !strcmp($_GET['action'],"resolu"))
		{
			$id = $_GET['id'];
			echo $id;
			$bdd->exec('UPDATE question SET statut="resolue" WHERE IDQ='.$id.'');
		}
?>	









