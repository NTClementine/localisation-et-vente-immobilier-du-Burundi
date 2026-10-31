

<?php
require_once __DIR__ . "/include/database.php";

?>



</div>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acheter un bien - INZU BURUNDI</title>
    <link rel="stylesheet"href="/localisation%20et%20vente/css/style.css">
</head>
<body>

<header class="acheter-header">
    <a href="index.php" class="logo">INZU <span>BURUNDI</span></a>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="acheter.php" class="active">Acheter</a>
        <a href="louer.php">Louer</a>
        <a href="publier.php">Publier</a>
        <a href="connexion.php">Connexion</a>
    </nav>
</header>

<section class="acheter-hero">
    <div class="acheter-hero-contenu">
        <h1>Trouvez votre future maison <br><strong>au Burundi</strong> </h1>
        <p>Découvrez des maisons, villas, appartements et terrains à vendre.</p>
    </div>
</section>

<main class="acheter-container">

    <section class="recherche-bien">
        <h2>Rechercher un bien immobilier</h2>

        <form action="acheter.php" method="GET">
            <input type="text" name="localisation"
                   placeholder="Province ou commune">

            <select name="type">
                <option value="">Tous les biens</option>
                <option value="Maison">Maison</option>
                <option value="Villa">Villa</option>
                <option value="Appartement">Appartement</option>
                <option value="Terrain">Terrain</option>
            </select>

            <input type="number" name="prix_max"
                   placeholder="Prix maximum (BIF)">

            <button type="submit">Rechercher</button>
        </form>
    </section>

    <section class="liste-biens">
        <h2>Biens disponibles à la vente</h2>
        
        <?php
            $sql = "SELECT b.*,
                    (SELECT chemin_image
                    FROM images_biens
                    WHERE bien_id = b.id
                    ORDER BY id ASC
                    LIMIT 1) AS photo
                    FROM biens_immobiliers b
                    WHERE b.transaction = 'vente'
                    ORDER BY b.date_publication DESC";

            $resultat = $connexion->query($sql);
        ?>

        <div class="biens-grille">

            <?php if ($resultat && $resultat->num_rows > 0): ?>

                <?php while ($bien = $resultat->fetch_assoc()): ?>

                    <article class="bien-card">

                        <?php if (!empty($bien['photo'])): ?>
                            <img
                                src="<?php echo htmlspecialchars($bien['photo']); ?>"
                                alt="Photo du bien immobilier">
                        <?php else: ?>
                            <img
                                src="images/maison1.jpg"
                                alt="Photo non disponible">
                        <?php endif; ?>

                        <div class="bien-infos">

                            <span class="bien-categorie">
                                <?php echo htmlspecialchars($bien['type_bien']); ?>
                            </span>

                            <h3>
                                <?php echo htmlspecialchars($bien['titre']); ?>
                            </h3>

                            <p class="bien-prix">
                                <?php echo number_format($bien['prix'], 0, ',', ' '); ?>
                                BIF
                            </p>

                            <p>
                                <?php echo htmlspecialchars($bien['description']); ?>
                            </p>

                            <a href="detail.php?id=<?= $bien['id'] ?>" class="btn">
                            Voir détail
                        </a>

                        </div>
                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <p>Aucun bien immobilier disponible à la vente pour le moment.</p>

            <?php endif; ?>
    </section>
    
</main>

<footer class="acheter-footer">
    <p>© 2026 INZU BURUNDI - Trouvez votre bien immobilier au Burundi.</p>
</footer>

</body>
</html>
