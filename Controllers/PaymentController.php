<?php
namespace Controllers;
use DAO\MailerDAO as MailerDAO;
        // DAO WITH JSON
        /*
use DAO\BookingDAO as BookingDAO;
use DAO\PetDAO as PetDAO;
use DAO\KeeperDAO as KeeperDAO;*/

        // MODELS
use Models\Booking as Booking;
use Models\Keeper as Keeper;
use Models\Owner as Owner;

        // DAO WITH DATA BASE
use DAODB\PetDAO as PetDAO;
use DAODB\KeeperDAO as KeeperDAO;
use DAODB\BookingDAO as BookingDAO;
use Helper\SessionHelper as SessionHelper;

class PaymentController {
    private $OwnerDAO;
    private $newMailerDAO;
    private $KeeperDAO;
    private $PetDAO;
    private $bookingDAO;


    public function  __construct(){
        $this->newMailerDAO = new MailerDAO();
        $this->KeeperDAO = new KeeperDAO();
        $this->PetDAO = new PetDAO();
        $this->BookingDAO = new BookingDAO();
    }

    public function goLanding(){
        $userRole=SessionHelper::InfoSession([2]);
        require_once(VIEWS_PATH."landingPage.php");
    }


    public function generatePaymentBooking($booking=null,$paymentReceipt=null){
        if($booking ===null && $paymentReceipt === null){
            if(SessionHelper::getCurrentUser()){
                SessionHelper::redirectTo403();
            }
        }
        SessionHelper::validateUserRole([2]);

        $Booking=$this->BookingDAO->searchBookingByID($booking);
        $keeper = $this->KeeperDAO->searchKeeperByID($Booking->getKeeperId()->getKeeperId()); 
        $pet = $this->PetDAO->searchPet($Booking->getPetID()->getPetID());
        $status = $this->newMailerDAO->bookingCupon($keeper->getEmail(),$keeper->getfirstName(),$keeper->getLastName(),$keeper->getCBU(),$Booking->getAmountReservation(),$Booking->getStartDate(),$Booking->getEndDate(),$pet->getPetName());
        $uploadPaymentFileResult = $this->uploadPaymentFile($booking,$paymentReceipt);//Ruta del archivo subido
        
        if($uploadPaymentFileResult['success']){
            $paymentURL = $uploadPaymentFileResult['uploadPaymentFile'];//Paso la ruta
        }
        if($status && $paymentURL){
            $this->BookingDAO->updatePaymentStatus($booking,$paymentURL);//Actualizo la ruta
            echo '<div class="alert alert-success">The payment was successful! Please wait until the keeper accepts your reservation. </div>';
            $this->BookingDAO->updateByID($booking,"4");
            $this->goLanding();
        }else if($status == false){
            echo '<div class="alert alert-danger">Error al enviar el comprobante</div>';
            $this->goLanding();
        }elseif($uploadPaymentFileResult){
            echo '<div class="alert alert-danger">Error al subir el comprobante.. </div>';
            $this->goLanding();
        }
        else{
            echo '<div class="alert alert-danger">The payment could not be made.. </div>';
            $this->goLanding();
        }

    }

    private function uploadPaymentFile($bookingID, $uploadPaymentFile){
        // Definir el directorio de subida
        $uploadDir = PAYMENT_PATH . "Reservation - {$bookingID}/";

        // Crear el directorio si no existe
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Inicializo los resultados
        $result = [
            'success' => true,
            'uploadPaymentFile' => '',
            'message' => ''
        ];

        // Verificar si se ha subido el archivo correctamente
        if (isset($uploadPaymentFile) && $uploadPaymentFile['error'] == 0) {
            // Obtener info del archivo
            $fileTmpPath = $uploadPaymentFile['tmp_name'];
            $fileName = basename($uploadPaymentFile['name']);
            $fileSize = $uploadPaymentFile['size'];
            $fileType = $uploadPaymentFile['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // extensiones permitidas
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];


            if (in_array($fileExtension, $allowedExtensions)) {
                $maxFileSize = 10 * 1024 * 1024; // 10mb maximo
                if ($fileSize <= $maxFileSize) {
                    
                    $destination = $uploadDir . $fileName;
                    if (move_uploaded_file($fileTmpPath, $destination)) {
                        $result['uploadPaymentFile'] = $destination;
                        $result['message'] = "Archivo subido correctamente.";
                    } else {
                        $result['success'] = false;
                        $result['message'] = "Error al mover el archivo a su ubicación final.";
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = "El archivo es demasiado grande. El tamaño máximo permitido es de 10MB.";
                }
            } else {
                $result['success'] = false;
                $result['message'] = "Tipo de archivo no permitido. Solo se permiten archivos PDF, JPG, JPEG, y PNG.";
            }
        } else {
            $result['success'] = false;
            $result['message'] = "Error en la subida del archivo.";
        }

        return $result;
    }
}

?>