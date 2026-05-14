-- form_fields に visible 列を追加（旧DB向け手動実行例）
ALTER TABLE form_fields
    ADD COLUMN visible TINYINT(1) NOT NULL DEFAULT 1 AFTER label;
