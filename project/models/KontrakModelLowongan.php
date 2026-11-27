<?php
require_once __DIR__ . '/Lowongan.php';

/**
 * Kontrak Model untuk modul Lowongan.
 * Presenter hanya bergantung pada interface ini, bukan pada kelas TabelLowongan.
 */
interface KontrakModelLowongan {
    /**
     * Mengambil seluruh data lowongan sebagai array objek Lowongan.
     * @return Lowongan[]
     */
    public function getAllLowongan(): array;

    /**
     * Mengambil satu data lowongan berdasarkan ID (null jika tidak ada).
     */
    public function getLowonganById(int $id): ?Lowongan;

    public function addLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function updateLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void;
    public function deleteLowongan(int $id): void;
}
?>
