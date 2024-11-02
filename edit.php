<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = 'localhost';
$dbuser = 'root';
$dbpassword = '';
$dbname = 'test';

// 建立資料庫連接
$link = new mysqli($host, $dbuser, $dbpassword, $dbname);

// 檢查連接
if ($link->connect_error) {
    die("資料庫連接失敗: " . $link->connect_error);
}

// 設定字符集
$link->set_charset("utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['operation'])) {
        $operation = $_POST['operation'];

        // 查詢日記條目和日期根據username
        if ($operation == 'search') {
            if (isset($_POST['search_date'])) {
                $username = $_SESSION['username'];
                $diary_date = $_POST['search_date'];

                $stmt = $link->prepare("SELECT date, diary FROM diary WHERE username = ? AND date = ?");
                $stmt->bind_param("ss", $username, $diary_date);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo $row['diary'];
                    }
                } else {
                    echo $_POST['search_date'];
                    echo "您於" . $diary_date . "尚未建立日記";
                }

                $stmt->close();
            } else {
                echo "Missing username or date parameter.";
            }
        }
        // 更新日記條目
        elseif ($operation == 'save') {
            if ( isset($_POST['new_diary']) && isset($_POST['save']) && isset($_POST['edit_date'])) {
                $username = $_SESSION['username'];
                $new_diary = $_POST['new_diary'];
                $save = $_POST['save'];
                $edit_date = $_POST['edit_date'];

                $stmt = $link->prepare("UPDATE diary SET diary = ? WHERE username = ? AND date = ?");
                $stmt->bind_param("sss", $new_diary, $username, $edit_date);
                if ($stmt->execute()) {
                    echo "Diary entry updated" . "on" . $edit_date . "for user: " . $username ;
                } else {
                    echo "Failed to update diary entry.";
                }

                $stmt->close();
            } else {
                echo "Missing required parameters for update operation.";
            }
        }
        elseif ($operation == 'delete') {
            if (isset($_POST['remove_date'])) {
                $username = $_SESSION['username'];
                $remove_date = $_POST['remove_date'];

                // 刪除日記條目
                $stmt = $link->prepare("DELETE FROM diary WHERE username = ? AND date = ?");
                $stmt->bind_param("ss", $username, $remove_date);
                if ($stmt->execute()) {
                    echo "Diary entry deleted for user: " . $username;
                } else {
                    echo "Failed to delete diary entry.";
                }

                $stmt->close();
            } else {
                echo "Missing operation parameter.";
            }
        }
  
    } 
}else {
    echo "Invalid request method.";
}

// 關閉資料庫連接
$link->close();
?>
