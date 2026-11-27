<?php
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../views/KontrakView.php';

class PresenterLowongan implements KontrakPresenterLowongan {
    private const STATUS_VALID = ['Dibuka', 'Ditutup'];

    private KontrakModelLowongan $model;
    private KontrakView $view;

    public function __construct(KontrakModelLowongan $model, KontrakView $view) {
        $this->model = $model;
        $this->view = $view;
    }

    /**
     * Merakit 1 baris HTML tabel lowongan (presentation logic ada di Presenter,
     * sehingga View tetap pasif).
     */
    private function buildRowHtml(int $no, Lowongan $lowongan): string {
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul());
        $departemen = htmlspecialchars($lowongan->getDepartemen());
        $lokasi = htmlspecialchars($lowongan->getLokasi());
        $kuota = number_format($lowongan->getKuota()) . ' Orang';

        $status = htmlspecialchars($lowongan->getStatus());
        $badgeClass = ($lowongan->getStatus() === 'Dibuka') ? 'badge-open' : 'badge-closed';

        return "<tr>
            <td class='col-id'>{$no}</td>
            <td><strong>{$judul}</strong></td>
            <td>{$departemen}</td>
            <td>{$lokasi}</td>
            <td>{$kuota}</td>
            <td><span class='badge {$badgeClass}'>{$status}</span></td>
            <td class='col-actions'>
                <a href='index.php?page=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                <form method='post' action='index.php' style='display:inline;' onsubmit=\"return confirm('Hapus lowongan ini?')\">
                    <input type='hidden' name='page' value='lowongan'>
                    <input type='hidden' name='action' value='hapus'>
                    <input type='hidden' name='id' value='{$id}'>
                    <button type='submit' class='btn btn-delete'>Hapus</button>
                </form>
            </td>
        </tr>";
    }

    /**
     * Merakit <option> untuk dropdown status; opsi yang cocok diberi atribut selected.
     */
    private function buildStatusOptionsHtml(string $selected): string {
        $html = '';
        foreach (self::STATUS_VALID as $opsi) {
            $attr = ($opsi === $selected) ? ' selected' : '';
            $html .= "<option value='{$opsi}'{$attr}>{$opsi}</option>";
        }
        return $html;
    }

    private function normalisasiStatus(string $status): string {
        return in_array($status, self::STATUS_VALID, true) ? $status : 'Dibuka';
    }

    public function tampilkanLowongan(): string {
        $listLowongan = $this->model->getAllLowongan();
        $totalLowongan = count($listLowongan);

        $rowsHtml = '';
        if (empty($listLowongan)) {
            $rowsHtml = '<tr><td colspan="7" style="text-align:center;color:#6b7280;padding:24px;">Belum ada data lowongan.</td></tr>';
        } else {
            $no = 1;
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalLowongan);
    }

    public function tampilkanFormLowongan(?int $id = null): string {
        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan === null) {
                header("Location: index.php?page=lowongan");
                exit();
            }

            $formData = [
                'id'            => $lowongan->getId(),
                'judul'         => $lowongan->getJudul(),
                'departemen'    => $lowongan->getDepartemen(),
                'lokasi'        => $lowongan->getLokasi(),
                'kuota'         => $lowongan->getKuota(),
                'statusOptions' => $this->buildStatusOptionsHtml($lowongan->getStatus()),
            ];
            return $this->view->renderForm("Ubah Data Lowongan", "ubah", $formData);
        }

        $formData = [
            'statusOptions' => $this->buildStatusOptionsHtml('Dibuka'),
        ];
        return $this->view->renderForm("Tambah Lowongan Baru", "tambah", $formData);
    }

    public function tambahLowongan(string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $kuota = max(1, $kuota);
        $status = $this->normalisasiStatus($status);

        $this->model->addLowongan($judul, $departemen, $lokasi, $kuota, $status);
        header("Location: index.php?page=lowongan");
        exit();
    }

    public function ubahLowongan(int $id, string $judul, string $departemen, string $lokasi, int $kuota, string $status): void {
        $kuota = max(1, $kuota);
        $status = $this->normalisasiStatus($status);

        $this->model->updateLowongan($id, $judul, $departemen, $lokasi, $kuota, $status);
        header("Location: index.php?page=lowongan");
        exit();
    }

    public function hapusLowongan(int $id): void {
        $this->model->deleteLowongan($id);
        header("Location: index.php?page=lowongan");
        exit();
    }
}
?>
