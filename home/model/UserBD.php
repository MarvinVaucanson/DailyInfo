<?php
// if ($_SERVER['PHP_SELF'] == "/index.php") {
if ($_SERVER['PHP_SELF'] == "../home/vue/mainvue.php") {
    require_once('../entities/User.php');
}
if ($_SERVER['PHP_SELF'] == '../home/vue/login.php' || $_SERVER['PHP_SELF'] == '/../home/vue/inscription.php') {
    require_once('../entities/User.php');
}

function get_user_by_id(int $id, $bdd): User
{
    $sql = "SELECT * FROM USER WHERE ID_JOUEUR = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    return new User($user);
}

function get_user_by_email_and_mdp(string $email, string $mdp, $bdd): User
{
    $sql = "SELECT * FROM USER WHERE EMAIL_USER = :email AND MDP = :mdp;";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    return new User($user);
}

function add_user_in_bd($idDiscord, string $nom, string $prenom, string $email, $pseudo, string $mdp, $photo, $color, $bdd)
{
    $sqlIds = "SELECT ID_JOUEUR FROM USER";
    $stmt = $bdd->prepare($sqlIds);
    $stmt->execute();
    $Ids = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $exclusions = [];
    foreach ($Ids as $row) {
        $exclusions[] = $row['ID_JOUEUR'];
    }
    do {
        $newId = rand(0, 9999);
    } while (in_array($newId, $exclusions));

    $sql = "INSERT INTO USER VALUES(:id, :iddiscord, :nom, :prenom, :email, :pseudo, :mdp, :photo, :color, 0, 0, 0)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id', $newId, PDO::PARAM_INT);
    $stmt->bindParam(':iddiscord', $idDiscord, PDO::PARAM_STR);
    $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
    $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
    $stmt->bindParam(':color', $color, PDO::PARAM_STR);

    $stmt->execute();
    $stmt = null;
    return $newId;
}

function user_in_bd_email_mdp(string $email, string $mdp, $bdd)
{
    $sql = "SELECT * FROM USER WHERE EMAIL_USER = :email AND MDP = :mdp;";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    return gettype($user) != "boolean";
}

function user_in_bd_email(string $email, $bdd)
{
    $sql = "SELECT * FROM USER WHERE EMAIL_USER = :email;";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;
    return gettype($user) != "boolean";
}

function update_user(int $id, string $iddiscord, string $email, string $pseudo, string $mdp, string $photo, string $color, $bdd)
{
    $sql = "UPDATE USER SET EMAIL_USER = :email, PSEUDO = :pseudo, MDP = :mdp, PHOTO = :photo, COLOR = :color, ID_DISCORD = :iddiscord WHERE ID_JOUEUR = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmt->bindParam(':mdp', $mdp, PDO::PARAM_STR);
    $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
    $stmt->bindParam(':color', $color, PDO::PARAM_STR);
    $stmt->bindParam(':iddiscord', $iddiscord, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = null;
}

function modify_user($userId, $newEmail, $newPseudo, $newPhoto, $newColor, $newNom, $newPrenom, $bdd)
{
    $sql = "UPDATE USER SET EMAIL_USER = :new_email, PSEUDO = :new_pseudo, PHOTO = :new_photo, COLOR = :new_color, NOM_USER = :new_nom, PRENOM_USER = :new_prenom WHERE ID_JOUEUR = :user_id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':new_email', $newEmail, PDO::PARAM_STR);
    $stmt->bindParam(':new_pseudo', $newPseudo, PDO::PARAM_STR);
    $stmt->bindParam(':new_photo', $newPhoto, PDO::PARAM_STR);
    $stmt->bindParam(':new_color', $newColor, PDO::PARAM_STR);
    $stmt->bindParam(':new_nom', $newNom, PDO::PARAM_STR);
    $stmt->bindParam(':new_prenom', $newPrenom, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
}

function get_all_users($bdd)
{
    $sql = "SELECT * FROM USER";

    // Ensure that $bdd is not null before calling prepare
    if ($bdd) {
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Handle the case where $bdd is null
        die("Database connection is null");
    }
}

function add_defi_presentiel_to_database($ID_DEFI_PRESENTIEL, $CODE_VALID, $CODE_QR, $bdd)
{
    $sql = "INSERT INTO defi_presentiel (ID_DEFI_PRESENTIEL, CODE_VALID, CODE_QR) VALUES (:idDefiPresentiel, :codeValid, :codeQR)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idDefiPresentiel', $ID_DEFI_PRESENTIEL, PDO::PARAM_INT);
    $stmt->bindParam(':codeValid', $CODE_VALID, PDO::PARAM_STR);
    $stmt->bindParam(':codeQR', $CODE_QR, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Close the statement
    $stmt = null;
}

function add_defi_to_database($ID_DEFI, $NOM_DEFI, $DATE_DEFI, $TYPE_DEFI, $DESCRIPTION_DEFI, $DIFFICULTE, $bdd)
{
    $sql = "INSERT INTO defi (ID_DEFI, NOM_DEFI, DATE_DEFI, TYPE_DEFI, DESCRIPTION_DEFI, DIFFICULTE) VALUES (:idDefi, :nomDefi, :dateDefi, :typeDefi, :descriptionDefi, :difficulte)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idDefi', $ID_DEFI, PDO::PARAM_INT);
    $stmt->bindParam(':nomDefi', $NOM_DEFI, PDO::PARAM_STR);
    $stmt->bindParam(':dateDefi', $DATE_DEFI, PDO::PARAM_STR);
    $stmt->bindParam(':typeDefi', $TYPE_DEFI, PDO::PARAM_STR);
    $stmt->bindParam(':descriptionDefi', $DESCRIPTION_DEFI, PDO::PARAM_STR);
    $stmt->bindParam(':difficulte', $DIFFICULTE, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = null;
}

function add_defi_qcm_to_database($ID_DEFI, $Q1, $Q2, $Q3, $Q4, $Q5, $bdd)
{
    $sql = "INSERT INTO defi_qcm (ID_DEFI_QCM, Q1, Q2, Q3, Q4, Q5) VALUES (:idDefiQcm, :q1, :q2, :q3, :q4, :q5)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idDefiQcm', $ID_DEFI, PDO::PARAM_INT);
    $stmt->bindParam(':q1', $Q1, PDO::PARAM_STR);
    $stmt->bindParam(':q2', $Q2, PDO::PARAM_STR);
    $stmt->bindParam(':q3', $Q3, PDO::PARAM_STR);
    $stmt->bindParam(':q4', $Q4, PDO::PARAM_STR);
    $stmt->bindParam(':q5', $Q5, PDO::PARAM_STR);
    $stmt->execute();
    $stmt = null;
}

function add_defi_multimedia_to_database($ID_DEFI, $CODE_VALIDM, $MULTIM, $bdd)
{
    $sql = "INSERT INTO defi_multimedia (ID_DEFI_MULTIMEDIA, CODE_VALID_MULTIMEDIA, MULTIMEDIA) VALUES (:idDefiMultimedia, :codeValidM, :multim)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':idDefiMultimedia', $ID_DEFI, PDO::PARAM_INT);
    $stmt->bindParam(':codeValidM', $CODE_VALIDM, PDO::PARAM_STR);
    $stmt->bindParam(':multim', $MULTIM, PDO::PARAM_STR);
    $stmt->execute();
    $stmt = null;
}

function get_max_defi_id($type, $bdd)
{
    $sql = "SELECT MAX(ID_DEFI) AS maxId FROM defi WHERE TYPE_DEFI = :type";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['maxId'] ?? 0; // Return 0 if no existing ID found
}
?>
