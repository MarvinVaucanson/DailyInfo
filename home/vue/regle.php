<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Règles du jeu</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div id="logo">
            <a href="/SAE/daily-info/index.php"><img src="img/Logo V2.svg" alt="logo" class="logo"></a>
        </div>
    </header>
    <div id="Barre"></div>
    <ul class="menu">
        <li><a class="menuItem" href="mainvue.php">Mission Du Jour</a></li>
        <li><a class="menuItem" href="scoreboard.php">Scoreboard</a></li>
        <li><a class="menuItem" href="regle.php">Règles</a></li>
        <?php
        if (isset($_SESSION['idUser'])) {
            echo '<li><a class="menuItem" href="compte.php">Compte</a></li>';
        } else {
            echo '<li><a class="menuItem" href="login.php">Connexion</a></li>';
        }
        echo '<li><a class="menuItem" href="compte.php">Compte</a></li>';
        echo '<li><a class="menuItem" href="https://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot">Bot Discord</a></li>'
            ?>
    </ul>

    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
        <i class="closeIcon"><img src="img/close_black_24dp.svg"></i>
    </button>
    <script src="js/script.js"></script>
    <div id="RèglesBoite">
        <div id="RèglesBG">
            <h1 class="Titre">Règles du jeu</h1>
            <h3 class="citation">“Ces règles sont obligatoires” -Tutel</h3>

            <section id="reglesListe">
                <h2>Concept du jeu</h2>
                <p>
                    Bienvenue dans le jeu quotidien! Chaque jour, vous recevrez une mission unique à accomplir sur
                    le
                    site.
                    Remplissez la mission pour gagner des points et grimper dans le classement du tableau des
                    scores.
                </p>
            </section>

            <section id="reglesEdition">
                <h2>Édition du Profil</h2>
                <p>
                    Personnalisez votre profil à votre guise. Ajoutez une photo, éditez votre pseudo, et suivez
                    votre
                    progression
                    à travers le nombre de parties jouées et vos combos réalisés. Attention pas d'image ou de pseudo
                    offensant.
                </p>
            </section>

            <section id="liensUtiles">
                <h2>Liens Utiles</h2>
                <ul>
                    <li><a href="https://forge.univ-lyon1.fr/p2202150/daily-info">GitLab du Projet</a></li>
                    <li><a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ">Vidéo du Projet sur YouTube</a></li>
                    <li><a href="https://iut.univ-lyon1.fr/">Site Web de l'IUT Lyon 1</a></li>
                </ul>
                <h2>Les créateurs</h2>
                <ul>
                    <li><a href="https://www.linkedin.com/in/baptiste-rousselot-00121b251/">Baptiste Rousselot</a>
                    </li>
                    <li><a href="https://www.linkedin.com/in/arnaud-jin/">Arnaud Jin</a></li>
                    <li><a href="https://www.linkedin.com/in/baptiste-rousselot-00121b251/">Emmanuel Ardoin</a>
                    </li>
                    <li><a href="https://www.linkedin.com/in/arnaud-jin/">Clément Carvalho</a></li>
                </ul>
            </section>
        </div>
    </div>
</body>

</html>