
<?php
require_once __DIR__ . "/include/database.php";

// Récupérer l'identifiant du bien
$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id <= 0) {
    die("Bien immobilier introuvable.");
}

// Récupérer les informations du bien et du propriétaire
$sql = "SELECT b.*, l.nom AS localisation,
               u.nom AS nom_proprietaire,
               u.prenom AS prenom_proprietaire,
               u.telephone AS telephone_proprietaire,
               (SELECT chemin_image
                FROM images_biens
                WHERE bien_id = b.id
                ORDER BY id ASC
                LIMIT 1) AS photo
        FROM biens_immobiliers b
        INNER JOIN localisations l
            ON b.localisation_id = l.id
        INNER JOIN utilisateurs u
            ON b.proprietaire_id = u.id
        WHERE b.id = ?";

$requete = $connexion->prepare($sql);
$requete->bind_param("i", $id);
$requete->execute();

$resultat = $requete->get_result();
$bien = $resultat->fetch_assoc();

$requete->close();

if (!$bien) {
    die("Ce bien immobilier n'existe pas.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Détails du bien - INZU BURUNDI</title>

    <link rel="stylesheet"
    href="/localisation%20et%20vente/css/style.css">
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

<main class="detail-container">

    <h1><?= htmlspecialchars($bien["titre"]) ?></h1>

    <div class="detail-card">

        <?php if (!empty($bien["photo"]) &&
                  file_exists(__DIR__ . "/" . $bien["photo"])): ?>

            <img
                src="<?= htmlspecialchars($bien["photo"]) ?>"
                alt="Photo du bien immobilier"
                class="detail-image"
            >

        <?php else: ?>

            <p>Photo non disponible.</p>

        <?php endif; ?>

        <div class="detail-info">

            <h2>
                <?= number_format((float)$bien["prix"], 0, ",", " ") ?>
                FBu
            </h2>

            <p>
                <strong>Localisation :</strong>
                <?= htmlspecialchars($bien["localisation"]) ?>
            </p>

            <p>
                <strong>Type de bien :</strong>
                <?= htmlspecialchars($bien["type_bien"]) ?>
            </p>

            <p>
                <strong>Transaction :</strong>
                <?= htmlspecialchars($bien["transaction"]) ?>
            </p>

            <p>
                <strong>Chambres :</strong>
                <?= (int)$bien["nombre_chambres"] ?>
            </p>

            <p>
                <strong>Salles de bain :</strong>
                <?= (int)$bien["nombre_salles_bain"] ?>
            </p>

            <p>
                <strong>Statut :</strong>
                <?= htmlspecialchars($bien["statut"]) ?>
            </p>

            <h3>Description</h3>

            <p>
                <?= nl2br(htmlspecialchars(
                    $bien["description"] ?? "Aucune description disponible."
                )) ?>
            </p>

            <hr>

            <h3>Informations du propriétaire</h3>

            <p>
                <strong>Nom :</strong>
                <?= htmlspecialchars($bien["prenom_proprietaire"] . " " .
                    $bien["nom_proprietaire"]) ?>
            </p>

            <p>
                <strong>Téléphone :</strong>
                <?= htmlspecialchars($bien["telephone_proprietaire"]) ?>
            </p>

            <a href="acheter.php" class="btn">
                Retour aux biens
            </a>

        </div>

    </div>

</main>

</body>
</html>
