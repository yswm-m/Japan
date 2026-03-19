<?php
session_start();
require 'config.php';

/**
 * 安全过滤用户输入（防XSS）
 * @param string $data 原始输入
 * @return string 过滤后数据
 */
function safe_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * 生成CSRF Token（防跨站请求伪造）
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * 验证CSRF Token
 */
function check_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * 判断用户是否登录
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * 登录校验：未登录自动跳转到登录页
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * 检查用户名/邮箱是否已存在
 */
function check_user_exists($field, $value) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE $field = ?");
    $stmt->execute([$value]);
    return $stmt->rowCount() > 0;
}
?>