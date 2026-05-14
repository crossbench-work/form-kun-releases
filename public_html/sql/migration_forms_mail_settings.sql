ALTER TABLE forms
    ADD COLUMN mail_transport VARCHAR(10) DEFAULT NULL AFTER mail_template_admin,
    ADD COLUMN mail_from_email VARCHAR(255) DEFAULT NULL AFTER mail_transport,
    ADD COLUMN mail_from_name VARCHAR(255) DEFAULT NULL AFTER mail_from_email,
    ADD COLUMN mail_smtp_host VARCHAR(255) DEFAULT NULL AFTER mail_from_name,
    ADD COLUMN mail_smtp_port INT DEFAULT NULL AFTER mail_smtp_host,
    ADD COLUMN mail_smtp_secure VARCHAR(10) DEFAULT NULL AFTER mail_smtp_port,
    ADD COLUMN mail_smtp_username VARCHAR(255) DEFAULT NULL AFTER mail_smtp_secure,
    ADD COLUMN mail_smtp_password VARCHAR(255) DEFAULT NULL AFTER mail_smtp_username,
    ADD COLUMN mail_smtp_timeout INT DEFAULT NULL AFTER mail_smtp_password,
    ADD COLUMN mail_smtp_ehlo_host VARCHAR(255) DEFAULT NULL AFTER mail_smtp_timeout,
    ADD COLUMN mail_smtp_verify_peer TINYINT(1) NOT NULL DEFAULT 1 AFTER mail_smtp_ehlo_host,
    ADD COLUMN mail_smtp_verify_peer_name TINYINT(1) NOT NULL DEFAULT 1 AFTER mail_smtp_verify_peer,
    ADD COLUMN mail_smtp_allow_self_signed TINYINT(1) NOT NULL DEFAULT 0 AFTER mail_smtp_verify_peer_name;
