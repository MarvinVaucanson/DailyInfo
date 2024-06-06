<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Dayly info</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<div class="DefiContainer">

    <?php

    if ($_SERVER['REQUEST_URI'] === '/daily-info/home/vue/mission/deficode.php') {
        include '../../model/connexionbd.php';
        $bdd = connexion();
        session_name('Agent12');
        session_start();

        require_once '../../entities/Defi.php';
        if (isset($_SESSION['missiondujour'])) {
            $defiData = $_SESSION['missiondujour'];

            // Si le type de défi est "code", effectuez le traitement ici
            if ($defiData['type'] == 'code') {
                $idDefi = $defiData['id_defi'];

                $sql = 'SELECT LANGAGE FROM defi_codes WHERE ID_DEFI_CODE = :idDefi';
                $stmt = $bdd->prepare($sql);
                $stmt->bindParam(':idDefi', $idDefi);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $language = $result['LANGAGE'];

                    if ($language == 'python') {
                        header("Location: ../interpreteur%20python/testPy.php");
                        exit();
                    } else if ($language == 'javaScript') {
                        header("Location: ../interpreteur%20js/testJs.php");
                        exit();
                    } else {
                        echo 'erreur pas de code de ce type';
                        exit();
                    }
                } else {
                    echo "Error: Language not found.";
                }
            } else {
                echo "tu es un intrus";
            }
        } else {
            echo "La clé 'missiondujour' n'est pas définie dans la session.";
            exit();
        }
    }
    ?>

    <button class="connexionB" role="button"><a href="../mainvue.php">Retour au menu</a></button>
</div>

</html>