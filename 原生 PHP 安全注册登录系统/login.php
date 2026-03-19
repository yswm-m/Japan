<?php
require 'functions.php';
$error = '';

// 已登录用户直接跳转到首页
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

// 处理登录请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 验证CSRF Token
    if (!check_csrf_token($_POST['csrf_token'])) {
        $error = "安全验证失败，请刷新页面重试";
    } 
    // 2. 过滤输入
    $username = safe_input($_POST['username']);
    $password = $_POST['password'];

    // 3. 验证输入
    if (empty($username) || empty($password)) {
        $error = "用户名和密码均为必填项";
    } 
    // 4. 验证用户信息
    else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // 验证密码（password_verify 匹配哈希值）
            if ($user && password_verify($password, $user['password'])) {
                // 创建登录会话（核心：只存用户ID，不存敏感信息）
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // 登录成功跳转到首页
                header("Location: index.php");
                exit();
            } else {
                $error = "用户名或密码错误";
            }
        } catch (PDOException $e) {
            $error = "登录失败，请稍后重试";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>用户登录</title>
</head>
<body>
    <h2>登录</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="post">
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
        
        <p>用户名：<input type="text" name="username" required></p>
        <p>密码：<input type="password" name="password" required></p>
        <p><button type="submit">登录</button></p>
        <p>没有账号？<a href="register.php">立即注册</a></p>
    </form>
</body>
</html>