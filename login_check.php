<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if (!isset($_SESSION['loggedin'])) {
    echo "Not logged in";
    
    exit();
}else{
    echo"Logged in";
}

?>

