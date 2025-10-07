<?php
// Fichier : Inscription.php

// Paramètres de connexion à la base de données
// REMPLACEZ CES VALEURS
$servername = "localhost";
$username_db = "votre_utilisateur_db";
$password_db = "votre_mot_de_passe_db";
$dbname = "warhammer_db"; // Doit correspondre au nom de votre BDD

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Récupération et validation des données du formulaire
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $role_id = (int)($_POST['role_id'] ?? 0); // S'assurer que c'est un entier

    if (empty($prenom) || empty($nom) || empty($email) || empty($mot_de_passe) || $role_id === 0) {
        $error_message = "Erreur du Cogitateur : tous les champs doivent être remplis. Vérifiez votre Mandat d'Enregistrement.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Erreur de transmission : le format de l'Email est invalide.";
    } else {
        // 2. Hachage sécurisé du mot de passe
        $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        try {
            // Créer une connexion PDO
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 3. Préparation de la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO users (prenom, nom, email, mot_de_passe, role_id) VALUES (:prenom, :nom, :email, :mot_de_passe, :role_id)");

            // 4. Exécution de la requête
            $stmt->execute([
                'prenom' => $prenom,
                'nom' => $nom,
                'email' => $email,
                'mot_de_passe' => $mot_de_passe_hache,
                'role_id' => $role_id
            ]);

            $success_message = "Enregistrement dans le Scriptorum réussi ! Vous pouvez maintenant vous connecter.";

        } catch(PDOException $e) {
            if ($e->getCode() == 23000) { // Code d'erreur pour les doublons (UNIQUE constraint violation)
                $error_message = "Erreur de Scriptorum : Cet Email est déjà enregistré dans les archives.";
            } else {
                $error_message = "Erreur de base de données (Inquisition) : " . $e->getMessage();
            }
        }
    }
}

// ----------------------------------------------------------------------
// Affichage du résultat (peut être inclus dans un fichier de template plus tard)
// ----------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat d'Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Module /CSS/styles.css">
    <style>
        .result-mandate {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            text-align: center;
            border: 3px solid #C0A04C;
            background-color: #1a1a1a;
        }
        .success { color: #00FF00; } /* Vert pour Succès (Data-OK) */
        .error { color: #FF0000; }   /* Rouge pour Erreur (Data-Corrupted) */
    </style>
</head>
<body class="imperial-archive">
    <div class="result-mandate">
        <?php if ($success_message): ?>
            <h2 class="success">Transmission Acceptée</h2>
            <p class="success"><?php echo $success_message; ?></p>
            <p style="margin-top: 20px;"><a href="../index.html" class="button primary-button runic-command">Retour à l'Accueil</a></p>
        <?php elseif ($error_message): ?>
            <h2 class="error">Protocole Échoué</h2>
            <p class="error"><?php echo $error_message; ?></p>
            <p style="margin-top: 20px;"><a href="./Formulaire.html" class="button secondary-button auxiliary-command">Réessayer l'Enregistrement</a></p>
        <?php else: ?>
            <p>Accès non autorisé. Veuillez passer par le formulaire d'inscription.</p>
        <?php endif; ?>
    </div>
</body>
</html>