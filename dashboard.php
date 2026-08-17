<?php
/**
 * لوحة التحكم - الرئيسية
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';
require_once '../includes/functions.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// جلب الإحصائيات
$stats = [
    'projects' => getCount('projects'),
    'services' => getCount('services'),
    'testimonials' => getCount('testimonials'),
    'faqs' => getCount('faqs'),
    'messages' => getCount('messages'),
    'unread_messages' => getUnreadMessagesCount(),
];

// جلب آخر الرسائل
$recentMessages = getRecentMessages(5);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>لوحة التحكم - <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<div class="admin-container">
    <!-- الشريط الجانبي -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <h2>🌿 لوحة التحكم</h2>
            <p><?php echo SITE_NAME; ?></p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="active">📊 الرئيسية</a>
            <a href="projects.php">📁 المشاريع</a>
            <a href="services.php">🛠️ الخدمات</a>
            <a href="testimonials.php">👥 آراء العملاء</a>
            <a href="faq.php">❓ الأسئلة الشائعة</a>
            <a href="messages.php">
                💬 الرسائل
                <?php if ($stats['unread_messages'] > 0): ?>
                <span class="badge"><?php echo $stats['unread_messages']; ?></span>
                <?php endif; ?>
            </a>
            <a href="settings.php">⚙️ الإعدادات</a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="../index.php" target="_blank">🔗 عرض الموقع</a>
            <a href="logout.php">🚪 تسجيل الخروج</a>
        </div>
    </aside>
    
    <!-- المحتوى -->
    <main class="admin-content">
        <div class="page-header">
            <h1>لوحة التحكم الرئيسية</h1>
            <p>مرحباً بك، <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></p>
        </div>
        
        <!-- الإحصائيات -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(40,156,107,.2);">🏗️</div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['projects']; ?></span>
                    <span class="stat-label">المشاريع</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(219,171,69,.2);">🛠️</div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['services']; ?></span>
                    <span class="stat-label">الخدمات</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(40,156,107,.2);">👥</div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['testimonials']; ?></span>
                    <span class="stat-label">آراء العملاء</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(219,171,69,.2);">❓</div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['faqs']; ?></span>
                    <span class="stat-label">الأسئلة الشائعة</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(220,80,80,.2);">💬</div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['messages']; ?></span>
                    <span class="stat-label">الرسائل</span>
                </div>
            </div>
        </div>
        
        <!-- إجراءات سريعة -->
        <div class="quick-actions">
            <h2>إجراءات سريعة</h2>
            <div class="actions-grid">
                <a href="projects.php" class="action-card">
                    <span style="font-size:2rem;">📁</span>
                    إدارة المشاريع
                </a>
                <a href="services.php" class="action-card">
                    <span style="font-size:2rem;">🛠️</span>
                    إدارة الخدمات
                </a>
                <a href="faq.php" class="action-card">
                    <span style="font-size:2rem;">❓</span>
                    إدارة الأسئلة
                </a>
                <a href="settings.php" class="action-card">
                    <span style="font-size:2rem;">⚙️</span>
                    الإعدادات
                </a>
            </div>
        </div>
        
        <!-- آخر الرسائل -->
        <?php if (!empty($recentMessages)): ?>
        <div class="recent-messages">
            <h2>آخر الرسائل</h2>
            <div class="messages-list">
                <?php foreach ($recentMessages as $msg): ?>
                <div class="message-item">
                    <div class="message-info">
                        <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                        <span dir="ltr"><?php echo htmlspecialchars($msg['phone']); ?></span>
                    </div>
                    <div class="message-date">
                        <?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <a href="messages.php" class="btn-view-all">عرض جميع الرسائل</a>
        </div>
        <?php endif; ?>
    </main>
</div>

<script src="assets/js/admin.js"></script>
</body>
</html>