<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LatihanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Hindari data ganda kalau seeder dijalankan dua kali
        if (DB::table('courses')->where('slug', 'python-dasar')->exists()) {
            return;
        }

        // User id 1 (dipakai SubmissionController: auth()->id() ?? 1)
        if (!DB::table('users')->where('id', 1)->exists()) {
            DB::table('users')->insert([
                'id' => 1,
                'name' => 'Sukma Ananda',
                'email' => 'learner@pybeginner.test',
                'password' => Hash::make('password'),
                'role' => 'learner',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            // Sinkronkan sequence Postgres supaya register user baru tidak bentrok
            DB::statement("SELECT setval(pg_get_serial_sequence('users','id'), (SELECT MAX(id) FROM users))");
        }

        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python Dasar',
            'slug' => 'python-dasar',
            'description' => 'Belajar Python dari nol.',
            'level' => 'beginner',
            'is_published' => true,
            'sort_order' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId,
            'title' => 'Percabangan & Logika Kondisional',
            'slug' => 'percabangan',
            'description' => 'if, elif, else, dan operator.',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $lessonId = DB::table('lessons')->insertGetId([
            'module_id' => $moduleId,
            'title' => 'Latihan Percabangan',
            'slug' => 'latihan-percabangan',
            'content' => 'Materi percabangan if-else.',
            'sort_order' => 1,
            'is_published' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $exercises = [
            [
                'title' => 'Cetak Salam & String Formatting',
                'slug' => 'cetak-salam',
                'description' => 'Cetak salam dengan nama yang dimasukkan pengguna.',
                'instructions' => 'Baca satu baris nama, lalu cetak: Halo, <nama>!',
                'starter_code' => "nama = input()\n# tulis kodemu di bawah ini\n",
                'difficulty' => 'easy',
                'tests' => [
                    ['Budi', 'Halo, Budi!', false],
                    ['Sari', 'Halo, Sari!', true],
                ],
            ],
            [
                'title' => 'Cek Bilangan Genap atau Ganjil',
                'slug' => 'cek-genap-ganjil',
                'description' => 'Tentukan apakah bilangan genap atau ganjil.',
                'instructions' => 'Baca satu bilangan bulat. Cetak "Genap" atau "Ganjil".',
                'starter_code' => "angka = int(input())\n# tulis kodemu di bawah ini\n",
                'difficulty' => 'easy',
                'tests' => [
                    ['4', 'Genap', false],
                    ['7', 'Ganjil', false],
                    ['0', 'Genap', true],
                ],
            ],
        ];

        foreach ($exercises as $i => $ex) {
            $exerciseId = DB::table('exercises')->insertGetId([
                'lesson_id' => $lessonId,
                'title' => $ex['title'],
                'slug' => $ex['slug'],
                'description' => $ex['description'],
                'instructions' => $ex['instructions'],
                'starter_code' => $ex['starter_code'],
                'difficulty' => $ex['difficulty'],
                'time_limit_ms' => 10000,
                'memory_limit_mb' => 128,
                'sort_order' => $i + 1,
                'is_published' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($ex['tests'] as $j => [$input, $expected, $hidden]) {
                DB::table('test_cases')->insert([
                    'exercise_id' => $exerciseId,
                    'input' => $input,
                    'expected_output' => $expected,
                    'weight' => 1,
                    'is_hidden' => $hidden,
                    'is_active' => true,
                    'sort_order' => $j + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}