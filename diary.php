<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = 'localhost';
$dbuser = 'root';
$dbpassword = '';
$dbname = 'test';

$link = new mysqli($host, $dbuser, $dbpassword, $dbname);

if ($link->connect_error) {
    die("資料庫連接失敗: " . $link->connect_error);
}

$link->set_charset("utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['diary']) && isset($_POST['date'])) {
        $diary = $_POST['diary'];
        $date = $_POST['date'];
        $username = $_SESSION['username'];

        // 檢查是否已有相同日期的日記條目
        $stmt = $link->prepare("SELECT COUNT(*) as count FROM diary WHERE username = ? AND date = ?");
        $stmt->bind_param("ss", $username, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            echo "Only one new diary can be added per day";
        } else {
            // 插入日記條目
            $stmt = $link->prepare("INSERT INTO diary (username, diary, date) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $diary, $date);

            if ($stmt->execute()) {
                echo "Diary entry saved for date: " . $date . ", " . $diary;
            } else {
                echo "Error saving diary entry: " . $stmt->error;
            }
        }

        $stmt->close();
    } else {
        echo "Missing diary or date parameter.";
    }
} else {
    echo "Invalid request method.";
}

$link->close();
?>


