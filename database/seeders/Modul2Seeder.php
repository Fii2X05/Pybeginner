<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul2Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 2.
     *
     * Jalankan: php artisan db:seed --class=Modul2Seeder
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
            'sort_order' => 2,
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
            'slug' => 'variabel-dan-tipe-data',
            'title' => 'Variabel dan Tipe Data',
            'description' => 'Memahami cara menyimpan data ke dalam variabel, tipe data angka (integer & float), teks (string), boolean, serta konversi tipe data dinamis.',
            'lessons' => [
                [
                    'slug' => 'variabel-aturan-penamaan',
                    'title' => 'Variabel & Aturan Penamaan',
                    'content' => <<<'MD'
Variabel adalah wadah penyimpanan di memori untuk menyimpan nilai atau data. Di Python, variabel dibuat secara otomatis saat pertama kali diberi nilai menggunakan tanda sama dengan (`=`).

```python
nama = "Adelia"
umur = 20
ipk = 3.85
print(nama, umur, ipk)
```

## Aturan Penamaan Variabel (PEP 8)

1. Hanya boleh menggunakan **huruf**, **angka**, dan **garis bawah** (`_`).
2. **Wajib diawali huruf atau garis bawah**, tidak boleh diawali angka (`1nama` ❌, `nama1` ✔️).
3. Bersifat **case-sensitive** (`umur` berbeda dengan `Umur`).
4. Tidak boleh menggunakan kata kunci bawaan Python (reserved keywords) seperti `class`, `def`, `if`, dll.
5. Direkomendasikan menggunakan gaya **`snake_case`** (misal: `nama_lengkap`, `total_harga`).
MD,
                ],
                [
                    'slug' => 'integer-dan-float',
                    'title' => 'Integer & Float',
                    'content' => <<<'MD'
Di Python, angka dibagi menjadi dua jenis utama: **Integer (`int`)** untuk bilangan bulat tanpa koma, dan **Float (`float`)** untuk bilangan riil yang memiliki titik desimal.

```python
panjang = 10       # int
lebar = 4.5        # float
luas = panjang * lebar
print("Luas:", luas)
```

## Operator Aritmatika

| Operator | Operasi | Contoh | Hasil |
|---|---|---|---|
| `+` | Penjumlahan | `10 + 5` | `15` |
| `-` | Pengurangan | `10 - 4` | `6` |
| `*` | Perkalian | `3 * 4` | `12` |
| `/` | Pembagian (Float) | `10 / 4` | `2.5` |
| `//` | Pembagian Bulat | `10 // 4` | `2` |
| `%` | Modulus (Sisa Bagi) | `10 % 4` | `2` |
| `**` | Perpangkatan | `2 ** 3` | `8` |

> Operator pembagian `/` di Python selalu menghasilkan float.
MD,
                ],
                [
                    'slug' => 'string-operasi-dasar-string',
                    'title' => 'String & Operasi Dasar String',
                    'content' => <<<'MD'
String (`str`) adalah tipe data untuk menyimpan teks, diapit kutip tunggal (`'...'`), kutip ganda (`"..."`), atau tiga tanda kutip (`"""..."""` untuk multiline).

## Operasi Dasar String

```python
teks = "Python"
print("Panjang string:", len(teks))       # 6
print("Penggabungan   :", teks + " Keren") # Python Keren
print("Pengulangan    :", "=" * 10)       # ==========
```

## Indexing & Slicing

Indeks Python dimulai dari 0:

```python
bahasa = "PYTHON"
print(bahasa[0])    # P
print(bahasa[-1])   # N
print(bahasa[0:3])  # PYT (dari indeks 0 sampai sebelum 3)
```

## Method Populer

- `.lower()` : Mengubah ke huruf kecil
- `.upper()` : Mengubah ke huruf kapital
- `.strip()` : Menghapus spasi di awal & akhir
- `.replace(lama, baru)` : Mengganti bagian teks
MD,
                ],
                [
                    'slug' => 'boolean-operator-perbandingan',
                    'title' => 'Boolean & Operator Perbandingan',
                    'content' => <<<'MD'
Tipe data **Boolean (`bool`)** hanya bernilai `True` atau `False`. Digunakan sebagai dasar logika keputusan percabangan program.

## Operator Perbandingan

| Operator | Arti | Contoh | Hasil |
|---|---|---|---|
| `==` | Sama dengan | `5 == 5` | `True` |
| `!=` | Tidak sama dengan | `5 != 3` | `True` |
| `>` | Lebih besar dari | `10 > 5` | `True` |
| `<` | Lebih kecil dari | `10 < 5` | `False` |
| `>=` | Lebih besar atau sama | `10 >= 10` | `True` |
| `<=` | Lebih kecil atau sama | `7 <= 5` | `False` |

```python
nilai = 80
kkm = 75
lulus = nilai >= kkm
print("Status Lulus:", lulus)  # True
```

> Jangan tertukar antara `=` (assignment nilai) dengan `==` (perbandingan kesamaan).
MD,
                ],
                [
                    'slug' => 'mengecek-tipe-dengan-type',
                    'title' => 'Mengecek Tipe dengan type()',
                    'content' => <<<'MD'
Fungsi `type()` digunakan untuk mengetahui jenis tipe data dari sebuah nilai atau variabel saat program berjalan (runtime).

```python
x = 100
y = 3.14
nama = "Adelia"
aktif = True

print(type(x))     # <class 'int'>
print(type(y))     # <class 'float'>
print(type(nama))  # <class 'str'>
print(type(aktif)) # <class 'bool'>
```

## Menggunakan isinstance()

Untuk memeriksa tipe data di dalam percabangan, fungsi `isinstance()` lebih disarankan:

```python
umur = 20
if isinstance(umur, int):
    print("Variabel umur adalah integer yang valid.")
```
MD,
                ],
                [
                    'slug' => 'konversi-tipe-data-casting',
                    'title' => 'Konversi Tipe Data (Casting)',
                    'content' => <<<'MD'
Konversi tipe data (type casting) adalah proses mengubah satu tipe data ke tipe data lainnya.

## Fungsi Casting Utama

- `int()` : Mengubah float (dibulatkan ke bawah) atau string angka ke integer.
- `float()` : Mengubah integer atau string angka ke desimal.
- `str()` : Mengubah data angka atau boolean ke bentuk teks.
- `bool()` : Mengubah nilai ke boolean (nilai `0`, `""`, `None` menjadi `False`, lainnya `True`).

```python
angka_str = "25"
angka_int = int(angka_str)
print(angka_int + 5)   # 30

desimal = float("3.14")
print(desimal)         # 3.14
```

## Menangani Input Pengguna

Data dari fungsi `input()` selalu bertipe `str`. Selalu konversi terlebih dahulu sebelum menghitung:

```python
umur = int(input("Masukkan umurmu: "))
print("Tahun depan umurmu:", umur + 1)
```
MD,
                ],
            ],
        ];
    }
}
