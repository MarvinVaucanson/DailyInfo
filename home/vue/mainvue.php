<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Daily Info</title>
    <link rel="stylesheet" type="text/css" href="css\style.css">
</head>


<body>
    <header>
        <div id="logo">
            <a href="/daily-info/index.php"><img src="img/Logo V2.svg" alt="logo" class="logo"></a>
        </div>
        </div>
    </header>

    <div id="Barre"></div>
    <ul class="menu">
        <li><a class="menuItem" href="mainvue.php">Mission Du Jour</a></li>
        <li><a class="menuItem" href="scoreboard.php">Scoreboard</a></li>
        <li><a class="menuItem" href="regle.php">Regle</a></li>
        <?php
        if (isset($_SESSION['idUser'])) {
            echo '<li><a class="menuItem" href="compte.php">Compte</a></li>';
        } else {
            echo '<li><a class="menuItem" href="login.php">Connexion</a></li>';
        }
        echo '<li><a class="menuItem" href="compte.php">Compte</a></li>';
        echo '<li><a class="menuItem" href="https://discord.com/api/oauth2/authorize?client_id=1164841675278008331&permissions=8&scope=bot">Bot Discord</a></li>'
            ?>
        <li><a class="menuItem" href="Adminpage.php">Temporaire</a></li>
    </ul>
    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
        <i class="closeIcon"><img src="img/close_black_24dp.svg"></i>
    </button>
    <script src="js\script.js"></script>
    <!--Version sans moo-->
    <?php

    // session_name('Agent12');
// session_start();
    
    // include 'home/model/connexionbd.php';
    
    // $bdd = connexion();
    
    // function dateset(){ //gère l'affichage de la date
//     $date = new DateTime();  
//     $dateformat = $date->format("Y-m-d");  
//     return $dateformat;
// }
    
    // function verifbonjour($result){
//     if (count($result) > 0) {
//         $mission = $result[0][0];
//     } else {
//         $mission = "Pas de mission aujourd'hui, le monde est en paix :(<h2>";
//     }
//     return $mission;
// }
    

    // $jourmission = dateset();
    
    // //$sql = "SELECT DESCRIPTION_DEFI FROM defi WHERE DATE_DEFI LIKE '2023-11-17'";
// //$sql = "SELECT DESCRIPTION_DEFI FROM defi WHERE DATE_DEFI LIKE 'rentrer la date de la mission à tester ici'"; fonction test
// $sql = "SELECT DESCRIPTION_DEFI, ID_DEFI FROM defi WHERE DATE_DEFI LIKE '$jourmission'";
// $stmt = $bdd->prepare($sql);
// $stmt->execute();
// $result = $stmt->fetchAll();
// $mission = verifbonjour($result);
    
    // if($result != null){$_SESSION['defidujour']=$result[0][1];}
    
    // echo($_SESSION['defidujour']);
// echo($jourmission);
// echo("<br>");
// echo("<h2>$mission<h2>");
    
    ?>

    <?php
    session_name('Agent12');
    session_start();

    include '../model/connexionbd.php';
    $bdd = connexion();

    require_once('../entities/Defi.php');

    function dateset()
    { //gère l'affichage de la date
        $date = new DateTime();
        $dateformat = $date->format("Y-m-d");
        return $dateformat;
    }

    $jourmission = dateset();

    $defi = Defi::creerDefi('2024-01-21', $bdd); //test
    //$defi = Defi::creerDefi('2024-03-04', $bdd); //test 2
    
    // $defi = Defi::creerDefi($jourmission,$bdd);
    
    $nom = "";
    if ($defi) {
        $mission = $defi->getDescription();
        $nom = $defi->getNom();
        $_SESSION['missiondujour'] = $defi->toTab();
        $dejafait = $defi->verifaccomplissement();
    } else {
        $mission = "Pas de mission aujourd'hui.";
        $dejafait = false;
    }

    //var_dump($defi); debug
    
    //var_dump($_SESSION['missiondujour']);
    //echo($_SESSION['missiondujour']['nom']); exemple d'utilisation
    //$_SESSION['bdd']=$bdd;
    

    if (!isset($_SESSION['idUser'])) {
        header("Location: login.php");
        exit;
    }

    if ($dejafait) {
        echo (
            '<form action="../vue/mission/defi' . $defi->getType() . '.php" method="post" class="btn-mid">
            <button type="submit" id="connexionB" >Partir en mission</button>
        </form>'
        );
    } else {
        if (!isset($_SESSION['idUser'])) {
            echo ( //cette situation n'est pas sensé arrivé comme on force la connexion mais tkt
                '<form action="login.php" method="post" class="btn-mid">
                <button type="submit" id="connexionB" >Se connecter</button>
            </form>'
            );
        } else {
            echo (
                '<button type="submit" id="connexionB" disabled >Mission accomplie</button>'

            );
        }

    }
    ?>

    <h1>
        <div class="typewriter">
            <?php
            echo '<p id="typewriter-text">[' . $jourmission . '] <br/>Voici votre mission du jour : ' . $nom . ' <br/>' . $mission . '</p>';
            ?>
        </div>
    </h1>
</body>

<!--<footer>
<a class="" href="contacte.php">Nous contacter</a>
    <a class="" href="">La vidéo de présentation</a>
    <a class="" href="">mention légal</a>
    <a class="" href="">remerciment</a>  
    <a class="" href="compte.php">Compte</a>
    <a class="" href="login.html">Connexion</a>  
</footer>-->

</html>