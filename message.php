<!DOCTYPE html>

<?php
session_start();
/**********changer police du message***************/
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
			echo '<div id="page">';

			if(isset($_POST['search']) and isset($_POST['message']))
			{
				$req = $bdd->query('SELECT mail FROM membre WHERE pseudo="'.$_POST['search'].'"');
				$res = $req->fetch();
				$req2 = $bdd->prepare('INSERT INTO message(mail_exp,mail_dest,contenu,date) VALUES(:mail_e,:mail_d,:message,NOW())');
				$req2 -> execute(array(
				'mail_e' => $_SESSION['mail'],
			 	'mail_d' => $res['mail'], 
				'message' => $_POST['message']));
		
				header('Location:message.php?m='.$_POST['search']);
			}	

			//si get alors afficher les messages avec barre de texte a la fin
			//sinon affiche une barre de recherche et une barre de texte
			else if(isset($_GET['m']))
			{
				//on écrit le pseudo en caché pour pouvoir le récupérer en javascript
				echo '<textarea id="pseudo_dest" style="display:none;">'.$_GET['m'].'</textarea>';

				//on récupére le mail 
				$req_mail = $bdd->query('SELECT mail FROM membre WHERE pseudo="'.$_GET['m'].'"');
				$res_mail = $req_mail->fetch();

				$req_messages = $bdd->query('SELECT * FROM `message` WHERE (mail_exp="'.$_SESSION['mail'].'" AND mail_dest="'.$res_mail['mail'].'") 
																		OR (mail_exp="'.$res_mail['mail'].'" AND mail_dest="'.$_SESSION['mail'].'") ORDER BY date;');
				foreach($req_messages as $message)
				{
					if(!strcmp($message['mail_exp'],$_SESSION['mail']))
						echo ' <div id="m_message"><nav id="nav_m_msg">'.$message['mail_exp'].' '.$message['contenu'].'</nav><div/>';//mes messages
					else
						echo ' <div id="m_message"><nav id="nav_c_msg">'.$message['mail_exp'].' '.$message['contenu'].'</nav><div/>';//message du correspondant
				}

				//champs de texte 
				echo '
							<br/>
							<textarea placeholder="message" name="message" id="message" style="float:left;clear:both;"></textarea>
							<br/>
							<input type="button" id="post_message" value="envoyer" name="message" style="float:left;clear:both;">';
	
				echo '<div id="fin_page"></div>';//une ancre

		?>

		<script>
			//envoyer une requète qui va s'occuper de rajouter un message 
			//puis soit réactualiser la page ou alors rajouter avec js une balise avec le contenu du message
		
			var post_message = document.getElementById('post_message');
			var message = document.getElementById('message');
			var destinataire = document.getElementById('pseudo_dest');
	
			post_message.addEventListener('click',function()
			{
				var xhr = new XMLHttpRequest();
				xhr.open('POST','message_serveur.php');
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.addEventListener("readystatechange",function()
				{
					//donées récéptionées
					if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200)
					{
						//réactualiser la page
						window.location.reload();
					}
				});

				xhr.send('pseudo=' + destinataire.value + '&contenu=' + message.value);
			});
		</script>

		<?php
			}
			else
			{
				//barre de recherche par mail et par pseudo
				echo '
				<br/>
				<form action="message.php" method="post">
				<input type=\'text\' name=\'search\' placeholder=\'À\' id=\'recherche\' autocomplete=\'off\' style="margin-left:50px;" required>
				<div id="results" style="z-index:2;margin-left:50px;">
				</div>
				<br/>
				<textarea placeholder="message" id="message" name="message" rows=20 cols=98 style="margin-left:50px;"></textarea>
				<br/>
				<input type="submit"  value="envoyer" style="margin-left:50px;position:absolute;"/>
				</form>';
		?>

			<script>
			(function(){
			
				var searchElement = document.getElementById('recherche');
				var results = document.getElementById('results');
				var message = document.getElementById('message');
				var form = this.form;
				var selectedResult = -1;
				var previousRequest;
				var previousValue = searchElement.value;
				var previewValue="";


				function getResults(value)//effetcue une recherhe sur le serveur 
				{
					//envoi de requete
					var xhr = new XMLHttpRequest();
					xhr.open('GET','recherche_serveur.php?type=membre&s='+encodeURIComponent(value));
			
					//controle de récéption de donées
					xhr.addEventListener("readystatechange",function()
					{
						//donées récéptionées
						if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200)
						{
							//afficher les données comme désiré
							displayResults(xhr.responseText);
						}
					});

					xhr.send(null);
					return xhr;
				}

				function chooseResult(choix)
				{
					searchElement.value = previousValue =  choix.innerHTML;
					results.style.display = "none";
					choix.className='';
					selectedResult = -1;
					searchElement.focus();
				}

				function displayResults(response)
				{
					if(/[a-zA-Z0-9]/.test(response))
					{
						results.style.display = 'block';
					}
					else
						results.style.display = 'none';
				
					if(response.length)
					{
						//parser la réponse à la requète
						response = response.split("|");
						var response_len = response.length;
						results.innerHTML = '';

						//créer les éléments html nécessaire à l'affichage
						for(var i=0,option;i<response_len;i++)
						{
							option = results.appendChild(document.createElement('option'));//créé une nouvelle balise <option></option>
							option.innerHTML = response[i];
							option.addEventListener("click",function(e)
							{
								chooseResult(e.target);//séléctionne l'élément si on clique dessus
							});
						}
					}

				}


				searchElement.addEventListener("keyup",function(e)
				{
					var opts = results.getElementsByTagName('option');
					var appui = e.keyCode || e.which;

					//si fléche haut
					if(appui == 38 && selectedResult > -1)
					{
						opts[selectedResult].className = '';
						selectedResult--;
					
						if(selectedResult > -1)
						{
							opts[selectedResult].className = "result_focus";//on séléctionne cet élément
							var ps = opts[selectedResult].value.split(" ");
							searchElement.value = ps[0];
							previewValue = searchElement.value;
						}
						else
						{
							searchElement.value = previousValue;
						}

					}

					//si fléche bas
					else if(appui == 40 && selectedResult < opts.length)
					{
						results.style.display = "block";//on affiche les résultats
						if(selectedResult > -1)//dans le cas ou il est égal a -1 au début
						{
							opts[selectedResult].className = "";
						}
						selectedResult++;

						if(selectedResult < opts.length)
						{
							opts[selectedResult].className = "result_focus";
							var ps = opts[selectedResult].value.split(" ");
							searchElement.value = ps[0];
							previewValue = searchElement.value;
						}
						else
						{
							searchElement.value = previousValue;
						}

					}

					//si touche entrée
					else if(appui == 13)
					{
						results.style.display = 'none';
						document.getElementById('message').focus();
					}
				
					//si lettre ou chiffre
					else if(previewValue!="" && previewValue!=searchElement.value)
					{

						previousValue = searchElement.value;
						previewValue="";

						previousValue = searchElement.value;

						if(previousRequest && previousRequest.readystate < XMLHttpRequest.DONE)
						{
							previousRequest.abort();//si on a pas términé l'ancienne requète on la stoppe
						}

						previousRequest = getResults(searchElement.value);//on envoie la requete
					
						selectedResult = -1;//on remet la séléction a zéro a chaque caractére écrit
					}

					else if(previousValue!=searchElement.value)
					{
						previewValue="";

						previousValue = searchElement.value;

						if(previousRequest && previousRequest.readystate < XMLHttpRequest.DONE)
						{
							previousRequest.abort();//si on a pas términé l'ancienne requète on la stoppe
						}

						previousRequest = getResults(searchElement.value);//on envoie la requete
					
						selectedResult = -1;//on remet la séléction a zéro a chaque caractére écrit
					}	


				});


			})();

			//désactiver la touche entrée sur le bouton de submission
			recherche.addEventListener("keypress",function(event)
			{
				 // Compatibilité IE / Firefox
				if(!event && window.event) {
				    event = window.event;
				}

				var appui = event.keyCode || event.which;

				if(appui == 38 || appui == 13)
				{
					event.preventDefault();
					event.stopPropagation();
				}

			});

			recherche.addEventListener("blur",function(event)
			{
				results.style.display = "none";
			});

		</script>

		<?php
			}
		
		echo '</div>';
	}

	?>

  </body>
</html>
