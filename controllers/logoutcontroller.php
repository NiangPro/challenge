<?php 


unset($_SESSION["user"]);

return header("Location:?page=home");