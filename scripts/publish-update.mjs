#!/usr/bin/env node
import { execSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { copyTree, ensureDir, rimraf } from './lib/fs-utils.mjs';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const releasesDir = path.join(root, 'releases');
const manifestPath = path.join(releasesDir, 'manifest.json');
const worktreeDir = path.join(root, '.worktrees', 'update');

function run(cmd, opts = {}) {
  execSync(cmd, { cwd: opts.cwd || root, stdio: 'inherit' });
}
function capture(cmd, opts = {}) {
  return execSync(cmd, { cwd: opts.cwd || root, encoding: 'utf8' }).trim();
}

if (!fs.existsSync(manifestPath)) {
  console.error('[publish-update] releases/manifest.json がありません。');
  process.exit(1);
}

const hasUpdate = capture('git branch --list update').includes('update');
rimraf(worktreeDir);
ensureDir(path.dirname(worktreeDir));

if (hasUpdate) {
  run(`git worktree add ${JSON.stringify(worktreeDir)} update`);
} else {
  run(`git worktree add -b update ${JSON.stringify(worktreeDir)}`);
  run('git rm -rf .', { cwd: worktreeDir, stdio: 'pipe' });
}

const wtReleases = path.join(worktreeDir, 'releases');
rimraf(wtReleases);
ensureDir(wtReleases);
for (const name of fs.readdirSync(releasesDir)) {
  const src = path.join(releasesDir, name);
  const dest = path.join(wtReleases, name);
  if (fs.statSync(src).isDirectory()) {
    copyTree(src, dest);
  } else {
    fs.copyFileSync(src, dest);
  }
}

run('git add releases', { cwd: worktreeDir });
const status = capture('git status --porcelain', { cwd: worktreeDir });
if (status) {
  const latest = JSON.parse(fs.readFileSync(manifestPath, 'utf8')).latest;
  run(`git commit -m ${JSON.stringify(`Release ${latest}`)}`, { cwd: worktreeDir });
} else {
  console.log('[publish-update] worktree に変更なし');
}

run(`git worktree remove ${JSON.stringify(worktreeDir)} --force`);
console.log('[publish-update] 完了。次を手動で実行: git push origin update');
