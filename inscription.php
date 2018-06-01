<!DOCTYPE html>

<?php
session_start();
?>


<html>
  <head>
	<meta charset = "utf-8" />
	<link rel="stylesheet" href="style.css">
	<title>inscription</title>
  </head>
  <body>

	<?php
	include('header.php');
	?>

	<?php
	if(isset($_SESSION['pseudo']))
	{
		echo 'Erreur chargement de page!<br/>Vous ètes actuellemnent connécté!';
	}
	else
	{

	//fonction pour création aléatoire de mot de passe
	function mdp_alea($chaine='azertyuiopqsdfghjklmwxcvbn123456789')
	{
		$nb_lettres = strlen($chaine) - 1;
		$mdp = "";
		for($i=0;$i<8;$i++)
		{
			$pos = mt_rand(0,$nb_lettres);
			$car = $chaine[$pos];
			$mdp.=$car;
		}
		return $mdp;
	}

	?>
			<article id="page">
			</br>
			<h2>Inscription</h2>
			<form action='inscription.php' method='post'>
		
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

			if(isset($_POST['pseudo']) AND isset($_POST['mail']))
			{
			//vérification des donées
	
			//on demande une requéte comprenant le mail ou le pseudo donné 
			$membres = $bdd->prepare('SELECT * FROM membre WHERE mail = :mail OR pseudo = :pseudo');
			$membres->execute(array(
			'mail' => $_POST['mail'],
			'pseudo' => $_POST['pseudo']));
	
			if(!($membres->fetch()))//si on ne trouve pas de requète on va pouvoir enregistrer nos donées aprés les avoir vérifier
			{
				$erreur1 = false;
				$erreur2 = false;

				//erreur 1
				if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#",$_POST['mail']))
				{
					$erreur1 = true;
					echo '<FONT color="red">-mail non valide! Exemple de mail valide: "nicky.larson@yahoo.fr"</FONT></br>';
				}
				
				//erreur 2		
				if(!preg_match("#^[a-zA-Z0-9.-_]{5,}$#",$_POST['pseudo']))
				{
					$erreur2 = true;
					echo '<FONT color="red">-pseudo non valide! Vous devez avoir un pseudo possédant au moins 5 lettres</FONT></br>';
				}
			
				if(!($erreur1 == true || $erreur2 == true))
				{
					$mdp = mdp_alea();//le mot de passe aléatoire assigné au compte
					echo $mdp.'<br/>';

					//hacker le mot de passe
					$mdp = sha1($mdp);
			
					//enregistrer le compte dans la base de donnée

					$req=$bdd->prepare('INSERT INTO membre(mail,pseudo,mot_de_passe,date_inscription,idr) VALUES(:mail,:pseudo,:mdp,NOW(),2)');
					$req->execute(array('mail'=>$_POST['mail'],
							'pseudo'=>$_POST['pseudo'],
							'mdp'=>$mdp));

					//envoyer un message sur la boite mail comprenant $mdp

					//=====Création de la boundary
					$boundary = "-----=".md5(rand());

					//=====Définition du sujet.
					$sujet = "Bienvenue su Technote";

					//=====Création du header de l'e-mail.
					$header = "From: \"TecnoteWebMaster\"<couteau07@outlook.com>\n";
					$header.= "Reply-to: \"\"TecnoteWebMaster\"<couteau07@outlook.com>\n";
					$header.= "MIME-Version: 1.0\n";
					$header.= "Content-Type: multipart/alternative;\n"." boundary=\"$boundary\"\n";


					//=====Création du message.
					$message = "\n--".$boundary."\n";

					//=====Ajout du message au format texte.
					$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"\n";
					$message.= "Content-Transfer-Encoding: 8bit\nç ";
					$message.= "\nBonjour,\nVotre compte sur Technote a été créé avec succés.\nVoici vos identifiants de connexion:\npseudo: ".$_POST['pseudo']."\nmot de passe: ".$mdp."\n\nA bientot sur Technote.\nL\'équipe de Technote\n";
					//==========

					$message.= "\n--".$boundary."\n";
					$message.= "\n--".$boundary."--\n";
					$message.= "\n--".$boundary."--\n";

					//==========

					 

					//=====Envoi de l'e-mail.

					$res=mail($_POST['mail'],$sujet,$message,$header);

					//==========

					echo "Votre inscription a bien été pris en compte!<br/>
					Vous allez recevoir un mail sous peu avec le mot de passe avec lequel vous vous connecterez sur le site.<br/>
					<br/>Vous allez ètre redirigés sur la page de connexion...";
					header('Refresh: 4; connect.php');//redirection au bout de 4 secondes
				}
		
			}
			else
			{	

				$erreur3 = false;//mail déja enregistré dans la base de donnée
				$erreur4 = false;//pseudo déja utilisé

				$membres->closeCursor();
				$membres = $bdd->prepare('SELECT * FROM membre WHERE mail = :mail OR pseudo = :pseudo');
				$membres->execute(array(
				'mail' => $_POST['mail'],
				'pseudo' => $_POST['pseudo']));
				
				foreach($membres as $membre)
				{
					$ps = $_POST['pseudo'];
					$ml = $_POST['mail'];
				
					//erreur 3
					if($membre['mail']==$ml)
						$erreur3 = true;
				
					//erreur4
					if($membre['pseudo']==$ps)
						$erreur4 = true;

				}

				if($erreur3 == true) echo '<FONT color="red">mail déja enregistré dans la base de donnée!</FONT></br>';
				if($erreur4 == true) echo '<FONT color="red">pseudo déja utilisé!</FONT></br>';

			}	
		}
	
			if(!(isset($erreur1) or isset($erreur2) or isset($erreur3) or isset($erreur4))
			or (isset($erreur1) and $erreur1)
			or (isset($erreur2) and $erreur2)
			or (isset($erreur3) and $erreur3)
			or (isset($erreur4) and $erreur4)
				)
				echo "<br/>	
				<form action='inscription.php' method='post'>
				<input type='text' id='pseudo' name='pseudo' placeholder='pseudo désiré' required>
				<br/>	
				<input type='text' id='mail' name='mail' placeholder='votre mail' required>
				<br/>	
				<button type='button' id='buton' onclick='submit();'>Envoyer</button>
				<br/>
				</form>"
			?>
	</article>
	<?php
	}
	?>

	<script>
		
		var buton = document.getElementById('buton');	

		//animation pour le bouton de submission
		buton.addEventListener("mouseover",function animOver(){
			var s = buton.style;
			s.background="#00008B";
		});


		buton.addEventListener("mouseout",function animOut(){
			var s = buton.style;
			s.background="#4682B4";
		});
		

	</script>



  </body>
</html>
