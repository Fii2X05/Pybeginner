<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul6Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 6.
     *
     * Jalankan: php artisan db:seed --class=Modul6Seeder
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
            'sort_order' => 6,
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
            'slug' => 'struktur-data-dasar',
            'title' => 'Struktur Data Dasar',
            'description' => 'Mengelola kumpulan data terstruktur: indexing & slicing pada List, ketetapan Tuple, dan pasangan key-value Dictionary.',
            'lessons' => [
                [
                    'slug' => 'list',
                    'title' => 'List',
                    'content' => <<<'MD'
List menyimpan banyak nilai dalam satu variabel, dan isinya bisa diubah.

```python
buah = ["apel", "jeruk", "mangga"]
buah.append("pisang")
print(buah[0])      # apel (index mulai dari 0)
print(buah[-1])     # pisang (index negatif dari belakang)
print(buah[1:3])    # ['jeruk', 'mangga'] (slicing)
print(len(buah))    # 4
```
MD,
                ],
                [
                    'slug' => 'tuple',
                    'title' => 'Tuple',
                    'content' => <<<'MD'
Tuple mirip list, tetapi **tidak bisa diubah** setelah dibuat.

```python
koordinat = (10, 20)
x, y = koordinat
print(x, y)

# koordinat[0] = 5   # error: tuple tidak bisa diubah
```

Pakai tuple untuk data yang memang tidak boleh berubah, seperti koordinat atau tanggal.
MD,
                ],
                [
                    'slug' => 'dictionary',
                    'title' => 'Dictionary',
                    'content' => <<<'MD'
Dictionary menyimpan pasangan **key** dan **value**.

```python
mahasiswa = {
    "nama": "Adelia",
    "umur": 20,
}

print(mahasiswa["nama"])
mahasiswa["jurusan"] = "SIB"

for key, value in mahasiswa.items():
    print(key, "=", value)
```

Gunakan `mahasiswa.get("alamat", "-")` untuk mengambil nilai dengan aman jika key belum tentu ada.
MD,
                ],
            ],
        ];
    }
}
