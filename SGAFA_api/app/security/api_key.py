import os
from typing import Optional
from fastapi import Security, HTTPException, status
from fastapi.security import APIKeyHeader, APIKeyQuery

# Nombre del header y query param
API_KEY_NAME = "X-API-Key"
API_KEY_QUERY_NAME = "api_key"

# Clave esperada configurada en entorno
API_KEY = os.getenv("API_KEY", "sgafa_qr_api_key_2024_secure")

# Opción para requerir o no API key (por defecto activado)
API_KEY_REQUIRED = os.getenv("API_KEY_REQUIRED", "true").lower() in ("true", "1", "t", "yes")

api_key_header_scheme = APIKeyHeader(name=API_KEY_NAME, auto_error=False)
api_key_query_scheme = APIKeyQuery(name=API_KEY_QUERY_NAME, auto_error=False)


async def verify_api_key(
    key_header: Optional[str] = Security(api_key_header_scheme),
    key_query: Optional[str] = Security(api_key_query_scheme),
) -> Optional[str]:
    """
    Verifica que la petición incluya un API Key válido.
    Acepta la clave mediante el encabezado 'X-API-Key' o el parámetro de consulta '?api_key='.
    """
    if not API_KEY_REQUIRED:
        return None

    provided_key = key_header or key_query
    if not provided_key:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="API Key requerida. Proporcione la cabecera 'X-API-Key' o el parámetro '?api_key='.",
            headers={"WWW-Authenticate": "ApiKey"},
        )

    if provided_key != API_KEY:
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="API Key inválida o no autorizada.",
        )

    return provided_key
