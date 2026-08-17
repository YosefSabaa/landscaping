<?php
/**
 * ============================================================
 * لوحة التحكم - تسجيل الخروج
 * ============================================================
 */

require_once '../config.php';

// تدمير الجلسة
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// التحويل لصفحة تسجيل الدخول
header('Location: login.php');
exit;