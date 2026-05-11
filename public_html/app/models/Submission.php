<?php
namespace Models;

use Core\Database;

class Submission
{
    /**
     * 送信を保存（値・ファイルを含む）。
     */
    public static function create(int $formId, array $values, array $fileMetas, string $ip, string $ua): int
    {
        return Database::transaction(function () use ($formId, $values, $fileMetas, $ip, $ua) {
            Database::execute(
                'INSERT INTO submissions (form_id, ip, user_agent, submitted_at) VALUES (?, ?, ?, ?)',
                [$formId, $ip, mb_substr($ua, 0, 255), date('Y-m-d H:i:s')]
            );
            $sid = Database::lastInsertId();

            foreach ($values as $key => $val) {
                if (is_array($val)) {
                    $val = self::isAddressValueShape($val)
                        ? json_encode($val, JSON_UNESCAPED_UNICODE)
                        : implode("\n", array_map('strval', $val));
                }
                if ($val === null) {
                    $val = '';
                }
                Database::execute(
                    'INSERT INTO submission_values (submission_id, field_key, value) VALUES (?, ?, ?)',
                    [$sid, $key, (string) $val]
                );
            }

            foreach ($fileMetas as $fieldKey => $meta) {
                if (!$meta) continue;
                Database::execute(
                    'INSERT INTO submission_files (submission_id, field_key, original_name, stored_path, mime_type, size)
                     VALUES (?,?,?,?,?,?)',
                    [
                        $sid,
                        $fieldKey,
                        $meta['original_name'],
                        $meta['stored_path'],
                        $meta['mime_type'] ?? null,
                        $meta['size'] ?? null,
                    ]
                );
            }

            return $sid;
        });
    }

    /**
     * 住所フィールド（postal / line1 / line2）かどうか。チェックボックスの添字配列と区別する。
     */
    private static function isAddressValueShape(array $arr): bool
    {
        if ($arr === []) {
            return false;
        }
        $allowed = [
            'postal', 'postal3', 'postal4',
            'prefecture', 'city', 'number', 'building', 'street', 'line1', 'line2',
        ];
        foreach (array_keys($arr) as $k) {
            if (!in_array((string) $k, $allowed, true)) {
                return false;
            }
        }
        return true;
    }

    public static function find(int $id): ?array
    {
        return Database::fetchOne('SELECT * FROM submissions WHERE id = ?', [$id]);
    }

    public static function valuesFor(int $submissionId): array
    {
        $rows = Database::fetchAll(
            'SELECT field_key, value FROM submission_values WHERE submission_id = ?',
            [$submissionId]
        );
        $out = [];
        foreach ($rows as $r) {
            $out[$r['field_key']] = $r['value'];
        }
        return $out;
    }

    public static function filesFor(int $submissionId): array
    {
        return Database::fetchAll(
            'SELECT * FROM submission_files WHERE submission_id = ? ORDER BY id ASC',
            [$submissionId]
        );
    }

    public static function findFile(int $fileId): ?array
    {
        return Database::fetchOne('SELECT * FROM submission_files WHERE id = ?', [$fileId]);
    }

    public static function delete(int $id): void
    {
        Database::transaction(function () use ($id) {
            $files = self::filesFor($id);
            foreach ($files as $f) {
                \Core\FileStorage::delete($f['stored_path']);
            }
            Database::execute('DELETE FROM submissions WHERE id = ?', [$id]);
        });
    }

    /**
     * 検索＋ページング。
     *
     * @return array{rows: array, total: int}
     */
    public static function search(int $formId, ?string $q, ?string $from, ?string $to, int $page, int $perPage): array
    {
        $params = [$formId];
        $where  = 's.form_id = ?';

        if ($from !== null && $from !== '') {
            $where .= ' AND s.submitted_at >= ?';
            $params[] = $from . ' 00:00:00';
        }
        if ($to !== null && $to !== '') {
            $where .= ' AND s.submitted_at <= ?';
            $params[] = $to . ' 23:59:59';
        }

        if ($q !== null && $q !== '') {
            $where .= ' AND EXISTS (SELECT 1 FROM submission_values sv WHERE sv.submission_id = s.id AND sv.value LIKE ?)';
            $params[] = '%' . $q . '%';
        }

        $totalRow = Database::fetchOne(
            'SELECT COUNT(*) AS c FROM submissions s WHERE ' . $where,
            $params
        );
        $total = (int) ($totalRow['c'] ?? 0);

        $offset = max(0, ($page - 1) * $perPage);
        $sql = 'SELECT s.* FROM submissions s WHERE ' . $where
             . ' ORDER BY s.submitted_at DESC LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset;
        $rows = Database::fetchAll($sql, $params);

        return ['rows' => $rows, 'total' => $total];
    }

    public static function recent(int $limit = 10): array
    {
        return Database::fetchAll(
            'SELECT s.*, f.title AS form_title, f.slug AS form_slug
             FROM submissions s JOIN forms f ON f.id = s.form_id
             ORDER BY s.submitted_at DESC LIMIT ' . (int) $limit
        );
    }

    public static function countsByForm(): array
    {
        return Database::fetchAll(
            'SELECT f.id, f.title, f.slug, f.status,
                    (SELECT COUNT(*) FROM submissions s WHERE s.form_id = f.id) AS cnt
             FROM forms f ORDER BY f.id DESC'
        );
    }
}
