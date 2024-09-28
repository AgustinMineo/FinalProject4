<?php
 namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
//use DAO\KeeperDAO as KeeperDAO;
use Models\Owner as Owner;
use DAODB\OwnerDAO as OwnerDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAO\MailerDAO as MailerDAO;

use Helper\SessionHelper as SessionHelper;

 class OwnerController
 {
    private $OwnerDAO;
    private $newOwner;
    private $newMailer;
    private $KeeperDAO;
    
    public function  __construct(){
        $this->OwnerDAO = new OwnerDAO();
        $this->newMailerDAO = new MailerDAO();
        $this->KeeperDAO = new KeeperDAO();
    }

    public function goLoginOwner(){
        require_once(VIEWS_PATH."loginUser.php");
    }
    public function goLandingOwner(){
        $userRole=SessionHelper::InfoSession([1,2]);
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function addOwnerView(){
        require_once(VIEWS_PATH."owner-add.php");
    }

    public function goMyProfile($owner){
        $userRole=SessionHelper::InfoSession([2]);
        require_once(VIEWS_PATH."myProfileOwner.php");
    }

    public function newOwner($rol,$lastName,$firstName,$cellPhone,$birthDate,$email,$password,
    $confirmPassword,$userDescription,$QuestionRecovery,$answerRecovery){
        $userRole=0;
        if($rol!='0'){
            $userRole=SessionHelper::getCurrentRole();
        }

        if($lastName && $firstName && $cellPhone && $birthDate 
            && $email && $password && $confirmPassword && $userDescription 
            && $QuestionRecovery && $answerRecovery){
        if($this->OwnerDAO->searchOwnerByEmail($email) == NULL){
            if($this->KeeperDAO->searchKeeperByEmail($email) == NULL){
                if(strcmp($password,$confirmPassword) == 0){
                    $newOwner = new Owner();
                    $newOwner->setLastName($lastName);
                    $newOwner->setfirstName($firstName);
                    $newOwner->setCellPhone($cellPhone);
                    $newOwner->setbirthDate($birthDate);
                    $newOwner->setEmail($email);
                    $newOwner->setPassword($password);
                    $newOwner->setDescription($userDescription);
                    $newOwner->setQuestionRecovery($QuestionRecovery);
                    $newOwner->setAnswerRecovery($answerRecovery);
                    $newOwner->setPetAmount(0);
                    $newOwner->setRol(2);
                    if(intval($rol) === 1 && intval(SessionHelper::getCurrentRole()) === 1){
                        //Si el rol enviado es 1 y el rol del currentUser es 1 (Admin) pisamos el rol
                        $newOwner->setRol(1);//Asigno role admin
                    }
                    $this->OwnerDAO->AddOwner($newOwner);
                    $this->newMailerDAO->welcomeMail($lastName,$firstName,$email);

                    if($userRole===0){//Se evalua si es desde admin o desde registro owner
                        $_SESSION["loggedUser"] = $newOwner;
                        $this->goLandingOwner();
                    }else{
                        $userRole=SessionHelper::InfoSession([1]);
                        $ownerUsers = $this->OwnerDAO->GetAllOwner();
                        $keeperUsers= $this->KeeperDAO->GetAllKeeper();
                        $adminUsers = $this->OwnerDAO->GetAllAdminUser();
                        require_once(VIEWS_PATH."userListAdminView.php");
                        //return $newOwner;
                    }
                }else{
                    echo '<div class="alert alert-danger">Las contraseñas no son iguales. Intente de nuevo</div>';
                    $this->addOwnerView(); 
                } 
            }
            else{
                echo '<div class="alert alert-danger">Email already exist! Please try again with another email</div>';
                $this->addOwnerView();  
            }
        }
    }else{
        echo '<div class="alert alert-danger">All the values are required!</div>';
        $this->addOwnerView();
    }
}

    public function showCurrentOwner(){
        //SessionHelper::validateUserRole([2]);
        $owner=$this->OwnerDAO->searchOwnerByEmail(SessionHelper::getCurrentUser()->getEmail());
        $this->goMyProfile($owner);
    }

} 
?>
