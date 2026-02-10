<?php
$platformMetrics = [
    ['value' => '120K+', 'label' => 'Aktif Öğrenci'],
    ['value' => '1.800+', 'label' => 'Öğretmen'],
    ['value' => '12.500+', 'label' => 'Video Ders'],
    ['value' => '%96', 'label' => 'Memnuniyet'],
];

$studentFeatures = [
    [
        'title' => 'Ders Deneyimi',
        'items' => [
            'Adaptive video player (hız, kalite, altyazı)',
            'Ders notu/PDF ve konu özeti',
            'Dersi tamamladım + ilerleme senkronu',
            'Canlı ders tekrarı ve soru geçmişi'
        ]
    ],
    [
        'title' => 'Test Motoru',
        'items' => [
            'Konu sonu testleri + zamanlı denemeler',
            'Otomatik puanlama ve başarı analizi',
            'Yanlış defteri ve önerilen tekrar videoları',
            'Video çözüm linkleri ve etiketli çözümler'
        ]
    ],
    [
        'title' => 'Motivasyon & Takip',
        'items' => [
            'Günlük çalışma süresi raporu',
            'Haftalık gelişim heatmap',
            'Konu bazlı başarı yüzdesi',
            'Rozetler, hedefler ve seri günü takibi'
        ]
    ]
];

$teacherFeatures = [
    [
        'title' => 'İçerik Stüdyosu',
        'items' => [
            'Video upload + bölümlendirme',
            'PDF/test ekleme ve yayın takvimi',
            'Kazanım bazlı etiketleme',
            'A/B thumbnail ve başlık testi'
        ]
    ],
    [
        'title' => 'Sınav Yönetimi',
        'items' => [
            'Soru bankası ve sürümleme',
            'Zorluk seviyesi bazlı test üretimi',
            'Otomatik puanlama',
            'Video çözüm yönetimi'
        ]
    ],
    [
        'title' => 'Öğrenci Analitiği',
        'items' => [
            'İzlenme drop-off analizi',
            'Başarı trendi ve riskli öğrenciler',
            'Canlı ders katılım raporu',
            'Toplu mesaj ve duyuru akışları'
        ]
    ]
];

$adminFeatures = [
    [
        'title' => 'Kullanıcı & Yetki',
        'items' => [
            'Öğrenci / öğretmen / admin rol matrisi',
            'Hesap dondurma ve cihaz yönetimi',
            'IP anomali kontrolü',
            'Audit log ve güvenlik alarmı'
        ]
    ],
    [
        'title' => 'Ticari Operasyon',
        'items' => [
            'Paket ve abonelik yönetimi',
            'İyzico / PayTR entegrasyon noktası',
            'Kupon & kampanya',
            'İade / iptal iş akışı'
        ]
    ],
    [
        'title' => 'İçerik & Kalite',
        'items' => [
            'Ders onay süreci',
            'Müfredat yönetimi',
            'Yayın planlama',
            'En çok izlenen ders raporu'
        ]
    ]
];

$premiumSections = [
    [
        'title' => '3 Tıkta Satın Alma',
        'desc' => 'Kayıt, paket seçimi ve ödeme adımlarını minimum tıkla tamamlayan dönüşüm odaklı checkout.'
    ],
    [
        'title' => 'Mobil %100 Uyum',
        'desc' => 'Özellikle mobil öğrenciler için tek elle kullanım ve hızlı sayfa geçiş mimarisi.'
    ],
    [
        'title' => 'SEO Dostu İçerik',
        'desc' => 'Ders sayfaları, öğretmen profilleri ve başarı hikayeleriyle organik trafik büyümesi.'
    ],
    [
        'title' => 'Güvenli Video Akışı',
        'desc' => 'Signed URL, tek cihaz politikası ve log kayıtlarıyla içerik koruması.'
    ]
];

$plans = [
    [
        'name' => 'Starter',
        'price' => '₺299/ay',
        'features' => ['Temel ders erişimi', 'Konu testleri', 'Forum erişimi']
    ],
    [
        'name' => 'Pro',
        'price' => '₺599/ay',
        'features' => ['Tüm dersler + canlı ders', 'Deneme sınavları', 'Haftalık raporlar']
    ],
    [
        'name' => 'Elite',
        'price' => '₺999/ay',
        'features' => ['Koçluk görüşmeleri', 'Öncelikli soru desteği', 'Kişisel çalışma planı']
    ]
];

$faqItems = [
    ['q' => 'Markahost Eco Linux paketinde çalışır mı?', 'a' => 'Evet. Bu sürüm doğrudan PHP ile çalışacak şekilde tasarlandı.'],
    ['q' => 'Ödeme entegrasyonu eklenebilir mi?', 'a' => 'Evet. İyzico veya PayTR entegrasyonu için backend katmanı eklenebilir.'],
    ['q' => 'Canlı ders eklemek mümkün mü?', 'a' => 'Evet. Zoom/Agora/Jitsi gibi servislerle entegre canlı ders modülü kurulabilir.']
];
