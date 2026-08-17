<?php
/**
 * ============================================================
 * لوحة التحكم - ملف التوجيه
 * ============================================================
 */

require_once '../config.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;