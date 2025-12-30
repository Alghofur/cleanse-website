# Cleanse Website - Setup untuk GitHub

## 📋 Checklist Sebelum Upload ke GitHub

### ✅ File/Folder yang BOLEH diupload:
- ✅ `app/` - source code aplikasi
- ✅ `config/constant.php` - public constants
- ✅ `config/database.php.example` - template configuration
- ✅ `public/` - public assets (kecuali uploads/)
- ✅ `tests/` - unit tests
- ✅ `sql/cleanse_db.sql` - database schema
- ✅ `assets/` - CSS, JavaScript, images
- ✅ `.gitignore` - file yang di-ignore
- ✅ `composer.json` - dependency list
- ✅ `README.md` - documentation
- ✅ `phpunit.xml` - test configuration

### ❌ File/Folder yang JANGAN diupload:
```
vendor/                          # Auto-generated dari composer install
composer.lock                    # Auto-generated
config/database.php              # SENSITIF! Berisi password database
.env, .env.local                 # SENSITIF! Environment variables
public/uploads/                  # User uploads (dynamic files)
sessions/                        # Session files
logs/, *.log                     # Log files
coverage/                        # Test coverage reports
.idea/, .vscode/                 # IDE settings
node_modules/                    # Jika ada Node.js dependencies
```

## 🔐 File Sensitif yang Perlu Diproteksi

### 1. **config/database.php** (PALING PENTING!)
Berisi:
- Database host, name
- Database username & password
- PDO configuration

**Solution:**
- Upload `config/database.php.example` ke GitHub
- Dev/Team members copy ke `config/database.php` dan edit dengan data lokal
- `config/database.php` di-ignore oleh `.gitignore`

### 2. **.env files** (Jika ada)
Berisi:
- API keys
- Database credentials
- App settings sensitif

**Solution:**
- Gunakan `.env.example` template
- Setiap developer membuat `.env` lokal mereka
- `.env` di-ignore oleh `.gitignore`

### 3. **Upload Folders**
- `public/uploads/` atau folder upload apapun
- Ini generated files, bukan source code
- Size bisa membesar tanpa terkontrol

## 📦 Proses Setup untuk Developer Baru

Setelah clone repository:

```bash
# 1. Install dependencies
composer install

# 2. Setup database configuration
cp config/database.php.example config/database.php
# Edit config/database.php dengan database lokal Anda

# 3. Create upload directories
mkdir -p public/uploads
mkdir -p sessions
mkdir -p logs

# 4. Setup database
mysql -u root < sql/cleanse_db.sql

# 5. Run tests
vendor/bin/phpunit
```

## 🚨 Best Practices untuk GitHub

### Jangan Pernah Commit:
- ❌ Password dalam source code
- ❌ API keys dalam source code
- ❌ Private credentials
- ❌ Generated files (vendor/, node_modules/)
- ❌ Build artifacts
- ❌ Local configuration files
- ❌ Log files
- ❌ IDE/editor personal settings

### Selalu Gunakan:
- ✅ `.gitignore` untuk exclude files
- ✅ `.example` files untuk templates
- ✅ Environment variables untuk secrets
- ✅ `.env` files untuk local config
- ✅ Comments di README untuk setup instructions

## 📝 Contoh Workflow

### Local Development:
```
Anda (Developer)
    ↓
Edit files & test locally
    ↓
Commit & push ke GitHub (hanya source code)
    ↓
.gitignore ensures:
  - config/database.php tidak ter-upload
  - vendor/ tidak ter-upload
  - uploads/ tidak ter-upload
  - logs/ tidak ter-upload
```

### Production:
```
Pull dari GitHub
    ↓
Copy config/database.php.example → config/database.php
    ↓
Edit dengan production credentials
    ↓
Run: composer install
    ↓
Create directories: uploads/, logs/, sessions/
```

## 🔍 Verify .gitignore

Pastikan files sensitif tidak akan ter-track:

```bash
# Check apa yang akan di-track oleh git
git status

# Check apa yang di-ignore
git ls-files --others --exclude-standard

# Jika sudah di-commit (mistake), hapus:
git rm --cached config/database.php
git commit -m "Remove database.php from tracking"
```

## 📚 Resources

- [GitHub .gitignore Documentation](https://help.github.com/en/articles/ignoring-files)
- [Collection of .gitignore templates](https://github.com/github/gitignore)
- [PHP .gitignore template](https://raw.githubusercontent.com/github/gitignore/main/PHP.gitignore)

---

**Kesimpulan:** Upload `.gitignore` dan pastikan file sensitif tercantum di dalamnya. Setiap developer akan setup konfigurasi lokal mereka sendiri.
