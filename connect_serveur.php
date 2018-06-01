<?php

		try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());	
			}

		if(isset($_GET['pseudo']) and isset($_GET['mdp']))
		{
			$req = $bdd->query('SELECT * FROM membre WHERE pseudo="'.$_GET['pseudo'].'"');
			$donnee = $req->fetch();
			if(!$donnee)
			{
				echo 'erreur:pseudo';
			}
			else
			{
				$mdp = sha1($_GET['mdp']);
				$req2 = $bdd->query('SELECT * FROM membre WHERE pseudo="'.$_GET['pseudo'].'" AND mot_de_passe="'.$mdp.'"');
				if(!($req2->fetch()))
				{
					echo 'erreur:mdp';
				}

				else
				{
					echo 'accepte';
					//session_start();
					//il faudra se connecter sur la page quand l'utilisateur sera accépté
					$req_donnee_membre = $bdd->query('SELECT * FROM membre WHERE pseudo="'.$_GET['pseudo'].'"');
					$donnee_membre = $req_donnee_membre->fetch();
					session_start();
					$_SESSION['pseudo'] = $donnee_membre['pseudo'];
					$_SESSION['mail'] = $donnee_membre['mail'];
					$_SESSION['role'] = $donnee_membre['idr'];
					if(isset($_POST['stayconnect']))//si la checkbox a été coché
					{
						//rester connecter
						$_SESSION['stayconnect']=1;
					}
				}
			}
		}


?>
