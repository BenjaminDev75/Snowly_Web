<?php 
// Connexion à la base de données
$db = new mysqli('localhost', 'root', '', 'ns');
if ($db->connect_error) {
    die("Échec de la connexion : " . $db->connect_error);
}

if (isset($_GET['IDAppart']) && isset($_GET['page'])) {
    $IDAppart = intval($_GET['IDAppart']); // Assurez-vous que l'IDAppart est un entier
    $pageID = intval($_GET['page']); // Assurez-vous que id est aussi un entier

    // Requête pour supprimer l'appartement
    $sql = "DELETE FROM appartement WHERE ID_Appartement = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $IDAppart);

    if ($stmt->execute()) {
        // Suppression réussie, redirection vers la page de gestion des appartements
        header('Location: index.php?page=11&deleted=true'); // Redirection vers la page de gestion des appartements
        exit();
    } else {
        echo "Erreur lors de la suppression de l'appartement.";
    }
} else {
    echo "Paramètres manquants.";
}
?>
