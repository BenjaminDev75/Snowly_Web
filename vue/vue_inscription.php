<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | LM2025</title>
    <!-- Lien Bootstrap CSS -->
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
        .form-container .brand-logo {
            display: block;
            margin: 0 auto 1rem;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
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
        .form-footer {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .form-footer a {
            color: #1e90ff;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Inscription'])) {
    // Récupération des données du formulaire
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['mdp']);
    $confirm_password = trim($_POST['confirm_mdp']);

    // Vérifications des champs
    $errors = [];
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Tous les champs doivent être remplis.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email est invalide.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    // Si pas d'erreurs, insérer dans la base de données
    if (empty($errors)) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=ns', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Préparation et exécution de la requête
            $stmt = $pdo->prepare("INSERT INTO utilisateur (ID_Utilisateur, Email, Mot_De_Passe, Type_Compte) VALUES (null, :email, :password, 'Client')");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                echo "<p style='color:green;'>Inscription réussie !</p>";
                // Vous pouvez rediriger l'utilisateur vers la page de connexion ou autre page après l'inscription
                header("Location: ../index.php?page=1");  // Redirige vers la page d'accueil (ou autre page)
                exit; // Il est important d'utiliser exit() après header() pour arrêter l'exécution du script

                header("Location: index.php?page=9");  // Par exemple
                exit; // Terminer l'exécution du script après la redirection
            } else {
                $errors[] = "Une erreur est survenue lors de l'inscription.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    }

    // Affichage des erreurs
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}
?>


<div class="form-container">
    <img src="images/logo.png" alt="Logo Snowly" class="brand-logo">
    <h1>Inscription</h1>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'Utilisateur</label>
            <input type="text" class="form-control" name="username" placeholder="Entrez votre nom d'utilisateur" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" placeholder="Entrez votre email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de Passe</label>
            <input type="password" class="form-control" id="password" name="mdp" placeholder="Entrez votre mot de passe" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le Mot de Passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_mdp" placeholder="Confirmez votre mot de passe" required>
        </div>
        <input type="submit" class="btn btn-primary w-100" name="Inscription" value="S'inscrire">
    </form>
    <div class="form-footer">
        <p>Vous avez déjà un compte ? <a href="../index.php?page=9">Connectez-vous</a></p>
    </div>
</div>


<!-- Lien Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
