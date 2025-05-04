<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | LM2025</title>
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
<div class="form-container">
    <img src="images/logo.png" alt="Logo LM2025" class="brand-logo">
    <h1>Connexion</h1>
    <form method="post" action="index.php">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input type="email" class="form-control" name="email" placeholder="Entrez votre email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de Passe</label>
            <input type="password" class="form-control" id="password" name="mdp" placeholder="Entrez votre mot de passe" required>
        </div>
        <input type="submit" class="btn btn-primary w-100" name="Connexion" value="Se Connecter">
    </form>
    <div class="form-footer">
        <p>Pas encore de compte ? <a href="vue/vue_inscription.php">Inscrivez-vous</a></p>
    </div>
</div>

<!-- Lien Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
