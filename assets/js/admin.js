/**
 * لوحة التحكم - JavaScript
 */

(function(){
    'use strict';
    
    /* ---------- تأكيد الحذف ---------- */
    document.querySelectorAll('.btn-delete').forEach(function(btn){
        btn.addEventListener('click', function(e){
            if (!confirm('هل أنت متأكد من الحذف؟')) {
                e.preventDefault();
            }
        });
    });
    
    /* ---------- تبديل النماذج القابلة للطي ---------- */
    window.toggleForm = function(formId, iconId) {
        var formBody = document.getElementById(formId);
        var toggleIcon = document.getElementById(iconId);
        
        if (formBody) {
            formBody.classList.toggle('hidden');
        }
        
        if (toggleIcon) {
            toggleIcon.classList.toggle('collapsed');
        }
    };
    
    /* ---------- إخفاء التنبيهات تلقائياً ---------- */
    setTimeout(function(){
        document.querySelectorAll('.alert').forEach(function(alert){
            alert.style.transition = 'opacity .5s ease';
            alert.style.opacity = '0';
            setTimeout(function(){
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);
    
    /* ---------- معاينة الصورة قبل الرفع ---------- */
    document.querySelectorAll('input[type="file"]').forEach(function(input){
        input.addEventListener('change', function(e){
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = input.parentElement.querySelector('.image-preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });
    
})();