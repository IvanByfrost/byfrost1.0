# Script para iniciar el servidor PHP de SunObra
Write-Host "Iniciando servidor PHP para SunObra..." -ForegroundColor Green
Write-Host ""
Write-Host "Puerto: 8000" -ForegroundColor Yellow
Write-Host "URL: http://localhost:8000" -ForegroundColor Yellow
Write-Host ""
Write-Host "Presiona Ctrl+C para detener el servidor" -ForegroundColor Cyan
Write-Host ""

# Rutas comunes de PHP en XAMPP
$phpPaths = @(
    "C:\xampp\php\php.exe",
    "F:\xampp\php\php.exe",
    "C:\wamp\bin\php\php8.1.0\php.exe",
    "C:\wamp64\bin\php\php8.1.0\php.exe",
    "php.exe"
)

$phpFound = $false

foreach ($path in $phpPaths) {
    if (Test-Path $path) {
        Write-Host "Usando PHP en: $path" -ForegroundColor Green
        & $path -S localhost:8000
        $phpFound = $true
        break
    }
}

if (-not $phpFound) {
    Write-Host "Error: No se pudo encontrar PHP" -ForegroundColor Red
    Write-Host "Por favor, asegurate de que XAMPP esté instalado y PHP esté en el PATH" -ForegroundColor Red
    Read-Host "Presiona Enter para continuar"
} 