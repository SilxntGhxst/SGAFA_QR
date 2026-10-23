from fastapi import FastAPI, Request, Depends
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from sqlalchemy import text
from slowapi.errors import RateLimitExceeded
from slowapi.middleware import SlowAPIMiddleware

from app.database.db import engine
from app.database import models
from app.routers import buzon, varios, activos, auditorias, auth, usuarios, reportes, ajustes
from app.security.api_key import verify_api_key
from app.security.limiter import limiter

# Crea todas las tablas en la BD si no existen
models.Base.metadata.create_all(bind=engine)
with engine.begin() as connection:
    connection.execute(text(
        "ALTER TABLE buzon ADD COLUMN IF NOT EXISTS "
        "usuario_id UUID REFERENCES usuarios(id) ON DELETE SET NULL"
    ))

app = FastAPI(
    title="SGAFA QR - API",
    description="API para gestión de activos, autenticación JWT, API Key, control BOLA y buzón de discrepancias",
    version="4.1.0"
)

# Integración del Rate Limiter
app.state.limiter = limiter
app.add_middleware(SlowAPIMiddleware)


@app.exception_handler(RateLimitExceeded)
async def rate_limit_handler(request: Request, exc: RateLimitExceeded):
    response = JSONResponse(
        status_code=429,
        content={
            "success": False,
            "detail": f"Límite de peticiones excedido: {exc.detail}. Intenta de nuevo más tarde."
        }
    )
    if hasattr(request.state, "view_rate_limit"):
        response = limiter._inject_headers(response, request.state.view_rate_limit)
    return response

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Dependencia de seguridad global para rutas de la API
api_deps = [Depends(verify_api_key)]

# Rutas públicas / de bienvenida
app.include_router(varios.router)

# Rutas de autenticación (login, registro, recuperación)
app.include_router(auth.router)
app.include_router(usuarios.router, dependencies=api_deps)
app.include_router(buzon.router, dependencies=api_deps)
app.include_router(activos.router, dependencies=api_deps)
app.include_router(auditorias.router, dependencies=api_deps)
app.include_router(reportes.router, dependencies=api_deps)
app.include_router(ajustes.router, dependencies=api_deps)
