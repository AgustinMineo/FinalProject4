<?php
 namespace Controllers;
 use Models\Keeper as Keeper;
 use Helper\SessionHelper as SessionHelper;

 use DAO\KeeperDAO as KeeperDAO;
 use DAO\MailerDAO as MailerDAO;
 //use DAODB\KeeperDAO as KeeperDAO;
 use DAO\OwnerDAO as OwnerDAO;
 //use DAODB\OwnerDAO as OwnerDAO;
 
 class KeeperController{
    private $KeeperDAO;
    private $newKeeper;
    private $newMailer;
    private $OwnerDAO;

    public function __construct(){
        $this->KeeperDAO = new KeeperDAO();
        $this->newMailer = new MailerDAO();
        $this->OwnerDAO = new OwnerDAO();
    }
    public function addKeeperView(){
        require_once(VIEWS_PATH."keeper-add.php");
    }
    public function goLoginKeeper(){
        require_once(VIEWS_PATH."loginUser.php");
    }
    public function goLandingKeeper(){
        require_once(VIEWS_PATH."keeperNav.php");
    }
    public function Index($message = ""){
         require_once(VIEWS_PATH."keeperNav.php");
    }
    public function myProfile($keeper){
        require_once(VIEWS_PATH."myProfileKeeper.php");
    }

    public function newKeeper($lastName,$firstName,$cellPhone,$birthDate,$email,$password,$confirmPassword,$animalSize,$price,$userDescription,$cbu,$answerRecovery,$questionRecovery){
        if($this->KeeperDAO->searchKeeperByEmail($email) == NULL){
            if($this->OwnerDAO->searchOwnerByEmail($email) == NULL){
                if(strcmp($password,$confirmPassword) == 0){
            $newKeeper = new Keeper();
            $newKeeper->setLastName($lastName);
            $newKeeper->setfirstName($firstName);
            $newKeeper->setCellPhone($cellPhone);
            $newKeeper->setbirthDate($birthDate);
            $newKeeper->setEmail($email);
            $newKeeper->setPassword($password);
            $newKeeper->setDescription($userDescription);
            $newKeeper->setAnimalSize($animalSize);
            $newKeeper->setPrice($price);
            $newKeeper->setCBU($cbu);
            $newKeeper->setAnswerRecovery($answerRecovery);
            $newKeeper->setQuestionRecovery($questionRecovery);
            $this->KeeperDAO->AddKeeper($newKeeper);
            $this->newMailer->welcomeMail($lastName,$firstName,$email);
            $this->goLoginKeeper();
            }else{
                echo '<div class="alert alert-danger">Passwords are not the same. Please try again</div>';
                    $this->addKeeperView();
            }
        }
        else{
                echo '<div class="alert alert-danger">Email already exist! Please try again with another email</div>';
                $this->addKeeperView();
            }
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
        $value = $this->KeeperDAO->changeAvailabilityDays(SessionHelper::getCurrentKeeperID(),$date1,$date2);
        if($value){
            echo '<div class="alert alert-success">The new dates were set correctly</div>';
        }else{
            echo '<div class="alert alert-danger">Error saving the days! Please try again later</div>';
        }
        $this->goLandingKeeper();
    }

    public function showCurrentKeeper(){
        $keeper=$this->KeeperDAO->searchKeeperByEmail(SessionHelper::getCurrentUser()->getEmail());
        var_dump($keeper);
        $this->myProfile($keeper);
    }
 }
?>