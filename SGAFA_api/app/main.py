from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.database.db import engine
from app.database import models
from app.routers import buzon, varios, activos, auditorias

# Crea todas las tablas en la BD si no existen
models.Base.metadata.create_all(bind=engine)

app = FastAPI(
    title="SGAFA QR - API",
    description="API para gestión de activos y buzón de discrepancias",
    version="3.0.0"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(varios.router)
app.include_router(buzon.router)
app.include_router(activos.router)
app.include_router(auditorias.router)
