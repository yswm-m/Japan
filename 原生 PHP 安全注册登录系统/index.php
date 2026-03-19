<?php
require 'functions.php';
// 强制登录验证
require_login();
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
</head>
<body>
    <h2>欢迎回来，<?= $_SESSION['username'] ?>！</h2>
    <p>您已成功登录系统</p>
    <p><a href="logout.php" onclick="return confirm('确定退出登录？')">退出登录</a></p>
</body>
</html>