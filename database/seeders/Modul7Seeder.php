<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class Modul7Seeder extends Seeder
{
    /**
     * Jalankan seeder Modul 7.
     *
     * Jalankan: php artisan db:seed --class=Modul7Seeder
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
            'sort_order' => 7,
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
            'slug' => 'pengantar-pandas-numpy',
            'title' => 'Pengantar Pandas & NumPy',
            'description' => 'Pengenalan library analisis data Python: membuat dan memanipulasi array NumPy, serta bekerja dengan DataFrame dan Series pada Pandas.',
            'lessons' => [
                [
                    'slug' => 'apa-itu-numpy',
                    'title' => 'Apa itu NumPy?',
                    'content' => <<<'MD'
**NumPy** (Numerical Python) adalah library fundamental untuk komputasi ilmiah di Python. NumPy menyediakan struktur data `ndarray` (N-dimensional array) yang jauh lebih cepat dan efisien dari list Python biasa untuk operasi numerik.

## Mengapa NumPy?

```python
import time

# Perbandingan kecepatan
ukuran = 1_000_000

# Cara Python biasa (list)
mulai = time.time()
hasil = [x**2 for x in range(ukuran)]
print(f"List Python: {time.time() - mulai:.3f} detik")

# Cara NumPy
import numpy as np
mulai = time.time()
arr = np.arange(ukuran)
hasil = arr**2
print(f"NumPy: {time.time() - mulai:.3f} detik")
# NumPy bisa 10-100x lebih cepat!
```

## Instalasi & Import

```python
# Instalasi (jalankan di terminal):
# pip install numpy

# Import dengan alias standar
import numpy as np
```

## Membuat Array Sederhana

```python
import numpy as np

# Dari list Python
arr1d = np.array([1, 2, 3, 4, 5])
print(arr1d)          # [1 2 3 4 5]
print(arr1d.dtype)    # int64
print(arr1d.shape)    # (5,)
print(arr1d.ndim)     # 1

# Array 2D (matriks)
arr2d = np.array([[1, 2, 3], [4, 5, 6]])
print(arr2d)
print(arr2d.shape)    # (2, 3) — 2 baris, 3 kolom
```

## Fungsi Pembuat Array

```python
import numpy as np

print(np.zeros(5))           # [0. 0. 0. 0. 0.]
print(np.ones((2, 3)))       # [[1. 1. 1.] [1. 1. 1.]]
print(np.arange(0, 10, 2))   # [0 2 4 6 8]
print(np.linspace(0, 1, 5))  # [0.   0.25 0.5  0.75 1. ]
print(np.eye(3))             # matriks identitas 3x3
```

> **Tahukah kamu?** NumPy adalah fondasi dari hampir semua library data science Python: Pandas, SciPy, scikit-learn, TensorFlow semuanya menggunakan NumPy di balik layar!

## Ringkasan

- NumPy menyediakan `ndarray` — array N-dimensi yang efisien.
- Jauh lebih cepat dari list Python untuk operasi numerik besar.
- Import dengan `import numpy as np` (konvensi standar).
- Attribute penting: `.dtype`, `.shape`, `.ndim`, `.size`.
MD,
                ],
                [
                    'slug' => 'membuat-dan-memanipulasi-array',
                    'title' => 'Membuat & Memanipulasi Array',
                    'content' => <<<'MD'
Setelah mengenal NumPy, mari pelajari cara membuat berbagai jenis array dan melakukan manipulasi dasar.

## Membuat Array dengan Berbagai Cara

```python
import numpy as np

# Dari list Python
a = np.array([1, 2, 3, 4, 5])

# Array acak
np.random.seed(42)   # agar hasil dapat direproduksi
acak = np.random.randint(0, 100, size=6)
print(acak)   # [51 92 14 71 60 20]

acak2d = np.random.rand(3, 4)   # float acak antara 0 dan 1
print(acak2d.shape)   # (3, 4)

# Array dengan nilai spesifik
penuh = np.full((3, 3), 7)   # 3x3 berisi angka 7
print(penuh)
```

## Indexing & Slicing Array

```python
import numpy as np

arr = np.array([10, 20, 30, 40, 50])

print(arr[0])      # 10
print(arr[-1])     # 50
print(arr[1:4])    # [20 30 40]
print(arr[::2])    # [10 30 50]

# 2D Array
matriks = np.array([[1, 2, 3], [4, 5, 6], [7, 8, 9]])

print(matriks[1, 2])      # 6 — baris 1, kolom 2
print(matriks[0, :])      # [1 2 3] — seluruh baris 0
print(matriks[:, 1])      # [2 5 8] — seluruh kolom 1
print(matriks[0:2, 1:3])  # [[2 3] [5 6]] — sub-matriks
```

## Mengubah Bentuk Array

```python
import numpy as np

arr = np.arange(12)
print(arr)   # [ 0  1  2  3  4  5  6  7  8  9 10 11]

# Ubah bentuk menjadi 3x4
matriks = arr.reshape(3, 4)
print(matriks)
# [[ 0  1  2  3]
#  [ 4  5  6  7]
#  [ 8  9 10 11]]

# Ratakan kembali
print(matriks.flatten())   # [ 0  1  2  ... 11]

# Transpose
print(matriks.T.shape)     # (4, 3)
```

> **Tips:** Operasi `reshape()` tidak menyalin data — hanya mengubah cara melihatnya. Ini membuat reshape sangat efisien untuk array besar.

## Ringkasan

- `np.random.randint()`, `np.random.rand()`, `np.full()` untuk array khusus.
- Indexing 2D: `arr[baris, kolom]`.
- Slicing 2D: `arr[baris_slice, kolom_slice]`.
- `.reshape()` mengubah bentuk; `.flatten()` meratakan; `.T` untuk transpose.
MD,
                ],
                [
                    'slug' => 'operasi-array-numpy',
                    'title' => 'Operasi Array NumPy',
                    'content' => <<<'MD'
Salah satu kekuatan terbesar NumPy adalah **vectorized operation** — operasi yang diterapkan ke seluruh elemen array sekaligus tanpa perlu loop eksplisit.

## Operasi Aritmatika

```python
import numpy as np

a = np.array([1, 2, 3, 4, 5])
b = np.array([10, 20, 30, 40, 50])

print(a + b)      # [11 22 33 44 55]
print(b - a)      # [ 9 18 27 36 45]
print(a * b)      # [ 10  40  90 160 250]
print(b / a)      # [10. 10. 10. 10. 10.]
print(a ** 2)     # [ 1  4  9 16 25]

# Operasi dengan scalar
print(a * 3)      # [ 3  6  9 12 15]
print(a + 100)    # [101 102 103 104 105]
```

## Broadcasting

NumPy bisa melakukan operasi antara array dengan bentuk berbeda secara cerdas:

```python
import numpy as np

matriks = np.array([[1, 2, 3], [4, 5, 6], [7, 8, 9]])
vektor = np.array([10, 20, 30])

# Tambahkan vektor ke setiap baris matriks
print(matriks + vektor)
# [[11 22 33]
#  [14 25 36]
#  [17 28 39]]
```

## Fungsi Matematika

```python
import numpy as np

data = np.array([4, 9, 16, 25, 36])

print(np.sqrt(data))     # [2. 3. 4. 5. 6.] — akar kuadrat
print(np.log(data))      # logaritma natural
print(np.abs([-3, -1, 2]))   # [3 1 2] — nilai absolut

# Agregasi
nilai = np.array([78, 85, 92, 67, 90, 73])
print(f"Mean: {np.mean(nilai):.1f}")    # 80.8
print(f"Std:  {np.std(nilai):.1f}")     # 8.5
print(f"Min:  {np.min(nilai)}")         # 67
print(f"Max:  {np.max(nilai)}")         # 92
print(f"Sum:  {np.sum(nilai)}")         # 485
```

## Boolean Indexing (Filtering)

```python
import numpy as np

nilai = np.array([78, 85, 92, 67, 90, 73])

# Cari yang di atas rata-rata
rata = np.mean(nilai)
di_atas = nilai[nilai > rata]
print(di_atas)   # [85 92 90]

# Hitung yang lulus (>= 75)
print(np.sum(nilai >= 75))   # 4
```

> **Tahukah kamu?** Vectorized operation NumPy diimplementasikan dalam C, sehingga jauh lebih cepat dari loop Python. Untuk array besar, gunakan selalu operasi NumPy, bukan loop.

## Ringkasan

- Operasi aritmatika diterapkan ke setiap elemen secara otomatis.
- Broadcasting: operasi antara array berbeda bentuk (tapi kompatibel).
- `np.sqrt()`, `np.mean()`, `np.std()`, dll. — fungsi matematika vectorized.
- Boolean indexing: `arr[arr > nilai]` — filter elemen secara efisien.
MD,
                ],
                [
                    'slug' => 'series-pandas',
                    'title' => 'Series Pandas',
                    'content' => <<<'MD'
**Pandas** adalah library Python untuk analisis dan manipulasi data. Dua struktur data utamanya adalah **Series** (data 1D) dan **DataFrame** (data 2D mirip tabel).

## Instalasi & Import

```python
# pip install pandas

import pandas as pd
import numpy as np
```

## Membuat Series

Series adalah array 1D berlabel — seperti kolom di spreadsheet:

```python
import pandas as pd

# Dari list
s1 = pd.Series([10, 20, 30, 40, 50])
print(s1)
# 0    10
# 1    20
# 2    30
# 3    40
# 4    50
# dtype: int64

# Dengan index kustom
nilai = pd.Series(
    [85, 92, 78, 90],
    index=["Adelia", "Budi", "Dinda", "Eko"]
)
print(nilai)
# Adelia    85
# Budi      92
# Dinda     78
# Eko       90
# dtype: int64
```

## Mengakses Data Series

```python
import pandas as pd

nilai = pd.Series([85, 92, 78, 90], index=["Adelia", "Budi", "Dinda", "Eko"])

# Akses by label
print(nilai["Budi"])      # 92
print(nilai[["Adelia", "Eko"]])   # multi-label

# Akses by posisi
print(nilai.iloc[0])      # 85 (index posisi 0)
print(nilai.iloc[1:3])    # slice berdasarkan posisi
```

## Operasi pada Series

```python
import pandas as pd

nilai = pd.Series([85, 92, 78, 90, 67], index=["A", "B", "C", "D", "E"])

print(nilai.mean())     # 82.4
print(nilai.max())      # 92
print(nilai.min())      # 67
print(nilai.std())      # 9.66...
print(nilai.describe()) # ringkasan statistik lengkap

# Filter
lulus = nilai[nilai >= 80]
print(lulus)
# A    85
# B    92
# D    90
# dtype: int64

# Operasi aritmatika
print(nilai + 5)    # tambah 5 ke semua elemen
```

> **Analogi:** Jika NumPy array seperti kolom angka biasa, Series Pandas seperti kolom dalam spreadsheet Excel — punya label (nama baris) dan bisa diakses dengan nama atau posisi.

## Ringkasan

- `pd.Series(data, index=...)` membuat Series dengan index kustom.
- Akses by label: `s["nama"]`; by posisi: `s.iloc[n]`.
- `.mean()`, `.max()`, `.min()`, `.describe()` untuk statistik ringkas.
- Boolean indexing: `s[s > nilai]` untuk filter data.
MD,
                ],
                [
                    'slug' => 'dataframe-pandas',
                    'title' => 'DataFrame Pandas',
                    'content' => <<<'MD'
**DataFrame** adalah struktur data 2D Pandas — seperti tabel spreadsheet atau tabel database dengan baris dan kolom berlabel. Ini adalah struktur yang paling sering digunakan dalam analisis data.

## Membuat DataFrame

```python
import pandas as pd

# Dari dictionary
data = {
    "nama":  ["Adelia", "Budi", "Dinda", "Eko", "Fani"],
    "nilai": [85, 72, 91, 65, 88],
    "kelas": ["A", "B", "A", "B", "A"],
    "lulus": [True, True, True, False, True],
}

df = pd.DataFrame(data)
print(df)
```

**Output:**
```
     nama  nilai kelas  lulus
0   Adelia     85     A   True
1     Budi     72     B   True
2    Dinda     91     A   True
3      Eko     65     B  False
4     Fani     88     A   True
```

## Mengakses Data DataFrame

```python
# Akses kolom
print(df["nama"])          # Series
print(df[["nama", "nilai"]])  # Sub-DataFrame

# Akses baris by posisi (iloc)
print(df.iloc[0])          # baris pertama
print(df.iloc[1:3])        # baris 1 dan 2

# Akses baris by label (loc)
print(df.loc[0])           # baris index 0

# Info umum
print(df.shape)            # (5, 4)
print(df.dtypes)           # tipe data tiap kolom
print(df.head(3))          # 3 baris pertama
print(df.tail(2))          # 2 baris terakhir
print(df.info())           # ringkasan lengkap
print(df.describe())       # statistik kolom numerik
```

## Menambah & Mengubah Kolom

```python
import pandas as pd

df = pd.DataFrame({
    "nama": ["Adelia", "Budi", "Dinda"],
    "nilai": [85, 72, 91]
})

# Tambah kolom baru
df["grade"] = df["nilai"].apply(
    lambda n: "A" if n >= 90 else ("B" if n >= 80 else "C")
)

df["lulus"] = df["nilai"] >= 75

print(df)
```

**Output:**
```
     nama  nilai grade  lulus
0   Adelia     85     B   True
1     Budi     72     C  False
2    Dinda     91     A   True
```

> **Tips:** `.apply(fungsi)` menerapkan fungsi ke setiap elemen kolom — sangat berguna untuk transformasi data yang lebih kompleks dari operasi aritmatika biasa.

## Ringkasan

- DataFrame: tabel 2D dengan baris dan kolom berlabel.
- Akses kolom: `df["kolom"]`; baris by posisi: `df.iloc[n]`.
- `.head()`, `.tail()`, `.info()`, `.describe()` untuk eksplorasi awal.
- Tambah kolom baru dengan assignment atau `.apply()`.
MD,
                ],
                [
                    'slug' => 'membaca-dan-memfilter-data',
                    'title' => 'Membaca & Memfilter Data',
                    'content' => <<<'MD'
Pada materi terakhir modul ini, kita akan belajar membaca data dari file eksternal dan melakukan filtering serta agregasi — kemampuan inti analisis data dengan Pandas.

## Membaca File CSV

```python
import pandas as pd

# Membaca CSV dari file
# df = pd.read_csv("data_siswa.csv")

# Untuk latihan, kita buat DataFrame langsung
df = pd.DataFrame({
    "nama": ["Adelia", "Budi", "Dinda", "Eko", "Fani", "Galuh"],
    "nilai": [85, 72, 91, 65, 88, 79],
    "kelas": ["A", "B", "A", "B", "A", "B"],
    "kota":  ["Bandung", "Jakarta", "Bandung", "Surabaya", "Jakarta", "Bandung"],
})

# Menyimpan ke CSV
df.to_csv("output.csv", index=False)
print("DataFrame berhasil dibuat!")
print(df)
```

## Filtering Data

```python
import pandas as pd

df = pd.DataFrame({
    "nama":  ["Adelia", "Budi", "Dinda", "Eko", "Fani", "Galuh"],
    "nilai": [85, 72, 91, 65, 88, 79],
    "kelas": ["A", "B", "A", "B", "A", "B"],
})

# Filter satu kondisi
lulus = df[df["nilai"] >= 75]
print("Siswa Lulus:")
print(lulus)

# Filter multi-kondisi
kelas_a_bagus = df[(df["kelas"] == "A") & (df["nilai"] >= 85)]
print("\nKelas A dengan nilai >= 85:")
print(kelas_a_bagus)

# Filter dengan isin()
target_kelas = df[df["kelas"].isin(["A"])]
```

## Agregasi & Grouping

```python
import pandas as pd

df = pd.DataFrame({
    "nama":  ["Adelia", "Budi", "Dinda", "Eko", "Fani", "Galuh"],
    "nilai": [85, 72, 91, 65, 88, 79],
    "kelas": ["A", "B", "A", "B", "A", "B"],
})

# Statistik per kelompok
stats = df.groupby("kelas")["nilai"].agg(["mean", "max", "min", "count"])
print("\nStatistik per Kelas:")
print(stats)

# Urutkan
terurut = df.sort_values("nilai", ascending=False)
print("\nPeringkat Siswa:")
print(terurut[["nama", "nilai"]].reset_index(drop=True))
```

**Output:**
```
Statistik per Kelas:
       mean  max  min  count
kelas                       
A      88.0   91   85      3
B      72.0   79   65      3

Peringkat Siswa:
    nama  nilai
0  Dinda     91
1  Fani      88
2  Adelia    85
3  Galuh     79
4  Budi      72
5  Eko       65
```

> **Tahukah kamu?** Pandas `.groupby()` mirip dengan SQL `GROUP BY`. Jika kamu pernah belajar SQL, konsep ini akan terasa sangat familiar!

## Ringkasan Modul 07

- **NumPy**: array numerik efisien, operasi vectorized, broadcasting.
- **Pandas Series**: array 1D berlabel — satu kolom data.
- **Pandas DataFrame**: tabel 2D — koleksi Series dengan baris dan kolom.
- Filtering: `df[df["kolom"] > nilai]` dengan kondisi boolean.
- Agregasi: `.groupby().agg()` untuk analisis per kelompok.

**Selamat! Kamu telah menyelesaikan seluruh 7 modul Python Dasar di PyBeginner!** 🎉
MD,
                ],
            ],
        ];
    }
}
