<?php
require_once __DIR__ . '/Database.php';

class Repository
{
    public static function getDashboardMetrics(string $panel): array
    {
        $defaults = [
            'ogrenci' => [
                ['value' => '6s 40dk', 'label' => 'Bugünkü Çalışma'],
                ['value' => '%78', 'label' => 'Haftalık Başarı'],
                ['value' => '24', 'label' => 'Çözülen Test'],
                ['value' => '9', 'label' => 'Kazanılan Rozet'],
            ],
            'ogretmen' => [
                ['value' => '42', 'label' => 'Yayındaki Ders'],
                ['value' => '18.2K', 'label' => 'Aylık İzlenme'],
                ['value' => '%91', 'label' => 'Tamamlama'],
                ['value' => '315', 'label' => 'Gelen Soru'],
            ],
            'admin' => [
                ['value' => '120K+', 'label' => 'Toplam Kullanıcı'],
                ['value' => '₺8.2M', 'label' => 'Aylık Ciro'],
                ['value' => '1.8K', 'label' => 'Aktif Öğretmen'],
                ['value' => '34', 'label' => 'Güvenlik Alarmı'],
            ],
        ];

        return $defaults[$panel] ?? [];
    }

    public static function getRoadmap(): array
    {
        return [
            ['phase' => 'Faz 1', 'title' => 'Auth + RBAC + Dashboard', 'status' => 'Hazır'],
            ['phase' => 'Faz 2', 'title' => 'Video + Test + Forum Motoru', 'status' => 'Hazır'],
            ['phase' => 'Faz 3', 'title' => 'Ödeme + Abonelik + Fatura', 'status' => 'Hazır'],
            ['phase' => 'Faz 4', 'title' => 'Raporlama + Güvenlik + SEO', 'status' => 'Hazır'],
        ];
    }
}
