<?php
	//tableau de tous les mots clés pour l'autocomplétion

		try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());	
			}

		if(isset($_GET['type']) and !strcmp($_GET['type'],"post"))
		{
			if(strcmp('',$_GET['s']) and !strcmp($_GET['par'],'mc'))
			{
				//le dernier mot 
				$last_mot ='';
				$mots = explode(' ',$_GET['s']);
				$last_mot = $mots[count($mots)-1];

				if(strlen($last_mot)!=0 and !(strlen($last_mot)==1 and !strcmp($last_mot[0],'+')))
				{
					//on vérifie si le mot est préfixé d'un +
					$prefixe = false;
					if(!strcmp($last_mot[0],'+'))
					{
						$prefixe = true;
						$last_mot = substr($last_mot,1);
					}


					$mots_clef = array();//le tableau contenant tous les mots clefs
					$req = $bdd->query('SELECT mot FROM mot_clef WHERE mot LIKE "'.$last_mot.'%" ORDER BY mot LIMIT 0,10');
					foreach($req as $mot)
					{
						$m1 = '';
						$i=0;
						for($i=0;$i<count($mots)-1;$i++)
						{
							$m1 .= $mots[$i].' ';
						}
	
						if($prefixe)//si le dernier mot était préfixé
							$m1 .= '+';
			
						$m1 .= $mot['mot'];
						$mots_clef[] = $m1;
					}

					echo implode('|',$mots_clef);
				}
			}
			
			if(strcmp('',$_GET['s']) and !strcmp($_GET['par'],'a'))
			{
				$membres = array();
				if(strcmp($_GET['s'],''))
				{
					$req = $bdd->query('SELECT pseudo,mail FROM membre WHERE pseudo LIKE "'.$_GET['s'].'%" 
															OR mail LIKE "'.$_GET['s'].'%" ORDER BY pseudo LIMIT 0,10');
					foreach($req as $membre)
					{
						$membres[] = htmlspecialchars($membre['pseudo']);
					}

					echo implode('|',$membres);
				}
			}			
		}

		if(isset($_GET['type']) and !strcmp($_GET['type'],"membre"))
		{
			$membres = array();
			if(strcmp($_GET['s'],''))
			{
				$req = $bdd->query('SELECT pseudo,mail FROM membre WHERE pseudo LIKE "'.$_GET['s'].'%" 
														OR mail LIKE "'.$_GET['s'].'%" ORDER BY pseudo LIMIT 0,10');
				foreach($req as $membre)
				{
					$membres[] = htmlspecialchars($membre['pseudo'].'   <'.$membre['mail'].'>');
				}

				echo implode('|',$membres);
			}
		}
?>	









