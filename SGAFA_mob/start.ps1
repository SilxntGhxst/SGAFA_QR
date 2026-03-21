# 1. Escanear adaptadores de red y obtener la IP activa real de la computadora
$activeAdapter = Get-NetIPConfiguration | Where-Object { $_.IPv4DefaultGateway -ne $null -and $_.NetAdapter.Status -ne "Disconnected" }
$ip = $activeAdapter.IPv4Address.IPAddress

# 2. Inyectar la IP detectada en el entorno temporal de la terminal
$env:REACT_NATIVE_PACKAGER_HOSTNAME = $ip
$env:EXPO_PUBLIC_API_IP = $ip

# 3. Mostrar un monitor visual limpio en la consola
Write-Host "===================================================" -ForegroundColor Cyan
Write-Host " Quirófano Móvil Iniciando..." -ForegroundColor Cyan
Write-Host " IP Detectada e Inyectada a Expo: $ip" -ForegroundColor Green
Write-Host "===================================================" -ForegroundColor Cyan

# 4. Levantar el contenedor leyendo la nueva variable
docker compose up