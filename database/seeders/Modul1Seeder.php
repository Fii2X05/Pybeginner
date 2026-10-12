<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul1Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 1.
     *
     * Jalankan: php artisan db:seed --class=Modul1Seeder
     */
    public function run(): void
    {
        $course = Course::updateOrCreate(['slug' => 'python-dasar'], [
            'title' => 'Python Dasar',
            'description' => 'Belajar Python dari nol: sintaks, variabel, percabangan, perulangan, fungsi, sampai struktur data.',
            'level' => 'beginner',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $m = $this->module();

        $module = $course->modules()->updateOrCreate(['slug' => $m['slug']], [
            'title' => $m['title'],
            'description' => $m['description'],
            'sort_order' => 1,
            'is_published' => true,
        ]);

        foreach ($m['lessons'] as $li => $l) {
            $module->lessons()->updateOrCreate(['slug' => $l['slug']], [
                'title' => $l['title'],
                'content' => $l['content'],
                'sort_order' => $li + 1,
                'is_published' => true,
            ]);
        }
    }

    private function module(): array
    {
        return [
            'slug' => 'dasar-python-sintaks-awal',
            'title' => 'Dasar Python & Sintaks Awal',
            'description' => 'Mengenal struktur program Python, fungsi print(), komentar kode, dan aturan indentasi yang menjadi ciri khas Python.',
            'lessons' => [
                [
                    'slug' => 'program-python-pertama',
                    'title' => 'Program Python Pertamamu',
                    'content' => <<<'MD'
Python adalah bahasa pemrograman yang mudah dibaca dan cocok untuk pemula. Program paling sederhana hanya satu baris:

```python
print("Halo, Dunia!")
```

Fungsi `print()` menampilkan teks ke layar. Teks ditulis di antara tanda kutip, bisa kutip dua (`"..."`) atau kutip satu (`'...'`).

## Mencetak beberapa nilai

```python
print("Nama:", "Adelia")
print("Umur:", 20)
```

Pisahkan nilai dengan koma, dan Python akan menambahkan spasi di antaranya.
MD,
                ],
                [
                    'slug' => 'komentar-kode',
                    'title' => 'Komentar Kode',
                    'content' => <<<'MD'
Komentar adalah catatan untuk manusia. Python mengabaikannya saat program dijalankan. Awali komentar dengan tanda `#`.

```python
# Ini komentar satu baris
print("Belajar Python")  # komentar di akhir baris
```

Gunakan komentar untuk menjelaskan **alasan** di balik kode, bukan sekadar mengulang apa yang sudah jelas terbaca.
MD,
                ],
                [
                    'slug' => 'indentasi',
                    'title' => 'Aturan Indentasi',
                    'content' => <<<'MD'
Berbeda dengan banyak bahasa lain, Python memakai **indentasi** (spasi di awal baris) untuk menandai blok kode. Biasanya dipakai 4 spasi.

```python
if True:
    print("Baris ini menjorok ke dalam")
    print("Masih di blok yang sama")
print("Baris ini di luar blok")
```

> Indentasi yang tidak konsisten akan menyebabkan `IndentationError`. Pilih satu gaya dan konsisten.
MD,
                ],
            ],
        ];
    }
}
