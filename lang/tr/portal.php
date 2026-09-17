<?php

return [
    'brand' => [
        'tagline' => 'Hasta Portalı',
        'auth_pitch' => 'Sağlık hizmetinize her yerden ulaşın',
        'auth_pitch_sub' => 'Randevularınız, reçeteleriniz ve lab sonuçlarınız tek yerde.',
    ],

    'nav' => [
        'dashboard' => 'Ana Sayfa',
        'appointments' => 'Randevularım',
        'prescriptions' => 'Reçetelerim',
        'lab_results' => 'Lab Sonuçlarım',
        'invoices' => 'Faturalarım',
        'profile' => 'Profilim',
        'logout' => 'Çıkış Yap',
    ],

    'common' => [
        'view_all' => 'Tümünü gör',
        'details' => 'Detay',
        'records' => 'kayıt',
        'orders' => 'istek',
    ],

    'dashboard' => [
        'greeting' => 'Günaydın, :name',
        'mrn_label' => 'Hasta No',
        'next_appointment' => 'Yaklaşan Randevunuz',
        'view_all' => 'Randevularım',
        'recent_activity' => 'Son Muayeneler',
        'no_activity' => 'Henüz muayene kaydınız yok.',
        'balance_label' => 'Açık Bakiye',
        'balance_help' => 'Ödenmemiş hizmet bedelleri toplamı',
        'no_patient_record' => 'Hasta kaydınız bulunamadı',
        'call_clinic' => 'Randevu almak için kliniğimizle iletişime geçebilirsiniz.',
        'contact_support' => 'Lütfen kliniğimizle iletişime geçin.',
    ],

    'appointments' => [
        'title' => 'Randevularım',
        'subtitle' => 'Geçmiş ve yaklaşan randevularınız',
        'upcoming' => 'Yaklaşan',
        'past' => 'Geçmiş',
        'default_title' => 'Muayene',
        'with_provider' => 'Sağlayıcı: :provider',
        'room' => 'Oda :room',
        'no_upcoming' => 'Yaklaşan randevunuz bulunmuyor. Randevu almak için kliniğimizi arayın.',
        'no_past' => 'Geçmiş randevu kaydınız yok.',
    ],

    'prescriptions' => [
        'title' => 'Reçetelerim',
        'subtitle' => 'E-reçete geçmişiniz ve ilaç kullanım talimatları',
        'number' => 'Reçete #REC-:number',
        'prescriber' => 'Reçeteyi Yazan',
        'dosage' => 'Dozaj',
        'instructions' => 'Kullanım',
        'days' => 'gün',
        'empty' => 'Henüz reçete kaydınız bulunmuyor.',
    ],

    'lab-results' => [
        'title' => 'Lab Sonuçlarım',
        'subtitle' => 'Laboratuvar sonuçlarınız ve referans aralıkları',
        'number' => 'İstek #LAB-:number',
        'empty' => 'Henüz laboratuvar sonucunuz bulunmuyor.',
    ],

    'invoices' => [
        'title' => 'Faturalarım',
        'subtitle' => 'Hizmet kalemleriniz ve ödemeleriniz',
        'balance_label' => 'Açık Bakiye',
        'charges' => 'Hizmet Kalemleri',
        'payments' => 'Ödemeleriniz',
        'visit' => 'Muayene',
        'discount' => ':amount ₺ indirim',
        'no_charges' => 'Faturalanmış hizmet kaleminiz yok.',
        'no_payments' => 'Ödeme kaydınız yok.',
    ],

    'profile' => [
        'title' => 'Profilim',
        'subtitle' => 'Kişisel bilgilerinizi güncelleyin',
        'personal_info' => 'Hesap Bilgileri',
        'account' => 'Sağlık Kaydı Bilgileri',
        'name' => 'Ad Soyad',
        'email' => 'E-posta',
        'mrn' => 'Hasta No',
        'birth_date' => 'Doğum Tarihi',
    ],
];
