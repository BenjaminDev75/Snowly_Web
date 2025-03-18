<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ns");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Construire la requête SQL
$sql = "SELECT 
    s.Nom AS Nom_Station, 
    GROUP_CONCAT(a.Nom_Immeuble SEPARATOR ', ') AS Immeubles,
    a.*, 
    i.image
FROM 
    appartement a
JOIN 
    station s ON a.ID_Station = s.ID_Station
LEFT JOIN 
    image i ON a.idImage = i.idImage
GROUP BY 
    s.Nom, a.ID_Appartement";

// Exécution de la requête
$result = $conn->query($sql);

// Vérifier si des résultats sont trouvés
$stations = [];
$apartments = [];

if ($result->num_rows > 0) {
    // Récupérer tous les résultats sous forme de tableau associatif
    while ($row = $result->fetch_assoc()) {
        // Regrouper les stations par nom et les immeubles associés
        $stations[$row['Nom_Station']][] = [
            'immeubles' => explode(', ', $row['Immeubles']),
            'apartments' => $row,
            'image' => $row['image']
        ];
    }
} else {
    echo "<p>Aucun résultat trouvé.</p>";
}

// Fermer la connexion à la base de données
$conn->close();
?>

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

<!-- Search Results Section -->
<section class="search-results">
    <div class="container">
        <h2>Résultats de votre recherche</h2>
        <div class="search-container">
            <?php if (!empty($stations)): ?>
                <?php foreach ($stations as $station => $data): ?>
                    <div class="station-group">
                        <h3 class="text-primary">Station: <?= htmlspecialchars($station) ?></h3>
                        <div class="row g-4">
                            <?php foreach ($data as $apartment_data): ?>
                                <div class="col-md-4">
                                    <div class="card result-card">
                                        <!-- Affichage de l'image de l'appartement -->
                                        <?php if (!empty($apartment_data['image'])): ?>
                                            <img src="<?= htmlspecialchars($apartment_data['image']) ?>" class="card-img-top" alt="Image de l'appartement">
                                        <?php else: ?>
                                            <img src="default-image.jpg" class="card-img-top" alt="Image par défaut">
                                        <?php endif; ?>

                                        <div class="card-body result-card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($apartment_data['apartments']['Nom_Immeuble']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($apartment_data['apartments']['Description']) ?></p>
                                            <p class="text-danger fw-bold"><?= htmlspecialchars($apartment_data['apartments']['Tarif']) ?>€ / nuit</p>
                                            <a href="index.php?page=3&id=<?= htmlspecialchars($apartment_data['apartments']['ID_Appartement']) ?>" class="btn btn-primary">Voir plus</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Aucun résultat trouvé pour votre recherche.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
