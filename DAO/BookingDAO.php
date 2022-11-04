<?php
namespace DAO;
use DAO\IBookingDAO as IBookingDAO;
use Models\Booking as Booking;


class BookingDAO implements IBookingDAO{
    private $bookingList = array();

    public function addBooking(Booking $booking){
        $this->RetriveData();
       array_push($this->bookingList,$booking);
       $this->SaveData();
    }
    public function getAllBooking(){
        // bring all of the keeper
        $this->RetriveData();
        // return the keeper list
        return $this->bookingList;
    }

    private function SaveData(){
        // creamos un arreglo de keepers
        $bookingList= array();
        // recorremos la lista y guardamos la info del los keeper.
        foreach($this->bookingList as $Booking){
            $bookingValue = array();
            $bookingValue["bookingID"] = $Booking->getBookingID();
            $bookingValue["status"] = $Booking->getStatus();
            $bookingValue["petID"] = $Booking->getPetID();
            $bookingValue["firstDate"] = $Booking->getFirstDate();
            $bookingValue["lastDate"] = $Booking->getLastDate();
            $bookingValue["keeperID"] = $Booking->getKeeperID();
            array_push($bookingList, $bookingValue);
        }
        $bookingFile = json_encode($bookingList, JSON_PRETTY_PRINT);
        file_put_contents('Data/Booking.json',$bookingFile);
    }
  
    private function RetriveData(){
        $this->bookingList = array();
        //Tenemos que tener el file creado
        if(file_exists('Data/Booking.json')){
            $bookingFile = file_get_contents(ROOT.'Data/Booking.json');
            // Si el file tiene datos hace un decode de la info y la guarda en el arreglo, sino devuelve un arreglo vacio.
            $bookingFileDecode =($bookingFile) ? json_decode($bookingFile, true) : array();
  
            foreach($bookingFileDecode as $BookingDecode){
                $booking = new Booking();
                $booking->setBookingID($BookingDecode["bookingID"]);
                $booking->setStatus($BookingDecode["status"]);
                $booking->setPetID($BookingDecode["petID"]);
                $booking->setFirstDate($BookingDecode["firstDate"]);
                $booking->setLastDate($BookingDecode["lastDate"]);
                $booking->setKeeperID($BookingDecode["keeperID"]);
                array_push($this->bookingList, $booking);
            }
        }else{
            echo "The booking file doesn't exists";
        }
    }

    public function showBookingByKeeperID(){
        $this->RetriveData();
        $bookingListKeeper = array();
        if($this->bookingList){
            foreach($this->bookingList as $booking){
                if($booking->getKeeperID() == $_SESSION["loggedUser"]->getKeeperId()){
                    array_push($bookingListKeeper,$booking);
                }
            }
        }else{
            echo "<h1>No existen reservas</h1>";
        }

        if($bookingListKeeper){
         return $bookingListKeeper;
        }else{
            return array();
        }
    }
    public function searchByID($idBooking){
        $bookingList = array();
        $bookingList = $this->showBookingByKeeperID();
        if($bookingList){
            foreach($bookingList as $booking){
                if($booking->getBookingID()==$idBooking){
                    return $booking;
                }
            }
        }
    }
    public function updateByID($id, $status){
        $bookingRecord = $this->searchByID($id);
        if($bookingRecord){
            $bookingRecord->setStatus($status);
            $this->SaveData();
            return $status;
        }else{
            return null;
        }
    }
    public function getLastBookingID(){
        $listValues = array();
        $this->RetriveData();
         $listValues = $this->bookingList;
        end($listValues);
        if(key ($listValues) !=null){
            return key($listValues);
        }else{
            return 1;
        }
    }
}
?>