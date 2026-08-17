<?php
require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

// إضافة رأي
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_testimonial'])) {
    $client_name = sanitize($_POST['client_name'] ?? '');
    $client_position = sanitize($_POST['client_position'] ?? '');
    $content = sanitize($_POST['content'] ?? '');
    $rating = (int)($_POST['rating'] ?? 5);
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    
    if ($client_name && $content) {
        $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, client_position, content, rating, sort_order) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$client_name, $client_position, $content, $rating, $sort_order])) {
            $success = 'تم إضافة الرأي بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء الإضافة';
        }
    } else {
        $error = 'يرجى تعبئة جميع الحقول المطلوبة';
    }
}

// حذف رأي
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم حذف الرأي بنجاح!';
    }
}

// تبديل حالة النشاط
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $stmt = $pdo->prepare("UPDATE testimonials SET is_active = NOT is_active WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم تحديث الحالة بنجاح!';
    }
}

// جلب الآراء
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll();

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>إدارة آراء العملاء</h1>
            <p>إضافة وحذف وتعديل آراء العملاء</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- نموذج إضافة رأي -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>إضافة رأي جديد</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="add_testimonial" value="1">
                    
                    <div class="form-group">
                        <label>اسم العميل *</label>
                        <input type="text" name="client_name" required placeholder="مثال: محمد عبدالله">
                    </div>
                    
                    <div class="form-group">
                        <label>الصفة / الوظيفة</label>
                        <input type="text" name="client_position" placeholder="مثال: مالك فيلا — الرياض">
                    </div>
                    
                    <div class="form-group">
                        <label>نص الرأي *</label>
                        <textarea name="content" required placeholder="اكتب رأي العميل هنا..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>التقييم</label>
                        <select name="rating">
                            <option value="5">5 نجوم</option>
                            <option value="4">4 نجوم</option>
                            <option value="3">3 نجوم</option>
                            <option value="2">نجمتان</option>
                            <option value="1">نجمة واحدة</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" value="0">
                    </div>
                    
                    <button type="submit" class="btn-primary">إضافة الرأي</button>
                </form>
            </div>
        </div>
        
        <!-- قائمة الآراء -->
        <div class="table-container">
            <div class="table-header">
                <h2>الآراء (<?php echo count($testimonials); ?>)</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الصفة</th>
                        <th>النص</th>
                        <th>التقييم</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonials as $testimonial): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($testimonial['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($testimonial['client_position'] ?? '-'); ?></td>
                        <td><?php echo mb_substr(htmlspecialchars($testimonial['content']), 0, 50, 'UTF-8') . '...'; ?></td>
                        <td><?php echo str_repeat('⭐', $testimonial['rating']); ?></td>
                        <td>
                            <a href="?toggle=<?php echo $testimonial['id']; ?>" style="color:<?php echo $testimonial['is_active'] ? 'green' : 'red'; ?>;font-weight:700;">
                                <?php echo $testimonial['is_active'] ? 'نشط' : 'غير نشط'; ?>
                            </a>
                        </td>
                        <td>
                            <a href="?delete=<?php echo $testimonial['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>