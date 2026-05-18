#!/usr/bin/env node
import { createHash } from 'node:crypto';
import { execSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { ensureDir, readJson, rimraf } from './lib/fs-utils.mjs';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const distDir = path.join(root, 'dist');
const releasesDir = path.join(root, 'releases');
const protectedPaths = readJson(path.join(root, 'scripts', 'protected-paths.json'));
const releaseConfig = readJson(path.join(root, 'scripts', 'release-config.json'));
const pkg = readJson(path.join(root, 'package.json'));
const version = pkg.version || '0.0.0';
const notes = process.argv.slice(2).join(' ').trim() || `Release ${version}`;

if (!fs.existsSync(distDir)) {
  console.error('[build-release] dist/ がありません。先に npm run build を実行してください。');
  process.exit(1);
}

for (const pattern of protectedPaths.exclude_from_dist_copy) {
  if (fs.existsSync(path.join(distDir, pattern))) {
    console.error('[build-release] dist に保護パスが含まれています:', pattern);
    process.exit(1);
  }
}

const zipName = `${releaseConfig.zip_name_prefix}-${version}.zip`;
const zipPath = path.join(releasesDir, zipName);
ensureDir(releasesDir);
if (fs.existsSync(zipPath)) fs.unlinkSync(zipPath);

console.log('[build-release] zip', zipPath);
execSync(`zip -r -q ${JSON.stringify(zipPath)} . -x "*.DS_Store"`, { cwd: distDir, stdio: 'inherit' });

const sha256 = createHash('sha256').update(fs.readFileSync(zipPath)).digest('hex');
const branch = releaseConfig.update_branch || 'update';
const zipUrl = `https://raw.githubusercontent.com/${releaseConfig.github_org}/${releaseConfig.github_repo}/${branch}/releases/${zipName}`;

const manifestPath = path.join(releasesDir, 'manifest.json');
let manifest = { latest: version, channel: branch, releases: [] };
if (fs.existsSync(manifestPath)) {
  try { manifest = readJson(manifestPath); } catch (_) {}
}
if (!Array.isArray(manifest.releases)) manifest.releases = [];
manifest.latest = version;
manifest.channel = branch;
manifest.releases = manifest.releases.filter((r) => r.version !== version);
manifest.releases.unshift({
  version,
  zip_url: zipUrl,
  sha256,
  released_at: new Date().toISOString().slice(0, 10),
  notes,
});
fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 2) + '\n');
console.log('[build-release] manifest updated', manifestPath);
console.log('[build-release] sha256', sha256);
