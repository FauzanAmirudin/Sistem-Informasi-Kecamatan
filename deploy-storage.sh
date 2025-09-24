#!/bin/bash

# Script untuk memastikan storage link berfungsi dengan benar saat deploy
# Jalankan script ini setelah deploy aplikasi

echo "🔧 Setting up storage for production..."

# Pastikan direktori storage ada
if [ ! -d "storage/app/public" ]; then
    echo "📁 Creating storage/app/public directory..."
    mkdir -p storage/app/public
fi

# Hapus symlink lama jika ada
if [ -L "public/storage" ]; then
    echo "🗑️ Removing old storage symlink..."
    rm public/storage
fi

# Buat symlink baru
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Set permission yang benar
echo "🔐 Setting correct permissions..."
chmod -R 755 storage/
chmod -R 755 public/storage/

# Pastikan direktori uploads juga ada
if [ ! -d "public/uploads" ]; then
    echo "📁 Creating public/uploads directory..."
    mkdir -p public/uploads
    chmod 755 public/uploads
fi

echo "✅ Storage setup completed!"
echo ""
echo "📋 Next steps:"
echo "1. Pastikan web server memiliki permission untuk membaca file di storage/"
echo "2. Pastikan APP_URL di .env sudah benar"
echo "3. Test upload dan download file"
