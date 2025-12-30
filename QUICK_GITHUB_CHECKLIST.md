# 📌 Quick Reference - File GitHub Upload

## Ringkas: File Mana yang BOLEH/JANGAN Diupload

| File/Folder | Status | Alasan |
|---|---|---|
| `app/` | ✅ UPLOAD | Source code aplikasi |
| `config/constant.php` | ✅ UPLOAD | Public constants |
| `config/database.php` | ❌ JANGAN | **SENSITIF - Password database!** |
| `config/database.php.example` | ✅ UPLOAD | Template untuk developers |
| `public/` (except uploads/) | ✅ UPLOAD | Public assets (CSS, JS, images) |
| `public/uploads/` | ❌ JANGAN | User-generated uploads |
| `tests/` | ✅ UPLOAD | Unit tests & test files |
| `sql/cleanse_db.sql` | ✅ UPLOAD | Database schema |
| `assets/` | ✅ UPLOAD | Static assets |
| `vendor/` | ❌ JANGAN | Auto-generated dari composer |
| `composer.json` | ✅ UPLOAD | Dependency list |
| `composer.lock` | ❌ JANGAN | Auto-generated (optional) |
| `.gitignore` | ✅ UPLOAD | Rules untuk GitHub |
| `.env*` | ❌ JANGAN | Environment variables (SENSITIF) |
| `logs/`, `*.log` | ❌ JANGAN | Log files |
| `sessions/` | ❌ JANGAN | Session files |
| `.idea/`, `.vscode/` | ❌ JANGAN | IDE settings |
| `coverage/` | ❌ JANGAN | Test coverage reports |
| `node_modules/` | ❌ JANGAN | JS dependencies |
| `debug.log` | ❌ JANGAN | Debug files |

## 🔥 Paling Penting (JANGAN LUPA!)

### TOP PRIORITY - JANGAN UPLOAD:
1. **config/database.php** ← Password database!
2. **.env files** ← API keys & secrets
3. **vendor/** ← Terlalu besar, di-generate
4. **public/uploads/** ← User files (dynamic)
5. **logs/**, **sessions/** ← Generated files

### WAJIB UPLOAD (untuk setup):
1. **config/database.php.example** ← Template
2. **sql/cleanse_db.sql** ← Database schema
3. **.gitignore** ← Ignore rules
4. **GITHUB_SETUP.md** ← Setup instructions

## ⚡ Cepat Setup untuk Team

```bash
# Dev/Team member baru:
git clone <repo>
cd cleanse-website
composer install
cp config/database.php.example config/database.php
# Edit config/database.php dengan database lokal
mkdir -p public/uploads sessions logs
mysql -u root < sql/cleanse_db.sql
vendor/bin/phpunit
```

---

**Files sudah siap:** ✅ `.gitignore` ✅ `config/database.php.example` ✅ `GITHUB_SETUP.md`
