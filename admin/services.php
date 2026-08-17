<?php
require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

// إضافة خدمة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $icon = sanitize($_POST['icon'] ?? 'default');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    
    if ($title && $description) {
        $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, sort_order) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $icon, $sort_order])) {
            $success = 'تم إضافة الخدمة بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء الإضافة';
        }
    } else {
        $error = 'يرجى تعبئة جميع الحقول المطلوبة';
    }
}

// تحديث خدمة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
    $id = (int)$_POST['service_id'];
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $icon = sanitize($_POST['icon'] ?? 'default');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($title && $description) {
        $stmt = $pdo->prepare("UPDATE services SET title = ?, description = ?, icon = ?, sort_order = ?, is_active = ? WHERE id = ?");
        if ($stmt->execute([$title, $description, $icon, $sort_order, $is_active, $id])) {
            $success = 'تم تحديث الخدمة بنجاح!';
        }
    }
}

// حذف خدمة
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم حذف الخدمة بنجاح!';
    }
}

// جلب الخدمات
$services = $pdo->query("SELECT * FROM services ORDER BY sort_order ASC")->fetchAll();

// جلب خدمة للتعديل
$edit_service = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$id]);
    $edit_service = $stmt->fetch();
}

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>إدارة الخدمات</h1>
            <p>إضافة وتعديل وحذف الخدمات</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($edit_service): ?>
        <!-- نموذج تعديل خدمة -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>تعديل الخدمة</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="update_service" value="1">
                    <input type="hidden" name="service_id" value="<?php echo $edit_service['id']; ?>">
                    
                    <div class="form-group">
                        <label>عنوان الخدمة *</label>
                        <input type="text" name="title" required value="<?php echo htmlspecialchars($edit_service['title']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>الوصف *</label>
                        <textarea name="description" required><?php echo htmlspecialchars($edit_service['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الأيقونة</label>
                        <select name="icon">
                            <option value="design" <?php echo $edit_service['icon'] == 'design' ? 'selected' : ''; ?>>تصميم</option>
                            <option value="planting" <?php echo $edit_service['icon'] == 'planting' ? 'selected' : ''; ?>>تشجير</option>
                            <option value="irrigation" <?php echo $edit_service['icon'] == 'irrigation' ? 'selected' : ''; ?>>ري</option>
                            <option value="lighting" <?php echo $edit_service['icon'] == 'lighting' ? 'selected' : ''; ?>>إضاءة</option>
                            <option value="seating" <?php echo $edit_service['icon'] == 'seating' ? 'selected' : ''; ?>>جلسات</option>
                            <option value="maintenance" <?php echo $edit_service['icon'] == 'maintenance' ? 'selected' : ''; ?>>صيانة</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" value="<?php echo $edit_service['sort_order']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" <?php echo $edit_service['is_active'] ? 'checked' : ''; ?>>
                            نشط
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-primary">حفظ التعديلات</button>
                    <a href="services.php" class="btn-delete" style="display:inline-block;">إلغاء</a>
                </form>
            </div>
        </div>
        <?php else: ?>
        <!-- نموذج إضافة خدمة -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>إضافة خدمة جديدة</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="add_service" value="1">
                    
                    <div class="form-group">
                        <label>عنوان الخدمة *</label>
                        <input type="text" name="title" required placeholder="مثال: تصميم الحدائق">
                    </div>
                    
                    <div class="form-group">
                        <label>الوصف *</label>
                        <textarea name="description" required placeholder="وصف الخدمة..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الأيقونة</label>
                        <select name="icon">
                            <option value="design">تصميم</option>
                            <option value="planting">تشجير</option>
                            <option value="irrigation">ري</option>
                            <option value="lighting">إضاءة</option>
                            <option value="seating">جلسات</option>
                            <option value="maintenance">صيانة</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" value="0">
                    </div>
                    
                    <button type="submit" class="btn-primary">إضافة الخدمة</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- قائمة الخدمات -->
        <div class="table-container">
            <div class="table-header">
                <h2>الخدمات (<?php echo count($services); ?>)</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>الترتيب</th>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo $service['sort_order']; ?></td>
                        <td><?php echo htmlspecialchars($service['title']); ?></td>
                        <td><?php echo mb_substr(htmlspecialchars($service['description']), 0, 50, 'UTF-8') . '...'; ?></td>
                        <td>
                            <?php if ($service['is_active']): ?>
                            <span style="color:green;font-weight:700;">نشط</span>
                            <?php else: ?>
                            <span style="color:red;font-weight:700;">غير نشط</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $service['id']; ?>" class="btn-edit">تعديل</a>
                            <a href="?delete=<?php echo $service['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>