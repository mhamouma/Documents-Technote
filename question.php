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

	//ajouter une réponse
	if(isset($_GET['type']) and !strcmp($_GET['type'],'reponse') and isset($_POST['message']))
	{
		$ajout = $bdd->prepare('INSERT INTO reponse(id_question,message,mail_membre,date) VALUES(:id,:message,:mail,NOW())');
			$ajout->execute(array(
			'id' => $_GET['ID'],
			'message' => $_POST['message'],
			'mail' => $_SESSION['mail']));
	}

	if(isset($_GET['type']) and !strcmp($_GET['type'],"commentaire") and isset($_POST['message']))
	{
		$ajout = $bdd->prepare('INSERT INTO commentaire(id_reponse,message,mail_membre,date) VALUES(:id,:message,:mail,NOW())');
		$ajout->execute(array(
		'id' => $_GET['IDR'],
		'message' => $_POST['message'],
		'mail' => $_SESSION['mail']));
	}

	echo '<input type="hidden" id="id" value='.$_GET['ID'].'>';//id caché
 

	if(droit('L'))//droit en lecture
	{
		echo '<div id="page">';
	
		$req = $bdd->query('SELECT titre,contenu,date_creation,pseudo,statut FROM question,membre WHERE mail = mail_membre AND IDQ='.(int)$_GET['ID']);
		$resultat = $req->fetch();


		echo '<br/><h2>Sujet: '.$resultat['titre'].'</h2>';
		if(!strcmp($resultat['statut'],'resolue'))
			echo '<div id="statut">✔ résolue</div>';
		if((droit('E1')
			||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo'])) and !strcmp($resultat['statut'],'non resolue'))
			echo '<div id="statut"><a href="#" id="edit">marquer comme résolue</a></div>';

		echo '<div id="contenu_question">';

			//edit
			if(droit('E1')
			||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
			{
				echo'<span id="droite"><a href="new_page.php?statut=edit_question&ID='.$_GET['ID'].'" id="edit">modifier</a>  ';
				echo'<a href="delete.php?statut=question&ID='.$_GET['ID'].'" id="edit" onclick=\'return confirm("Etes vous sur de vouloir supprimer cette question?")\'>supprimer</a></span>';
			}

			//pseudo/date
			echo '<div id="h2"><a href="profil.php?pseudo='.$resultat['pseudo'].'">'.$resultat['pseudo'].'</a></div>  '.'<a id="h3">'.$resultat['date_creation'].'</a>';

			//contenu
			echo manager_texte($resultat['contenu']);
		echo '</div>';
		echo '<br/>';

		//------------------------lecture des mots clef---------------------------------------------------
		echo '<div id="label_mots_clef"><label>Mots clef</label></div>';

		$req_mots = $bdd->query('SELECT caracterise.idmc AS idmc,mot FROM question,caracterise,mot_clef
														WHERE IDQ=id_question AND caracterise.idmc=mot_clef.idmc AND IDQ='.$_GET['ID']);
		echo '<div id="mots_clef">';
			foreach($req_mots as $mot)
			{
				echo '<a href="resultat.php?req=mot_clef&idmc='.$mot['idmc'].'" id="mot">'.$mot['mot'].'</a>';
			}
		echo '</div>';

		
		//----------------------------------lecture des réponses--------------------------------------------------

		echo '<div id="label_reponse"><label>Réponses</label></div>';

		$req2 = $bdd->query('SELECT reponse.IDR as IDR,message,pseudo,date FROM reponse,membre WHERE id_question='.(int)$_GET['ID'].' AND mail_membre=mail ORDER BY date');
		while($resultat=$req2->fetch())
		{
			echo '<div id="reponse">';
				if(droit('E1')
				||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
				{
					echo'<span id="droite"><a href="delete.php?statut=reponse&ID='.$resultat['IDR'].
					'&url=question.php?ID='.$_GET['ID'].'" id="edit" onclick=\'return confirm("Etes vous sur de vouloir supprimer cette réponse?")\'>supprimer</a></span>';//supprimer réponse
				}
				echo '<div id="h2"><a href="profil.php?pseudo='.$resultat['pseudo'].'">'.$resultat['pseudo'].'</a></div> <a id="h3"> '.$resultat['date'].'</a>';
				echo manager_texte($resultat['message']);
				echo '<br/><a id="com" href="#" onclick="javascript:visibilite('.$resultat['IDR'].'); return false;"">commentaires</a>
							';//marquer le nombre de commentaires et changer l'id
			echo '</div>';
			echo '<br/>';
		
				//afficher les commentaires des réponses
				$req3 = $bdd->query('SELECT commentaire.IDC as IDC,message,pseudo,date FROM commentaire,membre WHERE id_reponse='.(int)$resultat['IDR'].
				' AND mail_membre=mail ORDER BY date');
				echo '<div id="commentaire_reponse'.$resultat['IDR'].'" style="display:none;">';
					while($commentaire = $req3->fetch())
					{
						echo '<div id="commentaire">';
							if(droit('E1')
							||(droit('E2') and $_SESSION['pseudo']==$resultat['pseudo']))
							{
								echo'<span id="droite"><a href="delete.php?statut=commentaire&ID='.$commentaire['IDC'].
								'&url=reponse.php?ID='.$resultat['IDR'].'" id="edit" onclick=\'return 
								confirm("Etes vous sur de vouloir supprimer ce commentaire?")\'>supprimer</a></span>';//supprimer réponse
							}
							echo '<div id="h2"><a href="profil.php?pseudo='.$commentaire['pseudo'].'">'.$commentaire['pseudo']
							.'</a></div> <a id="h3"> '.$commentaire['date'].'</a>';
							echo manager_texte($commentaire['message']);
						echo '</div>';
						echo '<br/>';
					}
					//textarea pour poster un commentaire a une réponse
					if(droit('C'))//si on a les droits pour poster un commentaire
					{
						echo '<form action="question.php?ID='.$_GET['ID'].'&type=commentaire&IDR='.$resultat['IDR'].'#fin_page" method="post">';
						echo '<br/><textarea name="message" id="message" rows="1" cols="45" placeholder="écrivez un commentaire"></textarea>';
						//mettre un marqueure sur le textarea
						echo '<br/><input type="submit" value="envoyer">';
						echo '</form>';
						echo '<br/>';
					}
					echo '<br/>';
				echo '</div>';//commmentaire_reponse

		}

		if(droit('Q'))//si on a les droits pour poster une réponse
		{
			echo '<div id="text"><form action="question.php?type=reponse&ID='.$_GET['ID'].'#fin_page" method="post">';
			echo '<br/><textarea name="message" rows="10" cols="45" placeholder="écrivez une réponse"></textarea>';
			echo '<br/><input type="submit" value="envoyer">';
			echo '</form></div>';
		}
		echo '<div id="fin_page"></div>';//une ancre

		echo "</div>";
	}
	?>	
	
	<script>

	document.getElementById('statut').addEventListener('click',function()
	{
		if(confirm('etes vous sur?'))
			marquerResolu();		
	});

	function marquerResolu()
	{
		var id = document.getElementById('id');
		var xhr = new XMLHttpRequest();
		xhr.open('GET','question_serveur.php?action=resolu&id='+id.value);
		xhr.addEventListener("readystatechange",function()
				{
					//donées récéptionées
					if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200)
					{
						window.location.reload();
					}
				});

		xhr.send(null);

	}

	//envoyer une requète pour les nouveaux messages qui renverra ce qui sera affiché par la suite!
	//puis créer une nouvelle div avec un id message dans la div commentaire_reponse

	//faire afficher les commentaire des réponses
	function visibilite(id)
	{
		var id_com = document.getElementById('commentaire_reponse'+id);
		if(id_com.style.display=="none")
		{
			id_com.style.display="";
			id_com.style.marginLeft = "35px";
		}
		else
			id_com.style.display="none";
	}

	function commente(id)
	{
		visibilite(id);
		//donner un id au textarea
		//mettre le focus sur le textarea
	}

	
	</script>
	

  </body>
</html>
