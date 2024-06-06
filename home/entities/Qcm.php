<?php 

    require_once 'Defi.php';

    class Qcm extends Defi {

        private $q1;
        private $q2;
        private $q3;
        private $q4;
        private $q5;

        private $r1;
        private $r2;
        private $r3;
        private $r4;
        private $r5;

        private $tabres=array();
        private $tabque=array();

        public function __construct($date,$bdd) {
            parent::__construct($date,$bdd);
            $this->chargerInfosDepuisBD($bdd);
        }

        public function sep($res) {
            $parts = explode(';', $res);
            $results = [
                'q' => $parts[0],
                'r' => $parts[1],
            ];
            return $results;
        }

        public function getQuestion($i) {
            return $this->tabque[$i-1];
        }
        
        public function getReponse($i) {
            return $this->tabres[$i-1];
        }

        protected function chargerInfosDepuisBD($bdd) {

            $sql = "SELECT * FROM defi WHERE DATE_DEFI LIKE '$this->date'";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            $this->id_defi = $result[0][0];
            $this->nom = $result[0][1];
            $this->type = $result[0][3];
            $this->description = $result[0][4];
            $this->difficulte = $result[0][5];

            $sql = "SELECT * FROM defi_qcm WHERE ID_DEFI_QCM = $this->id_defi";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            //echo($result[0][1]); //formate les resultats et les reponses des 5 questions
            $req1=$this->sep($result[0][1]); $this->tabque[] = $req1['q']; $this->tabres[] = $req1['r'];
            $req2=$this->sep($result[0][2]); $this->tabque[] = $req2['q']; $this->tabres[] = $req2['r'];
            $req3=$this->sep($result[0][3]); $this->tabque[] = $req3['q']; $this->tabres[] = $req3['r'];
            $req4=$this->sep($result[0][4]); $this->tabque[] = $req4['q']; $this->tabres[] = $req4['r'];
            $req5=$this->sep($result[0][5]); $this->tabque[] = $req5['q']; $this->tabres[] = $req5['r'];

        }

        public function verif($reponses){

            //format du get : 1=vrai&2=faux&3=vrai&4=faux&5=vrai format réponse

            //initialisation
            $juste = 0;
            $fausse = 0;
            $i = 0;

            foreach ($reponses as $rep) {
                
                if ($this->tabres[$i] === $rep) {
                    $juste++;
                    $i++;
                } else {
                    $fausse++;
                    $i++;
                }
            }

            $fausse = $fausse - 1; //obligatoire car la methode get rajoute un élément
            
            $heure = $this->dateheure();
            $id_joueur = $_SESSION["idUser"];
            $bdd = $this->getbdd();
            
            $interval = 'PT' . $fausse . '0M';
            $intervalObj = new DateInterval($interval);
            $heureObj = new DateTime($heure);
            $heureObj->add($intervalObj);
            
            // Récupérer la date formatée sous forme de chaîne
            $heureFormatee = $heureObj->format('Y-m-d H:i:s');
            
            // Validation du défi
            $sql = "INSERT INTO realise VALUES ('$id_joueur','$this->id_defi','1','$heureFormatee')";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            //maj du nb de partie
            $sql = "UPDATE user SET NB_PARTIE=NB_PARTIE+1 WHERE ID_JOUEUR=$id_joueur";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            //score
            $this->updateScore($heureFormatee,$id_joueur,$this->id_defi);

            sleep(2);

            header("Location: ../reussite.html");
            exit();
                
        }

    }
//   .    .        .      .             . .     .        .          .          .
//            .                 .                    .                .
//     .               A long time ago in a galaxy far, far away...   .
//        .               .           .               .        .             .
//     .     .                                                        .
//                 .   A terrible civil war burns throughout the  .        .     .
//                    galaxy: a rag-tag group of freedom fighters   .  .
//        .       .  has risen from beneath the dark shadow of the            .
//   .        .     evil monster the Galactic Empire has become.                  .
//      .             Imperial  forces  have  instituted  a reign of   .      .
//                terror,  and every  weapon in its arsenal has  been
//             . turned upon the Rebels  and  their  allies:  tyranny, .   .
//      .       oppression, vast fleets, overwhelming armies, and fear.        .  .
//   .      .  Fear  keeps  the  individual systems in line,  and is the   .
//            prime motivator of the New Order.             .
//       .      Outnumbered and outgunned,  the Rebellion burns across the   .    .
//   .      vast reaches of space and a thousand-thousand worlds, with only     .
//       . their great courage - and the mystical power known as the Force -
//        flaming a fire of hope.                                    .
//          This is a  galaxy  of wondrous aliens,  LUKA MARET,  strange   .
//    . Droids, powerful weapons, great heroes, and terrible villains.  It is a
//     galaxy of fantastic worlds,  magical devices, vast fleets, awesome machi-  .
//    nery, terrible conflict, and unending hope.              .         .
//   .        .          .    .    .            .            .                   .
//                  .               ..       .       .   .             .
//    .      .     T h i s   i s   t h e   g a l a x y   o f   . . .             .
//                        .              .       .                    .      .
//   .        .               .       .     .            .
//                .               .    .          .              .   .         .
//                  _________________      ____         __________
//    .       .    /                 |    /    \    .  |          \
//        .       /    ______   _____| . /      \      |    ___    |     .     .
//                \    \    |   |       /   /\   \     |   |___>   |
//              .  \    \   |   |      /   /__\   \  . |         _/               .
//    .     ________>    |  |   | .   /            \   |   |\    \_______    .
//         |            /   |   |    /    ______    \  |   | \           |
//         |___________/    |___|   /____/      \____\ |___|  \__________|    .
//     .     ____    __  . _____   ____      .  __________   .  _________
//          \    \  /  \  /    /  /    \       |          \    /         |      .
//           \    \/    \/    /  /      \      |    ___    |  /    ______|  .
//            \              /  /   /\   \ .   |   |___>   |  \    \
//      .      \            /  /   /__\   \    |         _/.   \    \            +
//              \    /\    /  /            \   |   |\    \______>    |   .
//               \  /  \  /  /    ______    \  |   | \              /          .
//    .       .   \/    \/  /____/      \____\ |___|  \____________/ 
//                                  .                                        .
//        .                           .         .               .                 .
//                   .                                   .            .
  

?>