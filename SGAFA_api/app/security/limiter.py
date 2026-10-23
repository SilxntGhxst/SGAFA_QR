import os
from fastapi import Request
from slowapi import Limiter


def get_real_client_ip(request: Request) -> str:
    """
    Obtiene la IP real del cliente detrás de Cloudflare, proxies inversos (Nginx/Caddy)
    o directamente en desarrollo local.
    """
    # 1. Cabecera oficial de Cloudflare: IP real del visitante garantizada
    cf_ip = request.headers.get("cf-connecting-ip")
    if cf_ip:
        return cf_ip.strip()

    # 2. True-Client-IP (Cloudflare Enterprise u otros CDNs)
    true_client_ip = request.headers.get("true-client-ip")
    if true_client_ip:
        return true_client_ip.strip()

    # 3. Cabecera estándar de proxy X-Forwarded-For (la primera IP es el cliente original)
    forwarded = request.headers.get("x-forwarded-for")
    if forwarded:
        return forwarded.split(",")[0].strip()

    # 4. Fallback a la conexión directa de Uvicorn (desarrollo local / red interna)
    if request.client and request.client.host:
        return request.client.host

    return "127.0.0.1"


# Límite por defecto por ruta (por ejemplo: 60 peticiones/minuto por endpoint)
DEFAULT_RATE_LIMIT = os.getenv("RATE_LIMIT_DEFAULT", "60/minute")

# Límite global por IP para toda la API combinada (ej: 120 peticiones/minuto en total)
APPLICATION_RATE_LIMIT = os.getenv("RATE_LIMIT_APPLICATION", "120/minute")

# Backend de almacenamiento ('memory://' por defecto, o 'redis://...' para multi-workers)
STORAGE_URL = os.getenv("RATE_LIMIT_STORAGE_URL", "memory://")

limiter = Limiter(
    key_func=get_real_client_ip,
    default_limits=[DEFAULT_RATE_LIMIT] if DEFAULT_RATE_LIMIT else [],
    application_limits=[APPLICATION_RATE_LIMIT] if APPLICATION_RATE_LIMIT else [],
    headers_enabled=True,  # Envía X-RateLimit-* en las respuestas para verificar que funciona
    storage_uri=STORAGE_URL,
)

