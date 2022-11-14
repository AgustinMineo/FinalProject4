<?php
namespace Controllers;
//use DAO\BookingDAO as BookingDAO;
use Models\Booking as Booking;
//use DAO\PetDAO as PetDAO;
//use DAO\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;
use DAODB\PetDAO as PetDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\BookingDAO as BookingDAODB;

class BookingController{
    private $BookingDAO;
    private $petDAO;
    private $keeperDAO;

    public function GoBooking()
     {
         require_once(VIEWS_PATH."showBookingKeeper.php");
     }
     public function goIndexOwner()
     {
         require_once(VIEWS_PATH."showPetBooking.php");
     }
     public function goBookingView($petList, $listKeepers){
        require_once(VIEWS_PATH."ownerNav.php");
        require_once(VIEWS_PATH."BookingViews.php");
     }
    
    public function __construct(){
        //$this->BookingDAO = new BookingDAO();
        $this->BookingDAO = new BookingDAODB;
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function bookingBuild($value1,$value2){
        $keeperDAO = new KeeperDAO();
        $listKeepers = array();
        $listKeepers = $keeperDAO->getKeeperByDisponibility($value1,$value2);
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
                    echo "<h1>No tiene mascotas que concuerden con el tamaño</h1>";
                }
                //$this->goBookingView($petList, $listKeepers);
            }else{
                echo "<h1>No existen keepers con disponibilidad de entre $value1 y $value2</h1>";
            }
        }
    }

    public function newBooking($email,$petId){
        echo $petId;
        $newBooking = new Booking();
        $keeperInfo = new Keeper(); //CHECK
        $keeperInfo=$this->keeperDAO->searchKeeperByEmail($email);
        $newBooking->setStatus('1');
        $newBooking->setFirstDate($keeperInfo->getFirstAvailabilityDays());//cambiar a dias que pide el owner // tomar datos de form y pasarlos a array para tirarlos aca VER
        $newBooking->setLastDate($keeperInfo->getLastAvailabilityDays());// cambiar a dias que pide el owner
        $newBooking->setKeeperID($keeperInfo->getKeeperId());
        $newBooking->setTotalValue($this->priceCounter($newBooking->getFirstDate(), $newBooking->getLastDate(), $keeperInfo->getPrice()));
        $newBooking->setAmountReservation(1000); /// value*cantDias * 0.5; ESTO ES LA SEÑA TO DO
        //require_once(VIEWS_PATH. "showPetBooking.php");
        $newBooking->setPetID($petId);
        $this->BookingDAO->addBooking($newBooking);
        //require_once()
    }
// MIGRAR A DAO
    public function showBookings(){
        $bookingListKeeper = array();
        $bookingListKeeper=$this->BookingDAO->showBookingByKeeperID();
        require_once(VIEWS_PATH."showBookingKeeper.php");
    }
// MIGRAR A DAO
    public function updateBookingStatus($idBooking, $status){
        $value = $this->BookingDAO->updateByID($idBooking,$status);
        if($value){
            echo "<h1>Update correcta.</h1>";
        }else{
            echo "<h4>Error al actualizar el status</h4>";
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