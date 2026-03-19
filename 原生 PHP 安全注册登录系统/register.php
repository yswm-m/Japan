<?php
require 'functions.php';
$error = '';
$success = '';

// 已登录用户直接跳转到首页
if (is_logged_in()) {
    header("Location: index.php");
    exit();
}

// 处理注册请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 验证CSRF Token
    if (!check_csrf_token($_POST['csrf_token'])) {
        $error = "安全验证失败，请刷新页面重试";
    } 
    // 2. 接收并过滤输入
    $username = safe_input($_POST['username']);
    $email = safe_input($_POST['email']);
    $password = $_POST['password'];

    // 3. 表单验证
    if (empty($username) || empty($email) || empty($password)) {
        $error = "所有字段均为必填项";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $error = "用户名长度需在3-20位之间";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "邮箱格式不正确";
    } elseif (strlen($password) < 6) {
        $error = "密码长度至少6位";
    } 
    // 4. 检查重复用户
    elseif (check_user_exists('username', $username)) {
        $error = "用户名已被注册";
    } elseif (check_user_exists('email', $email)) {
        $error = "邮箱已被注册";
    } 
    // 5. 注册用户
    else {
        try {
            // 密码哈希（PHP原生安全函数，自动加盐）
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);
            
            $success = "注册成功！请登录";
            // 清空CSRF Token
            unset($_SESSION['csrf_token']);
        } catch (PDOException $e) {
            $error = "注册失败，请稍后重试";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>用户注册</title>
</head>
<body>
    <h2>注册</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>
    
    <form method="post">
        <!-- CSRF Token 隐藏域 -->
        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
        
        <p>用户名：<input type="text" name="username" required></p>
        <p>邮箱：<input type="email" name="email" required></p>
        <p>密码：<input type="password" name="password" required></p>
        <p><button type="submit">注册</button></p>
        <p>已有账号？<a href="login.php">立即登录</a></p>
    </form>
</body>
</html>