import fs from 'node:fs';
import path from 'node:path';

export function readJson(filePath) {
  return JSON.parse(fs.readFileSync(filePath, 'utf8'));
}

export function ensureDir(dir) {
  fs.mkdirSync(dir, { recursive: true });
}

export function rimraf(target) {
  if (!fs.existsSync(target)) {
    return;
  }
  fs.rmSync(target, { recursive: true, force: true });
}

function shouldSkip(relPath, excludePatterns) {
  const normalized = relPath.split(path.sep).join('/');
  for (const pattern of excludePatterns) {
    const p = pattern.split(path.sep).join('/');
    if (normalized === p || normalized.startsWith(p + '/')) {
      return true;
    }
  }
  return false;
}

export function copyTree(srcRoot, destRoot, excludePatterns = []) {
  ensureDir(destRoot);
  for (const entry of fs.readdirSync(srcRoot, { withFileTypes: true })) {
    const srcPath = path.join(srcRoot, entry.name);
    const rel = path.relative(srcRoot, srcPath);
    if (shouldSkip(rel, excludePatterns)) {
      continue;
    }
    const destPath = path.join(destRoot, entry.name);
    if (entry.isDirectory()) {
      copyTree(srcPath, destPath, excludePatterns);
    } else if (entry.isFile()) {
      ensureDir(path.dirname(destPath));
      fs.copyFileSync(srcPath, destPath);
    }
  }
}
