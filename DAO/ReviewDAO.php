<?php
namespace DAO;

use Models\Review as Review;

class ReviewDAO implements IReviewDAO{

    private $reviewList= array();

    public function AddReview($review){
        $this->RetriveData();
       // $review->setReviewID($this->ReviewID());
        array_push($this->reviewList,$review);
        $this->SaveData();
        return true;
    }

    public function GetAllReview(){
        // bring all of the owners
        $this->RetriveData();
        // return the owner list
        return $this->reviewList;
    }

    private function SaveData(){
        // creamos un arreglo de pets
        $reviewArrayEncode= array();
        // recorremos la lista y guardamos la info del los pets.
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
        //Tenemos que tener el file creado
        if(file_exists('Data/Reviews.json')){
            $reviewFile = file_get_contents(ROOT.'Data/Reviews.json');
            // Si el file tiene datos hace un decode de la info y la guarda en el arreglo, sino devuelve un arreglo vacio.
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



    public function searchReviewByOwner(){
        
    }

    public function searchReviewByKeeper(){

    }
}
?>