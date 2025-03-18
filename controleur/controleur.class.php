<?php
	require_once ("modele/modele.class.php"); 
	class Controleur {
		private $unModele ; 

		public function __construct (){
			$this->unModele = new Modele ();
		}
		/****************** Gestion des clients  ******************/
		public function selectAllClients (){
			return $this->unModele->selectAllClients(); 
		}

		public function insertClient($tab){
			//controler les données du client avant insertion. 
			$this->unModele->insertClient($tab); 
		}

		public function selectLikeClients ($filtre){
			return $this->unModele->selectLikeClients($filtre);
		}

		public function deleteClient($idclient){
			$this->unModele->deleteClient($idclient);
		}

		public function selectWhereUtilisateur($email){
			return $this->unModele->selectWhereUtilisateur($email);
		}

		public function updateClient($tab){
			$this->unModele->updateClient($tab);
		}
		/****************** Gestion des telephones  ******************/
		public function selectAllTelephones (){
			return $this->unModele->selectAllTelephones(); 
		}

		public function insertTelephone($tab){
			//controler les données du telephone avant insertion. 
			$this->unModele->insertTelephone($tab); 
		}

		public function selectLikeTelephones ($filtre){
			return $this->unModele->selectLikeTelephones($filtre);
		}

		public function deleteTelephone($idtelephone){
			$this->unModele->deleteTelephone($idtelephone);
		}

		public function selectWhereTelephone($idtelephone){
			return $this->unModele->selectWhereTelephone($idtelephone);
		}

		public function updateTelephone($tab){
			$this->unModele->updateTelephone($tab);
		}

		/************ Gestion users **************/
		public function verifConnexion ($email, $mdp){
			//controle des données 

			//retourner le user resultat 
			return $this->unModele->verifConnexion($email, $mdp);
		}
	}

?>





