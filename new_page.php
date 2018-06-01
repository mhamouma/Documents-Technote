<!DOCTYPE html>

<?php
session_start();



/*********************************************************************
*création et modification d'une technote, d'une question
*********************************************************************/

?>


<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="style.css">
	<title='Technote'>
  </head>
  <body>

	
	<?php //fonctions dont on aura besoin
	function new_p($statut)
	{
		echo 
		'<form action="new_page.php" method="post">
			<input type="text" cols="70" name="titre" placeholder="titre de votre '.$statut.'" required>
			<br/>
			<textarea name="contenu" rows="20" cols="70" placeholder="contenu de votre '.$statut.'" required></textarea>
			<br/>
			<textarea name="mots_clef" rows="1" cols="70" placeholder="tapez vos mots clefs (mot_clef1 mot_clef2)"></textarea>
			<br/>
			<input type="submit" name="new'.$statut.'" value="envoyer">
		</form>';
	}

	function edit_p($statut,$titre,$contenu,$ID,$mots_clef)
	{
		echo 
		'<form action="new_page.php?ID='.$ID.'" method="post">
			<input type="text" name="titre" value="'.$titre.'" placeholder="titre de votre '.$statut.'" required>
			<br/>
			<textarea name="contenu" rows="20" cols="70" placeholder="contenu de votre '.$statut.'" required>'.$contenu.'</textarea>
			<br/>
			<input type="submit" name="edit'.$statut.'" value="envoyer">
			<textarea name="mots_clefs" rows="1" cols="70" placeholder="ajoutez des mots clés (mot_clef1 mot_clef2)">'.$mots_clef.'</textarea>
			<br/>
		</form>';
	}

	function manag_motclefs_t($mots_clef,$titre,$id_technote,$bdd)
	{

		//récupérer les mots clefs dans un tableau
		$tab = explode(" ",$mots_clef);
		$tab=array_merge($tab,explode(" ",$titre));//on enregistre également chaque mot du titre comme mot clef

		//on parcourt tous les mots clefs que l'on vient d'extraire
		foreach($tab as $e)
		{
			//vérifier si le mot clef n'existe pas déja sinon le rajouter dans la bdd
			$req1 = $bdd->query('SELECT idmc FROM mot_clef WHERE mot="'.$e.'"');

			if($mc=$req1->fetch())//le mot clef n'existe pas dans la bdd
			{
				if(preg_match("#\S#",mot_clef))
				{
					//vérifier si le mot clef n'a pas déja été enregistré dans cette technote
					$req2 = $bdd->query('SELECT * FROM caracterise WHERE idmc = '.$mc['idmc'].' AND id_technote='.$id_technote);

					//on l'ajoute a la bdd si elle n'a pas déja été rajouté
					if(!($reponse_req2 = $req2->fetch()))
					{
						$req2 = $bdd->prepare('INSERT INTO caracterise(idmc,id_technote) VALUES(:mot_clef,:technote)');
						$req2->execute(array('mot_clef' => $mc['idmc'],'technote'=>$id_technote));
					}
				}
			}
			else//le mot clef existe déja dans la bdd
			{
				if(preg_match("#\S#",mot_clef))
				{
					$req2 = $bdd->prepare('INSERT INTO mot_clef(mot) VALUES(:mot)');
					$req2->execute(array('mot' => $e));
					$req3 = $bdd->query('SELECT idmc FROM mot_clef WHERE mot="'.$e.'"');//on récupére l'id du mot_clef que l'on vient de rentrer
					$rep = $req3->fetch();
					//comme le mot n'existait pas on fait directement le lien entre le mot et la technote
					$req4 = $bdd->prepare('INSERT INTO caracterise(idmc,id_technote) VALUES(:mot_clef,:technote)');
					$req4->execute(array('mot_clef' => $rep['idmc'],'technote'=>$id_technote));
				}
			}

			//vérifier si des mots clef ont été supprimé en faisant une boucle pour chaque élémenent dans caracterise qui contient mot
			//les supprimer dans le cas ou il en contienne
			$req = $bdd->query('SELECT caracterise.idmc,mot FROM caracterise,mot_clef WHERE caracterise.id_technote='.$id_technote.' AND caracterise.idmc = mot_clef.idmc;');
			while($mot = $req->fetch())
			{
				$n = 0;
				foreach($tab as $e)
				{	
					if(strcmp($mot['mot'],$e)==0) $n++;
				}
				if($n==0)//si ce mot ne fait plus partie des mots_clefs
				{
					//supprimer le mot de la bdd
					$delete_mot = $bdd->exec('DELETE FROM caracterise WHERE idmc='.$mot['idmc'].' AND id_technote='.$id_technote);
				}
			}
		}
	}

function manag_motclefs_q($mots_clef,$titre,$id_question,$bdd)
	{

		//récupérer les mots clefs dans un tableau
		$tab = explode(" ",$mots_clef);
		$tab=array_merge($tab,explode(" ",$titre));//on enregistre également chaque mot du titre comme mot clef

		//on parcourt tous les mots clefs que l'on vient d'extraire
		foreach($tab as $e)
		{
			//vérifier si le mot clef n'existe pas déja sinon le rajouter dans la bdd
			$req1 = $bdd->query('SELECT idmc FROM mot_clef WHERE mot="'.$e.'"');
			if($mc=$req1->fetch())//le mot clef n'existe pas dans la bdd
			{
				if(preg_match("#\S#",mot_clef))
				{
					//vérifier si le mot clef n'a pas déja été enregistré dans cette question
					$req2 = $bdd->query('SELECT * FROM caracterise WHERE idmc = '.$mc['idmc'].' AND id_question='.$id_question);

					//on l'ajoute a la bdd si elle n'a pas déja été rajouté
					if(!($reponse_req2 = $req2->fetch()))
					{
						$req2 = $bdd->prepare('INSERT INTO caracterise(idmc,id_question) VALUES(:mot_clef,:question)');
						$req2->execute(array('mot_clef' => $mc['idmc'],'question'=>$id_question));
					}
				}
			}
			else//le mot clef existe déja dans la bdd
			{
				if(preg_match("#\S#",mot_clef))
				{
					$req2 = $bdd->prepare('INSERT INTO mot_clef(mot) VALUES(:mot)');
					$req2->execute(array('mot' => $e));
					$req3 = $bdd->query('SELECT idmc FROM mot_clef WHERE mot="'.$e.'"');//on récupére l'id du mot_clef que l'on vient de rentrer
					$rep = $req3->fetch();
					//comme le mot n'existait pas on fait directement le lien entre le mot et la question
					$req4 = $bdd->prepare('INSERT INTO caracterise(idmc,id_question) VALUES(:mot_clef,:question)');
					$req4->execute(array('mot_clef' => $rep['idmc'],'question'=>$id_question));
				}
			}

			//vérifier si des mots clef ont été supprimé en faisant une boucle pour chaque élémenent dans caracterise qui contient mot
			//les supprimer dans le cas ou il en contienne
			$req = $bdd->query('SELECT caracterise.idmc,mot FROM caracterise,mot_clef WHERE caracterise.id_question='.$id_question.' AND caracterise.idmc = mot_clef.idmc;');
			while($mot = $req->fetch())
			{
				$n = 0;
				foreach($tab as $e)
				{	
					if(strcmp($mot['mot'],$e)==0) $n++;
				}
				if($n==0)//si ce mot ne fait plus partie des mots_clefs
				{
					//supprimer le mot de la bdd
					$delete_mot = $bdd->exec('DELETE FROM caracterise WHERE idmc='.$mot['idmc'].' AND id_question='.$id_question);
				}
			}
		}
	}


	?>

	<?php
	include('header.php');//l'en-tête

	if(isset($_POST['titre']) and isset($_POST['contenu']))//si on a déja un contenu a rajouter a la bdd
	{
		if(isset($_POST['newtechnote']))//nouvelle technote
		{
			$req = $bdd->prepare('INSERT INTO technote(mail_,titre,contenu,date_creation) VALUES(:mail,:titre,:contenu,NOW())');
			$req->execute(array(
			'mail' => $_SESSION['mail'],
			'titre' => $_POST['titre'],
			'contenu' => $_POST['contenu']));
			
			//on récupére l'id de la technote ajouté
			$req2 = $bdd->query('SELECT ID FROM technote WHERE mail_="'.$_SESSION['mail'].'" AND titre="'.$_POST['titre'].'" AND date_creation=NOW()');
			$id = $req2->fetch();
			
			//les mots clés sont rentrés dans la base de donnée
			manag_motclefs_t($_POST['mots_clef'],$_POST['titre'],$id['ID'],$bdd);
			header("location:view_technote.php?ID=".$id['ID']);
			
			
		}
		
		if(isset($_POST['newquestion']))//nouvelle question
		{	
			echo $_SESSION['mail'].' '.$_POST['titre'].' '.$_POST['contenu'];
			$req = $bdd->prepare('INSERT INTO question(mail_membre,titre,contenu,date_creation,statut) VALUES(:mail,:titre,:contenu,NOW(),:statut)');
			$req->execute(array(
			'mail' => $_SESSION['mail'],
			'titre' => $_POST['titre'],
			'contenu' => $_POST['contenu'],
			'statut' => 'non resolue'));

			//on récupére l'id de la question ajouté
			$req2 = $bdd->query('SELECT IDQ FROM question WHERE mail_membre="'.$_SESSION['mail'].'" AND titre="'.$_POST['titre'].'" AND date_creation=NOW()');
			$id = $req2->fetch();
				
			manag_motclefs_q($_POST['mots_clef'],$_POST['titre'],$id['IDQ'],$bdd);
			header("location:question.php?ID=".$id['IDQ']);
		}

		if(isset($_POST['edittechnote']))
		{
			$req = $bdd->prepare('UPDATE technote SET titre=:titre, contenu=:contenu WHERE ID=:ID');
			
			$req->execute(array(
			'titre' => $_POST['titre'],
			'contenu' => $_POST['contenu'],
			'ID' => $_GET['ID']));
			
			manag_motclefs_t($_POST['mots_clefs'],$_POST['titre'],$_GET['ID'],$bdd);
			header("location:view_technote.php?ID=".$_GET['ID']);
		}
	
		if(isset($_POST['editquestion']))
		{
			$req = $bdd->prepare('UPDATE question SET titre=:titre, contenu=:contenu WHERE IDQ=:ID');
			
			$req->execute(array(
			'titre' => $_POST['titre'],
			'contenu' => $_POST['contenu'],
			'ID' => $_GET['ID']));
			echo "La question a été modifié!";

			header("location:question.php?ID=".$_GET['ID']);
		}
		
	}
	else //sinon on va devoir taper le contenu
	{
		if(isset($_GET['statut']) and ($_GET['statut']=='technote' or $_GET['statut']=='question'))//si on veut créer une nouvelle technote 															ou une nouvelle question 
		{
			new_p($_GET['statut']);
		}
		
		if(isset($_GET['statut']) and $_GET['statut']=='edit_technote')//pour modifier une technote
		{
			//on récupére les donées de la technote
			$req = $bdd->query('SELECT titre,contenu FROM technote WHERE ID='.(int)$_GET['ID']);
			$res = $req->fetch();

			//on récupére les mots clef dans une chaine
			$mots_clef = "";
			$req = $bdd->query('SELECT mot FROM mot_clef,caracterise WHERE id_technote='.$_GET['ID'].' AND caracterise.idmc=mot_clef.idmc');
			$mc = $req->fetch();
			$mots_clef = $mc['mot'];
			while($mc=$req->fetch())
			{
				$mots_clef .= " ".$mc['mot'];
			}


			//on affiche la page
			edit_p('technote',$res['titre'],$res['contenu'],$_GET['ID'],$mots_clef);
		}

		if(isset($_GET['statut']) and $_GET['statut']=='edit_question')//pour modifier une technote
		{
			//rajouter des parametres
			$req = $bdd->query('SELECT titre,contenu FROM question WHERE IDQ='.(int)$_GET['ID']);
			$res = $req->fetch();

			//on récupére les mots clef dans une chaine
			$mots_clef = "";
			$req = $bdd->query('SELECT mot FROM mot_clef,caracterise WHERE id_question='.$_GET['ID'].' AND caracterise.idmc=mot_clef.idmc');

			$mc = $req->fetch();
			$mots_clef = $mc['mot'];
			while($mc=$req->fetch())
			{
				$mots_clef .= " ".$mc['mot'];
			}

			edit_p('question',$res['titre'],$res['contenu'],$_GET['ID'],$mots_clef);
		}
		
	?>	
	
	<?php } ?>

	

  </body>
</html>
