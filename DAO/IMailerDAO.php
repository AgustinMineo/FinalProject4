<?php
namespace DAO;
use Models\User as User;
use Models\Keeper as Keeper;
use Models\Owner as Owner;
use Models\Booking as Booking;

interface IMailerDAO{
    function welcomeMail($email,$name,$lastname);
    function newBooking($lastname,$name,$correo);
    function bookingCupon($Keeperemail,$Keepername,$Keeperlastname,$cuit,$amountReservation,$firstDate,$lastDate,$petName);
}
?>