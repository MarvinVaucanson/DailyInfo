<?php
    session_name('Agent12');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interpréteur JavaScript</title>
</head>
<body>
    <h1>Interpréteur JavaScript</h1>
    <h2><?php echo isset($_SESSION['missiondujour']) ? $_SESSION['missiondujour']['description'] : ''; ?></h2>
    <form id="javascriptForm" action="" method="post">
        <textarea name="code" id="code" rows="10" cols="50" placeholder="Saisissez votre code JavaScript ici"></textarea>
        <br>
        <button type="button" id="testButton">Tester votre code</button>
        <button type="submit" name="submitButton">Envoyer la réponse</button>
        <input type="hidden" name="output" id="hiddenOutput">
        <input type="hidden" name="assertResult" id="hiddenAssertResult">
    </form>
    <h2>Résultat :</h2>
    <div id="output"></div>
    <div id="assertResult"></div>

    <?php

    require_once '../../entities/Code.php';
    include '../../model/connexionbd.php';
    
    $bdd = connexion();

    if (!isset($_SESSION['idUser'])) {
        header("Location: ../login.html");
        exit();
    }

    if (isset($_SESSION['missiondujour'])) {
        $defi = $_SESSION['missiondujour'];
    } else {
        echo "La clé 'missiondujour' n'est pas définie dans la session.";
    }
    
    
    $date = $defi['date'];
    $deficode = new DefiCode($date, $bdd);
    $id_defi = $deficode->getIdDefi();
    $difficulte = $deficode->getDifficulte();

    if($difficulte == 1){
        $sql2 = "SELECT REPONCE_CODE FROM defi_codes WHERE ID_DEFI_CODE = :id_defi";
        $stmt2 = $bdd->prepare($sql2);
        $stmt2->bindParam(':id_defi', $id_defi, PDO::PARAM_INT);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $res = $result2['REPONCE_CODE'];
        $valtest = 0000;
    } else {
        $sql2 = "SELECT VALEUR_TEST, REPONCE_CODE FROM defi_codes WHERE ID_DEFI_CODE = :id_defi";
        $stmt2 = $bdd->prepare($sql2);
        $stmt2->bindParam(':id_defi', $id_defi, PDO::PARAM_INT);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $res = $result2['REPONCE_CODE'];
        $valtest = $result2['verifier'];
    }

    echo '<script>var difficulte = ' . json_encode($difficulte) . ';</script>';
    echo '<script>var valeurTest = ' . json_encode($valtest) . ';</script>';
    echo '<script>var valeurReponse = ' . json_encode($res) . ';</script>';

    if ($difficulte == 1){
        if (isset($_POST['submitButton'])) {
            $valeurOutput = trim($_POST['output']);
            $res = trim($res);

            //echo "Valeur de la balise output côté serveur : " . $valeurOutput;
            //echo "<br> Résultat attendu : " . $res;
    
            if($valeurOutput == $res){
                //echo "<br> La vérification est correcte. Exécution de la fonction de vérification...";
                $verif = true;
            } else {
                //echo "<br> La vérification a échoué.";
                $verif = false;
            }
            echo "<br> Résultat final : " . $deficode->verif($verif);
        }
    }else{
        if(isset($_POST['submitButton'])){
            $valRes = $_POST['assertResult'];

            if($valeurRes == 'true'){
                $verif = true;
            }else{
                $verif = false;
            }
            echo($deficode->verif($verif));
        }
    }
    
    ?>

    <script src="interpreterJs.js"></script>
</body>
</html>
