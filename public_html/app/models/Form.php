<?php
namespace Models;

use Core\Database;

class Form
{
    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM forms ORDER BY id DESC');
    }

    public static function find(int $id): ?array
    {
        return Database::fetchOne('SELECT * FROM forms WHERE id = ?', [$id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetchOne('SELECT * FROM forms WHERE slug = ?', [$slug]);
    }

    public static function findActiveBySlug(string $slug): ?array
    {
        return Database::fetchOne(
            "SELECT * FROM forms WHERE slug = ? AND status = 'active'",
            [$slug]
        );
    }

    public static function create(array $data): int
    {
        $now = date('Y-m-d H:i:s');
        Database::execute(
            'INSERT INTO forms (
                slug, title, input_page_title, show_input_page_title, description, show_title, show_description, confirm_page_title, show_confirm_page_title, confirm_button_text, submit_button_text, back_button_text, confirm_message, thanks_page_title, show_thanks_page_title, thanks_message, thanks_button_text, thanks_button_url,
                admin_emails, mail_subject_admin, mail_template_admin,
                reply_enabled, reply_from_field_key, reply_subject, reply_template,
                captcha_enabled, status, created_at, updated_at
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
            [
                $data['slug'],
                $data['title'],
                $data['input_page_title'] ?? null,
                !empty($data['show_input_page_title']) ? 1 : 0,
                $data['description'] ?? null,
                !empty($data['show_title']) ? 1 : 0,
                !empty($data['show_description']) ? 1 : 0,
                $data['confirm_page_title'] ?? null,
                !empty($data['show_confirm_page_title']) ? 1 : 0,
                $data['confirm_button_text'] ?? null,
                $data['submit_button_text'] ?? null,
                $data['back_button_text'] ?? null,
                $data['confirm_message'] ?? null,
                $data['thanks_page_title'] ?? null,
                !empty($data['show_thanks_page_title']) ? 1 : 0,
                $data['thanks_message'] ?? null,
                $data['thanks_button_text'] ?? null,
                $data['thanks_button_url'] ?? null,
                $data['admin_emails'] ?? null,
                $data['mail_subject_admin'] ?? null,
                $data['mail_template_admin'] ?? null,
                !empty($data['reply_enabled']) ? 1 : 0,
                $data['reply_from_field_key'] ?? null,
                $data['reply_subject'] ?? null,
                $data['reply_template'] ?? null,
                !empty($data['captcha_enabled']) ? 1 : 0,
                $data['status'] ?? 'draft',
                $now,
                $now,
            ]
        );
        return Database::lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $now = date('Y-m-d H:i:s');
        Database::execute(
            'UPDATE forms SET
                slug = ?, title = ?, input_page_title = ?, show_input_page_title = ?, description = ?, show_title = ?, show_description = ?, confirm_page_title = ?, show_confirm_page_title = ?, confirm_button_text = ?, submit_button_text = ?, back_button_text = ?, confirm_message = ?, thanks_page_title = ?, show_thanks_page_title = ?, thanks_message = ?, thanks_button_text = ?, thanks_button_url = ?,
                admin_emails = ?, mail_subject_admin = ?, mail_template_admin = ?,
                reply_enabled = ?, reply_from_field_key = ?, reply_subject = ?, reply_template = ?,
                captcha_enabled = ?, status = ?, updated_at = ?
              WHERE id = ?',
            [
                $data['slug'],
                $data['title'],
                $data['input_page_title'] ?? null,
                !empty($data['show_input_page_title']) ? 1 : 0,
                $data['description'] ?? null,
                !empty($data['show_title']) ? 1 : 0,
                !empty($data['show_description']) ? 1 : 0,
                $data['confirm_page_title'] ?? null,
                !empty($data['show_confirm_page_title']) ? 1 : 0,
                $data['confirm_button_text'] ?? null,
                $data['submit_button_text'] ?? null,
                $data['back_button_text'] ?? null,
                $data['confirm_message'] ?? null,
                $data['thanks_page_title'] ?? null,
                !empty($data['show_thanks_page_title']) ? 1 : 0,
                $data['thanks_message'] ?? null,
                $data['thanks_button_text'] ?? null,
                $data['thanks_button_url'] ?? null,
                $data['admin_emails'] ?? null,
                $data['mail_subject_admin'] ?? null,
                $data['mail_template_admin'] ?? null,
                !empty($data['reply_enabled']) ? 1 : 0,
                $data['reply_from_field_key'] ?? null,
                $data['reply_subject'] ?? null,
                $data['reply_template'] ?? null,
                !empty($data['captcha_enabled']) ? 1 : 0,
                $data['status'] ?? 'draft',
                $now,
                $id,
            ]
        );
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM forms WHERE id = ?', [$id]);
    }

    public static function isSlugAvailable(string $slug, ?int $excludeId = null): bool
    {
        if ($excludeId === null) {
            $row = Database::fetchOne('SELECT id FROM forms WHERE slug = ?', [$slug]);
        } else {
            $row = Database::fetchOne('SELECT id FROM forms WHERE slug = ? AND id <> ?', [$slug, $excludeId]);
        }
        return $row === null;
    }
}
