<?php

require_once 'Defi.php';

class Multimedia extends Defi
{

    private $code;
    private $pathimg;

    public function __construct($date, $bdd)
    {
        parent::__construct($date, $bdd);
        $this->chargerInfosDepuisBD($bdd);
    }

    protected function chargerInfosDepuisBD($bdd)
    {

        $sql = "SELECT * FROM defi WHERE DATE_DEFI LIKE '$this->date'";
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $this->id_defi = $result[0][0];
        $this->nom = $result[0][1];
        $this->type = $result[0][3];
        $this->description = $result[0][4];
        $this->difficulte = $result[0][5];

        $sql = "SELECT * FROM defi_multimedia WHERE ID_DEFI_MULTIMEDIA = $this->id_defi";
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $this->code = $result[0][1];
        $this->pathimg = $result[0][2]; //c'est le nom du fichier de l'image ou le lien 

    }

    public function getCodeval()
    {
        return $this->code;
    }

    public function getPath()
    {
        return $this->pathimg;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function verif($codeuser)
    {
        $codeuser = strtolower(trim($codeuser)); //convertir en min et supprimer les espaces

        $boncode = strtolower($this->code); //convertir la bonne rep en min


        // Vérifier si la réponse utilisateur est une sous-chaîne de la réponse attendue
        if (strpos($codeuser, $boncode) !== false) {
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
            return "<p>Ce n'est pas la reponse désolé</p>";
        }
    }
}

// I like TUTEL and You ?                                                         
//                             +++**++++++                     
//                        ++++++*************#                 
//                     +++**+++***+*********###**              
//                   +###+***+****++*****######****            
//                  ++###########++*######****##****+          
//                +++###++++++########*********###****         
//           =======+##+++++******###***********###****        
//        ==============+++******+*##+++**+*******##**##       
//      ==================***+++++*###****++*******##### ====  
//     ====================+****++++##**************###*+===   
//    ===%**%+====%*%%======********##*****+********##**+==    
//   ====%%%%=====%%%%=======*******###+*****+++***##***==     
//   ========================+*******##*+**++++++###+++=       
//   ======+%%===#%*==========*******###***+*#####*+++==       
//    =======%%%%%=============################+++++====       
//    =========================+********++++++++++=======      
//     ==========================*******++++++===========      
//       ================================================      
//          =================================   ========       
//          ==============================                     
//         ==========   =================                      
//         ==========          ===========                     
//          =========          ===========                     
//                              ==========                     
//                               ======                        

?>