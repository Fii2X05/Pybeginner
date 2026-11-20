<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul4Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 4.
     *
     * Jalankan: php artisan db:seed --class=Modul4Seeder
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
            'sort_order' => 4,
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
            'slug' => 'perulangan-loops',
            'title' => 'Perulangan (Loops)',
            'description' => 'Otomatisasi tugas berulang dengan for loop, fungsi range(), while loop, serta kontrol perulangan break dan continue.',
            'lessons' => [
                [
                    'slug' => 'for-loop-dan-range',
                    'title' => 'for loop & range()',
                    'content' => <<<'MD'
`for loop` di Python digunakan untuk perulangan dengan jumlah hitungan yang sudah ditentukan atau menelusuri data berurutan.

```python
for i in range(5):
    print("Iterasi ke-", i)
```

## Variasi Parameter range()

- `range(stop)` : mulai dari 0 sampai sebelum stop.
- `range(start, stop)` : mulai dari start sampai sebelum stop.
- `range(start, stop, step)` : melompat sebesar interval step.

```python
for angka in range(1, 10, 2):
    print(angka)  # 1, 3, 5, 7, 9
```
MD,
                ],
                [
                    'slug' => 'iterasi-string-dan-list',
                    'title' => 'Iterasi String dan List',
                    'content' => <<<'MD'
Tipe data teks (string) dan kumpulan data (list) dapat diiterasi langsung tanpa memerlukan indeks numerik manual:

```python
for huruf in "PYTHON":
    print(huruf, end="-")
print()

bahasa = ["Python", "JavaScript", "Rust"]
for b in bahasa:
    print("Belajar", b)
```

Gunakan fungsi `enumerate()` jika memerlukan nomor urut dan nilai secara bersamaan:

```python
for nomor, nama in enumerate(bahasa, start=1):
    print(f"{nomor}. {nama}")
```
MD,
                ],
                [
                    'slug' => 'while-loop',
                    'title' => 'while loop',
                    'content' => <<<'MD'
`while loop` mengulang sekumpulan kode selama kondisi boolean bernilai `True`.

```python
hitung = 1
while hitung <= 5:
    print("Antrean:", hitung)
    hitung += 1  # Pembaruan nilai wajib agar tidak infinite loop
```

> Hati-hati dengan *infinite loop*. Pastikan kondisi kelak bernilai `False` atau dihentikan dengan `break`.
MD,
                ],
                [
                    'slug' => 'break-dan-continue',
                    'title' => 'break & continue',
                    'content' => <<<'MD'
Modifikasi alur perulangan dengan dua kata kunci kontrol:

- `break` : Menghentikan seluruh perulangan seketika dan langsung keluar dari loop.
- `continue` : Melompati sisa kode pada putaran saat ini dan lanjut ke iterasi berikutnya.

```python
for angka in range(1, 10):
    if angka == 4:
        continue  # lewati angka 4
    if angka == 8:
        break     # berhenti total di angka 8
    print(angka)
```
MD,
                ],
                [
                    'slug' => 'nested-loop',
                    'title' => 'Nested Loop (Perulangan Bersarang)',
                    'content' => <<<'MD'
Perulangan bersarang adalah penulisan loop di dalam loop lainnya. Sangat umum digunakan untuk tabel matriks 2D atau pola grafis.

```python
for baris in range(1, 4):
    for kolom in range(baris):
        print("⭐", end=" ")
    print()
```

Setiap 1 putaran outer loop, inner loop akan berputar penuh dari awal sampai akhir.
MD,
                ],
                [
                    'slug' => 'pola-perulangan-umum',
                    'title' => 'Pola Perulangan Umum',
                    'content' => <<<'MD'
Pola algoritma perulangan yang paling sering digunakan dalam problem solving:

1. **Akumulator** : Menjumlahkan data (`total += x`)
2. **Pencari Max/Min** : Mencari nilai ekstrem
3. **Counter** : Menghitung kemunculan kondisi tertentu

```python
nilai_list = [70, 85, 90, 65, 80]
total = 0
jumlah_lulus = 0

for n in nilai_list:
    total += n
    if n >= 75:
        jumlah_lulus += 1

rata_rata = total / len(nilai_list)
print("Rata-rata:", rata_rata)
print("Jumlah Lulus:", jumlah_lulus)
```
MD,
                ],
            ],
        ];
    }
}
