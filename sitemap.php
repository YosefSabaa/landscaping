<?php
/**
 * ============================================================
 * خريطة الموقع XML - الأرض الطيبة لتنسيق الحدائق
 * ============================================================
 */

require_once 'config.php';
require_once 'includes/functions.php';

// إعدادات الرأس
header('Content-Type: application/xml; charset=utf-8');
header('X-Robots-Tag: noindex, follow');

// جلب المشاريع النشطة
$projects = getAllProjects();

// إنشاء خريطة الموقع
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- الصفحة الرئيسية -->
    <url>
        <loc><?php echo SITE_URL; ?>/</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    
    <!-- الأقسام الرئيسية -->
    <url>
        <loc><?php echo SITE_URL; ?>/#services</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <url>
        <loc><?php echo SITE_URL; ?>/#work</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    
    <url>
        <loc><?php echo SITE_URL; ?>/#testimonials</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo SITE_URL; ?>/#faq</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <url>
        <loc><?php echo SITE_URL; ?>/#contact</loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- صفحات المشاريع (إذا كانت هناك صفحات فردية) -->
    <?php foreach ($projects as $project): ?>
    <url>
        <loc><?php echo SITE_URL; ?>/#work</loc>
        <lastmod><?php echo date('Y-m-d', strtotime($project['created_at'])); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
        <?php if (!empty($project['image_path'])): ?>
        <image:image>
            <image:loc><?php echo SITE_URL . '/' . $project['image_path']; ?></image:loc>
            <image:title><?php echo htmlspecialchars($project['title']); ?></image:title>
        </image:image>
        <?php endif; ?>
    </url>
    <?php endforeach; ?>
    
</urlset>