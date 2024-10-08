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

// Récupérer toutes les factures
$resultat_factures = $connexion->query("SELECT * FROM factures");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Factures</title>
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
    <h1>Historique des Factures</h1>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <thead>
            <tr>
                <th>Numéro de Facture</th>
                <th>Nom du Client</th>
                <th>Date de la Facture</th>
                <th>Total TTC</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($facture = $resultat_factures->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $facture['numero_facture']; ?></td>
                <td><?php echo $facture['nom_client']; ?></td>
                <td><?php echo $facture['date_facture']; ?></td>
                <td><?php echo $facture['total_ttc']; ?></td>
                <td>
                    <a href="imprimer_facture.php?id=<?php echo $facture['id']; ?>">Imprimer</a>
                    <a href="supprimer_facture.php?id=<?php echo $facture['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?');">Supprimer</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <button  class="btn btn-danger" style="background-color: red; color:wheat;"><a href="dashboard.php">Retour Tableau de bord</a></button>

    </table>
</body>
</html>

<?php
$connexion->close();
?>
