<?php
session_start();

$message = ""; // Variable pour stocker les messages
$success = false; // Variable pour gérer l'affichage

if (!isset($_GET['idClient'])) {
    die("ID client manquant.");
}

$idClient = $_GET['idClient'];
$pdo = new PDO('mysql:host=localhost;dbname=ns', 'root', '');
$stmt = $pdo->prepare("SELECT idClient FROM client WHERE idClient = ?");
$stmt->execute([$idClient]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password =/* password_hash($new_password, PASSWORD_BCRYPT); // Hash du mot de passe*/ $new_password;
        $stmt = $pdo->prepare("UPDATE client SET Mot_de_Passe = ?, ModifMdp = curdate() WHERE idClient = ?");
        $stmt->execute([$hashed_password, $user['idClient']]);

        $message = "<div class='alert alert-success text-center'>Mot de passe mis à jour avec succès.<br><a href='../index.php?page=9'>Se connecter</a></div>";
        $success = true; // On cache le formulaire
    } else {
        $message = "<div class='alert alert-danger text-center'>Les mots de passe ne correspondent pas.</div>";
    }
}
?>

<?php
if (isset($_SESSION['showPopup']) && $_SESSION['showPopup'] === true) {
    echo "<script>alert('Il est temps de changer votre mot de passe.');</script>";
    unset($_SESSION['showPopup']);  // Effacer la variable de session après l'affichage de la popup
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe | LM2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e90ff, #00bcd4);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .form-container h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e90ff, #00bcd4);
            border: none;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #00bcd4, #1e90ff);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="form-container">
    <?php if (!$success): ?>
        <h1>Réinitialisation du mot de passe</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control" name="password" placeholder="Entrez votre nouveau mot de passe" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmez le mot de passe</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirmez votre mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Réinitialiser</button>
        </form>
    <?php endif; ?>

    <!-- Affichage des messages -->
    <div class="mt-3"><?= $message ?></div>

    <?php if (!$success): ?>
        <div class="form-footer text-center mt-3">
            <p><a href="../index.php?page=9">Retour à la connexion</a></p>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
