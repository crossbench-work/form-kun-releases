-- form_fields.type に address（住所）を追加（旧DB向け手動実行例）
ALTER TABLE form_fields MODIFY COLUMN type ENUM(
    'text','textarea','email','tel','number','address',
    'radio','checkbox','select','date','time','file'
) NOT NULL;
