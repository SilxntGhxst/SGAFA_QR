from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
from typing import Optional, List
from datetime import datetime
from app.routers import buzon
from app.models.BuzonModel import BuzonCreate, BuzonUpdate
from app.database.dbImaginary import buzon_db
from app.routers import varios

app = FastAPI(
    title="SGAFA QR - Buzón de Discrepancias",
    description="API para gestionar reportes de incidencias de activos",
    version="2.0.3"
)

app.include_router(varios.router)
app.include_router(buzon.router)

