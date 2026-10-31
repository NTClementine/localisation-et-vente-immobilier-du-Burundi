

<?php
// Page d'accueil de INZU BURUNDI
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INZU BURUNDI - Immobilier au Burundi</title>

    <link rel="stylesheet" href="/localisation%20et%20vente/css/style.css">
</head>

<body>

    <!-- BARRE DE NAVIGATION -->
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


    <!-- SECTION PRINCIPALE -->
    <section class="hero">

        <div class="hero-content">

            <h1>
                Trouvez votre maison idéale
                <span>au Burundi !</span>
            </h1>

            <p>
                Achetez, louez et localisez facilement
                des biens immobiliers partout au Burundi.
            </p>

            <div class="hero-buttons">
                <a href="acheter.php" class="btn-orange">
                    Acheter une maison
                </a>

                <a href="louer.php" class="btn-white">
                    Louer une maison
                </a>
            </div>

        </div>

    </section>


    <!-- SECTION RECHERCHE -->
    <section class="search-section">

        <h2>Rechercher un bien immobilier</h2>

        <form action="acheter.php" method="GET" class="search-form">

            <div class="form-group">
                <label>Province</label>

                <select name="province">
                    <option value="">Choisir une province</option>
                    <option value="Bujumbura Mairie">Bujumbura Mairie</option>
                    <option value="Gitega">Gitega</option>
                    <option value="Ngozi">Ngozi</option>
                    <option value="Burunga">Burunga</option>
                    <option value="Butanyerera">Butanyerera</option>
                    <option value="Buhumuza">Buhumuza</option>
                </select>
            </div>

            <div class="form-group">
                <label>Commune</label>
                <input type="text" name="commune"
                    placeholder="Ex : Mukaza">
            </div>

            <div class="form-group">
                <label>Type de bien</label>

                <select name="type_bien">
                    <option value="">Tous les biens</option>
                    <option value="Maison">Maison</option>
                    <option value="Appartement">Appartement</option>
                    <option value="Terrain">Terrain</option>
                </select>
            </div>

            <div class="form-group">
                <label>Budget maximum (FBu)</label>
                <input type="number" name="prix_max"
                    placeholder="Ex : 250000000">
            </div>

            <button type="submit" class="search-btn">
                Rechercher
            </button>

        </form>

    </section>


    <!-- MAISONS RECEMMENT PUBLIEES -->
    <section class="properties">

        <h2>Biens immobiliers récemment publiés</h2>

        <p class="section-description">
            Découvrez quelques biens immobiliers disponibles au Burundi.
        </p>

        <div class="property-container">

            <div class="property-card">
                <img src="image/ig1.png" alt="Maison moderne">

                <div class="property-info">
                    <h3>Maison moderne à Rohero</h3>
                    <p>📍 Rohero, Bujumbura</p>
                    <h4>250 000 000 FBu</h4>
                    <p>4 chambres | 3 salles de bains</p>
                <a href="detail.php?id=5" class="btn">
    Voir détail
</a>
                </div>
            </div>

            <div class="property-card">
                <img src="image/im2.jpg" alt="Appartement">

                <div class="property-info">
                    <h3>Appartement à Kinindo</h3>
                    <p>📍 Kinindo, Bujumbura</p>
                    <h4>1 000 000 FBu / mois</h4>
                    <p>3 chambres | 2 salles de bains</p>
                    <a href="detail.php?id=4" class="btn">
                        Voir détail
                    </a>
                </div>
            </div>

            <div class="property-card">
                <img src="image/ig2.png" alt="Maison familiale">

                <div class="property-info">
                    <h3>Maison familiale à Gitega</h3>
                    <p>📍 Gitega, Burundi</p>
                    <h4>180 000 000 FBu</h4>
                    <p>5 chambres | 3 salles de bains</p>
         <a href="detail.php?id=6" class="btn">
                    Voir détail
                </a>
                </div>
            </div>

        </div>

    </section>


    <!-- NOS SERVICES -->
    <section class="services">

        <h2>Nos services</h2>

        <div class="services-container">

            <div class="service-card">
                <h3>Acheter</h3>
                <p>Trouvez une maison ou un terrain à acheter.</p>
            </div>

            <div class="service-card">
                <h3>Louer</h3>
                <p>Trouvez un logement adapté à votre budget.</p>
            </div>

            <div class="service-card">
                <h3>Publier</h3>
                <p>Publiez facilement votre bien immobilier.</p>
            </div>

            <div class="service-card">
                <h3>Localiser</h3>
                <p>Retrouvez les biens selon leur localisation.</p>
            </div>

        </div>

    </section>


    <!-- PIED DE PAGE -->
    <footer>

        <h2>INZU BURUNDI</h2>

        <p>
            Votre partenaire pour trouver un logement
            partout au Burundi.
        </p>
        <p>&copy; 2026 INZU BURUNDI. Tous droits réservés.</p>

    </footer>

</body>
</html>
