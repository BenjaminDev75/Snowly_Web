<?php
// Récupérer l'ID de l'appartement depuis l'URL
if (isset($_GET['id'])) {
    $apartmentId = intval($_GET['id']);
    // Connexion à la base de données
    $conn = new mysqli("localhost", "root", "", "ns");

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Récupérer les détails de l'appartement
    $sql = "SELECT * FROM appartement WHERE ID_Appartement = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $apartmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $apartment = $result->fetch_assoc();

    if (!$apartment) {
        die("Appartement non trouvé.");
    }

    $stmt->close();
    $conn->close();
} else {
    die("Aucun appartement sélectionné.");
}
$checkin = isset($_GET['checkin']) ? htmlspecialchars($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? htmlspecialchars($_GET['checkout']) : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation | Snowly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .booking-section {
            padding: 4rem 0;
        }
        .booking-card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            background-color: #fff;
        }
        .btn-submit {
            background-color: #ff385c;
            border-radius: 8px;
            color: #fff;
            font-weight: bold;
        }
        .btn-submit:hover {
            background-color: #e0314d;
        }
    </style>
</head>
<body>


<!-- Booking Section -->
<section class="booking-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="booking-card">
                    <h2 class="mb-4 text-center">Réservez votre séjour</h2>
                    <h5 class="mb-3">Appartement : <?php echo htmlspecialchars($apartment['Nom_Immeuble']); ?></h5>
                    <p class="mb-3"><strong>Localisation :</strong> <?php echo htmlspecialchars($apartment['Adresse']); ?></p>
                    <p class="mb-3"><strong>Prix par nuit :</strong> <?php echo htmlspecialchars($apartment['Tarif']); ?> €</p>

                    <!-- Formulaire de réservation -->
                    <form action="index.php" method="GET">
                        <!-- Champ caché pour indiquer la page -->
                        <input type="hidden" name="page" value="5">
                        <input type="hidden" name="apartment_id" value="<?php echo $apartmentId; ?>">

                        <!-- Champs du formulaire -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom complet" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                        </div>
                        <div class="mb-3">
                            <label for="checkin" class="form-label">Date d'arrivée</label>
                            <input type="date" class="form-control" id="checkin" name="checkin" value="<?php echo isset($_GET['checkin']) ? htmlspecialchars($_GET['checkin']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="checkout" class="form-label">Date de départ</label>
                            <input type="date" class="form-control" id="checkout" name="checkout" value="<?php echo isset($_GET['checkout']) ? htmlspecialchars($_GET['checkout']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="guests" class="form-label">Nombre de personnes</label>
                            <input type="number" class="form-control" id="guests" name="guests" placeholder="Nombre de personnes" min="1" required>
                        </div>

                        <!-- Affichage du prix total -->
                        <div class="total-price">
                            <label for="total-price">Prix total :</label>
                            <span id="total-price">0 €</span>
                        </div>



                        <input type="hidden" id="hidden-total-price" name="total_price" value="0">

                        <button type="submit" class="btn btn-submit w-100">Valider la réservation</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="footer text-center py-4">
    <p>© 2024 Snowly | <a href="#">Mentions légales</a></p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Récupérer les éléments du formulaire
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const totalPriceElement = document.getElementById('total-price');
    const hiddenTotalPrice = document.getElementById('hidden-total-price'); // Le champ caché pour le prix total
    const pricePerNight = parseFloat(<?php echo json_encode($apartment['Tarif']); ?>) || 0; // Sécurisation du tarif
    const discountMessage = document.getElementById('discount-message'); // Message de réduction
    const discountedPriceElement = document.getElementById('discounted-price'); // Le prix après réduction

    // Fonction pour réinitialiser les prix
    function resetPrices() {
        totalPriceElement.textContent = '0 €';
        discountedPriceElement.textContent = '0 €';
        discountMessage.style.display = 'none'; // Masquer le message de réduction
        hiddenTotalPrice.value = '0';
    }

    // Fonction pour calculer le nombre de nuits et le prix total
    function calculateTotalPrice() {
        // Vérifier si les champs sont remplis
        if (!checkinInput.value || !checkoutInput.value) {
            resetPrices();
            return;
        }

        const checkinDate = new Date(checkinInput.value);
        const checkoutDate = new Date(checkoutInput.value);

        // Vérifier si les dates sont valides
        if (isNaN(checkinDate.getTime()) || isNaN(checkoutDate.getTime()) || checkoutDate <= checkinDate) {
            resetPrices();
            return;
        }

        // Calcul du nombre de nuits
        const timeDiff = checkoutDate - checkinDate; // Différence en millisecondes
        const nights = timeDiff / (1000 * 3600 * 24); // Convertir en jours

        // Calcul du prix total
        const totalPrice = nights * pricePerNight;

        // Mettre à jour l'affichage
        totalPriceElement.textContent = totalPrice.toFixed(2) + ' €';

        hiddenTotalPrice.value = totalPrice.toFixed(2);
    }

    // Ajouter des événements pour recalculer lorsque les dates changent
    checkinInput.addEventListener('input', calculateTotalPrice);
    checkoutInput.addEventListener('input', calculateTotalPrice);

    // Appeler la fonction au chargement pour initialiser le prix total si les dates sont déjà présentes
    document.addEventListener('DOMContentLoaded', calculateTotalPrice);
</script>




</body>
</html>
