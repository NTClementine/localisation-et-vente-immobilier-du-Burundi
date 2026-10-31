
<?php
session_start();

require_once __DIR__ . "/include/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $mot_de_passe = $_POST["mot_de_passe"];

    if (empty($email) || empty($mot_de_passe)) {

        $message = "Veuillez remplir tous les champs.";

    } else {

        $sql = "SELECT id, nom, prenom, email, mot_de_passe, role
                FROM utilisateurs
                WHERE email = ?";

        $requete = $connexion->prepare($sql);
        $requete->bind_param("s", $email);
        $requete->execute();

        $resultat = $requete->get_result();

        if ($utilisateur = $resultat->fetch_assoc()) {

            if (password_verify(
                $mot_de_passe,
                $utilisateur["mot_de_passe"]
            )) {

                // Enregistrer les informations dans la session
                session_regenerate_id(true);

                $_SESSION["id"] = $utilisateur["id"];
                $_SESSION["nom"] = $utilisateur["nom"];
                $_SESSION["prenom"] = $utilisateur["prenom"];
                $_SESSION["email"] = $utilisateur["email"];
                $_SESSION["role"] = $utilisateur["role"];

                // Rediriger vers la page d'accueil
                header("Location: index.php");
                exit();

            } else {
                $message = "Adresse e-mail ou mot de passe incorrect.";
            }

        } else {
            $message = "Adresse e-mail ou mot de passe incorrect.";
        }

        $requete->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - INZU BURUNDI</title>
    <link rel="stylesheet" href="/localisation%20et%20vente/css/style.css">
</head>

<body class="connexion-page">

    <header class="connexion-header">

        <a href="index.php" class="logo">
            INZU <span>BURUNDI</span>
        </a>

        <a href="index.php" class="retour">Retour à l'accueil</a>

    </header>

    <main class="connexion-main">

        <section class="connexion-card">

            <div class="connexion-titre">

                <p class="connexion-petit-titre">
                    BIENVENUE SUR INZU BURUNDI
                </p>

                <h1>Se connecter</h1>

                <p>
                    Accédez à votre compte pour découvrir nos services immobiliers.
                </p>

            </div>

            <?php if (!empty($message)): ?>
                <div class="message-connexion">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="connexion.php" method="POST">

                <div class="champ-connexion">

                    <label for="email">Adresse e-mail</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="exemple@email.com"
                        required
                    >

                </div>

                <div class="champ-connexion">

                    <label for="mot_de_passe">Mot de passe</label>

                    <input
                        type="password"
                        id="mot_de_passe"
                        name="mot_de_passe"
                        placeholder="Entrez votre mot de passe"
                        required
                    >

                </div>

                <button type="submit" class="bouton-connexion">
                    Se connecter
                </button>

            </form>

            <p class="inscription-lien">
                Vous n'avez pas encore de compte ?
                <a href="inscription.php">Créer un compte</a>
            </p>

        </section>

    </main>

</body>
</html>
