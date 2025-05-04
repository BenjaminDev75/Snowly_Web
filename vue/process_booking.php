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
    $montantTT = number_format($_GET['total_price'], 2);
    echo "<p>$montantTT</p>";

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
        $reservationId = $stmt->insert_id;

        require('fpdf.php');

        $factureDir = 'Facture/';
        if (!file_exists($factureDir)) {
            mkdir($factureDir, 0777, true);
        }

        $numeroFacture = "FAC-" . date("Ymd") . "-" . $reservationId;
        $nomFichier = "facture_" . $numeroFacture . ".pdf";
        $cheminFacture = $factureDir . $nomFichier;

        $pdf = new FPDF();
        $pdf->AddPage();

// Informations de l'entreprise
        $company_name = "Snowly SARL";
        $company_address = "123 Rue des Neiges, 75017 Paris";
        $siret = "SIRET : 123 456 789 00012";
        $vat_number = "TVA Intracommunautaire : FR12345678901";
        $contact_email = "contact@snowly.fr";
        $contact_phone = "+33 1 23 45 67 89";

// Logo
        $pdf->Image('images/logo.png', 10, 10, 30);

// Titre
        $pdf->SetY(15);
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Facture de Réservation"), 0, 1, 'C');
        $pdf->Ln(10);

// Informations de l'entreprise
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $company_name), 0, 1);
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $company_address), 0, 1);
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $siret), 0, 1);
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $vat_number), 0, 1);
        $pdf->Ln(5);

// Numéro de facture
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Numéro de facture : ") . $numeroFacture, 0, 1);
        $pdf->Ln(5);

// Date de la facture
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Date de la facture : " . date("d/m/Y")), 0, 1);
        $pdf->Ln(5);

// Infos client
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Informations du client"), 0, 1, 'L', true);
        $pdf->Cell(100, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Nom : ") . iconv("UTF-8", "ISO-8859-1//TRANSLIT", $name), 0, 1);
        $pdf->Cell(100, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Email : ") . $email, 0, 1);
        $pdf->Ln(2);

// Détails réservation
        $pdf->Cell(0, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Détails de la réservation"), 0, 1, 'L', true);
        $pdf->Cell(100, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Date d'arrivée : ") . $checkin, 0, 1);
        $pdf->Cell(100, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Date de départ : ") . $checkout, 0, 1);
        $pdf->Cell(100, 8, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Nombre de personnes : ") . $guests, 0, 1);
        $pdf->Ln(5);

// Montant total avant réduction
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Montant total avant réduction"), 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Total avant réduction : ") . number_format($montantTT/(1-0.05), 2) . " EUR", 0, 1);
        $pdf->Ln(5);

// Appliquer la réduction (si applicable)
        if ($reducValue > 0) {
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Réduction appliquée : - " . number_format($montantTT * 0.05, 2) . " EUR (5%)"), 0, 1);
            $pdf->Ln(5);
        }

// Montant total à payer
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->Cell(0, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Montant total à payer"), 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", "Total TTC : ") . number_format($montantTT, 2) . " EUR", 0, 1);
        $pdf->Ln(10);


// Footer
        $pdf->SetY(-15);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 10, "Snowly SARL - $company_address | $contact_email | $contact_phone", 0, 0, 'C');



// Sauvegarde du fichier
        $pdf->Output('F', $cheminFacture);

// ➕ Mise à jour de la base avec le chemin du fichier
        $updateSql = "UPDATE reservation SET facture = ? WHERE ID_Reservation = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("si", $cheminFacture, $reservationId);
        $stmt->execute();


        // Message de succès
        echo "<p>Réservation effectuée avec succès !</p>";
        echo "<a href='index.php'>Retour à l'accueil</a><br>";
        echo "<a href='$cheminFacture' target='_blank'>Télécharger la facture (PDF)</a>";
    } else {
        echo "<p>Erreur lors de la réservation : " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p>Requête non valide.</p>";
}

$conn->close();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
