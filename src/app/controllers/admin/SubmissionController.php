<?php
namespace Controllers\Admin;

use Core\Auth;
use Core\Csrf;
use Core\FileStorage;
use Core\View;
use Models\Form;
use Models\FormField;
use Models\Submission;

class SubmissionController
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index(int $formId): void
    {
        $form = Form::find($formId);
        if (!$form) { $this->notFound(); return; }

        $q    = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
        $from = isset($_GET['from']) ? trim((string) $_GET['from']) : '';
        $to   = isset($_GET['to']) ? trim((string) $_GET['to']) : '';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 20;

        $result = Submission::search($formId, $q ?: null, $from ?: null, $to ?: null, $page, $perPage);
        $totalPages = max(1, (int) ceil($result['total'] / $perPage));

        $fields = FormField::forForm($formId, true);
        // 一覧プレビュー用に各送信の値を取得
        $previewKeys = array_slice(array_map(fn($f) => $f['field_key'], $fields), 0, 3);
        $previews = [];
        if (!empty($result['rows'])) {
            $ids = array_map(fn($r) => (int) $r['id'], $result['rows']);
            $in = implode(',', array_fill(0, count($ids), '?'));
            $rows = \Core\Database::fetchAll(
                "SELECT submission_id, field_key, value FROM submission_values WHERE submission_id IN ($in)",
                $ids
            );
            foreach ($rows as $r) {
                $previews[(int) $r['submission_id']][$r['field_key']] = $r['value'];
            }
        }

        (new View())->render('admin/submissions/index', [
            'title'   => '送信履歴: ' . $form['title'],
            'form'    => $form,
            'rows'    => $result['rows'],
            'total'   => $result['total'],
            'page'    => $page,
            'totalPages' => $totalPages,
            'q'       => $q,
            'from'    => $from,
            'to'      => $to,
            'previewKeys' => $previewKeys,
            'previews' => $previews,
            'fields'  => $fields,
        ], 'admin');
    }

    public function show(int $id): void
    {
        $sub = Submission::find($id);
        if (!$sub) { $this->notFound(); return; }
        $form = Form::find((int) $sub['form_id']);
        if (!$form) { $this->notFound(); return; }
        $fields = FormField::forForm((int) $form['id'], true);
        $values = Submission::valuesFor($id);
        $files  = Submission::filesFor($id);

        // ファイルキーのインデックス
        $filesByKey = [];
        foreach ($files as $f) {
            $filesByKey[$f['field_key']][] = $f;
        }

        (new View())->render('admin/submissions/show', [
            'title' => '送信内容詳細',
            'form'  => $form,
            'sub'   => $sub,
            'fields'=> $fields,
            'values'=> $values,
            'filesByKey' => $filesByKey,
        ], 'admin');
    }

    public function destroy(int $id): void
    {
        Csrf::verify();
        $sub = Submission::find($id);
        if (!$sub) { $this->notFound(); return; }
        $formId = (int) $sub['form_id'];
        Submission::delete($id);
        flash('success', '送信を削除しました。');
        redirect('/admin/forms/' . $formId . '/submissions');
    }

    public function download(int $fileId): void
    {
        $file = Submission::findFile($fileId);
        if (!$file) { $this->notFound(); return; }
        $abs = FileStorage::absolutePath($file['stored_path']);
        if (!is_file($abs)) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }

        $name = $file['original_name'];
        $mime = $file['mime_type'] ?: 'application/octet-stream';

        // ダウンロード用ヘッダ
        while (ob_get_level() > 0) { ob_end_clean(); }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($abs));
        header('Content-Disposition: attachment; filename="' . self::asciiFallback($name)
            . '"; filename*=UTF-8\'\'' . rawurlencode($name));
        header('X-Content-Type-Options: nosniff');
        readfile($abs);
        exit;
    }

    private static function asciiFallback(string $name): string
    {
        $ascii = preg_replace('/[^\x20-\x7e]/', '_', $name) ?? 'download';
        return str_replace('"', '_', $ascii);
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '<!doctype html><meta charset="utf-8"><title>404</title><h1>404</h1>';
        exit;
    }
}
