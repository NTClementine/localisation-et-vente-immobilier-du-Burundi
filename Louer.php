<?php
require_once __DIR__ . "/include/database.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Louer - INZU BURUNDI</title>
    <link rel="stylesheet" href="/localisation%20et%20vente/css/style.css">
</head>
<body>
     <header>
        <nav class="navbar">

            <div class="logo">
                INZU <span>BURUNDI</span>
            </div>

            <ul class="nav-links">
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="acheter.php">Acheter</a></li>
                <li><a href="louer.php">Louer</a></li>
                <li><a href="publier.php">Publier une maison</a></li>
            </ul>

            <div class="nav-buttons">
                <a href="connexion.php" class="login">Connexion</a>
                <a href="inscription.php" class="register">S'inscrire</a>
            </div>

        </nav>
    </header>
    
</body>
</html>

<?php
$sql = "SELECT b.*, l.nom AS localisation,
        (SELECT chemin_image
         FROM images_biens
         WHERE bien_id = b.id
         ORDER BY id ASC
         LIMIT 1) AS photo
        FROM biens_immobiliers b
        INNER JOIN localisations l
        ON b.localisation_id = l.id
        WHERE b.transaction = 'location'
        AND b.statut = 'disponible'
        ORDER BY b.date_publication DESC";

$resultat = $connexion->query($sql);
?>

<div class="properties-grid">

    <?php if ($resultat && $resultat->num_rows > 0): ?>

        <?php while ($bien = $resultat->fetch_assoc()): ?>

            <div class="property-card">

                <img
                    src="<?php echo htmlspecialchars($bien['photo'] ?? 'image/maison1.jpg'); ?>"
                    alt="Photo du bien immobilier"
                >

                <div class="property-info">

                    <h3>
                        <?php echo htmlspecialchars($bien['titre']); ?>
                    </h3>

                    <p>
                        Localisation :
                        <?php echo htmlspecialchars($bien['localisation']); ?>
                    </p>

                    <p>
                        Type :
                        <?php echo htmlspecialchars($bien['type_bien']); ?>
                    </p>

                    <p>
                        <?php echo number_format($bien['prix'], 0, ',', ' '); ?> FBU / mois
                    </p>

                    <p>
                        <?php echo htmlspecialchars($bien['description'] ?? ''); ?>
                    </p>

                    <a href="detail.php?id=<?= $bien['id'] ?>" class="btn">
                        Voir détail
                    </a>

                </div>
            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <p>Aucun bien immobilier disponible à la location.</p>

    <?php endif; ?>

</div>
