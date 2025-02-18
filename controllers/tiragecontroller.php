<?php 

if (!isset($_SESSION["user"])) {
    return header("Location:?page=home");
}

$challenges = challenges();


require_once("views/includes/entete.php");
require_once("views/includes/navbar.php");

if (isset($_GET["type"])) {
    if (isset($_GET["id"])) {
        $c = challenge($_GET["id"]);
    }
    require_once("views/challenge/add.php");
}else{
    require_once("views/tirage.php");
}
require_once("views/includes/footer.php");



