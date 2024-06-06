<?php

require_once 'Defi.php';

class DefiCode extends Defi
{

    public function __construct($date, $bdd)
    {
        parent::__construct($date, $bdd);
        $this->setDefiCodeProperties();
    }

    private function setDefiCodeProperties()
    {

        $sql = "SELECT * FROM defi WHERE DATE_DEFI LIKE '$this->date'";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->id_defi = $result['ID_DEFI'];
            $this->nom = $result['NOM_DEFI'];
            $this->type = $result['TYPE_DEFI'];
            $this->description = $result['DESCRIPTION_DEFI'];
            $this->difficulte = $result['DIFFICULTE'];
        } else {
            throw new Exception("DefiCode not found for date: $this->date");
        }
    }

    public function getExpectedValueTestFromDB()
    {
        $sql = "SELECT VALEUR_TEST FROM defi_codes WHERE ID_DEFI_CODE = $this->id_defi";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['VALEUR_TEST'];
        } else {
            return null;
        }
    }

    public function getExpectedResponseFromDB()
    {
        $sql = "SELECT REPONSE_CODE FROM defi_codes WHERE ID_DEFI_CODE = $this->id_defi";
        $stmt = $this->bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result['REPONSE_CODE'];
        } else {
            return null;
        }
    }

    public function verif($verif)
    {
        if ($verif == "true") {
            echo "<p>Bravo !</p>";

            $heure = $this->dateheure();
            $id_joueur = $_SESSION["idUser"];
            $bdd = $this->getbdd();

            //validation du défi
            $sql = "INSERT INTO realise VALUES ('$id_joueur','$this->id_defi','1','$heure')";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            //maj du nb de partie
            $sql = "UPDATE user SET NB_PARTIE=NB_PARTIE+1 WHERE ID_JOUEUR=$id_joueur";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            $this->updateScore($heure, $id_joueur, $this->id_defi);

            sleep(2);

            header("Location: ../reussite.html");
            exit();

        } else {
            return "<p>Ce n'est pas la reponse désolé tu n'auras pas les points de ce défis</p>";
        }
    }
}

?>