<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de Recherche | Snowly</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
     /* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    color: #333;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

/* Navbar */
.navbar {
    background-color: #007bff;
    color: #fff;
}
.navbar a {
    color: #ff385c;
    text-decoration: none;
    transition: color 0.3s ease;
}
.navbar a:hover {
    color: black;
}

/* Footer */
.footer {
    background-color: #007bff;
    color: white;
    text-align: center;
    padding: 15px 0;
    margin-top: 30px;
}
.footer a {
    color: white;
    text-decoration: underline;
    transition: color 0.3s ease;
}
.footer a:hover {
    color: #f8f9fa;
}

/* Hero Section */
.hero {
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('images/snowy-mountains.jpg') no-repeat center center/cover;
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: white;
    position: relative;
}
.hero h1 {
    font-size: 2.5rem;
    margin: 0;
}
.hero p {
    font-size: 1.2rem;
    margin-top: 10px;
}

/* Search Container */
.search-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 30px;
    margin-top: 20px;
}

/* Filter Form */
.filter-form {
    flex: 1 1 30%;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.filter-form select,
.filter-form input,
.filter-form button {
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 1rem;
}
.filter-form button {
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.filter-form button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Search Results */
.search-results {
    flex: 1 1 65%;
}
.result-card {
    margin-bottom: 20px;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.result-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}
.result-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-bottom: 2px solid #007bff;
}
.result-card-body {
    padding: 1.5rem;
}
.result-card-body h5 {
    font-size: 1.25rem;
    margin-bottom: 10px;
    color: #333;
}
.result-card-body p {
    color: #555;
    font-size: 0.9rem;
    margin-bottom: 15px;
}
.result-card-body .btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 0.9rem;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}
.result-card-body .btn:hover {
    background-color: #0056b3;
}

/* Media Queries */
@media (max-width: 768px) {
    .search-container {
        flex-direction: column;
    }
    .filter-form, .search-results {
        flex: 1 1 100%;
    }
    .result-card img {
        height: 200px;
    }
}


    </style>
</head>
<body>


<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1>Résultats de votre Recherche</h1>
        <p>Trouvez l'appartement idéal pour votre séjour.</p>
    </div>
</section>
<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ns");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupération des paramètres de recherche
$city = isset($_GET['city']) ? $conn->real_escape_string($_GET['city']) : '';
$startDate = isset($_GET['checkin']) ? $conn->real_escape_string($_GET['checkin']) : '';
$endDate = isset($_GET['checkout']) ? $conn->real_escape_string($_GET['checkout']) : '';
$priceMin = isset($_GET['price_min']) && $_GET['price_min'] !== '' ? (float)$_GET['price_min'] : null;
$priceMax = isset($_GET['price_max']) && $_GET['price_max'] !== '' ? (float)$_GET['price_max'] : null;

// Construire la requête SQL de base
$sql = "SELECT * FROM appartement  
        LEFT JOIN image ON appartement.idImage = image.idImage
        WHERE Ville LIKE ?";

// Ajouter des filtres pour la disponibilité si les dates sont fournies
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND appartement.ID_Appartement NOT IN (
        SELECT ID_Appartement 
        FROM reservation 
        WHERE (DateDebut BETWEEN ? AND ?) 
        OR (DateFin BETWEEN ? AND ?)
        OR (DateDebut <= ? AND DateFin >= ?)
    )";
}

// Ajouter des filtres pour les prix si renseignés
if (!is_null($priceMin)) {
    $sql .= " AND Tarif >= ?";
}
if (!is_null($priceMax)) {
    $sql .= " AND Tarif <= ?";
}

// Préparer la requête
$stmt = $conn->prepare($sql);

// Construire dynamiquement les paramètres
$params = ["s"];
$searchCity = "%$city%";
$params[] = &$searchCity;

if (!empty($startDate) && !empty($endDate)) {
    $params[0] .= "ssssss";
    $params[] = &$startDate;
    $params[] = &$endDate;
    $params[] = &$startDate;
    $params[] = &$endDate;
    $params[] = &$startDate;
    $params[] = &$endDate;
}

if (!is_null($priceMin)) {
    $params[0] .= "d";
    $params[] = &$priceMin;
}
if (!is_null($priceMax)) {
    $params[0] .= "d";
    $params[] = &$priceMax;
}

// Lier les paramètres et exécuter
call_user_func_array([$stmt, "bind_param"], $params);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si des résultats sont trouvés
if ($result->num_rows > 0) {
    $apartments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $apartments = [];
    echo "<p>Aucun résultat trouvé pour votre recherche.</p>";
}

$stmt->close();
$conn->close();
?>


<!-- Search Results Section -->
<section class="search-results">
    <div class="container">
        <div class="search-container">
            <!-- Formulaire de filtre à gauche -->
            <?php
// Récupérer les valeurs des dates via GET
$checkin = isset($_GET['checkin']) ? htmlspecialchars($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? htmlspecialchars($_GET['checkout']) : '';
?>
<div class="filter-form">
    <form method="get" action="">
        <input type="hidden" name="page" value="2">
        <input type="hidden" name="checkin" value="<?php echo isset($_GET['checkin']) ? htmlspecialchars($_GET['checkin']) : ''; ?>">
        <input type="hidden" name="checkout" value="<?php echo isset($_GET['checkout']) ? htmlspecialchars($_GET['checkout']) : ''; ?>">

        <!-- Remplacement de l'input texte par un select dynamique pour les villes -->
        <select id="city" name="city">
            <option value="" disabled selected>Choisissez une ville</option>
            <?php
            $db = new mysqli('localhost', 'root', '', 'ns');
            if ($db->connect_error) {
                die("Échec de la connexion : " . $db->connect_error);
            }

            $result = $db->query("SELECT nom FROM station ORDER BY nom");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($_GET['city']) && $_GET['city'] == $row['nom']) ? 'selected' : '';
                    echo "<option value=\"{$row['nom']}\" $selected>{$row['nom']}</option>";
                }
            }
            ?>
        </select>

        <!-- Les autres champs restent inchangés -->
        <input type="date" name="checkin" placeholder="Date d'arrivée" value="<?= htmlspecialchars($_GET['checkin'] ?? '') ?>">
        <input type="date" name="checkout" placeholder="Date de départ" value="<?= htmlspecialchars($_GET['checkout'] ?? '') ?>">
        <input type="number" name="price_min" placeholder="Prix minimum" value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>">
        <input type="number" name="price_max" placeholder="Prix maximum" value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>">
        <select name="capacity">
            <option value="">Capacité d'accueil</option>
            <option value="1" <?= (isset($_GET['capacity']) && $_GET['capacity'] == '1') ? 'selected' : '' ?>>1 personne</option>
            <option value="2" <?= (isset($_GET['capacity']) && $_GET['capacity'] == '2') ? 'selected' : '' ?>>2 personnes</option>
            <option value="3" <?= (isset($_GET['capacity']) && $_GET['capacity'] == '3') ? 'selected' : '' ?>>3 personnes</option>
            <option value="4" <?= (isset($_GET['capacity']) && $_GET['capacity'] == '4') ? 'selected' : '' ?>>4 personnes</option>
        </select>

        <button type="submit">Rechercher</button>
    </form>
</div>




            <!-- Résultats de recherche à droite -->
            <div class="search-results">
    <div class="row g-4">
        <?php if (!empty($apartments)): ?>
            <?php foreach ($apartments as $apartment): ?>
                <div class="col-md-4">
                    <div class="card result-card">
                        <img src="<?= htmlspecialchars($apartment['image']) ?>" class="card-img-top" alt="">
                        <div class="card-body result-card-body">
                            <h5 class="card-title"><?= htmlspecialchars($apartment['Nom_Immeuble']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($apartment['Description']) ?></p>
                            <p class="text-danger fw-bold"><?= htmlspecialchars($apartment['Tarif']) ?>€ / nuit</p>
                            <a href="index.php?page=3&id=<?= htmlspecialchars($apartment['ID_Appartement']) ?>&checkin=<?= htmlspecialchars($startDate) ?>&checkout=<?= htmlspecialchars($endDate) ?>" class="btn btn-primary">Voir plus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucun résultat trouvé pour votre recherche.</p>
        <?php endif; ?>
    </div>
</div>
</section>

<!-- Footer -->
<footer class="footer">
    <p>© 2024 Snowly | <a href="#">Mentions légales</a></p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
