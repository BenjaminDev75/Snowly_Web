<?php
// Connexion à la base de données
$db = new mysqli('localhost', 'root', '', 'ns');
if ($db->connect_error) {
    die("Échec de la connexion : " . $db->connect_error);
}

// Supposons que l'utilisateur est connecté et qu'on utilise l'ID de l'utilisateur pour récupérer ses appartements
$email = $_SESSION['email'];   $unControleur = new Controleur();
$id_utilisateur = $unControleur->selectWhereUtilisateur($email); // Vous récupérez l'ID de l'utilisateur  

// Requête pour récupérer les appartements de l'utilisateur
$sql = "SELECT * FROM appartement WHERE ID_Utilisateur = '$id_utilisateur'";
$result = $db->query($sql);
?>

<div class="container">
    <div class="row">
        <!-- Menu de navigation à gauche -->
        <div class="col-md-3" style="padding-top: 10%;">
            <div class="list-group">
                <h4 class="list-group-item active">Mon Compte</h4>
                <a href="index.php?page=7" class="list-group-item">Profil</a>
                <a href="index.php?page=10" class="list-group-item">Ajouter un Appartement</a>
                <a href="index.php?page=8" class="list-group-item">Mes Réservations</a>
                <a href="index.php?page=11" class="list-group-item">Mes Appartements</a>
            </div>
        </div>

<!-- Contenu principal (table des appartements) -->
<div class="col-md-9">
    <h2>Gérer mes Appartements</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom de l'Immeuble</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Exposition</th>
                <th>Tarif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Nom_Immeuble'] . "</td>";
                echo "<td>" . $row['Adresse'] . "</td>";
                echo "<td>" . $row['Ville'] . "</td>";
                echo "<td>" . $row['Exposition'] . "</td>";
                echo "<td>" . $row['Tarif'] . " €</td>";
                echo "<td>
                        <a href='#' onclick='confirmDelete(" . $row['ID_Appartement'] . ")' class='btn btn-danger btn-sm'>Supprimer</a>
                    </td>";
                echo "</tr>";
            }
            /*<a href='edit_appartement.php?id=" . $row['ID_Appartement'] . "' class='btn btn-warning btn-sm'>Modifier</a>*/
        } else {
            echo "<tr><td colspan='6'>Aucun appartement trouvé.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
                <!-- Modal (popup) de confirmation -->
                <div id="deleteSuccessModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="deleteSuccessModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteSuccessModalLabel">Suppression réussie</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>L'appartement a été supprimé avec succès.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="okButton">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

<!-- Modal (popup) de confirmation -->
<div id="deleteModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet appartement ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Valider</a>
            </div>
        </div>
    </div>
</div>

<!-- Ajoutez le script pour afficher la popup -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(idAppart, pageID) {
    // Ouvrir la popup de confirmation
    $('#deleteModal').modal('show');

    // Mettre à jour le lien de validation avec les deux paramètres : idAppart et pageID
    $('#confirmDeleteButton').attr('href', 'index.php?IDAppart=' + idAppart + '&page=12');
}

</script>

<script>
    // Si la page contient le paramètre 'deleted=true', afficher la modal de succès
    $(document).ready(function() {
        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 'true'): ?>
            $('#deleteSuccessModal').modal('show');
        <?php endif; ?>

        // Ajouter un événement au bouton OK pour rediriger sans 'deleted=true'
        $('#okButton').click(function() {
            // Rediriger vers la même page sans le paramètre 'deleted'
            window.location.href = window.location.pathname + '?page=11';  // Ou utilisez 'index.php?page=11' selon votre logique de routage
        });
    });
</script>


</div>
