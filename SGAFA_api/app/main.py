from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel, Field
from typing import Optional, List
from datetime import datetime

app = FastAPI(
    title="SGAFA QR - Buzón de Discrepancias",
    description="API para gestionar reportes de incidencias de activos",
    version="1.0.0"
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

buzon_db: List[dict] = [
    {
        "id": 1,
        "codigo": "COD-001",
        "activo": "Laptop Lenovo",
        "tipo": "Activo Faltante",
        "reportado_por": "Kevin Aguilar",
        "area": "Laboratorio 3",
        "estado": "Pendiente",
        "descripcion": "Laptop no encontrada en inventario",
        "foto": None,
        "created_at": "2026-02-11T10:00:00",
    },
    {
        "id": 2,
        "codigo": "COD-002",
        "activo": "Proyector Epson",
        "tipo": "Reporte de Daño",
        "reportado_por": "Ana Ruiz",
        "area": "Aula 5",
        "estado": "En revisión",
        "descripcion": "Proyector con pantalla dañada",
        "foto": None,
        "created_at": "2026-02-10T09:30:00",
    },
    {
        "id": 3,
        "codigo": "COD-003",
        "activo": "Monitor Dell 27\"",
        "tipo": "Activo Fantasma",
        "reportado_por": "Carlos Gómez",
        "area": "Oficina Administrativa",
        "estado": "Resuelto",
        "descripcion": "Monitor sin etiqueta QR",
        "foto": None,
        "created_at": "2026-02-09T08:00:00",
    },
]

contador_id = 4  

class BuzonCreate(BaseModel):
    activo:        str  = Field(..., example="Laptop HP")
    tipo:          str  = Field(..., example="Activo Fantasma")  
    reportado_por: str  = Field(..., example="Santiago Meneses")
    area:          str  = Field(..., example="Laboratorio 3")
    descripcion:   Optional[str] = Field(None, example="Activo sin etiqueta QR")
    foto:          Optional[str] = Field(None, example="base64_o_url_de_foto")

class BuzonUpdate(BaseModel):
    activo:        Optional[str] = None
    tipo:          Optional[str] = None
    reportado_por: Optional[str] = None
    area:          Optional[str] = None
    estado:        Optional[str] = None  
    descripcion:   Optional[str] = None
    foto:          Optional[str] = None


@app.get("/", tags=["Inicio"])
async def bienvenida():
    return {"mensaje": "SGAFA QR - API Buzón de Discrepancias activa"}


# GET todos
@app.get("/api/buzon", tags=["Buzón"])
async def listar_buzon():
    return {
        "success": True,
        "total": len(buzon_db),
        "data": buzon_db
    }


# GET por ID
@app.get("/api/buzon/{id}", tags=["Buzón"])
async def obtener_buzon(id: int):
    for registro in buzon_db:
        if registro["id"] == id:
            return {"success": True, "data": registro}
    raise HTTPException(status_code=404, detail="Reporte no encontrado.")


# POST - Insertar nuevo reporte (usado desde la app móvil)
@app.post("/api/buzon", tags=["Buzón"], status_code=201)
async def crear_buzon(payload: BuzonCreate):
    global contador_id

    nuevo = {
        "id":           contador_id,
        "codigo":       f"COD-{str(contador_id).zfill(3)}",
        "activo":       payload.activo,
        "tipo":         payload.tipo,
        "reportado_por": payload.reportado_por,
        "area":         payload.area,
        "estado":       "Pendiente",
        "descripcion":  payload.descripcion,
        "foto":         payload.foto,
        "created_at":   datetime.now().isoformat(),
    }

    buzon_db.append(nuevo)
    contador_id += 1

    return {
        "success": True,
        "message": "Reporte creado exitosamente.",
        "data": nuevo
    }


# PUT - Editar reporte
@app.put("/api/buzon/{id}", tags=["Buzón"])
async def actualizar_buzon(id: int, payload: BuzonUpdate):
    for registro in buzon_db:
        if registro["id"] == id:
            campos = payload.dict(exclude_unset=True)
            registro.update(campos)
            return {
                "success": True,
                "message": "Reporte actualizado exitosamente.",
                "data": registro
            }
    raise HTTPException(status_code=404, detail="Reporte no encontrado.")


# DELETE - Eliminar reporte
@app.delete("/api/buzon/{id}", tags=["Buzón"])
async def eliminar_buzon(id: int):
    for i, registro in enumerate(buzon_db):
        if registro["id"] == id:
            eliminado = buzon_db.pop(i)
            return {
                "success": True,
                "message": "Reporte eliminado exitosamente.",
                "data": eliminado
            }
    raise HTTPException(status_code=404, detail="Reporte no encontrado.")
