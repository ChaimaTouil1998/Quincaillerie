<?php
// Vérifier si un ID de produit est passé dans l'URL
if (isset($_GET['id'])) {
    // Récupérer l'ID du produit depuis l'URL
    $id_produit = $_GET['id'];

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

    // Préparer et exécuter la requête de suppression
    $requete = $connexion->prepare("DELETE FROM produits WHERE id = ?");
    $requete->bind_param("i", $id_produit);

    if ($requete->execute()) {
        // Redirection vers la page de gestion des produits après la suppression
        header("Location: gestion_produit.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du produit : " . $connexion->error;
    }

    // Fermer la connexion
    $requete->close();
    $connexion->close();
} else {
    // Si aucun ID de produit n'est passé, rediriger vers la gestion des produits
    header("Location: gestion_produit.php");
    exit();
}
?>
