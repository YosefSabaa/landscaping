<?php
/**
 * ============================================================
 * الدوال المساعدة - الأرض الطيبة لتنسيق الحدائق
 * ============================================================
 */

// ==================== دوال جلب البيانات ====================
function getAllServices() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC");
    return $stmt->fetchAll();
}

function getAllProjects() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM projects WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC");
    return $stmt->fetchAll();
}

function getAllTestimonials() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order ASC");
    return $stmt->fetchAll();
}

function getAllFAQs() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC");
    return $stmt->fetchAll();
}

function getProjectById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getCategoryName($category) {
    $categories = [
        'residential' => 'سكني',
        'commercial' => 'تجاري',
        'landscape' => 'مسطحات'
    ];
    return $categories[$category] ?? $category;
}

// ==================== دوال حفظ البيانات ====================
function saveMessage($name, $phone, $city, $area) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO messages (name, phone, city, area) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $phone, $city, $area]);
}

// ==================== دوال رفع الصور ====================
function uploadImage($file, $directory = null) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'نوع الملف غير مدعوم. الأنواع المسموحة: JPG, PNG, WebP, GIF'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'حجم الملف أكبر من المسموح (5MB كحد أقصى)'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'حدث خطأ أثناء رفع الملف'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('img_') . '_' . time() . '.' . $extension;
    
    // تحديد المسار الصحيح
    if ($directory === null) {
        // المسار الفعلي على السيرفر - المجلد الرئيسي للموقع
        $fullDirectory = dirname(__DIR__) . '/uploads/gallery/';
        // المسار المخزن في قاعدة البيانات
        $dbPath = 'uploads/gallery/' . $filename;
    } else {
        // إذا تم تمرير مسار مخصص
        $fullDirectory = rtrim($directory, '/') . '/';
        $dbPath = $fullDirectory . $filename;
    }
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!is_dir($fullDirectory)) {
        mkdir($fullDirectory, 0755, true);
    }
    
    $targetPath = $fullDirectory . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $dbPath];
    }
    
    return ['success' => false, 'message' => 'فشل رفع الملف'];
}

// ==================== دوال الأمان ====================
function generatePasswordHash($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// ==================== دوال SEO ====================
function generateMetaTags($title, $description, $keywords) {
    $html = '<title>' . htmlspecialchars($title) . '</title>' . PHP_EOL;
    $html .= '<meta name="description" content="' . htmlspecialchars($description) . '">' . PHP_EOL;
    $html .= '<meta name="keywords" content="' . htmlspecialchars($keywords) . '">' . PHP_EOL;
    return $html;
}

// ==================== دوال مساعدة للوحة التحكم ====================
function getCount($table) {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
        return $stmt->fetch()['count'];
    } catch (PDOException $e) {
        return 0;
    }
}

function getUnreadMessagesCount() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0");
        return $stmt->fetch()['count'];
    } catch (PDOException $e) {
        return 0;
    }
}

function getRecentMessages($limit = 5) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM messages ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

// ==================== دوال تنظيف البيانات ====================
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function isValidPhone($phone) {
    return preg_match('/^[0-9+\-\s]{9,15}$/', $phone);
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}