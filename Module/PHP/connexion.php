<?php
// Fichier : connexion.php (Modifié)
session_start();

// ----------------------------------------------------------------------
// ÉTAPE 1: Gérer la soumission du formulaire
// ----------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Paramètres de connexion à la base de données
    // MODIFIEZ CES VALEURS
    $servername = "localhost";
    $username_db = "votre_utilisateur_db";
    $password_db = "votre_mot_de_passe_db";
    $dbname = "warhammer_db"; // Assurez-vous que cela correspond

    // Récupérer les données du formulaire (ici, on suppose que l'utilisateur entre son EMAIL ou son NOM)
    // J'utilise l'email comme identifiant de connexion standard.
    $email_input = trim($_POST['email'] ?? '');
    $password_input = $_POST['password'] ?? '';

    // Vérification basique des champs
    if (empty($email_input) || empty($password_input)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Créer une connexion PDO
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparation de la requête pour la SÉCURITÉ (on recherche par email)
            // On sélectionne le prénom pour le message de bienvenue.
            $stmt = $conn->prepare("SELECT id, prenom, mot_de_passe FROM users WHERE email = :email");
            $stmt->execute(['email' => $email_input]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // ----------------------------------------------------------------------
            // ÉTAPE 2: Vérification des identifiants
            // ----------------------------------------------------------------------
            if ($user && password_verify($password_input, $user['mot_de_passe'])) {
                // Succès: Les identifiants sont corrects

                // Stocker les informations de l'utilisateur en session
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['prenom']; // Utilisation du prénom pour le message de bienvenue

                // Redirection vers la page d'accueil personnalisée
                header("Location: accueil.php");
                exit();

            } else {
                // Échec: Identifiants invalides ou utilisateur non trouvé
                $error_message = "Email ou Sceau d'Accès incorrect.";
            }

        } catch(PDOException $e) {
            $error_message = "Erreur de connexion à la base de données: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion au Cogitateur Impérial</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./Module /CSS/styles.css">
    <style>
        /* Styles spécifiques pour le formulaire de connexion (Grimdark) */
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background-color: #1a1a1a;
            border: 3px solid #990000;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
            text-align: center;
        }
        .login-container h2 {
            color: #C0A04C;
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container input[type="email"] { /* Changement de 'type="text"' en 'type="email"' */
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #555555;
            background-color: #0D0D0D;
            color: #EEEEEE;
            font-family: 'Roboto Mono', monospace;
        }
        .login-container .error-message {
            color: #FF4444;
            margin-bottom: 15px;
        }
        .login-container button {
            background-color: #990000;
            color: #EEEEEE;
            padding: 10px 20px;
            border: 2px solid #C0A04C;
            cursor: pointer;
            text-transform: uppercase;
            font-family: 'Cinzel', serif;
            transition: background-color 0.3s;
        }
        .login-container button:hover {
            background-color: #AA0000;
        }
    </style>
</head>
<body class="imperial-archive">
    <div class="login-container">
        <h2>Accès au Cogitateur Impérial</h2>

        <?php
        if (isset($error_message)) {
            echo '<p class="error-message">' . htmlspecialchars($error_message) . '</p>';
        }
        ?>

        <form action="connexion.php" method="post">
            <input type="email" name="email" placeholder="Email (Scribe ID)" required> <input type="password" name="password" placeholder="Mot de passe (Sceau d'Accès)" required>
            <button type="submit">Valider l'Accès</button>
        </form>
        <p style="margin-top: 15px; color: #555555; font-size: 0.9em;">
            <a href="./Page web /Formulaire.html" style="color: #C0A04C;">Nouveau sujet ? Enregistrez-vous.</a>
        </p>
    </div>
</body>
</html>