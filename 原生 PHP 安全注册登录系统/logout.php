<?php
session_start();
// 销毁会话所有数据
session_unset();
session_destroy();
// 跳转到登录页
header("Location: login.php");
exit();
?>