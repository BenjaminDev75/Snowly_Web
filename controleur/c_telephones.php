<h2> Gestion des téléphones </h2>

<?php
	$lesClients = $unControleur->selectAllClients(); 

	$leTelephone = null;
	if (isset($_GET['action']) && isset($_GET['idtelephone']) ){
		$action   = $_GET['action']; 
		$idtelephone = $_GET['idtelephone'];

		switch($action){
			case "sup"  : $unControleur->deleteTelephone($idtelephone);break;
			case "edit" : $leTelephone = $unControleur->selectWhereTelephone($idtelephone);break;
		}
	}
	require_once ("vue/vue_insert_telephone.php");
	
	if(isset($_POST['Valider'])){
		
		$unControleur->insertTelephone($_POST); 
		echo "<br> Insertion réussie du Telephone <br>"; 
	}

	if(isset($_POST['Modifier'])){
		
		$unControleur->updateTelephone($_POST); 
		header("Location: index.php?page=3");
	}

	if (isset($_POST['Filtrer'])){
		$lesTelephones = $unControleur->selectLikeTelephones($_POST['filtre']);
	} else {
		$lesTelephones = $unControleur->selectAllTelephones (); 
	}

	require_once ("vue/vue_select_telephones.php");
?>