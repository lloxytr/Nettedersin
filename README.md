# Nettedersin (PHP - Markahost Uyumlu, Tam Kapsam İskelet)

Bu proje, Markahost Eco Linux (PHP hosting) için hazırlanmış **premium + hafif dark** görünümlü online eğitim platformu iskeletidir.

## Veritabanı Bilgileri
- DB Name: `n`
- DB User: `ne`
- DB Pass: `Sif`

Bu bilgiler `config/database.php` içinde varsayılan olarak tanımlı.

## Mimari
- `index.php`: Ana landing + premium pazarlama akışı
- `pages/`: Öğrenci / öğretmen / admin dashboard ve alt modüller
- `partials/`: Ortak layout, component renderer, veri listeleri, page shell
- `lib/`: DB bağlantısı (`Database.php`) + veri servisleri (`Repository.php`)
- `setup/`: `schema.sql` ve `seed.sql`
- `public/style.css`: hafif dark premium tasarım sistemi

## Modüller
### Öğrenci
- Dersler (video + PDF + yorum/soru)
- Test/deneme + yanlış defteri
- Motivasyon + hedef + rozet
- Satın alma + paketler

### Öğretmen
- İçerik yönetimi
- Sınav/test yönetimi
- Öğrenci analitiği
- İletişim ve duyuru

### Admin
- Kullanıcı/rol
- Ödeme/paket
- Raporlama
- Güvenlik

## Kurulum
### 1) Lokal sunucu
```bash
php -S 127.0.0.1:8000
```

### 2) Veritabanı kur
MySQL'de sırayla çalıştır:
```sql
SOURCE setup/schema.sql;
SOURCE setup/seed.sql;
```

### 3) Aç
`http://127.0.0.1:8000/index.php`

## Hosting'e Yükleme
1. Tüm dosyaları `public_html` altına yükle.
2. `index.php` kökte kalsın.
3. `pages/`, `partials/`, `lib/`, `setup/`, `public/` klasörlerini koru.
4. Gerekirse `config/database.php` içindeki bilgileri hosting paneline göre güncelle.

## Sonraki Ürünleştirme Adımı
- Gerçek auth/login/register
- CRUD ekranları (ders, test, paket)
- Ödeme webhookları (İyzico/PayTR)
- Bildirim queue sistemi
- SEO sayfaları + sitemap + canonical
