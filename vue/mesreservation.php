<?php

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ns");

// Récupère les informations de l'utilisateur
$email = $_SESSION['email'];
$query = "SELECT * FROM utilisateur u
join client c on u.ID_Utilisateur = c.ID_Utilisateur
where c.email = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ID de l'utilisateur récupéré depuis la session
$idUtilisateur = $user['ID_Utilisateur'];

// Pagination : Nombre de réservations par page
$limite = 10; // Par exemple, 10 réservations par page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limite;

// Requête SQL pour récupérer les réservations de l'utilisateur
$query = "SELECT * FROM vuereservation WHERE ID_Utilisateur = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $idUtilisateur);
$stmt->execute();
$reservations = $stmt->get_result();

// Requête pour le nombre total de réservations
$totalQuery = "SELECT COUNT(*) AS total_reservations FROM vuereservation WHERE ID_Utilisateur = ?";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param('i', $idUtilisateur);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalReservations = $totalRow['total_reservations'];

// Calcul de la pagination
$totalPages = ceil($totalReservations / $limite);
?>

<div class="container mt-5">
    <!-- Menu de navigation -->
    <div class="row">
        <div class="col-md-3"> <!-- Colonne pour le menu de navigation -->
            <div class="list-group">
                <h4 class="list-group-item active">Mon Compte</h4>
                <a href="index.php?page=7" class="list-group-item">Profil</a>
                <a href="index.php?page=10" class="list-group-item">Ajouter un Appartement</a>
                <a href="index.php?page=8" class="list-group-item">Mes Réservations</a>
                <a href="index.php?page=11" class="list-group-item">Mes Appartements</a>
            </div>
        </div>
        <div class="col-md-9"> <!-- Colonne pour le contenu principal -->
            <h2>Mes Réservations</h2>
            <div class="row">
                <?php if ($reservations->num_rows > 0) : ?>
                    <?php while ($reservation = $reservations->fetch_assoc()) : ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?= htmlspecialchars($reservation['image']) ?>" class="card-img-top" alt="Hotel Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($reservation['Nom_Immeuble']); ?></h5>
                                    <p class="card-text">Date : <?php echo htmlspecialchars($reservation['DateDebut']); ?> - <?php echo htmlspecialchars($reservation['DateFin']); ?></p>
                                    <p class="card-text">Montant Total : <?php echo number_format($reservation['Montant_Total'], 2, ',', ''); ?>€</p>
                                 <!--   <a href="reservation_details.php?id=<?php //echo $reservation['ID_Reservation']; ?>" class="btn btn-primary">Voir les détails</a>-->
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Aucune réservation trouvée.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>