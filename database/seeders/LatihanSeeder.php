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

        if (DB::table('courses')->where('slug', 'python-dasar')->exists()) {
            return;
        }

        if (!DB::table('users')->where('id', 1)->exists()) {
            DB::table('users')->insert([
                'id' => 1, 'name' => 'Sukma Ananda', 'email' => 'learner@pybeginner.test',
                'password' => Hash::make('password'), 'role' => 'learner',
                'created_at' => $now, 'updated_at' => $now,
            ]);
            DB::statement("SELECT setval(pg_get_serial_sequence('users','id'), (SELECT MAX(id) FROM users))");
        }

        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python Dasar', 'slug' => 'python-dasar', 'description' => 'Belajar Python dari nol.',
            'level' => 'beginner', 'is_published' => true, 'sort_order' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId, 'title' => 'Dasar Pemrograman Python', 'slug' => 'dasar-python',
            'description' => 'Dari input-output sampai logika dasar.', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $lessons = [
            'easy' => [
                'title' => 'Input, Output, dan Percabangan', 'slug' => 'input-output-percabangan',
                'content' => "Fungsi print() menampilkan teks ke layar, sedangkan input() membaca satu baris ketikan pengguna sebagai teks (string).\n"
                    . "Untuk mengubah teks menjadi bilangan bulat gunakan int(), misalnya angka = int(input()).\n"
                    . "Percabangan memakai if, elif, dan else untuk memilih kode mana yang dijalankan berdasarkan suatu kondisi.\n"
                    . "Operator % menghasilkan sisa bagi, misalnya 7 % 2 hasilnya 1.\n\n"
                    . "Referensi lanjutan:\nhttps://www.w3schools.com/python/python_variables.asp\nhttps://www.w3schools.com/python/python_conditions.asp",
            ],
            'medium' => [
                'title' => 'Perulangan dan String', 'slug' => 'perulangan-string',
                'content' => "Perulangan for mengulang kode untuk setiap nilai dalam suatu rangkaian, misalnya for i in range(1, 6) mengulang dari 1 sampai 5.\n"
                    . "Fungsi range(a, b) menghasilkan bilangan dari a sampai b dikurangi 1.\n"
                    . "String bisa diakses per karakter dan diiris. Irisan teks[::-1] menghasilkan teks yang dibalik.\n\n"
                    . "Referensi lanjutan:\nhttps://www.w3schools.com/python/python_for_loops.asp\nhttps://www.w3schools.com/python/python_strings.asp",
            ],
            'hard' => [
                'title' => 'Logika Algoritma Dasar', 'slug' => 'logika-algoritma',
                'content' => "Perulangan while berjalan selama kondisinya benar, cocok untuk proses yang jumlah ulangannya tidak pasti.\n"
                    . "Bilangan prima adalah bilangan lebih dari 1 yang hanya habis dibagi 1 dan dirinya sendiri. Cukup periksa pembagi sampai akar kuadratnya.\n"
                    . "Deret Fibonacci: dua suku pertama bernilai 1, lalu setiap suku adalah jumlah dua suku sebelumnya.\n\n"
                    . "Referensi lanjutan:\nhttps://www.w3schools.com/python/python_while_loops.asp\nhttps://www.w3schools.com/python/python_functions.asp",
            ],
        ];

        $lessonIds = [];
        $order = 1;
        foreach ($lessons as $level => $l) {
            $lessonIds[$level] = DB::table('lessons')->insertGetId([
                'module_id' => $moduleId, 'title' => $l['title'], 'slug' => $l['slug'],
                'content' => $l['content'], 'sort_order' => $order++, 'is_published' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // [level, judul, slug, deskripsi, instruksi, starter, tests[[input, output, hidden]]]
        $exercises = [
            ['easy', 'Cetak Salam', 'cetak-salam', 'Cetak salam dengan nama yang dimasukkan pengguna.',
                'Baca satu baris nama, lalu cetak: Halo, <nama>!',
                "nama = input()\n# tulis kodemu di bawah ini\n",
                [['Budi', 'Halo, Budi!', false], ['Sari', 'Halo, Sari!', true]]],
            ['easy', 'Cek Bilangan Genap atau Ganjil', 'cek-genap-ganjil', 'Tentukan apakah bilangan genap atau ganjil.',
                'Baca satu bilangan bulat. Cetak "Genap" atau "Ganjil".',
                "angka = int(input())\n# tulis kodemu di bawah ini\n",
                [['4', 'Genap', false], ['7', 'Ganjil', false], ['0', 'Genap', true]]],
            ['easy', 'Jumlah Dua Bilangan', 'jumlah-dua-bilangan', 'Hitung jumlah dua bilangan bulat.',
                'Baca dua bilangan bulat (masing-masing satu baris), lalu cetak jumlahnya.',
                "a = int(input())\nb = int(input())\n# tulis kodemu di bawah ini\n",
                [["2\n3", '5', false], ["10\n-4", '6', false], ["0\n0", '0', true]]],
            ['medium', 'Jumlah Deret 1 sampai N', 'jumlah-deret', 'Jumlahkan semua bilangan dari 1 sampai N.',
                'Baca N (bilangan bulat positif), cetak 1 + 2 + ... + N.',
                "n = int(input())\n# tulis kodemu di bawah ini\n",
                [['5', '15', false], ['1', '1', false], ['100', '5050', true]]],
            ['medium', 'Faktorial', 'faktorial', 'Hitung faktorial dari sebuah bilangan.',
                'Baca N (0 atau lebih), cetak N! (N faktorial). Ingat 0! = 1.',
                "n = int(input())\n# tulis kodemu di bawah ini\n",
                [['5', '120', false], ['0', '1', false], ['10', '3628800', true]]],
            ['medium', 'Balik Kata', 'balik-kata', 'Balikkan urutan huruf sebuah kata.',
                'Baca satu kata, lalu cetak kata itu dengan urutan huruf terbalik.',
                "kata = input()\n# tulis kodemu di bawah ini\n",
                [['python', 'nohtyp', false], ['aku', 'uka', false], ['a', 'a', true]]],
            ['hard', 'Deteksi Bilangan Prima', 'bilangan-prima', 'Tentukan apakah sebuah bilangan prima.',
                'Baca satu bilangan bulat. Cetak "Prima" atau "Bukan Prima".',
                "n = int(input())\n# tulis kodemu di bawah ini\n",
                [['7', 'Prima', false], ['10', 'Bukan Prima', false], ['1', 'Bukan Prima', true], ['97', 'Prima', true]]],
            ['hard', 'Fibonacci Suku ke-N', 'fibonacci', 'Cari suku ke-N deret Fibonacci.',
                'Baca N (N >= 1). Suku 1 dan 2 bernilai 1. Cetak suku ke-N.',
                "n = int(input())\n# tulis kodemu di bawah ini\n",
                [['1', '1', false], ['6', '8', false], ['20', '6765', true]]],
        ];

        foreach ($exercises as $i => [$level, $title, $slug, $desc, $instr, $starter, $tests]) {
            $exerciseId = DB::table('exercises')->insertGetId([
                'lesson_id' => $lessonIds[$level], 'title' => $title, 'slug' => $slug,
                'description' => $desc, 'instructions' => $instr, 'starter_code' => $starter,
                'difficulty' => $level, 'time_limit_ms' => 10000, 'memory_limit_mb' => 128,
                'sort_order' => $i + 1, 'is_published' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);

            foreach ($tests as $j => [$input, $expected, $hidden]) {
                DB::table('test_cases')->insert([
                    'exercise_id' => $exerciseId, 'input' => $input, 'expected_output' => $expected,
                    'weight' => 1, 'is_hidden' => $hidden, 'is_active' => true, 'sort_order' => $j + 1,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }
    }
}