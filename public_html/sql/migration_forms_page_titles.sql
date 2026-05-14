ALTER TABLE forms
    ADD COLUMN input_page_title VARCHAR(200) DEFAULT NULL AFTER title,
    ADD COLUMN show_input_page_title TINYINT(1) NOT NULL DEFAULT 1 AFTER input_page_title,
    ADD COLUMN confirm_page_title VARCHAR(200) DEFAULT NULL AFTER show_description,
    ADD COLUMN show_confirm_page_title TINYINT(1) NOT NULL DEFAULT 1 AFTER confirm_page_title,
    ADD COLUMN thanks_page_title VARCHAR(200) DEFAULT NULL AFTER confirm_message,
    ADD COLUMN show_thanks_page_title TINYINT(1) NOT NULL DEFAULT 1 AFTER thanks_page_title;

UPDATE forms
   SET input_page_title = title
 WHERE (input_page_title IS NULL OR input_page_title = '')
   AND title IS NOT NULL
   AND title <> '';

UPDATE forms
   SET show_input_page_title = 1
 WHERE show_input_page_title IS NULL
    OR show_input_page_title NOT IN (0, 1);

UPDATE forms
   SET confirm_page_title = '入力内容のご確認'
 WHERE confirm_page_title IS NULL
    OR confirm_page_title = '';

UPDATE forms
   SET show_confirm_page_title = 1
 WHERE show_confirm_page_title IS NULL
    OR show_confirm_page_title NOT IN (0, 1);

UPDATE forms
   SET thanks_page_title = '送信が完了しました'
 WHERE thanks_page_title IS NULL
    OR thanks_page_title = '';

UPDATE forms
   SET show_thanks_page_title = 1
 WHERE show_thanks_page_title IS NULL
    OR show_thanks_page_title NOT IN (0, 1);
