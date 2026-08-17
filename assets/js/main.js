(function(){
    'use strict';
    document.documentElement.classList.add('js');

    /* ---------- شريط التنقل عند التمرير ---------- */
    var header = document.getElementById('siteHeader');
    var onScroll = function(){
        header.classList.toggle('scrolled', window.scrollY > 24);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ---------- القائمة المتنقلة ---------- */
    var burger = document.getElementById('burgerBtn');
    var menu = document.getElementById('mobileMenu');
    var openIc = document.getElementById('burgerOpen');
    var closeIc = document.getElementById('burgerClose');
    
    var toggleMenu = function(force){
        var isOpen = force !== undefined ? force : !menu.classList.contains('open');
        menu.classList.toggle('open', isOpen);
        burger.setAttribute('aria-expanded', isOpen);
        burger.setAttribute('aria-label', isOpen ? 'إغلاق القائمة' : 'فتح القائمة');
        openIc.style.display = isOpen ? 'none' : 'block';
        closeIc.style.display = isOpen ? 'block' : 'none';
        document.body.style.overflow = isOpen ? 'hidden' : '';
    };
    
    burger.addEventListener('click', function(){
        toggleMenu();
    });
    
    menu.querySelectorAll('a').forEach(function(a){
        a.addEventListener('click', function(){
            toggleMenu(false);
        });
    });

    /* ---------- ظهور العناصر عند التمرير ---------- */
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var ro = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    ro.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -60px 0px'
        });
        revealEls.forEach(function(el){
            ro.observe(el);
        });
    } else {
        revealEls.forEach(function(el){
            el.classList.add('in');
        });
    }

    /* ---------- عدّادات الأرقام (بأرقام عربية) ---------- */
    var counters = document.querySelectorAll('.counter');
    var fmt = function(n){
        return n.toLocaleString('ar-EG');
    };
    
    var animateCounter = function(el){
        var to = parseInt(el.getAttribute('data-to'), 10) || 0;
        var dur = 1800;
        var start = null;
        
        var step = function(ts){
            if (!start) start = ts;
            var p = Math.min((ts - start) / dur, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = fmt(Math.round(to * eased));
            if (p < 1) requestAnimationFrame(step);
        };
        
        requestAnimationFrame(step);
    };
    
    if ('IntersectionObserver' in window) {
        var co = new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    co.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.4
        });
        counters.forEach(function(el){
            co.observe(el);
        });
    } else {
        counters.forEach(function(el){
            el.textContent = fmt(parseInt(el.getAttribute('data-to'), 10) || 0);
        });
    }

    /* ---------- تصفية الأعمال ---------- */
    var tabs = document.querySelectorAll('.tab');
    var cards = document.querySelectorAll('.project-card');
    
    tabs.forEach(function(tab){
        tab.addEventListener('click', function(){
            tabs.forEach(function(t){
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');
            
            var filter = tab.getAttribute('data-filter');
            var idx = 0;
            
            cards.forEach(function(card){
                var show = filter === 'all' || card.getAttribute('data-cat') === filter;
                card.classList.toggle('hide', !show);
                card.classList.remove('pop');
                if (show) {
                    card.style.animationDelay = (idx * 0.06) + 's';
                    void card.offsetWidth; /* إعادة تشغيل الحركة */
                    card.classList.add('pop');
                    idx++;
                }
            });
        });
    });

    /* ---------- الأسئلة الشائعة ---------- */
    var items = document.querySelectorAll('.faq-item');
    
    items.forEach(function(item){
        var btn = item.querySelector('.faq-q');
        var ans = item.querySelector('.faq-a');
        
        btn.addEventListener('click', function(){
            var isOpen = item.classList.contains('open');
            
            /* إغلاق البقية */
            items.forEach(function(other){
                other.classList.remove('open');
                other.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
            });
            
            if (!isOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* ---------- تحسين الصور - Lazy Loading (إصلاح) ---------- */
    // لا نقوم بتعديل src هنا - الصور تستخدم loading="lazy" الأصلي من المتصفح
    // المتصفحات الحديثة تدعم lazy loading تلقائياً بدون JavaScript
    
    // معالجة أخطاء تحميل الصور (اختياري)
    document.querySelectorAll('img').forEach(function(img) {
        img.addEventListener('error', function() {
            // في حالة فشل تحميل الصورة، نعرض صورة بديلة
            if (!img.dataset.fallback) {
                img.dataset.fallback = 'true';
                img.src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"%3E%3Crect width="400" height="400" fill="%2306251b"/%3E%3Ctext x="200" y="200" text-anchor="middle" fill="%23e4c26b" font-size="20"%3E%D8%AC%D8%A7%D8%B1%D9%8A %D8%A7%D9%84%D8%AA%D8%AD%D9%85%D9%8A%D9%84...%3C/text%3E%3C/svg%3E';
            }
        });
    });
})();