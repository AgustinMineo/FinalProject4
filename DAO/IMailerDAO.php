<?php
namespace DAO;
use Models\User as User;

interface IMailerDAO{
    function welcomeMail($email,$name,$lastname);
    function confirmBooking($filePDF,$correo,$name,$lastname);
   // function forgotPassword($email,$name,$lastname);
    //function newBooking(User $user);
}
?>