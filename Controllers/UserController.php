<?php
namespace Controllers;

//use DAO\OwnerDAO as OwnerDAO;
//use DAO\KeeperDAO as KeeperDAO;

use DAODB\OwnerDAO as OwnerDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\UserDAO as UserDAO;
use DAODB\MessageDAO as MessageDAO;
use Models\Owner as Owner;
use Models\Keeper as Keeper;
use Models\User as User;
use Helper\SessionHelper as SessionHelper;
use Helper\FileUploader as FileUploader;

class UserController{
    private $OwnerDAO;
    private $KeeperDAO;
    private $UserDAO;
    private $MessageDAO;
    private $fileUploader;

    public function __construct(){
        $this->OwnerDAO = new OwnerDAO();
        $this->KeeperDAO = new KeeperDAO();
        $this->UserDAO = new UserDAO();
        $this->MessageDAO = new MessageDAO();
        $this->fileUploader = new FileUploader(USER_PATH);
    }
    //Registros de usuario (Owner)
    public function newOwner(){
        require_once(VIEWS_PATH."owner-add.php");
    }
    //Registros de usuario (Keeper)
    public function newKeeper(){
        require_once(VIEWS_PATH."keeper-add.php");
    }
    //Navbar all users
    public function goNavBar(){
        $userRole=SessionHelper::InfoSession([1,2,3]);
        require_once(VIEWS_PATH."mainNavbar.php");
        require_once(VIEWS_PATH."landingPage.php");
    }
    public function gologinUser(){
        require_once(VIEWS_PATH."loginUser.php");
    }
    //Vista para editar a todos los usuarios (Admin,owner y keeper (Formularios))
    //Se agrega =null por si se intenta de viajar directamente cambiando la url, para que se evalue y no de error
    //Si tiene session significa que esta logeado y tira al 403, sino te manda al login
    public function goEditView($userSearch=null,$roleUser=null){
        if($userSearch === null && $roleUser === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            $userRole=SessionHelper::InfoSession([1,2,3]);
            //Reviso si el usuario es un string y si tiene forma de correo.
            //Solo en el caso de que sea admin y edite un usuario
            $flag=1;
            //Transformo el role a int
            $role = intval($roleUser);

            if($role){
                //Caso de que entre por vista de admin (Owner o Keeper)
                if(is_string($userSearch) && filter_var($userSearch,FILTER_VALIDATE_EMAIL) && $role!=1){
                    $flag=1;
                    if($role === 2){
                        $user = $this->OwnerDAO->searchOwnerByEmail($userSearch);
                    }else{
                        $user= $this->KeeperDAO->searchKeeperByEmail($userSearch);
                    }
                }elseif($role !=1){
                    $user = $userSearch;
                }else{
                    //Si es perfil admin y el correo es el mismo que el del admin, entonces modifica su usuario
                    if($role === 1 && $userSearch === SessionHelper::getCurrentUser()->getEmail()){
                        $flag = 0;
                        $user=$this->OwnerDAO->searchAdminByEmail($userSearch);
                    }elseif($role===1 && $userSearch != SessionHelper::getCurrentUser()->getEmail()){
                        //Editando a otro usuario admin.
                        $editUserRole = intval($this->UserDAO->getRoleByEmail($userSearch));
                        if($editUserRole === 2){
                            $user = $this->OwnerDAO->searchOwnerByEmail($userSearch);
                        }elseif($editUserRole ===3){
                            $user= $this->KeeperDAO->searchKeeperByEmail($userSearch);
                        }else{
                            $user=$this->OwnerDAO->searchAdminByEmail($userSearch);
                        }
                        $flag = 1;
                    }
                }
            }elseif(intval(SessionHelper::getCurrentUser()->getRol()) === 1){//Si ocurrio algo en el update
                $this->editUser();
            }else{//Como ultima instancia, buscamos el usuario en keepers y owner para devolver a la vista.
                $flag=1;
                $role=2;
                $user = $this->OwnerDAO->searchOwnerByEmail($userSearch);
                if(!$user){
                    $role=3;
                    $user= $this->KeeperDAO->searchKeeperByEmail($userSearch);
                }
            }
            
            require_once(VIEWS_PATH."myProfileUser.php");
            //exit();
        }
    }
    //Vista para editar a todos los usuarios (Admin,owner y keeper (Vista admin))
    public function goAdminView($ownerUsers = null,$keeperUsers= null,$adminUsers= null){
        if($ownerUsers === null && $keeperUsers === null && $adminUsers === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            $userRole=SessionHelper::InfoSession([1]);
            require_once(VIEWS_PATH."userListAdminView.php");
            exit();
        }
    }
    public function goRecovery($user=null){
        if($user){
            require_once(VIEWS_PATH."recoveryPassword.php");
        }else{
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
    }
    public function goRecoveryView(){
        require_once(VIEWS_PATH."recoveryView.php");
    }

    public function logOut(){
        SessionHelper::sessionEnd();
        //require_once(VIEWS_PATH."mainLanding.php");
        header("location: " . FRONT_ROOT . VIEWS_PATH . "loginUser.php");
    }

    public function loginUser($email = null,$password = null){
        if($email === null && $password === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }else{
            if($email && $password){
                try{
                    $newOwner = $this->OwnerDAO->searchOwnerToLogin($email,$password);
                        if($newOwner){
                            if($newOwner->getStatus()==='1'){
                                $_SESSION['messageCount'] = $this->MessageDAO->getUnreadMessages($newOwner->getUserID());
                                $_SESSION['messageCountGroup'] = $this->MessageDAO->getUnreadMessagesGroup($newOwner->getUserID());
                                $loggedUser = $newOwner;
                                $_SESSION["loggedUser"] = $loggedUser;
                                echo '<div class="alert alert-success">Login successful!</div>';
                                $this->goNavBar();
                            }else{
                                echo '<div class="alert alert-danger my-0"> The User is deleted!</div>';
                                $this->gologinUser();
                            }
                        } else if($newKeeper = $this->KeeperDAO->searchKeeperToLogin($email,$password)){
                            if($newKeeper){
                                if($newKeeper->getStatus()==='1'){
                                    $loggedUser = $newKeeper;
                                    $_SESSION['messageCount']  = $this->MessageDAO->getUnreadMessages($newKeeper->getUserID());
                                    $_SESSION['messageCountGroup'] = $this->MessageDAO->getUnreadMessagesGroup($newAdmin->getUserID());
                                    $_SESSION["loggedUser"] = $loggedUser;
                                    echo '<div class="alert alert-success my-0">Login successful!</div>';
                                    $this->goNavBar();
                                }else{
                                    echo '<div class="alert alert-danger my-0">The User is deleted!</div>';
                                    $this->gologinUser();
                                } 

                            }else{
                                echo '<div class="alert alert-danger">The user or password is invalid!</div>';
                                $this->gologinUser();
                            }
                    }elseif($newAdmin = $this->OwnerDAO->searchAdminToLogin($email,$password)){
                        if($newAdmin->getStatus()==='1'){
                            $_SESSION['messageCount'] = $this->MessageDAO->getUnreadMessages($newAdmin->getUserID());
                            $_SESSION['messageCountGroup'] = $this->MessageDAO->getUnreadMessagesGroup($newAdmin->getUserID());
                            $_SESSION["loggedUser"] = $newAdmin;
                            echo '<div class="alert alert-success">Login successful!</div>';
                            $this->goNavBar();
                            
                        }else{
                            echo '<div class="alert alert-danger my-0"> The User is deleted!</div>';
                            $this->gologinUser();
                        }
                    }
                    else{
                        echo '<div class="alert alert-danger">The user or password is invalid!</div>';
                        $this->gologinUser();
                    }
                }catch ( Exception $ex) {
                    throw $ex;
                }
            }else if($password){
                echo '<div class="alert alert-danger">The Email is requeried to login!</div>';
                $this->gologinUser();
            }else if($email){
                echo '<div class="alert alert-danger">The Password is requeried to login!</div>';
                $this->gologinUser();
            }else{
                echo '<div class="alert alert-danger">The Email and Password is requeried to login!</div>';
                $this->gologinUser();
            }
        }
    }
    /*------------------------- Updates -------------------------*/
    public function updateFirstName($newFirstName = null,$userEmail = null){
        if($newFirstName === null && $userEmail === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }

        if($newFirstName){
            $response=$this->UserDAO->updateFirstName($newFirstName,$userEmail);
            if($response){
                echo '<div class="alert alert-success">You have successful update your First Name!</div>';
                $this->goEditView($userEmail,$response);
            }else{
                echo '<div class="alert alert-danger">You have successful update your First Name!</div>';
                $this->goEditView($userEmail,$response);
            }
        }else{
            echo '<div class="alert alert-danger">First Name cannot be empty!!</div>';
            $this->goEditView($userEmail,$response);
        }
    }

    public function updateLastName($newName = null,$userEmail = null){
        if($newName === null && $userEmail === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        $response=$this->UserDAO->updateLastName($newName,$userEmail);
        if($response){
            echo '<div class="alert alert-success">You have successful update your Last Name!</div>';
            $this->goEditView($userEmail,$response);
        }else{
            echo '<div class="alert alert-danger">Last Name cannot be empty!!</div>';
            $this->goEditView($user,$response);
        }
    }
    public function UpdateUserCellphone($newCellphone = null,$userEmail = null){
        if($newCellphone === null && $userEmail === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($newCellphone){
            $response=$this->UserDAO->updateCellphone($newCellphone,$userEmail);
            if($response){
                echo '<div class="alert alert-success">You have successful update your Cellphone!</div>';
                $this->goEditView($userEmail,$response);
            }
        }else{
            echo '<div class="alert alert-danger">The Cellphone cannot be empty!!</div>';
            $this->goEditView($userEmail,$response);
        }
    }
    public function UpdateDescription($newDescription = null,$userEmail = null){
        if($newDescription === null && $userEmail === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($newDescription){
            $response=$this->UserDAO->updateDescription($newDescription,$userEmail);
            if($response){
                echo '<div class="alert alert-success">You have successful update your Description!</div>';
                $this->goEditView($userEmail,$response);
            }
        }else{
            echo '<div class="alert alert-danger">Description cannot be empty!!</div>';
            $this->goEditView($userEmail,$response);
        }
    }
    public function updateEmail($newEmail = null,$userEmail = null){
        if($newEmail === null && $userEmail === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        $response=0;
        if($newEmail != $userEmail){
            $exist = $this->UserDAO->searchUserByEmail($newEmail);
            $response = null;
            if(!$exist){
                $response = $this->UserDAO->updateEmail($newEmail,$userEmail);
                if($response){
                    echo '<div class="alert alert-success">You have successful update your email!!!</div>';
                    $this->goEditView($newEmail,$response);
                }else{
                    echo '<div class="alert alert-danger">Opss, something wrong update your email!!!</div>';
                    $this->goEditView($userEmail,$response);
                }
            }else{
                echo '<div class="alert alert-danger">The email already exist!!!</div>';
                $this->goEditView($userEmail,$response);
            }
        }else{
            echo '<div class="alert alert-danger">You already have that email!</div>';
            $this->goEditView($userEmail,$response);
        }
    }
    public function updateRecovery($QuestionRecovery = null,$answerRecovery = null,$userEmail = null){
        if($userEmail === null && $QuestionRecovery === null && $answerRecovery ===null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if(!empty(trim($QuestionRecovery)) && !empty(trim($answerRecovery)) && !empty(trim($userEmail))){
            $resultQuestion = $this->UserDAO->updateQuestionRecovery($QuestionRecovery,$userEmail);
            if($resultQuestion){
                $resultAnswer= $this->UserDAO->updateAnswerRecovery($answerRecovery,$userEmail);
                if($resultAnswer){
                    echo '<div class="alert alert-success">You have successful update your recovery configuration!!!</div>';
                    $this->goEditView($userEmail,$resultAnswer);
                }else{
                    echo '<div class="alert alert-danger">Ops, error while trying to update the answer recovery!!!</div>';
                    $this->goEditView($userEmail,$resultAnswer);
                }
            }else{
                echo '<div class="alert alert-danger">Ops, error while trying to update the Question recovery !!!</div>';
                $this->goEditView($userEmail,$resultQuestion);
            }
        }else{
            echo '<div class="alert alert-danger">Ops, error while trying to update the recovery configuration!!!</div>';
            $this->goEditView($userEmail,$resultQuestion);
        }
    }
    public function updateBirthdate($newBirthdate = null,$userEmail = null){
        if($newBirthdate === null && $userEmail ===null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($newBirthdate){
            $dateNew = date('Y-m-d',strtotime($newBirthdate));
            $response = $this->UserDAO->updateBirthdate($dateNew,$userEmail);
            if($response){
                echo '<div class="alert alert-success">You have successful update your Birthdate!!!</div>';
                $this->goEditView($userEmail,$response);
            }else{
                echo '<div class="alert alert-danger">Ops, something wrong!!!</div>';
            $this->goEditView($userEmail,$response);
            }
        }
    }

    public function UpdateAnimalSize($animalSizeKeeper = null,$userEmail = null,$keeperID= null){
        if($animalSizeKeeper === null && $userEmail === null && $keeperID === null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($animalSizeKeeper){
            $response = $this->KeeperDAO->updateAnimalSizeKeeper($animalSizeKeeper,$keeperID);
            if($response){
                echo '<div class="alert alert-success">You have successful update your Animal Size!</div>';
                $this->goEditView($userEmail,$response);
            }
        }else{
            echo '<div class="alert alert-danger">Animal Size cannot be empty!!</div>';
            $this->goEditView($userEmail,$response);
        }
    }
    public function UpdatePrice($priceKeeper = null,$userEmail = null,$keeperID = null){
        if($priceKeeper === null && $userEmail === null && $keeperID === null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($priceKeeper){
            $response = $this->KeeperDAO->updatePrice($priceKeeper,$keeperID);
            if($response){
                echo '<div class="alert alert-success">You have successful update your Price!</div>';
                $this->goEditView($userEmail,$response);;
            }
        }else{
            echo '<div class="alert alert-danger">Price cannot be empty!!</div>';
            $this->goEditView($userEmail,$response);
        }
    }

    public function UpdateCBU($CBU = null,$userEmail = null,$keeperID = null){
        if($CBU === null && $userEmail === null && $keeperID === null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        $response=0;
        if($CBU){
            $flag = $this->KeeperDAO->searchCBU($CBU);
            if(!$flag){
                $response = $this->KeeperDAO->updateCBU($CBU,$keeperID);
                if($response){
                    echo '<div class="alert alert-success">You have successful update your CBU!</div>';
                    $this->goEditView($userEmail,$response);;
                }else{
                    echo '<div class="alert alert-danger">Error while updating your CBU!</div>';
                    $this->goEditView($userEmail,$response);;
                }
            }else{
                echo '<div class="alert alert-danger">The CBU already exist!</div>';
                $this->goEditView($userEmail,$response);;
            }
        }else{
            echo '<div class="alert alert-danger">CBU cannot be empty!!</div>';
            $this->goEditView($userEmail,$response);;
        }
    }

    public function updatePassword($password = null,$password1 = null,$userEmail = null){
        if($password === null && $userEmail === null && $password1 === null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        $response=0;
        if($password == $password1){
            $response=$this->UserDAO->updatePassword($password,$userEmail);
            if($response){
                echo '<div class="alert alert-success">You have successful update your Password!</div>';
                $this->goEditView($userEmail,$response);;
            }
        }else{
            echo '<div class="alert alert-danger">The Password doesnt match!!</div>';
            $this->goEditView($userEmail,$response);;
        }
    }
    public function deleteUser($userEmail=null,$newStatus=null){
        if($newStatus === null && $userEmail === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($newStatus === '1'){
            $status=0;
        }else{
            $status=1;
        }
        $response=0;
        $response = $this->UserDAO->deleteUser($userEmail,$status);
        if($response){
            if(SessionHelper::getCurrentRole() === 1){
                if($status=== 0 && SessionHelper::getCurrentUser()->getEmail() !=$userEmail){
                    echo '<div class="alert alert-success">You have successful delete this account!</div>';
                    $this->editUser();
                }elseif($status=== 0 && SessionHelper::getCurrentUser()->getEmail() ===$userEmail ){
                    echo '<div class="alert alert-success">You have successful delete your account!</div>';
                    $this->logOut();
                }else{
                    echo '<div class="alert alert-success">You have successful reactive this account!</div>';
                    $this->editUser();
                }
            }else{
                echo '<div class="alert alert-success">You have successful delete your account!</div>';
                $this->logOut();
            }
        }else{
            echo '<div class="alert alert-danger">Error while trying to delete your account!</div>';
            $this->goEditView($userEmail,$response);
        }
    }

    //Flujo de recovery
    public function UpdatePasswordRecovery($email = null,$password = null,$password1 = null){
        if($email === null && $password === null && $password1 === null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($password == $password1){
            $result = $this->UserDAO->updatePassword($password,$email);
            if($result){
                echo '<div class="alert alert-success">You have successfully updated your password</div>';
                $this->gologinUser();
            }else{
                echo '<div class="alert alert-danger">Error while updateing the password, please try again later!</div>';
                $this->gologinUser();
            }
        }else{
            echo '<div class="alert alert-danger">The passwords doesnt match!!</div>';
            //Deberia de mandar a la misma vista anterior para volver a ingresar las 2 password
            //O agregar validación a nivel js en el front
            $this->goRecoveryView();
        }
    }
    public function forgotPassword($email = null,$questionRecovery = null,$answerRecovery = null){
        if($email === null && $questionRecovery === null && $answerRecovery === null ){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        if($email && $questionRecovery && $answerRecovery){
            $newOwner = $this->UserDAO->searchUserToRecovery($email);
                if($newOwner){
                    if($questionRecovery === $newOwner->getQuestionRecovery() && $answerRecovery === $newOwner->getAnswerRecovery()){
                        $this->goRecovery($newOwner);
                    }else{
                        echo '<div class="alert alert-danger">Oops! Something´s wrong, please try again</div>';
                        $this->goRecoveryView();
                    }
                }else{
                    echo '<div class="alert alert-danger">The information provided is not valid!</div>';
                    $this->goRecoveryView();
                }
        }else{
            echo '<div class="alert alert-danger">The Email, question and answer is requeried to recovery the password!</div>';
            $this->goRecoveryView();
        }
    }
    //Flujo de recovery

    public function editUser(){
        if(SessionHelper::getCurrentRole() ===2){
            $user = $this->OwnerDAO->searchOwnerByEmail(SessionHelper::getCurrentUser()->getEmail());
            if($user){
                $this->goEditView($user,2);
            }else{
                echo "Agregar que pasa si no se encuentra";
            }
        }
        elseif(SessionHelper::getCurrentRole() ===3){
            $user = $this->KeeperDAO->searchKeeperByEmail(SessionHelper::getCurrentUser()->getEmail());
            if($user){
                $this->goEditView($user,3);
            }else{
                echo "Agregar que pasa si no se encuentra";
            }
        }
        elseif(SessionHelper::getCurrentRole() === 1)
        {//Rol admin
            $ownerUsers = $this->OwnerDAO->GetAllOwner();
            $keeperUsers= $this->KeeperDAO->GetAllKeeper();
            $adminUsers = $this->OwnerDAO->GetAllAdminUser();
           // $user = array_merge($ownerUsers,$keeperUsers);//Uno los 2 arrays
            if($ownerUsers || $keeperUsers || $adminUsers){
                $this->goAdminView($ownerUsers,$keeperUsers,$adminUsers);
            }else{
                echo "Agregar que pasa si no se encuentra";
            }
        }else{
            echo "No esta log o algo raro, evaluar este caso.";
        }
    }   
    public function updateImage($userEmail = null, $userId) {
        if ($userEmail === null) {
            if (SessionHelper::getCurrentUser()) {
                SessionHelper::redirectTo403();
            }
        }
    
        $response = 0;
    
        if (isset($_FILES['newImage']) && $_FILES['newImage']['error'][0] === UPLOAD_ERR_OK) {
            $formatName = function($files, $key) use ($userId) {
                $extension = strtolower(pathinfo($_FILES['newImage']['name'][$key], PATHINFO_EXTENSION));
                return "profile_image_{$userId}." . $extension;
            };
    
            $imageRoute = $this->fileUploader->uploadFiles($_FILES['newImage'], $userId, $formatName);
    
            if ($imageRoute) {
                $response = $this->UserDAO->updateImage($imageRoute[0], $userEmail);
                if ($response) {
                    echo '<div class="alert alert-success">You have successfully updated your image!</div>';
                    $this->goEditView($userEmail, $response);
                } else {
                    echo '<div class="alert alert-danger">Failed to update the image in the database!</div>';
                    $this->goEditView($userEmail, $response);
                }
            } else {
                echo '<div class="alert alert-danger">Error while uploading the image!</div>';
                $this->goEditView($userEmail, $response);
            }
        } else {
            echo '<div class="alert alert-danger">No image uploaded or there was an error!</div>';
            $this->goEditView($userEmail, $response);
        }
    }
}
?>