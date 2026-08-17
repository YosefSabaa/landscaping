<?php
require_once '../config.php';
require_once '../includes/functions.php';
requireLogin();

$success = '';

// تحديد رسالة كمقروءة
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم تحديد الرسالة كمقروءة';
    }
}

// تحديد جميع الرسائل كمقروءة
if (isset($_GET['read_all'])) {
    $stmt = $pdo->query("UPDATE messages SET is_read = 1 WHERE is_read = 0");
    $success = 'تم تحديد جميع الرسائل كمقروءة';
}

// حذف رسالة
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = 'تم حذف الرسالة بنجاح!';
    }
}

// جلب الرسائل
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC")->fetchAll();

include 'includes/admin-header.php';
?>

<div class="admin-container">
    <?php include 'includes/admin-sidebar.php'; ?>
    
    <main class="admin-content">
        <div class="page-header">
            <h1>إدارة الرسائل</h1>
            <p>الرسائل الواردة من نموذج الاتصال</p>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div style="margin-bottom:1rem;">
            <a href="?read_all=1" class="btn-primary">تحديد الكل كمقروء</a>
        </div>
        
        <!-- قائمة الرسائل -->
        <div class="table-container">
            <div class="table-header">
                <h2>الرسائل (<?php echo count($messages); ?>)</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>الحالة</th>
                        <th>الاسم</th>
                        <th>الجوال</th>
                        <th>المدينة</th>
                        <th>المساحة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                    <tr style="<?php echo $msg['is_read'] ? '' : 'background:rgba(219,171,69,.05);'; ?>">
                        <td>
                            <?php if ($msg['is_read']): ?>
                            <span style="color:green;">✓ مقروءة</span>
                            <?php else: ?>
                            <span style="color:#cd9331;font-weight:700;">● جديدة</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td dir="ltr"><?php echo htmlspecialchars($msg['phone']); ?></td>
                        <td><?php echo htmlspecialchars($msg['city'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($msg['area'] ?? '-'); ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></td>
                        <td>
                            <?php if (!$msg['is_read']): ?>
                            <a href="?read=<?php echo $msg['id']; ?>" class="btn-edit">تحديد كمقروء</a>
                            <?php endif; ?>
                            <a href="?delete=<?php echo $msg['id']; ?>" class="btn-delete" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include 'includes/admin-footer.php'; ?>