<?php
// أنشئ ملف باسم generate-password.php في المجلد الرئيسي
$password = '123456789'; // ضع كلمة المرور التي تريدها
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
echo "Password: " . $password . "\n";
echo "Hash Key: " . $hash . "\n";
?>