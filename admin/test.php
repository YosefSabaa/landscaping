<?php
// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>تشخيص المشكلة</h1>";
echo "<hr>";

// 1. اختبار تحميل config.php
echo "<h3>1. اختبار config.php:</h3>";
try {
    require_once '../config.php';
    echo "✓ تم تحميل config.php بنجاح<br>";
} catch (Exception $e) {
    echo "✗ خطأ في config.php: " . $e->getMessage() . "<br>";
    exit;
}

// 2. اختبار الاتصال بقاعدة البيانات
echo "<h3>2. اختبار قاعدة البيانات:</h3>";
if (isset($pdo)) {
    echo "✓ تم الاتصال بقاعدة البيانات<br>";
} else {
    echo "✗ فشل الاتصال بقاعدة البيانات<br>";
}

// 3. اختبار تحميل functions.php
echo "<h3>3. اختبار functions.php:</h3>";
try {
    require_once '../includes/functions.php';
    echo "✓ تم تحميل functions.php بنجاح<br>";
} catch (Exception $e) {
    echo "✗ خطأ في functions.php: " . $e->getMessage() . "<br>";
}

// 4. اختبار الدوال
echo "<h3>4. اختبار الدوال:</h3>";
if (function_exists('getCount')) {
    echo "✓ دالة getCount موجودة<br>";
    try {
        $count = getCount('projects');
        echo "✓ عدد المشاريع: " . $count . "<br>";
    } catch (Exception $e) {
        echo "✗ خطأ في getCount: " . $e->getMessage() . "<br>";
    }
} else {
    echo "✗ دالة getCount غير موجودة<br>";
}

if (function_exists('isLoggedIn')) {
    echo "✓ دالة isLoggedIn موجودة<br>";
} else {
    echo "✗ دالة isLoggedIn غير موجودة<br>";
}

// 5. اختبار الجداول
echo "<h3>5. اختبار الجداول:</h3>";
$tables = ['projects', 'services', 'testimonials', 'faqs', 'messages', 'settings', 'users'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
        $count = $stmt->fetchColumn();
        echo "✓ جدول {$table}: {$count} سجل<br>";
    } catch (Exception $e) {
        echo "✗ جدول {$table}: غير موجود أو خطأ<br>";
    }
}

echo "<hr>";
echo "<h2>التشخيص اكتمل</h2>";
?>