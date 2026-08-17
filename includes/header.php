<?php
if (!isset($settings)) {
    $settings = getAllSettings();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- SEO Meta Tags -->
    <title><?php echo htmlspecialchars($settings['meta_title'] ?? SITE_NAME); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($settings['meta_keywords'] ?? ''); ?>" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="<?php echo SITE_NAME; ?>" />
    <link rel="canonical" href="<?php echo SITE_URL; ?>/" />
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($settings['meta_title'] ?? SITE_NAME); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo SITE_URL; ?>/" />
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>" />
    <meta property="og:locale" content="ar_SA" />
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($settings['meta_title'] ?? SITE_NAME); ?>" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($settings['meta_description'] ?? ''); ?>" />
    
    <meta name="theme-color" content="#06251b" />
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ctext y='0.9em' font-size='90'%3E%F0%9F%8C%BF%3C/text%3E%3C/svg%3E" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Amiri:ital,wght@1,400;1,700&display=swap" rel="stylesheet" />
    
    <!-- Main Styles -->
    <link rel="stylesheet" href="assets/css/style.css" />
    
    <!-- OneSignal -->
    <?php if (!empty($settings['onesignal_app_id'])): ?>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "<?php echo htmlspecialchars($settings['onesignal_app_id']); ?>",
                notifyButton: {
                    enable: true,
                },
                allowLocalhostAsSecureOrigin: true,
                promptOptions: {
                    slidedown: {
                        prompts: [
                            {
                                type: "push",
                                autoPrompt: true,
                                text: {
                                    actionMessage: "نود إرسال إشعارات لك عن آخر التحديثات والعروض",
                                    acceptButton: "السماح",
                                    cancelButton: "رفض",
                                },
                                delay: {
                                    pageViews: 1,
                                    timeDelay: 5
                                }
                            }
                        ]
                    }
                }
            });
        });
    </script>
    <?php endif; ?>
</head>
<body>
<a class="skip-link" href="#home" style="position:absolute;left:-9999px;top:auto">تخطَّ إلى المحتوى</a>

<!-- ======================= شريط التنقل ======================= -->
<header class="site-header" id="siteHeader">
    <nav class="container nav-bar" aria-label="القائمة الرئيسية">
        <a href="#home" class="logo" aria-label="<?php echo SITE_NAME; ?> — الرئيسية">
            <span class="logo-mark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
            </span>
            <span class="logo-name">الأرض الطيبة<span class="logo-sub">لتنسيق الحدائق</span></span>
        </a>

        <ul class="nav-links">
            <li><a href="#home">الرئيسية</a></li>
            <li><a href="#services">خدماتنا</a></li>
            <li><a href="#work">أعمالنا</a></li>
            <li><a href="#why">لماذا نحن</a></li>
            <li><a href="#testimonials">آراء العملاء</a></li>
            <li><a href="#faq">الأسئلة الشائعة</a></li>
        </ul>

        <div class="nav-actions">
            <a class="nav-phone" href="tel:<?php echo $settings['phone_number'] ?? '920012345'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>
                <span dir="ltr"><?php echo $settings['phone_number'] ?? '920 012 345'; ?></span>
            </a>
            <a href="#contact" class="btn btn-gold btn-md nav-cta">
                استشارة مجانية
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><path d="M19 12H5"/><path d="m11 18-6-6 6-6"/></svg>
            </a>
            <button class="burger" id="burgerBtn" aria-expanded="false" aria-controls="mobileMenu" aria-label="فتح القائمة">
                <svg id="burgerOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" width="20" height="20" aria-hidden="true"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>
                <svg id="burgerClose" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" width="20" height="20" aria-hidden="true" style="display:none"><path d="M6 6l12 12"/><path d="M18 6 6 18"/></svg>
            </button>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="#home">الرئيسية</a></li>
            <li><a href="#services">خدماتنا</a></li>
            <li><a href="#work">أعمالنا</a></li>
            <li><a href="#why">لماذا نحن</a></li>
            <li><a href="#testimonials">آراء العملاء</a></li>
            <li><a href="#faq">الأسئلة الشائعة</a></li>
            <li><a href="#contact" class="btn btn-gold btn-md btn-block">احجز استشارتك المجانية</a></li>
        </ul>
    </div>
</header>

<main id="home">