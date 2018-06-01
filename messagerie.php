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

	if(droit('M',true))
	{
		echo '<div id=\'page\'>';
			echo '<h2>messagerie</h2>';
			echo '<a id="a_bouton" href="message.php"><button id="bouton_msg">nouveau message</button></a>';
			echo '<br/></br/>';

			//-------------affichage des des blocs de messages----------------

			//on récupére les messages que l'on a écrit
			$req_envoi = $bdd->query('SELECT pseudo,mail_dest FROM message,membre
																WHERE mail_dest = mail AND mail_exp="'.$_SESSION['mail'].'"
																GROUP BY mail_dest
																ORDER BY date');
			$envoi = array();
			foreach($req_envoi as $res_envoi)
			{
				$envoi[] = $res_envoi['pseudo'];
			}

			//on récupére les messages qu'on nous a écrit
			$req_recoit = $bdd->query('SELECT pseudo,mail_exp FROM message,membre
																WHERE mail_exp = mail AND mail_dest="'.$_SESSION['mail'].'"
																GROUP BY mail_exp
																ORDER BY date');
			$recoit = array();
			foreach($req_recoit as $res_recoit)
			{
				$recoit[] = $res_recoit['pseudo'];
			}

			//fusion des deux tableaux en ne laissant pas de doublons
			$bloc = $envoi + $recoit;

			//affichage
			foreach($bloc as $b)
			{
				echo '<div id="msg_view"><a style="text-decoration:none;color:black;" href="message.php?m='.$b.'">'.$b.'</a></div>';
			}

			echo '<br/>';

		echo '</div>';//page
	}

	?>	

	

  </body>
</html>
