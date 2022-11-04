<?php
 namespace Controllers;

 use DAO\KeeperDAO as KeeperDAO;
 use Models\Keeper as Keeper;

 class KeeperController
 {
    private $KeeperDAO;
    private $newKeeper;

    public function __construct(){
        $this->KeeperDAO = new KeeperDAO();
    }

    public function addKeeperView(){
        require_once(VIEWS_PATH."keeper-add.php");
    }

    public function goLoginKeeper(){
        require_once(VIEWS_PATH."loginkeeper.php");
    }

    public function goLandingKeeper(){
        require_once(VIEWS_PATH."keeperNav.php");
    }


    public function Index($message = "")
    {
         require_once(VIEWS_PATH."keeperNav.php");
    }
     
    public function newKeeper(/*$keeperId,*/$keeperImg,$lastName,$firstName,$cellPhone,$birthDate,$email,$password,$animalSize/*$points,/*$reviews*/,$price){
         $newKeeper = new Keeper();
        // $newKeeper->setkeeperId($keeperId);
         $newKeeper->setKeeperImg($keeperImg);
         $newKeeper->setLastName($lastName);
         $newKeeper->setfirstName($firstName);
         $newKeeper->setCellPhone($cellPhone);
         $newKeeper->setbirthDate($birthDate);
         $newKeeper->setEmail($email);
         $newKeeper->setPassword($password);
         $newKeeper->setAnimalSize($animalSize);
         $newKeeper->setPoints('0');
         $newKeeper->setPrice($price);
         $this->KeeperDAO->AddKeeper($newKeeper);
         $this->goLoginKeeper();
    }

    public function loginKeeper($email,$password){
        $newKeeper = $this->KeeperDAO->searchEmail($email);
        if($newKeeper->getPassword()==$password){
            $loggedUser = $newKeeper;
            $_SESSION["loggedUser"] = $loggedUser;
            $this->goLandingKeeper();
            }else{
            $this->goIndex();
            echo "<h2>no entre</h2>";
            }
    }

    public function showKeepers(){
        $listKeepers = array();
        $listKeepers = $this->KeeperDAO->getAllKeeper();
        require_once(VIEWS_PATH. "showKeeper.php");
    }
    public function showKeepersByAvailability($value1,$value2){
        $listKeepers = array();
        $listKeepers = $this->KeeperDAO->getKeeperByDisponibility($value1,$value2);
        if($listKeepers){
            require_once(VIEWS_PATH. "keeperCard.php");
        }else{
            echo "<h1>No existen keepers con disponibilidad de entre $value1 y $value2</h1>";
        }
    }

    public function updateAvailabilityDays($date1,$date2){
        $value=$this->KeeperDAO->changeAvailabilityDays($_SESSION["loggedUser"]->getEmail(),$date1,$date2);
        if($value){
            echo"<h1>Los cambios fueron realizados correctamente</h1>";
        }else{
            echo"<h1>Error al realizar los cambios</h1>";
        }
        $this->goLandingKeeper();
    }
 }
?>