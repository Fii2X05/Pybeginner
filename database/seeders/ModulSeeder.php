<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class ModulSeeder extends Seeder
{
    /**
     * Isi awal materi belajar Python.
     * Aman dijalankan berulang kali (pakai updateOrCreate berdasarkan slug).
     *
     * Jalankan: php artisan db:seed --class=ModulSeeder
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

        foreach ($this->modules() as $mi => $m) {
            $module = $course->modules()->updateOrCreate(['slug' => $m['slug']], [
                'title' => $m['title'],
                'description' => $m['description'],
                'sort_order' => $mi + 1,
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
    }

    private function modules(): array
    {
        return [
            [
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
            ],
            [
                'slug' => 'variabel-dan-tipe-data',
                'title' => 'Variabel dan Tipe Data',
                'description' => 'Memahami cara menyimpan data ke dalam variabel, tipe data angka (integer & float), teks (string), boolean, serta konversi tipe data dinamis.',
                'lessons' => [
                    [
                        'slug' => 'apa-itu-variabel',
                        'title' => 'Apa itu Variabel?',
                        'content' => <<<'MD'
Variabel adalah tempat menyimpan data yang diberi nama. Di Python, kamu tidak perlu menulis tipe datanya.

```python
nama = "Adelia"
umur = 20
print(nama, umur)
```

## Aturan penamaan

- Boleh berisi huruf, angka, dan garis bawah `_`
- Tidak boleh diawali angka
- Huruf besar dan kecil dibedakan (`nama` berbeda dengan `Nama`)
- Gunakan gaya `snake_case`, misalnya `nama_lengkap`
MD,
                    ],
                    [
                        'slug' => 'tipe-data-dasar',
                        'title' => 'Tipe Data Dasar',
                        'content' => <<<'MD'
Ada empat tipe data dasar yang paling sering dipakai:

| Tipe | Contoh | Keterangan |
|------|--------|------------|
| `int` | `10` | Bilangan bulat |
| `float` | `3.14` | Bilangan desimal |
| `str` | `"halo"` | Teks |
| `bool` | `True` | Benar atau salah |

```python
a = 10
b = 3.14
c = "halo"
d = True
print(type(a), type(b), type(c), type(d))
```

Fungsi `type()` menunjukkan tipe dari sebuah nilai.
MD,
                    ],
                    [
                        'slug' => 'konversi-tipe-data',
                        'title' => 'Konversi Tipe Data',
                        'content' => <<<'MD'
Kadang kamu perlu mengubah satu tipe data ke tipe lain. Gunakan `int()`, `float()`, dan `str()`.

```python
angka_teks = "25"
angka = int(angka_teks)
print(angka + 5)   # 30
print(str(angka) + " tahun")
```

Data dari `input()` selalu bertipe `str`, jadi ubah dulu sebelum dihitung:

```python
umur = int(input("Umur kamu: "))
print("Tahun depan:", umur + 1)
```
MD,
                    ],
                ],
            ],
            [
                'slug' => 'percabangan-logika-kondisional',
                'title' => 'Percabangan & Logika Kondisional',
                'description' => 'Membangun logika keputusan menggunakan if, elif, dan else. Menggunakan operator logika and, or, not.',
                'lessons' => [
                    [
                        'slug' => 'if-else',
                        'title' => 'Percabangan if dan else',
                        'content' => <<<'MD'
Percabangan membuat program bisa mengambil keputusan.

```python
nilai = 80

if nilai >= 75:
    print("Lulus")
else:
    print("Belum lulus")
```

Setelah kondisi, jangan lupa tanda titik dua `:` dan indentasi pada baris di bawahnya.
MD,
                    ],
                    [
                        'slug' => 'elif',
                        'title' => 'Banyak Kondisi dengan elif',
                        'content' => <<<'MD'
Gunakan `elif` untuk memeriksa lebih dari dua kemungkinan.

```python
nilai = 82

if nilai >= 90:
    print("A")
elif nilai >= 80:
    print("B")
elif nilai >= 70:
    print("C")
else:
    print("D")
```

Python memeriksa dari atas ke bawah dan berhenti pada kondisi pertama yang benar.
MD,
                    ],
                    [
                        'slug' => 'operator-logika',
                        'title' => 'Operator Logika',
                        'content' => <<<'MD'
Gabungkan beberapa kondisi dengan `and`, `or`, dan `not`.

```python
umur = 20
punya_ktp = True

if umur >= 17 and punya_ktp:
    print("Boleh mendaftar")

if not punya_ktp:
    print("Urus KTP dulu")
```

- `and`: benar jika **semua** kondisi benar
- `or`: benar jika **salah satu** kondisi benar
- `not`: membalik nilai kebenaran
MD,
                    ],
                ],
            ],
            [
                'slug' => 'perulangan-loops',
                'title' => 'Perulangan (Loops)',
                'description' => 'Otomatisasi tugas berulang dengan for loop, fungsi range(), while loop, serta kontrol perulangan break dan continue.',
                'lessons' => [
                    [
                        'slug' => 'for-dan-range',
                        'title' => 'Perulangan for dan range()',
                        'content' => <<<'MD'
Gunakan `for` saat jumlah pengulangan sudah diketahui.

```python
for i in range(5):
    print(i)   # 0, 1, 2, 3, 4
```

`range(awal, akhir, langkah)` menghasilkan deretan angka. Angka `akhir` tidak ikut.

```python
for i in range(1, 10, 2):
    print(i)   # 1, 3, 5, 7, 9
```
MD,
                    ],
                    [
                        'slug' => 'while',
                        'title' => 'Perulangan while',
                        'content' => <<<'MD'
`while` mengulang selama kondisinya benar.

```python
hitung = 3
while hitung > 0:
    print(hitung)
    hitung -= 1
print("Selesai!")
```

> Pastikan kondisi suatu saat menjadi salah, kalau tidak program akan berulang tanpa henti.
MD,
                    ],
                    [
                        'slug' => 'break-dan-continue',
                        'title' => 'break dan continue',
                        'content' => <<<'MD'
- `break` menghentikan perulangan sepenuhnya
- `continue` melewati sisa isi perulangan dan lanjut ke putaran berikutnya

```python
for i in range(1, 8):
    if i == 3:
        continue   # lewati 3
    if i == 6:
        break      # berhenti di 6
    print(i)       # 1, 2, 4, 5
```
MD,
                    ],
                ],
            ],
            [
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
            ],
            [
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
            ],
        ];
    }
}
