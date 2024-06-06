<?php
require_once("../entities/User.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_name('Agent12');
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div id="Barre"></div>
    <ul class="menu">
        <li><a class="menuItem" href="mainvue.php">Mission Du Jour</a></li>
        <li><a class="menuItem" href="scoreboard.php">Scoreboard</a></li>
        <li><a class="menuItem" href="regle.php">Regle</a></li>
        <li><a class="menuItem" href="compte.php">Compte</a></li>
        <li><a class="menuItem"
                href="https://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot">Bot
                Discord</a></li>
    </ul>
    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
    </button>
    <script src="js\script.js"></script>

    <div class="inscription-container">
        <form method="post" class="iform" enctype="multipart/form-data">
            <div class="div2">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="div1">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="div4">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="div5">
                <label for="mdp">Mot de passe *</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <div class="div3">
                <label for="pseudo">Pseudo</label>
                <input type="text" id="pseudo" name="pseudo">
            </div>
            <div class="div7" id="profile-pic">
                <label class="-label" for="photo">
                    <span>Photo de profile</span>
                </label>
                <img src="img/PP.svg" id="output" width="200" />
                <input id="photo" type="file" name="photo" class="input1" onchange="loadFile(event)" />
            </div>

            <div class="div6">
                <label for="discordId">ID Discord</label>
                <input type="text" id="discordId" name="discordId">
            </div>
            <div class="div8">
                <input type="submit" value="S'inscrire" id="connexionB">
            </div>
        </form>
        <!--<a href="login.php"><button>Se connecter</button></a>-->
    </div>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include '../model/connexionbd.php';
        $bdd = connexion();
        include '../model/UserBD.php';
        $email = $_POST["email"];
        $color = '#000000';

        if (user_in_bd_email($email, $bdd)) {
            echo "<p>Utilisateur déjà inscrit</p>";
        } else {
            if (empty($_POST["discordId"])) {
                $_POST["discordId"] = null;
            }
            if (!empty($_FILES['photo']['tmp_name'])) {
                $targetDirectory = "img/";
                $photoFileName = basename($_FILES["photo"]["name"]);
                $targetPath = $targetDirectory . $photoFileName;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

                // Vérifier si le fichier est une image réelle
                $check = getimagesize($_FILES["photo"]["tmp_name"]);
                if ($check === false) {
                    echo "Le fichier n'est pas une image.";
                    $uploadOk = 0;
                }

                // Vérifier la taille de l'image (optionnel)
                if ($_FILES["photo"]["size"] > 500000) {
                    echo "Désolé, votre fichier est trop volumineux.";
                    $uploadOk = 0;
                }

                // Autoriser certains formats d'images (vous pouvez ajuster selon vos besoins)
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                    $uploadOk = 0;
                }

                // Vérifier si $uploadOk est défini à 0 par une erreur
                if ($uploadOk == 0) {
                    echo "Désolé, votre fichier n'a pas été téléchargé.";
                } else {
                    // Si tout est OK, déplacez le fichier téléchargé dans le dossier img
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetPath)) {
                        echo "Le fichier " . htmlspecialchars(basename($_FILES["photo"]["name"])) . " a été téléchargé.";

                        // Enregistrez le nom de l'image dans la base de données
                        $photo = $photoFileName;
                    } else {
                        echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
                    }
                }

            }
            $photo = $targetPath;
            $newId = add_user_in_bd($_POST["discordId"], $_POST["nom"], $_POST["prenom"], $_POST["email"], $_POST["pseudo"], $_POST["mdp"], $photo, $color, $bdd);
            $_SESSION["idUser"] = $newId;
            $_SESSION["prenom"] = $_POST["prenom"];
            $_SESSION["nom"] = $_POST["nom"];
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["mdp"] = $_POST["mdp"];
            $_SESSION["nb_partie"] = 0;
            $_SESSION["combos"] = 0;
            $_SESSION["nb_points"] = 0;
            $_SESSION["photo"] = $photo;
            $_SESSION["color"] = $color;
            if (gettype($_POST["discordId"]) == "integer") {
                $_SESSION["idDiscord"] = $_POST["discordId"];
            } else {
                unset($_SESSION["idDiscord"]);
            }
            if (gettype($_POST["pseudo"]) == "string") {
                $_SESSION["pseudo"] = $_POST["pseudo"];
            } else {
                unset($_SESSION["pseudo"]);
            }

            echo "<p>Inscription réussi</p>";
            echo "<p>Bonjour " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . "</p>";
            echo "<br><a href='../../index.php'>Aller à la page d'acceuil</a>";
        }
    }

    $bdd = null;
    ?>
</body>

</html>