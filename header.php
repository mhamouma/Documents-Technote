
<header>
<h1>TECHNOTE</h1>
<ul id="menu">	
	<li><a href="main.php">accueil</a></li>
	<li><a href="page_question.php">questions</a></li>
	<li><a href="recherche.php">recherche</a></li>
	<?php
	if(!isset($_SESSION['pseudo']))
	{
		echo '
		<li><a href="connect.php">connexion</a></li>';
	}
	else
	{
		//mettre un onglet déroulant avec le pseudo de la personne connéctée
		//mettre dedans: deconnexion,profil
		//echo '<a id=deconnect href="deconnect.php"><i data-title="Se déconnecter ">déconnexion</i></a>';
		echo '
		<li><a>'.$_SESSION['pseudo'].'</a>';
		echo '
			<ul>
				<li><a href="profil.php?pseudo='.$_SESSION['pseudo'].'">profil</a></li>
				<li><a href="parametre.php">parametres</a></li>
				<li><a href="messagerie.php">messagerie</a></li>
				<li><a href="#" onclick="deconnection();">deconnexion</a></li>				
			</ul>
		</li>';
		//echo '<br/>'.$_SESSION['pseudo'].'<br/>';
	}
	if(!isset($_SESSION['role']))
		$_SESSION['role']=1;//visiteur
	echo "<br/>";	

	try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());	
			}


	function droit($action,$affiche_erreur=false)
	{

		try
			{
				$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
			}
			catch(PDOException $e)
			{
				die('Erreur :'.$e->getMessage());	
			}

		$look_droits = $bdd->query('SELECT * FROM droits WHERE idrole='.$_SESSION['role'].' AND codeAct="'.$action.'"');
		$is_ok = $look_droits->fetch();
		if(!$is_ok)
		{
			if($affiche_erreur)
			{
				$req_action = $bdd->query('SELECT nom FROM action WHERE codeAct="'.$action.'"');
				$action = $req_action->fetch();
				echo 'Vous n\'avez pas les droits nécessaire pour l\'action "'.$action['nom'].'"';
			}
			return false;
		}
		else return true;
	}

	function smileys($texte)
	{
		$in=array(
		   
		   ":*" , // bisou
		   "<3", //coeur
		   ":o", // étonné
		   ":p", // passe la langue
		   ":'(", //cry
		   ";)", //clin oeil
		   ":D", //heureux
		   ":)", //sourire
		   ":/" //pas sure
		   );

		$out=array(
           	   '<img src="smiley/bisou.png" alt="" />',
		   '<img src="smiley/coeur.png" alt="" />',
		   '<img src="smiley/etoone.png" alt="" />',
		   '<img src="smiley/langue.png" alt="" />',
		   '<img src="smiley/cry.png" alt="" />',
		   '<img src="smiley/clin_oeil.png" alt="" />',
		   '<img src="smiley/heureux.png" alt="" />',
		   '<img src="smiley/sourire.png" alt="" />',
		   '<img src="smiley/unsure.png" alt="" />'
		   );

           return str_replace($in,$out,$texte);
	}


	function manager_texte($texte)
	{
		//modifications de base
		$texte = htmlspecialchars($texte);//rend innofensifs les balises html dans le texte
		$texte = nl2br($texte);//créé des <br/>

		//on applique des modifications en fonction de regex
		$texte = preg_replace('#\[code\](.*)\[/code\]#isU','<div id="regex_code">$1</div>',$texte);//les codes que place le visiteur
		$texte = smileys($texte);//smileys regex
				

		return $texte;
	}
	?>

	<script>
		//déconnexion 
		function deconnection()
		{
			var xhr = new XMLHttpRequest();
			xhr.open('GET','deconnect.php');
			xhr.addEventListener("readystatechange",function()
			{
				//donées récéptionées
				if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200)
				{
					//réactualiser la page
					alert("Vous avez été déconnecté!");
					window.location.reload();
				}
			});
			xhr.send(null);
		}
	</script>
</ul>
</header>










