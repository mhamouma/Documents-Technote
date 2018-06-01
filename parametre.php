<!DOCTYPE HTML>

<?php
session_start();
?>

<html>
  <head>
	<meta charset = "utf-8" />
	 <link rel="stylesheet" href="style.css">
	<title>Technote</title>
  </head>
  <body>
	<?php
	include('header.php');

	if(droit('Pr',true))
	{
	
		try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());
			}

		//changement de mot de passe
		if(isset($_POST['mdp']) and isset($_POST['new_mdp']))
		{
			$req = $bdd->query('SELECT mot_de_passe FROM membre WHERE mail="'.$_SESSION['mail'].'"');
			$mdp = $req->fetch();
			if(strcmp(sha1($_POST['mdp']),$mdp['mot_de_passe'])!=0)//comparer deux string
			{
				echo "<FONT color=\"red\">mot de passe actuel éronné!</FONT><br/>";
			}
			else
			{
				if(strcmp($_POST['new_mdp'],$_POST['confirm_mdp'])!=0 or !preg_match("#^[a-zA-Z0-9]{6,}$#",$_POST['new_mdp']))
				{
					echo "<FONT color=\"red\">Les mots de passe sont différents ou le mot de passe ne respecte pas le bon format</FONT><br/>";
					if(!preg_match("#^[a-zA-Z0-9]{6,}$#",$_POST['new_mdp']))
					{
						echo "<FONT color=\"red\">Le mot de passe peut contenir ces ensembles [a-z][A-Z][0-9] et doit contenir au moins 6 lettres</FONT><br/>";
					}
				}
				else
				{	
					$bdd->exec('UPDATE membre SET mot_de_passe="'.sha1($_POST['new_mdp']).'" WHERE mail="'.$_SESSION['mail'].'"');
					echo "mot de passe modifié<br/>";
				}
			}
		}


		//changement de pseudo
		if(isset($_POST['mdp']) and isset($_POST['new_pseudo']))
		{
			$req = $bdd->query('SELECT mot_de_passe FROM membre WHERE mail="'.$_SESSION['mail'].'"');
			$mdp = $req->fetch();
			if(strcmp(sha1($_POST['mdp']),$mdp['mot_de_passe'])!=0)//comparer deux string
			{
				echo "<FONT color=\"red\">Vous n'avez pas rentré votre bon mot de passe!</FONT><br/>";
			}
			else
			{
				if(!preg_match("#^[a-zA-Z0-9.-_]{5,}$#",$_POST['new_pseudo']))//majorer le pseudo
				{
					echo "<FONT color=\"red\">pseudo non valide! Vous devez avoir un pseudo possédant au moins 5 lettres</FONT><br/>";
				}
				else
				{	
					$bdd->exec('UPDATE membre SET pseudo="'.$_POST['new_pseudo'].'" WHERE mail="'.$_SESSION['mail'].'"');
					$_SESSION['pseudo']=$_POST['new_pseudo'];
					//les posts changent?
					echo "pseudo modifié<br/>";
				}
			}
		}

		//supression du compte
		if(isset($_POST['mdp']) and isset($_POST['delete_compte']))
		{
			$req = $bdd->query('SELECT mot_de_passe FROM membre WHERE mail="'.$_SESSION['mail'].'"');
			$mdp = $req->fetch();
			if(strcmp(sha1($_POST['mdp']),$mdp['mot_de_passe'])!=0)//comparer deux string
			{
				echo "<FONT color=\"red\">Vous n'avez pas rentré votre bon mot de passe!</FONT><br/>";
			}
			else
			{
				//rechercher toutes les technotes, questions et réponses de cet utilisateur
				$bdd->exec('UPDATE technote SET mail_="membre_supprime"  WHERE (mail_="'.$_SESSION['mail'].'") ');
				$bdd->exec('UPDATE question SET mail_membre="membre_supprime" WHERE mail_membre="'.$_SESSION['mail'].'"');
				$bdd->exec('UPDATE reponse SET mail_membre="membre_supprime" WHERE mail_membre="'.$_SESSION['mail'].'"');

				//supprimer tous les commentaires émis par cet utilisateur
				$bdd->exec('DELETE FROM commentaire WHERE mail_membre="'.$_SESSION['mail'].'"');

				//on supprime le membre de la bdd
				$bdd->exec('DELETE FROM membre WHERE mail="'.$_SESSION['mail'].'"');
				//déconnecter
				$_SESSION = array();
				session_destroy();
				echo "Votre compte a été supprimé!";	
				//envoyer un mail au membre supprimé
			}
		}

		if(isset($_POST['submit_staymail']))
		{
			if(isset($_POST['staymail']))
				setcookie("staymail",1);
			else
				setcookie('staymail',0);

			header('Location:parametre.php');
		}

  echo '<div id="page">';
	
	echo '<h2>Paramètres</h2>';
		//sinon
		echo "<ul>";
			//echo '<li><a href="parametre.php?parametre=pseudo">modifier pseudo</a></li>';
			echo '<div id="accordeon1" style="padding-left:40px;">
						<a style="color:black;" href="#">modifier pseudo <a style="color:black;font-size:0.6em;">▼</a></a></div>';

				echo '<div id="element_accordeon1" style="display:none;">';
					echo '<form action="parametre.php" method="post">';
					echo '  <input type="text" name="new_pseudo" placeholder="nouveau pseudo" required><br/>';
					echo '  <input type="password" name="mdp" placeholder="mdp actuel" required>';
					echo '<br/>  <input type="submit" name="edit_pseudo" value="valider">';
					echo'</form>';
				echo '</div>';

			echo '<div id="accordeon2" style="padding-left:40px;padding-top:5px;">
						<a style="color:black;" href="#">modifier mot de passe <a style="color:black;font-size:0.6em;">▼</a></a></div>';
			
        echo '<div id="element_accordeon2" style="display:none;">';
				  echo '<form action="parametre.php" method="post">';
				  echo '  <input type="password" name="mdp" placeholder="mdp actuel" required>';
				  echo '</br><input type="password" name="new_mdp" placeholder="nouveau mdp" required>';
				  echo '</br><input type="password" name="confirm_mdp" placeholder="confirmez nouveau mdp" required>'; 
				  echo '</br><input type="submit" name="edit_mdp" value="valider">';
				  echo'</form>';
        echo '</div>';


			echo '<div id="accordeon3" style="padding-left:40px;padding-top:5px;">
						<a style="color:black;" href="#">supprimer compte <a style="color:black;font-size:0.6em;">▼</a></a></div>';

			  echo '<div id="element_accordeon3" style="display:none;">';
				echo '<form action="parametre.php" method="post">';
				echo '  <input type="password" name="mdp" placeholder="mdp actuel" required>';
				echo '<br/><input type="submit" name="delete_compte" value="supprimer mon compte" onclick=\'return confirm("Etes vous sur de vouloir supprimer votre compte?")\'>';
				echo'</form>';
        echo '</div>';

			echo '<div id="accordeon4" style="padding-left:40px;padding-top:5px;">
						<a style="color:black;" href="#">afficher mail<a style="color:black;font-size:0.6em;">▼</a></a></div>';
				echo '<div id="element_accordeon4" style="display:none;">';
				echo '<form action="parametre.php" method="post">';
				if(isset($_COOKIE['staymail']) and $_COOKIE['staymail']==1)
				{
					echo '<label><input type="checkbox" id="checkbox" name="staymail" checked>permettre aux autres membres de voir mon mail</label>';
				}
				else
					echo '<label><input type="checkbox" id="checkbox" name="staymail">permettre aux autres membres de voir mon mail</label>';
				echo '<br/><input type="submit" name="submit_staymail" value="valider">';
				echo '</form>';	
				echo '</div>';
			echo '</div>';

	 echo '</div>';
	}
	?>

	<script>
		var acc = document.getElementById('element_accordeon1');

		document.getElementById('accordeon1').addEventListener('click',function(){displayB('accordeon1');});
    document.getElementById('accordeon2').addEventListener('click',function(){displayB('accordeon2');});
    document.getElementById('accordeon3').addEventListener('click',function(){displayB('accordeon3');});
		document.getElementById('accordeon4').addEventListener('click',function(){displayB('accordeon4');});

		function displayB(accordeon)
		{
      if(accordeon=="accordeon1")
      {
        acc=document.getElementById('element_accordeon1');
      }
      else if(accordeon=="accordeon2")
      {
        acc=document.getElementById('element_accordeon2');
      }
      else if(accordeon=="accordeon3")
      {
        acc=document.getElementById('element_accordeon3');
      }
			else if(accordeon=="accordeon4")
      {
        acc=document.getElementById('element_accordeon4');
      }
			if(acc.style.display == "none")
			  acc.style.display="block";
      else
        acc.style.display = "none";
		}

	</script>
  </body>
</html>
