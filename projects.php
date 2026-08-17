<?php
/**
 * ============================================================
 * لوحة التحكم - إدارة المشاريع
 * ============================================================
 */

require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

// ==================== إضافة مشروع ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_project'])) {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $category = sanitize($_POST['category'] ?? 'residential');
    $location = sanitize($_POST['location'] ?? '');
    $area_size = sanitize($_POST['area_size'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    
    // معالجة الصورة
    $image_path = '';
    
    // التحقق من رفع صورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image']);
        if ($uploadResult['success']) {
            $image_path = $uploadResult['path'];
        } else {
            $error = $uploadResult['message'];
        }
    } 
    // أو استخدام رابط خارجي
    elseif (!empty($_POST['image_url'])) {
        $image_path = sanitize($_POST['image_url']);
    }
    
    if ($title && $image_path && !$error) {
        $stmt = $pdo->prepare("INSERT INTO projects (title, description, category, image_path, location, area_size, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $category, $image_path, $location, $area_size, $sort_order])) {
            $success = 'تم إضافة المشروع بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء الإضافة';
        }
    } elseif (!$error) {
        $error = 'يرجى تعبئة العنوان وإضافة صورة';
    }
}

// ==================== تحديث مشروع ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_project'])) {
    $id = (int)$_POST['project_id'];
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $category = sanitize($_POST['category'] ?? 'residential');
    $location = sanitize($_POST['location'] ?? '');
    $area_size = sanitize($_POST['area_size'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // جلب المشروع الحالي
    $stmt = $pdo->prepare("SELECT image_path FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch();
    $image_path = $current['image_path'] ?? '';
    
    // معالجة الصورة الجديدة إذا تم رفعها
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image']);
        if ($uploadResult['success']) {
            // حذف الصورة القديمة إذا كانت محلية
            if ($image_path && strpos($image_path, 'uploads/') === 0 && file_exists($image_path)) {
                unlink($image_path);
            }
            $image_path = $uploadResult['path'];
        } else {
            $error = $uploadResult['message'];
        }
    } 
    // أو استخدام رابط خارجي جديد
    elseif (!empty($_POST['image_url'])) {
        $image_path = sanitize($_POST['image_url']);
    }
    
    if ($title && $image_path && !$error) {
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, description = ?, category = ?, image_path = ?, location = ?, area_size = ?, sort_order = ?, is_active = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $category, $image_path, $location, $area_size, $sort_order, $is_active, $id])) {
            $success = 'تم تحديث المشروع بنجاح!';
        }
    }
}

// ==================== حذف مشروع ====================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // جلب مسار الصورة لحذفها
    $stmt = $pdo->prepare("SELECT image_path FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    
    if ($project) {
        // حذف الصورة إذا كانت محلية
        if ($project['image_path'] && strpos($project['image_path'], 'uploads/') === 0 && file_exists($project['image_path'])) {
            unlink($project['image_path']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success = 'تم حذف المشروع بنجاح!';
        }
    }
}

// ==================== تبديل حالة المشروع ====================
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE projects SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم تحديث حالة المشروع بنجاح!';
    }
}

// ==================== جلب المشاريع ====================
$projects = $pdo->query("SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC")->fetchAll();

// ==================== جلب مشروع للتعديل ====================
$edit_project = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $edit_project = $stmt->fetch();
}

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>إدارة المشاريع</h1>
            <p>إضافة وتعديل وحذف المشاريع في المعرض</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($edit_project): ?>
        <!-- ==================== نموذج تعديل مشروع ==================== -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>تعديل المشروع: <?php echo htmlspecialchars($edit_project['title']); ?></h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="update_project" value="1">
                    <input type="hidden" name="project_id" value="<?php echo $edit_project['id']; ?>">
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label>عنوان المشروع *</label>
                            <input type="text" name="title" required value="<?php echo htmlspecialchars($edit_project['title']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>الفئة *</label>
                            <select name="category" required>
                                <option value="residential" <?php echo $edit_project['category'] == 'residential' ? 'selected' : ''; ?>>سكني</option>
                                <option value="commercial" <?php echo $edit_project['category'] == 'commercial' ? 'selected' : ''; ?>>تجاري</option>
                                <option value="landscape" <?php echo $edit_project['category'] == 'landscape' ? 'selected' : ''; ?>>مسطحات</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="description" placeholder="وصف المشروع..."><?php echo htmlspecialchars($edit_project['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label>الموقع</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($edit_project['location'] ?? ''); ?>" placeholder="مثال: الرياض">
                        </div>
                        
                        <div class="form-group">
                            <label>المساحة</label>
                            <input type="text" name="area_size" value="<?php echo htmlspecialchars($edit_project['area_size'] ?? ''); ?>" placeholder="مثال: 1,200 م²">
                        </div>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label>الترتيب</label>
                            <input type="number" name="sort_order" value="<?php echo $edit_project['sort_order']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="is_active">
                                <option value="1" <?php echo $edit_project['is_active'] ? 'selected' : ''; ?>>نشط</option>
                                <option value="0" <?php echo !$edit_project['is_active'] ? 'selected' : ''; ?>>غير نشط</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>الصورة الحالية</label>
                        <div style="margin-bottom:.5rem;">
                            <img src="<?php echo htmlspecialchars($edit_project['image_path']); ?>" 
                                 alt="الصورة الحالية" 
                                 style="width:150px;height:150px;object-fit:cover;border-radius:1rem;border:2px solid var(--forest-100);">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>رفع صورة جديدة</label>
                        <input type="file" name="image" accept="image/*">
                        <small style="color:var(--ink-500);display:block;margin-top:.3rem;">اتركه فارغاً للإبقاء على الصورة الحالية</small>
                    </div>
                    
                    <div class="form-group">
                        <label>أو رابط صورة خارجي</label>
                        <input type="url" name="image_url" placeholder="https://..." value="<?php echo strpos($edit_project['image_path'], 'http') === 0 ? htmlspecialchars($edit_project['image_path']) : ''; ?>">
                    </div>
                    
                    <div style="display:flex;gap:1rem;">
                        <button type="submit" class="btn-primary">حفظ التعديلات</button>
                        <a href="projects.php" class="btn-delete" style="display:inline-block;padding:.7rem 1.5rem;">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <!-- ==================== نموذج إضافة مشروع ==================== -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>إضافة مشروع جديد</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="add_project" value="1">
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label>عنوان المشروع *</label>
                            <input type="text" name="title" required placeholder="مثال: فيلا النخيل">
                        </div>
                        
                        <div class="form-group">
                            <label>الفئة *</label>
                            <select name="category" required>
                                <option value="residential">سكني</option>
                                <option value="commercial">تجاري</option>
                                <option value="landscape">مسطحات</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea name="description" placeholder="وصف المشروع..."></textarea>
                    </div>
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label>الموقع</label>
                            <input type="text" name="location" placeholder="مثال: الرياض">
                        </div>
                        
                        <div class="form-group">
                            <label>المساحة</label>
                            <input type="text" name="area_size" placeholder="مثال: 1,200 م²">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label>رفع صورة *</label>
                        <input type="file" name="image" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label>أو رابط صورة خارجي</label>
                        <input type="url" name="image_url" placeholder="https://...">
                        <small style="color:var(--ink-500);display:block;margin-top:.3rem;">يمكنك رفع صورة أو إدخال رابط خارجي</small>
                    </div>
                    
                    <button type="submit" class="btn-primary">إضافة المشروع</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- ==================== قائمة المشاريع ==================== -->
        <div class="table-container">
            <div class="table-header">
                <h2>المشاريع (<?php echo count($projects); ?>)</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>العنوان</th>
                        <th>الفئة</th>
                        <th>الموقع</th>
                        <th>المساحة</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($project['image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($project['title']); ?>" 
                                 style="width:60px;height:60px;object-fit:cover;border-radius:.5rem;"
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 60 60%22%3E%3Crect width=%2260%22 height=%2260%22 fill=%22%23d9f3e3%22/%3E%3Ctext x=%2230%22 y=%2235%22 text-anchor=%22middle%22 fill=%22%23156446%22 font-size=%2212%22%3E%D8%B5%D9%88%D8%B1%D8%A9%3C/text%3E%3C/svg%3E'">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                            <?php if (!empty($project['description'])): ?>
                            <br><small style="color:var(--ink-500);"><?php echo mb_substr(htmlspecialchars($project['description']), 0, 30, 'UTF-8') . '...'; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span style="padding:.3rem .8rem;border-radius:99px;font-size:.75rem;font-weight:700;
                                <?php echo $project['category'] == 'residential' ? 'background:rgba(40,156,107,.15);color:#156446;' : 
                                    ($project['category'] == 'commercial' ? 'background:rgba(219,171,69,.15);color:#b07528;' : 
                                    'background:rgba(83,210,171,.15);color:#1a7d55;'); ?>">
                                <?php echo getCategoryName($project['category']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($project['location'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($project['area_size'] ?? '-'); ?></td>
                        <td><?php echo $project['sort_order']; ?></td>
                        <td>
                            <a href="?toggle=<?php echo $project['id']; ?>" style="color:<?php echo $project['is_active'] ? 'green' : 'red'; ?>;font-weight:700;text-decoration:none;">
                                <?php echo $project['is_active'] ? '✓ نشط' : '✗ غير نشط'; ?>
                            </a>
                        </td>
                        <td>
                            <div style="display:flex;gap:.5rem;">
                                <a href="?edit=<?php echo $project['id']; ?>" class="btn-edit">تعديل</a>
                                <a href="?delete=<?php echo $project['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد من حذف هذا المشروع؟')">حذف</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center;padding:3rem;color:var(--ink-500);">
                            لا توجد مشاريع بعد. أضف أول مشروع من النموذج أعلاه.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>