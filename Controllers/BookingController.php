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
use Helper\SessionHelper as SessionHelper;

class BookingController{
    private $BookingDAO;
    private $petDAO;
    private $keeperDAO;
    private $MailerDAO;

    public function GoBooking(){
         require_once(VIEWS_PATH."showBookingKeeper.php");
    }
     public function goIndexOwner(){
         require_once(VIEWS_PATH."showPetBooking.php");
    }
    public function goIndexKeeper(){
        require_once(VIEWS_PATH."keeperNav.php");
    }
     public function goBookingView($petList,$listKeepers){

        require_once(VIEWS_PATH."ownerNav.php");
        require_once(VIEWS_PATH."BookingViews.php");
    }
     public function goIndex(){
        require_once(VIEWS_PATH."landingPage.php");
    } 
    public function __construct(){
        $this->BookingDAO = new BookingDAO();
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->MailerDAO = new MailerDAO();
    }
    public function bookingBuild($value1,$value2){
        // if($value1 > $value2){
        //     echo "<div class='alert alert-danger'>Las fechas no son correctas</div>";
        //     $this->goIndex(); }
        // else {
            $keeperDAO = new KeeperDAO();
            $listKeepers = array();
            $listKeepers = $this->keeperDAO->getKeeperByDisponibility($value1,$value2);
            if($listKeepers){
                if(SessionHelper::getCurrentUser()){
                    $petList = array(); /// create a pet array
                    foreach($listKeepers as $keeperInfo){
                        $petList=$this->petDAO->searchPetsBySize(SessionHelper::getCurrentOwnerID(),$keeperInfo->getAnimalSize());

                        }
                            if($petList)
                            {
                                $this->goBookingView($petList,$listKeepers);
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

    public function newBooking($email,$petId){
        $newBooking = new Booking();
        $keeperInfo = new Keeper(); //CHECK
        $keeperInfo=$this->keeperDAO->searchKeeperByEmail($email);
        $newBooking->setStatus('1');
        $newBooking->setFirstDate($keeperInfo->getFirstAvailabilityDays());//cambiar a dias que pide el owner // tomar datos de form y pasarlos a array para tirarlos aca VER
        $newBooking->setLastDate($keeperInfo->getLastAvailabilityDays());// cambiar a dias que pide el owner
        $newBooking->setKeeperID($keeperInfo->getKeeperId());
        $newBooking->setTotalValue($this->priceCounter($newBooking->getFirstDate(), $newBooking->getLastDate(), $keeperInfo->getPrice()));
        $newBooking->setAmountReservation($newBooking->getTotalValue()*0.5); /// value*cantDias * 0.5; ESTO ES LA SEÑA TO DO
        $newBooking->setPetID($petId);
        $this->MailerDAO->newBooking($keeperInfo->getLastName(),$keeperInfo->getfirstName(),$keeperInfo->getEmail());
        $result =$this->BookingDAO->addBooking($newBooking);
        if($result){
            echo "<div class='alert alert-success'>You have successfully created a reservation</div>";
            $this->goIndex(); 
        }else{
            echo "<div class='alert alert-danger'>Ops! Something happened when creating the reservation</div>";
            $this->goIndex();
        }
    }
// MIGRAR A DAO
    public function showBookings(){
        $bookingListKeeper = array();
        $bookingListKeeper= $this->BookingDAO->showBookingByKeeperID();
        require_once(VIEWS_PATH."showBookingKeeper.php");
    }
//MIGRAR A DAO
    public function showBookingsOwner(){
        $bookingListKeeper = array();
        $petListByOwner = array();
        $petListByOwner=$this->petDAO->searchPetList();
        if($petListByOwner){
            $bookingListKeeper=$this->BookingDAO->showBookingByOwnerID($petListByOwner);
            if($bookingListKeeper){
                require_once(VIEWS_PATH."BookingViewOwner.php");
            }else{
                echo "<div class='alert alert-danger'>You have no reservations available!</div>";
                $this->goIndex();
            }

        }else{
            echo "<div class='alert alert-danger'>You have no pets!!</div>";
                $this->goIndex();
        }
    }
// MIGRAR A DAO
    public function updateBookingStatus($idBooking,$status){
        $value = $this->BookingDAO->updateByID($idBooking,$status);
        if($value){
            if($status == 2){
                echo "<div class='alert alert-success'>You have rejected the reservation!</div>";
                $this->goIndexKeeper();
            }else if($status == 3){
                echo "<div class='alert alert-success'>You have accepted the reservation!</div>";
                $this->goIndexKeeper();
            }else if($status == 5){
                echo "<div class='alert alert-success'>You have confirmed the reservation!</div>";
                $this->goIndexKeeper();
            }
        }else{
            echo "<div class='alert alert-danger'>Oops! Something was wrong</div>";
            $this->goIndexKeeper();
        }
    }
    public function petWithBooking($petID){
        try{
            $query = "SELECT petID FROM ".$this->bookingTable." WHERE petID = $petID;";

            $this->connection = Connection::GetInstance();
            $resultSet = $this->connection->Execute($query);
            if($resultSet){ return $resultSet; } else { return NULL; } 
        } catch( Exception $ex ){ throw $ex; }
    }
//ARREGLAR TOTAL DE PRECIO Y MIGRARLO A DAO
    public function priceCounter($first, $last, $price){
        $firstDay = strtotime($first);
        $lastDay = strtotime($last);
        $timeDiff = abs($firstDay - $lastDay);
        $numberDays = $timeDiff/86400;  // 86400 SEGUNDOS EN EL DIA
        $numberDays = intval($numberDays); // PARA PASARLO A ENTERO
        return $price * $numberDays;
    }
} ?>