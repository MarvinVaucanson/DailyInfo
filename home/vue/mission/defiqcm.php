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

        require_once '../../entities/Qcm.php';

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

        $qcm = new Qcm($date, $bdd);

        echo '<form method="get" class="formqcm">';
        for ($i = 1; $i < 6; $i++) {
            echo '<label for="' . $i . '">' . $qcm->getQuestion($i) . '</label>';
            echo
                '
            <div class="reponse">
            <input type="radio" name="' . $i . '" value="vrai" required>vrai</input>
            <input type="radio" name="' . $i . '" value="faux" required>faux</input>
            </div>
            '
            ;
            //echo $qcm->getReponse($i);
            echo "<br>";
        }

        echo
            '
        <button type="submite" name="submite">Envoyer</button>
        </form>
        '
        ;

        if (isset($_GET['submite'])) {
            $reponse = $_GET;
            echo ($qcm->verif($reponse));
        } else {
            echo "Le champ de réponse n'est pas défini.";
        }

        ?>

    </div>
</body>

</html>