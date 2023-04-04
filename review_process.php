<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("models/Review.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");
require_once("dao/ReviewDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);
$reviewDao = new ReviewDao($conn, $BASE_URL);



// Recebendo o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Resgata os dados do usuário

$userData = $userDao->verifyToken(true);



if ($type === "create") {

    // Recebendo dados do post
    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $users_id = $userData->id;  
    $movies_id = filter_input(INPUT_POST, "movies_id");


    $reviewObject = new Review;

    $movieData = $movieDao->findById($movies_id);



    // Validando se o filme existe

    if ($movieData) {


        // Dados minimos

        if (!empty($rating) && !empty($review) && !empty($movies_id)) {

            $reviewObject->rating = $rating;
            $reviewObject->review = $review;
            $reviewObject->users_id = $users_id;
            $reviewObject->movies_id = $movies_id;


            $reviewDao->create($reviewObject);
        } else {

            $message->setMessage("Preencha todos os campos", "error", "back");
        }
    } else {

        $message->setMessage("Informações inválidas", "error", "index.php");
    }
} else {
    $message->setMessage("Informações inválidas", "error", "index.php");
}
