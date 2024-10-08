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

    // Récupérer les données de la facture
    $numero_facture = $_POST['numero_facture'];
    $nom_client = $_POST['nom_client'];
    $code_client = $_POST['code_client'];
    $adresse_client = $_POST['adresse_client'];
    $total_ht = $_POST['total_ht'];
    $total_tva = $_POST['total_tva'];
    $total_ttc = $_POST['total_ttc'];
    $date_facture = date('Y-m-d');

    // Insérer les données de la facture dans la table factures
    $requete_facture = $connexion->prepare("INSERT INTO factures (numero_facture, nom_client, code_client, adresse_client, date_facture, total_ht, total_tva, total_ttc) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $requete_facture->bind_param("ssssdddd", $numero_facture, $nom_client, $code_client, $adresse_client, $date_facture, $total_ht, $total_tva, $total_ttc);
    
    if ($requete_facture->execute()) {
        $id_facture = $requete_facture->insert_id;
        
        // Récupérer les produits de la facture
        foreach ($_POST['produits'] as $produit) {
            $code_produit = $produit['code_produit'];
            $designation_produit = $produit['designation_produit'];
            $quantite = $produit['quantite'];
            $prix_unitaire_ht = $produit['prix_unitaire_ht'];
            $remise = $produit['remise'];
            $tva = $produit['tva'];
            $prix_unitaire_ttc = $produit['prix_unitaire_ttc'];
            $montant_ttc = $produit['montant_ttc'];

            // Insérer les produits dans la table produits_facture
            $requete_produit = $connexion->prepare("INSERT INTO produits_facture (id_facture, code_produit, designation_produit, quantite, prix_unitaire_ht, remise, tva, prix_unitaire_ttc, montant_ttc) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $requete_produit->bind_param("issiddddd", $id_facture, $code_produit, $designation_produit, $quantite, $prix_unitaire_ht, $remise, $tva, $prix_unitaire_ttc, $montant_ttc);
            $requete_produit->execute();
        }

        echo "Facture ajoutée avec succès !";
    } else {
        echo "Erreur : " . $connexion->error;
    }

    // Fermer la connexion
    $requete_facture->close();
    $connexion->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Facture</title>
    <style>
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    color: #333;
}

/* Header Styling */
h1, h2, h3 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* Form Styling */
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 900px;
    margin: 0 auto;
}

/* Input Group Styling */
input[type="text"], input[type="number"], textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

label {
    font-weight: bold;
}

/* Button Styling */
button {
    background-color: #27ae60;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2ecc71;
}

button[type="button"] {
    background-color: #2980b9;
}

button[type="button"]:hover {
    background-color: #3498db;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
}

th {
    background-color: #34495e;
    color: white;
    font-weight: bold;
}

td {
    background-color: #ecf0f1;
}

tfoot td {
    font-weight: bold;
}

/* Footer Styles */
footer {
    text-align: center;
    margin-top: 40px;
}

footer p {
    margin: 10px 0;
    font-weight: bold;
}

/* Actions (Links for Imprimer and Supprimer) */
td a {
    text-decoration: none;
    color: #2980b9;
    font-weight: bold;
    margin: 0 5px;
}

td a:hover {
    color: #3498db;
}

/* Container for the main content */
.container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Styling for Success/Error Messages */
.success-message, .error-message {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

.success-message {
    background-color: #2ecc71;
    color: white;
}

.error-message {
    background-color: #e74c3c;
    color: white;
}

    </style>
</head>
<body>
    <h1 style="text-align: center;">Entreprise Quincaillerie Salem</h1>
    <p style="text-align: center;">Adresse : Khelideia, Ben Arous, 2054</p>
    <p style="text-align: center;">Téléphone : 55802189 | RIB : 0000012222233333665555</p>
    <div style="text-align: right;">
        <h2>Bon de Livraison</h2>
        <div class="input-group">
        <label for="numero_facture">Numéro de facture</label>
        <input type="text" id="numero_facture" name="numero_facture" required>
    </div>    </div>

    <form action="ajouter_facture.php" method="POST">
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <tr>
                <td>Nom du Client : <input type="text" name="nom_client" required></td>
                <td>Code Client : <input type="text" name="code_client" required></td>
                <td>Adresse Client : <input type="text" name="adresse_client"></td>
            </tr>
        </table>

        <h3>Produits :</h3>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
            <thead>
                <tr>
                    <th>Code Produit</th>
                    <th>Désignation</th>
                    <th>Quantité</th>
                    <th>P.U. H.T</th>
                    <th>Remise (%)</th>
                    <th>TVA (%)</th>
                    <th>P.U. TTC</th>
                    <th>Montant TTC</th>
                </tr>
            </thead>
            <tbody id="produits">
                <tr>
                    <td><input type="text" name="produits[0][code_produit]" required></td>
                    <td><input type="text" name="produits[0][designation_produit]" required></td>
                    <td><input type="number" name="produits[0][quantite]" required></td>
                    <td><input type="number" step="0.01" name="produits[0][prix_unitaire_ht]" required></td>
                    <td><input type="number" step="0.01" name="produits[0][remise]" required></td>
                    <td><input type="number" step="0.01" name="produits[0][tva]" required></td>
                    <td><input type="number" step="0.01" name="produits[0][prix_unitaire_ttc]" required></td>
                    <td><input type="number" step="0.01" name="produits[0][montant_ttc]" required></td>
                </tr>
            </tbody>
        </table>

        <button type="button" onclick="ajouterProduit()">Ajouter un produit</button>

        <h3>Totaux :</h3>
        <p>Total H.T : <input type="number" step="0.01" name="total_ht" required></p>
        <p>Total TVA : <input type="number" step="0.01" name="total_tva" required></p>
        <p>Total TTC : <input type="number" step="0.01" name="total_ttc" required></p>

        <button type="submit">Créer la Facture</button>
        <button  class="btn btn-danger" style="background-color: red; color:wheat;"><a href="historique_factures.php">Liste des factures</a></button>

    </form>

    <script>
        let compteurProduits = 1;

        function ajouterProduit() {
            const tableauProduits = document.getElementById('produits');
            const nouvelleLigne = `<tr>
                <td><input type="text" name="produits[${compteurProduits}][code_produit]" required></td>
                <td><input type="text" name="produits[${compteurProduits}][designation_produit]" required></td>
                <td><input type="number" name="produits[${compteurProduits}][quantite]" required></td>
                <td><input type="number" step="0.01" name="produits[${compteurProduits}][prix_unitaire_ht]" required></td>
                <td><input type="number" step="0.01" name="produits[${compteurProduits}][remise]" required></td>
                <td><input type="number" step="0.01" name="produits[${compteurProduits}][tva]" required></td>
                <td><input type="number" step="0.01" name="produits[${compteurProduits}][prix_unitaire_ttc]" required></td>
                <td><input type="number" step="0.01" name="produits[${compteurProduits}][montant_ttc]" required></td>
            </tr>`;
            tableauProduits.insertAdjacentHTML('beforeend', nouvelleLigne);
            compteurProduits++;
        }
    </script>

    <footer>
        <div style="text-align: center; margin-top: 30px;">
            <p>Signature du Client ______________________</p>
            <p>Signature de Salem ______________________</p>
        </div>
    </footer>
</body>
</html>
