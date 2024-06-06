<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Daily info</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <button class="hamburger">
        <i class="menuIcon"><a href="/daily-info/home/vue/mainvue.php"><img src="../img/close_black_24dp.svg"></a></i>
    </button>
    <div class="DefiContainer">

        <?php

        require_once '../../entities/Multimedia.php';

        include '../../model/connexionbd.php';

        $bdd = connexion();

        session_name('Agent12');
        session_start();

        //forcage de la connection à setup
        if (!isset($_SESSION['idUser'])) {
            header("Location: ../login.html");
            exit();
        }

        if (isset($_SESSION['missiondujour'])) {
            $defi = $_SESSION['missiondujour'];
        } else {
            echo "La clé 'missiondujour' n'est pas définie dans la session.";
        }

        //$defi = $_SESSION['missiondujour'];
        $date = $defi['date'];
        echo ($date);

        $multimedia = new Multimedia($date, $bdd);
        $desc = $multimedia->getDescription();

        $link = $multimedia->getPath();

        echo '<p>' . $desc . '</p>';

        //echo '<img src="'. $multimedia->getPath() .'" alt="'.$path.'" />'; ///////////////////////// ATTENTION ICI LES " DES COORDONNEES
        
        //regex images
        if (preg_match('/^https:\/\//', $link)) {
            echo '<img src="' . $link . '" alt="' . $link . '" class="imgM" />';
        }

        //regex coordonnées gps. Format = 45°46'04.0"N 4°50'10.0"E
        elseif (preg_match('/^(\d{1,2})°(\d{1,2})\'(\d{1,2}(?:\.\d+)?)\"([NS]) (\d{1,3})°(\d{1,2})\'(\d{1,2}(?:\.\d+)?)\"([WE])$/', $link, $matches)) {
            echo '<p>' . $link . '</p>';
        }

        //regex lien youtube
        elseif (stripos($link, '<iframe') !== false) {
            echo '<div>' . $link . '</div><br>';
        } else {
            echo 'Mauvais format :( merci de report ce bug aux équipes techniques';
        }

        echo
            '
        <form method="get">
        <input type="text" name="reponse"></input>
        <button type="submit">Envoyer</button>
        </form>
        '
        ;

        if (isset($_GET['reponse'])) {
            $reponse = $_GET['reponse'];
            echo ($multimedia->verif($reponse));
        } else {
            echo "Le champ de réponse n'est pas défini.";
        }


        ?>

    </div>
</body>

</html>