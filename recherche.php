<!DOCTYPE html>

<?php
session_start();
/****************quand on retire le focus de la barre de recherche cacher options**********************/
?>

<html>
  <head>
	<meta charset = "utf-8" />
	<link rel="stylesheet" href="style.css">
	<title='Rechercher une technote'>
  </head>
  <body>

	<?php
	include('header.php');
	
?>
	<form id="form" action="resultat.php?req=recherche" method="post">
	<div id="page_search">
	<br/>
	<input type='text' name='search' placeholder='Rechercher par mots clefs' id="recherche" autocomplete="off" required>
	<input type="submit" id="bouton" value=">" />
	<div id="results"></div>
	</br>
	<button id="filtre" onclick="display_filtres();return false;">Filtres <a style="color:black;font-size:0.6em;">▼</a></button>
	<div id="filtre_cache" style="display:none;">
		<div id="rechercher_par">
			<label id="titre_rechercher">Rechercher par</label>
			<br/>
			<input type="radio" name="par" id="par_mc" value="mots clefs" onchange="changeCategorie();" style="margin-left:10px;margin-right:5px;margin-top:10px;" checked="checked" 
			><label>mots clefs</label>
			<br/>
			<input type="radio" name="par" id="par_a" value="auteur" onchange="changeCategorie();" style="margin-left:10px;margin-right:5px;margin-top:0px;" 
			><label>auteur</label>
			<br/>
			<input type="radio" name="par" id="par_d" value="date" onchange="changeCategorie();" style="margin-left:10px;margin-right:5px;margin-top:0px;" 
			><label>date</label>
		</div>
		<div id="rechercher_statut">
			<label id="titre_rechercher">statut</label>
			<br/>
			<input type="checkbox" id="cb_res" name="resolue" style="margin-left:10px;margin-right:5px;margin-top:10px;" checked="checked">
			<label for="cb_statut">résolu</label>
			<br/>
			<input type="checkbox" id="cb_non_res" name="non_resolue" style="margin-left:10px;margin-right:5px;margin-top:0px;" checked="checked">
			<label for="cb_statut">non résolu</label>
		</div>
		<div id="rechercher_dans">
			<label id="titre_rechercher">Rechercher dans</label>
			<br/>			
			<input type="checkbox" id="cb_technote" name="dans_technote" style="margin-left:10px;margin-right:5px;margin-top:10px;" checked="checked">
			<label for="cb_technote">technote</label>
			<br/>
			<input type="checkbox" id="cb_question" name="dans_question" style="margin-left:10px;margin-right:5px;margin-top:0px;" checked="checked" 
			onchange="change_question();">
			<label for="cb_question">question</label>
		</div>
	</form>
	</div>
	</div>

	<script>
		(function(){
			
			var searchElement = document.getElementById('recherche');
			var results = document.getElementById('results');
			var form = this.form;
			var selectedResult = -1;
			var previousRequest;
			var previousValue = searchElement.value;
			var previewValue="";


			function getResults(value)//effetcue une recherhe sur le serveur 
			{
				var option;
				if(document.getElementById('par_mc').checked)
					option = 'mc';
				else if(document.getElementById('par_a').checked)
					option = 'a';
				else
					option = 'd';
				//envoi de requete
				var xhr = new XMLHttpRequest();
				xhr.open('GET','recherche_serveur.php?type=post&par='+option+'&s='+encodeURIComponent(value));
			
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


			/*
			*	événements
			*/

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
						searchElement.value = opts[selectedResult].value;
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
						searchElement.value = opts[selectedResult].value;
						previewValue = searchElement.value;
					}
					else
					{
						searchElement.value = previousValue;
					}

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

		var bouton = document.getElementById("bouton");
		var recherche = document.getElementById("recherche");

		//animation pour le bouton de submission
		//animation pour le bouton de submission
		bouton.addEventListener("mouseover",function animOver(){
			var s = bouton.style;
			s.background="#00008B";
		});


		bouton.addEventListener("mouseout",function animOut(){
			var s = bouton.style;
			s.background="#4682B4";
		});

		//désactiver la touche entrée sur le bouton de submission
		recherche.addEventListener("keypress",function(event)
		{
			 // Compatibilité IE / Firefox
		  if(!event && window.event) {
		      event = window.event;
		  }

			var appui = event.keyCode || event.which;

			if(appui == 38)
			{
				event.preventDefault();
				event.stopPropagation();
			}

		});

		recherche.addEventListener("blur",function(event)
		{
			results.style.display = "none";
		});

		/*
		*	filtres
		*/

		function display_filtres()
		{
			if(document.getElementById('filtre_cache').style.display=="none")
			{
				document.getElementById('filtre_cache').style.display="block";
				document.getElementById('filtre').value='Filtres <a style="color:black;font-size:0.6em;">▲<a>';
			}
			else
			{
				document.getElementById('filtre_cache').style.display="none";
				document.getElementById('filtre').value='Filtres <a style="color:black;font-size:0.6em;">▼<a>';
			}
		}

		function changeCategorie()
		{
			if(document.getElementById('par_mc').checked)
				document.getElementById('recherche').placeholder="Rechercher par mots clefs";
			if(document.getElementById('par_a').checked)
				document.getElementById('recherche').placeholder="Rechercher par auteur";
			if(document.getElementById('par_d').checked)
				document.getElementById('recherche').placeholder="Rechercher par date (format: AAAA-MM-DD)";
		}


		function change_question()
		{
			if(document.getElementById('cb_question').checked)
			{
				document.getElementById('cb_res').checked=true;
				document.getElementById('cb_non_res').checked=true;
			}
			else
			{
				document.getElementById('cb_res').checked=false;
				document.getElementById('cb_non_res').checked=false;
			}
		}
		
	</script>

  </body>
</html>
