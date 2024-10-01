<?php
namespace DAO;
use Models\Review as Review;



interface IReviewDAO{
    function AddReview(Review $review);
    function GetAllReviews();
    function getReviewByID($idReview);
    function getReviewByOwner($owner);
    function getReviewByKeeper($keeper);
    function getReviewByBooking($bookingID);
}

?>