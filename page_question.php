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

	if(droit('L'))
	{
		echo '<div id="page">';

		$questions = $bdd->query('SELECT IDQ,date_creation,titre,statut,pseudo FROM question,membre 
															WHERE mail=mail_membre ORDER BY date_creation DESC LIMIT 0,10');

		echo '<br/>';
	
		if(droit('Q')) echo '<a id="n" href="new_page.php?statut=question"><input type="submit" id="newPage" value="nouvelle question"></a>';

		foreach($questions as $question)
		{
			//calcul le nombre de commentaires
			$req_nb_reponses = $bdd->query('SELECT COUNT(*) as nb_rep FROM reponse
			WHERE id_question="'.$question['IDQ'].'"');
			$nb_rep = $req_nb_reponses->fetch();

			echo '<div><a href="question.php?ID='.$question['IDQ'].'" id="post"><span id="droite" style="line-height: 50px;">'.$nb_rep['nb_rep'].' 
			reponses</span>'.$question['date_creation'].' par '.$question['pseudo'].'<br/>'.$question['titre'];
			if(!strcmp($question['statut'],'resolue'))
				echo ' ✔';
			echo '</a></div><br/>';
		}

		if(droit('Q')) echo '<a id="n" href="new_page.php?statut=question"><input type="submit" id="newPage" value="nouvelle question"></a>';

	
		echo '</div>';//page
	}

	?>
  </body>
</html>
