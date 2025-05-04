
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .account-container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #e9ecef;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .stats-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .stats-item {
            text-align: center;
        }
        .stats-item h5 {
            font-weight: bold;
        }
        .stats-item p {
            margin: 0;
        }
    </style>
</head>
<body>
<?php
// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si non connecté
    exit();
}

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ns");

// Récupère les informations de l'utilisateur
$email = $_SESSION['email'];
$query = "SELECT c.* FROM utilisateur u
join client c ON u.ID_Utilisateur = c.ID_Utilisateur

WHERE c.email = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$page = isset($_GET['page']) ? $_GET['page'] : 1; // Par défaut, page=1 si aucun paramètre 'page' n'est présent

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement de la mise à jour du profil
    $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
    $nouveauEmail = isset($_POST['email']) ? $_POST['email'] : '';
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';

    $updateQuery = "UPDATE client SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE email = ?;
                    UPDATE utilisateur SET nom = ? WHERE email = ?;";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sssss', $nom, $prenom, $nouveauEmail, $telephone, $email);

    if ($updateStmt->execute()) {
        $_SESSION['email'] = $nouveauEmail; // Met à jour l'email dans la session
        $email = $nouveauEmail;
        $successMessage = "Votre profil a été mis à jour avec succès.";
        header("Location: index.php?page=" . $page); // Redirige vers la page d'accueil (ou autre page)
        exit; // Il est important d'utiliser exit() après header() pour arrêter l'exécution du script
    } else {
        $errorMessage = "Erreur lors de la mise à jour du profil.";
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <!-- Menu de navigation -->
        <div class="col-md-3">
            <div class="list-group">
                <h4 class="list-group-item active">Mon Compte</h4>
                <a href="index.php?page=7" class="list-group-item">Profil</a>
                <a href="index.php?page=10" class="list-group-item">Ajouter un Appartement</a>
                <a href="index.php?page=8" class="list-group-item">Mes Réservations</a>
                <a href="index.php?page=11" class="list-group-item">Mes Appartements</a>
            </div>
        </div>

        <!-- Contenu du profil -->
        <div class="col-md-9">
            <div class="account-container">
                <!-- Avatar Utilisateur -->
                <div class="text-center">
                    <div class="profile-avatar">
                        <img src="images/default-avatar.png" alt="Avatar Utilisateur">
                    </div>
                    <h2 class="mt-3">Bonjour, <?php echo htmlspecialchars($user['prenom']); ?>!</h2>
                    <p class="text-muted">Voici vos informations personnelles.</p>
                </div>

                <?php

                    // Obtient l'ID de l'utilisateur depuis la session
                    $email = $_SESSION['email'];
                    $unControleur = new Controleur();
                    $unID = $unControleur->selectWhereUtilisateur($email); // Vous récupérez l'ID de l'utilisateur

                    // Requête pour récupérer le nombre de réservations et le total dépensé
                    $query = "SELECT COUNT(*) AS nb_reservations, SUM(Montant_Total) AS total_depense FROM reservations_utilisateur WHERE ID_Utilisateur = ?";

                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('i', $unID); // 'i' pour l'ID_Utilisateur qui est un entier
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $data = $result->fetch_assoc();

                    // Nombre de réservations et total dépensé
                    $nbReservations = $data['nb_reservations'];
                    $totalDepense = $data['total_depense'] ? $data['total_depense'] : 0;
                ?>

                <!-- Statistiques -->
                <div class="stats-container row text-center">
                    <div class="col-md-4">
                        <h5><?php echo $nbReservations; ?></h5>
                        <p>Réservations</p>
                    </div>
                    <div class="col-md-4">
                        <h5><?php echo number_format($totalDepense, 2, ',', ' '); ?>€</h5>
                        <p>Total Dépensé</p>
                    </div>
                    <div class="col-md-4">
                        <h5>2 ans</h5>
                        <p>Fidélité</p>
                    </div>
                </div>
                <!-- Formulaire de mise à jour -->
                <form method="post" class="mt-4">
                    <?php if (isset($successMessage)): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                    <?php if (isset($errorMessage)): ?>
                        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>


                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom"
                                value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom"
                                value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone"
                                value="<?php echo ($user['Telephone']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
