<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

/**
 * Front controller: hanya melakukan routing & merakit objek (Model, View, Presenter).
 * Seluruh logika tampilan/bisnis ada di Presenter.
 */

// Helper kecil untuk mengambil input POST
function postStr(string $key): string {
    return trim((string)($_POST[$key] ?? ''));
}
function postInt(string $key): int {
    return (int)($_POST[$key] ?? 0);
}

// Halaman aktif: kandidat (default) atau lowongan
$page = $_GET['page'] ?? $_POST['page'] ?? 'kandidat';
if (!in_array($page, ['kandidat', 'lowongan'], true)) {
    $page = 'kandidat';
}

if ($page === 'lowongan') {
    $model     = new TabelLowongan('localhost', 'mvp_kandidat_db', 'root', '');
    $view      = new ViewLowongan();
    $presenter = new PresenterLowongan($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'tambah') {
            $presenter->tambahLowongan(
                postStr('judul'), postStr('departemen'), postStr('lokasi'),
                postInt('kuota'), postStr('status')
            );
        } elseif ($action === 'ubah') {
            $presenter->ubahLowongan(
                postInt('id'), postStr('judul'), postStr('departemen'), postStr('lokasi'),
                postInt('kuota'), postStr('status')
            );
        } elseif ($action === 'hapus') {
            $presenter->hapusLowongan(postInt('id'));
        }
        header("Location: index.php?page=lowongan");
        exit();
    }

    $screen = $_GET['screen'] ?? 'list';
    if ($screen === 'add') {
        echo $presenter->tampilkanFormLowongan();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormLowongan((int)$_GET['id']);
    } else {
        echo $presenter->tampilkanLowongan();
    }

} else {
    $model     = new TabelKandidat('localhost', 'mvp_kandidat_db', 'root', '');
    $view      = new ViewKandidat();
    $presenter = new PresenterKandidat($model, $view);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        if ($action === 'tambah') {
            $presenter->tambahKandidat(
                postStr('nama'), postStr('posisi'), postStr('domisili'),
                postInt('skorTes'), postInt('pengalamanTahun')
            );
        } elseif ($action === 'ubah') {
            $presenter->ubahKandidat(
                postInt('id'), postStr('nama'), postStr('posisi'), postStr('domisili'),
                postInt('skorTes'), postInt('pengalamanTahun')
            );
        } elseif ($action === 'hapus') {
            $presenter->hapusKandidat(postInt('id'));
        }
        header("Location: index.php?page=kandidat");
        exit();
    }

    $screen = $_GET['screen'] ?? 'list';
    if ($screen === 'add') {
        echo $presenter->tampilkanFormKandidat();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormKandidat((int)$_GET['id']);
    } else {
        echo $presenter->tampilkanKandidat();
    }
}
?>
