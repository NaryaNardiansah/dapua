# PowerShell script untuk menjalankan Laravel server dengan host custom
# Pastikan Anda menjalankan PowerShell sebagai Administrator

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Laravel Development Server" -ForegroundColor Cyan
Write-Host "URL: http://localhost.dapursakura.com:8000" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cek apakah host sudah ada di hosts file
$hostsPath = "$env:SystemRoot\System32\drivers\etc\hosts"
$hostEntry = "127.0.0.1 localhost.dapursakura.com"

if (Test-Path $hostsPath) {
    $hostsContent = Get-Content $hostsPath -Raw
    if ($hostsContent -notmatch "localhost\.dapursakura\.com") {
        Write-Host "Host belum ditambahkan ke file hosts." -ForegroundColor Yellow
        Write-Host "Menambahkan entry ke file hosts..." -ForegroundColor Yellow
        
        try {
            Add-Content -Path $hostsPath -Value "`n$hostEntry" -ErrorAction Stop
            Write-Host "Host berhasil ditambahkan!" -ForegroundColor Green
        } catch {
            Write-Host "Gagal menambahkan host. Pastikan PowerShell dijalankan sebagai Administrator." -ForegroundColor Red
            Write-Host "Atau tambahkan manual: $hostEntry" -ForegroundColor Yellow
        }
    } else {
        Write-Host "Host sudah ada di file hosts." -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "Menjalankan server..." -ForegroundColor Green
Write-Host ""

php artisan serve --host=localhost.dapursakura.com --port=8000

