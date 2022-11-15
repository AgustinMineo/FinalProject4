<?php
namespace Controllers;
use DAO\MailerDAO as MailerDAO;
use DAO\BookingDAO as BookingDAO;
use Models\Booking as Booking;
use DAO\PetDAO as PetDAO;
use DAO\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;
//use DAODB\PetDAO as PetDAO;
//use DAODB\KeeperDAO as KeeperDAO;
//use DAODB\BookingDAO as BookingDAODB;


class BookingController{
    private $BookingDAO;
    private $petDAO;
    private $keeperDAO;
    private $MailerDAO;

    public function GoBooking()
     {
         require_once(VIEWS_PATH."showBookingKeeper.php");
     }
     public function goIndexOwner()
     {
         require_once(VIEWS_PATH."showPetBooking.php");
     }
     public function goBookingView($listKeepers,$petList){
        require_once(VIEWS_PATH."ownerNav.php");
        require_once(VIEWS_PATH."BookingViews.php");
     }

     public function goIndex(){
        require_once(VIEWS_PATH."landingPage.php");
     }
    
    public function __construct(){
        $this->BookingDAO = new BookingDAO();
        //$this->BookingDAO = new BookingDAODB;
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->MailerDAO = new MailerDAO();
    }
    public function bookingBuild($value1,$value2){
        $listKeepers = array();
        $listKeepers = $this->keeperDAO->getKeeperByDisponibility($value1,$value2);
        if($listKeepers){
            if($_SESSION['loggedUser']){
                 $petList = array(); /// create a pet array
                 foreach($listKeepers as $keeperInfo){
                 $petList=$this->petDAO->searchPetsBySize($_SESSION['loggedUser']->getOwnerID(),$keeperInfo->getAnimalSize());
                }
                 if($petList)
                 {
                     $this->goBookingView($petList,$listKeepers);
                }else{
                    echo "<div class='alert alert-danger'>No tiene mascotas que concuerden con el tamaño</div>";
                    $this->goIndex();
                }
                //$this->goBookingView($petList, $listKeepers);
            }else{
                echo "<div class='alert alert-danger'>No existen keepers con disponibilidad de $value1 a $value2</div>";
                $this->goIndex();
            }
        }else{
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
        //require_once(VIEWS_PATH. "showPetBooking.php");
        $newBooking->setPetID($petId);
        $this->MailerDAO->newBooking($keeperInfo->getLastName(),$keeperInfo->getfirstName(),$keeperInfo->getEmail());
        $this->BookingDAO->addBooking($newBooking);
        
        $this->goIndex();
        //require_once()
    }
// MIGRAR A DAO
    public function showBookings(){
        $bookingListKeeper = array();
        $bookingListKeeper=$this->BookingDAO->showBookingByKeeperID();
        require_once(VIEWS_PATH."showBookingKeeper.php");
    }
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
        }
    }
// MIGRAR A DAO
    public function updateBookingStatus($idBooking, $status){
        $value = $this->BookingDAO->updateByID($idBooking,$status);
        if($value){
            echo "<div class='alert alert-success'>Fechas actualizadas correctamente!</div>";
            $this->goIndex();
        }else{
            echo "<div class='alert alert-danger'>Error al actualizar las fechas!</div>";
            $this->goIndex();
        }
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
}
?>