<?php

return [
    'roles' => [
        'admin' => 'Yönetici',
        'doctor' => 'Doktor',
        'nurse' => 'Hemşire',
        'receptionist' => 'Resepsiyonist',
        'accountant' => 'Muhasebe',
        'lab_technician' => 'Laborant',
    ],

    'permissions' => [
        'access_admin_panel' => 'Yönetim paneline erişim',

        'view_any_facility' => 'Tüm şubeleri görüntüle',
        'view_facility' => 'Şubeyi görüntüle',
        'create_facility' => 'Şube oluştur',
        'update_facility' => 'Şube güncelle',
        'delete_facility' => 'Şube sil',

        'view_any_patient' => 'Tüm hastaları görüntüle',
        'view_patient' => 'Hastayı görüntüle',
        'create_patient' => 'Hasta oluştur',
        'update_patient' => 'Hasta güncelle',
        'delete_patient' => 'Hasta sil',

        'view_any_appointment' => 'Tüm randevuları görüntüle',
        'view_appointment' => 'Randevuyu görüntüle',
        'create_appointment' => 'Randevu oluştur',
        'update_appointment' => 'Randevu güncelle',
        'delete_appointment' => 'Randevu sil',

        'view_any_encounter' => 'Tüm muayeneleri görüntüle',
        'view_encounter' => 'Muayeneyi görüntüle',
        'create_encounter' => 'Muayene oluştur',
        'update_encounter' => 'Muayene güncelle',
        'delete_encounter' => 'Muayene sil',

        'view_any_prescription' => 'Tüm reçeteleri görüntüle',
        'view_prescription' => 'Reçeteyi görüntüle',
        'create_prescription' => 'Reçete oluştur',
        'update_prescription' => 'Reçete güncelle',
        'delete_prescription' => 'Reçete sil',

        'view_any_lab_order' => 'Tüm laboratuvar isteklerini görüntüle',
        'view_lab_order' => 'Laboratuvar isteğini görüntüle',
        'create_lab_order' => 'Laboratuvar isteği oluştur',
        'record_lab_result' => 'Laboratuvar sonucu kaydet',
        'review_lab_result' => 'Laboratuvar sonucunu onayla',

        'view_any_report' => 'Tüm raporları görüntüle',

        'view_any_service' => 'Tüm hizmetleri görüntüle',
        'view_service' => 'Hizmeti görüntüle',
        'create_service' => 'Hizmet oluştur',
        'update_service' => 'Hizmet güncelle',
        'delete_service' => 'Hizmet sil',

        'view_any_billing' => 'Tüm fatura kalemlerini görüntüle',
        'view_billing' => 'Fatura kalemini görüntüle',
        'create_billing' => 'Fatura kalemi oluştur',
        'update_billing' => 'Fatura kalemi güncelle',
        'delete_billing' => 'Fatura kalemi sil',

        'view_any_payment' => 'Tüm ödemeleri görüntüle',
        'view_payment' => 'Ödemeyi görüntüle',
        'create_payment' => 'Ödeme al',
        'update_payment' => 'Ödeme güncelle',
        'delete_payment' => 'Ödeme sil',

        'view_any_claim' => 'Tüm talepleri görüntüle',
        'view_claim' => 'Talebi görüntüle',
        'create_claim' => 'Talep oluştur',
        'update_claim' => 'Talep güncelle',
        'delete_claim' => 'Talep sil',
    ],

    'navigation' => [
        'organization' => 'Kurum',
        'patients' => 'Hastalar',
        'clinical' => 'Klinik',
        'billing' => 'Faturalandırma',
        'laboratory' => 'Laboratuvar',
        'reports' => 'Raporlar',
        'settings' => 'Ayarlar',
    ],

    'genders' => [
        'male' => 'Erkek',        'female' => 'Kadın',
        'other' => 'Diğer',
    ],

    'blood_types' => [
        'A+' => 'A Rh+',
        'A-' => 'A Rh-',
        'B+' => 'B Rh+',
        'B-' => 'B Rh-',
        'AB+' => 'AB Rh+',
        'AB-' => 'AB Rh-',
        '0+' => '0 Rh+',
        '0-' => '0 Rh-',
    ],

    'age_unit' => [
        'years' => 'yaşında',
    ],

    'patients' => [
        'singular' => 'Hasta',
        'plural' => 'Hastalar',
        'invite_to_portal' => 'Hasta Portalına Davet Et',
        'invite_to_portal_help' => 'Bu hasta için portal hesabı oluşturulacak ve :email adresine şifre belirleme bağlantısı gönderilecek.',
        'invite_sent' => 'Portal daveti gönderildi. Hasta e-postasından şifresini belirleyebilir.',
        'invite_failed' => 'Davet bağlantısı gönderilemedi. E-posta ayarlarını kontrol edin.',
    ],

    'patient-form' => [
        'mrn' => 'Hasta No',
        'full_name' => 'Ad Soyad',
        'first_name' => 'Ad',
        'last_name' => 'Soyad',
        'birth_date' => 'Doğum Tarihi',
        'age' => 'Yaş',
        'gender' => 'Cinsiyet',
        'national_id' => 'T.C. Kimlik No',
        'blood_type' => 'Kan Grubu',
        'phone' => 'Telefon',
        'email' => 'E-posta',
        'address' => 'Adres',
        'city' => 'İl',
        'district' => 'İlçe',
        'emergency_contact_name' => 'Acil Durum Kişisi',
        'emergency_contact_phone' => 'Acil Durum Telefonu',
        'emergency_contact_relation' => 'Yakınlık Derecesi',
        'is_deceased' => 'Vefat',
        'deceased_at' => 'Vefat Tarihi',
        'notes' => 'Notlar',
        'policies' => 'Sigorta Poliçeleri',
        'registered_at' => 'Kayıt Tarihi',
        'tabs' => [
            'demographics' => 'Demografik Bilgiler',
            'contact' => 'İletişim',
            'emergency' => 'Acil Durum',
            'additional' => 'Diğer',
        ],
    ],

    'insurance' => [
        'company_name' => 'Sigorta Şirketi',
        'policy_number' => 'Poliçe No',
        'start_date' => 'Başlangıç Tarihi',
        'end_date' => 'Bitiş Tarihi',
        'notes' => 'Notlar',
    ],

    'documents' => [
        'title' => 'Başlık',
        'file' => 'Dosya',
        'uploaded_by' => 'Yükleyen',
        'uploaded_at' => 'Yükleme Tarihi',
    ],

    'appointments' => [
        'singular' => 'Randevu',
        'plural' => 'Randevular',
        'patient' => 'Hasta',
        'provider' => 'Sağlayıcı',
        'facility' => 'Şube',
        'title' => 'Başlık',
        'starts_at' => 'Başlangıç',
        'ends_at' => 'Bitiş',
        'duration' => 'Süre',
        'status' => 'Durum',
        'room' => 'Oda',
        'notes' => 'Notlar',
        'section' => [
            'appointment' => 'Randevu Bilgileri',
            'notes' => 'Notlar',
        ],
        'filters' => [
            'today' => 'Bugün',
            'upcoming' => 'Yaklaşan',
        ],
    ],

    'appointment-statuses' => [
        'scheduled' => 'Planlandı',
        'checked_in' => 'Geldi',
        'in_progress' => 'Muayenede',
        'completed' => 'Tamamlandı',
        'no_show' => 'Gelmedi',
        'cancelled' => 'İptal',
    ],

    'flow-board' => [
        'singular' => 'Hasta Akışı',
        'title' => "Bugünün Hasta Akışı",
    ],

    'encounters' => [
        'singular' => 'Muayene',
        'plural' => 'Muayeneler',
        'patient' => 'Hasta',
        'started_at' => 'Muayene Tarihi',
        'ended_at' => 'Bitiş',
        'chief_complaint' => 'Ana Şikayet',
        'subjective' => 'Öykü (S)',
        'objective' => 'Bulgular (O)',
        'assessment' => 'Değerlendirme (A)',
        'plan' => 'Tedavi Planı (P)',
        'tabs' => [
            'soap' => 'SOAP Notu',
            'notes' => 'Notlar',
        ],
        'filters' => [
            'open' => 'Açık Muayeneler',
        ],
    ],

    'vitals' => [
        'singular' => 'Yaşam Bulgusu',
        'plural' => 'Yaşam Bulguları',
        'measured_at' => 'Ölçüm Zamanı',
        'height_cm' => 'Boy (cm)',
        'weight' => 'Kilo',
        'weight_help' => 'Gram cinsinden girin (örn. 72000 = 72 kg)',
        'systolic' => 'Sistolik',
        'diastolic' => 'Diyastolik',
        'blood_pressure' => 'Tansiyon',
        'pulse' => 'Nabız',
        'temperature' => 'Ateş',
        'spo2' => 'SpO2',
        'respiratory_rate' => 'Solunum Sayısı',
        'bmi' => 'BKİ',
    ],

    'diagnoses' => [
        'singular' => 'Teşhis',
        'plural' => 'Teşhisler',
        'code' => 'Kod',
        'description' => 'Açıklama',
        'is_primary' => 'Birincil Teşhis',
        'attach' => 'Teşhis Ekle',
    ],

    'allergies' => [
        'singular' => 'Alerji',
        'plural' => 'Alerjiler',
        'allergen' => 'Alerjen',
        'reaction' => 'Reaksiyon',
        'severity' => 'Şiddet',
        'noted_at' => 'Kayıt Tarihi',
    ],

    'allergy-severities' => [
        'mild' => 'Hafif',
        'moderate' => 'Orta',
        'severe' => 'Ağır',
        'anaphylactic' => 'Anafilaktik',
    ],

    'immunizations' => [
        'singular' => 'Aşı',
        'plural' => 'Aşılar',
        'vaccine_name' => 'Aşı Adı',
        'dose_number' => 'Doz No',
        'administered_at' => 'Uygulama Tarihi',
    ],

    'prescriptions' => [
        'singular' => 'Reçete',
        'plural' => 'Reçeteler',
        'prescriber' => 'Reçeteyi Yazan',
        'issued_at' => 'Reçete Tarihi',
        'dispensed_at' => 'Tahsis Tarihi',
        'dispense' => 'Tahsis Edildi',
        'drug' => 'İlaç',
        'dosage' => 'Dozaj',
        'frequency' => 'Kullanım Sıklığı',
        'duration_days' => 'Süre (gün)',
        'quantity' => 'Adet',
        'instructions' => 'Kullanım Talimatı',
        'items' => 'İlaç Kalemleri',
        'print' => 'Yazdır',
        'print_title' => 'Reçete #REC-:id',
        'print_footer' => 'Bu belge HospitaleMR tarafından oluşturulmuştur. (Reçete No: REC-:id)',
        'days' => 'gün',
        'section' => [
            'details' => 'Reçete Bilgileri',
            'items' => 'İlaç Kalemleri',
        ],
    ],

    'prescription-statuses' => [
        'draft' => 'Taslak',
        'active' => 'Aktif',
        'dispensed' => 'Tahsis Edildi',
        'cancelled' => 'İptal',
    ],

    'drugs' => [
        'singular' => 'İlaç',
        'plural' => 'İlaçlar',
        'code' => 'İlaç Kodu',
        'name' => 'İlaç Adı',
        'active_ingredient' => 'Etken Madde',
        'form' => 'Form',
        'strength' => 'Dozu',
        'unit' => 'Birim',
        'is_prescription_only' => 'Reçeteli',
        'stock_quantity' => 'Stok Miktarı',
        'low_stock_threshold' => 'Kritik Stok Eşiği',
        'is_low_stock' => 'Kritik Stok',
        'filters' => [
            'low_stock' => 'Kritik Stok',
        ],
        'section' => [
            'details' => 'İlaç Bilgileri',
            'stock' => 'Stok',
        ],
    ],

    'services' => [
        'singular' => 'Hizmet',
        'plural' => 'Hizmetler',
        'code' => 'Hizmet Kodu',
        'name' => 'Hizmet Adı',
        'description' => 'Açıklama',
        'price' => 'Fiyat',
        'is_active' => 'Aktif',
        'times_billed' => 'Fatura Sayısı',
        'section' => [
            'details' => 'Hizmet Bilgileri',
        ],
    ],

    'billing-lines' => [
        'singular' => 'Fatura Kalemi',
        'plural' => 'Fatura Kalemleri',
        'service' => 'Hizmet',
        'quantity' => 'Adet',
        'unit_price' => 'Birim Fiyat',
        'discount' => 'İndirim',
        'total' => 'Toplam',
        'billed_at' => 'Fatura Tarihi',
        'apply_service_price' => 'Fiyatı Katalogdan Uygula',
        'summary' => 'Faturalama Özeti',
        'filters' => [
            'linked_to_encounter' => 'Muayeneye Bağlı',
        ],
        'section' => [
            'details' => 'Fatura Kalemi Bilgileri',
        ],
    ],

    'payments' => [
        'singular' => 'Ödeme',
        'plural' => 'Ödemeler',
        'method' => 'Ödeme Yöntemi',
        'amount' => 'Tutar',
        'paid_at' => 'Ödeme Tarihi',
        'reference' => 'Referans',
        'received_by' => 'Tahsil Eden',
        'total' => 'Genel Toplam',
        'section' => [
            'details' => 'Ödeme Bilgileri',
        ],
    ],

    'payment-methods' => [
        'cash' => 'Nakit',
        'card' => 'Kart',
        'transfer' => 'Havale/EFT',
        'insurance' => 'Sigorta',
    ],

    'claims' => [
        'singular' => 'Sigorta Talebi',
        'plural' => 'Sigorta Talepleri',
        'insurer_name' => 'Sigorta Şirketi',
        'claim_number' => 'Talep No',
        'amount' => 'Talep Tutarı',
        'submitted_at' => 'Gönderilme Tarihi',
        'decided_at' => 'Sonuç Tarihi',
        'section' => [
            'details' => 'Talep Bilgileri',
        ],
    ],

    'claim-statuses' => [
        'draft' => 'Taslak',
        'submitted' => 'Gönderildi',
        'approved' => 'Onaylandı',
        'denied' => 'Reddedildi',
        'paid' => 'Ödendi',
    ],

    'lab-orders' => [
        'singular' => 'Lab İsteği',
        'plural' => 'Lab İstekleri',
        'ordered_at' => 'İstek Tarihi',
        'ordered_by' => 'İsteği Yapan',
        'sample_collected_at' => 'Numune Alma Tarihi',
        'tests' => 'İstenen Testler',
        'results_count' => 'Sonuç Sayısı',
        'filters' => [
            'pending' => 'Bekleyen İstekler',
        ],
        'section' => [
            'details' => 'İstek Bilgileri',
            'results' => 'Sonuçlar',
        ],
    ],

    'lab-order-statuses' => [
        'ordered' => 'İstek Yapıldı',
        'collected' => 'Numune Alındı',
        'in_laboratory' => 'Laborda',
        'resulted' => 'Sonuçlandı',
        'reviewed' => 'İncelendi',
        'cancelled' => 'İptal',
    ],

    'lab-tests' => [
        'singular' => 'Test',
        'plural' => 'Testler',
        'code' => 'Test Kodu',
        'name' => 'Test Adı',
        'category' => 'Kategori',
        'sample_type' => 'Numune Türü',
        'unit' => 'Birim',
        'reference_range' => 'Referans Aralığı',
    ],

    'lab-results' => [
        'singular' => 'Sonuç',
        'plural' => 'Sonuçlar',
        'value' => 'Değer',
        'value_text' => 'Metin Değer',
        'flag' => 'Değerlendirme',
        'resulted_by' => 'Sonucu Kaydeden',
        'reviewed_by' => 'İnceleyen',
        'review' => 'İncelendi Olarak İşaretle',
    ],

    'lab-result-flags' => [
        'normal' => 'Normal',
        'low' => 'Düşük',
        'high' => 'Yüksek',
        'critical' => 'Kritik',
    ],

    'widgets' => [
        'appointments_today' => 'Bugünkü Randevular',
        'appointments_today_help' => 'Bugün planlanmış toplam randevu',
        'open_encounters' => 'Açık Muayeneler',
        'open_encounters_help' => 'Henüz tamamlanmamış muayeneler',
        'revenue_today' => 'Bugünkü Hasılat',
        'revenue_help' => 'Bugün tahsil edilen toplam ödemeler',
        'pending_lab_orders' => 'Bekleyen Lab İstekleri',
        'pending_lab_orders_help' => 'Laboratuvarda bekleyen istekler',
    ],

    'users' => [
        'singular' => 'Kullanıcı',
        'plural' => 'Kullanıcılar',
        'name' => 'Ad Soyad',
        'email' => 'E-posta',
        'password' => 'Şifre',
        'roles' => 'Roller',
        'current_team' => 'Mevcut Klinik',
        'verified_at' => 'Doğrulama Tarihi',
        'created_at' => 'Kayıt Tarihi',
        'section' => [
            'details' => 'Kullanıcı Bilgileri',
        ],
    ],

    'facilities' => [
        'singular' => 'Şube',
        'plural' => 'Şubeler',
        'name' => 'Şube Adı',
        'code' => 'Şube Kodu',
        'phone' => 'Telefon',
        'email' => 'E-posta',
        'address' => 'Adres',
        'city' => 'İl',
        'district' => 'İlçe',
        'postal_code' => 'Posta Kodu',
        'is_active' => 'Aktif',
        'section' => [
            'details' => 'Şube Bilgileri',
            'notes' => 'Notlar',
        ],
    ],

    'api-clients' => [
        'singular' => 'API İstemcisi',
        'plural' => 'API İstemcileri',
        'name' => 'İstemci Adı',
        'client_code' => 'İstemci Kodu',
        'is_active' => 'Aktif',
        'last_used_at' => 'Son Kullanım',
        'requests_count' => 'İstek Sayısı',
        'generate_token' => 'Token Üret',
        'token_title' => 'Yeni API Token',
        'token_warning' => 'Bu token yalnızca bir kez gösterilir. Güvenli bir yerde saklayın.',
        'back' => 'API istemcilerine dön',
        'section' => [
            'details' => 'İstemci Bilgileri',
        ],
    ],

    'integration' => [
        'singular' => 'Entegrasyon Kaydı',
        'plural' => 'Entegrasyon Kayıtları',
        'service' => 'Servis',
        'operation' => 'İşlem',
        'subject' => 'Kayıt',
        'code' => 'Yanıt Kodu',
        'message' => 'Mesaj',
        'attempts' => 'Deneme Sayısı',
        'sent_at' => 'Gönderim Tarihi',
        'retry' => 'Yeniden Gönder',
        'request' => 'İstek Yükü',
        'response' => 'Yanıt Yükü',
        'statuses' => [
            'pending' => 'Bekliyor',
            'success' => 'Başarılı',
            'failed' => 'Başarısız',
        ],
    ],

    'validation' => [
        'appointment_overlap' => 'Seçilen sağlayıcının bu saatte başka bir randevusu var.',
    ],
];
