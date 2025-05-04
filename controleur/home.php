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

        /* Hero Section */
        .hero {
            background: url('images/snowy-mountains.jpg') center/cover no-repeat;
            color: #fff;
            text-align: center;
            padding: 7rem 2rem;
            position: relative;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        /* Search Box */
        .search-box {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .search-box input,
        .search-box button {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #ff385c;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e0314d;
        }

        /* Featured Regions */
        .featured-destinations {
            padding: 4rem 0;
        }

        .featured-destinations .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .featured-destinations .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .featured-destinations .card img {
            border-radius: 15px;
            object-fit: cover;
            height: 250px;
        }

        .featured-destinations .card-body {
            background-color: #fff;
            padding: 1.5rem;
        }

        .featured-destinations .card-title {
            font-size: 1.5rem;
            font-weight: 500;
            color: #333;
        }

        .btn-view {
            background-color: #ff385c;
            border-radius: 8px;
            padding: 8px 15px;
            color: #fff;
            text-decoration: none;
        }

        .btn-view:hover {
            background-color: #e0314d;
        }

        /* Testimonials */
        .testimonials {
            background-color: #f8f9fa;
            padding: 4rem 0;
        }

        .testimonial-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
        }

        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
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

        /* Carousel */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #ff385c;
            border-radius: 50%;
            width: 40px;
            height: 40px;
        }

        .carousel-control-prev-icon:hover,
        .carousel-control-next-icon:hover {
            background-color: #e0314d;
        }

        .carousel-control-prev,
        .carousel-control-next {
            background-color: rgba(0, 0, 0, 0.4);
            border: none;
            width: 50px;
            height: 50px;
        }

        .carousel-indicators {
            display: none;
        }
    </style>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Snowly | Location d'Appartements près des Stations de Ski</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
            /* (CSS reste inchangé sauf indication contraire) */
        </style>
    </head>
    <body>

    <?php

    // Exemple de tableau de destinations (vous pouvez le remplacer par des données de votre base de données)
    $destinations = [
        [
            "name" => "Chamonix",
            "description" => "Découvrez la beauté des Alpes françaises à Chamonix.",
            "image" => "images/chamonix.jpg",
            "city" => "Chamonix"
        ],
        [
            "name" => "Méribel",
            "description" => "Une station de ski luxueuse dans le cœur des 3 Vallées.",
            "image" => "images/meribel.jpg",
            "city" => "Meribel"
        ],
        [
            "name" => "Val Thorens",
            "description" => "La station la plus haute d'Europe, idéale pour les amateurs de ski.",
            "image" => "images/valthorens.jpg",
            "city" => "Val Thorens"
        ],
        [
            "name" => "Courchevel",
            "description" => "Une station réputée pour son élégance et ses pistes incroyables.",
            "image" => "images/courchevel.jpg",
            "city" => "Courchevel"
        ],
        [
            "name" => "Les Arcs",
            "description" => "Des paysages magnifiques et des pistes pour tous niveaux.",
            "image" => "images/les-arcs.jpg",
            "city" => "Les Arcs"
        ],
        [
            'image' => 'images/megeve.jpg', 
            'name' => 'Megève', 
            'description' => 'Une station chic et élégante pour des vacances de rêve.',
            'city'=>'Megève'
        ],
    ];

// Sélectionne 3 destinations aléatoires
$random_keys = array_rand($destinations, 3);
$featured_destinations = array_map(fn($key) => $destinations[$key], $random_keys);



    $testimonials = [
        ['image' => 'images/client1.jpg', 'name' => 'Pierre L.', 'feedback' => 'Un séjour incroyable à Chamonix, l\'appartement était parfait et proche des pistes.'],
        ['image' => 'images/client1.jpg', 'name' => 'Marie B.', 'feedback' => 'Séjour inoubliable à Megève. L\'appartement était luxueux et bien situé.'],
        ['image' => 'images/client1.jpg', 'name' => 'Julien T.', 'feedback' => 'Val Thorens était l\'endroit idéal pour des vacances en famille. Tout était impeccable!']
    ];

    // Exemple de tableau de témoignages (remplacez par vos données réelles)
$testimonials = [
    [
        "name" => "Alice Dupont",
        "feedback" => "Snowly a rendu nos vacances inoubliables ! Des hébergements parfaits.",
        "image" => "images/client1.jpg"
    ],
    [
        "name" => "Jean Martin",
        "feedback" => "Un service incroyable et des options adaptées à nos besoins. Merci Snowly !",
        "image" => "images/client1.jpg"
    ],
    [
        "name" => "Marie Curie",
        "feedback" => "Un séjour de rêve grâce à leur service rapide et fiable.",
        "image" => "images/client1.jpg"
    ],
    [
        "name" => "Paul Durand",
        "feedback" => "Les meilleurs choix pour les vacances d'hiver. Nous recommandons à 100% !",
        "image" => "images/client1.jpg"
    ],
    [
        "name" => "Élise Lambert",
        "feedback" => "Super expérience, des logements confortables et proches des pistes.",
        "image" => "images/client1.jpg"
    ],
];
$random_keys = array_rand($testimonials, 3);
$featured_testimonials = array_map(fn($key) => $testimonials[$key], $random_keys);

?>

    <!-- Hero Section -->
    <section class="hero">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1>Explorez les Stations de Ski comme Jamais</h1>
        <p>Réservez un appartement idéal avec Snowly pour profiter des meilleures expériences hivernales.</p>
        <!-- Search Form -->
        <div class="search-box">
            <form action="index.php" method="get" class="row g-3">
                <input type="hidden" name="page" value="2">
                <div class="col-md-4">
                    <label for="city" class="form-label">Ville</label>
                    <select id="city" name="city" class="form-control" required>
                        <option value="" disabled selected>Choisissez une ville</option>
                        <?php
                        $db = new mysqli('localhost', 'root', '', 'ns');
                        if ($db->connect_error) {
                            die("Échec de la connexion : " . $db->connect_error);
                        }

                        $result = $db->query("SELECT nom FROM station ORDER BY nom");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value=\"{$row['nom']}\">{$row['nom']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" id="checkin" name="checkin" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" id="checkout" name="checkout" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>
    </div>
</section>


    <!-- Featured Destinations Section -->

    <section class="featured-destinations">
    <div class="container">
        <h2 class="text-center mb-4">Explorez les Destinations Phare</h2>
        <div class="row g-4">
            <?php foreach ($featured_destinations as $destination): ?>
                <div class="col-md-4">
                    <div class="card">
                    <img src="<?= $destination['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($destination['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($destination['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($destination['description']) ?></p>
                            <a href="index.php?page=2&city=<?= htmlspecialchars($destination['city']) ?>" class="btn-view">Voir les options</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


    <!-- Testimonials Section -->
    <section class="testimonials">
    <div class="container">
        <h2 class="text-center mb-4">Témoignages de Nos Clients</h2>
        <div class="row">
            <?php foreach ($featured_testimonials as $testimonial): ?>
                <div class="col-md-4">
                    <div class="testimonial-card">
                    <img src="<?= $testimonial['image'] ?>" alt="<?= htmlspecialchars($testimonial['name']) ?>">
                    <p class="font-weight-bold"><?= htmlspecialchars($testimonial['name']) ?></p>
                    <p>"<?= htmlspecialchars($testimonial['feedback']) ?>"</p>
                    </div>
                </div>
            <?php endforeach; ?>
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
