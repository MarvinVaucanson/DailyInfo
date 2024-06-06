<?php 

    require_once 'Defi.php';

    class Presentiel extends Defi { //Les defi à faire sur place à l'iut

        private $code;
        private $pathqr;

        public function __construct($date,$bdd) {
            parent::__construct($date,$bdd);
            $this->chargerInfosDepuisBD($bdd);
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

            $sql = "SELECT * FROM defi_presentiel WHERE ID_DEFI_PRESENTIEL = $this->id_defi";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            $this->code = $result[0][1];
            $pathqr = $result[0][2]; //c'est le nom du fichier

        }

        public function getCodeval() {
            return $this->code;
        }

        public function getPath() {
            return $this->pathqr;
        }

        public function verif($codeuser){
            if($codeuser === $this->code){  

                echo "<p>Bravo !</p>";

                $heure=$this->dateheure();
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

                $this->updateScore($heure,$id_joueur,$this->id_defi);

                sleep(2);

                header("Location: ../reussite.html");
                exit();
                
            } else {
                return "<p>Ce n'est pas le code désolé</p>";
            }
        }

    }

// Bravo d'etre arrivé jusqu'ici, malheureusement vous n'aurez pas de gateau ;)
//               .,-:;//;:=,
//           . :H@@@MM@M#H/.,+%;,
//        ,/X+ +M@@M@MM%=,-%HMMM@X/,
//      -+@MM; $M@@MH+-,;XMMMM@MMMM@+-
//     ;@M@@M- XM@X;. -+XXXXXHHH@M@M#@/.
//   ,%MM@@MH ,@%=             .---=-=:=,.
//   =@#@@@MX.,                -%HX$$%%%:;
//  =-./@M@M$                   .;@MMMM@MM:
//  X@/ -$MM/                    . +MM@@@M$
// ,@M@H: :@:                    . =X#@@@@-
// ,@@@MMX, .                    /H- ;@M@M=
// .H@@@@M@+,                    %MM+..%#$.
//  /MMMM@MMH/.                  XM@MH; =;
//   /%+%$XHH@$=              , .H@@@@MX,
//    .=--------.           -%H.,@@@@@MX,
//    .%MM@@@HHHXX$$$%+- .:$MMX =M@@MM%.
//      =XMMM@MM@MM#H;,-+HMM@M+ /MMMX=
//        =%@M@M#@$-.=$@MM@@@M; %M%=
//          ,:+$+-,/H#MMMMMMM@= =,
//                =++%%%%+/:-.
?>