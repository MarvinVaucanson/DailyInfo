<?php
    $pieces = explode("/", $_SERVER["PHP_SELF"]);
    if(count($pieces) == 3){
        ?>
        <header>
            <a class="" href="home/vue/regle.php">Regle</a>
            <a class="" href="home/vue/scoreboard.php">Scorboard</a>
            <a class="" href="home/vue/compte.php">Compte</a>
            <a class="" href="home/vue/login.php">Connexion</a>
        </header>
        <?php
    } else if(count($pieces) == 6){
        ?>
        <header>
            <a class="" href="../regle.php">Regle</a>
            <a class="" href="../scoreboard.php">Scorboard</a>
            <a class="" href="../compte.php">Compte</a>
            <a class="" href="../login.php">Connexion</a>
        </header>
        <?php
    } else if(count($pieces) == 5){
        ?>
        <header>
            <a class="" href="regle.php">Regle</a>
            <a class="" href="scoreboard.php">Scorboard</a>
            <a class="" href="compte.php">Compte</a>
            <a class="" href="login.php">Connexion</a>
        </header>
        <?php
    }
?>