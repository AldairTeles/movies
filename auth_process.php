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



// Verificação do tipo de formulãrio

if ($type === "register") {

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // Verificação de dados minimos

    if ($name && $lastname && $email && $password) {

        //   verificar se as senhas batem

        if ($password === $confirmpassword) {

            // Verificar se o email já é cadastrado no sistema

            if ($userDao->findByEmail($email) === false) {

                $user = new User();

                // Criação de token e senha

                $userToken = $user->generateToken();
                $finalPassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;

                $auth = true;

                $userDao->create($user, $auth);
            } else {
                // mensagem de erro email já cadastrado
                $message->setMessage("Email já cadastrado, tente cadastrar outro email", "error", "back");
            }
        } else {
            // mensagem de erro senhas não batem
            $message->setMessage("A confirmação precisa ser exatamente igual a senha", "error", "back");
        }
    } else {
        // msg de erro de dados faltantes
        $message->setMessage("Por favor, preencha todos os campos", "error", "back");
    }
} else if ($type === "login") {

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    if ($email && $password) {
        $user = $userDao->findByEmail($email);

        if ($user !== false) {
            $isPasswordCorrect = password_verify($password, $user->password);

            if ($isPasswordCorrect) {
                $userDao->setTokenToSession($user->token);
            } else {
                $message->setMessage("Senha inválida", "error", "back");
            }
        } else {
            $message->setMessage("Usuário não encontrado", "error", "back");
        }
    } else {
        $message->setMessage("Preencha email e senha", "error", "back");
    }
}
