<?php
require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

// حفظ الإعدادات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings_to_save = [
        'phone_number' => sanitize($_POST['phone_number'] ?? ''),
        'email' => sanitize($_POST['email'] ?? ''),
        'address' => sanitize($_POST['address'] ?? ''),
        'working_hours' => sanitize($_POST['working_hours'] ?? ''),
        'facebook_url' => sanitize($_POST['facebook_url'] ?? ''),
        'twitter_url' => sanitize($_POST['twitter_url'] ?? ''),
        'instagram_url' => sanitize($_POST['instagram_url'] ?? ''),
        'whatsapp_url' => sanitize($_POST['whatsapp_url'] ?? ''),
        'youtube_url' => sanitize($_POST['youtube_url'] ?? ''),
        'onesignal_app_id' => sanitize($_POST['onesignal_app_id'] ?? ''),
        'onesignal_api_key' => sanitize($_POST['onesignal_api_key'] ?? ''),
        'onesignal_rest_api_key' => sanitize($_POST['onesignal_rest_api_key'] ?? ''),
        'meta_title' => sanitize($_POST['meta_title'] ?? ''),
        'meta_description' => sanitize($_POST['meta_description'] ?? ''),
        'meta_keywords' => sanitize($_POST['meta_keywords'] ?? ''),
    ];
    
    $all_saved = true;
    foreach ($settings_to_save as $key => $value) {
        if (!updateSetting($key, $value)) {
            $all_saved = false;
        }
    }
    
    if ($all_saved) {
        $success = 'تم حفظ الإعدادات بنجاح!';
    } else {
        $error = 'حدث خطأ أثناء حفظ الإعدادات';
    }
}

// تغيير كلمة المرور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $user = $stmt->fetch();
    
    if (!password_verify($current_password, $user['password_hash'])) {
        $error = 'كلمة المرور الحالية غير صحيحة';
    } elseif (strlen($new_password) < 8) {
        $error = 'كلمة المرور الجديدة يجب أن تكون 8 أحرف على الأقل';
    } elseif ($new_password !== $confirm_password) {
        $error = 'كلمتا المرور غير متطابقتين';
    } else {
        $new_hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        if ($stmt->execute([$new_hash, $_SESSION['admin_id']])) {
            $success = 'تم تغيير كلمة المرور بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء تغيير كلمة المرور';
        }
    }
}

// جلب الإعدادات الحالية
$settings = getAllSettings();

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>الإعدادات</h1>
            <p>تعديل إعدادات الموقع</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- إعدادات التواصل -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>معلومات التواصل</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="save_settings" value="1">
                    
                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($settings['phone_number'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>العنوان</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>مواعيد العمل</label>
                        <input type="text" name="working_hours" value="<?php echo htmlspecialchars($settings['working_hours'] ?? ''); ?>">
                    </div>
                    
                    <h3 style="margin:1.5rem 0 1rem;">روابط التواصل الاجتماعي</h3>
                    
                    <div class="form-group">
                        <label>فيسبوك</label>
                        <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>تويتر / X</label>
                        <input type="url" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>إنستغرام</label>
                        <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>واتساب</label>
                        <input type="url" name="whatsapp_url" value="<?php echo htmlspecialchars($settings['whatsapp_url'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>يوتيوب</label>
                        <input type="url" name="youtube_url" value="<?php echo htmlspecialchars($settings['youtube_url'] ?? ''); ?>">
                    </div>
                    
                    <h3 style="margin:1.5rem 0 1rem;">إعدادات OneSignal</h3>
                    
                    <div class="form-group">
                        <label>App ID</label>
                        <input type="text" name="onesignal_app_id" value="<?php echo htmlspecialchars($settings['onesignal_app_id'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>API Key</label>
                        <input type="text" name="onesignal_api_key" value="<?php echo htmlspecialchars($settings['onesignal_api_key'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>REST API Key</label>
                        <input type="text" name="onesignal_rest_api_key" value="<?php echo htmlspecialchars($settings['onesignal_rest_api_key'] ?? ''); ?>">
                    </div>
                    
                    <h3 style="margin:1.5rem 0 1rem;">إعدادات SEO</h3>
                    
                    <div class="form-group">
                        <label>عنوان الموقع (Meta Title)</label>
                        <input type="text" name="meta_title" value="<?php echo htmlspecialchars($settings['meta_title'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>وصف الموقع (Meta Description)</label>
                        <textarea name="meta_description"><?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الكلمات المفتاحية (Meta Keywords)</label>
                        <input type="text" name="meta_keywords" value="<?php echo htmlspecialchars($settings['meta_keywords'] ?? ''); ?>">
                    </div>
                    
                    <button type="submit" class="btn-primary">حفظ الإعدادات</button>
                </form>
            </div>
        </div>
        
        <!-- تغيير كلمة المرور -->
        <div class="table-container">
            <div class="table-header">
                <h2>تغيير كلمة المرور</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="change_password" value="1">
                    
                    <div class="form-group">
                        <label>كلمة المرور الحالية</label>
                        <input type="password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>كلمة المرور الجديدة (8 أحرف على الأقل)</label>
                        <input type="password" name="new_password" required minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label>تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="confirm_password" required minlength="8">
                    </div>
                    
                    <button type="submit" class="btn-primary">تغيير كلمة المرور</button>
                </form>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>