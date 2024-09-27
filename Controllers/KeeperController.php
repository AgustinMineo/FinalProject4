<?php
 namespace Controllers;
 use Models\Keeper as Keeper;
 use Helper\SessionHelper as SessionHelper;

//use DAO\KeeperDAO as KeeperDAO;
//use DAO\OwnerDAO as OwnerDAO;

use DAO\MailerDAO as MailerDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\OwnerDAO as OwnerDAO;
use DAODB\BookingDAO as BookingDAO;

class KeeperController{
    private $KeeperDAO;
    private $BookingDAO;
    private $newKeeper;
    private $newMailer;
    private $OwnerDAO;

    public function __construct(){
        $this->KeeperDAO = new KeeperDAO();
        $this->BookingDAO = new BookingDAO();
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
        SessionHelper::InfoSession([3]);
    }
    public function showKeepersAll($listKeepers){
        $userRole=SessionHelper::InfoSession([1,2]);
        require_once(VIEWS_PATH. "showKeeper.php");
    }
    public function myProfile($keeper){
        SessionHelper::InfoSession([3]);
        require_once(VIEWS_PATH."myProfileKeeper.php");
    }
    public function updateDaysAvailables(){
        SessionHelper::InfoSession([3]);
        require_once(VIEWS_PATH."updateAvailabilityDays.php");
    }
    public function calendarDays($days,$bookings,$keeper){
        SessionHelper::InfoSession([3]);
        require_once(VIEWS_PATH."keeperDays.php");
    }

    public function newKeeper($lastName,$firstName,$cellPhone,$birthDate,$email,
    $password,$confirmPassword,$animalSize,$price,$userDescription,$cbu,$QuestionRecovery,$answerRecovery){
        if($this->KeeperDAO->searchKeeperByEmail($email) == NULL){
            if($this->OwnerDAO->searchOwnerByEmail($email) == NULL){
                if(strcmp($password,$confirmPassword) == 0){
                    if($this->KeeperDAO->searchCBU($cbu) ==NULL && strlen($cbu)<=20){
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
                        $newKeeper->setPoints(0);
                        $newKeeper->setQuestionRecovery($QuestionRecovery);
                        $newKeeper->setAnswerRecovery($answerRecovery);
                        $newKeeper->setRol(3);
                        $this->KeeperDAO->AddKeeper($newKeeper);
                        $this->newMailer->welcomeMail($lastName,$firstName,$email);
                        $this->goLoginKeeper();
                    }else{
                        echo "<div class='alert alert-danger'>The CBU already exist or has more than 20 digits.</div>";
                        $this->addKeeperView();
                    }
            }else{
                echo "<div class='alert alert-danger'>Passwords are not the same. Please try again</div>";
                $this->addKeeperView();
            }
        }
        else{
                echo "<div class='alert alert-danger'>Email already exist! Please try again with another email</div>";
                $this->addKeeperView();
            }
        }        
        else{
            echo "<div class='alert alert-danger'>Email already exist! Please try again with another email</div>";
            $this->addKeeperView();
        }
         //$newKeeper->setPoints('0');
         //$newKeeper->setkeeperId($this->searchLastKeeperID()); TO DO
         //$newKeeper->setKeeperImg($keeperImg);
    }

    public function showKeepers(){
        $listKeepers = array();
        $listKeepers = $this->KeeperDAO->getAllKeeper();
        if($listKeepers){
            $this->showKeepersAll($listKeepers);
        }else{
            echo '<div class="alert alert-danger">There is no availables keepers right now!</div>';
            $this->goLandingKeeper();
        }
    }

    public function showKeepersByAvailability($value1,$value2){
        SessionHelper::validateUserRole([3]);
        $listKeepers = array();
        $listKeepers = $this->KeeperDAO->getKeeperByDisponibility($value1,$value2);
        if($listKeepers){
            require_once(VIEWS_PATH. "keeperCard.php");
        }else{
            echo "<h1>No existen keepers con disponibilidad de entre $value1 y $value2</h1>";
        }
    }

    public function updateAvailabilityDays($date1,$date2,$available){
        SessionHelper::validateUserRole([3]);
        //Si la fecha date1 es < a la fecha actual deberia dar error
        //Si la fecha de date2 es < a la fecha de hoy deberia dar error
        $today = date('Y-m-d');
        if($date1 <$today && $date2<$today){
            echo '<div class="alert alert-danger">Error saving the days! The dates can be less that today´s day </div>';
            $this->goLandingKeeper();
            return;
        }else if($date1<$today){
            echo '<div class="alert alert-danger">Error saving the days! The start date can be less that today´s day </div>';
            $this->goLandingKeeper();
            return;
        }else if($date2<$today){
            echo '<div class="alert alert-danger">Error saving the days! The end date can be less that today´s day </div>';
            $this->goLandingKeeper();
            return;
        }else{

            $value = $this->KeeperDAO->changeAvailabilityDays(SessionHelper::getCurrentKeeperID(),$date1,$date2,$available);
            if($value){
                echo '<div class="alert alert-success">The new dates were set correctly</div>';
            }else{
                echo '<div class="alert alert-danger">Error saving the days! Please try again later</div>';
            }
            $this->goLandingKeeper();
        }
    }
    //Funcion para vista calendario
    public function updateAvailabilityDay($date,$available){
        SessionHelper::validateUserRole([3]);
        if($date < date('Y-m-d')){
            echo json_encode(['status' => 'error', 'message' => 'No se puede actualizar una fecha pasada a la actual ']);
            return;
        }else{
            if($available=='1'){
                $newAvailable=0;
            }else{
                $newAvailable=1;
            }
            $value = $this->KeeperDAO->changeAvailabilityDay(SessionHelper::getCurrentKeeperID(),$date,$newAvailable);
            if($value){
                echo json_encode(['status' => 'success', 'message' => 'The date was succefully update! ']);
                return;
            }else{
                echo json_encode(['status' => 'error', 'message' => 'Error saving the days! Please try again later! ']);
                return;
            }
        }
    }
    //Funcion para mostrar el calendario
    public function showCalendarData(){
        SessionHelper::validateUserRole([3]);

        // Obtener el keeper actual
        $keeper = $this->KeeperDAO->searchKeeperByIDReduce(SessionHelper::getCurrentUser()->getKeeperId());

        // Obtener los días del keeper
        $days = $this->KeeperDAO->getAvailabilityDays($keeper->getKeeperId());

        $bookings = $this->BookingDAO->showBookingByKeeperID();
        $this->calendarDays($days,$bookings,$keeper);
    }
    //Funcion para refrescar el calendario luego de un cambio
    public function refreshDays() {
        SessionHelper::validateUserRole([3]);
        
        // Obtener el keeper actual
        $keeper = $this->KeeperDAO->searchKeeperByIDReduce(SessionHelper::getCurrentUser()->getKeeperId());
    
        // Obtener los días del keeper
        $days = $this->KeeperDAO->getAvailabilityDays($keeper->getKeeperId());
        $bookings = $this->BookingDAO->showBookingByKeeperID();
        //$this->calendarDays($days,$bookings,$keeper);
        // Generar el HTML que será enviado a la llamada AJAX
    
        return $days; /// Devuelve el HTML generado
    }
    
    public function showCurrentKeeper(){
        SessionHelper::validateUserRole([3]);
        $keeper=$this->KeeperDAO->searchKeeperByEmail(SessionHelper::getCurrentUser()->getEmail());
        $this->myProfile($keeper);
    }
}
?>