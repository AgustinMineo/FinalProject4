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
         require_once(VIEWS_PATH."showPetBooking.php");
     }
    
    public function __construct(){
        $this->BookingDAO = new BookingDAO();
        $this->petDAO = new PetDAO();
        $this->keeperDAO = new KeeperDAO();
    }


    public function newBooking($email){
        $newBooking = new Booking();
        $keeperInfo = new Keeper();
        $keeperInfo=$this->keeperDAO->searchEmail($email);
       // $newBooking->getLastBookingID(); connected with the database
       $newBooking->setStatus('1');
       $newBooking->setFirstDate($keeperInfo->getFirstAvailabilityDays());
       $newBooking->setLastDate($keeperInfo->getLastAvailabilityDays());
       $newBooking->setKeeperID($keeperInfo->getKeeperId());
       $newBooking->setTotalValue($keeperInfo->getPrice()); /// calcular el valor de la noche, $value*cantDias.
       //$newBooking->setAmountReservation(); /// value*cantDias * 0.5;
       if($_SESSION['loggedUser']){
        $petList = array(); /// create a pet array
        $petList=$this->petDAO->searchPetsBySize($_SESSION['loggedUser']->getEmail(),$keeperInfo->getAnimalSize());
        if($petList)
        {
            require_once(VIEWS_PATH. "showPetBooking.php");
            $pet=1;
            var_dump($pet);
            $newBooking->setPetID(1);
            $this->BookingDAO->addBooking($newBooking);
        }else{
            echo "<h1>No tiene mascotas que concuerden con el tamaño</h1>";
        }
        
        
        
       }

    }
    
}
?>