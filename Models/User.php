<?php
namespace Models;

abstract class User {
    private $userID;
    private $lastName;
    private $firstName;
    private $cellPhone;
    private $birthDate;
    private $email;
    private $password;
    private $userDescription;
    private $questionRecovery;
    private $answerRecovery;
    private $rol; // 1- Admin, 2- Owner, 3- Keeper
    private $status; //1 activo, 0 eliminado
    private $image;
    
    // Getter and setters
    public function getUserID(){
        return $this->userID;
    }
    public function setUserID($userID){
         $this->userID = $userID;
    }

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
    $this->birthDate =$birthDate;
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
    public function getRol(){
        return $this->userRol;
    }
    public function setRol($rol){
        $this->userRol = $rol;
    }

    public function getDescription(){
        return $this->userDescription;
    }
    public function setDescription($userDescription){
        $this->userDescription = $userDescription;
    }

    public function getQuestionRecovery(){
        return $this->questionRecovery;
    }
    public function setQuestionRecovery($questionRecovery){
        $this->questionRecovery = $questionRecovery;
    }

    public function getAnswerRecovery(){
        return $this->answerRecovery;
    }
    public function setAnswerRecovery($answerRecovery){
        $this->answerRecovery = $answerRecovery;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }
    public function getImage(){
        return $this->image;
    }
    public function setImage($image){
        $this->image = $image;
    }
    public function toArray() {
        return [
            'userID' => $this->userID,
            'lastName' => $this->lastName,
            'firstName' => $this->firstName,
            'cellPhone' => $this->cellPhone,
            'birthDate' => $this->birthDate,
            'email' => $this->email,
            'userDescription' => $this->userDescription,
            'rol' => $this->rol,
            'status' => $this->status,
            'image' => $this->image,
        ];
    }
}
?>