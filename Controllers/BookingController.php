<?php
namespace Controllers;
use DAO\BookingDAO as BookingDAO;
use Models\Booking as Booking;
use DAO\PetDAO as PetDAO;
use DAO\KeeperDAO as KeeperDAO;

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


    public function newBooking($firstDate,$lastDate,$keeperID,$value,$petSize){
        $newBooking = new Booking();
       // $newBooking->getLastBookingID(); connected with the database
       $newBooking->setStatus('1');
       $newBooking->setFirstDate($firstDate);
       $newBooking->setLastDate($lastDate);
       $newBooking->setKeeperID($keeperID);
       $newBooking->setTotalValue($value); /// calcular el valor de la noche, $value*cantDias.
       //$newBooking->setAmountReservation(); /// value*cantDias * 0.5;
       if($_SESSION['loggedUser']){
        $petList = array(); /// create a pet array
        var_dump ($newBooking);
        $petList=$this->petDAO->searchPetsBySize($_SESSION['loggedUser']->getEmail(),$petSize);
        var_dump($petList);
            $this->GoBooking();
            $newBooking->setPetID(selectPetByID());
            $this->BookingDAO->addBooking($newBooking);
       }

    }
}
?>