<?php
 namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
use DAODB\OwnerDAO as OwnerDAO;
use Models\Owner as Owner;
use DAO\MailerDAO as MailerDAO;
 class OwnerController
 {
    private $OwnerDAO;
    private $newOwner;
    private $newMailer;
    
    public function  __construct(){
        $this->OwnerDAO = new OwnerDAO();
        $this->newMailerDAO = new MailerDAO();
    }

    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginUser.php");
    }
    public function goLandingOwner(){
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }

     public function newOwner($lastName,$firstName,$cellPhone,$birthDate,$email,$password,$userImage,$userDescription){

        if($this->OwnerDAO->searchOwnerByEmail($email) == NULL){
            $newOwner = new Owner();
            $newOwner->setLastName($lastName);
            $newOwner->setfirstName($firstName);
            $newOwner->setCellPhone($cellPhone);
            $newOwner->setbirthDate($birthDate);
            $newOwner->setEmail($email);
            $newOwner->setPassword($password);
            $newOwner->setImage($userImage);
            $newOwner->setDescription($userDescription);
            $newOwner->setPetAmount('0');
            $this->OwnerDAO->AddOwner($newOwner);
            $this->newMailerDAO->welcomeMail($lastName,$firstName,$email);
            $this->goLoginOwner();
        }
        else{
            //echo '<div class="alert alert-danger">Email already exist! Please try again with another email</div>';
            $this->addOwnerView();
        }
    }
    
 }

?>