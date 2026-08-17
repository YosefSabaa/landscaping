<?php
/**
 * لوحة التحكم - إدارة المعرض
 */

require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

// ==================== إضافة صورة للمعرض ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_project'])) {
    $title = sanitize($_POST['title'] ?? '');
    $category = sanitize($_POST['category'] ?? 'residential');
    
    // رفع الصورة
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image']);
        if ($uploadResult['success']) {
            $image_path = $uploadResult['path'];
        } else {
            $error = $uploadResult['message'];
        }
    } elseif (!empty($_POST['image_url'])) {
        // استخدام رابط خارجي
        $image_path = sanitize($_POST['image_url']);
    }
    
    if ($title && $image_path && !$error) {
        $stmt = $pdo->prepare("INSERT INTO projects (title, category, image_path) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $category, $image_path])) {
            $success = '✅ تم إضافة الصورة بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء الإضافة';
        }
    } elseif (!$error) {
        $error = '⚠️ يرجى تعبئة العنوان وإضافة صورة';
    }
}

// ==================== حذف صورة ====================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // حذف الصورة من المجلد إذا كانت محلية
    $stmt = $pdo->prepare("SELECT image_path FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $image = $stmt->fetch();
    
    if ($image && strpos($image['image_path'], 'uploads/') === 0 && file_exists('../' . $image['image_path'])) {
        unlink('../' . $image['image_path']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = '🗑️ تم حذف الصورة بنجاح!';
    }
}

// ==================== تبديل حالة الصورة ====================
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE projects SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = '✅ تم تحديث الحالة!';
    }
}

// ==================== جلب الصور ====================
$projects = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC")->fetchAll();

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>🖼️ إدارة المعرض</h1>
            <p>إضافة وحذف الصور من المعرض</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- نموذج إضافة صورة -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>➕ إضافة صورة جديدة</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="add_project" value="1">
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label>عنوان الصورة *</label>
                            <input type="text" name="title" required placeholder="مثال: فيلا النخيل" 
                                   style="border:2px solid #4db887 !important;background:#fff !important;box-shadow:0 2px 8px rgba(6,37,27,.1);">
                        </div>
                        
                        <div class="form-group">
                            <label>الفئة *</label>
                            <select name="category" required 
                                    style="border:2px solid #4db887 !important;background:#fff !important;box-shadow:0 2px 8px rgba(6,37,27,.1);">
                                <option value="residential">🏠 سكني</option>
                                <option value="commercial">🏢 تجاري</option>
                                <option value="landscape">🌿 مسطحات</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>رفع صورة *</label>
                            <input type="file" name="image" accept="image/*"
                                   style="border:2px dashed #4db887 !important;background:#f3f1e7 !important;padding:.8rem;">
                        </div>
                        
                        <div class="form-group">
                            <label>أو رابط صورة خارجي</label>
                            <input type="url" name="image_url" placeholder="https://example.com/image.jpg"
                                   style="border:2px solid #4db887 !important;background:#fff !important;box-shadow:0 2px 8px rgba(6,37,27,.1);">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary" style="margin-top:1rem;padding:.9rem 2rem;font-size:1rem;">➕ إضافة الصورة</button>
                </form>
            </div>
        </div>
        
        <!-- عرض الصور كبطاقات -->
        <div class="table-container">
            <div class="table-header">
                <h2>🖼️ الصور (<?php echo count($projects); ?>)</h2>
            </div>
            
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;padding:1.5rem;">
                <?php foreach ($projects as $project): ?>
                <div style="background:#fff;border:1px solid #d9f3e3;border-radius:1rem;overflow:hidden;transition:all .3s;box-shadow:0 2px 10px rgba(6,37,27,.04);">
                    <img src="<?php echo strpos($project['image_path'], 'http') === 0 ? htmlspecialchars($project['image_path']) : '../' . htmlspecialchars($project['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($project['title']); ?>"
                         style="width:100%;height:180px;object-fit:cover;"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23d9f3e3%22/%3E%3Ctext x=%22150%22 y=%22105%22 text-anchor=%22middle%22 fill=%22%23156446%22 font-size=%2214%22%3E%D8%B5%D9%88%D8%B1%D8%A9%3C/text%3E%3C/svg%3E'">
                    
                    <div style="padding:1rem;">
                        <h3 style="font-size:1rem;font-weight:700;margin-bottom:.5rem;"><?php echo htmlspecialchars($project['title']); ?></h3>
                        
                        <span style="display:inline-block;padding:.25rem .7rem;border-radius:99px;font-size:.75rem;font-weight:700;margin-bottom:.5rem;
                            <?php echo $project['category'] == 'residential' ? 'background:rgba(40,156,107,.15);color:#156446;' : 
                                ($project['category'] == 'commercial' ? 'background:rgba(219,171,69,.15);color:#b07528;' : 
                                'background:rgba(83,210,171,.15);color:#1a7d55;'); ?>">
                            <?php echo getCategoryName($project['category']); ?>
                        </span>
                        
                        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-top:.5rem;">
                            <a href="?toggle=<?php echo $project['id']; ?>" 
                               style="padding:.4rem .8rem;border-radius:.5rem;font-size:.8rem;font-weight:700;text-decoration:none;
                                    <?php echo $project['is_active'] ? 'background:rgba(40,156,107,.2);color:#156446;' : 'background:rgba(220,80,80,.2);color:#dc5050;'; ?>">
                                <?php echo $project['is_active'] ? '✓ ظاهرة' : '✗ مخفية'; ?>
                            </a>
                            <a href="?delete=<?php echo $project['id']; ?>" 
                               style="padding:.4rem .8rem;border-radius:.5rem;font-size:.8rem;font-weight:700;text-decoration:none;background:rgba(220,80,80,.9);color:#fff;"
                               onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                🗑️ حذف
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($projects)): ?>
                <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#54655c;">
                    لا توجد صور في المعرض. أضف أول صورة من النموذج أعلاه.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>