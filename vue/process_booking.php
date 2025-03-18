<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ns");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les données du formulaire
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $apartmentId = intval($_GET['apartment_id']);
    $name = htmlspecialchars(trim($_GET['name']));
    $email = htmlspecialchars(trim($_GET['email']));
    $checkin = $_GET['checkin'];
    $checkout = $_GET['checkout'];
    $guests = intval($_GET['guests']);
    $montantTT = intval($_GET['total_price']);

    // Validation des données
    if (empty($name) || empty($email) || empty($checkin) || empty($checkout) || $guests <= 0 ) {
        echo "<p>Veuillez remplir tous les champs correctement.</p>";
        exit();
    }

    // Vérifier la validité des dates
    if (strtotime($checkin) >= strtotime($checkout)) {
        echo "<p>La date de départ doit être postérieure à la date d'arrivée.</p>";
        exit();
    }
    $idUtil = ['ID_Utilisateur'];

    // (Facultatif) Vérification des disponibilités
    $sql = "SELECT * FROM reservation WHERE ID_Appartement = ? AND ((DateDebut <= ? AND DateFin > ?) OR (DateDebut < ? AND DateFin >= ?))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $apartmentId, $checkout, $checkin, $checkout, $checkin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p>L'appartement n'est pas disponible pour les dates sélectionnées.</p>";
        exit();
    }
    $stmt->close();

    $sql = "SELECT ID_Utilisateur, COUNT(ID_Utilisateur) AS nbReserv, ID_Appartement, (SELECT 5/100) AS Reduc
        FROM reservation
        WHERE ID_Appartement = ? AND ID_Utilisateur = ?
        GROUP BY ID_Appartement, ID_Utilisateur
        HAVING COUNT(ID_Appartement) >= 3;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $apartmentId, $idUtil);
    $stmt->execute();
    $reducResult = $stmt->get_result();


    if ($reducResult->num_rows > 0) {
        $reduc = $reducResult->fetch_assoc();
        $reducValue = $reduc['Reduc']; // Assure-toi que la réduction est bien récupérée.

        // Calcul du montant total avec réduction
        $montantTT = $montantTT * (1 - $reducValue); // Applique correctement la réduction
        // Insérer la réservation dans la base de données
        $sql = "INSERT INTO reservation (ID_Appartement, DateReservation, DateDebut, DateFin, Montant_Total, ID_Utilisateur) VALUES (?, CURDATE(), ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdi", $apartmentId, $checkin, $checkout, $montantTT, $idUtil);
    } else {
        // Insérer la réservation dans la base de données sans réduction
        $sql = "INSERT INTO reservation (ID_Appartement, DateReservation, DateDebut, DateFin, Montant_Total, ID_Utilisateur) VALUES (?, CURDATE(), ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdi", $apartmentId, $checkin, $checkout, $montantTT, $idUtil);
    }






    if ($stmt->execute()) {
        echo "<p>Réservation effectuée avec succès !</p>";
        echo "<a href='index.php'>Retour à l'accueil</a>";
    } else {
        echo "<p>Erreur lors de la réservation : " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p>Requête non valide.</p>";
}

$conn->close();
?>
