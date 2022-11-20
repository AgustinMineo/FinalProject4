<?php
namespace DAO;
use Models\Review as Review;



interface IReviewDAO{
    function AddReview(Review $review);
    function GetAllReview();
}

?>