
<?php
session_start();

require_once __DIR__ . "/include/database.php";

$message = "";
$succes = "";

// Vérifier si l'utilisateur est connecté et propriétaire
if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "proprietaire") {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = trim($_POST["titre"]);
    $description = trim($_POST["description"]);
    $localisation_id = (int) $_POST["localisation_id"];
    $type_bien = $_POST["type_bien"];
    $transaction = $_POST["transaction"];
    $prix = (float) $_POST["prix"];
    $chambres = (int) $_POST["nombre_chambres"];
    $salles_bain = (int) $_POST["nombre_salles_bain"];

    $proprietaire_id = (int) $_SESSION["id"];

    // Vérifier les informations
    if (
        $titre === "" ||
        $localisation_id <= 0 ||
        $prix <= 0 ||
        !in_array($type_bien, ["maison", "appartement", "terrain"]) ||
        !in_array($transaction, ["vente", "location"])
    ) {
        $message = "Veuillez remplir correctement les informations.";
    } elseif (
        !isset($_FILES["photo"]) ||
        $_FILES["photo"]["error"] !== UPLOAD_ERR_OK
    ) {
        $message = "Veuillez sélectionner une photo valide.";
    } elseif ($_FILES["photo"]["size"] > 5 * 1024 * 1024) {
        $message = "La photo ne doit pas dépasser 5 Mo.";
    } else {

        // Vérifier le type réel de la photo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES["photo"]["tmp_name"]);

        $extensions = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        ];

        if (!isset($extensions[$mime]) ||
            getimagesize($_FILES["photo"]["tmp_name"]) === false) {

            $message = "Format accepté : JPG, PNG ou WEBP.";

        } else {

            // Vérifier que la localisation existe
            $verif = $connexion->prepare(
                "SELECT id FROM localisations WHERE id = ?"
            );
            $verif->bind_param("i", $localisation_id);
            $verif->execute();
            $localisationExiste = $verif->get_result()->num_rows > 0;
            $verif->close();

            if (!$localisationExiste) {
                $message = "Veuillez choisir une localisation valide.";
            } else {

                $nomPhoto = "bien_" . uniqid() . "." . $extensions[$mime];
                $cheminPhoto = "image/" . $nomPhoto;
                $destination = __DIR__ . "/" . $cheminPhoto;

                if (move_uploaded_file(
                    $_FILES["photo"]["tmp_name"],
                    $destination
                )) {

                    try {
                        $connexion->begin_transaction();

                        // Enregistrer le bien immobilier
                        $sql = "INSERT INTO biens_immobiliers
                        (proprietaire_id, localisation_id, titre,
                        description, type_bien, transaction, prix,
                        nombre_chambres, nombre_salles_bain, statut)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'disponible')";

                        $requete = $connexion->prepare($sql);

                        $requete->bind_param(
                            "iissssdii",
                            $proprietaire_id,
                            $localisation_id,
                            $titre,
                            $description,
                            $type_bien,
                            $transaction,
                            $prix,
                            $chambres,
                            $salles_bain
                        );

                        $requete->execute();

                        $bien_id = $connexion->insert_id;
                        $requete->close();

                        // Enregistrer le chemin de la photo
                        $sqlPhoto = "INSERT INTO images_biens
                        (bien_id, chemin_image) VALUES (?, ?)";

                        $requetePhoto = $connexion->prepare($sqlPhoto);
                        $requetePhoto->bind_param(
                            "is",
                            $bien_id,
                            $cheminPhoto
                        );
                        $requetePhoto->execute();
                        $requetePhoto->close();

                        $connexion->commit();

                        $succes = "Votre bien immobilier a été publié avec succès !";

                    } catch (Exception $e) {
                        $connexion->rollback();
                        if (file_exists($destination)) {
                            unlink($destination);
                        }
                        $message = "Erreur lors de l'enregistrement du bien.";
                    }

                } else {
                    $message = "Impossible d'enregistrer la photo.";
                }
            }
        }
    }
}

// Récupérer les localisations
$localisations = $connexion->query(
    "SELECT id, nom FROM localisations ORDER BY nom ASC"
);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un bien - INZU BURUNDI</title>

    <link rel="stylesheet" href="/localisation%20et%20vente/css/style.css">
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

<main class="publication-container">

    <h1>Publier un bien immobilier</h1>

    <p class="publication-description">
        Ajoutez votre maison, appartement ou terrain sur INZU BURUNDI.
    </p>

    <?php if ($message !== ""): ?>
        <p class="message-erreur">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <?php if ($succes !== ""): ?>
        <p class="message-succes">
            <?= htmlspecialchars($succes) ?>
        </p>

        <p>
            <a href="acheter.php">Voir les biens à vendre</a>
            |
            <a href="louer.php">Voir les biens à louer</a>
        </p>
    <?php endif; ?>

    <form class="publication-form"
          method="POST"
          action="publier.php"
          enctype="multipart/form-data">

        <label>Titre du bien</label>
        <input type="text" name="titre" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <label>Localisation</label>
        <select name="localisation_id" required>
            <option value="">Choisir une province</option>

            <?php while ($lieu = $localisations->fetch_assoc()): ?>
                <option value="<?= (int) $lieu["id"] ?>">
                    <?= htmlspecialchars($lieu["nom"]) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Type de bien</label>
        <select name="type_bien" required>
            <option value="maison">Maison</option>
            <option value="appartement">Appartement</option>
            <option value="terrain">Terrain</option>
        </select>

        <label>Type de transaction</label>
        <select name="transaction" required>
            <option value="vente">Vente</option>
            <option value="location">Location</option>
        </select>

        <label>Prix (en FBu)</label>
        <input type="number" name="prix" min="1" step="0.01" required>

        <label>Nombre de chambres</label>
        <input type="number" name="nombre_chambres" min="0" value="0">

        <label>Nombre de salles de bain</label>
        <input type="number" name="nombre_salles_bain" min="0" value="0">

        <label>Photo du bien</label>
        <input type="file" name="photo"
               accept=".jpg,.jpeg,.png,.webp" required>

        <button type="submit" class="btn-publication">
            Publier le bien
        </button>

    </form>

</main>

</body>
</html>
