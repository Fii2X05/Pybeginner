<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul3Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 3.
     *
     * Jalankan: php artisan db:seed --class=Modul3Seeder
     */
    public function run(): void
    {
        $course = Course::firstOrCreate(['slug' => 'python-dasar'], [
            'title' => 'Python Dasar',
            'description' => 'Belajar Python dari nol: sintaks, variabel, percabangan, perulangan, fungsi, sampai struktur data.',
            'level' => 'beginner',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $m = $this->module();

        $module = $course->modules()->firstOrCreate(['slug' => $m['slug']], [
            'title' => $m['title'],
            'description' => $m['description'],
            'sort_order' => 3,
            'is_published' => true,
        ]);

        foreach ($m['lessons'] as $li => $l) {
            $module->lessons()->firstOrCreate(['slug' => $l['slug']], [
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
            'slug' => 'percabangan-logika-kondisional',
            'title' => 'Percabangan & Logika Kondisional',
            'description' => 'Membangun logika keputusan menggunakan if, elif, dan else. Menggunakan operator logika and, or, not.',
            'lessons' => [
                [
                    'slug' => 'statement-if',
                    'title' => 'Statement if',
                    'content' => <<<'MD'
Statement `if` adalah struktur percabangan paling dasar di Python. Kode di dalam blok `if` hanya akan dijalankan jika kondisinya bernilai `True`.

```python
suhu = 35

if suhu > 30:
    print("Suhu panas!")
    print("Nyalakan kipas angin.")
```

## Aturan Penulisan
- Wajib diakhiri tanda titik dua `:` di akhir baris kondisi.
- Blok kode di dalamnya wajib memiliki indentasi 4 spasi.
MD,
                ],
                [
                    'slug' => 'if-else',
                    'title' => 'Percabangan if-else',
                    'content' => <<<'MD'
Percabangan `if-else` menyediakan dua jalur keputusan: satu jalur ketika kondisi bernilai `True`, dan satu jalur alternatif ketika bernilai `False`.

```python
nilai = 80
kkm = 75

if nilai >= kkm:
    print("Selamat, Anda Lulus!")
else:
    print("Mohon maaf, Anda belum lulus.")
```

## Ternary Operator (One-Liner)

Python mendukung bentuk percabangan ringkas satu baris:

```python
status = "Lulus" if nilai >= 75 else "Remedial"
print(status)
```
MD,
                ],
                [
                    'slug' => 'if-elif-else',
                    'title' => 'Percabangan if-elif-else',
                    'content' => <<<'MD'
Gunakan `elif` (else if) untuk menguji lebih dari dua kemungkinan secara bertingkat. Python memeriksa dari atas ke bawah dan berhenti begitu menemukan kondisi pertama yang bernilai `True`.

```python
skor = 85

if skor >= 85:
    grade = "A"
elif skor >= 75:
    grade = "B"
elif skor >= 60:
    grade = "C"
else:
    grade = "D"

print("Grade Anda:", grade)
```

> Selalu urutkan kondisi dari yang paling spesifik/tertinggi ke yang paling umum.
MD,
                ],
                [
                    'slug' => 'operator-logika',
                    'title' => 'Operator Logika (and, or, not)',
                    'content' => <<<'MD'
Operator logika menggabungkan beberapa syarat boolean:

- `and` : Bernilai `True` jika **semua** kondisi bernilai `True`.
- `or` : Bernilai `True` jika **minimal salah satu** kondisi bernilai `True`.
- `not` : Membalikkan nilai kebenaran (`True` jadi `False`, dan sebaliknya).

```python
umur = 20
punya_sim = True
bebas_tilang = True

if umur >= 17 and punya_sim and not bebas_tilang:
    print("Boleh mengemudikan kendaraan bermotor.")
```

## Chained Comparison

Di Python kamu bisa merangkai perbandingan rentang:

```python
usia = 22
if 18 <= usia <= 30:
    print("Usia produktif.")
```
MD,
                ],
                [
                    'slug' => 'kondisi-bersarang-nested-if',
                    'title' => 'Kondisi Bersarang (Nested if)',
                    'content' => <<<'MD'
Kondisi bersarang (*nested if*) adalah penulisan statement `if` di dalam blok `if` lainnya. Pola ini sangat berguna untuk memvalidasi kondisi bertahap.

```python
pin_benar = True
saldo = 500000
tarik = 200000

if pin_benar:
    print("Autentikasi berhasil.")
    if tarik <= saldo:
        saldo -= tarik
        print("Penarikan berhasil! Sisa:", saldo)
    else:
        print("Saldo tidak cukup.")
else:
    print("PIN salah.")
```
MD,
                ],
            ],
        ];
    }
}
