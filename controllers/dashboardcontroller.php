<?php 

if (!isset($_SESSION["user"])) {
    return header("Location:?page=home");
}

require_once("views/includes/entete.php");
require_once("views/includes/navbar.php");
require_once("views/dashboard.php");
require_once("views/includes/footer.php");



