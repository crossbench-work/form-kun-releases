ALTER TABLE forms
    ADD COLUMN show_title TINYINT(1) NOT NULL DEFAULT 1 AFTER description,
    ADD COLUMN show_description TINYINT(1) NOT NULL DEFAULT 1 AFTER show_title;
