<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_name('Agent12');
session_start();

// Include necessary files and functions
require_once('../model/UserBD.php');
require_once('../model/connexionbd.php');

// Establish a database connection
$bdd = connexion();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['modify_user'])) {
        $userId = $_POST['selected_user'];
        $newEmail = $_POST['new_email'];
        $newPseudo = $_POST['new_pseudo'];
        $newPrenom = $_POST['new_prenom'];
        $newNom = $_POST['new_nom'];
        $newPhoto = ('');
        if (!empty($_FILES['new_photo']['tmp_name'])) {
            $targetDirectory = "img/";
            $photoFileName = uniqid() . "_" . basename($_FILES["new_photo"]["name"]);
            $targetPath = $targetDirectory . $photoFileName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));


            $check = getimagesize($_FILES["new_photo"]["tmp_name"]);
            if ($check === false) {
                echo "Le fichier n'est pas une image.";
                $uploadOk = 0;
            }


            if ($_FILES["new_photo"]["size"] > 500000) {
                echo "Désolé, votre fichier est trop volumineux.";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                $uploadOk = 0;
            }


            if ($uploadOk == 0) {
                echo "Désolé, votre fichier n'a pas été téléchargé.";
            } else {

                if (move_uploaded_file($_FILES["new_photo"]["tmp_name"], $targetPath)) {
                    echo "Le fichier " . htmlspecialchars(basename($_FILES["new_photo"]["name"])) . " a été téléchargé.";


                    $newPhoto = $targetPath;
                } else {
                    echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
                }
            }
        }

        $newColor = $_POST['new_color'];

        modify_user($userId, $newEmail, $newPseudo, $newPhoto, $newColor, $newNom, $newPrenom, $bdd);
    }

    if (isset($_POST['add_mission'])) {
        $TYPE_DEFI = $_POST['typeMission'];
        $maxId = get_max_defi_id($TYPE_DEFI, $bdd);
        $ID_DEFI = $maxId + 1;

        $NOM_DEFI = $_POST['NomMission'];
        $DATE_DEFI = $_POST['DateDefi'];
        $DESCRIPTION_DEFI = $_POST['Description'];
        $DIFFICULTE = $_POST['difficulte'];

        add_defi_to_database($ID_DEFI, $NOM_DEFI, $DATE_DEFI, $TYPE_DEFI, $DESCRIPTION_DEFI, $DIFFICULTE, $bdd);

        if ($TYPE_DEFI === 'presentiel') {
            $CODE_VALIDP = $_POST['codeValidP'];
            $CODE_QR = $_POST['codeQR'];
            add_defi_presentiel_to_database($ID_DEFI, $CODE_VALIDP, $CODE_QR, $bdd);
        } elseif ($TYPE_DEFI === 'qcm') {
            $Q1 = $_POST['QQ1'] . ';' . $_POST['Q1R'];
            $Q2 = $_POST['QQ2'] . ';' . $_POST['Q2R'];
            $Q3 = $_POST['QQ3'] . ';' . $_POST['Q3R'];
            $Q4 = $_POST['QQ4'] . ';' . $_POST['Q4R'];
            $Q5 = $_POST['QQ5'] . ';' . $_POST['Q5R'];
            add_defi_qcm_to_database($ID_DEFI, $Q1, $Q2, $Q3, $Q4, $Q5, $bdd);
        } elseif ($TYPE_DEFI === 'multimedia') {
            $CODE_VALIDM = $_POST['codeValidM'];
            $MULTIM = $_POST['multiM'];
            add_defi_multimedia_to_database($ID_DEFI, $CODE_VALIDM, $MULTIM, $bdd);
        }
    }

}

$users = get_all_users($bdd);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Admin Page</title>

    <style>
        #userDetails {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 20px;
        }

        .missionForm {
            display: none;
        }
    </style>
</head>

<body>
    <h1>Welcome to the Admin Page</h1>
    <h2>Modify User</h2>
    <form method="post">
        <label for="selected_user">Select User:</label>
        <select id="selected_user" name="selected_user" onchange="updateUserDetails()" required>
            <option>Choisi un utilisateur</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo $user['ID_JOUEUR']; ?>">
                    <?php echo $user['ID_JOUEUR']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" required>
        <br>
        <label for="new_pseudo">New Pseudo:</label>
        <input type="text" name="new_pseudo" required>
        <br>
        <label for="new_prenom">New First Name:</label>
        <input type="text" name="new_prenom" required>
        <br>
        <label for="new_nom">New Last Name:</label>
        <input type="text" name="new_nom" required>
        <br>
        <label for="new_photo">New Photo:</label>
        <input type="file" name="new_photo" accept=".png, .jpg, .gif" required>
        <br>
        <label for="new_color">New Color:</label>
        <input type="color" id="new_color" name="new_color">
        <input type="submit" name="modify_user" value="Modify User">
    </form>
    <div id="userDetails">
    </div>

    <h2>Add Mission</h2>
    <form method="post">
        <label for="NomMission">Nom de la mission :</label>
        <input type="text" name="NomMission" required>
        <br>
        <label for="Description">Description de la mission :</label>
        <input type="text" name="Description" required>
        <br>
        <label for="DateDefi">Date de la mission :</label>
        <input type="date" name="DateDefi" required>
        <br>
        <label for="difficulte">Difficulté de la mission :</label>
        <select id="difficulte" name="difficulte" required>
            <option>Choisi ta Difficulté</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
        <br>
        <label for="TypeMission">Type de mission :</label>
        <select id="typeMission" name="typeMission" onchange="updateMissionForm()" required>
            <option>Choissisez un type de mission</option>
            <option value="presentiel">Défi Présentiel</option>
            <option value="qcm">Défi QCM</option>
            <option value="multimedia">Défi Multimédia</option>
            <option value="code">Défi Code</option>
        </select>
        <br>

        <div id="presentielForm" class="missionForm">
            <label for="codeValidP">Réponse à la mission/code :</label>
            <input type="text" name="codeValidP"></input>
            <br>
            <label for="codeQR">Code pour le QRcode :</label>
            <input type="text" name="codeQR"></input>
        </div>

        <div id="qcmForm" class="missionForm">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <label for="QQ<?php echo $i; ?>">Question
                    <?php echo $i; ?> :
                </label>
                <textarea name="QQ<?php echo $i; ?>"></textarea>
                <br>
                <label for="Q<?php echo $i; ?>R">Réponse de la Question
                    <?php echo $i; ?> :
                </label>
                <select id="Q<?php echo $i; ?>R" name="Q<?php echo $i; ?>R">
                    <option>Choisi ta Réponse</option>
                    <option value="vrai">Vrai</option>
                    <option value="faux">Faux</option>
                </select>
                <br>
            <?php endfor; ?>
        </div>

        <div id="multimediaForm" class="missionForm">
            <label for="codeValidM">Réponse à la mission/code :</label>
            <input type="text" name="codeValidM"></input>
            <br>
            <label for="multiM">lien vers le multimedia ou text :</label>
            <input type="text" name="multiM"></input>
        </div>
        <div id="codeForm" class="missionForm">
            <label for="test">Non implémenté pour l'instant</label>
        </div>
        <br>
        <input type="submit" name="add_mission" value="Add Mission">
    </form>

    <script>
        function updateUserDetails() {
            var selectedUserId = document.getElementById('selected_user').value;
            var userDetailsDiv = document.getElementById('userDetails');

            <?php foreach ($users as $user): ?>
                if (selectedUserId == '<?php echo $user['ID_JOUEUR']; ?>') {
                    userDetailsDiv.innerHTML = `
                                                                                                                                                                                                <h3>User Details</h3>
                                                                                                                                                                                                <p><strong>ID:</strong> <?php echo $user['ID_JOUEUR']; ?></p>
                                                                                                                                                                                                <p><strong>Email:</strong> <?php echo $user['EMAIL_USER']; ?></p>
                                                                                                                                                                                                <p><strong>Pseudo:</strong> <?php echo $user['PSEUDO']; ?></p>
                                                                                                                                                                                                <p><strong>Nom:</strong> <?php echo $user['NOM_USER']; ?></p>
                                                                                                                                                                                                <p><strong>Prenom:</strong> <?php echo $user['PRENOM_USER']; ?></p>
                                                                                                                                                                                                <p><strong>Photo:</strong> <?php echo "<img src='{$user['PHOTO']}' alt='User Photo' style='max-width: 100px; max-height: 100px;'>"; ?></p>
                                                                                                                                                                                                <p><strong>Color:</strong> <?php echo "<input type='color' value='{$user['COLOR']}'>"; ?></p>
                                                                                                                                                                                            `;
                } else {
                    userDetailsDiv.innerHTML = '';
                }
            <?php endforeach; ?>
        }

        function updateMissionForm() {
            var selectedType = document.getElementById('typeMission').value;
            var missionForms = document.getElementsByClassName('missionForm');

            for (var i = 0; i < missionForms.length; i++) {
                missionForms[i].style.display = 'none';
                var formInputs = missionForms[i].querySelectorAll('input, select, textarea');
                for (var j = 0; j < formInputs.length; j++) {
                    formInputs[j].value = '';
                }
            }

            var selectedForm = document.getElementById(selectedType + 'Form');
            selectedForm.style.display = 'block';

            console.log('Selected Form:', selectedType);
        }



    </script>
</body>

</html>