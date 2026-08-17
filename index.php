<?php
/**
 * ============================================================
 * الأرض الطيبة لتنسيق الحدائق - الصفحة الرئيسية
 * ============================================================
 */

require_once 'config.php';
require_once 'includes/functions.php';

// جلب البيانات من قاعدة البيانات
$services = getAllServices();
$projects = getAllProjects();
$testimonials = getAllTestimonials();
$faqs = getAllFAQs();
$settings = getAllSettings();

// معالجة النموذج
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // التحقق من CSRF
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error_message = 'خطأ في التحقق من الأمان. يرجى تحديث الصفحة والمحاولة مرة أخرى.';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $city = sanitize($_POST['city'] ?? '');
        $area = sanitize($_POST['area'] ?? '');
        $honeypot = trim($_POST['website'] ?? '');
        
        if ($honeypot !== '') {
            // تم ملء حقل honeypot - من المحتمل أن يكون سبام
            $error_message = 'حدث خطأ. يرجى المحاولة مرة أخرى.';
        } elseif ($name && $phone) {
            if (saveMessage($name, $phone, $city, $area)) {
                $success_message = 'تم استلام طلبك بنجاح! سنتواصل معك خلال 24 ساعة.';
            } else {
                $error_message = 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.';
            }
        } else {
            $error_message = 'يرجى تعبئة جميع الحقول المطلوبة';
        }
    }
}

include 'includes/header.php';
?>

<!-- ======================= البطل — Hero ======================= -->
<section class="hero" aria-label="مقدمة">
    <img class="hero-bg" 
         src="https://images.pexels.com/photos/13573493/pexels-photo-13573493.jpeg?auto=compress&cs=tinysrgb&w=1920" 
         alt="فيلا عصرية بإضاءة دافئة وسط حديقة خضراء عند الغسق" 
         fetchpriority="high" />
    <div class="hero-overlay"></div>
    <div class="hero-overlay2"></div>
    <div class="hero-orb anim-float-slow" style="top:-6rem;left:-6rem;width:24rem;height:24rem;background:rgba(40,156,107,.25)"></div>
    <div class="hero-orb anim-float" style="bottom:-4rem;right:-4rem;width:28rem;height:28rem;background:rgba(219,171,69,.15)"></div>

    <div class="container">
        <div class="hero-content">
            <span class="hero-badge glass reveal" style="--d:.1s">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><path d="M12 3.5 13.8 9l5.7 1.8-5.7 1.8L12 18.5l-1.8-5.9L4.5 10.8 10.2 9 12 3.5Z"/><path d="M19 15.5l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8.8-2.2Z"/></svg>
                أكثر من 12 عاماً من الإبداع الأخضر
                <span class="dot"></span>
                <span style="color:rgba(255,255,255,.7)">في خدمة عملائنا</span>
            </span>

            <h1 class="reveal" style="--d:.2s">
                نُبدع لك حديقةً
                <span class="block text-grad">تتنفّس الحياة</span>
            </h1>
            <p class="lead reveal" style="--d:.3s">— حيث تلتقي الطبيعةُ بالذوق الرفيع</p>

            <p class="desc reveal" style="--d:.4s">
                الأرض الطيبة لتنسيق الحدائق — نصمّم وننفّذ ونعتني بالمساحات الخضراء السكنية
                والتجارية، من الفكرة الأولى حتى آخر ورقة شجر، بفريق مهندسين وفنّانين
                يعشقون التفاصيل ويحترمون ميزانيتك.
            </p>

            <div class="hero-ctas reveal" style="--d:.5s">
                <a href="#contact" class="btn btn-gold btn-lg">
                    احجز استشارتك المجانية
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><path d="M19 12H5"/><path d="m11 18-6-6 6-6"/></svg>
                </a>
                <a href="#work" class="btn btn-glass btn-lg">استكشف أعمالنا</a>
            </div>

            <ul class="hero-chips reveal" style="--d:.6s">
                <li><span class="chip-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>استشارة مجانية</li>
                <li><span class="chip-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>ضمان يصل إلى 3 سنوات</li>
                <li><span class="chip-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>إشراف هندسي كامل</li>
            </ul>
        </div>
    </div>

    <a class="scroll-hint" href="#services" aria-label="مرر للأسفل">
        <span>اكتشف المزيد</span>
        <span class="scroll-mouse"><span></span></span>
    </a>
</section>

<!-- ======================= الخدمات — Services ======================= -->
<section class="section services" id="services">
    <div class="services-glow"></div>
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow dark">خدماتنا</span>
            <h2>كل ما تحتاجه مساحتك الخضراء، <span class="text-grad">في مكان واحد</span></h2>
            <p>من التصميم إلى الصيانة المستمرة، نقدّم حلولاً متكاملة تبدأ من شغفنا بالتفاصيل وتنتهي برضاك التام.</p>
        </div>

        <div class="cards-grid">
            <?php foreach ($services as $index => $service): ?>
            <article class="service-card reveal" style="--d:<?php echo ($index * 0.07) + 0.05; ?>s">
                <span class="glow"></span>
                <span class="service-num"><?php echo str_pad($service['sort_order'], 2, '0', STR_PAD_LEFT); ?></span>
                <span class="service-ic">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="m16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36 6.36-2.12Z"/>
                    </svg>
                </span>
                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================= معرض الصور ======================= -->
<section class="section work" id="work">
    <div class="container">
        <div class="section-head dark reveal">
            <span class="eyebrow light">معرض الصور</span>
            <h2>أعمالنا <span class="text-grad">بالصور</span></h2>
            <p>تصفح معرض الصور الخاص بأعمالنا السابقة</p>
        </div>

        <!-- أزرار الفلترة -->
        <div class="tabs reveal" role="tablist" aria-label="تصفية الصور">
            <button class="tab active" data-filter="all" role="tab" aria-selected="true">
                <span>الكل</span>
            </button>
            <button class="tab" data-filter="residential" role="tab" aria-selected="false">
                <span>سكني</span>
            </button>
            <button class="tab" data-filter="commercial" role="tab" aria-selected="false">
                <span>تجاري</span>
            </button>
            <button class="tab" data-filter="landscape" role="tab" aria-selected="false">
                <span>مسطحات</span>
            </button>
        </div>

        <!-- شبكة الصور - صور فقط بدون معلومات -->
        <div class="projects-grid" id="projectsGrid">
            <?php foreach ($projects as $project): ?>
            <figure class="project-card" data-cat="<?php echo htmlspecialchars($project['category']); ?>">
                <img src="<?php echo htmlspecialchars($project['image_path']); ?>" 
                     alt="صورة من معرض أعمالنا" 
                     loading="lazy"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 500%22%3E%3Crect width=%22400%22 height=%22500%22 fill=%22%2306251b%22/%3E%3Ctext x=%22200%22 y=%22250%22 text-anchor=%22middle%22 fill=%22%23e4c26b%22 font-size=%2218%22%3E%D8%B5%D9%88%D8%B1%D8%A9%3C/text%3E%3C/svg%3E'" />
                <span class="shade"></span>
            </figure>
            <?php endforeach; ?>
            
            <?php if (empty($projects)): ?>
            <div style="grid-column:1/-1;text-align:center;padding:3rem;color:rgba(255,255,255,.5);">
                لا توجد صور في المعرض حالياً
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ======================= لماذا نحن — Why Us ======================= -->
<section class="section benefits" id="why">
    <div class="benefits-glow"></div>
    <div class="container">
        <div class="benefit-grid">
            <!-- الجزء المرئي (الصور) -->
            <div class="benefit-visual">
                <div class="benefit-main reveal">
                    <img src="https://images.pexels.com/photos/26599272/pexels-photo-26599272.jpeg?auto=compress&cs=tinysrgb&w=1100" 
                         alt="ممر حديقة فاخر تحيط به الأشجار المشذبة" 
                         loading="lazy"
                         width="1100"
                         height="1210"
                         style="width:100%;height:100%;object-fit:cover;display:block;" />
                </div>
                <div class="benefit-mini reveal" style="--d:.15s">
                    <img src="https://images.pexels.com/photos/32774607/pexels-photo-32774607.jpeg?auto=compress&cs=tinysrgb&w=700" 
                         alt="نافورة أنيقة وسط مساحات خضراء" 
                         loading="lazy"
                         width="700"
                         height="700"
                         style="width:100%;height:100%;object-fit:cover;display:block;" />
                </div>
                <div class="benefit-badge reveal" style="--d:.25s">
                    <span class="bb-ic anim-sway">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" aria-hidden="true">
                            <path d="M7 20h10"/>
                            <path d="M10 20c5.5-2.5.8-6.4 3-10"/>
                            <path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8Z"/>
                            <path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.3-4.1 1-4.9 2Z"/>
                        </svg>
                    </span>
                    <span><b>+12 عاماً</b><small>من الإبداع الأخضر</small></span>
                </div>
            </div>

            <!-- الجزء النصي -->
            <div>
                <div class="section-head start reveal">
                    <span class="eyebrow dark">لماذا الأرض الطيبة؟</span>
                    <h2>شغفٌ بالطبيعة… <span class="text-grad">واحترافٌ في التنفيذ</span></h2>
                    <p>لسنا مجرد منفّذين؛ نحن شركاء يبنون معك مساحة تحبّ العودة إليها كل يوم. هذا ما يميزنا:</p>
                </div>

                <div class="perks-grid">
                    <div class="perk reveal" style="--d:.05s">
                        <span class="perk-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
                                <path d="M12 2.5 4.5 5.2v6.1c0 4.6 3.2 8.5 7.5 10.2 4.3-1.7 7.5-5.6 7.5-10.2V5.2L12 2.5Z"/>
                                <path d="m8.8 11.8 2.2 2.2 4.2-4.5"/>
                            </svg>
                        </span>
                        <span><b>جودة مضمونة</b><span>مواد ونباتات مختارة بعناية مع ضمان يصل إلى 3 سنوات على أعمالنا.</span></span>
                    </div>
                    <div class="perk reveal" style="--d:.12s">
                        <span class="perk-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
                                <path d="M3 15.5 15.5 3l5.5 5.5L8.5 21 3 15.5Z"/>
                                <path d="m7.5 11 2 2"/>
                                <path d="m10.5 8 2 2"/>
                                <path d="m13.5 5 2 2"/>
                            </svg>
                        </span>
                        <span><b>إشراف هندسي كامل</b><span>مهندسون متخصصون في تنسيق الحدائق يتابعون كل مرحلة حتى أدق التفاصيل.</span></span>
                    </div>
                    <div class="perk reveal" style="--d:.19s">
                        <span class="perk-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
                                <rect x="3.5" y="5" width="17" height="16" rx="2.5"/>
                                <path d="M8 3v4"/>
                                <path d="M16 3v4"/>
                                <path d="M3.5 10.5h17"/>
                                <path d="m10 15.5 1.5 1.5 3-3"/>
                            </svg>
                        </span>
                        <span><b>التزام بالمواعيد</b><span>جدول زمني واضح منذ اليوم الأول، وتسليم في الموعد كما وعدناك.</span></span>
                    </div>
                    <div class="perk reveal" style="--d:.26s">
                        <span class="perk-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="22" height="22" aria-hidden="true">
                                <path d="M7 20h10"/>
                                <path d="M10 20c5.5-2.5.8-6.4 3-10"/>
                                <path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8Z"/>
                                <path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.3-4.1 1-4.9 2Z"/>
                            </svg>
                        </span>
                        <span><b>استدامة حقيقية</b><span>حلول ري ذكية ونباتات محلية توفّر المياه وتزدهر في بيئتنا.</span></span>
                    </div>
                </div>

                <ul class="checklist reveal" style="--d:.25s">
                    <li><span class="ck-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>ترخيص رسمي</li>
                    <li><span class="ck-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>عقود واضحة</li>
                    <li><span class="ck-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>أسعار شفافة</li>
                </ul>
            </div>
        </div>

        <!-- الخطوات -->
        <div class="steps-wrap">
            <div class="section-head reveal">
                <span class="eyebrow dark">رحلة مشروعك</span>
                <h2>أربع خطوات… <span class="text-grad">وتسلم المفاتيح الخضراء</span></h2>
            </div>
            <div class="steps-grid">
                <span class="steps-line"></span>
                <div class="step-card reveal" style="--d:.05s">
                    <span class="step-ic">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36 6.36-2.12Z"/></svg>
                        <span class="step-no">01</span>
                    </span>
                    <h3>الاستشارة</h3>
                    <p>زيارة ميدانية مجانية لفهم رؤيتك ومساحتك وميزانيتك.</p>
                </div>
                <div class="step-card reveal" style="--d:.15s">
                    <span class="step-ic">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><path d="M3 15.5 15.5 3l5.5 5.5L8.5 21 3 15.5Z"/><path d="m7.5 11 2 2"/><path d="m10.5 8 2 2"/><path d="m13.5 5 2 2"/></svg>
                        <span class="step-no">02</span>
                    </span>
                    <h3>التخطيط والتنسيق</h3>
                    <p>اختيار النباتات والعناصر المناسبة وتنسيقها بما يتناسب مع مساحة حديقتك وذوقك.</p>
                </div>
                <div class="step-card reveal" style="--d:.25s">
                    <span class="step-ic">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8Z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.3-4.1 1-4.9 2Z"/></svg>
                        <span class="step-no">03</span>
                    </span>
                    <h3>التنفيذ</h3>
                    <p>فريق متكامل بإشراف هندسي ينفّذ بأعلى معايير الجودة.</p>
                </div>
                <div class="step-card reveal" style="--d:.35s">
                    <span class="step-ic">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="28" height="28" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                        <span class="step-no">04</span>
                    </span>
                    <h3>الصيانة</h3>
                    <p>برنامج صيانة دوري يحفظ حديقتك خضراء طول العام.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================= آراء العملاء — Testimonials ======================= -->
<section class="section testimonials" id="testimonials">
    <div class="orb anim-float-slow" style="top:-4rem;right:20%;width:24rem;height:24rem;background:rgba(40,156,107,.15)"></div>
    <div class="orb" style="bottom:-6rem;left:-4rem;width:20rem;height:20rem;background:rgba(219,171,69,.1)"></div>
    <div class="container">
        <div class="section-head dark reveal">
            <span class="eyebrow light">آراء العملاء</span>
            <h2>قصص نجاح يرويها <span class="text-grad">عملاؤنا</span></h2>
            <p>أكثر من 350 عميلاً وثقوا بنا… وهذه شهادات بعضهم بكل فخر.</p>
        </div>

        <div class="tst-grid">
            <?php foreach ($testimonials as $index => $testimonial): ?>
            <figure class="tst-card reveal" style="--d:<?php echo ($index * 0.07) + 0.05; ?>s">
                <svg class="tst-quote" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36" height="36" aria-hidden="true"><path d="M9.5 5C6 6.6 4 9.4 4 13.2V19h6v-6H6.8c.2-2.3 1.4-3.9 3.7-4.9L9.5 5Zm10 0c-3.5 1.6-5.5 4.4-5.5 8.2V19h6v-6h-3.2c.2-2.3 1.4-3.9 3.7-4.9L19.5 5Z"/></svg>
                <div class="tst-stars" aria-label="تقييم <?php echo $testimonial['rating']; ?> من 5 نجوم">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="<?php echo $i <= $testimonial['rating'] ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="1.5" width="16" height="16" aria-hidden="true"><path d="M12 2.6 14.9 8.5l6.5.9-4.7 4.6 1.1 6.4L12 17.4l-5.8 3 1.1-6.4L2.6 9.4l6.5-.9L12 2.6Z"/></svg>
                    <?php endfor; ?>
                </div>
                <p><?php echo htmlspecialchars($testimonial['content']); ?></p>
                <figcaption class="tst-foot">
                    <span class="tst-avatar" style="background:linear-gradient(135deg,var(--forest-500),var(--forest-800))">
                        <?php echo mb_substr($testimonial['client_name'], 0, 1, 'UTF-8'); ?>
                    </span>
                    <span>
                        <b><?php echo htmlspecialchars($testimonial['client_name']); ?></b>
                        <small><?php echo htmlspecialchars($testimonial['client_position'] ?? ''); ?></small>
                    </span>
                </figcaption>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================= الأسئلة الشائعة — FAQ ======================= -->
<section class="section faq" id="faq">
    <div class="faq-glow"></div>
    <div class="container">
        <div class="faq-grid">
            <div>
                <div class="section-head start reveal">
                    <span class="eyebrow dark">الأسئلة الشائعة</span>
                    <h2>لديك سؤال؟ <span class="text-grad">لدينا الإجابة</span></h2>
                    <p>جمعنا لك أكثر الأسئلة التي تصلنا من عملائنا. وإن لم تجد ما تبحث عنه، فريقنا جاهز للرد عليك في أي وقت.</p>
                </div>
                <a href="#contact" class="btn btn-dark btn-md reveal" style="--d:.15s">تواصل معنا مباشرة</a>
            </div>

            <div>
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item <?php echo $index === 0 ? 'open' : ''; ?> reveal" style="--d:<?php echo ($index * 0.05) + 0.05; ?>s">
                    <button class="faq-q" aria-expanded="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                        <span class="q-label">
                            <span class="faq-no"><?php echo str_pad($faq['sort_order'], 2, '0', STR_PAD_LEFT); ?></span>
                            <span class="q-text"><?php echo htmlspecialchars($faq['question']); ?></span>
                        </span>
                        <span class="faq-chev">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </span>
                    </button>
                    <div class="faq-a">
                        <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ======================= الدعوة للتواصل — CTA ======================= -->
<section class="cta" id="contact">
    <div class="container">
        <div class="cta-panel reveal">
            <img class="cta-bg" src="https://images.pexels.com/photos/32217856/pexels-photo-32217856.jpeg?auto=compress&cs=tinysrgb&w=1920" alt="" loading="lazy" />
            <div class="cta-shade"></div>
            <div class="cta-orb anim-float-slow" style="top:-4rem;left:2.5rem;width:16rem;height:16rem;background:rgba(219,171,69,.2)"></div>
            <div class="cta-orb anim-float" style="bottom:-4rem;right:2.5rem;width:16rem;height:16rem;background:rgba(40,156,107,.25)"></div>

            <div class="cta-inner">
                <div class="cta-copy">
                    <span class="hero-badge glass">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><path d="M12 3.5 13.8 9l5.7 1.8-5.7 1.8L12 18.5l-1.8-5.9L4.5 10.8 10.2 9 12 3.5Z"/><path d="M19 15.5l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8.8-2.2Z"/></svg>
                        استشارة مجانية — بدون أي التزام
                    </span>
                    <h2>حان وقت أن تتحوّل مساحتك إلى <span class="text-grad">لوحة خضراء</span> تحكي ذوقك</h2>
                    <p>اترك بياناتك وسيتواصل معك أحد مهندسينا خلال 24 ساعة لزيارة ميدانية مجانية، وتصميم أولي يناسب مساحتك وميزانيتك.</p>
                    <ul class="cta-points">
                        <li><span class="pt-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 2"/></svg></span>نرد خلال 24 ساعة عمل كحد أقصى</li>
                        <li><span class="pt-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg></span>دعم مباشر عبر الهاتف والواتساب</li>
                        <li><span class="pt-ic"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg></span>عرض سعر تفصيلي واضح بلا مفاجآت</li>
                    </ul>
                </div>

                <div class="cta-form glass">
                    <?php if ($success_message): ?>
                    <div class="form-ok">
                        <span class="ok-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="36" height="36" aria-hidden="true"><path d="m4.5 12.5 5 5 10-11"/></svg>
                        </span>
                        <h3>شكراً لثقتك! 🌿</h3>
                        <p><?php echo $success_message; ?></p>
                    </div>
                    <?php else: ?>
                    <h3>اطلب استشارتك المجانية</h3>
                    <p class="sub">املأ النموذج وسنتواصل معك فوراً</p>

                    <?php if ($error_message): ?>
                    <div class="form-error" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="16" height="16" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4.5"/><path d="M12 16h.01"/></svg>
                        <?php echo $error_message; ?>
                    </div>
                    <?php endif; ?>

                    <form method="post" action="#contact" id="contactForm" novalidate>
                        <input type="hidden" name="submit_contact" value="1">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <!-- حقل حماية من السبام -->
                        <div class="hp-field" aria-hidden="true">
                            <label for="website">لا تملأ هذا الحقل</label>
                            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off" />
                        </div>

                        <div class="form-row">
                            <label for="cta-name">الاسم الكامل *</label>
                            <input class="form-field" type="text" id="cta-name" name="name" required placeholder="مثال: محمد عبدالله" />
                        </div>
                        <div class="form-row">
                            <label for="cta-phone">رقم الجوال *</label>
                            <input class="form-field" type="tel" id="cta-phone" name="phone" required inputmode="tel" placeholder="05X XXX XXXX" />
                        </div>
                        <div class="form-row">
                            <label for="cta-city">المدينة</label>
                            <select class="form-field" id="cta-city" name="city">
                                <option value="">اختر المدينة</option>
                                <option value="الرياض">الرياض</option>
                                <option value="جدة">جدة</option>
                                <option value="الدمام">الدمام / الخبر</option>
                                <option value="أبها">أبها</option>
                                <option value="أخرى">مدينة أخرى</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="cta-area">مساحة الحديقة (اختياري)</label>
                            <select class="form-field" id="cta-area" name="area">
                                <option value="">اختر المساحة</option>
                                <option value="أقل من 500 م²">أقل من 500 م²</option>
                                <option value="500 – 1,500 م²">500 – 1,500 م²</option>
                                <option value="أكثر من 1,500 م²">أكثر من 1,500 م²</option>
                                <option value="مشروع تجاري">مشروع تجاري / مؤسسة</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-gold btn-block" style="margin-top:1.5rem">
                            أرسل طلبي الآن
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><path d="M21 3 10.5 13.5"/><path d="M21 3l-6.8 18-3.7-7.5L3 9.8 21 3Z"/></svg>
                        </button>
                        <p class="form-trust">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="14" height="14" aria-hidden="true"><path d="M19 12H5"/><path d="m11 18-6-6 6-6"/></svg>
                            بياناتك آمنة ولن نشاركها مع أي طرف ثالث
                        </p>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>