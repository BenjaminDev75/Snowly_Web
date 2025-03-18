<?php
	class Modele {
		private $unPdo ; 

		public function __construct(){
			try{
				$serveur = "localhost";
				$bdd     = "ns";
				$user    = "root"; 
				$mdp     = "";
				$this->unPdo = new PDO("mysql:host=".$serveur.";dbname=".$bdd, $user, $mdp);
			}
			catch(PDOException $exp){
				echo "<br> Erreur de connexion à la BDD :".$exp->getMessage();
			}
		}

		/*************** Gestion des clients **************/
		public function selectAllClients (){
			$requete ="select * from client ;";
			$exec = $this->unPdo->prepare ($requete); 
			$exec->execute (); 
			return $exec->fetchAll(); 
		}
		public function insertClient($tab){
			$requete ="insert into client values (null, :nom, :prenom, :adresse, :email, :tel); "; 
			$exec = $this->unPdo->prepare ($requete); 
			$donnees = array(	":nom"=>$tab['nom'], 
								":prenom"=>$tab['prenom'],
								":adresse"=>$tab['adresse'],
								":email"=>$tab['email'],
								":tel"=>$tab['tel'] 
							); 
			$exec->execute ($donnees); 
		}
		public function selectLikeClients ($filtre){
			$requete ="select * from client where nom like :filtre or prenom like :filtre or adresse like :filtre or email like :filtre or tel like :filtre ; "; 
			$exec = $this->unPdo->prepare ($requete); 
			$donnees =array(":filtre"=>"%".$filtre."%");
			$exec->execute ($donnees); 
			return $exec->fetchAll(); 
		}
		public function deleteClient($idclient){
			$requete = "delete from client where idclient = :idclient;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees =array(":idclient"=>$idclient);
			$exec->execute ($donnees); 
		}
		public function selectWhereUtilisateur($email) {
			// Requête pour récupérer l'ID de l'utilisateur par email
			$requete = "SELECT utilisateur.ID_Utilisateur FROM utilisateur 
			join client on utilisateur.ID_Utilisateur = client.ID_Utilisateur
			WHERE email = :email";  
			$exec = $this->unPdo->prepare($requete);
			$donnees = array(":email" => $email);  
			$exec->execute($donnees);
			
			// Récupérer uniquement l'ID_Utilisateur
			$result = $exec->fetch();  // Récupère une ligne
			
			// Si un utilisateur est trouvé, renvoie l'ID
			return  $result['ID_Utilisateur'];
		}
		
		public function updateClient($tab){
			$requete = "update client set nom = :nom, prenom=:prenom, adresse =:adresse, email=:email, tel=:tel where idclient = :idclient;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees = array(	":nom"=>$tab['nom'], 
								":prenom"=>$tab['prenom'],
								":adresse"=>$tab['adresse'],
								":email"=>$tab['email'],
								":tel"=>$tab['tel'],
								":idclient"=> $tab['idclient']
							); 
			$exec->execute ($donnees);
		}

		/*************** Gestion des telephones **************/
		public function selectAllTelephones (){
			$requete ="select * from telephone ;";
			$exec = $this->unPdo->prepare ($requete); 
			$exec->execute (); 
			return $exec->fetchAll(); 
		}

		public function insertTelephone($tab){
			$requete ="insert into telephone values (null, :designation, :dateAchat, :prixAchat, :panne, :idclient); "; 
			$exec = $this->unPdo->prepare ($requete); 
			$donnees = array(	":designation"=>$tab['designation'], 
								":dateAchat"=>$tab['dateAchat'],
								":prixAchat"=>$tab['prixAchat'],
								":panne"=>$tab['panne'],
								":idclient"=>$tab['idclient'] 
							); 
			$exec->execute ($donnees); 
		}
		public function selectLikeTelephones ($filtre){
			$requete ="select * from telephone where designation like :filtre or dateAchat like :filtre or prixAchat like :filtre or panne like :filtre or idclient like :filtre ; "; 
			$exec = $this->unPdo->prepare ($requete); 
			$donnees =array(":filtre"=>"%".$filtre."%");
			$exec->execute ($donnees); 
			return $exec->fetchAll(); 
		}
		public function deleteTelephone($idtelephone){
			$requete = "delete from telephone where idtelephone = :idtelephone;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees =array(":idtelephone"=>$idtelephone);
			$exec->execute ($donnees); 
		}
		public function selectWhereTelephone ($idtelephone){
			$requete = "select * from telephone where idtelephone = :idtelephone;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees =array(":idtelephone"=>$idtelephone);
			$exec->execute ($donnees);
			return $exec->fetch();  
		}
		public function updateTelephone($tab){
			$requete = "update telephone set designation = :designation, dateAchat=:dateAchat, prixAchat =:prixAchat, panne=:panne, idclient=:idclient where idtelephone = :idtelephone;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees = array(	":designation"=>$tab['designation'], 
								":dateAchat"=>$tab['dateAchat'],
								":prixAchat"=>$tab['prixAchat'],
								":panne"=>$tab['panne'],
								":idclient"=>$tab['idclient'] ,
								":idtelephone"=> $tab['idtelephone']
							); 
			$exec->execute ($donnees);
		}
		/********* Gestion des users ***********/
		public function verifConnexion ($email, $mdp){
			$requete="select * from utilisateur u 
			join client c on u.ID_Utilisateur = c.ID_Utilisateur
			where email = :email and Mot_De_Passe = :mdp ;";
			$exec = $this->unPdo->prepare ($requete); 
			$donnees = array(":email"=>$email, ":mdp"=>$mdp); 
			$exec->execute ($donnees);
			return $exec->fetch(); 
		}
	}
?>

