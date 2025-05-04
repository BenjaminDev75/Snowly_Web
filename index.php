<?php
	session_start();
	require_once("controleur/controleur.class.php");
	//creation d'une instante de la classe Controleur
	$unControleur = new Controleur ();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Snowly | Location d'Appartements près des Stations de Ski</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<center>

	<?php
	if ( ! isset($_SESSION['email'])) {
		require_once ("vue/vue_connexion.php");
	}
    if (isset($_POST['Connexion'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Vérification de la connexion
        $unUser = $unControleur->verifConnexion($email, $mdp);

        if ($unUser) {


            $idClient = $unUser['idClient'];


                $_SESSION['email'] = $unUser['email'];
                $_SESSION['ID_Utilisateur'] = $unUser['ID_Utilisateur'];
                header("Location: index.php?page=1"); // Rediriger vers la page principale
                exit();
            }
        } else {
      //  }
    }

	if (isset($_SESSION['email'])){
		echo '
		<nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=1">Snowly</a> <!-- Redirection vers home.php -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=1">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=7">Mon Compte</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=6">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>';


		if (isset($_GET['page'])){
			$page = $_GET['page'];
		}else {
			$page = 1;
		}
		switch($page){
			case 1 : require_once("controleur/home.php"); break;
			case 2 : require_once("vue/search.php"); break;
			case 3 : require_once("vue/details.php"); break;
			case 4 : require_once("vue/booking.php"); break;
			case 5 : require_once("vue/process_booking.php"); break;
            case 7 : require_once("vue/account.php"); break;
            case 8 : require_once("vue/mesreservation.php"); break;
            case 9 : require_once("vue/vue_connexion.php"); break;
            case 10 : require_once("vue/mesappartement.php"); break;
            case 11 : require_once("vue/gererappartement.php"); break;
            case 12 : require_once("vue/delete_appartement.php"); break;
			case 6 : session_destroy(); unset($_SESSION['email']);
					header("Location: index.php");
					break;
		}
	} //fin du if session.


	?>

</center>
</body>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    /* Navbar */
    .navbar {
        background-color: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        font-size: 1.75rem;
        font-weight: 600;
        color: #ff385c;
    }

    .navbar-nav .nav-link {
        color: #333;
    }

    .navbar-nav .nav-link:hover {
        color: #ff385c;
    }

    .footer {
        background-color: #333;
        color: #fff;
        padding: 2rem 1rem;
        text-align: center;
    }

    .footer a {
        color: #ff385c;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }
</style>
</html>