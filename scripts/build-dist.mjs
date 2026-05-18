#!/usr/bin/env node
import { spawnSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { copyTree, ensureDir, readJson, rimraf } from './lib/fs-utils.mjs';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const srcDir = path.join(root, 'src');
const distDir = path.join(root, 'dist');
const protectedPaths = readJson(path.join(root, 'scripts', 'protected-paths.json'));

console.log('[build-dist] clean dist/');
rimraf(distDir);

console.log('[build-dist] copy src/ → dist/');
copyTree(srcDir, distDir, protectedPaths.exclude_from_dist_copy);

for (const pattern of protectedPaths.exclude_from_dist_copy) {
  if (fs.existsSync(path.join(distDir, pattern))) {
    console.error('[build-dist] 保護パスが dist に含まれています:', pattern);
    process.exit(1);
  }
}

ensureDir(path.join(distDir, 'app', 'config'));
fs.copyFileSync(
  path.join(root, 'scripts', 'protected-paths.json'),
  path.join(distDir, 'app', 'config', 'protected-paths.json'),
);

ensureDir(path.join(distDir, 'storage'));
fs.writeFileSync(path.join(distDir, 'storage', '.htaccess'), 'Require all denied\n');

console.log('[build-dist] sass');
const sass = spawnSync(
  'npx',
  [
    'sass',
    'src/assets/scss/public.scss:dist/assets/css/public.css',
    'src/assets/scss/admin.scss:dist/assets/css/admin.css',
    '--style=expanded',
    '--no-source-map',
  ],
  { cwd: root, stdio: 'inherit' },
);
if (sass.status !== 0) process.exit(sass.status ?? 1);
console.log('[build-dist] done');
