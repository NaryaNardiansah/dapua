# Cara Menjalankan Server Laravel dengan Custom Host

## Opsi 1: Menggunakan File Batch (serve.bat)
Double-click file `serve.bat` atau jalankan di CMD:
```cmd
serve.bat
```

## Opsi 2: Menggunakan PowerShell Script (serve.ps1)
Jalankan di PowerShell (sebagai Administrator untuk auto-setup hosts):
```powershell
.\serve.ps1
```

## Opsi 3: Manual Command
Jalankan langsung di CMD/PowerShell:
```cmd
php artisan serve --host=localhost.dapursakura.com --port=8000
```

## Setup Hosts File (Penting!)

Agar `localhost.dapursakura.com` dapat diakses, Anda perlu menambahkan entry ke file hosts Windows:

1. Buka Notepad sebagai Administrator
2. Buka file: `C:\Windows\System32\drivers\etc\hosts`
3. Tambahkan baris berikut:
   ```
   127.0.0.1 localhost.dapursakura.com
   ```
4. Simpan file

Atau jalankan PowerShell sebagai Administrator dan eksekusi:
```powershell
Add-Content -Path "$env:SystemRoot\System32\drivers\etc\hosts" -Value "`n127.0.0.1 localhost.dapursakura.com"
```

## Catatan
- Pastikan port 8000 tidak digunakan aplikasi lain
- Jika port 8000 sudah digunakan, ubah port di command: `--port=8001`
- Server akan berjalan di: http://localhost.dapursakura.com:8000

