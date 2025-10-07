<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header("Location: connexion.php");
    exit();
}

// Récupérer le nom d'utilisateur de la session pour la personnalisation
$username = htmlspecialchars($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Secteur d'Accueil Impérial</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./Module /CSS/styles.css">
    <style>
        /* Styles spécifiques pour la page d'accueil */
        .welcome-mandate {
            text-align: center;
            padding: 50px;
            margin: 50px auto;
            max-width: 800px;
            background-color: #1a1a1a;
            border: 2px solid #C0A04C; /* Or */
        }
        .welcome-mandate h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .welcome-mandate p {
            color: #EEEEEE;
        }
    </style>
</head>
<body class="imperial-archive">

    <main class="main-content">
        <section class="welcome-mandate">
            <h1>Bienvenu <?php echo $username; ?></h1>

            <p>Le Secteur d'Accueil est en ligne. Préparez-vous à recevoir le Mandat Impérial.</p>
            <p style="margin-top: 30px;">
                <a href="deconnexion.php" class="button primary-button runic-command">Déconnecter le Cogitateur</a>
            </p>
        </section>
    </main>
</body>
</html>