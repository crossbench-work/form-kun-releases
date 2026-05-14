ALTER TABLE forms
    ADD COLUMN input_page_title VARCHAR(200) DEFAULT NULL AFTER title,
    ADD COLUMN confirm_page_title VARCHAR(200) DEFAULT NULL AFTER show_description,
    ADD COLUMN thanks_page_title VARCHAR(200) DEFAULT NULL AFTER confirm_message;

UPDATE forms
   SET input_page_title = title
 WHERE (input_page_title IS NULL OR input_page_title = '')
   AND title IS NOT NULL
   AND title <> '';

UPDATE forms
   SET confirm_page_title = '入力内容のご確認'
 WHERE confirm_page_title IS NULL
    OR confirm_page_title = '';

UPDATE forms
   SET thanks_page_title = '送信が完了しました'
 WHERE thanks_page_title IS NULL
    OR thanks_page_title = '';
