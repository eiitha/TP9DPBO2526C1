# TP9 - Model View Presenter (MVP)

## Janji

Saya Jillena Surbakti dengan NIM 2501485 mengerjakan Tugas Praktikum 9 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

---

## Proyek MVP (Strict Passive View) — Manajemen Rekrutmen

Aplikasi web berbasis PHP Native dengan arsitektur **Model-View-Presenter (MVP)** menggunakan pendekatan **Strict Passive View**. Aplikasi ini digunakan untuk mengelola data kandidat dan lowongan kerja dalam sistem rekrutmen.

Program terdiri dari dua modul utama:

1. **Kandidat** — Melengkapi fitur CRUD pada starter project.
2. **Lowongan** — Modul tambahan bertema HR/Rekrutmen dengan fitur CRUD lengkap.

## Fitur Program

### 1. Kandidat

Modul Kandidat digunakan untuk mengelola data calon karyawan.

Fitur yang tersedia:

- **Create** — Menambahkan kandidat baru.
- **Read** — Menampilkan seluruh data kandidat.
- **Update** — Mengubah data kandidat.
- **Delete** — Menghapus data kandidat.

Data kandidat meliputi:

| Field | Keterangan |
|---|---|
| ID | Identitas kandidat |
| Nama | Nama kandidat |
| Posisi | Posisi yang dilamar |
| Domisili | Domisili kandidat |
| Skor Tes | Nilai tes kandidat |
| Pengalaman Tahun | Lama pengalaman kerja |

Daftar kandidat ditampilkan berdasarkan skor tes tertinggi.

### 2. Lowongan

Modul Lowongan merupakan modul tambahan yang berkaitan dengan rekrutmen dan Human Resources (HR).

Fitur yang tersedia:

- **Create** — Menambahkan lowongan baru.
- **Read** — Menampilkan seluruh lowongan.
- **Update** — Mengubah data lowongan.
- **Delete** — Menghapus lowongan.

Data lowongan meliputi:

| Field | Keterangan |
|---|---|
| ID | Identitas lowongan |
| Judul | Nama posisi pekerjaan |
| Departemen | Departemen terkait |
| Lokasi | Lokasi pekerjaan |
| Kuota | Jumlah kandidat yang dibutuhkan |
| Status | Status lowongan (Dibuka / Ditutup) |

## Desain Program

Program menerapkan arsitektur **Model-View-Presenter (MVP)** yang membagi tanggung jawab menjadi tiga komponen utama, yaitu Model, View, dan Presenter.

### Model

Model bertanggung jawab atas pengelolaan data dan komunikasi dengan database. Model menggunakan PDO dan prepared statement untuk menjalankan query database serta memetakan data ke objek Entity.

### View

View bertanggung jawab atas tampilan antarmuka pengguna. View membaca template HTML dan mengganti placeholder token seperti `{{TABLE_ROWS}}` dan `{{TOTAL_DATA}}` dengan data yang telah disiapkan Presenter.

View menerapkan prinsip **Strict Passive View**, yaitu:

- Tidak mengakses Model atau Entity secara langsung.
- Tidak menggunakan `foreach`.
- Tidak menggunakan `count()`.
- Tidak menjalankan business logic.
- Hanya melakukan rendering template dan penggantian placeholder.

### Presenter

Presenter berperan sebagai penghubung antara Model dan View.

Presenter bertanggung jawab untuk:

- Menerima input pengguna.
- Melakukan validasi sederhana.
- Memanggil Model untuk mengelola data.
- Menghitung jumlah data.
- Merakit baris tabel HTML (`<tr>...</tr>`).
- Menyiapkan pilihan status dalam form.
- Mengirim string siap tampil kepada View.

### Contract / Interface

Contract digunakan sebagai aturan komunikasi antar komponen agar masing-masing bagian tidak bergantung langsung pada implementasi konkret.

Interface yang digunakan:

| Modul | Model | View | Presenter |
|---|---|---|---|
| Kandidat | `KontrakModel` | `KontrakView` | `KontrakPresenter` |
| Lowongan | `KontrakModelLowongan` | `KontrakView` | `KontrakPresenterLowongan` |

`KontrakView` bersifat generik dan digunakan bersama oleh `ViewKandidat` dan `ViewLowongan`.

## Alur Program

### Read (Menampilkan Data)

1. User membuka `index.php?page=kandidat` atau `index.php?page=lowongan`.
2. `index.php` membuat objek Model, View, dan Presenter.
3. Presenter memanggil method `getAll()` pada Model.
4. Model menjalankan query `SELECT` dan mengembalikan data.
5. Presenter menghitung total data dan merakit baris tabel HTML.
6. Presenter mengirimkan hasil ke View melalui `renderDaftar()`.
7. View mengganti placeholder pada template HTML.
8. Hasil ditampilkan pada browser.

### Create (Tambah Data)

1. User membuka halaman tambah data melalui `?screen=add`.
2. Presenter menampilkan form kosong melalui View.
3. User mengisi form dan mengirim data menggunakan method POST.
4. `index.php` menerima request dan memanggil method tambah pada Presenter.
5. Presenter melakukan validasi input.
6. Model menjalankan query `INSERT` menggunakan prepared statement.
7. User diarahkan kembali ke halaman daftar data.

### Update (Ubah Data)

1. User memilih tombol Edit pada data yang ingin diubah.
2. Program menerima request `?screen=edit&id=N`.
3. Presenter mengambil data berdasarkan ID melalui Model.
4. Data diubah menjadi array sederhana untuk kebutuhan form.
5. View menampilkan form dengan data sebelumnya (*prefill*).
6. User mengubah data dan mengirim form melalui POST.
7. Presenter melakukan validasi dan Model menjalankan query `UPDATE`.
8. User diarahkan kembali ke halaman daftar.

### Delete (Hapus Data)

1. User memilih tombol Hapus pada data.
2. Program mengirim request POST berisi action `hapus` dan ID data.
3. Presenter memanggil Model untuk menghapus data.
4. Model menjalankan query `DELETE` menggunakan prepared statement.
5. User diarahkan kembali ke halaman daftar.

## Struktur Folder

```text
TP9DPBO2526C1/
├── project/
│   ├── index.php
│   ├── models/
│   │   ├── DB.php
│   │   ├── Kandidat.php
│   │   ├── KontrakModel.php
│   │   ├── TabelKandidat.php
│   │   ├── Lowongan.php
│   │   ├── KontrakModelLowongan.php
│   │   └── TabelLowongan.php
│   │
│   ├── presenters/
│   │   ├── KontrakPresenter.php
│   │   ├── PresenterKandidat.php
│   │   ├── KontrakPresenterLowongan.php
│   │   └── PresenterLowongan.php
│   │
│   ├── views/
│   │   ├── KontrakView.php
│   │   ├── ViewKandidat.php
│   │   └── ViewLowongan.php
│   │
│   └── template/
│       ├── skin.html
│       ├── form.html
│       ├── skin_lowongan.html
│       └── form_lowongan.html
│
├── dokumentasi/
│    └── CRUD.mp4
│
├── mvp_db.sql
└── README.md
```

## Database

Database yang digunakan bernama `mvp_kandidat_db`, yang terdiri dari dua tabel.

### Tabel `kandidat`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary key, auto increment |
| `nama` | VARCHAR(100) | Nama kandidat |
| `posisi` | VARCHAR(100) | Posisi yang dilamar |
| `domisili` | VARCHAR(100) | Domisili kandidat |
| `skorTes` | INT | Skor tes kandidat |
| `pengalamanTahun` | INT | Pengalaman kerja dalam tahun |

### Tabel `lowongan`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT | Primary key, auto increment |
| `judul` | VARCHAR(100) | Judul lowongan |
| `departemen` | VARCHAR(100) | Departemen pekerjaan |
| `lokasi` | VARCHAR(100) | Lokasi pekerjaan |
| `kuota` | INT | Jumlah tenaga kerja yang dibutuhkan |
| `status` | VARCHAR(20) | Status lowongan |

Relasi antara tabel Kandidat dan Lowongan tidak digunakan karena relasi tidak diwajibkan dalam tugas praktikum.

## Token Template

Template HTML menggunakan placeholder token yang akan diganti oleh View berdasarkan data yang telah disiapkan Presenter.

| Template | Token |
|---|---|
| `skin.html` / `skin_lowongan.html` | `{{TABLE_ROWS}}`, `{{TOTAL_DATA}}` |
| `form.html` | `{{FORM_TITLE}}`, `{{ACTION}}`, `{{ID}}`, `{{NAMA}}`, `{{POSISI}}`, `{{DOMISILI}}`, `{{SKOR_TES}}`, `{{PENGALAMAN_TAHUN}}` |
| `form_lowongan.html` | `{{FORM_TITLE}}`, `{{ACTION}}`, `{{ID}}`, `{{JUDUL}}`, `{{DEPARTEMEN}}`, `{{LOKASI}}`, `{{KUOTA}}`, `{{STATUS_OPTIONS}}` |

## Keamanan dan Validasi

- Query database menggunakan prepared statement.
- Input pengguna menjalani validasi sederhana pada Presenter.
- Skor tes kandidat dibatasi pada rentang 0–100.
- Kuota lowongan minimal bernilai 1.
- Status lowongan hanya menggunakan `Dibuka` atau `Ditutup`.
- Output teks pada tabel di-escape menggunakan `htmlspecialchars()` untuk mencegah HTML yang tidak diinginkan.

## Dokumentasi

https://github.com/user-attachments/assets/90309d3d-776c-4358-9831-e91c21efce8d
