<?php
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
    $nom_utilisateur = $_POST['nom_utilisateur'] ?? null;
    $email = $_POST['email'] ?? null;
    $mot_de_passe = $_POST['mot_de_passe'] ?? null;

    // Validation de la confirmation du mot de passe
    
    // Hacher le mot de passe pour le stockage sécurisé
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

    // Préparer et exécuter la requête d'insertion
    $requete = $connexion->prepare("INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe) VALUES (?, ?, ?)");
    $requete->bind_param("sss", $nom_utilisateur, $email, $mot_de_passe_hache);

    if ($requete->execute()) {
        echo "Compte créé avec succès !";
        header("Location: login.php"); // Redirection vers la page de connexion après la création du compte
        exit();
    } else {
        echo "Erreur : " . $requete->error;
    }

    // Fermer la connexion
    $requete->close();
}

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

/* Conteneur de la page de création de compte */
.register-container {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
}

/* Formulaire de création de compte */
.register-form h2 {
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

/* Lien vers la page de connexion */
.back-to-login {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #007BFF;
    text-decoration: none;
    transition: color 0.3s;
}

.back-to-login:hover {
    color: #0056b3;
}

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création de Compte</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <h2>Créer un Compte</h2>
            <form action="register.php" method="POST">
    <div class="input-group">
        <label for="nom_utilisateur">Nom d'utilisateur</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required>
    </div>
    <div class="input-group">
        <label for="email">Adresse Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="input-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    </div>
  
    <button type="submit">Créer un Compte</button>
    <a href="login.php" class="back-to-login">Déjà un compte ? Se connecter</a>
</form>
        </div>
    </div>
</body>
</html>
