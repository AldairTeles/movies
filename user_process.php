<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$userDao = new UserDAO($conn, $BASE_URL);



// Resgata o tipo de formulário

$type = filter_input(INPUT_POST, "type");

// Atualizar o Usuário

if ($type === "update") {

    // resgata dados do usuário
    $userData = $userDao->verifyToken();

    // recebe dados do post
    $name = filter_input(INPUT_POST, "name");
    $lastaname = filter_input(INPUT_POST, "lastaname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    // criar novo objeto de usuário

    $user = new User();

    // preencher os dados do usuário

    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    // upload da imagem

    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        // checagem de tipo de imagem

        if (in_array($image["type"], $imageTypes)) {

            // Checar se é jpeg

            if (in_array($image["type"], $jpgArray)) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else {

                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $user->imageGenerateName();

            imagejpeg($imageFile, "./img/users/" . $imageName, 100);

            $userData->image = $imageName;
        } else {

            $message->setMessage("formato de arquivo inválido, insira png ou jpg", "error", "back");
        }
    }

    $userDao->update($userData);
} else if ($type === "changepassword") {

    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // resgata dados do usuário
    $userData = $userDao->verifyToken();

    $id = $userData->id;

    if ($password === $confirmpassword) {

        $user = new User();
        $finalPassword = $user->generatePassword($password);
        $user->password = $finalPassword;
        $user->id = $id;

        $userDao->changePassword($user);
    } else {

        $message->setMessage("Senhas não conferem", "error", "index.php");
    }
} else {
    $message->setMessage("informações inválidas", "error", "index.php");
}
