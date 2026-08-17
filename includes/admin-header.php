<?php
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
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
    <style>
        :root{
            --forest-950:#06251b;
            --forest-900:#114230;
            --forest-800:#135039;
            --forest-700:#156446;
            --forest-600:#1a7d55;
            --forest-500:#289c6b;
            --forest-400:#4db887;
            --forest-100:#d9f3e3;
            --forest-50:#effaf3;
            --gold-500:#cd9331;
            --gold-400:#dbab45;
            --gold-300:#e4c26b;
            --gold-200:#eeda9f;
            --cream-50:#faf9f4;
            --cream-100:#f3f1e7;
            --ink-900:#0b1f16;
            --ink-700:#26382f;
            --ink-500:#54655c;
            --shadow-lg:0 24px 60px -18px rgba(6,37,27,.22);
            --shadow-md:0 12px 30px -8px rgba(6,37,27,.15);
        }
        
        *{margin:0;padding:0;box-sizing:border-box}
        
        body{
            font-family:'IBM Plex Sans Arabic',sans-serif;
            background:var(--cream-50);
            color:var(--ink-900);
            line-height:1.6;
            min-height:100vh;
        }
        
        a{text-decoration:none;color:inherit}
        ul{list-style:none}
        img{max-width:100%;display:block}
        
        .admin-container{
            display:flex;
            min-height:100vh;
        }
        
        /* ==================== الشريط الجانبي ==================== */
        .sidebar{
            width:280px;
            background:var(--forest-950);
            color:#fff;
            padding:2rem 1.5rem;
            position:fixed;
            top:0;
            right:0;
            bottom:0;
            overflow-y:auto;
            z-index:100;
            transition:transform .3s ease;
        }
        
        .sidebar-logo{
            text-align:center;
            margin-bottom:2rem;
            padding-bottom:1.5rem;
            border-bottom:1px solid rgba(255,255,255,.1);
        }
        
        .sidebar-logo h2{
            font-size:1.2rem;
            font-weight:700;
            color:var(--gold-300);
        }
        
        .sidebar-logo p{
            font-size:.8rem;
            color:rgba(255,255,255,.5);
            margin-top:.3rem;
        }
        
        .sidebar-nav{
            display:flex;
            flex-direction:column;
            gap:.3rem;
        }
        
        .sidebar-nav a{
            display:flex;
            align-items:center;
            gap:.8rem;
            padding:.8rem 1rem;
            border-radius:.8rem;
            color:rgba(255,255,255,.7);
            font-size:.95rem;
            font-weight:500;
            transition:all .3s;
        }
        
        .sidebar-nav a:hover{
            background:rgba(255,255,255,.1);
            color:#fff;
        }
        
        .sidebar-nav a.active{
            background:linear-gradient(270deg,var(--gold-500),var(--gold-300));
            color:var(--forest-950);
            font-weight:700;
        }
        
        .sidebar-nav a svg{
            flex-shrink:0;
        }
        
        .sidebar-nav a .badge{
            background:rgba(220,80,80,.3);
            color:#ffb4b4;
            padding:.2rem .6rem;
            border-radius:99px;
            font-size:.7rem;
            font-weight:700;
            margin-right:auto;
        }
        
        .sidebar-footer{
            margin-top:2rem;
            padding-top:1.5rem;
            border-top:1px solid rgba(255,255,255,.1);
        }
        
        .sidebar-footer a{
            display:flex;
            align-items:center;
            gap:.8rem;
            padding:.8rem 1rem;
            border-radius:.8rem;
            color:rgba(255,255,255,.7);
            font-size:.95rem;
            transition:all .3s;
        }
        
        .sidebar-footer a:hover{
            background:rgba(220,80,80,.2);
            color:#ffb4b4;
        }
        
        /* ==================== المحتوى الرئيسي ==================== */
        .admin-content{
            flex:1;
            margin-right:280px;
            padding:2rem 3rem;
        }
        
        .page-header{
            margin-bottom:2rem;
        }
        
        .page-header h1{
            font-size:1.8rem;
            font-weight:700;
            color:var(--ink-900);
        }
        
        .page-header p{
            color:var(--ink-500);
            margin-top:.3rem;
        }
        
        /* ==================== الإحصائيات ==================== */
        .stats-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:1.5rem;
            margin-bottom:2rem;
        }
        
        .stat-card{
            background:#fff;
            border:1px solid var(--forest-100);
            border-radius:1rem;
            padding:1.5rem;
            display:flex;
            align-items:center;
            gap:1rem;
            box-shadow:0 2px 10px rgba(6,37,27,.04);
            transition:all .3s;
        }
        
        .stat-card:hover{
            transform:translateY(-4px);
            box-shadow:var(--shadow-md);
        }
        
        .stat-icon{
            width:3.5rem;
            height:3.5rem;
            border-radius:1rem;
            display:flex;
            align-items:center;
            justify-content:center;
            flex-shrink:0;
        }
        
        .stat-info{
            display:flex;
            flex-direction:column;
        }
        
        .stat-value{
            font-size:1.5rem;
            font-weight:700;
            color:var(--ink-900);
        }
        
        .stat-label{
            font-size:.85rem;
            color:var(--ink-500);
        }
        
        /* ==================== الإجراءات السريعة ==================== */
        .quick-actions{
            background:#fff;
            border:1px solid var(--forest-100);
            border-radius:1rem;
            padding:1.5rem;
            margin-bottom:2rem;
        }
        
        .quick-actions h2{
            font-size:1.2rem;
            font-weight:700;
            margin-bottom:1rem;
        }
        
        .actions-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
            gap:1rem;
        }
        
        .action-card{
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:.5rem;
            padding:1.5rem;
            background:var(--forest-50);
            border:1px solid var(--forest-100);
            border-radius:1rem;
            transition:all .3s;
        }
        
        .action-card:hover{
            background:var(--forest-100);
            transform:translateY(-4px);
            box-shadow:var(--shadow-md);
        }
        
        .action-card span{
            font-weight:600;
            font-size:.9rem;
            color:var(--forest-800);
        }
        
        /* ==================== الرسائل الأخيرة ==================== */
        .recent-messages{
            background:#fff;
            border:1px solid var(--forest-100);
            border-radius:1rem;
            padding:1.5rem;
        }
        
        .recent-messages h2{
            font-size:1.2rem;
            font-weight:700;
            margin-bottom:1rem;
        }
        
        .messages-list{
            display:flex;
            flex-direction:column;
            gap:.5rem;
        }
        
        .message-item{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:1rem;
            border-radius:.8rem;
            background:var(--cream-50);
            border:1px solid transparent;
            transition:all .3s;
        }
        
        .message-item:hover{
            background:var(--forest-50);
            border-color:var(--forest-100);
        }
        
        .message-item.unread{
            background:rgba(219,171,69,.1);
            border-color:rgba(219,171,69,.3);
        }
        
        .message-info strong{
            display:block;
            font-size:.95rem;
        }
        
        .message-info span{
            font-size:.85rem;
            color:var(--ink-500);
        }
        
        .message-info small{
            display:block;
            font-size:.75rem;
            color:var(--ink-500);
        }
        
        .message-date{
            font-size:.8rem;
            color:var(--ink-500);
            white-space:nowrap;
        }
        
        .btn-view-all{
            display:inline-block;
            margin-top:1rem;
            padding:.7rem 1.5rem;
            background:var(--forest-900);
            color:#fff;
            border-radius:.8rem;
            font-size:.9rem;
            font-weight:600;
            transition:all .3s;
        }
        
        .btn-view-all:hover{
            background:var(--forest-800);
            transform:translateY(-2px);
        }
        
        /* ==================== الجداول ==================== */
        .table-container{
            background:#fff;
            border:1px solid var(--forest-100);
            border-radius:1rem;
            overflow:hidden;
            margin-bottom:2rem;
        }
        
        .table-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:1.5rem;
            border-bottom:1px solid var(--forest-100);
        }
        
        .table-header h2{
            font-size:1.2rem;
            font-weight:700;
        }
        
        .btn-primary{
            display:inline-flex;
            align-items:center;
            gap:.5rem;
            padding:.7rem 1.5rem;
            background:linear-gradient(270deg,var(--gold-500),var(--gold-300));
            color:var(--forest-950);
            border-radius:.8rem;
            font-weight:700;
            font-size:.9rem;
            transition:all .3s;
        }
        
        .btn-primary:hover{
            filter:brightness(1.1);
            transform:translateY(-2px);
        }
        
        table{
            width:100%;
            border-collapse:collapse;
        }
        
        table th{
            background:var(--forest-50);
            padding:1rem;
            text-align:right;
            font-weight:700;
            font-size:.9rem;
            color:var(--forest-800);
            border-bottom:2px solid var(--forest-100);
        }
        
        table td{
            padding:1rem;
            border-bottom:1px solid var(--forest-100);
            font-size:.9rem;
        }
        
        table tr:hover td{
            background:var(--forest-50);
        }
        
        .btn-edit{
            padding:.5rem 1rem;
            background:var(--forest-500);
            color:#fff;
            border-radius:.5rem;
            font-size:.8rem;
            font-weight:600;
            transition:all .3s;
        }
        
        .btn-edit:hover{
            background:var(--forest-600);
        }
        
        .btn-delete{
            padding:.5rem 1rem;
            background:rgba(220,80,80,.9);
            color:#fff;
            border-radius:.5rem;
            font-size:.8rem;
            font-weight:600;
            transition:all .3s;
        }
        
        .btn-delete:hover{
            background:rgba(200,60,60,1);
        }
        
        /* ==================== النماذج ==================== */
        .form-container{
            background:#fff;
            border:1px solid var(--forest-100);
            border-radius:1rem;
            padding:2rem;
            max-width:600px;
        }
        
        .form-group{
            margin-bottom:1.5rem;
        }
        
        .form-group label{
            display:block;
            font-weight:600;
            font-size:.9rem;
            margin-bottom:.5rem;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select{
            width:100%;
            padding:.9rem 1.2rem;
            border-radius:.8rem;
            border:1px solid var(--forest-200);
            font-family:inherit;
            font-size:.95rem;
            transition:all .3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus{
            outline:none;
            border-color:var(--forest-500);
            box-shadow:0 0 0 3px rgba(40,156,107,.1);
        }
        
        .form-group textarea{
            min-height:150px;
            resize:vertical;
        }
        
        .alert{
            padding:1rem;
            border-radius:.8rem;
            margin-bottom:1.5rem;
            font-size:.9rem;
        }
        
        .alert-success{
            background:rgba(40,156,107,.1);
            border:1px solid rgba(40,156,107,.3);
            color:var(--forest-700);
        }
        
        .alert-error{
            background:rgba(220,80,80,.1);
            border:1px solid rgba(220,80,80,.3);
            color:#dc5050;
        }
        
        /* ==================== استجابة ==================== */
        @media(max-width:768px){
            .sidebar{
                transform:translateX(100%);
            }
            
            .sidebar.open{
                transform:translateX(0);
            }
            
            .admin-content{
                margin-right:0;
                padding:1.5rem;
            }
            
            .stats-grid{
                grid-template-columns:1fr;
            }
            
            .actions-grid{
                grid-template-columns:1fr;
            }
            
            .table-container{
                overflow-x:auto;
            }
        }
        
        /* ==================== زر القائمة للجوال ==================== */
        .menu-toggle{
            display:none;
            position:fixed;
            top:1rem;
            right:1rem;
            z-index:200;
            width:3rem;
            height:3rem;
            background:var(--forest-950);
            color:#fff;
            border:none;
            border-radius:.8rem;
            cursor:pointer;
            font-size:1.5rem;
        }
        
        @media(max-width:768px){
            .menu-toggle{
                display:block;
            }
        }
    </style>
</head>
<body>

<button class="menu-toggle" id="menuToggle" aria-label="فتح القائمة">☰</button>