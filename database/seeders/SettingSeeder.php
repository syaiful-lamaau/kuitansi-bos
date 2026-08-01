<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'tahun_anggaran' => '2026',
                'nama_madrasah' => 'MIS Fathul Munir',
                'alamat' => 'Jl. Sangaji Utara, Kec. Ternate Utara, Kota Ternate, Prov. Maluku Utara',
                'sumber_dana' => 'APBN - BOS Tahap',
                'total_pagu_anggaran' => 0,
                'nama_kepala' => 'Sutisna Abdullatief, S.Pd.I',
                'nip_kepala' => '196804101999031001',
                'nama_bendahara' => 'Jon Hasan, S.Pd.I',
                'nip_bendahara' => '196704201998011001',
            ]
        );
    }
}
