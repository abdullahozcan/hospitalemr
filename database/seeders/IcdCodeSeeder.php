<?php

namespace Database\Seeders;

use App\Models\IcdCode;
use Illuminate\Database\Seeder;

class IcdCodeSeeder extends Seeder
{
    /**
     * A starter set of common ICD-10 codes.
     *
     * @var array<int, array{code: string, description: string}>
     */
    protected array $codes = [
        ['code' => 'A00', 'description' => 'Kolera'],
        ['code' => 'A09', 'description' => 'Bulaşıcı gastroenterit ve kolit'],
        ['code' => 'J06.9', 'description' => 'Akut üst solunum yolu enfeksiyonu, tanımlanmamış'],
        ['code' => 'J11.1', 'description' => 'Solunum belirtileri ile influenza'],
        ['code' => 'J15.9', 'description' => 'Bakteriyel pnömoni, tanımlanmamış'],
        ['code' => 'J45', 'description' => 'Astım'],
        ['code' => 'E11', 'description' => 'Tip 2 diabetes mellitus'],
        ['code' => 'E66', 'description' => 'Aşırı kilo ve obezite'],
        ['code' => 'I10', 'description' => 'Esansiyel (primer) hipertansiyon'],
        ['code' => 'I20', 'description' => 'Anjina pektoris'],
        ['code' => 'I48', 'description' => 'Atriyal fibrilasyon ve flutter'],
        ['code' => 'K02', 'description' => 'Diş çürüğü'],
        ['code' => 'K29', 'description' => 'Gastrit ve duodenit'],
        ['code' => 'K35', 'description' => 'Akut apandisit'],
        ['code' => 'M54.5', 'description' => 'Bel ağrısı'],
        ['code' => 'M17', 'description' => 'Diz artrozu (kireçlenme)'],
        ['code' => 'N39.0', 'description' => 'Üriner sistem enfeksiyonu, tanımlanmamış'],
        ['code' => 'R50.9', 'description' => 'Ateş, tanımlanmamış'],
        ['code' => 'R51', 'description' => 'Baş ağrısı'],
        ['code' => 'R07.4', 'description' => 'Göğüs ağrısı, tanımlanmamış'],
        ['code' => 'R42', 'description' => 'Baş dönmesi ve denge bozukluğu'],
        ['code' => 'R10.4', 'description' => 'Diğer ve tanımlanmamış karın ağrısı'],
        ['code' => 'B54', 'description' => 'Tanımlanmamış domuz gribi olmayan influenza'],
        ['code' => 'U07.1', 'description' => 'COVID-19, virüs tanımlı'],
        ['code' => 'S52.5', 'description' => 'Alt ucu distal radius kırığı'],
        ['code' => 'S82.5', 'description' => 'Tibia ve fibula malleol kırığı'],
        ['code' => 'S06.0', 'description' => 'Kafa içi yaralanma ile serebral konküzyon'],
        ['code' => 'L50.9', 'description' => 'Ürtiker, tanımlanmamış'],
        ['code' => 'F41.1', 'description' => 'Yaygın anksiyete bozukluğu'],
        ['code' => 'F32', 'description' => 'Depresif atak'],
    ];

    /**
     * Seed a starter set of ICD-10 codes.
     */
    public function run(): void
    {
        foreach ($this->codes as $code) {
            IcdCode::updateOrCreate(['code' => $code['code']], $code);
        }
    }
}
