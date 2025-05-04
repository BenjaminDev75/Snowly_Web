<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "ns");

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupération des stations
$stations = $conn->query("SELECT ID_Station, nom FROM station ORDER BY nom");
?>

<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom_immeuble = $_POST['nom_immeuble'];
    $adresse = $_POST['adresse'];
    $cp = $_POST['cp'];
    $ville = $_POST['ville'];
    $exposition = $_POST['exposition']; // Récupérer l'exposition
    $surface_habitable = $_POST['surface_habitable'];
    $surface_balcon = $_POST['surface_balcon'];
    $capacite_accueil = $_POST['capacite_accueil'];
    $distance_pistes = $_POST['distance_pistes'];
    $description = $_POST['description'];
    $tarif = $_POST['tarif'];
    $station = $_POST['station'];

    $email = $_SESSION['email'];   
    $unControleur = new Controleur();
    $proprietaire = $unControleur->selectWhereUtilisateur($email); // Vous récupérez l'ID de l'utilisateur    

    // Insérer l'appartement sans spécifier l'image
    $sql = "INSERT INTO appartement (Nom_Immeuble, Adresse, CP, Ville, Exposition, Surface_Habitable, Surface_Balcon, Capacite_Accueil, Distance_Pistes, Description, Tarif, ID_Station, ID_Utilisateur) 
            VALUES ('$nom_immeuble', '$adresse', '$cp', '$ville', '$exposition', '$surface_habitable', '$surface_balcon', '$capacite_accueil', '$distance_pistes', '$description', '$tarif', '$station', '$proprietaire')";

    if (mysqli_query($conn, $sql)) {
        // Récupérer l'ID de l'appartement après l'insertion
        $last_id = mysqli_insert_id($conn);

        // Ajouter les équipements sélectionnés
        if (isset($_POST['equipements'])) {
            $equipements = $_POST['equipements']; // Les équipements sélectionnés
            foreach ($equipements as $equipement) {
                // Récupérer l'ID de l'équipement en fonction du nom
                $equipement_query = "SELECT ID_Equipement FROM equipement WHERE Nom_Equipement = '$equipement'";
                $result = mysqli_query($conn, $equipement_query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $equipement_data = mysqli_fetch_assoc($result);
                    $id_equipement = $equipement_data['ID_Equipement'];
                    
                    // Insérer l'équipement dans la table de liaison
                    $insert_equipement_sql = "INSERT INTO appartements_equipements (id_appartement, id_equipement) VALUES ('$last_id', '$id_equipement')";
                    mysqli_query($conn, $insert_equipement_sql);
                }
            }
        }

        // Créer le dossier pour cet appartement
        $dossier_appartement = 'images/ImageAppartement/' . $last_id;
        if (!file_exists($dossier_appartement)) {
            mkdir($dossier_appartement, 0777, true);
        }

        // Initialiser une variable pour stocker l'ID de la première image
        $idImage_principale = null;

        // Parcourir tous les fichiers uploadés
        $image_count = count($_FILES['image']['name']);
        for ($i = 0; $i < $image_count; $i++) {
            $image_name = $_FILES['image']['name'][$i];
            $image_tmp_name = $_FILES['image']['tmp_name'][$i];
            $image_path = $dossier_appartement . '/' . $image_name;

            // Vérifier si l'image est correctement téléchargée
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                // Insérer l'image dans la table image
                $insert_image_sql = "INSERT INTO image (ID_Appartement, image) VALUES ('$last_id', '$image_path')";
                if (mysqli_query($conn, $insert_image_sql)) {
                    // Récupérer l'ID de l'image insérée
                    $idImage = mysqli_insert_id($conn);

                    // Si c'est la première image, stockez son ID pour l'utiliser comme image principale
                    if ($i == 0) {
                        $idImage_principale = $idImage;
                    }
                } else {
                    echo "Erreur lors de l'insertion de l'image dans la base de données : " . mysqli_error($conn);
                }
            } else {
                echo "Erreur de téléchargement de l'image $image_name.";
            }
        }

        // Mettre à jour la table appartement avec l'ID de l'image principale
        if ($idImage_principale) {
            $update_sql = "UPDATE appartement SET IdImage = '$idImage_principale' WHERE ID_Appartement = '$last_id'";
            if (!mysqli_query($conn, $update_sql)) {
                echo "Erreur lors de la mise à jour de l'image principale : " . mysqli_error($conn);
            }
        }

        $sql2="insert into contrat values(null, $last_id,$proprietaire,curdate(),'Actif',(Year(curdate())));";
        mysqli_query($conn, $sql2);

        echo "Appartement ajouté avec succès!";
    } else {
        echo "Erreur d'insertion de l'appartement : " . mysqli_error($conn);
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
}
?>

<!-- Page d'ajout d'un appartement style Airbnb -->
<div class="container py-5"> <h1 class="text-center mb-4 text-primary">Ajoutez un nouveau logement</h1>
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
        <div class="col-md-9">
    <form method="POST" enctype="multipart/form-data" class="shadow p-5 bg-white rounded">
        <!-- Section : Informations générales -->
        <h3 class="mb-4 text-secondary">1. Informations générales</h3>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="nom_immeuble"><i class="fas fa-building"></i> Nom de l'Immeuble</label>
                <input type="text" class="form-control" id="nom_immeuble" name="nom_immeuble" placeholder="Ex: Chalet Bellevue" required>
            </div>
            <div class="form-group col-md-6">
                <label for="station"><i class="fas fa-map-marker-alt"></i> Station</label>
                <select id="station" name="station" class="form-control" required>
                    <option value="" disabled selected>Choisissez une station</option>
                    <?php if ($stations->num_rows > 0): ?>
                        <?php while ($row = $stations->fetch_assoc()): ?>
                            <option value="<?= $row['ID_Station'] ?>"><?= $row['nom'] ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label for="adresse"><i class="fas fa-map"></i> Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Ex: 123 rue des Alpages" required>
            </div>
            <div class="form-group col-md-4">
                <label for="cp"><i class="fas fa-envelope"></i> Code Postal</label>
                <input type="text" class="form-control" id="cp" name="cp" placeholder="Ex: 75000" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ville"><i class="fas fa-city"></i> Ville</label>
            <input type="text" class="form-control" id="ville" name="ville" placeholder="Ex: Annecy" required>
        </div>

        <!-- Section : Détails de l'appartement -->
        <h3 class="mt-5 mb-4 text-secondary">2. Détails de l'appartement</h3>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="surface_habitable"><i class="fas fa-ruler-combined"></i> Surface Habitable (m²)</label>
                <input type="number" class="form-control" id="surface_habitable" name="surface_habitable" placeholder="Ex: 50" required>
            </div>
            <div class="form-group col-md-4">
                <label for="surface_balcon"><i class="fas fa-ruler-horizontal"></i> Surface du Balcon (m²)</label>
                <input type="number" class="form-control" id="surface_balcon" name="surface_balcon" placeholder="Ex: 10" required>
            </div>
            <div class="form-group col-md-4">
                <label for="capacite_accueil"><i class="fas fa-users"></i> Capacité d'Accueil</label>
                <input type="number" class="form-control" id="capacite_accueil" name="capacite_accueil" placeholder="Ex: 4" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="distance_pistes"><i class="fas fa-skiing"></i> Distance des Pistes (km)</label>
                <input type="number" class="form-control" id="distance_pistes" name="distance_pistes" placeholder="Ex: 1.5" required>
            </div>
            <div class="form-group col-md-6">
    <label for="exposition"><i class="fas fa-sun"></i> Exposition</label>
    <select id="exposition" name="exposition" class="form-control" required>
        <option value="" disabled selected>Choisissez une exposition</option>
        <option value="Nord">Nord</option>
        <option value="Sud">Sud</option>
        <option value="Est">Est</option>
        <option value="Ouest">Ouest</option>
        <option value="Sud-Est">Sud-Est</option>
        <option value="Sud-Ouest">Sud-Ouest</option>
        <!-- Ajoutez d'autres options si nécessaire -->
    </select>
</div>
            <div class="form-group col-md-6">
                <label for="tarif"><i class="fas fa-euro-sign"></i> Tarif par Nuit (€)</label>
                <input type="number" class="form-control" id="tarif" name="tarif" placeholder="Ex: 120" required>
            </div>
        </div>
        <div class="form-group">
            <label for="description"><i class="fas fa-info-circle"></i> Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Décrivez les points forts de votre logement..." required></textarea>
        </div>

        <h3 class="mt-5 mb-4 text-secondary">4. Équipements</h3>
<div class="form-row">
    <?php
    // Connexion à la base de données
    $db = new mysqli('localhost', 'root', '', 'ns');
    if ($db->connect_error) {
        die("Échec de la connexion : " . $db->connect_error);
    }

    // Récupérer les équipements groupés par catégorie
    $equipement_query = "SELECT Categorie, Nom_Equipement, ID_Equipement FROM equipement ORDER BY Categorie, Nom_Equipement";
    $result = $db->query($equipement_query);

    // Tableau pour regrouper les équipements par catégorie
    $equipements_par_categorie = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $equipements_par_categorie[$row['Categorie']][] = $row;
        }
    }

    // Tableau associatif pour les icônes par type d'équipement
    $icones_categories = [
        'Accessibilité' => 'fa-wheelchair',
        'Chambre' => 'fa-bed',
        'Confort' => 'fa-couch',
        'Cuisine' => 'fa-utensils',
        'Eco-responsabilité' => 'fa-leaf',
        'Équipements pour bébés' => 'fa-baby-carriage',
        'Équipements technologiques' => 'fa-tv',
        'Extérieur' => 'fa-sun',
        'Loisirs' => 'fa-gamepad',
        'Salle de bain' => 'fa-shower',
        'Sécurité' => 'fa-shield-alt'
    ];

    // Afficher les équipements par catégorie
    foreach ($equipements_par_categorie as $categorie => $equipements) {
        $icone = $icones_categories[$categorie] ?? 'fa-box'; // Icône par défaut si la catégorie n'est pas dans le tableau

        echo "<div class='col-md-6 mb-4'>"; // Une colonne pour chaque catégorie
        echo "<div class='form-group p-3 border rounded bg-light shadow-sm'>"; // Boîte avec bordure et ombre
        echo "<h5 class='text-secondary mb-3'><i class='fas $icone text-primary me-2'></i>$categorie</h5>";

        foreach ($equipements as $equipement) {
            $id_equipement = $equipement['ID_Equipement'];
            $nom_equipement = $equipement['Nom_Equipement'];

            echo "
                <div class='form-check'>
                    <input class='form-check-input' type='checkbox' id='equipement_$id_equipement' name='equipements[]' value='$nom_equipement'>
                    <label class='form-check-label' for='equipement_$id_equipement'>$nom_equipement</label>
                </div>
            ";
        }

        echo "</div>"; // Fin de la boîte
        echo "</div>"; // Fin de la colonne
    }

    // Fermer la connexion à la base
    $db->close();
    ?>
</div>




<!-- Ajout manuel d'équipements
<div class="mt-4">
    <div class="p-3 border rounded bg-white shadow-sm">
        <label for="equipement_perso" class="form-label">
            <i class="fas fa-plus-circle text-success"></i> Ajouter un autre équipement :
        </label>
        <input type="text" class="form-control mt-2" id="equipement_perso" name="equipement_perso" placeholder="Ex: Lave-vaisselle">
    </div>
</div>-->




        <!-- Section : Images -->
        <h3 class="mt-5 mb-4 text-secondary">3. Ajoutez des photos</h3>
        <div class="form-group">
            <label for="image"><i class="fas fa-camera"></i> Images de l'Appartement</label>
            <input type="file" class="form-control-file" id="image" name="image[]" multiple required>
            <small class="form-text text-muted">Vous pouvez télécharger plusieurs images. Formats acceptés : JPG, PNG.</small>
        </div>

        <!-- Section : signature contrat -->
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="contractCheckbox" required>
            <label class="form-check-label" for="contractCheckbox">
                J'accepte les <a href="#" data-toggle="modal" data-target="#contractModal">termes et les conditions</a> de ce contrat.
            </label>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-success btn-block mt-4" id="submitButton" disabled><i class="fas fa-upload"></i> Publier l'appartement</button>
    </form>
</div>
</div>
</div>

<div class="modal fade" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractModalLabel">Contrat de location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Contrat de location</h5>
                <hr>
                <p>
                    Ce contrat de location (ci-après le "Contrat") est conclu entre l'entreprise (ci-après le "Neige & Soleil") et le propriétaire de l'appartement (ci-après le "Propriétaire"). En cochant cette case, le Locataire accepte d'être lié par les termes et conditions de ce Contrat.
                </p>
                <ol>
                    <li><strong>Durée de la location</strong>: La durée de la location est d'un an. Le Contrat prend effet à la date de signature.</li>
                    <li><strong>Usage du bien</strong>: Le bien loué doit être utilisé uniquement à des fins de loisirs.</li>
                    <li><strong>Sous-location</strong>: Le Locataire n'est pas autorisé à sous-louer l'appartement.</li>
                    <li><strong>Entretien et réparations</strong>: Le Propriétaire sera responsable de l'entretien régulier de l'appartement et des réparations mineures.</li>
                    <li><strong>Equipement</strong>: L'appartement est fourni avec des équipements tels que décrits dans l'annonce de location.</li>
                    <li><strong>Paiement</strong>: Le Locataire est responsable du paiement du loyer dans les délais convenus.</li>
                    <li><strong>Résiliation</strong>: En cas de violation de l'une des conditions énumérées dans le présent Contrat, le Neige & Soleil se donne le droit de résilier le Contrat avec effet immédiat.</li>
                </ol>
                <p>
                    En cochant cette case, le Propriétaire confirme avoir lu, compris et accepté les termes et conditions énoncés dans le présent Contrat.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Inclure FontAwesome et Bootstrap -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<!-- CSS supplémentaire -->
<style>
    /* Stylisation des formulaires */
    .form-group label {
        font-weight: bold;
    }

    .form-control {
        border-radius: 0.375rem;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .btn-success {
        background-color: #28a745;
        border: none;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    /* Stylisation du container et du formulaire */
    .shadow-lg {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .rounded {
        border-radius: 0.375rem;
    }

    /* Espacement et alignement */
    h2 {
        font-size: 1.8rem;
        font-weight: 600;
    }

    .col-md-9 {
        padding-left: 40px;
    }
</style>

<script>
    document.getElementById("contractCheckbox").addEventListener("change", function() {
        document.getElementById("submitButton").disabled = !this.checked;
    });
</script>