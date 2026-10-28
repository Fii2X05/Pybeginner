<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Memanggil seluruh seeder modul pembelajaran secara berurutan.
     *
     * Jalankan: php artisan db:seed --class=ModulSeeder
     */
    public function run(): void
    {
        $this->call([
            Modul1Seeder::class,
            Modul2Seeder::class,
            Modul3Seeder::class,
            Modul4Seeder::class,
            Modul5Seeder::class,
            Modul6Seeder::class,
            Modul7Seeder::class,
        ]);
    }
}
