<?php
require_once __DIR__ . '/KontrakView.php';

/**
 * View pasif untuk modul Lowongan.
 * Hanya membaca template HTML lalu mengganti placeholder token.
 * Tidak mengenal Model/Entity dan tidak melakukan perulangan data.
 */
class ViewLowongan implements KontrakView {
    private string $skinPath;
    private string $formPath;

    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skin_lowongan.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/form_lowongan.html';
    }

    public function renderDaftar(string $tableRowsHtml, int $totalData): string {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{TABLE_ROWS}}' => $tableRowsHtml,
            '{{TOTAL_DATA}}' => (string)$totalData,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    public function renderForm(string $title, string $action, array $formData = []): string {
        $template = file_get_contents($this->formPath);

        $tokens = [
            '{{FORM_TITLE}}'     => htmlspecialchars($title),
            '{{ACTION}}'         => htmlspecialchars($action),
            '{{ID}}'             => htmlspecialchars((string)($formData['id'] ?? '')),
            '{{JUDUL}}'          => htmlspecialchars($formData['judul'] ?? ''),
            '{{DEPARTEMEN}}'     => htmlspecialchars($formData['departemen'] ?? ''),
            '{{LOKASI}}'         => htmlspecialchars($formData['lokasi'] ?? ''),
            '{{KUOTA}}'          => htmlspecialchars((string)($formData['kuota'] ?? 1)),
            // Sudah dirakit & di-escape oleh Presenter
            '{{STATUS_OPTIONS}}' => $formData['statusOptions'] ?? '',
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
