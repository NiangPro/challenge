<?php 

session_start();
require_once("views/includes/fonctions.php");
require_once("models/database.php");

if (isset($_GET["page"])) {
  switch ($_GET["page"]) {
    case 'login':
      require_once("controllers/logincontroller.php");
      break;
    case 'logout':
      require_once("controllers/logoutcontroller.php");
      break;
    case 'challenge':
      require_once("controllers/challengecontroller.php");
      break;
    case 'cohorte':
      require_once("controllers/cohortecontroller.php");
      break;
    case 'tirage':
      require_once("controllers/tiragecontroller.php");
      break;
    case 'match':
      require_once("controllers/matchcontroller.php");
      break;
    case 'dashboard':
      require_once("controllers/dashboardcontroller.php");
      break;
    default:
        require_once("controllers/homecontroller.php");
      break;
  }
}else{

  require_once("controllers/homecontroller.php");
}



