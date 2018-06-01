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

	if(droit('P'))//droit en la consultation de profil d'un membre
	{
		echo '<div id="page" style="padding-left:10px; padding-bottom:10px;">';

			//on récupére les informations du membre
			$req = $bdd->query('SELECT mail,pseudo,date_inscription,nom FROM membre,role WHERE idr=idrole AND pseudo="'.$_GET['pseudo'].'"');
			$resultat = $req->fetch();
			echo '<h2>profil</h2>';
			echo 'pseudo: '.$resultat['pseudo'].'<br/>inscription le '.$resultat['date_inscription'].'<br/>';
			//afficher mail si on a le droit
			if(isset($_COOKIE['staymail']) and $_COOKIE['staymail']==1) echo 'mail:'.$resultat['mail'].'</br>';
			echo 'role: '.$resultat['nom'].'<br/>';

			$req_nb = $bdd->query('SELECT COUNT(*) AS nb_postT FROM technote,membre WHERE mail=mail_ AND pseudo="'.$resultat['pseudo'].'"');
			$req_nb2 = $bdd->query('SELECT COUNT(*) AS nb_postQ FROM question,membre WHERE mail=mail_membre AND pseudo="'.$resultat['pseudo'].'"');

			$res_nb = $req_nb->fetch();
			$res_nb2 = $req_nb2->fetch();
			echo '<a id="h4" href="resultat.php?req=profil_technotes&pseudo='.$_GET['pseudo'].'">'.$res_nb['nb_postT'].' technotes</a><br/>';
			echo '<a id="h4" href="resultat.php?req=profil_questions&pseudo='.$_GET['pseudo'].'">'.$res_nb2['nb_postQ'].' questions</a><br/>';

			if(droit('S') and strcmp($_GET['pseudo'],$_SESSION['pseudo'])!=0)//bouton de supression de compte si administrateur
			{
				echo '
							<form action="delete.php" method="GET">
							<input type="hidden" name="statut" value="membre">
							<input type="hidden" name="pseudo" value='.$_GET['pseudo'].'>
							<br/><input type="submit" value="supprimer" onclick="return confirm(\'Etes vous sur de vouloir supprimer ce membre de Technote?\');">
							</form>';
			}
			?>

		<script>
		
		
		

		
		</script>

		<?php
		echo '</div>';
	}
	?>	

	

  </body>
</html>
