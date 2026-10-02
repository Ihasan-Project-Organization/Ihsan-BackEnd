#!/usr/bin/env bash
# ==============================================================================
# سكربت الإطلاق والتشغيل لبيئة الإنتاج — منصة أنيس لرعاية كبار السن
# Anees Production Deployment Script
# ==============================================================================

set -e

echo "🚀 [1/9] بدء تشغيل سكربت الإطلاق لبيئة الإنتاج..."

# 1. تفعيل وضع الصيانة المؤقت لحماية الجلسات والطلبات أثناء التحديث
echo "🔒 [2/9] تفعيل وضع الصيانة..."
php artisan down --render="errors::503" --secret="anees-deploy-bypass" || true

# 2. تثبيت وتحديث حزم PHP الخاصة بالإنتاج فقط
echo "📦 [3/9] تثبيت اعتماديات Composer لبيئة الإنتاج..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 3. تثبيت اعتماديات الواجهة وبناء الأصول
echo "🎨 [4/9] تجميع ملفات الواجهة (CSS & JS) عبر Vite..."
npm ci --prefer-offline --no-audit
npm run build

# 4. تشغيل التهجيرات وقواعد البيانات
echo "🗄️ [5/9] تشغيل أوامر تهجير قاعدة البيانات (Migrations)..."
php artisan migrate --force

# 5. تفريغ وإعادة بناء الكاش لكافة مكونات النظام
echo "⚡ [6/9] تفريغ وإعادة بناء الكاش (Config, Routes, Views, Events)..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. ربط مجلد التخزين العام
echo "🔗 [7/9] التأكد من ربط التخزين العام (Storage Link)..."
php artisan storage:link || true

# 7. إعادة تشغيل مشغلات الطوابير (Queue Workers)
echo "🔄 [8/9] إعادة تشغيل خدمات الطوابير (Queue)..."
php artisan queue:restart || true

# 8. إيقاف وضع الصيانة وإتاحة المنصة للجمهور
echo "✅ [9/9] إيقاف وضع الصيانة وإطلاق منصة أنيس بنجاح!"
php artisan up

echo "=============================================================================="
echo "🎉 تم اكتمال إطلاق منصة أنيس بنجاح على بيئة الإنتاج!"
echo "=============================================================================="
