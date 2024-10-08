<?php
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

// Récupérer tous les produits
$resultat = $connexion->query("SELECT * FROM produits");
// Exemple de boucle d'affichage des produits
$resultat_produits = $connexion->query("SELECT id, nom_produit FROM produits");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.product-form-container, .product-management-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.input-group input, .input-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #2980b9;
}

.add-product-btn {
    display: inline-block;
    padding: 10px 20px;
    margin-bottom: 20px;
    background-color: #2ecc71;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 16px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table img {
    max-width: 50px;
    border-radius: 4px;
}

table th {
    background-color: #3498db;
    color: #fff;
}

table tr:hover {
    background-color: #f1f1f1;
}

table a {
    color: #3498db;
    text-decoration: none;
    margin-right: 10px;
}

table a:hover {
    text-decoration: underline;
}

    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="product-management-container">
        <h2>Gestion des Produits</h2>
        <a href="ajouter_produit.php" class="add-product-btn">Ajouter un Produit</a>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Image</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($produit = $resultat->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $produit['code_produit']; ?></td>
                    <td><?php echo $produit['nom_produit']; ?></td>
                    <td><img src="images/<?php echo $produit['image_produit']; ?>" alt="<?php echo $produit['nom_produit']; ?>" width="50"></td>
                    <td><?php echo $produit['prix']; ?> €</td>
                    <td><?php echo $produit['quantite']; ?></td>
                    <td><?php echo $produit['description']; ?></td>
                    <td>
                        <a href="modifier_produit.php?id=<?php echo $produit['id']; ?>">Modifier</a>
                        <a href="supprimer_produit.php?id=<?php echo $produit['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table><br>
        <a href="dashboard.php" class="add-product-btn" style="background-color: red;">Retour tableau de bord </a>
    </div>
</body>
</html>

<?php
// Fermer la connexion
$connexion->close();
?>
