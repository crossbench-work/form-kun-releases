#!/usr/bin/env node
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { ensureDir } from './lib/fs-utils.mjs';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const envName = process.argv[2] || 'local';
const srcFile = path.join(root, 'env', envName, 'config.local.php');
const destFile = path.join(root, 'dist', 'app', 'config.local.php');

if (!fs.existsSync(srcFile)) {
  console.error(`[deploy-env] 見つかりません: ${srcFile}`);
  process.exit(1);
}
ensureDir(path.dirname(destFile));
fs.copyFileSync(srcFile, destFile);
console.log(`[deploy-env] ${srcFile} → ${destFile}`);
