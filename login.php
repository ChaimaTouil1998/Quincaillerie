<?php
session_start(); // Démarrer une session pour l'utilisateur

// Configurer la connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";  // Nom d'utilisateur MySQL
$mot_de_passe = "";      // Mot de passe MySQL
$base_de_donnees = "salem_quin";

// Créer la connexion
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $nom_utilisateur = $_POST['username'];
    $mot_de_passe = $_POST['password'];

    // Préparer la requête SQL pour vérifier l'utilisateur
    $requete = $connexion->prepare("SELECT mot_de_passe FROM utilisateurs WHERE nom_utilisateur = ?");
    $requete->bind_param("s", $nom_utilisateur);
    $requete->execute();
    $requete->store_result();

    // Vérifier si un utilisateur a été trouvé
    if ($requete->num_rows > 0) {
        $requete->bind_result($mot_de_passe_hache);
        $requete->fetch();

        // Vérifier si le mot de passe est correct
        if (password_verify($mot_de_passe, $mot_de_passe_hache)) {
            // Mot de passe correct, connexion réussie
            $_SESSION['nom_utilisateur'] = $nom_utilisateur;
            header("Location: dashboard.php"); // Rediriger vers la page de tableau de bord
            exit();
        } else {
            // Mot de passe incorrect
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        // Aucun utilisateur trouvé avec ce nom d'utilisateur
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }

    // Fermer la requête
    $requete->close();
}

// Fermer la connexion
$connexion->close();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        /* Styles globaux */
body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Conteneur de la page de login */
.login-container {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

/* Formulaire de login */
.login-form h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

/* Groupes de champs */
.input-group {
    margin-bottom: 20px;
}

/* Labels */
.input-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: bold;
}

/* Champs de texte */
.input-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.input-group input:focus {
    border-color: #007BFF;
    outline: none;
}

/* Bouton de soumission */
button {
    width: 100%;
    padding: 10px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

/* Lien de mot de passe oublié */
.forgot-password {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #007BFF;
    text-decoration: none;
    transition: color 0.3s;
}

.forgot-password:hover {
    color: #0056b3;
}

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <h2>Connexion</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Se connecter</button>
                
                <a href="register.php" class="back-to-login">Création d'un nouveau compte</a>
            </form>
        </div>
    </div>
</body>
</html>
