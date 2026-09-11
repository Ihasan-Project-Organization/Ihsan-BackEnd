import './bootstrap';

// تطبيق مقياس الخط المحفوظ مسبقاً عبر المنصة
try {
    const savedScale = localStorage.getItem('ihsan_font_scale');
    if (savedScale) {
        document.documentElement.style.fontSize = savedScale + '%';
    }
} catch (e) {}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
