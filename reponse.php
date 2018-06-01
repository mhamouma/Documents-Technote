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
	include('header.php');//l'en-tête

	//ajouter un commentaire
	if(isset($_POST['message']))
	{
		$ajout = $bdd->prepare('INSERT INTO commentaire(id_reponse,message,mail_membre,date) VALUES(:id,:message,:mail,NOW())');
			$ajout->execute(array(
			'id' => $_GET['ID'],
			'message' => $_POST['message'],
			'mail' => $_SESSION['mail']));
	}

	//ouverture de la page en fonction de la donnée placé en get
	
	echo '<div id="page">';
	echo '<br/>';

	$req = $bdd->query('SELECT message,date,pseudo FROM reponse,membre WHERE mail = mail_membre AND reponse.IDR='.(int)$_GET['ID']);
	$resultat = $req->fetch();

	echo '<div id="contenu">';

		//edit
		if(droit('E1')
		||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
		{
			echo'<span id="droite"><a href=\"delete.php?statut=question&ID='.$_GET['ID'].'" id="edit" onclick=\'return confirm("Etes vous sur de vouloir supprimer cette question?")\'>supprimer</a></span>';
		}

		//pseudo/date
		echo '<div id="h2"><a href="profil.php?pseudo='.$resultat['pseudo'].'">'.$resultat['pseudo'].'</a></div>  '.'<a id="h3">'.$resultat['date'].'</a>';

		//contenu
		echo manager_texte($resultat['message']);
		echo '<br/><a id="com" href="reponse.php?ID='.$_GET['ID'].'&focus=1">commentez</a>';
	echo '</div>';
	echo '<br/>';

		
	//lecture des commentaires
	$req2 = $bdd->query('SELECT commentaire.IDC as IDC,message,pseudo,date FROM commentaire,membre WHERE id_reponse='.(int)$_GET['ID'].' AND mail_membre=mail ORDER BY date');
	while($resultat=$req2->fetch())
	{
		echo '<div id="commentaire">';
			if(droit('E1')
			||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
			{
				echo'<span id="droite"><a href="delete.php?statut=commentaire&ID='.$resultat['IDC'].
				'&url=reponse.php?ID='.$_GET['ID'].'" id="edit" onclick=\'return confirm("Etes vous sur de vouloir supprimer ce commentaire?")\'>supprimer</a></span>';//supprimer réponse
			}
			echo '<div id="h2"><a href="profil.php?pseudo='.$resultat['pseudo'].'">'.$resultat['pseudo'].'</a></div> <a id="h3"> '.$resultat['date'].'</a>';
			echo manager_texte($resultat['message']);
		echo '</div>';
		echo '<br/>';
	}

	if(droit('C'))//si on a les droits pour poster un commentaire
	{
		echo '<div id="text"><form action="reponse.php?ID='.$_GET['ID'].'#fin_page" method="post">';
		echo '<br/><textarea name="message" id="message" rows="10" cols="45" placeholder="écrivez un commentaire"></textarea>';
		echo '<br/><input type="submit" value="envoyer">';
		echo '</form></div>';
	}
	echo '<div id="fin_page"></div>';//une ancre

	echo "</div>";

	if(isset($_GET['focus']))
	{
		echo '
		<script>
			var com = document.getElementById(\'message\');
			com.focus();
		</script>';
	}
	?>	
	
	<script>

	//faire afficher les commentaire
	//envoyer une requète qui va afficher les commentaires pour un message donné
	//utiliser surement un support xml
	//rajouter les commentaires dans des balises en donnant le moins d'informations possible
	//les rajouter avec une marge sur la gauche 
	</script>
	

  </body>
</html>
