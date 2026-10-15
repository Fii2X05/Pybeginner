<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul5Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 5.
     *
     * Jalankan: php artisan db:seed --class=Modul5Seeder
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
            'sort_order' => 5,
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
            'slug' => 'fungsi-modularitas-kode',
            'title' => 'Fungsi & Modularitas Kode',
            'description' => 'Membuat fungsi mandiri dengan def, parameter, argumen default, return value, dan memahami konsep local vs global scope.',
            'lessons' => [
                [
                    'slug' => 'membuat-fungsi',
                    'title' => 'Membuat Fungsi dengan def',
                    'content' => <<<'MD'
Fungsi adalah potongan kode bernama yang bisa dipakai berulang kali.

```python
def sapa(nama):
    print("Halo,", nama)

sapa("Dinda")
sapa("Adelia")
```

`nama` disebut **parameter**, sedangkan `"Dinda"` yang dikirim saat memanggil disebut **argumen**.
MD,
                ],
                [
                    'slug' => 'return-dan-default',
                    'title' => 'Return Value dan Argumen Default',
                    'content' => <<<'MD'
Gunakan `return` untuk mengembalikan hasil dari fungsi.

```python
def luas_persegi(sisi):
    return sisi * sisi

hasil = luas_persegi(4)
print(hasil)   # 16
```

Parameter bisa punya nilai bawaan:

```python
def sapa(nama, sapaan="Halo"):
    return sapaan + ", " + nama

print(sapa("Dinda"))
print(sapa("Dinda", "Selamat pagi"))
```
MD,
                ],
                [
                    'slug' => 'scope-variabel',
                    'title' => 'Scope Lokal dan Global',
                    'content' => <<<'MD'
Variabel yang dibuat **di dalam** fungsi hanya hidup di fungsi itu (lokal). Variabel di luar fungsi bersifat global.

```python
pesan = "global"

def contoh():
    pesan = "lokal"
    print(pesan)   # lokal

contoh()
print(pesan)       # global
```

Sebisa mungkin hindari mengubah variabel global dari dalam fungsi. Kirim lewat parameter dan kembalikan lewat `return`.
MD,
                ],
            ],
        ];
    }
}
