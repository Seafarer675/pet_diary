<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// 清除会话数据
session_unset();
session_destroy();
echo "已登出";
// 重定向到登录页面
//header("Location: http://localhost/login.html");
exit();
?>
