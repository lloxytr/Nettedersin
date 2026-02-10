# Nettedersin (PHP - Markahost Uyumlu, Tam Kapsam İskelet)

Bu proje, Markahost Eco Linux (PHP hosting) için hazırlanmış **premium + hafif dark** görünümlü online eğitim platformu iskeletidir.

## Terminal Yoksa Kurulum (Sizdeki Durum)
### Yöntem A — Web Kurulum Sihirbazı (Önerilen)
1. Dosyaları hostinge yükleyin.
2. Tarayıcıda şu sayfayı açın:
   - `https://alanadiniz.com/setup/install.php`
3. DB bilgilerini girin (varsayılanlar formda dolu gelir):
   - DB Name: `nettepfg_db`
   - DB User: `nettepfg_user`
   - DB Pass: `Sifre1234.`
4. "Kurulumu Başlat" butonuna basın.
5. Kurulum sonrası güvenlik için `setup/install.php` dosyasını silin veya erişimi engelleyin.

### Yöntem B — cPanel / phpMyAdmin
1. phpMyAdmin açın.
2. `setup/schema.sql` dosyasını import edin.
3. Sonra `setup/seed.sql` dosyasını import edin.

## Mimari
- `index.php`: Ana landing + premium pazarlama akışı
- `pages/`: Öğrenci / öğretmen / admin dashboard ve alt modüller
- `partials/`: Ortak layout, component renderer, veri listeleri, page shell
- `lib/`: DB bağlantısı (`Database.php`) + veri servisleri (`Repository.php`)
- `setup/`: `schema.sql`, `seed.sql`, `install.php`
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

## Lokal Test (İstersen)
```bash
php -S 127.0.0.1:8000
```
Aç: `http://127.0.0.1:8000/index.php`

## Güvenlik Notu
- `config/database.php` dosyası hassastır; herkese açık dizinde bırakmayın.
- Kurulum tamamlanınca `setup/install.php` dosyasını mutlaka kaldırın.
- Canlı ortamda güçlü şifre ve gerekirse ayrı DB kullanıcısı önerilir.
