<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_name('Agent12');
session_start();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Compte</title>
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
        <li><a class="menuItem" href="login.php">Connexion</a></li>
        <li><a class="menuItem" href="https://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot">Bot Discord</a></li>
    </ul>
    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
        <i class="closeIcon"><img src="img/close_black_24dp.svg"></i>
    </button>
    <div id="CarteID" style="background-color: <?php echo isset($_SESSION['color']) ? $_SESSION['color'] : ''; ?>">
        <div id="content">
            <div id="Carte">
                <h1>Votre carte d'identité</h1>
            </div>
            <div id="InfoID">
                <h1 class="TitreCarte">Informations personnelles</h1>
                <div id="InfoP">
                    <div id="Nom">
                        <?php
                        if (isset($_SESSION["idUser"])) {
                            ?>
                            <h2>
                                Nom
                            </h2>
                            <p>
                                <?php
                                echo $_SESSION["prenom"] . " " . $_SESSION["nom"];
                                ?>
                            </p>
                            <?php
                            if (isset($_SESSION["pseudo"])) {
                                echo "<p>Pseudo : " . $_SESSION["pseudo"] . "</p>";
                            }
                            ?>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="Mail">
                        <h2>Mail</h2>
                        <?php
                        if (isset($_SESSION["idUser"])) {
                            ?>
                            <p>
                                <?php
                                echo $_SESSION["email"];
                                ?>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="NuméroID">
                        <h2>Agent n°</h2>
                        <?php
                        if (isset($_SESSION["idUser"])) {
                            ?>
                            <p>
                                <?php
                                echo $_SESSION["idUser"];
                                ?>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <div id="InfoJ">
                    <div id="RangProfil">
                        <h2>Rang général</h2>
                        <p>Rang</p>
                    </div>
                    <div id="MissionRéussi">
                        <h2>Missions réussies</h2>
                        <?php
                        if (isset($_SESSION["idUser"])) {
                            ?>
                            <p>Nombre de missions réussies:
                                <?php echo $_SESSION["nb_partie"]; ?>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="ComboDeMissionRéussi">
                        <h2>Combo</h2>
                        <?php
                        if (isset($_SESSION["idUser"])) {
                            ?>
                            <p>Nombre de combo de missions réussies:
                                <?php echo $_SESSION["combos"]; ?>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                    <div id="MissionEchoué">

                    </div>
                </div>
            </div>
            <div id="photoDeProfil">
                <?php
                if (isset($_SESSION["idUser"])) {
                    ?>
                    <img src="<?php echo $_SESSION["photo"]; ?>" alt="Photo de profil" class="Photo">
                    <?php
                }
                ?>
            </div>
            <div id="StyleCode"></div>
        </div>
    </div>
    <script src="js/script.js"></script>
</body>

</html>