<?php

	session_start();

	try
		{
			$bdd = new PDO('mysql:host=localhost;dbname=technote','root','');
		}
		catch(PDOException $e)
		{
			die('Erreur :'.$e->getMessage());	
		}

	if(isset($_POST['pseudo']) and isset($_POST['contenu']))
	{
		//on récupére le mail du destinataire
		$req_mail = $bdd->query('SELECT mail FROM membre WHERE pseudo="'.$_POST['pseudo'].'"');
		if($mail = $req_mail->fetch())
		{
			$ins = $bdd->prepare('INSERT INTO message(mail_exp,mail_dest,contenu,date) VALUES(:mail_e,:mail_d,:message,NOW())');
			$ins->execute(array('mail_e'=>$_SESSION['mail'],
													'mail_d'=>$mail['mail'],
													'message'=>$_POST['contenu']));
		}
	}		

?>
