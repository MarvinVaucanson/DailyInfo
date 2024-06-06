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

        require_once '../../entities/Presentiel.php';

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

        $presentiel = new Presentiel($date, $bdd);

        $desc = $presentiel->getDescription();

        echo '<p>' . $desc . '</p>';

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
            echo ($presentiel->verif($reponse));
        } else {
            echo "Le champ de réponse n'est pas défini.";
        }


        ?>

    </div>
</body>

</html>