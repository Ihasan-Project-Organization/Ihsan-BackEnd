# ==============================================================================
# سكربت الإطلاق والتشغيل لبيئة الإنتاج (Windows PowerShell) — منصة أنيس
# Anees Production Deployment Script (PowerShell)
# ==============================================================================

$ErrorActionPreference = "Stop"

Write-Host "🚀 [1/9] بدء تشغيل سكربت الإطلاق لمنصة أنيس..." -ForegroundColor Cyan

# 1. تفعيل وضع الصيانة
Write-Host "🔒 [2/9] تفعيل وضع الصيانة المؤقت..." -ForegroundColor Yellow
try { php artisan down --render="errors::503" --secret="anees-deploy-bypass" } catch { Write-Warning "Could not activate maintenance mode" }

# 2. تثبيت اعتماديات Composer للإنتاج
Write-Host "📦 [3/9] تحديث حزم Composer (no-dev)..." -ForegroundColor Yellow
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 3. بناء الأصول
Write-Host "🎨 [4/9] تجميع ملفات الواجهة عبر Vite..." -ForegroundColor Yellow
npm ci --prefer-offline --no-audit
npm run build

# 4. التهجيرات
Write-Host "🗄️ [5/9] تشغيل أوامر التهجير (php artisan migrate --force)..." -ForegroundColor Yellow
php artisan migrate --force

# 5. التخزين المؤقت
Write-Host "⚡ [6/9] تفريغ وإعادة بناء الكاش..." -ForegroundColor Yellow
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. ربط التخزين
Write-Host "🔗 [7/9] ربط مجلد التخزين..." -ForegroundColor Yellow
try { php artisan storage:link } catch { Write-Warning "Storage already linked" }

# 7. إعادة تشغيل الطوابير
Write-Host "🔄 [8/9] إعادة تشغيل خدمات الطوابير..." -ForegroundColor Yellow
try { php artisan queue:restart } catch { Write-Warning "Queue restart failed" }

# 8. إيقاف الصيانة
Write-Host "✅ [9/9] إيقاف وضع الصيانة وإتاحة المنصة..." -ForegroundColor Green
php artisan up

Write-Host "==============================================================================" -ForegroundColor Green
Write-Host "🎉 تم اكتمال إطلاق منصة أنيس بنجاح!" -ForegroundColor Green
Write-Host "==============================================================================" -ForegroundColor Green
