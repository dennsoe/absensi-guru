# 🚀 PANDUAN PUSH KE GITHUB

Ikuti langkah-langkah berikut untuk push project ke GitHub:

## 1️⃣ Persiapan

### Install Git (jika belum)
```bash
# macOS
brew install git

# Atau download dari https://git-scm.com/
```

### Konfigurasi Git (jika belum)
```bash
git config --global user.name "Nama Anda"
git config --global user.email "email@anda.com"
```

## 2️⃣ Inisialisasi Git Repository

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/absen-guru

# Initialize git
git init

# Add all files
git add .

# First commit
git commit -m "🎉 Initial commit: PWA Sistem Absensi Guru v3.5

- ✅ Progressive Web App implementation
- ✅ Mobile-first responsive design
- ✅ Service Worker & offline support
- ✅ Push notifications
- ✅ Flexible routing & auto base URL detection
- ✅ GPS validation & QR Code attendance
- ✅ Complete settings management
- ✅ Multi-role access control
- ✅ Deployment ready (Shared hosting, VPS, Cloud)"
```

## 3️⃣ Buat Repository di GitHub

1. Buka https://github.com/
2. Login ke akun Anda
3. Klik tombol **"New"** atau **"+"** → **"New repository"**
4. Isi form:
   - **Repository name:** `absensi-guru-pwa` (atau nama lain)
   - **Description:** "Sistem Absensi Guru - Progressive Web App dengan GPS & QR Code"
   - **Visibility:** 
     - ✅ **Public** (jika ingin dibagikan ke publik)
     - ✅ **Private** (jika hanya untuk internal)
   - ❌ **JANGAN** centang "Add README" (sudah ada)
   - ❌ **JANGAN** centang "Add .gitignore" (sudah ada)
   - ❌ **JANGAN** centang "Choose a license" (sudah ada)
5. Klik **"Create repository"**

## 4️⃣ Connect ke GitHub Repository

Setelah repo dibuat, GitHub akan tampilkan instruksi. Gunakan yang ini:

```bash
# Add remote origin
git remote add origin https://github.com/USERNAME/absensi-guru-pwa.git

# Atau jika pakai SSH:
git remote add origin git@github.com:USERNAME/absensi-guru-pwa.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

**Ganti `USERNAME` dengan username GitHub Anda!**

## 5️⃣ Verifikasi

```bash
# Check remote
git remote -v

# Check status
git status

# Check commit history
git log --oneline
```

Buka https://github.com/USERNAME/absensi-guru-pwa untuk melihat repository Anda!

## 6️⃣ Update README.md di GitHub (Optional)

Edit `README.md` dan ganti:
```markdown
git clone https://github.com/yourusername/absen-guru.git
```

Menjadi:
```markdown
git clone https://github.com/USERNAME/absensi-guru-pwa.git
```

Lalu commit dan push:
```bash
git add README.md
git commit -m "📝 Update repository URL in README"
git push
```

## 7️⃣ GitHub Pages untuk Demo (Optional)

Jika ingin deploy demo ke GitHub Pages:

```bash
# Create gh-pages branch
git checkout -b gh-pages

# Push to gh-pages
git push -u origin gh-pages

# Back to main
git checkout main
```

Aktifkan di: **Settings** → **Pages** → **Source:** `gh-pages`

⚠️ **Note:** PHP tidak bisa jalan di GitHub Pages (static only). Untuk demo full, deploy ke Railway/Heroku.

## 8️⃣ Future Updates

Setiap ada perubahan:

```bash
# Check changes
git status

# Add changes
git add .

# Commit with message
git commit -m "✨ Add new feature: [description]"

# Push to GitHub
git push
```

### Commit Message Guidelines:
```
✨ feat: New feature
🐛 fix: Bug fix
📝 docs: Documentation
💄 style: UI/UX changes
♻️ refactor: Code refactoring
⚡ perf: Performance improvement
✅ test: Tests
🔧 chore: Configuration
```

## 9️⃣ Collaboration

### Clone repository (untuk developer lain):
```bash
git clone https://github.com/USERNAME/absensi-guru-pwa.git
cd absensi-guru-pwa

# Install dependencies
composer install

# Setup environment
cp .env.example .env
cp config/database.php.example config/database.php

# Edit .env dan config/database.php
# Setup database
mysql -u root -p < database/absensi_guru.sql

# Generate VAPID keys
composer generate-vapid
```

### Branching Strategy:
```bash
# Create feature branch
git checkout -b feature/nama-fitur

# Work on feature...
git add .
git commit -m "✨ Add feature: nama fitur"

# Push branch
git push origin feature/nama-fitur

# Create Pull Request di GitHub
```

## 🔒 Security Checklist

Pastikan file-file ini **TIDAK** ter-commit:

- ❌ `.env`
- ❌ `config/database.php` (jika ada password real)
- ❌ `config/config.php` (jika ada secret keys real)
- ❌ `vapid_keys.json`
- ❌ `public/uploads/*` (file user)
- ❌ `logs/*` (log files)
- ❌ `backup/*` (backup database)

Check dengan:
```bash
git status --ignored
```

## 📦 Repository Structure di GitHub

```
absensi-guru-pwa/
├── .github/          # GitHub Actions (future)
├── app/              # Application code
├── assets/           # CSS, JS, Images
├── config/           # Config examples
├── database/         # SQL files
├── public/           # Public directory
├── .env.example      # Environment example
├── .gitignore        # Git ignore rules
├── composer.json     # PHP dependencies
├── DEPLOYMENT.md     # Deployment guide
├── LICENSE           # MIT License
├── README.md         # Main documentation
└── SKEMA_APLIKASI.md # Full documentation
```

## 🎯 Next Steps

1. ✅ Push ke GitHub
2. ✅ Setup CI/CD dengan GitHub Actions (optional)
3. ✅ Deploy ke Railway/Heroku untuk demo
4. ✅ Invite collaborators
5. ✅ Setup branch protection rules
6. ✅ Add project to GitHub Profile

## 💡 Tips

- Gunakan **GitHub Desktop** jika tidak familiar dengan command line
- Setup **SSH keys** untuk push tanpa password: https://docs.github.com/en/authentication/connecting-to-github-with-ssh
- Gunakan **GitHub Issues** untuk bug tracking
- Gunakan **GitHub Projects** untuk project management
- Enable **GitHub Discussions** untuk Q&A

---

**Selamat! Repository Anda siap di GitHub! 🎉**
