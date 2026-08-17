<script>
(function(){
    'use strict';
    
    /* ---------- القائمة الجانبية للجوال ---------- */
    var menuToggle = document.getElementById('menuToggle');
    var sidebar = document.getElementById('sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function(){
            sidebar.classList.toggle('open');
        });
        
        // إغلاق القائمة عند النقر خارجها
        document.addEventListener('click', function(e){
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target) && 
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });
    }
    
    /* ---------- تأكيد الحذف ---------- */
    document.querySelectorAll('.btn-delete').forEach(function(btn){
        btn.addEventListener('click', function(e){
            if (!confirm('هل أنت متأكد من الحذف؟')) {
                e.preventDefault();
            }
        });
    });
})();
</script>
</body>
</html>