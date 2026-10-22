import os
from slowapi import Limiter
from slowapi.util import get_remote_address

# Límite por defecto para peticiones en toda la aplicación
DEFAULT_RATE_LIMIT = os.getenv("RATE_LIMIT_DEFAULT", "120/minute")

limiter = Limiter(
    key_func=get_remote_address,
    default_limits=[DEFAULT_RATE_LIMIT],
    headers_enabled=True,
)
