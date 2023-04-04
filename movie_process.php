<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");







// resgata dados do usuário
$userData = $userDao->verifyToken();

if ($type === "create") {

    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $lenght = filter_input(INPUT_POST, "lenght");

    $movie = new Movie;



    // Validação minima de dados 
    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->lenght = $lenght;
        $movie->users_id = $userData->id;


        // Upload de Imagem do filme

        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES['image'];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];


            // checando tipo da imagem

            if (in_array($image["type"], $imageTypes)) {

                // checa se imagem e jpg

                if (in_array($image["type"], $jpgArray)) {

                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                } else {

                    $imageName = imagecreatefrompng($image["tmp_name"]);
                }
                // Gerando o nome da imagem


                $imageName = $movie->imageGenerateName();

                imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                $movie->image = $imageName;
            } else {

                $message->setMessage("formato de arquivo inválido, insira png ou jpg", "error", "back");
            }
        }


        $movieDao->create($movie);
    } else {

        $message->setMessage("Titulo, Descrição e categoria são obrigatórios", "error", "back");
    }
} elseif ($type === "delete") {

    // Receber os dados do formulário
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);

    if ($movie) {

        // Verificar se o filme é do usuário
        if ($movie->users_id === $userData->id) {

            $movieDao->destroy($movie->id);
        } else {

            $message->setMessage("informações inválidas", "error", "index.php");
        }
    } else {

        $message->setMessage("informações inválidas", "error", "index.php");
    }
} elseif ($type === "update") {


    // Receber os Dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $lenght = filter_input(INPUT_POST, "lenght");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);



    // Verifica se encontrou o filme 
    if ($movieData) {


        // Verificar se o filme é do usuário
        if ($movieData->users_id === $userData->id) {

            // Validação minima de dados 
            if (!empty($title) && !empty($description) && !empty($category)) {

                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->lenght = $lenght;
            } else {

                $message->setMessage("Titulo, Descrição e categoria são obrigatórios", "error", "back");
            }

            // Edição do filme

            $movieData->title = $title;
            $movieData->description = $description;
            $movieData->trailer = $trailer;
            $movieData->category = $category;
            $movieData->lenght = $lenght;
            $movieData->id = $id;


            $movieDao->update($movieData);
        } else {

            $message->setMessage("informações inválidas", "error", "index.php");
        }
    } else {

        $message->setMessage("informações inválidas", "error", "index.php");
    }
} else {

    $message->setMessage(" não entrou nesse if informações inválidas", "error", "index.php");
}
