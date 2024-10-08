<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nom_utilisateur'])) {
    header("Location: login.php");
    exit();
}

// Inclure la configuration de la base de données
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

// Récupérer l'ID du produit depuis l'URL
$id_produit = $_GET['id'];

// Récupérer les détails du produit depuis la base de données
$requete = $connexion->prepare("SELECT * FROM produits WHERE id = ?");
$requete->bind_param("i", $id_produit);
$requete->execute();
$resultat = $requete->get_result();

if ($resultat->num_rows > 0) {
    $produit = $resultat->fetch_assoc();
} else {
    echo "Produit non trouvé.";
    exit();
}

// Gérer la soumission du formulaire de modification
if (isset($_POST['modifier'])) {
    // Récupérer les données du formulaire
    $code = $_POST['code_produit'];
    $nom = $_POST['nom_produit'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $description = $_POST['description'];

    // Gérer le téléchargement de la nouvelle image si elle est fournie
   
    // Préparer la requête de mise à jour
    $requete = $connexion->prepare("UPDATE produits SET code_produit = ?, nom_produit = ?,  prix = ?, quantite = ?, description = ? WHERE id = ?");
    $requete->bind_param("sssdisi", $code, $nom, $prix, $quantite, $description, $id_produit);

    // Exécuter la requête et vérifier le résultat
    if ($requete->execute()) {
        echo "Produit mis à jour avec succès !";
        header("Location: gestion_produit.php"); // Rediriger vers la page de gestion des produits après la modification
        exit();
    } else {
        echo "Erreur lors de la mise à jour du produit : " . $requete->error;
    }

    // Fermer la connexion
    $requete->close();
}

$connexion->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Produit</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Modifier le Produit</h2>
        <form action="modifier_produit.php?id=<?php echo $id_produit; ?>" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="code_produit">Code Produit</label>
                <input type="text" id="code_produit" name="code_produit" value="<?php echo $produit['code_produit']; ?>" required>
            </div>
            <div class="input-group">
                <label for="nom_produit">Nom du Produit</label>
                <input type="text" id="nom_produit" name="nom_produit" value="<?php echo $produit['nom_produit']; ?>" required>
            </div>
            
            <div class="input-group">
                <label for="prix">Prix</label>
                <input type="number" id="prix" name="prix" value="<?php echo $produit['prix']; ?>" required>
            </div>
            <div class="input-group">
                <label for="quantite">Quantité</label>
                <input type="number" id="quantite" name="quantite" value="<?php echo $produit['quantite']; ?>" required>
            </div>
            <div class="input-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo $produit['description']; ?></textarea>
            </div>
            <button type="submit" name="modifier">Modifier le Produit</button>
        </form>
    </div>
</body>
</html>
