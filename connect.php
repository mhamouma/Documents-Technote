<!DOCTYPE html>

<?php
session_start();
//______________________________il reste a finaliser l'apparence pour le javascript (placeholders,champ_error,champ_succes...)
?>

<html>
  <head>
	<meta charset = "utf-8" />
	<link rel="stylesheet" href="style.css">
	<title='connexion'>
  </head>
  <body>
	<?php //augmenter les tailles des cadres
	include('header.php');
	echo"<article>";
	?>
	<div id="page">
	<br/>
	<h2>Connexion</h2>
	<br/>

	<?php
	if(isset($_SESSION['pseudo']))
	{
		echo 'Erreur chargement de page!<br/>Vous ètes actuellemnent connécté!';
	}
	else
	{
	?>
		<?php

			echo "<form action='connect.php' method='post'>
			<input type='text' id='pseudo' name='pseudo' placeholder='Votre pseudo' >
			<br/>
			<input type='password' id='mdp' name='mdp' placeholder='Votre mot de passe' >
			<br/>
			<input type=\"checkbox\" id=\"box\" name=\"stayconnect\"><label for='box'>rester connecter</label>
			<br/>
			<button type='button' id='buton'>connexion</button>
			<br/>
			<a href='' id='connect_aide'>mot de passe perdu</a>
			<a href='inscription.php' id='connect_aide'>inscription</a>"; 
			echo "</article>";
		?>
	<script>//-----------------------------------------javascript------------------------------------------------------------
	//gérer les couleurs des cadres 

		//-----------------------variables------------------------------

		var buton = document.getElementById('buton');
		var pseudo = document.getElementById('pseudo');
		var mdp = document.getElementById('mdp');

		//________________________fonctions------------------------------

		function request()
		{
			var xhr = new XMLHttpRequest();
			xhr.open('get','connect_serveur.php?pseudo='+encodeURIComponent(pseudo.value)+'&mdp='+encodeURIComponent(mdp.value));

			xhr.addEventListener("readystatechange",function()
			{
				//donées récéptionées
				if(xhr.readyState === XMLHttpRequest.DONE && (xhr.status === 200 || xhr.status === 0))
				{
					displayResultat(xhr.responseText);
				}

			});

			xhr.send(null);
		}

		function displayResultat(reponse)
		{
			//si il ya écrit "erreur:pseudo"
			if(reponse=='erreur:pseudo')
			{
				pseudo.className="champ_erreur";
				pseudo.value="";
				pseudo.placeholder='compte inexistant';
			}
				//si il ya écrit "erreur:mdp"
			else if(reponse=='erreur:mdp')
			{
				mdp.className="champ_erreur";
				document.getElementsByName('mdp')[0].value="";
				document.getElementsByName('mdp')[0].placeholder='Incorrect';
			}
				//si accépté 
			else if(reponse=='accepte')
			{
				succes();
			}
		}

		function succes()
		{
			pseudo.className="champ_succes";
			mdp.className="champ_succes";
			pseudo.disabled=true;
			mdp.disabled=true;

			pseudo.value="";
			mdp.value="";
			pseudo.placeholder='Succès';
			mdp.placeholder='Redirection.';
			setTimeout(function()
			{
				mdp.placeholder='Redirection..';

				setTimeout(function()
				{
					mdp.placeholder='Redirection...';

					setTimeout(function()
					{
						document.location.href="main.php";//redirection sur la page principale
					},1000);
				},1000);

			},1000);
		}

		function reinit_pseudo()
		{
			pseudo.placeholder = "Votre pseudo";
			pseudo.value = "";
			pseudo.className="";
		}

		function reinit_mdp()
		{
			mdp.placeholder = "Votre mot de passe";
			mdp.value = "";
			mdp.className="";
		}


		//--------------------------événements------------------------------------

		//------clik---------

		//événement sur le bouton de submission
		buton.addEventListener('click',function()
		{
			//nombres de lettres suffisant?
			if(pseudo.value.length<5 || mdp.value.length<6)
			{
				//pour le pseudo
				if(pseudo.value.length<5)
				{
					pseudo.className="champ_erreur";
					pseudo.value = "";
					pseudo.placeholder = "5 caractères minimum";
				}

				//mot de passe
				if(mdp.value.length<6)
				{
					mdp.className="champ_erreur";
					mdp.value = "";
					mdp.placeholder = "6 caractères minimum";
				}
			}

			else
			{
			//envoyer une requète pour savoir si le mot de passe et le pseudo sont accéptés
			request();	
			}

		});

		//-------------key------------------

		pseudo.addEventListener('keypress',function(e)//entrée lorseque le focus est dans pseudo pseudo
		{
			 // Compatibilité IE / Firefox
		  if(!e && window.e) {
		      e = window.e;
		  }
		  // IE
		  if(e.keyCode == 13) {
				mdp.focus();
		  }
		  // DOM
		  if(e.which == 13) {
		      mdp.focus();
		  }
		});

		mdp.addEventListener('keypress',function(e)//entrée quand le focus est dans mdp
		{
			 // Compatibilité IE / Firefox
		  if(!e && window.e) {
		      e = window.e;
		  }
		  // IE
		  if(e.keyCode == 13) {
					//nombres de lettres suffisant?
				if(pseudo.value.length<5 || mdp.value.length<6)
				{
					//pour le pseudo
					if(pseudo.value.length<5)
					{
						pseudo.value = "";
						pseudo.placeholder = "5 caractères minimum";
						pseudo.className="champ_erreur";
					}

					//mot de passe
					if(mdp.value.length<6)
					{
						mdp.value = "";
						mdp.placeholder = "6 caractères minimum";
						mdp.className="champ_erreur";
					}
				}

				else
				{
				//envoyer une requète pour savoir si le mot de passe et le pseudo sont accéptés
				request();	
				}
		  }
		});

		//------------focus-----------------------



		pseudo.addEventListener('focus',function(e)//si pseudo gagne le focus
		{
			reinit_pseudo();
		});

		mdp.addEventListener('focus',function(e)//si mdp gagne le focus
		{
			reinit_mdp();
		});

		mdp.addEventListener('keyup',function(e)//si mdp gagne le focus
		{
			if(e.keyCode==13)
				buton.focus();
		});

		//animation pour le bouton de submission
		buton.addEventListener("mouseover",function animOver(){
			var s = buton.style;
			s.background="#00008B";
		});


		buton.addEventListener("mouseout",function animOut(){
			var s = buton.style;
			s.background="#4682B4";
		});
		
	</script>
	<?php
	}
	?>
	</div>
  </body>

</html>
