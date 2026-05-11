-- form_fields.regex を複数パターン（JSON 配列）の格納用に TEXT へ拡張（旧環境向け手動実行用）
ALTER TABLE form_fields MODIFY regex TEXT DEFAULT NULL;
