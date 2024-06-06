<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_name('Agent12');
session_start();
require_once("../entities/User.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div id="Barre"></div>
    <ul class="menu">
        <li><a class="menuItem" href="mainvue.php">Mission Du Jour</a></li>
        <li><a class="menuItem" href="scoreboard.php">Scoreboard</a></li>
        <li><a class="menuItem" href="regle.php">Regle</a></li>
        <li><a class="menuItem" href="compte.php">Compte</a></li>
        <li><a class="menuItem" href="https://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot">Bot Discord</a></li>
    </ul>
    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
    </button>
    <script src="js\script.js"></script>
    <!--<img src="img/Login.png" alt="log" class="log">-->
    <div class="login-container">
        <form method="post" class="lform">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Connexion" id="connexionB">
            <input type="button" value="inscription" id="inscription" onclick="window.location='inscription.php';" />
            <!-- <input type="submit" value="Connexion" id="connexionB"> -->
        </form>




        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include '../model/connexionbd.php';
            $bdd = connexion();
            include '../model/UserBD.php';
            $email = $_POST["email"];
            $mdp = $_POST["password"];
            if (user_in_bd_email_mdp($email, $mdp, $bdd)) {
                $user = get_user_by_email_and_mdp($email, $mdp, $bdd);
                $_SESSION["idUser"] = $user->get_id();
                $_SESSION["prenom"] = $user->get_prenom();
                $_SESSION["nom"] = $user->get_nom();
                $_SESSION["email"] = $user->get_email();
                $_SESSION["mdp"] = $user->get_mdp();
                $_SESSION["nb_partie"] = $user->get_nb_partie();
                $_SESSION["combos"] = $user->get_combos();
                $_SESSION["nb_points"] = $user->get_nb_points();
                $_SESSION["photo"] = $user->get_photo();
                $_SESSION["color"] = $user->get_color();
                if (gettype($user->get_idDiscord()) == "integer") {
                    $_SESSION["idDiscord"] = $user->get_idDiscord();
                } else {
                    unset($_SESSION["idDiscord"]);
                }
                if (gettype($user->get_pseudo()) == "string") {
                    $_SESSION["pseudo"] = $user->get_pseudo();
                } else {
                    unset($_SESSION["pseudo"]);
                }
                if (gettype($user->get_photo()) == "string") {
                    $_SESSION["photo"] = $user->get_photo();
                } else {
                    unset($_SESSION["photo"]);
                }
                header('Location: ../../home/vue/mainvue.php');
                exit;

                //echo "<p>Connexion réussi</p>";
                //echo "<p>Bonjour " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . "</p>";
                //echo "<br><a href='../../index.php'><p style='color: white'>Aller à la page d'acceuil</p><</a>";
            } else {
                ?>
                <!--<div class="erreur">
                <?php
                //echo "<p style='color: red'>Mauvais Email/MDP</p>";
                //echo "<a href='inscription.php'><p style='color: white'>Aller à la page d'inscription</p></a>";
                ?>
            </div>-->
                <?php
            }
        }
        //header('Location: ../../home/vue/mainvue.php');      
    $bdd = null; 
    ?>

    </div>

    <!-- <div class="login-container">
    <form method="post">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <input type="submit" value="Connexion" id="connexionB">
        </div>
    </form>

</div> -->
</body>

</html>