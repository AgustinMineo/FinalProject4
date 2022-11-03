<?php
namespace DAO;
use Models\Booking as Booking;


interface IBookingDAO{
    function addBooking(Booking $booking);
    function getAllBooking();
}
?>