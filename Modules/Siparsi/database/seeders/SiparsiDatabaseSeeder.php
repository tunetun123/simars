<?php

namespace Modules\Siparsi\Database\Seeders;

use Illuminate\Database\Seeder;

class SiparsiDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'Kelompok Manajemen RS' => [
                'Tata Kelola Rumah Sakit (TKRS)' => 15,
                'Kualifikasi dan Pendidikan Staf (KPS)' => 19,
                'Manajemen Fasilitas dan Keselamatan (MFK)' => 11,
                'Peningkatan Mutu dan Keselamatan Pasien (PMKP)' => 11,
                'Manajemen Rekam Medik dan Informasi Kesehatan (MRMIK)' => 13,
                'Pencegahan dan Pengendalian Infeksi (PPI)' => 13,
                'Pendidikan dalam Pelayanan Kesehatan (PPK)' => 6
            ],
            'Kelompok Pelayanan Berorientasi Pasien' => [
                'Akses dan Kontinuitas Pelayanan (AKP)' => 6,
                'Hak Pasien dan Keluarga (HPK)' => 4,
                'Pengkajian Pasien (PP)' => 4,
                'Pelayanan dan Asuhan Pasien (PAP)' => 5,
                'Pelayanan Anestesi dan Bedah (PAB)' => 7,
                'Pelayanan Kefarmasian dan Penggunaan Obat (PKPO)' => 8,
                'Komunikasi dan Edukasi (KE)' => 7
            ],
            'Sasaran Keselamatan Pasien' => [
                'Sasaran Keselamatan Pasien (SKP)' => 6
            ],
            'Program Nasional' => [
                'Program Nasional (PROGNAS)' => 5
            ]
        ];

        foreach ($groups as $groupName => $categories) {
            $group = \Modules\Siparsi\Models\DocumentGroup::firstOrCreate([
                'name' => $groupName
            ]);

            foreach ($categories as $catName => $epCount) {
                $category = \Modules\Siparsi\Models\DocumentCategory::firstOrCreate([
                    'document_group_id' => $group->id,
                    'name' => $catName
                ]);

                // Create Assessment Elements
                for ($i = 1; $i <= $epCount; $i++) {
                    \Modules\Siparsi\Models\AssessmentElement::firstOrCreate([
                        'document_category_id' => $category->id,
                        'name' => "EP {$i}"
                    ]);
                }
            }
        }
    }
}
