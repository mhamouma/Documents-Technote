<!DOCTYPE html>
<html>
  <head>
	<meta charset = "utf-8" />
	<title>inscription</title>
  </head>
  <body>

	<?php
	//ouverture base de donnée
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
	}
	catch(PDOException $e)
	{
		die('Erreur :'.$e->getMessage());	
	}

	//fonction pour création aléatoire de mot de passe
	function mdp_alea($chaine='azertyuiopqsdfghjklmwxcvbn123456789')
	{
		$nb_lettres= strlen($chaine) - 1;
		for($i=0;$i<8;$i++)
		{
			$pos = mt_rand(0,$nb_lettres);
			$car = $chaine[$pos];
			$mdp.=$car;
		}
		return $mdp;
	}

	if(isset($_POST['pseudo']) AND isset($_POST['mail']))
	{
		//vérification des donées
	
		//on demande une requéte comprenant le mail ou le pseudo donné 
		$membres = $bdd->prepare('SELECT * FROM membre WHERE mail = :mail AND pseudo = :pseudo');
		$membres->execute(array(
		'mail' => $_POST['mail'],
		'pseudo' => $_POST['pseudo']));
	
		if(!($membres->fech()))//si on ne trouve pas de requète on va pouvoir enregistrer nos donées aprés les avoir vérifier
		{
			$erreur1 = false;
			$erreur2 = false;

			//erreur 1
			if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#",$_POST))
				$erreur1 = true;
				
			//erreur 2		
			if(!preg_match("#^[a-zA-Z0-9.-_]{5,}$#",$ps))
				$erreur2 = true;

			if($erreur1 == true || erreur2 == true)//si on a détécté une erreur
			{
				header('inscription.php');//on revient sur la page principale
			}

			$mdp = $mdp_alea();//le mot de passe aléatoire assigné au compte

			//hacker le mot de passe
			//enregistrer le compte dans la base de donnée
			//envoyer un message sur la boite mail comprenant $mdp

			echo "Votre inscription a bien été pris en compte!<br/>
			Vous allez recevoir un mail sous peu avec le mot de passe avec lequel vous vous connecterez sur le site.<br/>
			<br/>Vous allez ètre redirigés sur la page de connexion...";
			header('Refresh: 3; bdd.php');//redirection au bout de 3 secondes
		
		}
		else
		{	

			$erreur3 = false;//mail déja enregistré dans la base de donnée
			$erreur4 = false;//pseudo déja utilisé

			membres->closeCursor();
			$membres = $bdd->prepare('SELECT * FROM membre WHERE mail = :mail AND pseudo = :pseudo');
			$membres->execute(array(
			'mail' => $_POST['mail'],
			'pseudo' => $_POST['pseudo']));
				
			foreach($membres as $membre)
			{
				$ps = $_POST['pseudo'];
				$ml = $_POST['mail'];
				
				//erreur 3
				if($membre['mail']==$ml)
					erreur3 = true;
				
				//erreur4
				if($membre['pseudo']==$ps)
					erreur4 = true;

			}
			
			/*
			*construire un message d'erreur qui dira si le pseudo est déja utilisé ou/et si le mail donné est déja uttilisé
			*/
			//récupérer les donées envoyées pour actualiser

			header('inscription.php');//redirection immédiate
		}	
	}

	?>
  </body>
</html>
