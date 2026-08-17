</main>

<!-- ======================= التذييل ======================= -->
<footer class="footer">
    <span class="footer-line"></span>
    <div class="footer-glow"></div>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="#home" class="logo" aria-label="الأرض الطيبة لتنسيق الحدائق">
                    <span class="logo-mark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    </span>
                    <span class="logo-name">الأرض الطيبة<span class="logo-sub">لتنسيق الحدائق</span></span>
                </a>
                <p>علامة سعودية رائدة في تصميم وتنفيذ وصيانة المساحات الخضراء منذ 2012. نحوّل المساحات إلى لوحات طبيعية تتنفس الحياة، بشغفٍ يفهم طبيعة بلادنا وذوقٍ يليق بطموحك.</p>
                <div class="socials">
                    <?php if (!empty($settings['twitter_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['twitter_url']); ?>" target="_blank" rel="noopener" aria-label="منصة إكس">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20" aria-hidden="true"><path d="M17.8 3h3.1l-6.8 7.8L22 21h-6.3l-4.9-6.4L5.2 21H2.1l7.3-8.3L2 3h6.4l4.4 5.9L17.8 3Zm-1.1 16.1h1.7L7.4 4.8H5.5l11.2 14.3Z"/></svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['instagram_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" rel="noopener" aria-label="إنستغرام">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><rect x="3.5" y="3.5" width="17" height="17" rx="4.5"/><circle cx="12" cy="12" r="3.8"/><circle cx="17.2" cy="6.8" r=".9" fill="currentColor" stroke-width="0"/></svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['whatsapp_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['whatsapp_url']); ?>" target="_blank" rel="noopener" aria-label="واتساب">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><path d="M4 20l1.4-4.2A8 8 0 1 1 8.3 18.6L4 20Z"/><path d="M9 9.2c.2-.4.5-.4.8-.4h.5c.3 0 .5 0 .7.5l.6 1.4c.1.3 0 .5-.1.7l-.5.6c-.2.2-.2.4-.1.6.4.7 1 1.4 1.8 1.8.2.1.4.1.6-.1l.6-.5c.2-.2.4-.3.7-.1l1.4.7c.3.2.5.3.5.6 0 .9-.7 1.6-1.5 1.7-.7.1-1.7.1-3.1-.6-2.2-1.2-3.6-3.3-3.7-4.9-.1-.8.3-1.4 1-1.9l.5-.6c.1-.2.2-.3 0-.6L9.6 9c-.1-.3-.3-.3-.6-.1v.3Z"/></svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['youtube_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank" rel="noopener" aria-label="يوتيوب">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" aria-hidden="true"><rect x="2.5" y="5.5" width="19" height="13" rx="3.5"/><path d="m10 9.5 5 2.5-5 2.5v-5Z" fill="currentColor"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <nav aria-label="روابط سريعة">
                <h4>روابط سريعة</h4>
                <ul class="footer-links">
                    <li><a href="#home">الرئيسية</a></li>
                    <li><a href="#services">خدماتنا</a></li>
                    <li><a href="#work">أعمالنا</a></li>
                    <li><a href="#faq">الأسئلة الشائعة</a></li>
                </ul>
            </nav>

            <nav aria-label="خدماتنا">
                <h4>خدماتنا</h4>
                <ul class="footer-links">
                    <li><a href="#services">تصميم الحدائق</a></li>
                    <li><a href="#services">التشجير والمسطحات</a></li>
                    <li><a href="#services">أنظمة الري الذكية</a></li>
                    <li><a href="#services">الإضاءة الخارجية</a></li>
                    <li><a href="#services">الصيانة الدورية</a></li>
                </ul>
            </nav>

            <div>
                <h4>تواصل معنا</h4>
                <ul class="foot-contact">
                    <li>
                        <a href="tel:<?php echo $settings['phone_number'] ?? '920012345'; ?>">
                            <span class="fc-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/></svg>
                            </span>
                            <span dir="ltr"><?php echo $settings['phone_number'] ?? '920 012 345'; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:<?php echo $settings['email'] ?? 'info@altayyiba.sa'; ?>">
                            <span class="fc-ic">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 7 8.5 6 8.5-6"/></svg>
                            </span>
                            <?php echo $settings['email'] ?? 'info@altayyiba.sa'; ?>
                        </a>
                    </li>
                    <li>
                        <span class="fc-ic">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18" aria-hidden="true"><path d="M12 21.5s7-6.1 7-11.5a7 7 0 1 0-14 0c0 5.4 7 11.5 7 11.5Z"/><circle cx="12" cy="10" r="2.6"/></svg>
                        </span>
                        <?php echo $settings['address'] ?? 'الرياض — حي الملقا، طريق الملك فهد'; ?>
                    </li>
                </ul>
                <p class="hours">🌿 مواعيد العمل: <?php echo $settings['working_hours'] ?? 'السبت — الخميس، 8 صباحاً حتى 8 مساءً'; ?></p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> — جميع الحقوق محفوظة</p>
            <div class="fb-links">
                <a href="#">سياسة الخصوصية</a>
                <a href="#">الشروط والأحكام</a>
                <span class="fb-love">صُنع بشغفٍ 
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="14" height="14" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg> 
                    للطبيعة
                </span>
            </div>
        </div>

        <!-- ======================= جزء المطور ======================= -->
        <div class="developer-credit">
            <p>
                تصميم وتطوير: 
                <a href="https://wa.me/201007790689" target="_blank" rel="noopener" class="developer-link">
                    <span class="developer-icon">💻</span>
                    <span class="developer-name">Youssef Sabaa</span>
                </a>
            </p>
        </div>
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>