
<?php

require_once __DIR__ . "/include/database.php";

$message = "";
$type_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recuperer les informations du formulaire
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telephone = trim($_POST["telephone"] ?? "");

    $mot_de_passe = $_POST["mot_de_passe"] ?? "";
    $confirmation = $_POST["confirmation"] ?? "";

    $role = $_POST["role"] ?? "";
    $est_etudiant = $_POST["est_etudiant"] ?? "";

    $etablissement = trim($_POST["etablissement"] ?? "");
    $niveau_etude = $_POST["niveau_etude"] ?? "";

    // Verifier les champs obligatoires
    if (
        $nom == "" || $prenom == "" ||
        $email == "" || $telephone == "" ||
        $mot_de_passe == "" || $confirmation == "" ||
        $role == "" || $est_etudiant == ""
    ) {
        $message = "Veuillez remplir tous les champs obligatoires.";
        $type_message = "erreur";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Adresse email invalide.";
        $type_message = "erreur";

    } elseif (!in_array($role, ["acheteur", "proprietaire"], true)) {

        $message = "Type de compte invalide.";
        $type_message = "erreur";

    } elseif (!in_array($est_etudiant, ["0", "1"], true)) {

        $message = "Veuillez préciser si vous êtes étudiant.";
        $type_message = "erreur";

    } elseif (strlen($mot_de_passe) < 8) {

        $message = "Le mot de passe doit contenir au moins 8 caractères.";
        $type_message = "erreur";

    } elseif ($mot_de_passe !== $confirmation) {

        $message = "Les deux mots de passe ne correspondent pas.";
        $type_message = "erreur";

    } elseif (
        $est_etudiant === "1" &&
        ($etablissement == "" || $niveau_etude == "")
    ) {

        $message = "Veuillez préciser votre établissement et votre niveau.";
        $type_message = "erreur";

    } else {

        // Gerer les informations des etudiants
        if ($est_etudiant === "0") {
            $etablissement = null;
            $niveau_etude = null;
        }

        // Securiser le mot de passe
        $mot_de_passe_hash = password_hash(
            $mot_de_passe,
            PASSWORD_DEFAULT
        );

        // Preparer la requete SQL
        $sql = "INSERT INTO utilisateurs
        (nom, prenom, email, telephone, mot_de_passe,
         role, est_etudiant, etablissement, niveau_etude)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $requete = $connexion->prepare($sql);

        if ($requete) {

            $requete->bind_param(
                "ssssssiss",
                $nom,
                $prenom,
                $email,
                $telephone,
                $mot_de_passe_hash,
                $role,
                $est_etudiant,
                $etablissement,
                $niveau_etude
            );

            if ($requete->execute()) {

                $message = "Inscription réussie ! Votre compte a été créé.";
                $type_message = "succes";

            } else {

                if ($requete->errno == 1062) {
                    $message = "Cette adresse email est déjà utilisée.";
                } else {
                    $message = "Une erreur est survenue lors de l'inscription.";
                }

                $type_message = "erreur";
            }

            $requete->close();

        } else {

            $message = "Impossible de préparer la requête.";
            $type_message = "erreur";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - INZU BURUNDI</title>
    <link rel="stylesheet" href="/localisation%20et%20vente/css/style.css">
</head>

<body class="inscription-page">

    <header class="inscription-header">
        <a href="index.php" class="logo">
            INZU <span>BURUNDI</span>
        </a>
        <a href="index.php" class="retour">Retour à l'accueil</a>
    </header>

    <main class="inscription-main">

        <section class="formulaire-card">

            <div class="formulaire-titre">
                <p class="petit-titre">BIENVENUE SUR INZU BURUNDI</p>
                <h1>Créer un compte</h1>
                <p>Inscrivez-vous pour accéder à nos services immobiliers.</p>
            </div>

            <!-- Garde ici ton bloc PHP d'affichage des messages
                 de succès et d'erreur déjà présent dans ton fichier. -->

            <form method="POST" action="inscription.php">

                <h3>Informations personnelles</h3>

                <div class="champs-deux">
                    <div class="champ">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom"
                               placeholder="Votre nom" required>
                    </div>

                    <div class="champ">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom"
                               placeholder="Votre prénom" required>
                    </div>
                </div>

                <div class="champ">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email"
                           placeholder="exemple@email.com" required>
                </div>

                <div class="champ">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone"
                           placeholder="+257 ..." required>
                </div>

                <h3 class="titre-compte">Informations du compte</h3>

                <div class="champ">
                    <label for="role">Type de compte</label>
                    <select id="role" name="role" required>
                        <option value="">Choisir un type de compte</option>
                        <option value="acheteur">Acheteur / Locataire</option>
                        <option value="proprietaire">Propriétaire</option>
                    </select>
                </div>

                <div class="champ">
                    <label for="est_etudiant">Êtes-vous étudiant(e) ?</label>
                    <select id="est_etudiant" name="est_etudiant" required>
                        <option value="">Choisir une réponse</option>
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>

                <div id="informations-etudiant" class="etudiant-cache">
                    <div class="champ">
                        <label for="etablissement">Établissement</label>
                        <input type="text" id="etablissement"
                               name="etablissement"
                               placeholder="Nom de votre établissement">
                    </div>

                    <div class="champ">
                        <label for="niveau_etude">Niveau d'études</label>
                        <select id="niveau_etude" name="niveau_etude">
                            <option value="">Choisir votre niveau</option>
                            <option value="BAC1">BAC 1</option>
                            <option value="BAC2">BAC 2</option>
                            <option value="BAC3">BAC 3</option>
                            <option value="Master">Master</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                </div>

                <div class="champ">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe"
                           name="mot_de_passe"
                           placeholder="Minimum 8 caractères" required>
                </div>

                <div class="champ">
                    <label for="confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="confirmation"
                           name="confirmation"
                           placeholder="Répétez votre mot de passe" required>
                </div>

                <button type="submit" class="bouton-inscription">
                    Créer mon compte
                </button>

                <p class="connexion-lien">
                    Vous avez déjà un compte ?
                    <a href="connexion.php">Se connecter</a>
                </p>

            </form>
        </section>

    </main>

    <script>
        const choixEtudiant = document.getElementById("est_etudiant");
        const informationsEtudiant =
            document.getElementById("informations-etudiant");

        function afficherInformationsEtudiant() {
            if (choixEtudiant.value === "1") {
                informationsEtudiant.style.display = "block";
            } else {
                informationsEtudiant.style.display = "none";
            }
        }

        choixEtudiant.addEventListener("change",
            afficherInformationsEtudiant);

        afficherInformationsEtudiant();
    </script>

</body>
</html>
