<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Municipality;
use Illuminate\Database\Seeder;

class DavaoRegionSeeder extends Seeder
{
    public function run(): void
    {
        // Region 11 (Davao Region) Provinces
        $provinces = [
            ['name' => 'Davao del Norte', 'code' => '11-01', 'region' => 'Region 11'],
            ['name' => 'Davao del Sur', 'code' => '11-02', 'region' => 'Region 11'],
            ['name' => 'Davao Oriental', 'code' => '11-03', 'region' => 'Region 11'],
            ['name' => 'Davao Occidental', 'code' => '11-04', 'region' => 'Region 11'],
        ];

        $municipalitiesByProvince = [
            'Davao del Norte' => [
                'Asuncion', 'Caac', 'Carmen', 'Compostela', 'Gingoog', 'Las Navas', 
                'Mabini', 'Maco', 'Nabunturan', 'Panabo', 'Samal', 'Tagum', 'Talaingod'
            ],
            'Davao del Sur' => [
                'Bansalan', 'Davao City', 'Digos', 'General Santos', 'Hagonoy', 'Kiblawan',
                'Magdiwang', 'Matanao', 'Padada', 'Santa Cruz', 'Sarangani', 'Sulop', 'Tambunan'
            ],
            'Davao Oriental' => [
                'Baganga', 'Banaybanay', 'Boston', 'Caraga', 'Cateel', 'Governor Generoso',
                'Lupon', 'Mati', 'San Isidro', 'Tarragona'
            ],
            'Davao Occidental' => [
                'Malita', 'Sabang', 'Santa Maria', 'Sarangani', 'Sulop', 'Tandag'
            ]
        ];

        foreach ($provinces as $provinceData) {
            $province = Province::updateOrCreate(
                ['code' => $provinceData['code']],
                ['name' => $provinceData['name'], 'region' => $provinceData['region']]
            );

            // Seed municipalities for this province
            if (isset($municipalitiesByProvince[$provinceData['name']])) {
                foreach ($municipalitiesByProvince[$provinceData['name']] as $index => $municipalityName) {
                    Municipality::updateOrCreate(
                        ['name' => $municipalityName, 'province_id' => $province->id],
                        ['code' => $provinceData['code'] . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT)]
                    );
                }
            }
        }
    }
}
