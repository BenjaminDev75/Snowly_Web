<?php
session_start();
$reset_link_display = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if ($email) {
        $pdo = new PDO('mysql:host=localhost;dbname=ns', 'root', '');
        $stmt = $pdo->prepare("SELECT u.idClient FROM utilisateur u 
                                            JOIN client c ON u.idClient = c.idClient
                                            WHERE c.email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $idClient = $user['idClient'];
            $reset_link_display = "vue_reinitialiserMdp.php?idClient=$idClient";
        } else {
            echo "<p class='text-danger text-center'>Aucun compte associé à cet email.</p>";
        }
    } else {
        echo "<p class='text-danger text-center'>Adresse e-mail invalide.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié | LM2025</title>
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
        }
        .form-container h1 {
            text-align: center;
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
    <h1>Mot de passe oublié</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" placeholder="Entrez votre email" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Envoyer</button>
    </form>
    <div class="form-footer text-center mt-3">
        <p><a href="index.php">Retour à la connexion</a></p>
    </div>
    <?php if ($reset_link_display): ?>
        <div class="alert alert-info mt-3 text-center">
            <p><strong>Lien de réinitialisation :</strong></p>
            <p><a href="<?= $reset_link_display ?>" target="_blank">Réinitialiser mot de passe</a></p>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
