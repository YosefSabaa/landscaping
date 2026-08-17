<?php
require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

// إضافة سؤال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_faq'])) {
    $question = sanitize($_POST['question'] ?? '');
    $answer = sanitize($_POST['answer'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    
    if ($question && $answer) {
        $stmt = $pdo->prepare("INSERT INTO faqs (question, answer, sort_order) VALUES (?, ?, ?)");
        if ($stmt->execute([$question, $answer, $sort_order])) {
            $success = 'تم إضافة السؤال بنجاح!';
        } else {
            $error = 'حدث خطأ أثناء الإضافة';
        }
    } else {
        $error = 'يرجى تعبئة السؤال والجواب';
    }
}

// تحديث سؤال
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_faq'])) {
    $id = (int)$_POST['faq_id'];
    $question = sanitize($_POST['question'] ?? '');
    $answer = sanitize($_POST['answer'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($question && $answer) {
        $stmt = $pdo->prepare("UPDATE faqs SET question = ?, answer = ?, sort_order = ?, is_active = ? WHERE id = ?");
        if ($stmt->execute([$question, $answer, $sort_order, $is_active, $id])) {
            $success = 'تم تحديث السؤال بنجاح!';
        }
    }
}

// حذف سؤال
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم حذف السؤال بنجاح!';
    }
}

// جلب الأسئلة
$faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC")->fetchAll();

// جلب سؤال للتعديل
$edit_faq = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->execute([$id]);
    $edit_faq = $stmt->fetch();
}

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>إدارة الأسئلة الشائعة</h1>
            <p>إضافة وتعديل وحذف الأسئلة والأجوبة</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($edit_faq): ?>
        <!-- نموذج تعديل سؤال -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>تعديل السؤال</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="update_faq" value="1">
                    <input type="hidden" name="faq_id" value="<?php echo $edit_faq['id']; ?>">
                    
                    <div class="form-group">
                        <label>السؤال *</label>
                        <input type="text" name="question" required value="<?php echo htmlspecialchars($edit_faq['question']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>الجواب *</label>
                        <textarea name="answer" required><?php echo htmlspecialchars($edit_faq['answer']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" value="<?php echo $edit_faq['sort_order']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" <?php echo $edit_faq['is_active'] ? 'checked' : ''; ?>>
                            نشط
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-primary">حفظ التعديلات</button>
                    <a href="faq.php" class="btn-delete" style="display:inline-block;">إلغاء</a>
                </form>
            </div>
        </div>
        <?php else: ?>
        <!-- نموذج إضافة سؤال -->
        <div class="table-container" style="margin-bottom:2rem;">
            <div class="table-header">
                <h2>إضافة سؤال جديد</h2>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="">
                    <input type="hidden" name="add_faq" value="1">
                    
                    <div class="form-group">
                        <label>السؤال *</label>
                        <input type="text" name="question" required placeholder="مثال: كم تستغرق مدة التنفيذ؟">
                    </div>
                    
                    <div class="form-group">
                        <label>الجواب *</label>
                        <textarea name="answer" required placeholder="اكتب الجواب هنا..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>الترتيب</label>
                        <input type="number" name="sort_order" value="0">
                    </div>
                    
                    <button type="submit" class="btn-primary">إضافة السؤال</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- قائمة الأسئلة -->
        <div class="table-container">
            <div class="table-header">
                <h2>الأسئلة (<?php echo count($faqs); ?>)</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>الترتيب</th>
                        <th>السؤال</th>
                        <th>الجواب</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faqs as $faq): ?>
                    <tr>
                        <td><?php echo $faq['sort_order']; ?></td>
                        <td><?php echo htmlspecialchars($faq['question']); ?></td>
                        <td><?php echo mb_substr(htmlspecialchars($faq['answer']), 0, 50, 'UTF-8') . '...'; ?></td>
                        <td>
                            <?php if ($faq['is_active']): ?>
                            <span style="color:green;font-weight:700;">نشط</span>
                            <?php else: ?>
                            <span style="color:red;font-weight:700;">غير نشط</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $faq['id']; ?>" class="btn-edit">تعديل</a>
                            <a href="?delete=<?php echo $faq['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>