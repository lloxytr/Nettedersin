# Nettedersin (PHP Sürümü)

Bu proje, **Markahost Eco Linux (PHP hosting)** üzerinde doğrudan çalışacak şekilde hazırlanmış online eğitim platformu başlangıç arayüzüdür.

## Neden PHP'ye Geçildi?
Önceki sürüm Next.js tabanlıydı. Siz hosting tarafında PHP istediğiniz için altyapı tamamen PHP'ye taşındı.

## Sayfalar
- `index.php` → Ana sayfa (premium landing + tüm modül özeti)
- `pages/ogrenci.php` → Öğrenci paneli demo
- `pages/ogretmen.php` → Öğretmen paneli demo
- `pages/admin.php` → Admin paneli demo

## Ortak Yapı
- `partials/layout.php` → Ortak HTML iskeleti + navbar
- `partials/data.php` → Öğrenci/öğretmen/admin özellik listeleri
- `partials/components.php` → Kart render yardımcı fonksiyonu
- `public/style.css` → Tasarım sistemi ve responsive stil

## Çalıştırma (Lokal)
PHP kuruluysa:

```bash
php -S 127.0.0.1:8000
```

Ardından:
- `http://127.0.0.1:8000/index.php`

## Hosting'e Yükleme
1. Tüm dosyaları public_html altına yükleyin.
2. `index.php` kökte kalmalı.
3. `/pages` ve `/partials` klasörlerini koruyun.
4. CSS için `/public/style.css` yolunun bozulmadığından emin olun.

## Sonraki Teknik Adımlar (Üretim)
1. MySQL veritabanı + PDO katmanı
2. Giriş sistemi (öğrenci/öğretmen/admin rolleri)
3. Video/PDF içerik yönetimi paneli
4. Test motoru + puanlama
5. Ödeme entegrasyonu (İyzico / PayTR)
