<?php

    require_once 'Qcm.php';
    require_once 'Presentiel.php';
    require_once 'Multimedia.php';
    require_once 'Code.php';

    // Class principale qui va créer les defis, les autres types de défi sont hérité de cette classe. Elle centralise le score et les combos

    class Defi {

        protected $id_defi;
        protected $nom;
        protected $date;
        protected $type;
        protected $description;
        protected $difficulte;
        protected $bdd;
        
        public function __construct($date,$bdd) {
            $this->date = $date;
            $this->bdd = $bdd;
        }

        public static function creerDefi($date,$bdd) {

            $sql = "SELECT * FROM defi WHERE DATE_DEFI LIKE '$date'";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
    
            if($result!=null){
                $typeMission = $result[0][3]; 
                
                $_SESSION['typesmission'] = $typeMission;
                switch ($typeMission) { //choisi le type de mission
                    case 'qcm':
                        return new Qcm($date,$bdd);
                    case 'presentiel':
                        return new Presentiel($date,$bdd);
                    case 'multimedia':
                        return new Multimedia($date,$bdd);
                    case 'code':
                        return new DefiCode($date,$bdd);
                    default:
                        throw new Exception("Type de mission inconnu : $typeMission");                        
                }
            }
        }

    // Getters
        public function getbdd(){
            return $this->bdd;
        }
        public function getIdDefi() {
            return $this->id_defi;
        }

        public function getNom() {
            return $this->nom;
        }

        public function getDate() {
            return $this->date;
        }
        
        public function getType() {
            return $this->type;
        }

        public function getDescription() {
            return $this->description;
        }

        public function getDifficulte() {
            return $this->difficulte;
        }

        public function toString() {
            return "[id_defi={$this->id_defi}, nom={$this->nom}, date={$this->date}, type={$this->type}, description={$this->description}, difficulte={$this->difficulte}]";
        }

        public function toTab() {
            return [
                'id_defi' => $this->id_defi,
                'nom' => $this->nom,
                'date' => $this->date,
                'type' => $this->type,
                'description' => $this->description,
                'difficulte' => $this->difficulte,
            ];
        }

        public function dateheure(){ //set l'heure de réalisation
            $date = new DateTime();  
            $dateformat = $date->format("Y-m-d H:i:s");  
            return $dateformat;
        }

        public function verifaccomplissement() {

            if (empty($_SESSION["idUser"])) {
                return false;
            }

            $id_joueur = $_SESSION["idUser"];

            $sql = "SELECT * FROM realise WHERE ID_JOUEUR LIKE '$id_joueur' AND ID_DEFi LIKE '$this->id_defi'";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if($result!=NULL){
                return false;
            } else {
                return true;
            }
        }

        public function setcombo($id_joueur) { //on doit pouvoir améliorer ça //problème importnat la boncle infini ne se fait que lorsque quelqu'un reload la page, enfin c'est à tester

            //set date de la veille
            $dateActuelle = new DateTime();

            $dateVeille = $dateActuelle->modify('-1 day');
        
            $dateVeille = $dateVeille->format('Y-m-d');

            //verif
            $sql = "SELECT * FROM realise WHERE ID_JOUEUR LIKE '$id_joueur' AND DATE(HEURE_DE_REALISATION) LIKE '$dateVeille'";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if (empty($result)) { //retourne une ligne

                //le défi de la veille n'est pas accompli par le joueur
                $sqlUpdateCombo = "UPDATE user SET COMBOS = 0 WHERE ID_JOUEUR = $id_joueur";
                $stmtUpdateCombo = $this->bdd->prepare($sqlUpdateCombo);
                $stmtUpdateCombo->execute();
            }
        }

        public function updateScore($heureRealise, $idJoueurRealise, $idDefiRealise) {
        
            //VVV Gestion des combos VVV

            $this->setcombo($idJoueurRealise);

            $nbPoints = 0;
            $bonus = 0;

            $sql = "SELECT COMBOS FROM user WHERE ID_JOUEUR = $idJoueurRealise";
            $stmt = $this->bdd->prepare($sql);
            $stmt->execute();
            $combo = $stmt->fetchColumn();

                $combo = $combo + 1;
                $_SESSION["combos"] = $combo;

                if ($combo != 0){
                    if ( $combo > 30 ) {
                        $bonus = 5;
                    } elseif ( $combo > 20 ) {
                        $bonus = 4;
                    } elseif ($combo > 10) {
                        $bonus = 3;
                    } elseif ($combo > 5 ) { //a partir du  6eme jour de suite
                        $bonus = 2;
                    } elseif ($combo > 3 ) { //a partir du  4eme jour de suite 
                        $bonus = 1;
                    } else {
                        $bonus = 0;
                    }
                } else {
                    $bonus = 0;
                }

            //VVV Gestion des Points VVV

                $sql = "SELECT * FROM realise WHERE id_defi = $idDefiRealise ORDER BY HEURE_DE_REALISATION";
                $stmt = $this->bdd->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll();


            //parcours en fonction de l'ordre de sorti des lignes
            foreach ($result as $key => $row) {
                if ($key < 10) {
                    $nbPoints = 20;
                } elseif ($key < 20) {
                    $nbPoints = 18;
                } elseif ($key < 50) {
                    $nbPoints = 16;
                } elseif ($key < 100) {
                    $nbPoints = 12;
                } else {
                    $nbPoints = 10;
                }
        
                $sql = "UPDATE user SET NB_POINTS = NB_POINTS + $nbPoints + $bonus, COMBOS = COMBOS+1 WHERE ID_JOUEUR = $idJoueurRealise";
                $stmtUpdate = $this->bdd->prepare($sql);
                // $stmtUpdate->bindParam(':nbPoints', $nbPoints, PDO::PARAM_INT);
                // $stmtUpdate->bindParam(':idJoueur', $idJoueurRealise, PDO::PARAM_INT);
                $stmtUpdate->execute();
            }
        }
    }
        
?>
