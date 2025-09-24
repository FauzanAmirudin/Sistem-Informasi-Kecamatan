@echo off
echo 🔧 Setting up storage for production...

REM Pastikan direktori storage ada
if not exist "storage\app\public" (
    echo 📁 Creating storage\app\public directory...
    mkdir storage\app\public
)

REM Hapus symlink lama jika ada
if exist "public\storage" (
    echo 🗑️ Removing old storage symlink...
    rmdir /s /q public\storage
)

REM Buat symlink baru
echo 🔗 Creating storage symlink...
php artisan storage:link

REM Pastikan direktori uploads juga ada
if not exist "public\uploads" (
    echo 📁 Creating public\uploads directory...
    mkdir public\uploads
)

echo ✅ Storage setup completed!
echo.
echo 📋 Next steps:
echo 1. Pastikan web server memiliki permission untuk membaca file di storage/
echo 2. Pastikan APP_URL di .env sudah benar
echo 3. Test upload dan download file
pause
