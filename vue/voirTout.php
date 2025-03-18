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

// Construire la requête SQL de base
$sql = "SELECT * FROM appartement_dispo ap
        LEFT JOIN appartement on ap.ID_Appartement = appartement.ID_Appartement
        LEFT JOIN image ON appartement.idImage = image.idImage";

// Préparer la requête
$stmt = $conn->prepare($sql);

// Lier les paramètres et exécuter
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
                                        <a href="index.php?page=3&id=<?= htmlspecialchars($apartment['ID_Appartement']) ?>&checkin=<?= htmlspecialchars(date('Y-m-d')) ?>&checkout=<?= htmlspecialchars(date('Y-m-d', strtotime('+1 day'))) ?>" class="btn btn-primary">Voir plus</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">Aucun résultat trouvé pour votre recherche.</p>
                    <?php endif; ?>
                </div>
            </div>
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
