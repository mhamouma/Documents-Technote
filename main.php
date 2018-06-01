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

			$technotes = $bdd->query('SELECT ID,date_creation,titre,pseudo FROM technote,membre WHERE mail=mail_ ORDER BY date_creation DESC LIMIT 0,10');

			echo '<br/>';
			if(droit('Q')) echo '<a id="n" href="new_page.php?statut=technote"><input type="submit" id="newPage" value="nouvelle technote"></a>';
			foreach($technotes as $technote)
			{
				//calcul le nombre de commentaires
				$req_nb_commentaires = $bdd->query('SELECT COUNT(*) as nb_com FROM commentaire
				WHERE id_technote="'.$technote['ID'].'"');
				$nb_commentaires = $req_nb_commentaires->fetch();	
		
				echo '<div><a href="view_technote.php?ID='.$technote['ID'].'" id="post" >
							<span id="droite" style="line-height: 50px;">'.$nb_commentaires['nb_com'].' 
							commentaires</span>'.$technote['date_creation'].' par '.$technote['pseudo'].'<br/>'.$technote['titre'].'</a></div><br/>';
			};

			if(droit('Q')) echo '<a id="n" href="new_page.php?statut=technote"><input type="submit" id="newPage" value="nouvelle technote"></a>';

		echo '</div>';//id=page
	}
	?>
  </body>
</html>
