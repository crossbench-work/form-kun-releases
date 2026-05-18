#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
if [ ! -d dist ]; then
  echo "dist/ がありません。npm run build && npm run env:local を実行してください。" >&2
  exit 1
fi
exec php -S 127.0.0.1:8080 -t dist dist/router.php
