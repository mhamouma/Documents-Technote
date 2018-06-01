<?php

		try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());	
			}

		

		if(isset($_GET['pseudo']) and isset($_GET['mail']))
		{
			$req = $bdd->query('SELECT * FROM membre WHERE pseudo="'.$_GET['pseudo'].'"');
			$donnee = $req->fetch();
			if($donnee)
			{
				echo 'erreur:pseudo';
			}
			else
			{
				$req2 = $bdd->query('SELECT * FROM membre WHERE mail="'.$_GET['mail'].'"');
				if(($req2->fetch()))
				{
					echo 'erreur:mail';
				}

				else
				{
					
					
				}
			}
		}


?>
