<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            [
                'type' => 'inventory',
                'name' => 'Baik',
                'color' => 'success',
            ],
            [
                'type' => 'inventory',
                'name' => 'Kurang',
                'color' => 'warning',
            ],
            [
                'type' => 'inventory',
                'name' => 'Tidak layak',
                'color' => 'danger',
            ],
            [
                'type' => 'inventory',
                'name' => 'Tidak diketahui',
                'color' => 'secondary',
            ],
            [
                'type' => 'inventory',
                'name' => 'Dalam Pengajuan Perbaikan',
                'color' => 'secondary',
            ],
            [
                'type' => 'inventory',
                'name' => 'Dalam Perbaikan',
                'color' => 'secondary',
            ],
            [
                'type' => 'inventory',
                'name' => 'Pengadaan',
                'color' => 'secondary',
            ],
            [
                'type' => 'issue',
                'name' => 'Pengajuan Perbaikan',
                'color' => 'secondary',
            ],
            [
                'type' => 'issue',
                'name' => 'Disetuji',
                'color' => 'primary',
            ],
            [
                'type' => 'issue',
                'name' => 'Ditolak',
                'color' => 'danger',
            ],
            [
                'type' => 'issue',
                'name' => 'Selesai',
                'color' => 'success',
            ],
            [
                'type' => 'request',
                'name' => 'Rencana Pembelian',
                'color' => 'secondary',
            ],
            [
                'type' => 'request',
                'name' => 'Pengajuan Pembelian',
                'color' => 'secondary',
            ],
            [
                'type' => 'request',
                'name' => 'Disetujui',
                'color' => 'primary',
            ],
            [
                'type' => 'request',
                'name' => 'Ditolak',
                'color' => 'danger',
            ],
            [
                'type' => 'request',
                'name' => 'Dalam Proses',
                'color' => 'warning',
            ],
            [
                'type' => 'request',
                'name' => 'Dalam Pengiriman',
                'color' => 'warning',
            ],
            [
                'type' => 'request',
                'name' => 'Diterima',
                'color' => 'success',
            ],
            [
                'type' => 'request',
                'name' => 'Selesai',
                'color' => 'success',
            ],
        ];

        foreach ($status as $key => $value) {
            DB::table('status')->insert($value);    
        }   
    }
}
