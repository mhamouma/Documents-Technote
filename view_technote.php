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
		$ajout = $bdd->prepare('INSERT INTO commentaire(id_technote,message,mail_membre,date) VALUES(:id,:message,:mail,NOW())');
			$ajout->execute(array(
			'id' => $_GET['ID'],
			'message' => $_POST['message'],
			'mail' => $_SESSION['mail']));
	}

	if(droit('L'))//droit en lecture
	{
		echo '<div id="page">';//style de la page

		//affichage de la technote
		$req = $bdd->query('SELECT titre,contenu,date_creation,pseudo FROM technote,membre WHERE mail = mail_ AND ID='.(int)$_GET['ID']);
		$resultat = $req->fetch();

		//titre de la technote
		echo '<br/><h2>Sujet: '.$resultat['titre'].'</h2><br/>';
		
		//contenu de la technote
		echo '<div id="contenu_technote">';
	
		//boutons modifier,supprimer si on est le créateur de la page ou si on est administrateur
		if(droit('E1')
		||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
		{
			echo'<span id="droite"><a href="new_page.php?statut=edit_technote&ID='.$_GET['ID'].'" id="edit">modifier</a> ';
			echo'<a href="delete.php?statut=technote&ID='.$_GET['ID'].'" id=\'edit\' onclick=\'return confirm("Etes vous sur de vouloir supprimer cette technote?")\'>supprimer</a></span>';
		}

		//pseudo et date	
		echo '<div id="h2"><a href="profil.php?pseudo='.$resultat['pseudo'].'">'.$resultat['pseudo'].'</a></div>
		<a id="h3">  '.$resultat['date_creation'].'</a>';

		//contenu de la technote
		echo manager_texte($resultat['contenu']);	

		echo '</div>';//contenu
		echo '<br/>';

		//lecture des mots clef
		echo '<div id="label_mots_clef"><label>Mots clef</label></div>';

		$req_mots = $bdd->query('SELECT caracterise.idmc AS idmc,mot FROM technote,caracterise,mot_clef
														WHERE ID=id_technote AND caracterise.idmc=mot_clef.idmc AND ID='.$_GET['ID']);
		echo '<div id="mots_clef">';
			foreach($req_mots as $mot)
			{
				echo '<a href="resultat.php?req=mot_clef&idmc='.$mot['idmc'].'" id="mot">'.$mot['mot'].'</a>';
			}
		echo '</div>';


		//lecture des commentaires 

		echo '<div id="label_commentaire"><label>Commentaires</label></div>';

		$req2 = $bdd->query('SELECT IDC,message,pseudo,date FROM commentaire,membre WHERE id_technote='.(int)$_GET['ID'].' AND mail_membre=mail ORDER BY date');
		while($resultat=$req2->fetch())
		{
			echo '<div id="commentaire">';
			if(droit('E1')
				||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
				{
					echo'<span id="droite"><a href="delete.php?statut=commentaire&ID='.$resultat['IDC'].
					'&url=view_technote.php?ID='.$_GET['ID'].'" id="edit" onclick=\'return confirm("Etes vous sur de vouloir supprimer ce commentaire?")\'>supprimer</a></span>';//supprimer commentaire
				}
				echo '<div  id="h2"><a href="profil.php?pseudo='.$resultat['pseudo'].'">'.$resultat['pseudo'].'</a></div>
				<a id="h3">'.$resultat['date'].'</a>';
				echo manager_texte($resultat['message']);
			echo '</div>';//commentaire
			echo '<br/>';
		}

		if(droit('C'))//si on a les droits pour poster un commentaire
		{
			echo '<form action="view_technote.php?ID='.$_GET['ID'].'#fin_page" method="post">';
			echo '<br/><textarea name="message" rows="10" cols="45" placeholder="écrivez un commentaire"></textarea>';
			echo '<br/><input type="submit" value="envoyer">';
			echo '</form>';
		}
		echo '<div id="fin_page"></div>';//une ancre

		echo '</div>';//page
	}

	?>	

	

  </body>
</html>
