<?php
namespace Models;

abstract class User {
    private $lastName;
    private $firstName;
    private $cellPhone;
    private $birthDate;
    private $email;
    private $password;
    private $userImage;
    private $userDescription;
    
    // Getter and setters
    public function getLastName(){
        return $this->lastName;
    }
    public function setLastName($lastName){
         $this->lastName = $lastName;
    }

    public function getfirstName(){
        return $this->firstName;
    }
    public function setfirstName($firstName){
        $this->firstName=$firstName;
    }

    public function getCellPhone(){
        return $this->cellPhone;
    }
    public function setCellPhone($cellPhone){
         $this->cellPhone = $cellPhone;
    }

    public function getbirthDate(){
        return $this->birthDate;
    }
    public function setbirthDate($birthDate){
        $this->birthDate = $birthDate;
    }

    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
         $this->email = $email;
    }

    public function getPassword(){
        return $this->password;
    }
    public function setPassword($password){
         $this->password = $password;
    }
    public function getImage(){
        return $this->userImage;
    }
    public function setImage($image){
        $this->userImage = $image;
    }

    public function getDescription(){
        return $this->userDescription;
    }
    public function setDescription($userDescription){
        $this->userDescription = $userDescription;
    }

}
?>