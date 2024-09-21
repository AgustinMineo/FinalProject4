<?php
namespace DAO;
/*      Models      */
use Models\Review as Review;
use Models\Owner as Owner;
use Models\Keeper as Keeper;
use Models\Booking as Booking;
/*      Models      */

/*      DAO      */
use DAO\OwnerDAO as OwnerDAO;
use DAO\KeeperDAO as KeeperDAO;

/*      DAO      */

/*      DAODB      */
//use DAODB\OwnerDAO as OwnerDAO;
//use DAODB\KeeperDAO as KeeperDAO;
//use DAODB\BookingDAO as BookingDAO;
/*      DAODB      */

class ReviewDAO implements IReviewDAO{

    private $reviewList= array();

    public function AddReview($review){
        $this->RetriveData();
        array_push($this->reviewList,$review);
        $this->SaveData();
        return true;
    }

    public function GetAllReview(){
     
        $this->RetriveData();
       
        return $this->reviewList;
    }

    private function SaveData(){
    
        $reviewArrayEncode= array();
   
        foreach($this->reviewList as $review){
            $reviewValue = array();
            $reviewValue["reviewID"] = $review->getReviewID();
            $reviewValue["booking"] = $review->getBooking();
            $reviewValue["description"] = $review->getDescription();
            $reviewValue["points"] = $review->getPoints();

            array_push($reviewArrayEncode, $reviewValue);
        }
        $reviewFile = json_encode($reviewArrayEncode, JSON_PRETTY_PRINT);
        file_put_contents('Data/Reviews.json',$reviewFile);
    }

    private function RetriveData(){
        $this->petList = array();
       
        if(file_exists('Data/Reviews.json')){
            $reviewFile = file_get_contents(ROOT.'Data/Reviews.json');
          
            $reviewFileDecode = ($reviewFile) ? json_decode($reviewFile, true) : array();
            
            foreach($reviewFileDecode as $reviewDecode){
                $reviewValue = new Review();
                $reviewValue->setReviewID($reviewDecode["reviewID"]);
                $reviewValue->setBooking($reviewDecode["booking"]);
                $reviewValue->setDescription($reviewDecode["description"]);
                $reviewValue->setPoints($reviewDecode["points"]);
                array_push($this->reviewList, $reviewValue);
            }
        }else{
            echo "<div class='alert alert-danger'>The Review file doesn't exists</div>";
        }

    }



    public function searchReviewByOwner($Owner){
        
    }

    public function searchReviewByKeeper($keeper){

    }
}
?>