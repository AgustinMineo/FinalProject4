<?php
namespace Controllers;
use DAO\BookingDAO as BookingDAO;
use Models\Booking as Booking;
use DAO\PetDAO as PetDAO;
use DAO\KeeperDAO as KeeperDAO;
use Models\Keeper as Keeper;

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
    
    public function __construct(){
        $this->BookingDAO = new BookingDAO();
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
                 $petList=$this->petDAO->searchPetsBySize($_SESSION['loggedUser']->getEmail(),$keeperInfo->getAnimalSize());
                }
                 if($petList)
                 {
                }else{
                    echo "<h1>No tiene mascotas que concuerden con el tamaño</h1>";
                }
                require_once(VIEWS_PATH. "BookingViews.php");
            }else{
                echo "<h1>No existen keepers con disponibilidad de entre $value1 y $value2</h1>";
            }
        }
    }

    public function newBooking($email,$petID){
        $newBooking = new Booking();
        $keeperInfo = new Keeper();
        $keeperInfo=$this->keeperDAO->searchEmail($email);
        $newBooking->setBookingID($this->BookingDAO->getLastBookingID()); //connected with the database
       $newBooking->setStatus('1');
       $newBooking->setFirstDate($keeperInfo->getFirstAvailabilityDays());
       $newBooking->setLastDate($keeperInfo->getLastAvailabilityDays());
       $newBooking->setKeeperID($keeperInfo->getKeeperId());
       $newBooking->setTotalValue($keeperInfo->getPrice()); /// calcular el valor de la noche, $value*cantDias.
       //$newBooking->setAmountReservation(); /// value*cantDias * 0.5;
            //require_once(VIEWS_PATH. "showPetBooking.php");
            $newBooking->setPetID($petID);
            $this->BookingDAO->addBooking($newBooking);
            //require_once()
    }
    public function showBookings(){
        $bookingListKeeper = array();
        $bookingListKeeper=$this->BookingDAO->showBookingByKeeperID();
        require_once(VIEWS_PATH."showBookingKeeper.php");
    }
    public function updateBookingStatus($idBooking, $status){
        $value = $this->BookingDAO->updateByID($idBooking,$status);
        if($value){
            echo "<h1>Update correcta.</h1>";
        }else{
            echo "<h4>Error al actualizar el status</h4>";
        }
    }
}
?>