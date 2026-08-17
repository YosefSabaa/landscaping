<?php
/**
 * ============================================================
 * لوحة التحكم - تسجيل الدخول
 * ============================================================
 */

require_once '../config.php';

// إذا كان المستخدم مسجل بالفعل، تحويل للوحة التحكم
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// التحقق من محاولات الدخول
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
    if (time() - ($_SESSION['last_attempt_time'] ?? 0) < LOCKOUT_TIME) {
        $error = 'تم قفل الحساب مؤقتاً. حاول مرة أخرى بعد 15 دقيقة.';
    } else {
        $_SESSION['login_attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCSRFToken($csrf_token)) {
        $error = 'خطأ في التحقق من الأمان. يرجى تحديث الصفحة.';
    } elseif (empty($username) || empty($password)) {
        $error = 'يرجى إدخال اسم المستخدم وكلمة المرور';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // تسجيل الدخول ناجح
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['login_attempts'] = 0;
            
            // تحديث آخر دخول
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW(), login_attempts = 0 WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            header('Location: dashboard.php');
            exit;
        } else {
            // تسجيل دخول فاشل
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['last_attempt_time'] = time();
            $error = 'بيانات الدخول غير صحيحة';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{
            font-family:'IBM Plex Sans Arabic',sans-serif;
            background:linear-gradient(135deg,#06251b 0%,#114230 50%,#06251b 100%);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:2rem;
        }
        .login-container{
            width:100%;
            max-width:420px;
        }
        .login-box{
            background:rgba(255,255,255,.05);
            border:1px solid rgba(255,255,255,.1);
            border-radius:1.5rem;
            padding:2.5rem;
            backdrop-filter:blur(20px);
            box-shadow:0 25px 50px -12px rgba(0,0,0,.5);
        }
        .login-logo{
            text-align:center;
            margin-bottom:2rem;
        }
        .login-logo h1{
            color:#fff;
            font-size:1.5rem;
            font-weight:700;
            margin-bottom:.5rem;
        }
        .login-logo p{
            color:rgba(255,255,255,.6);
            font-size:.9rem;
        }
        .form-group{
            margin-bottom:1.5rem;
        }
        .form-group label{
            display:block;
            color:rgba(255,255,255,.8);
            font-size:.9rem;
            font-weight:500;
            margin-bottom:.5rem;
        }
        .form-group input{
            width:100%;
            padding:.9rem 1.2rem;
            border-radius:.8rem;
            border:1px solid rgba(255,255,255,.2);
            background:rgba(255,255,255,.1);
            color:#fff;
            font-family:inherit;
            font-size:1rem;
            transition:all .3s;
        }
        .form-group input:focus{
            outline:none;
            border-color:#e4c26b;
            background:rgba(255,255,255,.15);
        }
        .btn-login{
            width:100%;
            padding:1rem;
            border-radius:.8rem;
            border:none;
            background:linear-gradient(270deg,#cd9331,#e4c26b);
            color:#06251b;
            font-family:inherit;
            font-size:1rem;
            font-weight:700;
            cursor:pointer;
            transition:all .3s;
        }
        .btn-login:hover{
            filter:brightness(1.1);
            transform:translateY(-2px);
        }
        .alert{
            padding:1rem;
            border-radius:.8rem;
            margin-bottom:1.5rem;
            font-size:.9rem;
            text-align:center;
        }
        .alert-error{
            background:rgba(220,80,80,.15);
            border:1px solid rgba(220,80,80,.3);
            color:#ffb4b4;
        }
        .back-link{
            display:block;
            text-align:center;
            margin-top:1.5rem;
            color:rgba(255,255,255,.6);
            font-size:.9rem;
            text-decoration:none;
            transition:color .3s;
        }
        .back-link:hover{
            color:#e4c26b;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <h1>🌿 لوحة التحكم</h1>
                <p>الأرض الطيبة لتنسيق الحدائق</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="username">اسم المستخدم</label>
                    <input type="text" id="username" name="username" required autofocus autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn-login">تسجيل الدخول</button>
            </form>
            
            <a href="../index.php" class="back-link">← العودة للموقع</a>
        </div>
    </div>
</body>
</html>