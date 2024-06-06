<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_name('Agent12');
session_start();

// Traitement des modifications
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../model/connexionbd.php';
    $bdd = connexion();
    include '../model/UserBD.php';
    if (isset($_POST['nouveauPseudo'])) {
        $nouveauPseudo = htmlspecialchars($_POST['nouveauPseudo']);
        if (!empty($nouveauPseudo)) {
            $_SESSION['pseudo'] = $nouveauPseudo;
        }
    }

    if (isset($_POST['nouveauEmail'])) {
        $nouveauEmail = htmlspecialchars($_POST['nouveauEmail']);
        if (!empty($nouveauEmail)) {
            $_SESSION['email'] = $nouveauEmail;
        }
    }

    if (isset($_POST['nouveauMdp'])) {
        $nouveauMdp = htmlspecialchars($_POST['nouveauMdp']);
        if (!empty($nouveauMdp)) {
            $_SESSION['mdp'] = $nouveauMdp;
        }
    }

    if (isset($_FILES['nouvellePhoto']) && $_FILES['nouvellePhoto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $dossierDestination = 'img/';
        $nomFichier = basename($_FILES['nouvellePhoto']['name']);
        $cheminFichier = $dossierDestination . $nomFichier;

        if (move_uploaded_file($_FILES['nouvellePhoto']['tmp_name'], $cheminFichier)) {
            $_SESSION['photo'] = $cheminFichier;
        } else {
            echo "Erreur lors du téléchargement du fichier.";
        }
    } else {
        $cheminFichier = $_SESSION['photo'];
    }

    if (isset($_POST['nouveauDiscordiD'])) {
        $nouveauDiscordiD = htmlspecialchars($_POST['nouveauDiscordiD']);
        if (!empty($nouveauDiscordiD)) {
            $_SESSION['discordID'] = $nouveauDiscordiD;
        }
    }


    if (isset($_POST['nouvelleCouleurFond'])) {
        $nouvelleCouleurFond = htmlspecialchars($_POST['nouvelleCouleurFond']);
        if (!empty($nouvelleCouleurFond)) {
            $_SESSION['color'] = $nouvelleCouleurFond;
        }
    }

    update_user($_SESSION['idUser'], $_SESSION['discordID'], $_SESSION['email'], $_SESSION['pseudo'], $_SESSION['mdp'], $cheminFichier, $_SESSION['color'], $bdd);
}
$bdd = null;

?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Modification du Profil</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <div id="logo">
            <a href="../../index.php"><img src="img/Logo V2.svg" alt="logo" class="logo"></a>
        </div>
    </header>
    <div id="Barre"></div>
    <ul class="menu">
        <li><a class="menuItem" href="mainvue.php">Mission Du Jour</a></li>
        <li><a class="menuItem" href="scoreboard.php">Scoreboard</a></li>
        <li><a class="menuItem" href="regle.php">Regle</a></li>
        <li><a class="menuItem" href="compte.php">Compte</a></li>
        <li><a class="menuItem" href="modifcarte.php">Modifier Ma Carte</a></li>
        <li><a class="menuItem"
                href="https://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot">Bot
                Discord</a></li>
    </ul>
    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
        <i class="closeIcon"><img src="img/close_black_24dp.svg"></i>
    </button>

    <div id="CarteID">
        <div id="content">
            <div id="Carte">
                <h1>Modifier vos informations personnelles</h1>
            </div>
            <!-- Formulaire de modification -->
            <div id="ModifierInfos">
                <form action="modifcarte.php" method="post" enctype="multipart/form-data" id="fModif">
                    <label for="nouveauPseudo">Nouveau Pseudo:</label>
                    <input type="text" id="nouveauPseudo" name="nouveauPseudo">

                    <label for="nouveauEmail">Nouveau Email:</label>
                    <input type="text" id="nouveauEmail" name="nouveauEmail">

                    <label for="nouveauMdp">Nouveau Mot de passe:</label>
                    <input type="password" id="nouveauMdp" name="nouveauMdp">

                    <label for="nouvellePhoto">Nouvelle Photo de profil:</label>
                    <input type="file" id="nouvellePhoto" name="nouvellePhoto">

                    <label for="nouveauDiscordiD">Nouveau Discord ID :</label>
                    <input type="text" id="nouveauDiscordiD" name="nouveauDiscordiD">

                    <label for="nouvelleCouleurFond">Nouvelle Couleur de fond:</label>
                    <?php
                    $CouleurFond = $_SESSION['color'] ?? '';
                    echo '<input type="color" id="nouvelleCouleurFond" name="nouvelleCouleurFond" value="' . $CouleurFond . '">';

                    ?>
                    <br>

                    <button type="submit" value="Modifier" id="connexionB">Modifier</button>
                </form>
            </div>


            <script src="js/script.js"></script>
</body>

</html>