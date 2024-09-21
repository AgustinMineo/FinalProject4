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
use Models\Owner as Owner;

        // DAO WITH DATA BASE
use DAODB\PetDAO as PetDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\BookingDAO as BookingDAO;
use Helper\SessionHelper as SessionHelper;

class PaymentController {
    private $OwnerDAO;
    private $newMailerDAO;
    private $KeeperDAO;
    private $PetDAO;
    private $bookingDAO;


    public function  __construct(){
        $this->newMailerDAO = new MailerDAO();
        $this->KeeperDAO = new KeeperDAO();
        $this->PetDAO = new PetDAO();
        $this->BookingDAO = new BookingDAO();
    }

    public function goLanding(){
        require_once(VIEWS_PATH."landingPage.php");
    }


    public function generatePaymentBooking($booking){
        SessionHelper::validateUserRole([2]);
        //$Booking=$this->BookingDAO->searchBookingByKeeperID($booking);
        $Booking=$this->BookingDAO->searchBookingByID($booking);
        $keeper = $this->KeeperDAO->searchKeeperByID($Booking->getKeeperId()); 
        $pet = $this->PetDAO->searchPet($Booking->getPetID());
        $status = $this->newMailerDAO->bookingCupon($keeper->getEmail(),$keeper->getfirstName(),$keeper->getLastName(),$keeper->getCBU(),$Booking->getAmountReservation(),$Booking->getStartDate(),$Booking->getEndDate(),$pet->getPetName());
        
        if($status){
            echo '<div class="alert alert-success">The payment was successful! Please wait until the keeper accepts your reservation. </div>';
            $this->BookingDAO->updateByID($booking,"4");
            $this->goLanding();
        }else if($status == false){
            echo '<div class="alert alert-danger">Error al enviar el comprobante</div>';
            $this->goLanding();
        }else{
            echo '<div class="alert alert-danger">The payment could not be made.. </div>';
            $this->goLanding();
        }
    }

}

?>