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

// Vérifier si l'identifiant de la facture est passé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_facture = $_GET['id'];

    // Préparer la requête pour récupérer les détails de la facture
    $requete = $connexion->prepare("SELECT * FROM factures WHERE id = ?");
    $requete->bind_param("i", $id_facture);
    $requete->execute();
    $resultat = $requete->get_result();

    if ($resultat->num_rows > 0) {
        $facture = $resultat->fetch_assoc();
    } else {
        echo "Facture non trouvée.";
        exit;
    }

    $requete->close();

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Supprimer les produits associés à la facture
        $requete_supprimer_produits = $connexion->prepare("DELETE FROM produits_facture WHERE id_facture = ?");
        $requete_supprimer_produits->bind_param("i", $id_facture);
        $requete_supprimer_produits->execute();
        $requete_supprimer_produits->close();

        // Supprimer la facture
        $requete_supprimer_facture = $connexion->prepare("DELETE FROM factures WHERE id = ?");
        $requete_supprimer_facture->bind_param("i", $id_facture);
        
        if ($requete_supprimer_facture->execute()) {
            echo "Facture supprimée avec succès !";
        } else {
            echo "Erreur : " . $connexion->error;
        }

        $requete_supprimer_facture->close();
    }

    // Fermer la connexion
    $connexion->close();
} else {
    echo "Identifiant de facture invalide.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer la Facture</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .message {
            background-color: #e74c3c;
            color: white;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }
        .message.success {
            background-color: #2ecc71;
        }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            font-weight: bold;
        }
        button {
            background-color: #e74c3c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #c0392b;
        }
        a {
            text-decoration: none;
            color: #2980b9;
        }
        a:hover {
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Supprimer la Facture</h1>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="message success">La facture a été supprimée avec succès !</div>
        <?php else: ?>
            <p>Êtes-vous sûr de vouloir supprimer la facture suivante ?</p>
            <table>
                <tr>
                    <td>Numéro de Facture:</td>
                    <td><?php echo htmlspecialchars($facture['numero_facture']); ?></td>
                </tr>
                <tr>
                    <td>Nom du Client:</td>
                    <td><?php echo htmlspecialchars($facture['nom_client']); ?></td>
                </tr>
                <tr>
                    <td>Code Client:</td>
                    <td><?php echo htmlspecialchars($facture['code_client']); ?></td>
                </tr>
                <tr>
                    <td>Adresse Client:</td>
                    <td><?php echo htmlspecialchars($facture['adresse_client']); ?></td>
                </tr>
                <tr>
                    <td>Total H.T:</td>
                    <td><?php echo htmlspecialchars($facture['total_ht']); ?></td>
                </tr>
                <tr>
                    <td>Total TVA:</td>
                    <td><?php echo htmlspecialchars($facture['total_tva']); ?></td>
                </tr>
                <tr>
                    <td>Total TTC:</td>
                    <td><?php echo htmlspecialchars($facture['total_ttc']); ?></td>
                </tr>
            </table>
            <form method="POST">
                <div class="form-group">
                    <button type="submit">Confirmer la Suppression</button>
                    <a href="historique_factures.php">Annuler</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
