<?php
namespace Controllers;
use DAO\MailerDAO as MailerDAO;
        // DAO WITH JSON

//use DAO\BookingDAO as BookingDAO;
//use DAO\PetDAO as PetDAO;
//use DAO\KeeperDAO as KeeperDAO;
        // MODELS
use Models\Booking as Booking;
use Models\Keeper as Keeper;

// DAO WITH DATA BASE
use DAODB\PetDAO as PetDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\BookingDAO as BookingDAO;
use DAODB\Connection as Connection;

use Helper\SessionHelper as SessionHelper;

class BookingController{
    private $BookingDAO;
    private $petDAO;
    private $keeperDAO;
    private $MailerDAO;

    private $connection;
    private $reviewTable = 'Review';

    //Vista para todos los roles
    public function goBookingViewAll($bookingList=null,$alterRole=null,$userRole=null){
        if($bookingList === null && $alterRole === null && $userRole ===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
        $userRole=SessionHelper::InfoSession([1,2,3]);
        require_once(VIEWS_PATH."BookingListView.php");
        }
    }

    public function goBookingView($petList = null,$listKeepers=null,$value1=null,$value2=null){
        if($petList === null && $listKeepers === null && $value1 ===null && $value2===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
        SessionHelper::InfoSession([2]);
        require_once(VIEWS_PATH."BookingViews.php");
        }
    }
    //Funcion para buscar cuidadores disponibles (Pasa a bookingBuild)
    public function searchBooking(){
        SessionHelper::InfoSession([2]);
        require_once(VIEWS_PATH . "searchByDateBooking.php");
    }
    public function goIndex(){
        SessionHelper::InfoSession([2,3]);
    } 
    public function __construct(){
        $this->BookingDAO = new BookingDAO();
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->MailerDAO = new MailerDAO();
    }
    //Generar la reserva
    public function bookingBuild($value1=null,$value2=null){
        if($value1 ===null && $value2===null){
                if(SessionHelper::getCurrentUser()){
                    SessionHelper::redirectTo403();
                }
        }
        SessionHelper::validateUserRole([2]);
            $keeperDAO = new KeeperDAO();
            $listKeepers = array();
            $listKeepers = $this->keeperDAO->getKeeperByDisponibility($value1,$value2,SessionHelper::getCurrentOwnerID());
            if($listKeepers){
                if(SessionHelper::getCurrentUser()){
                    $petList = array(); /// create a pet array
                    //Este foreach tiene un problema que se genera al tener 2 keepers que cuidan al mismo tamaño de mascota, lo que va a hacer es
                    //Duplicar los registros de las mascotas, 
                    //la forma mas rapida que veo de resolver esto es con un sort_Regular del array asociativo.
                    foreach($listKeepers as $keeperInfo){
                        $petsBySize=$this->petDAO->searchPetsBySize(SessionHelper::getCurrentOwnerID(),$keeperInfo->getAnimalSize());
                        
                        if ($petsBySize) {
                            $petList = array_merge($petList, $petsBySize);
                        }
                    }
                    
                        if($petList)
                        {
                            $petList = array_unique($petList, SORT_REGULAR);//Elimino las mascotas duplicadas.
                            $this->goBookingView($petList,$listKeepers,$value1,$value2);
                        }else{
                            echo "<div class='alert alert-danger'>No tiene mascotas que concuerden con el tamaño</div>";
                            $this->goIndex();
                        }
                }else{
                    echo "<div class='alert alert-danger'>No existen keepers con disponibilidad de $value1 a $value2</div>";
                    $this->goIndex();
                }
            }
            else{
                echo "<div class='alert alert-danger'>No existen keepers disponibles entre esas fechas</div>";
            $this->goIndex();
            }
    }
    //Creamos la reserva
    public function newBooking($email=null,$petId=null,$startDate=null,$finishDate=null){
        if($email === null && $petId === null && $startDate===null && $finishDate===null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        SessionHelper::validateUserRole([2]);
        $newBooking = new Booking();
        $keeperInfo = new Keeper(); //CHECK
        $pet = $this->petDAO->searchPet($petId);//Busco al pet
        $keeperInfo=$this->keeperDAO->searchKeeperByEmail($email); //Busco el keeper

        $finalPrice = $this->BookingDAO->calculateTotalDays($startDate,$finishDate, $keeperInfo->getKeeperId());
        $finalPrice = $finalPrice * $keeperInfo->getPrice();
        $newBooking->setStatus('1');
        $newBooking->setStartDate($startDate);
        $newBooking->setEndDate($finishDate);
        $newBooking->setKeeperID($keeperInfo);
        $newBooking->setTotalValue($finalPrice);
        $newBooking->setAmountReservation($newBooking->getTotalValue() * 0.5);
        $newBooking->setPetID($pet);
        $this->MailerDAO->newBooking($keeperInfo->getLastName(),$keeperInfo->getfirstName(),$keeperInfo->getEmail());
        $result =$this->BookingDAO->addBooking($newBooking);

        //Si se crea correctamente la booking, actualizamos las fechas
        if($result){
            $updateAvailability= $this->keeperDAO->updateDaysByKeeperIDAndDates($keeperInfo->getKeeperId(),$startDate,$finishDate);
        }
        if($result && $updateAvailability){
            echo "<div class='alert alert-success'>You have successfully created a reservation</div>";
            $this->goIndex(); 
        }else{
            echo "<div class='alert alert-danger'>Ops! Something happened when creating the reservation</div>";
            $this->goIndex();
        }
    }
    //Funcion para (Mostrar reservas)
    public function showBookings(){
        $bookingList = array();
        $alterRole =0;//Role para simular vistas
        if(SessionHelper::getCurrentRole() ===1){
            $userRole=SessionHelper::InfoSession([1]);
            $bookingList = $this->BookingDAO->GetAllBooking();
        }elseif(SessionHelper::getCurrentRole() === 2){
            $userRole=SessionHelper::InfoSession([2]);
            $bookingList=$this->BookingDAO->showBookingByOwnerID();
        }else{
            $userRole=SessionHelper::InfoSession([3]);
            $bookingList= $this->BookingDAO->showBookingByKeeperID();
        }
        $this->goBookingViewAll($bookingList,$alterRole,$userRole);
        exit();
    }
    public function updateBookingStatus($idBooking=null,$status=null){
        if($idBooking === null && $status === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        SessionHelper::validateUserRole([3]);
        $value = $this->BookingDAO->updateByID($idBooking,$status);
        $updateAvailability= $this->keeperDAO->updateDaysByBookingIDAndStatus($idBooking,$status);
        if($value && $updateAvailability){
            if($status == 2){
                echo "<div class='alert alert-success'>You have rejected the reservation!</div>";
                $this->goIndex();
            }else if($status == 3){
                echo "<div class='alert alert-success'>You have accepted the reservation!</div>";
                $this->goIndex();
            }else if($status == 5){
                echo "<div class='alert alert-success'>You have confirmed the reservation!</div>";
                $this->goIndex();
            }
        }else{
            echo "<div class='alert alert-danger'>Oops! Something was wrong</div>";
            $this->goIndex();
        }
    }
} 
?>
    