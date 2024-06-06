<?php
class User
{
    private $id;
    private $idDiscord;
    private $nom;
    private $prenom;
    private $email;
    private $pseudo;
    private $mdp;
    private $photo;
    private $color;
    private $nb_partie;
    private $combos;
    private $nb_points;
    public function __construct($resultUser)
    {
        $this->id = $resultUser['ID_JOUEUR'];
        $this->idDiscord = $resultUser["ID_DISCORD"];
        $this->nom = $resultUser['NOM_USER'];
        $this->prenom = $resultUser['PRENOM_USER'];
        $this->email = $resultUser['EMAIL_USER'];
        $this->pseudo = $resultUser['PSEUDO'];
        $this->mdp = $resultUser['MDP'];
        $this->photo = $resultUser['PHOTO'];
        $this->color = $resultUser['COLOR'];
        $this->nb_partie = $resultUser['NB_PARTIE'];
        $this->combos = $resultUser['COMBOS'];
        $this->nb_points = $resultUser['NB_POINTS'];
    }

    public function get_id(): int
    {
        return $this->id;
    }
    public function get_idDiscord()
    {
        return $this->idDiscord;
    }
    public function get_nom(): string
    {
        return $this->nom;
    }
    public function get_prenom(): string
    {
        return $this->prenom;
    }
    public function get_email(): string
    {
        return $this->email;
    }
    public function get_pseudo()
    {
        return $this->pseudo;
    }
    public function get_mdp(): string
    {
        return $this->mdp;
    }
    public function get_photo(): string
    {
        return $this->photo;
    }
    public function get_nb_partie(): int
    {
        return $this->nb_partie;
    }
    public function get_combos(): int
    {
        return $this->combos;
    }
    public function get_nb_points(): int
    {
        return $this->nb_points;
    }

    public function get_color(): string
    {
        return $this->color;
    }



    // protected function chargerInfosDepuisBD($bdd) {

    //     $sql = "SELECT * FROM defi WHERE DATE_DEFI LIKE '$this->date'";
    //     $stmt = $bdd->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll();

    //     $this->id_defi = $result[0][0];
    //     $this->nom = $result[0][1];
    //     $this->type = $result[0][3];
    //     $this->description = $result[0][4];
    //     $this->difficulte = $result[0][5];

    //     $sql = "SELECT * FROM defi_qcm WHERE ID_DEFI_QCM = $this->id_defi";
    //     $stmt = $bdd->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll();

    //     echo($result[0][1]);
    //     $req1=$this->sep($result[0][1]); $this->tabque[] = $req1['q']; $this->tabres[] = $req1['r'];
    //     $req2=$this->sep($result[0][2]); $this->tabque[] = $req2['q']; $this->tabres[] = $req2['r'];
    //     $req3=$this->sep($result[0][3]); $this->tabque[] = $req3['q']; $this->tabres[] = $req3['r'];
    //     $req4=$this->sep($result[0][4]); $this->tabque[] = $req4['q']; $this->tabres[] = $req4['r'];
    //     $req5=$this->sep($result[0][5]); $this->tabque[] = $req5['q']; $this->tabres[] = $req5['r'];

    // }

}

?>