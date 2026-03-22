from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
from typing import Optional, List
from datetime import datetime
from app.routers import buzon
from app.models.BuzonModel import BuzonCreate, BuzonUpdate
from app.database.dbImaginary import buzon_db
from fastapi.middleware.cors import CORSMiddleware

from app.routers import varios
from app.routers import activos

app = FastAPI(
    title="SGAFA QR - Buzón de Discrepancias",
    description="API para gestionar reportes de incidencias de activos",
    version="2.0.3",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=[
        "http://localhost",      # Laravel web en desarrollo
        "http://localhost:80",
    ],
    allow_credentials=True,
    allow_methods=["GET", "POST", "PUT", "DELETE"],  # solo los que usas
    allow_headers=["Content-Type"],
)

app.include_router(varios.router)
app.include_router(buzon.router)
app.include_router(activos.router)
