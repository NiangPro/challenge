<?php 

if (isset($_POST["login"])) {
    extract($_POST);

    $user = seconnecter($email);
    if ($user && password_verify($mdp, $user->mdp)) {
        $_SESSION["user"] = $user;
        return header("Location:?page=dashboard");
    }else{
        setmessage("Identifiants incorrects", "danger");
    }


}
require_once("views/includes/entete.php");
require_once("views/login.php");



