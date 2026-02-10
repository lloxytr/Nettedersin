# Nettedersin LMS (PHP 8 + MySQL, Terminal Gerektirmez)

Bu repo, **Markahost EcoLinux shared hosting** için hazırlanmış, FTP ile yüklenip web kurulum sihirbazı ile çalışan bir LMS'tir.

## Özellikler (Canlı Çalışan)

### 1) Auth + Güvenlik
- Kayıt / Giriş / Çıkış
- Şifre sıfırlama (token üretim + yeni şifre belirleme)
- Roller: `student`, `teacher`, `admin`
- RBAC koruması (`require_role`)
- CSRF token tüm kritik formlarda
- Login brute-force koruma (IP başına 15 dk pencerede limit)
- Şifre hash: bcrypt (`password_hash`)

### 2) Öğrenci Paneli
- Dashboard: ilerleme, son ders, son test skoru
- Kurs listesi ve kurs detay (Kurs > Bölüm > Ders)
- Ders sayfası:
  - HTML5 video
  - Hız seçimi
  - Kaldığı yerden devam (progress save)
  - Tamamlandı işareti
  - PDF ekleri
  - Yorum/Soru (moderasyonlu)
- Test çözme:
  - Çoktan seçmeli
  - Otomatik puanlama
  - Sonuç ekranı
  - Yanlış defteri kaydı
- Paket satın alma:
  - Manuel/Test ödeme siparişi
  - Sipariş geçmişi

### 3) Öğretmen Paneli
- Kurs oluşturma (admin onay akışı)
- Bölüm ekleme
- Soru bankası CRUD başlangıcı (ekleme + listeleme)
- Test oluşturma ve soru bağlama

### 4) Admin Paneli
- İçerik onayı (yayınla/taslağa al)
- Kullanıcı yönetimi (aktif/dondur)
- Sipariş yönetimi
- Manuel ödeme onayı (`pending -> paid`)
- Audit log kayıtları

### 5) SEO + Performans
- Clean URL (rewrite):
  - `/kurslar`
  - `/kurs/<slug>`
  - `/ogretmen/<slug>`
  - `/blog/<slug>`
- `robots.txt` dinamik
- `sitemap.xml` dinamik
- Course + Person schema.org JSON-LD
- Tailwind CDN (build yok)

---

## Kurulum (Terminal YOK Senaryosu)

### Adım 1) FTP ile dosyaları yükle
- Repo içeriğini `public_html` altına yükle.
- `.htaccess` dosyasının geldiğinden emin ol.

### Adım 2) Kurulum sihirbazını aç
- `https://alanadiniz.com/setup/install.php`

### Adım 3) DB bilgilerini gir
- DB Name: `nettepfg_db`
- DB User: `nettepfg_user`
- DB Pass: `Sifre1234.`
- Admin adı/e-posta/şifre belirle

### Adım 4) Kurulumu çalıştır
Sihirbaz:
- DB bağlantısını test eder
- Şemayı ve seed'i otomatik kurar
- `config/config.php` üretir
- Admin hesabı oluşturur
- `uploads/` yazılabilirlik kontrolü yapar

### Adım 5) Güvenlik
- Kurulumdan sonra `setup/install.php` dosyasını silin veya erişimini engelleyin.

---

## Ödeme Akışı

### Çalışan Mod (zorunlu)
- **Manuel/Test ödeme modu** aktif.
- Öğrenci sipariş oluşturur (`pending`)
- Admin panelinden "Manuel Paid" ile onaylar
- Sistem idempotent çalışır: aynı sipariş ikinci kez paid olmaz
- Paid olunca kullanıcıya plan erişimi açılır

### Adapter Hazırlığı
- `app/services/PaymentService.php` içinde iyzico payload adapter iskeleti mevcut.

---

## Klasör Yapısı

- `index.php` → Front controller (routing)
- `app/bootstrap.php` → app init
- `app/helpers.php` → csrf/rbac/render yardımcıları
- `app/services/AuthService.php` → auth, login rate-limit, reset
- `app/services/PaymentService.php` → manuel ödeme + adapter
- `app/views/*` → tüm ekranlar
- `lib/Database.php` → PDO
- `lib/Repository.php` → DB sorguları
- `setup/install.php` → web installer
- `setup/schema.sql`, `setup/seed.sql` → şema ve başlangıç verisi
- `uploads/` → video/pdf yükleme klasörü

---

## Kabul Testleri (Checklist)

1. Kayıt/Giriş/Şifre sıfırlama
2. Rol bazlı erişim (student/teacher/admin)
3. Öğretmen kurs ekler -> admin yayınlar -> öğrenci kursu görür
4. Öğrenci test çözer -> sonuç ekranı oluşur
5. Öğrenci sipariş açar -> admin manuel paid yapar -> erişim açılır
6. CSRF token olmadan POST başarısız olur
7. Login brute-force limiti devreye girer
8. XSS için tüm çıktı `htmlspecialchars` ile escape edilir

---

## Not
Bu sürüm shared hosting kısıtlarına göre tasarlandı:
- Node/Yarn/Composer build zorunluluğu yok
- Terminal zorunluluğu yok
- Web installer ile kurulum tamamlanır

## Hosting Uyumluluğu Notu (FK Hatası İçin)
- Bazı shared hosting MySQL/MariaDB kurulumlarında `errno:150` yabancı anahtar hatası alınabiliyor.
- Bu yüzden şema, **uyumluluk için FK bağımlılığı olmadan** ve index odaklı tasarlandı.
- Uygulama tarafında ilişkisel doğrulama Repository/servis katmanında korunur.
