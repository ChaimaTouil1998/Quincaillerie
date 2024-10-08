<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nom_utilisateur'])) {
    header("Location: login.php");
    exit();
}

// Configurer la connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees = "salem_quin";

// Créer la connexion
$connexion = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Récupérer le nombre de produits
$resultat_produits = $connexion->query("SELECT COUNT(*) AS total_produits FROM produits");
$total_produits = $resultat_produits->fetch_assoc()['total_produits'];

// Récupérer le nombre de factures
$resultat_factures = $connexion->query("SELECT COUNT(*) AS total_factures FROM factures");
$total_factures = $resultat_factures->fetch_assoc()['total_factures'];

// Récupérer le nombre d'utilisateurs
$resultat_utilisateurs = $connexion->query("SELECT COUNT(*) AS total_utilisateurs FROM utilisateurs");
$total_utilisateurs = $resultat_utilisateurs->fetch_assoc()['total_utilisateurs'];

// Fermer la connexion
$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles de base */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar img {
            width: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidebar h3 {
            margin: 0;
            margin-bottom: 30px;
            font-size: 20px;
            text-align: center;
        }

        .sidebar a {
            text-decoration: none;
            color: #ecf0f1;
            font-size: 18px;
            margin: 10px 0;
            display: block;
            text-align: center;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #34495e;
        }

        .dashboard-container {
            margin-left: 250px; /* La largeur de la sidebar */
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: 20px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 40px;
            margin: 0;
            color: #2c3e50;
        }

        .card.products {
            background-color: #3498db;
            color: #fff;
        }

        .card.invoices {
            background-color: #e67e22;
            color: #fff;
        }

        .card.users {
            background-color: #2ecc71;
            color: #fff;
        }

        .logout {
            text-align: right;
            padding: 20px;
        }

        .logout a {
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="images/logo.jpeg" alt="Avatar">
        <h3><?php echo $_SESSION['nom_utilisateur']; ?></h3>
        <a href="gestion_produit.php">Gestion des Produits</a>
        <a href="ajouter_facture.php">Ajouter Facture</a>
        <a href="historique_factures.php">Historique Factures</a>
        <div class="logout">
            <a href="logout.php">Se déconnecter</a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="card products">
            <h3>Produits</h3>
            <p><?php echo $total_produits; ?></p>
        </div>
        <div class="card invoices">
            <h3>Factures</h3>
            <p><?php echo $total_factures; ?></p>
        </div>
        <div class="card users">
            <h3>Utilisateurs</h3>
            <p><?php echo $total_utilisateurs; ?></p>
        </div>
    </div>
</body>
</html>
