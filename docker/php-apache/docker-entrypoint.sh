#!/bin/sh
set -eu
# uploads への書き込み（マウントされたホスト側パーミッション調整）
if [ -d /var/www/html/uploads ]; then
    chown -R www-data:www-data /var/www/html/uploads 2>/dev/null || true
    chmod -R ug+rwx /var/www/html/uploads 2>/dev/null || true
fi
exec "$@"
