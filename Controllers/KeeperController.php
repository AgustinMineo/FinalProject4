<?php
 namespace Controllers;

 //use DAO\KeeperDAO as KeeperDAO;
 use DAODB\KeeperDAO as KeeperDAO;
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
     
    public function newKeeper($lastName,$firstName,$cellPhone,$birthDate,$email,$password,$animalSize/*$points,/*$reviews*/,$price,$userImage,$userDescription){
        if($this->KeeperDAO->searchKeeperByEmail($email) == NULL){
            $newKeeper = new Keeper();
            $newKeeper->setLastName($lastName);
            $newKeeper->setfirstName($firstName);
            $newKeeper->setCellPhone($cellPhone);
            $newKeeper->setbirthDate($birthDate);
            $newKeeper->setEmail($email);
            $newKeeper->setPassword($password);
            $newKeeper->setImage($userImage);
            $newKeeper->setDescription($userDescription);
            $newKeeper->setAnimalSize($animalSize);
            $newKeeper->setPrice($price);
            $this->KeeperDAO->AddKeeper($newKeeper);
            $this->goLoginKeeper();
        }        
        else{
            echo '<div class="alert alert-danger">Email already exist! Please try again with another email</div>';
            $this->addKeeperView();
        }
         //$newKeeper->setPoints('0');
         //$newKeeper->setkeeperId($this->searchLastKeeperID()); TO DO
         //$newKeeper->setKeeperImg($keeperImg);
    }


// MIGRAR A DAO
    public function showKeepers(){
        $listKeepers = array();
        $listKeepers = $this->KeeperDAO->getAllKeeper();
        require_once(VIEWS_PATH. "showKeeper.php");
    }
// MIGRAR A DAO
    public function showKeepersByAvailability($value1,$value2){
        $listKeepers = array();
        $listKeepers = $this->KeeperDAO->getKeeperByDisponibility($value1,$value2);
        if($listKeepers){
            require_once(VIEWS_PATH. "keeperCard.php");
        }else{
            echo "<h1>No existen keepers con disponibilidad de entre $value1 y $value2</h1>";
        }
    }
// MIGRAR A DAO
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