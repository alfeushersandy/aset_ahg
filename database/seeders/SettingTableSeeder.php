<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert([
            'id_setting' => 1,
            'nama_perusahaan' => 'PT Armada Hada Graha',
            'alamat' => 'JL. Beringin III RT 05 RW 09 Tidar Utara, kec. Magelang Selatan, Kota Magelang',
            'telepon' => '',
            'tipe_nota' => 1, // kecil
            'diskon' => 5,
            'path_logo' => '',
            'path_kartu_member' => '',
        ]);
    }
}
