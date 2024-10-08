<?php
// Connexion à la base de données
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

// Récupérer l'ID de la facture à imprimer
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Requête pour obtenir les détails de la facture
$sql = "SELECT * FROM factures WHERE id = ?";
$requete = $connexion->prepare($sql);
$requete->bind_param("i", $id);
$requete->execute();
$resultat = $requete->get_result();

// Vérifier si la facture existe
if ($resultat->num_rows == 0) {
    die("Facture introuvable.");
}

// Récupérer les informations de la facture
$facture = $resultat->fetch_assoc();

// Fermer la connexion
$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression Facture</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .facture-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        .header-facture, .footer-facture {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-facture h2 {
            margin-bottom: 5px;
        }
        .header-facture p {
            margin: 0;
        }
        .info-facture, .client-info, .produits-info {
            margin-bottom: 20px;
        }
        .info-facture table, .produits-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-facture th, .info-facture td, .produits-info th, .produits-info td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .produits-info th {
            background-color: #f4f4f4;
        }
        .totaux {
            float: right;
            width: 300px;
            margin-top: 20px;
            text-align: right;
        }
        .totaux p {
            font-size: 18px;
        }
        .signature {
            margin-top: 50px;
        }
        .signature div {
            display: inline-block;
            width: 48%;
            text-align: center;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="facture-container">
        <div class="header-facture">
            <h2>Quincaillerie Salem</h2>
            <p>Adresse : Khelideia, Ben Arous, 2054</p>
            <p>Téléphone : 55802189 | RIB : 0000012222233333665555</p>
        </div>

        <div class="info-facture">
            <table>
                <tr>
                    <th>Facture N°</th>
                    <td><?php echo $facture['numero_facture']; ?></td>
                    <th>Date</th>
                    <td><?php echo $facture['date_facture']; ?></td>
                </tr>
                <tr>
                    <th>Nom du client</th>
                    <td><?php echo $facture['nom_client']; ?></td>
                    <th>Code client</th>
                    <td><?php echo $facture['code_client']; ?></td>
                </tr>
                <tr>
                    <th>Adresse client</th>
                    <td colspan="3"><?php echo $facture['adresse_client']; ?></td>
                </tr>
            </table>
        </div>

        <div class="produits-info">
            <table>
                <thead>
                    <tr>
                        <th>Nom du produit</th>
                        <th>Code produit</th>
                        <th>Quantité</th>
                        <th>P.U. HT</th>
                        <th>REM</th>
                        <th>TVA</th>
                        <th>P.U. TTC</th>
                        <th>Montant TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Simulation des produits (vous devez faire une requête à une table produits_factures)
                    $produits = [
                        ['nom' => 'Produit 1', 'code' => 'P001', 'quantite' => 2, 'pu_ht' => 100, 'rem' => 10, 'tva' => 19, 'pu_ttc' => 119, 'montant_ttc' => 238],
                        ['nom' => 'Produit 2', 'code' => 'P002', 'quantite' => 1, 'pu_ht' => 200, 'rem' => 5, 'tva' => 19, 'pu_ttc' => 238, 'montant_ttc' => 238]
                    ];
                    $total_ht = 0;
                    $total_tva = 0;
                    $total_ttc = 0;
                    foreach ($produits as $produit) {
                        $total_ht += $produit['pu_ht'] * $produit['quantite'];
                        $total_tva += ($produit['pu_ht'] * $produit['tva'] / 100) * $produit['quantite'];
                        $total_ttc += $produit['montant_ttc'];
                        echo "<tr>
                                <td>{$produit['nom']}</td>
                                <td>{$produit['code']}</td>
                                <td>{$produit['quantite']}</td>
                                <td>{$produit['pu_ht']} TND</td>
                                <td>{$produit['rem']}%</td>
                                <td>{$produit['tva']}%</td>
                                <td>{$produit['pu_ttc']} TND</td>
                                <td>{$produit['montant_ttc']} TND</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="totaux">
            <p><strong>Total HT:</strong> <?php echo $total_ht; ?> TND</p>
            <p><strong>Total TVA:</strong> <?php echo $total_tva; ?> TND</p>
            <p><strong>Total TTC:</strong> <?php echo $total_ttc; ?> TND</p>
        </div>

        <div class="signature">
            <div>
                <p>Signature du client</p>
                <p>__________________________</p>
            </div>
            <div>
                <p>Signature de Salem</p>
                <p>__________________________</p>
            </div>
        </div>

        <div class="print-button">
            <button onclick="window.print()">Imprimer la facture</button>
        </div>
    </div>
</body>
</html>
