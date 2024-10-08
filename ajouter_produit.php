<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Récupérer les données du formulaire
    $code_produit = $_POST['code_produit'];
    $nom_produit = $_POST['nom_produit'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $description = $_POST['description'];

    // Gestion de l'image
    $image_produit = $_FILES['image_produit']['name'];
    
    // Correction du chemin cible avec une barre oblique après 'htdocs/'
    $cible = $_SERVER['DOCUMENT_ROOT'] . "/quincaillerie_salem/images/" . basename($image_produit);

    // Déplacer l'image téléchargée dans le répertoire souhaité
    if (move_uploaded_file($_FILES['image_produit']['tmp_name'], $cible)) {
        // Préparer et exécuter la requête d'insertion
        $requete = $connexion->prepare("INSERT INTO produits (code_produit, nom_produit, image_produit, prix, quantite, description) VALUES (?, ?, ?, ?, ?, ?)");
        $requete->bind_param("sssdis", $code_produit, $nom_produit, $image_produit, $prix, $quantite, $description);

        if ($requete->execute()) {
            echo "Produit ajouté avec succès !";
        } else {
            echo "Erreur : " . $requete->error;
        }

        $requete->close();
    } else {
        echo "Échec du téléchargement de l'image.";
    }

    // Fermer la connexion
    $connexion->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <h2>Ajouter un Nouveau Produit</h2>
        <form action="ajouter_produit.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="code_produit">Code Produit</label>
                <input type="text" id="code_produit" name="code_produit" required>
            </div>
            <div class="input-group">
                <label for="nom_produit">Nom du Produit</label>
                <input type="text" id="nom_produit" name="nom_produit" required>
            </div>
            <div class="input-group">
                <label for="image_produit">Image du Produit</label>
                <input type="file" id="image_produit" name="image_produit" required>
            </div>
            <div class="input-group">
                <label for="prix">Prix</label>
                <input type="number" step="0.01" id="prix" name="prix" required>
            </div>
            <div class="input-group">
                <label for="quantite">Quantité</label>
                <input type="number" id="quantite" name="quantite" required>
            </div>
            <div class="input-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <button type="submit">Ajouter le Produit</button><br><br><br>
        </form>
        <button  class="btn btn-danger" style="background-color: red; color:wheat;"><a href="gestion_produit.php">Liste des produits</a></button>

    </div>
</body>
</html>
