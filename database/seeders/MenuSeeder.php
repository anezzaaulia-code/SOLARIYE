<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            [
                'nama' => 'Nasi Goreng Teri',
                'kategori' => 'nasi',
                'harga' => 15000,
                'foto' => 'pembeli/nasgor teri.jpg'
            ],
            [
                'nama' => 'Nasi Goreng Merah',
                'kategori' => 'nasi',
                'harga' => 15000,
                'foto' => 'pembeli/nasgor merah.jpg'
            ],
            [
                'nama' => 'Mie Goreng',
                'kategori' => 'mie',
                'harga' => 13000,
                'foto' => 'pembeli/mie goreng.jpg'
            ],
            [
                'nama' => 'Mie Goreng Spesial',
                'kategori' => 'mie',
                'harga' => 17000,
                'foto' => 'pembeli/mie goreng spesial.jpg'
            ],
            [
                'nama' => 'Mie Kuah',
                'kategori' => 'mie',
                'harga' => 13000,
                'foto' => 'pembeli/mie kuah.jpg'
            ],
            [
                'nama' => 'Kwetiau Goreng',
                'kategori' => 'lainnya',
                'harga' => 15000,
                'foto' => 'pembeli/kwetiau goreng.jpg'
            ],
            [
                'nama' => 'Kwetiau Kuah',
                'kategori' => 'lainnya',
                'harga' => 15000,
                'foto' => 'pembeli/kwetiau kuah.jpg'
            ],
        ];

        foreach ($menus as $m) {
            Menu::create($m);
        }
    }
}
