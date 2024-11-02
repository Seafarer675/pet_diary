<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$host = 'localhost';
$dbuser = 'root';
$dbpassword = '';
$dbname = 'test';
$link = new mysqli($host, $dbuser, $dbpassword, $dbname);

if ($link->connect_error) {
    echo json_encode(["status" => "error", "message" => "不正確連接資料庫: " . $link->connect_error]);
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 預處理語句防止 SQL 注入
    $stmt = $link->prepare("SELECT * FROM login WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            echo "sign in suceesfully";
            
        } else {
            echo "wrong password";
        }
    } else {
        echo "User does not exist";
    }

    $stmt->close();
}

$link->close();
?>
