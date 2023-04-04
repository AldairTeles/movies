<?php

class Review
{
    public $id;
    public $rating;
    public $review;
    public $users_id;
    public $movies_id;
}

interface ReviewDAOinterface
{
    public function buildReview($data);
    public function create(Review $review);
    public function getMoviesReview($id);
    public function hasAlreadyReviewid($id, $userId);
    public function getRatings($id);
}
