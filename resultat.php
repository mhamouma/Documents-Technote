<!DOCTYPE html>

<?php
session_start();
//--------------------------------------------afficher a chaque fois le nombres de commentaires et de réponses
?>


<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="style.css">
	<title='Technote'>
  </head>
  <body>

	<?php
	include('header.php');

	function display_req_search($mots,$tab_pr,$bdd)
	{
		//on lance la recherche sur un mot qu'on mettra ensuite dans un array 
		//si espace faire un ou : rasembler tous les mots qu'on a récupérer 
		// sinon récupérer tous les mots et rédupérer les technotes / ou questions qui on tous les mots rassemblés...

		
		//requête pour trouver les technotes
		$my_req_t = "SELECT DISTINCT ID,titre,date_creation,pseudo FROM technote,caracterise,mot_clef,membre
								WHERE caracterise.idmc = mot_clef.idmc AND mail=mail_ AND ID=id_technote AND (";
		for($i=0;$i<count($mots)-1;$i++)
		{
			$my_req_t .= 'mot LIKE "'.$mots[$i].'" OR ';
		}
		$my_req_t .= 'mot LIKE "'.$mots[count($mots)-1].'") ORDER BY date_creation DESC';

		//requête pour trouver les questions
		$my_req_q = "SELECT DISTINCT IDQ,titre,date_creation,statut,pseudo FROM question,caracterise,mot_clef,membre 
								WHERE caracterise.idmc = mot_clef.idmc AND mail=mail_membre AND IDQ=id_question AND (";
		for($i=0;$i<count($mots)-1;$i++)
		{
			$my_req_q .= 'mot LIKE "'.$mots[$i].'" OR ';
		}
		$my_req_q .= 'mot LIKE "'.$mots[count($mots)-1].'") ORDER BY date_creation DESC';

		//on lance la requète en fonction des mots_clé
		$req_technotes = $bdd->query($my_req_t);
		$req_questions = $bdd->query($my_req_q);


		//on récupére les données des requêtes dans des tableaux pour les trier par date

		//données des technotes
		$T = array();
		if(isset($_POST['dans_technote']))
		{
			while($technote = $req_technotes->fetch())
			{
				//on recuépre tous les mots clefs
				$array_t = array();
				$req_mc = $bdd->query('SELECT mot FROM caracterise,mot_clef WHERE caracterise.idmc=mot_clef.idmc AND id_technote='.$technote['ID']);
				foreach($req_mc as $mc)
				{
					$array_t[] = $mc['mot'];
				}

				//on vérifie que la technote ait tous les mots clés préfixés dans la recherche
				$ok = true;
				foreach($tab_pr as $prefixe)
				{
					if(!in_array($prefixe,$array_t))
						$ok = false;
				}
				if($ok)
				{
					$T[] = array($technote['ID'],$technote['date_creation'],$technote['titre'],$array_t,$technote['pseudo']);
				}
			}
		}

		//données des questions
		$Q = array();
		if(isset($_POST['dans_question']))
		{
			while($question = $req_questions->fetch())
			{
				if((!strcmp($question['statut'],"resolue") and isset($_POST['resolue']))
				or(!strcmp($question['statut'],"non resolue") and isset($_POST['non_resolue'])))
				{
					//on recuépre tous les mots clefs
					$array_q = array();
					$req_mc = $bdd->query('SELECT mot FROM caracterise,mot_clef WHERE caracterise.idmc=mot_clef.idmc AND id_question='.$question['IDQ']);
					foreach($req_mc as $mc)
					{
						$array_q[] = $mc['mot'];
					}

					//on vérifie que la technote ait tous les mots clés préfixés dans la recherche
					$ok = true;
					foreach($tab_pr as $prefixe)
					{
						if(!in_array($prefixe,$array_q))
							$ok = false;
					}
					if($ok)
						$Q[] = array($question['IDQ'],$question['date_creation'],$question['titre'],$array_q,$question['statut'],$question['pseudo']);
				}
			}
		}


		//on affiche les technotes et question dnas l'ordre de leurs dates

		$i1 = 0;$i2 = 0;
		
		while($i1<count($T) and $i2<count($Q))
		{
			$date_t = date($T[$i1][1]); $date_q = date($Q[$i2][1]);

			$date_t = new DateTime($date_t);
			$date_q = new DateTime($date_q);

			$date_t = $date_t->format('Y-m-d H:i:s');
			$date_q = $date_q->format('Y-m-d H:i:s');

			if($date_t<$date_q)
			{
				echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][1].' par '.$Q[$i2][5].'<br/>'.$Q[$i2][2];
				if(!strcmp($Q[$i2][4],"resolue"))
					echo ' ✔';
				echo '</a></div><br/>';
				$i2 = $i2+1;
			}
			else
			{
				echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][1].' par '.$T[$i1][4].'<br/>'.$T[$i1][2].'</a></div><br/>';
				$i1 = $i1+1;
			}
		}
		if($i1 == count($T))
		{
			while($i2<count($Q))
			{
				echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][1].' par '.$Q[$i2][5].'<br/>'.$Q[$i2][2];
				if(!strcmp($Q[$i2][4],"resolue"))
					echo ' ✔';
				echo '</a></div><br/>';
				$i2 = $i2+1;
			}
		}
		else
		{
			while($i1<count($T))
			{
				echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][1].' par '.$T[$i1][4].'<br/>'.$T[$i1][2].'</a></div><br/>';
				$i1 = $i1+1;
			}
		}

	}

	echo '<div id="page">';

		echo '<br/>';
		if(isset($_GET['req']) and !strcmp($_GET['req'],"recherche"))//si c'est le résultat d'une recherche
		{
			//___________________________________________recherche par mots clef_______________________________________________________
			if(isset($_POST['par']) and !strcmp($_POST['par'],"mots clefs"))//recherche de mots clefs
			{
				//on rassemble chaque mots dans un tableau
				$tab_mots = explode(' ',$_POST['search']);
				$tab_prefixes = array();
				for($i=0;$i<count($tab_mots);$i++)
				{
					
					if(!strcmp($tab_mots[$i][0],'+'))
					{
						$tab_prefixes[] = substr($tab_mots[$i],1);
						$tab_mots[$i] = substr($tab_mots[$i],1);
					}
					
				}
			display_req_search($tab_mots,$tab_prefixes,$bdd);
			}

			//___________________________________________recherche par auteurs_______________________________________________________
			if(isset($_POST['par']) and !strcmp($_POST['par'],"auteur"))
			{
				$auteur = $_POST['search'];
				
				//requète pour les technotes
				$req_technotes = $bdd->query('SELECT DISTINCT ID,titre,date_creation,pseudo FROM technote,membre 
																			WHERE mail=mail_ AND pseudo ="'.$_POST['search'].'" ORDER BY date_creation DESC');

				$T = array();
				if(isset($_POST['dans_technote']))
				{
					while($res_technote = $req_technotes->fetch())
					{
						//nombre de commentaires
						$T[] = array($res_technote['ID'],$res_technote['titre'],$res_technote['date_creation'],$res_technote['pseudo']);
					}
				}

				//requête pour les questions
				$req_questions = $bdd->query('SELECT DISTINCT IDQ,titre,date_creation,pseudo,statut FROM question,membre 
																			WHERE mail=mail_membre AND pseudo ="'.$_POST['search'].'" ORDER BY date_creation DESC');

				$Q = array();
				if(isset($_POST['dans_question']))
				{
					while($res_question = $req_questions->fetch())
					{
						if((!strcmp($res_question['statut'],"resolue") and isset($_POST['resolue']))
						or(!strcmp($res_question['statut'],"non resolue") and isset($_POST['non_resolue'])))
						{
							//nombre de commentaires
							$Q[] = array($res_question['IDQ'],$res_question['titre'],$res_question['date_creation'],
														$res_question['pseudo'],$res_question['statut']);
						}
					}
				}

				//on écrit les technotes et questions dans l'ordre de leurs dates
				$i1 = 0;$i2 = 0;
				while($i1<count($T) and $i2<count($Q))
				{
					$date_t = date($T[$i1][2]); $date_q = date($Q[$i2][2]);

					$date_t = new DateTime($date_t);
					$date_q = new DateTime($date_q);

					$date_t = $date_t->format('Y-m-d H:i:s');
					$date_q = $date_q->format('Y-m-d H:i:s');

					if($date_t<$date_q)
					{
						echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][2].' par '.$Q[$i2][3].'<br/>'.$Q[$i2][1];
						if(!strcmp($Q[$i2][4],"resolue"))
							echo ' ✔';
						echo '</a></div><br/>';
						$i2 = $i2+1;
					}
					else
					{
						echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][2].' par '.$T[$i1][3].'<br/>'.$T[$i1][1].'</a></div><br/>';
						$i1 = $i1+1;
					}
				}
				if($i1 == count($T))
				{
					while($i2<count($Q))
					{
						echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][2].' par '.$Q[$i2][3].'<br/>'.$Q[$i2][1];
						if(!strcmp($Q[$i2][4],"resolue"))
							echo ' ✔';
						echo '</a></div><br/>';
						$i2 = $i2+1;
					}
				}
				else
				{
					while($i1<count($T))
					{
						echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][2].' par '.$T[$i1][3].'<br/>'.$T[$i1][1].'</a></div><br/>';
						$i1 = $i1+1;
					}
				}
				
			}

			//___________________________________________recherche par dates_______________________________________________________
			if(isset($_POST['par']) and !strcmp($_POST['par'],"date"))//recherche par date
			{
				$auteur = $_POST['search'];
				
				//requète pour les technotes
				$req_technotes = $bdd->query('SELECT DISTINCT ID,titre,date_creation,pseudo FROM technote,membre 
																			WHERE mail=mail_ AND date_creation BETWEEN "'.$_POST['search'].' 00:00:00" AND "'.$_POST['search'].' 23:59:59" 
																			ORDER BY date_creation DESC');

				$T = array();
				if(isset($_POST['dans_technote']))
				{
					while($res_technote = $req_technotes->fetch())
					{
						//nombre de commentaires
						$T[] = array($res_technote['ID'],$res_technote['titre'],$res_technote['date_creation'],$res_technote['pseudo']);
					}
				}

				//requête pour les questions
				$req_questions = $bdd->query('SELECT DISTINCT IDQ,titre,date_creation,pseudo,statut FROM question,membre 
																			WHERE mail=mail_membre AND date_creation BETWEEN "'.$_POST['search'].' 00:00:00" 
																			AND "'.$_POST['search'].' 23:59:59" 
																			ORDER BY date_creation DESC');

				$Q = array();
				if(isset($_POST['dans_question']))
				{
					while($res_question = $req_questions->fetch())
					{
						if((!strcmp($res_question['statut'],"resolue") and isset($_POST['resolue']))
						or(!strcmp($res_question['statut'],"non resolue") and isset($_POST['non_resolue'])))
						{
							//nombre de commentaires
							$Q[] = array($res_question['IDQ'],$res_question['titre'],$res_question['date_creation'],
														$res_question['pseudo'],$res_question['statut']);
						}
					}
				}

				//on écrit les technotes et questions dans l'ordre de leurs dates
				$i1 = 0;$i2 = 0;
				while($i1<count($T) and $i2<count($Q))
				{
					$date_t = date($T[$i1][2]); $date_q = date($Q[$i2][2]);

					$date_t = new DateTime($date_t);
					$date_q = new DateTime($date_q);

					$date_t = $date_t->format('Y-m-d H:i:s');
					$date_q = $date_q->format('Y-m-d H:i:s');

					if($date_t<$date_q)
					{
						echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][2].' par '.$Q[$i2][3].'<br/>'.$Q[$i2][1];
						if(!strcmp($Q[$i2][4],"resolue"))
							echo ' ✔';
						echo '</a></div><br/>';
						$i2 = $i2+1;
					}
					else
					{
						echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][2].' par '.$T[$i1][3].'<br/>'.$T[$i1][1].'</a></div><br/>';
						$i1 = $i1+1;
					}
				}
				if($i1 == count($T))
				{
					while($i2<count($Q))
					{
						echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][2].' par '.$Q[$i2][3].'<br/>'.$Q[$i2][1];
						if(!strcmp($Q[$i2][4],"resolue"))
							echo ' ✔';
						echo '</a></div><br/>';
						$i2 = $i2+1;
					}
				}
				else
				{
					while($i1<count($T))
					{
						echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][2].' par '.$T[$i1][3].'<br/>'.$T[$i1][1].'</a></div><br/>';
						$i1 = $i1+1;
					}
				}
			}
			
		}
		else if(isset($_GET['req']) and strcmp($_GET['req'],"profil_technotes")==0 and isset($_GET['pseudo']))//technotes d'un membre
		{
			$req = $bdd->query('SELECT ID,titre,date_creation FROM technote,membre WHERE pseudo="'.$_GET['pseudo'].'" AND mail_=mail');
			//on affiche le résultat obtenu
			foreach($req as $reponse)
			{
				echo '<div><a href="view_technote.php?ID='.$reponse['ID'].'" id="post" >'.$reponse['date_creation'].'<br/>'.$reponse['titre'].'</a></div><br/>';
			}
		}
		else if(isset($_GET['req']) and strcmp($_GET['req'],"profil_questions")==0 and isset($_GET['pseudo']))//questions d'un membre
		{
			$req = $bdd->query('SELECT IDQ,statut,titre,date_creation FROM question,membre WHERE pseudo="'.$_GET['pseudo'].'" AND question.mail_membre=membre.mail');
			//on affiche le résultat obtenu
			foreach($req as $reponse)
			{
				echo '<div><a href="view_technote.php?ID='.$reponse['IDQ'].'" id="post" >'.$reponse['date_creation'].'<br/>'.$reponse['titre']
				.' ('.$reponse['statut'].')'.'</a></div><br/>';
			}
		}


		else if(isset($_GET['req']) and !strcmp($_GET['req'],"mot_clef"))//résultat corresopondant à un mot clef
		{
			$id_mc = $_GET['idmc'];
			//requète pour les technotes
			$req_technotes = $bdd->query('SELECT DISTINCT ID,titre,date_creation,pseudo FROM technote,membre,caracterise
																		WHERE mail=mail_ AND id_technote=ID AND idmc='.$id_mc.'
																		ORDER BY date_creation DESC');

			$T = array();

			while($res_technote = $req_technotes->fetch())
			{
				//nombre de commentaires
				$T[] = array($res_technote['ID'],$res_technote['titre'],$res_technote['date_creation'],$res_technote['pseudo']);
			}

			//requête pour les questions
			$req_questions = $bdd->query('SELECT DISTINCT IDQ,titre,date_creation,pseudo,statut FROM question,membre,caracterise
																		WHERE mail=mail_membre AND id_question=IDQ AND idmc='.$id_mc.'
																		ORDER BY date_creation DESC');

			$Q = array();

			while($res_question = $req_questions->fetch())
			{
				$Q[] = array($res_question['IDQ'],$res_question['titre'],$res_question['date_creation'],
											$res_question['pseudo'],$res_question['statut']);
			}

			//on écrit les technotes et questions dans l'ordre de leurs dates
			$i1 = 0;$i2 = 0;
			while($i1<count($T) and $i2<count($Q))
			{
				$date_t = date($T[$i1][2]); $date_q = date($Q[$i2][2]);

				$date_t = new DateTime($date_t);
				$date_q = new DateTime($date_q);

				$date_t = $date_t->format('Y-m-d H:i:s');
				$date_q = $date_q->format('Y-m-d H:i:s');

				if($date_t<$date_q)
				{
					echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][2].' par '.$Q[$i2][3].'<br/>'.$Q[$i2][1];
					if(!strcmp($Q[$i2][4],"resolue"))
						echo ' ✔';
					echo '</a></div><br/>';
					$i2 = $i2+1;
				}
				else
				{
					echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][2].' par '.$T[$i1][3].'<br/>'.$T[$i1][1].'</a></div><br/>';
					$i1 = $i1+1;
				}
			}
			if($i1 == count($T))
			{
				while($i2<count($Q))
				{
					echo '<div><a href="question.php?ID='.$Q[$i2][0].'" id="post" >'.$Q[$i2][2].' par '.$Q[$i2][3].'<br/>'.$Q[$i2][1];
					if(!strcmp($Q[$i2][4],"resolue"))
						echo ' ✔';
					echo '</a></div><br/>';
					$i2 = $i2+1;
				}
			}
			else
			{
				while($i1<count($T))
				{
					echo '<div><a href="view_technote.php?ID='.$T[$i1][0].'" id="post" >'.$T[$i1][2].' par '.$T[$i1][3].'<br/>'.$T[$i1][1].'</a></div><br/>';
					$i1 = $i1+1;
				}
			}
		}
		else
		{
			header("location:recherche.php");
		}


	//afficher retour

	echo '</div>';
	?>	
	
</html>
