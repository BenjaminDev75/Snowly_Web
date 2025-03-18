<h2> Gestion des clients </h2>

<?php
if (isset($_SESSION['role']) && $_SESSION['role']=="admin"){
	$leClient = null;
	if (isset($_GET['action']) && isset($_GET['idclient']) ){
		$action   = $_GET['action']; 
		$idclient = $_GET['idclient'];

		switch($action){
			case "sup"  : $unControleur->deleteClient($idclient);break;
			case "edit" : $leClient = $unControleur->selectWhereClient($idclient);break;
		}
	}
	require_once ("vue/vue_insert_client.php");
	if(isset($_POST['Valider'])){
		
		$unControleur->insertClient($_POST); 
		echo "<br> Insertion réussie du client <br>"; 
	}
	if(isset($_POST['Modifier'])){
		
		$unControleur->updateClient($_POST); 
		header("Location: index.php?page=2");
	}
}
	if (isset($_POST['Filtrer'])){
		$lesClients = $unControleur->selectLikeClients($_POST['filtre']);
	} else {
		$lesClients = $unControleur->selectAllClients (); 
	}

	require_once ("vue/vue_select_clients.php");
?>
