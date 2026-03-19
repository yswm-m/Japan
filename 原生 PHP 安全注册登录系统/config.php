<?php
// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'native_php_auth');
define('DB_USER', 'root'); // 你的数据库用户名
define('DB_PASS', '');     // 你的数据库密码
define('DB_CHAR', 'utf8mb4');

// 安全会话配置（防会话劫持）
ini_set('session.cookie_httponly', 1); // 禁止JS读取Cookie
ini_set('session.cookie_secure', 0);   // HTTPS环境设为1
ini_set('session.use_strict_mode', 1);

// 连接数据库
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("数据库连接失败：" . $e->getMessage());
}
?>