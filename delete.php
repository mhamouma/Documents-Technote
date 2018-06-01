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

	/*********************************************************************************************************
	******vérifier les droits de suppression également dans cette page****************************************
	*********************************************************************************************************/

	//supprimer technote
	if(isset($_GET['statut']) and $_GET['statut']=='technote')
	{	
		//on supprime les commentaires de la technote
		$bdd->exec('DELETE FROM commentaire WHERE id_technote='.(int)$_GET['ID']);

		//pour chaque mot_clef appartenant a la technote vérifier si la technote est la seule qui le contenait
		$req2 = $bdd->query('SELECT idmc FROM caracterise WHERE id_technote='.(int)$_GET['ID']);
		foreach($req2 as $e)
		{
			$r = $bdd->query('SELECT COUNT(*) as nb FROM caracterise WHERE idmc='.$e['idmc']);
			$nb = $r->fetch();
			if($nb['nb']==1)//si c'est le cas on supprime le mot clef de la base de donnée
			{
				$r2 = $bdd->exec('DELETE FROM mot_clef WHERE idmc='.$e['idmc']);
			}
		}
		
		//supprimer liens entre mots clefs et la technote dans caracterise
		$bdd->exec('DELETE FROM caracterise WHERE id_technote='.(int)$_GET['ID']);

		//on supprime enfin la technote
		$bdd->exec('DELETE FROM technote WHERE ID='.(int)$_GET['ID']);

		header('Location:main.php');
	}
	
	//supprimer question
	if(isset($_GET['statut']) and $_GET['statut']=='question')
	{
		//on supprime les commntaires de toutes les réponses de la question
		$bdd->exec('DELETE FROM reponse r,commentaire c WHERE id_reponse=IDR AND id_question='.$_GET['ID']);

		//on suuprime les réponses de la question
		$bdd->exec('DELETE FROM reponse r WHERE id_question='.$_GET['ID']);

		//pour chaque mot_clef appartenant a la technote vérifier si la question est la seule qui le contenait
		$req2 = $bdd->query('SELECT idmc FROM caracterise WHERE id_question='.(int)$_GET['ID']);
		foreach($req2 as $e)
		{
			$r = $bdd->query('SELECT COUNT(*) as nb FROM caracterise WHERE idmc='.$e['idmc']);
			$nb = $r->fetch();
			if($nb['nb']==1)//si c'est le cas on supprime le mot clef de la base de donnée
			{
				$bdd->exec('DELETE FROM mot_clef WHERE idmc='.$e['idmc']);
			}
		}
		
		//supprimer liens entre mots clefs et la question dans caracterise
		$bdd->exec('DELETE FROM caracterise WHERE id_question='.(int)$_GET['ID']);

		$bdd->exec('DELETE FROM question WHERE IDQ='.(int)$_GET['ID']);//on supprime enfin la question
		header('Location:page_question.php');
	}

	//supprimer réponse
	if(isset($_GET['statut']) and $_GET['statut']=='reponse')
	{
		//supprimer les commentaires de la réponse
		$bdd->exec('DELETE FROM commentaire WHERE id_reponse='.$_GET['ID']);
		$bdd->exec('DELETE FROM reponse WHERE IDR='.(int)$_GET['ID']);
		header('Location:'.$_GET['url']);
	}

	//supprimer commentaire
	if(isset($_GET['statut']) and $_GET['statut']=='commentaire')
	{
		$bdd->exec('DELETE FROM commentaire WHERE IDC='.(int)$_GET['ID']);
		header('Location:'.$_GET['url']);
	}

	//supprimer un membre
	if(isset($_GET['statut']) and $_GET['statut']=='membre')
	{
		$req = $bdd->query('SELECT mail FROM membre WHERE pseudo="'.$_GET['pseudo'].'"');
		$res = $req->fetch();
		$mail = $res['mail'];

		//rechercher toutes les technotes, questions et réponses de cet utilisateur
		$bdd->exec('UPDATE technote SET mail_="membre_supprime"  WHERE (mail_="'.$mail.'") ');
		$bdd->exec('UPDATE question SET mail_membre="membre_supprime" WHERE mail_membre="'.$mail.'"');
		$bdd->exec('UPDATE reponse SET mail_membre="membre_supprime" WHERE mail_membre="'.$mail.'"');

		//supprimer tous les commentaires émis par cet utilisateur
		$bdd->exec('DELETE FROM commentaire WHERE mail_membre="'.$mail.'"');

		//on supprime le membre de la bdd
		$bdd->exec('DELETE FROM membre WHERE mail="'.$mail.'"');
		header('Location:main.php');
	}

	?>	

	

  </body>
</html>
