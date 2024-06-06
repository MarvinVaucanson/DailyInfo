<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/scoreboard.css">
    <title>Scoreboard</title>
</head>

<body>
    <header>
        <div id="logo">
            <a href="/daily-info/index.php"><img src="img/Logo V2.svg" alt="logo" class="logo"></a>
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
    </ul>
    <button class="hamburger">
        <i class="menuIcon"><img src="img/menu_black_24dp.svg"></i>
        <i class="closeIcon"><img src="img/close_black_24dp.svg"></i>
    </button>

    <div class="content-wrapper">
        <div class="scoreboard">
            <?php
            include '../model/connexionbd.php';
            $bdd = connexion();
            session_name('Agent12');
            session_start();
            // Requête SQL pour récupérer les données du scoreboard
            $sql = "SELECT id_joueur, pseudo, nb_partie, combos, nb_points, photo FROM user ORDER BY nb_points DESC";
            $stmt = $bdd->query($sql);

            if ($stmt) {
                ?>
                <table>
                    <tr>
                        <th>Position</th>
                        <th>Pseudo</th>
                        <th>Nombre de Parties</th>
                        <th>Combos</th>
                        <th>Points</th>
                    </tr>
                    <?php
                    $position = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr data-user-id="<?php echo $row['id_joueur']; ?>">
                            <td>
                                <?php echo $position; ?>
                            </td>
                            <td>
                                <?php echo $row["pseudo"]; ?>
                            </td>
                            <td>
                                <?php echo $row["nb_partie"]; ?>
                            </td>
                            <td>
                                <?php echo $row["combos"]; ?>
                            </td>
                            <td>
                                <?php echo $row["nb_points"]; ?>
                            </td>
                        </tr>
                        <?php
                        $position++;
                    endwhile;
                    ?>
                </table>
                <?php
            } else {
                echo "Aucun résultat trouvé dans la base de données.";
            }
            ?>
        </div>

        <div id="userDetails">
            <!-- User details will be displayed here -->
        </div>
    </div>

    <script src="js\script.js"></script>

    <script>
        // Attach the hover event to each row in the table
        var tableRows = document.querySelectorAll('table tr');

        tableRows.forEach(function (row) {
            row.addEventListener('mouseover', function () {
                var userId = this.getAttribute('data-user-id');
                updateUserDetails(userId);
            });

            row.addEventListener('mouseout', function () {
                var userDetailsDiv = document.getElementById('userDetails');
                userDetailsDiv.innerHTML = ''; // Clear user details on mouseout
            });
        });

        function updateUserDetails(userId) {
            var userDetailsDiv = document.getElementById('userDetails');
            <?php
            $stmt->execute(); // Reset the statement pointer
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                if (userId == '<?php echo $user['id_joueur']; ?>') {
                    userDetailsDiv.innerHTML = `
                                        <h3>User Details</h3>
                                        <p><strong>Pseudo:</strong> <?php echo $user['pseudo']; ?></p>
                                        <p><strong>Nombre de Parties:</strong> <?php echo $user['nb_partie']; ?></p>
                                        <p><strong>Combos:</strong> <?php echo $user['combos']; ?></p>
                                        <p><strong>Points:</strong> <?php echo $user['nb_points']; ?></p>
                                        <img class="ppImg" src="<?php echo $user['photo']; ?>">
                                    `;
                }
            <?php endwhile; ?>
        }
    </script>

</body>

</html>