<?php
namespace DAO;
use Models\Booking as Booking;
use DAODB\Connect as Connect;


interface IBookingDAO{
    function addBooking(Booking $booking);
    function getAllBooking();
   // function SaveData();
   // function RetriveData();
}
?>